<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use App\Models\VipMemberships;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\ApiService;
use App\Models\Registration;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Illuminate\Support\Facades\Storage;
use DateTimeImmutable;

use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Mail\OtpMailForgotPassword;


use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Email\Email;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Security\Security;
use App\Mail\RegisteredMail;
use App\Models\UserLoginLog;
use App\Mail\WelcomeMail;

use App\Mail\PasswordResetMail;

class LoginApiController extends Controller
{


    public function loginWithEmail(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where(function ($query) use ($credentials) {
            $query->where('email', $credentials['email']);
        })->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {

            $accessToken = $this->generateAccessToken($user->id);
            $refreshToken = $this->generateRefreshToken($user->id);
            $tokenExpiration = now()->addDay(1);

            $user->update([
                'verify_phone' => true,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_at' => $tokenExpiration,
                'last_logged_In' => now(),
            ]);

            UserLoginLog::create([
                'user_id' => $user->id,
                'last_logged_in' => now(),
            ]);
            $data = [
                "user_id" => $user->id,
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
            ];

            return ApiService::response(true, $data, 'Login Successful.');
        } else {

            return ApiService::error(false, 'Invalid email/phone or password');
        }
    }

    private function generateAccessToken($userId)
    {
        $signer = new Sha256();
        $privateKey = '7A95895F6CE7FC2672AA68AA814C4FED'; // Replace with your own private key

        $config = Configuration::forSymmetricSigner($signer, InMemory::plainText($privateKey));

        $builder = $config->builder()
            ->issuedBy('turkey-creek-app') // Replace with your own issuer
            ->permittedFor('mobile-app') // Replace with your own audience
            ->identifiedBy($userId) // Set a unique identifier for the token
            ->expiresAt(new DateTimeImmutable('+1 hour')); // Set the expiration time to 1 hour from now

        $token = $builder->getToken($signer, $config->signingKey());
        return $token->toString();
    }

    public function generateRefreshToken($userId)
    {
        // dd($userId);
        $signer = new Sha256();
        $privateKey = '7A95895F6CE7FC2672AA68AA814C4FED'; // Replace with your own private key
        $config = Configuration::forSymmetricSigner($signer, InMemory::plainText($privateKey));

        // $userId = Auth::id();
        // $user = Member::find($userId);
        $builder = $config->builder()
            ->issuedBy('turkey-creek-mobile-app') // Replace with your own issuer
            ->permittedFor('mobile-app') // Replace with your own audience
            ->identifiedBy($userId) // Set the token identifier to the user's ID
            ->expiresAt(new DateTimeImmutable('+30 days')); // Set the expiration time to 30 days from now

        $token = $builder->getToken($signer, $config->signingKey());

        return $token->toString();
    }

    // public function registerCustomer(Request $request)
    // {

    //     try {
    //         $request->validate([
    //             'name' => 'required|string',
    //             'email' => 'required|email|unique:users,email',
    //             'password' => 'required|string|min:6',
    //         ]);
    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'mobile_no' => $request->mobile_no,
    //             'password' => Hash::make($request->password),
    //             'confirm_password' => $request->confirm_password,
    //             'address' => $request->address,
    //             'zip_code' => $request->zip_code
    //         ]);

    //         $accessToken = $this->generateAccessToken($user->id);
    //         $refreshToken = $this->generateRefreshToken($user->id);

    //         $user->update([
    //             'verify_phone' => true,
    //             'access_token' => $accessToken,
    //             'refresh_token' => $refreshToken,
    //             'expires_at' => now()->addDay(1),
    //         ]);

    //         // Prepare response data
    //         $data = [
    //             "user_id" => $user->id,
    //             "access_token" => $accessToken,
    //             "refresh_token" => $refreshToken,
    //         ];

    //         // Return success response
    //         return ApiService::response(true, $data, 'Registration Successful.');
    //     } catch (\Exception $e) {
    //         // If an exception occurs, return error response
    //         return ApiService::response(false, null, $e->getMessage());
    //     }
    // }


    public function registerCustomer(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'password' => Hash::make($request->password),
                'confirm_password' => $request->confirm_password,
                'address' => $request->address,
                'zip_code' => $request->zip_code,
                'status' => $request->status,
                'payment_status' => $request->payment_status
            ]);

            $accessToken = $this->generateAccessToken($user->id);
            $refreshToken = $this->generateRefreshToken($user->id);

            $user->update([
                'verify_phone' => true,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_at' => now()->addDay(1),
            ]);

            // Prepare response data
            $data = [
                "user_id" => $user->id,
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
                "status" =>$user->status,
                "payment_status" =>$user->payment_status

            ];

            ///////send Email To Admin ////////////
            $admin_email = Setting::get();
            foreach ($admin_email as $admin_mail) {
              Mail::to($admin_mail->email)->send(new RegisteredMail($user));
            }

            ////////////////// Welcome Mail to user ///////////////
            Mail::to('yrabadia99@gmail.com')->send(new WelcomeMail($user));

            return ApiService::response(true, $data, 'Registration Successful.');
        } catch (\Exception $e) {
            return ApiService::response(false, null, $e->getMessage());
        }
    }
    public function updateUserInfo(Request $request)
    {
        try {
            $user_id = $request->auth_user_id;
            $user = User::find($user_id);
            if (!$user) {
                return ApiService::error(false, 'User not found');
            }

            $data = $request->all();
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            unset($data['auth_user_id']);
            if ($request->image) {
                $imageData = base64_decode($data['image']);
                $filename = 'avatar_' . time() . '.png';
                Storage::disk('public')->put($filename, $imageData);
                $data['image'] = $filename; // Store the filename in the database
            } else {
                unset($data['image']);
            }

            $user->update($data);

            $updated_user = User::where('id', $user_id)->get();

            $users = $updated_user->map(function ($user) {
                // $user->user_id = $user->user_id ?? 0;
                $user->name = $user->name ?? '';
                $user->email = $user->email ?? '';
                $user->password = $user->password ?? '';
                $user->remember_token = $user->remember_token ?? '';
                $user->created_at = $user->created_at ?? '';
                $user->updated_at = $user->updated_at ?? '';
                $user->stripe_id = $user->stripe_id ?? '';
                $user->access_token = $user->access_token ?? '';
                $user->refresh_token = $user->refresh_token ?? '';
                $user->expires_at = $user->expires_at ?? '';
                $user->verify_phone = $user->verify_phone ?? 0;
                $user->date_of_birth = $user->date_of_birth ?? '';
                $user->gender = $user->gender ?? '';
                $user->image = $user->image ? asset('storage/' . $user->image) : '';

                return $user;
            });

            return ApiService::response(true, ['user' => $users], 'User information updated successfully');
        } catch (\Exception $e) {
            return ApiService::error(false, 'Error updating user information', $e->getMessage());
        }
    }


    public function userInfo(Request $request)
    {
        try {
            $userId = $request->auth_user_id;

            $user = User::find($userId);

            $userInfo = User::where('id', $userId)->get();
            $users = $userInfo->map(function ($user) {
                // $user->user_id = $user->user_id ?? 0;
                $user->name = $user->name ?? '';
                $user->email = $user->email ?? '';
                $user->email_verified_at = $user->email_verified_at ?? '';
                $user->password = $user->password ?? '';
                $user->remember_token = $user->remember_token ?? '';
                $user->created_at = $user->created_at ?? '';
                $user->updated_at = $user->updated_at ?? '';
                $user->stripe_id = $user->stripe_id ?? '';
                $user->pm_type = $user->pm_type ?? '';
                $user->pm_last_four = $user->pm_last_four ?? '';
                $user->trial_ends_at = $user->trial_ends_at ?? '';
                $user->verify_phone = $user->verify_phone ?? 0;
                $user->access_token = $user->access_token ?? '';
                $user->refresh_token = $user->refresh_token ?? '';
                $user->expires_at = $user->expires_at ?? '';
                $user->date_of_birth = $user->date_of_birth ?? '';
                $user->gender = $user->gender ?? '';
                $user->image = asset('storage/' . $user->image) ?? '';


                return $user;
            });


            return ApiService::response(true, ['member' => $users], 'Member data retrieved successfully');
        } catch (\Exception $e) {
            return ApiService::error(false, 'Unauthorized', 'Invalid token');
        }

    }


    public function forgotPasswordEmailOrPhone(Request $request)
    {
        $input = $request->only('email');
        $isEmail = filter_var($input['email'], FILTER_VALIDATE_EMAIL);

        $country_code = $request->country_code;
        $user = User::where(function ($query) use ($input, $isEmail) {
            if ($isEmail) {
                $query->where('email', $input['email']);
            }
        })->first();

        if ($user) {
            $otp = $this->generateOtp();
            if ($isEmail) {
                // Send OTP via email
                $this->sendOtpViaEmail($user->email, $otp);
            }
            $resetTokenExpiration = now()->addHour();

            $user->update([
                'reset_otp' => $otp,
                'reset_token_expires_at' => $resetTokenExpiration,
            ]);

            $data = [
                'user_id' => $user->id,
                'reset_otp' => $otp,
                'reset_token_expires_at' => $resetTokenExpiration,
            ];

            return ApiService::response(true, $data, 'OTP Sent Successfully');
        } else {
            return ApiService::error(false, 'No account found with the provided email/phone', 'Login Failed');
        }
    }

    private function generateOtp()
    {
        return strval(random_int(1000, 9999));
    }

    private function sendOtpViaEmail($email, $otp)
    {
        try {
            Mail::to($email)->send(new OtpMailForgotPassword($otp));
            return ApiService::response(true, [], 'OTP Sent Successfully.');
        } catch (\Exception $e) {
            return ApiService::error(false, 'OTP Sent Failed', 'Failed to send OTP via Email');
        }
    }

    public function verifyOtp(Request $request)
    {
        $input = $request->only('email', 'otp');
        $user = User::where('email', $input['email'])->first();
        if ($user) {
            $ValidTime = $user->reset_token_expires_at;

            if ($user->reset_otp === $input['otp']) {

                if (now()->lt($user->reset_token_expires_at)) {

                    return ApiService::response(true, [], 'OTP Verified Successfully');
                } else {
                    return ApiService::error(false, 'OTP has expired', 'Verification Failed');
                }
            } else {
                return ApiService::error(false, 'Invalid OTP', 'Verification Failed');
            }
        } else {
            return ApiService::error(false, 'No account found with the provided email', 'Verification Failed');
        }
    }

    public function resetPassword(Request $request)
    {
        $input = $request->only('email');
        $newPassword = $request->input('new_password');
        $user = User::where('email', $input['email'])->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($newPassword),
            ]);

            return ApiService::response(true, [], 'Password reset successful');
        } else {
            return ApiService::error(false, 'Password Reset Failed', 'User not found');
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'new_password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }

}
