<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Product;

use App\Models\Store;
use App\Models\Region;
use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class ClientOrderController extends Controller
{





    public function index(Request $request)
    {
        // $events = Event::paginate(8);

        if ($request->ajax()) {
            $orders = Order::select('orders.*')
                ->orderBy('created_at', 'desc')
                ->get();

            return Datatables::of($orders)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $edit_url = route('order.views', $row->order_id);
                    $delete_url = route('order.destroy', $row->order_id);

                    $btn = '<a href="' . $edit_url . '" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                    <a href="' . $delete_url . '" class="btn btn-danger" onclick="return deleteConfirm()"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        return view('orders.order-list');
    }

    public function view_orders($orderId)
    {
        $token = env('ACCESS_TOKEN', NULL);
        $shopId = env('SHOPID', NULL);

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api.printify.com/v1/shops/' . $shopId . '/orders/' . $orderId . '.json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $token . '',
                    'Cookie: __cf_bm=SIx8Uc7sAtFlme5rPPR2.g8uNgb9sVC6IsoLC.Thhxg-1706527439-1-AZScHKnzUVpowPNFw+va3ZB6D3xmzFycNcktT/HHPCw4N/OK14IwJsYkbYsIdSKQk87Y7m640dO9wXR0dLeQzdA='
                ),
            )
        );

        $response = curl_exec($curl);


        $data = json_decode($response, true);

        if (!empty($data['address_to'])) {
            $shippingAddress = $data['address_to'];
        } else {
            $shippingAddress = [];
        }

        /*---- Create it in forloop ----*/
        if (!empty($data['line_items'])) {
            $productDetails  = $data['line_items'];
        } else {
            $productDetails = [];
        }

        // $productCount = count($data['data']);


        curl_close($curl);


        $orderView = Order::where('order_id', $orderId)->first();
        $productId = $orderView['product_id'];
        $productView = Product::where('product_id', $productId)->first();

        return view('orders.order-view', compact('shippingAddress', 'productDetails', 'productView'));


    }

    public function destroy($order_id)
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
            return redirect()->route('order.index')->with('success', 'Order Cancellation Failed..');
        } else {
            return redirect()->route('order.index')->with('success', 'Order Cancellation Successfully.');

        }




    }

    public function orderemail()
    {

        $orderDetails = [
            [
                'image' => 'image1.jpg',
                'name' => 'Product 1',
                'quantity' => 5,
                'price' => 400.00,
            ],
            [
                'image' => 'image2.jpg',
                'name' => 'Product 2',
                'quantity' => 3,
                'price' => 300.00,
            ],
        ];

        return view('emails.orderemail', compact('orderDetails'));
    }






}
