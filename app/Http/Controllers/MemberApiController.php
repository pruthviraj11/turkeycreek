<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
use Stripe\Stripe;
use Stripe\Customer;


use App\Mail\PasswordResetMail;

class MemberApiController extends Controller
{


    public function vip_member_benefits(Request $request)
    {
        $userId = $request->auth_user_id;
        try {
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            $offset = ($page - 1) * $perPage;

            $vipMemberships = VipMemberships::select('vip_memberships.*')
                ->skip($offset)
                ->take($perPage)
                ->get();


            $vipMemberships = $vipMemberships->map(function ($membership) {
                $membership->title = $membership->title ?? '';
                $membership->image = $membership->image ?? '';
                $membership->description = $membership->description ?? '';
                $membership->status = $membership->status ?? '';
                $membership->created_at = $membership->created_at ?? '';
                $membership->updated_at = $membership->updated_at ?? '';

                return $membership;
            });

            $dataCount = VipMemberships::count();
            $lastPage = ceil($dataCount / $perPage);
            $paginationData = [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $dataCount,
                'last_page' => $lastPage,
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, $dataCount),
            ];

            return ApiService::response(true, [
                'vip_memberships' => $vipMemberships,
                'total_count' => $dataCount,
                'pagination' => $paginationData
            ], 'VIP member benefits retrieved successfully');

        } catch (\Exception $e) {
            return ApiService::error(false, 'Error getting data', $e->getMessage());
        }
    }

    public function membership_details(Request $request)
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Retrieve the Stripe customer
        $stripeCustomerId = auth()->user()->stripe_id;
        dd($stripeCustomerId);
        $customer = Customer::retrieve($stripeCustomerId);

        // Retrieve subscription data
        $subscriptionDetails = [];
        foreach ($customer->subscriptions->data as $subscription) {
            // Calculate remaining days until the subscription expires
            $currentPeriodEnd = Carbon::createFromTimestamp($subscription->current_period_end);
            $remainingDays = $currentPeriodEnd->diffInDays(Carbon::now());

            $subscriptionDetails[] = [
                'id' => $subscription->id,
                'status' => $subscription->status,
                'current_period_end' => date('d/m/Y', $subscription->current_period_end),
                'payment_method' => $subscription->default_payment_method,
                'remaining_days' => $remainingDays,
            ];
        }

        // Retrieve additional subscription details
        foreach ($subscriptionDetails as &$subscription) {
            $subscriptionData = RestaurantSubscription::where('stripe_subscription_id', $subscription['id'])->first();
            if ($subscriptionData) {
                $restaurantData = Restaurant::find($subscriptionData->restaurant_id);
                $restaurantPlan = RestaurantsPackage::find($subscriptionData->package_id);
                if ($restaurantData && $restaurantPlan) {
                    $subscription['restaurant_name'] = $restaurantData->restaurant_name;
                    $subscription['plan_name'] = $restaurantPlan->tagline;
                } else {
                    $subscription['restaurant_name'] = 'N/A';
                    $subscription['plan_name'] = 'N/A';
                }
            } else {
                $subscription['restaurant_name'] = 'N/A';
                $subscription['plan_name'] = 'N/A';
            }
        }
        unset($subscription);

        // Return the subscription details as JSON response
        return response()->json($subscriptionDetails);


    }


}
