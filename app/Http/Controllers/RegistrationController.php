<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Email\Email;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Security\Security;



use App\Mail\PasswordResetMail;

class RegistrationController extends Controller
{

    public function registration(Request $request)
    {
        try {

            $checkEmail = Registration::where('email', $request->input('email'))->count();

            if ($checkEmail > 0) {
                return response()->json(['success' => false, 'message' => 'Email already exists']);
            }


            $registration = new Registration();
            $registration->first_name = $request->input('first_name');
            $registration->last_name = $request->input('last_name');
            $registration->email = $request->input('email');
            $registration->mobile = $request->input('mobile');
            $registration->password = Hash::make($request->input('password'));
            $registration->is_active = 'active';
            $registration->save();


            $accessToken = $this->generateAccessToken($registration->id);
            $refreshToken = $this->generateRefreshToken($registration->id);


            $registration->update([
                'verify_phone' => true,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_at' => now()->addDay(1),
            ]);


            $data = [
                "user_id" => $registration->id,
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
            ];

            return ApiService::response(true, $data, 'User registered successfully.');

        } catch (\Exception $e) {

            return ApiService::response(false, null, $e->getMessage());
        }
    }


    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');


        $checkEmail = Registration::where('email', $email)->count();

        if ($checkEmail > 0) {
            $user = Registration::where('email', $email)->first();
            if (Hash::check($password, $user['password'])) {
                if ($user['is_active'] == "active") {
                    return response()->json(['success' => true, 'message' => 'Login Successfully']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Your Account is still Inactive']);
                }

            } else {
                return response()->json(['success' => false, 'message' => 'Invalid Login Information']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid Login Information']);
        }







        // echo "<pre>";

        // print_r($user);


        // echo "email :-" . $email . "</br>";
        // echo "password :-" . $password . "</br>";

        exit;




    }

    public function changePassword(Request $request)
    {
        $email = $request->input('email');
        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');
        $confirmPassword = $request->input('confirmPassword');


        $user = Registration::where('email', $email)->first();
        if (Hash::check($oldPassword, $user['password'])) {
            if ($newPassword != $confirmPassword) {
                return response()->json(['success' => false, 'message' => 'New Password and Confirm Password does not match']);
            } else {

                $user->password = Hash::make($newPassword);
                $user->save();

                return response()->json(['success' => true, 'message' => 'Password Successfully Updated']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid Old Password']);
        }
    }


    public function forgotPassword(Request $request)
    {

        $newPassword = $this->generateRandomPassword();
        $email = $request->input('email');
        $checkEmail = Registration::where('email', $email)->count();
        if ($checkEmail > 0) {
            $user = Registration::where('email', $email)->first();
            $user->password = Hash::make($newPassword);
            $user->save();


            $callback = route('user-reset-password', $user->id);

            $this->sendPasswordEmail($email, $callback);




            return response()->json(['success' => true, 'message' => 'Password Sent in your Email Address']);
        } else {
            return response()->json(['success' => false, 'message' => 'Email Id not Exits in Our Database']);
        }





    }

    // private function sendOtpViaEmail($email, $callback)
    // {

    //     try {
    //         Mail::to($email)->send(new OtpMail("Reset Link", $callback));
    //         return ApiService::response(true, [], 'Password Reset Link Sent Successfully.');
    //     } catch (\Exception $e) {
    //         return ApiService::error(false, 'OTP Sent Failed', 'Failed to send OTP via Email');
    //     }
    // }

    public function resetPassword(Request $request, $id)
    {
        $newPassword = $request->input('newPassword');
        $confirmPassword = $request->input('confirmPassword');

        if ($newPassword != $confirmPassword) {
            return response()->json(['success' => false, 'message' => 'New Password and Confirm Password does not match']);
        } else {

            $user = Registration::where('id', $id)->first();
            $user->password = Hash::make($newPassword);
            $user->save();

            return response()->json(['success' => true, 'message' => 'Password Successfully Updated']);

        }


    }


    private function generateRandomPassword($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }


    private function sendPasswordEmail($email, $callback)
    {
        Mail::to($email)->send(new PasswordResetMail($callback));
    }


    private function sendPasswordResetEmail($email, $callback)
    {



        // Implement your email sending logic here
        // You can use Laravel's built-in Mail class or any other mailing library
    }


    public function upateProfile(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');

        $password = $request->input('password');

        if ($request->has('photo')) {
            $profilePhotoData = base64_decode($request->input('photo'));
            $filename = 'avatar_' . time() . '.png'; // Generate a unique filename
            Storage::disk('public')->put($filename, $profilePhotoData);
            $uploadPath = Storage::disk('public')->url($filename);

        }

        $user = Registration::where('email', $email)->first();

        if ($password == '') {
            $newPassword = $user['password'];
        } else {
            $newPassword = Hash::make($request->input('password'));

        }


        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->mobile = $mobile;
        $user->password = $newPassword;

        if (isset($uploadPath)) {
            $user->photo = $filename;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Profile Successfully Updated']);
    }







    private function generateAccessToken($userId)
    {
        $signer = new Sha256();
        $privateKey = '7A95895F6CE7FC2672AA68AA814C4FED'; // Replace with your own private key

        $config = Configuration::forSymmetricSigner($signer, InMemory::plainText($privateKey));

        $builder = $config->builder()
            ->issuedBy('turkey-Greeks-app') // Replace with your own issuer
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
            ->issuedBy('turkey-Greeks-app') // Replace with your own issuer
            ->permittedFor('mobile-app') // Replace with your own audience
            ->identifiedBy($userId) // Set the token identifier to the user's ID
            ->expiresAt(new DateTimeImmutable('+30 days')); // Set the expiration time to 30 days from now

        $token = $builder->getToken($signer, $config->signingKey());

        return $token->toString();
    }







}
