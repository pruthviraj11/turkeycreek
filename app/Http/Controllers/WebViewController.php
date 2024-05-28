<?php

namespace App\Http\Controllers;

// use App\Http\Requests\Company\CreateCompanyRequest;
// use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Appcustomers;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Role;
use App\Services\RoleService;
use App\Services\ProductService;
use Spatie\Permission\Models\Permission;
use App\Helpers\Toast;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Models\PushNotification;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Stripe\Stripe;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\Exception\ApiErrorException;

class WebViewController extends Controller
{

    // public function index($VipMembershipId)
    // {
    //     $vipMembershipId = decrypt($VipMembershipId);
    //     $user = User::where('id', $vipMembershipId)->first();
    //     $customerId = $user->stripe_id;

    //     Stripe::setApiKey(env('STRIPE_SECRET'));

    //     $paymentMethods = PaymentMethod::all([
    //         'customer' => $customerId,
    //         'type' => 'card',
    //     ]);


    //     // dd($subscription);
    //     // Check if the customer has any payment methods
    //     if (count($paymentMethods->data) > 0) {
    //         // Customer has payment methods, list them
    //         $customerPaymentMethods = $paymentMethods->data;

    //         $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    //         $customer = $stripe->customers->retrieve($customerId);
    //         $defaultPaymentMethodId = $paymentMethods->data[0]->id;
    //         $stripe->customers->update(
    //             $customerId,
    //             ['invoice_settings' => ['default_payment_method' => $defaultPaymentMethodId]]
    //         );

    //         $subscription = $stripe->subscriptions->create([
    //             'customer' => $customerId,
    //             'items' => [['price' => 'price_1PKwpg07pZ01yxuzWgOcifwi']], // Replace with your actual price ID
    //         ]);

    //         $subscription = $stripe->subscriptions->create([
    //             'customer' => $customerId,
    //             'items' => [['price' => 'price_1PKwpg07pZ01yxuzWgOcifwi']],
    //         ]);

    //         return view('WebView.webview-list', ['paymentMethods' => $paymentMethods, 'customerId' => $customerId, 'customer' => $customer]);
    //     } else {


    //         $user = User::where('id', $vipMembershipId)->first();
    //         $customerId = $user->stripe_id;
    //         $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

    //         $setupIntent = SetupIntent::create([
    //             'customer' => $user->stripe_id,
    //         ]);

    //         $customer = $stripe->customers->retrieve($customerId);
    //         // dd($setupIntent);
    //         // $paymentMethod = PaymentMethod::create([
    //         //     'type' => 'card',
    //         //     'card' => [
    //         //         'number' => '4242424242424242',
    //         //         'exp_month' => 12,
    //         //         'exp_year' => 2024,
    //         //         'cvc' => '123',
    //         //     ],
    //         // ]);

    //         // $paymentMethod->attach(['customer' => $customerId]);

    //         // $updatedPaymentMethods = PaymentMethod::all([
    //         //     'customer' => $customerId,
    //         //     'type' => 'card',
    //         // ]);

    //         // $customerPaymentMethods = $updatedPaymentMethods->data;
    //         return view('PaymentMethod.paymentMethodAdd', ['intent' => $setupIntent, 'customer' => $customer, 'customerId' => $customerId, 'user' => $user]);
    //     }
    // }

    public function index($VipMembershipId)
    {
        $vipMembershipId = decrypt($VipMembershipId);
        $user = User::where('id', $vipMembershipId)->first();
        $customerId = $user->stripe_id;

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentMethods = PaymentMethod::all([
            'customer' => $customerId,
            'type' => 'card',
        ]);
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $customer = $stripe->customers->retrieve($customerId);

        if (count($paymentMethods->data) > 0) {
            $customerPaymentMethods = $paymentMethods->data;

            return view('WebView.webview-list', ['paymentMethods' => $paymentMethods, 'customerId' => $customerId, 'customer' => $customer]);
        } else {
            $setupIntent = SetupIntent::create([
                'customer' => $user->stripe_id,
            ]);

            return view('PaymentMethod.paymentMethodAdd', ['intent' => $setupIntent, 'customer' => $customer, 'customerId' => $customerId, 'user' => $user]);
        }
    }

    public function processPayment(Request $request)
    {
        $paymentMethodId = $request->input('payment_method_id');
        $customerId = $request->input('customer_id');
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            // Update the default payment method for the customer
            $stripe->customers->update(
                $customerId,
                ['invoice_settings' => ['default_payment_method' => $paymentMethodId]]
            );

            // Create a new subscription for the customer
            $subscription = $stripe->subscriptions->create([
                'customer' => $customerId,
                'items' => [['price' => 'price_1PKwpg07pZ01yxuzWgOcifwi']], // Replace with your actual price ID
            ]);

            $user = User::where('stripe_id', $customerId)->first();

            $user->subscription_id = $subscription->id;
            $user->start_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_start);
            $user->payment_status = 'Payment Successful'; // Adjust as necessary based on your application logic

            // Save the user
            $user->save();

            return response()->json(['success' => true, 'message' => 'Payment successful and subscription created.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateDefaultPaymentMethod(Request $request)
    {
        // dd($request->all());
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customerId = $request->input('customer_id');
        $paymentMethod = $request->input('payment_method');

        try {
            // Update the customer's default payment method in Stripe
            $customer = Customer::update($customerId, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethod
                ]
            ]);

            return response()->json(['success' => true]);
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
