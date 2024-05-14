<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StripeCustomers;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Auth;
use Exception;
use Stripe\StripeClient;



class PaymentController extends Controller
{
    public function api_create_stripe_customer(Request $request)
    {
        try {
            $stripe_secret_key = env('STRIPE_SECRET', NULL);
            $email = $request->email;
            $existingCustomer = StripeCustomers::where('email', $email)->count();
            if ($existingCustomer == 0) {
                $stripeDbCutomerData = StripeCustomers::create([
                    'email' => $request->email,
                ]);
                $stripeCustomer = $stripeDbCutomerData->createAsStripeCustomer();

                return response()->json(['success' => 'Customer created successfully.'], 200);
            } else {
                $Customer = StripeCustomers::where('email', $email)->get();
                return response()->json(['customer' => $Customer]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // public function api_add_payment_method(Request $request)
    // {
    //     try {
    //         $token = $request->token;
    //         $email = $request->email;
    //         $stripe_secret_key = env('STRIPE_SECRET', NULL);
    //         $stripe_key = env('STRIPE_KEY', NULL);

    //         $user = StripeCustomers::where('email', $email)->first();
    //         if (!$user) {
    //             return response()->json(['error' => 'User not found.'], 404);
    //         }


    //         $paymentMethod = $user->addPaymentMethod($token);
    //         // dd($paymentMethod);

    //         $user->updateDefaultPaymentMethod($paymentMethod->id);

    //         return response()->json(['success' => 'Payment method added successfully.'], 200);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 400);
    //     }
    // }

    public function api_add_payment_method(Request $request)
    {
        try {
            // Validate input parameters
            $request->validate([
                'token' => 'required|string',
                'email' => 'required|email',
            ]);

            // Retrieve the Stripe secret key from environment
            $stripe_secret_key = env('STRIPE_SECRET');
            $stripe = new StripeClient($stripe_secret_key);

            // Retrieve the customer from the database
            $user = StripeCustomers::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            // Create a payment intent
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => 500,
                'currency' => 'gbp',
                'payment_method' => $request->token,
                'customer' => $user->stripe_id,
            ]);

            // If you want to set this payment method as default for the customer
            // $stripe->customers->update($user->stripe_id, ['default_payment_method' => $paymentIntent->payment_method]);

            return response()->json(['success' => 'Payment method added successfully.'], 200);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()->first()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function api_remove_payment_method(Request $request)
    {

        try {
            $paymentMethodId = $request->payment_method_id;
            $email = $request->email;

            $user = StripeCustomers::where('email', $email)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            $user->removePaymentMethod($paymentMethodId);

            return response()->json(['success' => 'Payment method removed successfully.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }



}
