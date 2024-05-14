<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\StripeCustomers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class OrderController extends Controller
{

    public function api_create_orders(Request $request)
    {
        $shopId = env('SHOPID', NULL);
        $stripe_secret_key = env('STRIPE_SECRET', NULL);
        $token = env('ACCESS_TOKEN', NULL);

        $email = $request->address_to['email'];
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api.printify.com/v1/shops/' . $shopId . '/orders.json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request->json()->all()),

                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token . ''
                ),
            )
        );

        $response = curl_exec($curl);

        $data = json_decode($response, true);
        curl_close($curl);
        if (empty($data) || !isset($data['id'])) {
            return response()->json(['status' => 400, 'message' => 'Order failed!', 'data' => []], 200);
        }
        $orderId = $data['id'];
        $productid = $request->line_items[0]['product_id'];
        $variant_id = $request->line_items[0]['variant_id'];
        $quantity = $request->line_items[0]['quantity'];

        $shipping_method = $request->shipping_method;
        $is_printify_express = $request->is_printify_express;
        $send_shipping_notification = $request->send_shipping_notification;
        $first_name = $request->address_to['first_name'];
        $last_name = $request->address_to['last_name'];
        $email = $request->address_to['email'];
        $phone = $request->address_to['phone'];
        $country = $request->address_to['country'];
        $region = $request->address_to['region'];
        $address1 = $request->address_to['address1'];
        $address2 = $request->address_to['address2'];
        $city = $request->address_to['city'];
        $zip = $request->address_to['zip'];
        $payment_method_id = $request->address_to['payment_method_id'];
        $amount = $request->address_to['amount'];
        $orderAdd['order_id'] = $orderId;
        $orderAdd['product_id'] = $productid;
        $orderAdd['variant_id'] = $variant_id;
        $orderAdd['quantity'] = $quantity;
        $orderAdd['shipping_method'] = $shipping_method;
        $orderAdd['is_printify_express'] = $is_printify_express;
        $orderAdd['send_shipping_notification'] = $send_shipping_notification;
        $orderAdd['first_name'] = $first_name;
        $orderAdd['last_name'] = $last_name;
        $orderAdd['email'] = $email;
        $orderAdd['phone'] = $phone;
        $orderAdd['country'] = $country;
        $orderAdd['region'] = $region;
        $orderAdd['address1'] = $address1;
        $orderAdd['address2'] = $address2;
        $orderAdd['city'] = $city;
        $orderAdd['zip'] = $zip;
        $orderAdd['payment_method_id'] = $payment_method_id;
        $orderAdd['amount'] = $amount;
        $orderdata = Order::create($orderAdd);

        $orderdata['netAmount'] = 500;
        $emailData = [
            'order' => $orderdata,
        ];

        $orderDetailsResponse = $this->get_order_details($request, $orderId);

        $orderDetails = json_decode($orderDetailsResponse->getContent(), true);
        $amount = $orderDetails['data']['amount'];
        $tax = ($amount * 18) / 100;
        $shippingCharge = 20;
        $totalAmount = $amount + $tax + $shippingCharge;


        // Mail::to($request->address_to['email'])->send(new OrderConfirmation($emailData));
        Mail::to($request->address_to['email'])->send(new OrderConfirmation($emailData,$amount,$tax,$shippingCharge,$totalAmount));


        return response()->json(['status' => 200, 'message' => 'Order Placed Successfully', 'data' => $orderdata], 200);
    }

    public function api_cancel_order(Request $request, $order_id)
    {

        $orderId = $order_id;
        $shopId = env('SHOPID', NULL);

        $token = env('ACCESS_TOKEN', NULL);
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api.printify.com/v1/shops/' . $shopId . '/orders/' . $orderId . '/cancel.json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token . ''
                ),
            )
        );

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        if (empty($data) || !isset($data['id'])) {
            return response()->json(['status' => 400, 'message' => 'Order Cancellation Failed.Can Not Cancel Order ' . $orderId . ' in Cancelled Status'], 200);
        }

        return response()->json(['status' => 200, 'message' => 'Order Cancelled Successfully'], 200);
    }

    public function get_order_details(Request $request, $order_id)
    {
        $token = env('ACCESS_TOKEN', NULL);
        $shopId = env('SHOPID', NULL);

        $order = Order::where('order_id', $order_id)->first();
        if (!$order) {
            return response()->json(['status' => 404, 'message' => 'Order not found'], 404);
        }

        $products = Order::where('order_id', $order_id)->get();

        $orderData = [
            'order_id' => $order->order_id,
            'shipping_method' => $order->shipping_method,
            'is_printify_express' => $order->is_printify_express,
            'send_shipping_notification' => $order->send_shipping_notification,
            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
            'email' => $order->email,
            'phone' => $order->phone,
            'country' => $order->country,
            'region' => $order->region,
            'address1' => $order->address1,
            'address2' => $order->address2,
            'city' => $order->city,
            'zip' => $order->zip,
            'payment_method_id' => $order->payment_method_id,
            'amount' => $order->amount,
            'transaction_id' => $order->transaction_id,
            'transaction_status' => $order->transaction_status,
            'products' => [],
        ];

        foreach ($products as $product) {
            $curl = curl_init();
            $productId = $product->product_id;
            curl_setopt_array(
                $curl,
                array(

                    CURLOPT_URL => 'https://api.printify.com/v1/shops/' . $shopId . '/products/' . $productId . '.json',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token . ''
                    ),
                )
            );
            $response = curl_exec($curl);
            curl_close($curl);
            $productData = json_decode($response, true);
            $variant = null;
            foreach ($productData['variants'] as $v) {
                if ($v['id'] == $product->variant_id) {
                    $variant = $v;
                    break;
                }
            }

            $imageForVariant = null;
            foreach ($productData['images'] as $image) {
                if ($image['variant_ids'][0] == $product->variant_id) {
                    $imageForVariant = $image['src'];
                    break;
                }
            }
            $orderData['products'][] = [
                'product_id' => $product->product_id,
                'variant_id' => $product->variant_id,
                'quantity' => $product->quantity,
                'name' => $productData['title'],
                'price' => $variant['price'],
                'images' => $imageForVariant,
            ];
        }

        return response()->json(['status' => 200, 'message' => 'Order details retrieved successfully', 'data' => $orderData], 200);
    }
}
