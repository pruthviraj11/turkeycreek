<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PushNotification;
use App\Models\User;
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



use App\Mail\PasswordResetMail;

class NotificationApiController extends Controller
{

    public function notifications(Request $request)
    {
        $userId = $request->auth_user_id;
        try {
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            $offset = ($page - 1) * $perPage;

            $notifications = PushNotification::select('push_notifications.*')
            ->leftJoin('push_notification_user','push_notifications.id','=','push_notification_user.push_notification_id')
            ->where('push_notification_user.user_id', $userId)->skip($offset)->take($perPage)->get();

            $notifications = $notifications->map(function ($notification) {
                $notification->notification_title = $notification->notification_title ?? '';
                $notification->notification_message_body = $notification->notification_message_body ?? '';
                $notification->member_id = $notification->member_id ?? 0;
                $notification->sendToAllMembers = $notification->sendToAllMembers ?? 0;
                $notification->notification_type = $notification->notification_type ?? '';
                $notification->read_status = $notification->read_status ?? 0;
                $notification->status_flag = $notification->status_flag ?? 0;
                $notification->reference_id = $notification->reference_id ?? 0;
                $notification->created_at = $notification->created_at ?? '';
                $notification->updated_at = $notification->updated_at ?? '';
                $notification->deleted_at = $notification->deleted_at ?? '';
                return $notification;
            });


            $dataCount = PushNotification::where('user_id', $userId)->count();
            $lastPage = ceil($dataCount / $perPage);
            $paginationData = [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $dataCount,
                'last_page' => $lastPage,
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, $dataCount),
            ];

            return ApiService::response(true, ['notifications' => $notifications, 'uread_count' => $dataCount, 'pagination' => $paginationData], 'Notifications data retrieved successfully');

        } catch (\Exception $e) {
            return ApiService::error(false, 'Error getting data', $e->getMessage());
        }


    }



    public function notificationDetails(Request $request, $notificcationId)
    {

        $userId = $request->auth_user_id; //LoggedIn UserId
        try {
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);
            $offset = ($page - 1) * $perPage;
            $notifications = PushNotification::where('id', $notificcationId)->skip($offset)->take($perPage)->get();
            $totalNotifications = PushNotification::where('id', $notificcationId)->count();

            $notifications = $notifications->map(function ($notification) {
                $notification->notification_title = $notification->notification_title ?? '';
                $notification->notification_message_body = $notification->notification_message_body ?? '';
                $notification->member_id = $notification->member_id ?? 0;
                $notification->sendToAllMembers = $notification->sendToAllMembers ?? 0;
                $notification->notification_type = $notification->notification_type ?? '';
                $notification->read_status = $notification->read_status ?? 0;
                $notification->status_flag = $notification->status_flag ?? 0;
                $notification->reference_id = $notification->reference_id ?? 0;
                $notification->created_at = $notification->created_at ?? '';
                $notification->updated_at = $notification->updated_at ?? '';
                $notification->deleted_at = $notification->deleted_at ?? '';

                return $notification;
            });


            $paginationData = [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalNotifications
            ];

            return ApiService::response(true, ['notifications' => $notifications, 'pagination' => $paginationData], 'Notifications data retrieved successfully');

        } catch (\Exception $e) {
            return ApiService::error(false, 'Error getting data', $e->getMessage());
        }
    }

    public function readNotifications(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'notification_ids' => 'required|array',
                'read' => 'required|boolean',
            ]);
            $notificationIds = $validatedData['notification_ids'];
            $read = $validatedData['read'];
            $notifications = PushNotification::whereIn('id', $notificationIds)->get();
            foreach ($notifications as $notification) {
                $notification->read_status = $read;
                PushNotification::whereIn('id', $notificationIds)
                    ->update(['status_flag' => $read]);
            }

            return ApiService::response(true, [], 'Notifications marked as ' . ($read ? 'read' : 'unread') . ' successfully');
        } catch (\Exception $e) {
            return ApiService::error(false, 'Error updating notification status', $e->getMessage());
        }

    }


    public function UpdateNotificationToken(Request $request)
    {
        try {

            $user_id = $request->auth_user_id;
            $user = User::find($user_id);
            if (!$user) {
                return ApiService::error(false, 'User not found');
            }
            $data = $request->all();
            unset($data['auth_user_id']);
            // dd($data);
            $user->update($data);

            $updated_user = User::where('id', $user_id)->get();
            $users = $updated_user->map(function ($user) {
                // $user->user_id = $user->user_id ?? 0;
                $user->name = $user->name ?? '';
                $user->email = $user->email ?? '';
                $user->mobile_no = $user->mobile_no ?? '';
                $user->email_verified_at = $user->email_verified_at ?? '';
                $user->confirm_password = $user->confirm_password ?? '';
                $user->address = $user->address ?? '';
                $user->zip_code = $user->zip_codev ?? '';
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
                $user->reset_otp = $user->reset_otp ?? '';
                $user->reset_token_expires_at = $user->reset_token_expires_at ?? '';
                $user->notification_token = $user->notification_token ?? '';

                return $user;
            });
            return ApiService::response(true, ['user' => $users], 'User information updated successfully');
        } catch (\Exception $e) {
            return ApiService::error(false, 'Error updating user information', $e->getMessage());
        }
    }

}
