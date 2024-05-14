<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Category;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;

class StoresController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $Store = Store::select('stores.*', 'categories.name AS category_name', 'sub_categories.name AS subcategory_name', 'categories.parent_id')
                ->leftJoin('categories', 'stores.category_id', '=', 'categories.id')
                ->leftJoin('categories AS sub_categories', 'stores.sub_category_id', '=', 'sub_categories.id')
                ->get();

            return Datatables::of($Store)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit_url = route('store-edit', $row->id);
                    $delete_url = route('store-delete', $row->id);
                    $btn = '<a href="' . $edit_url . '" class="btn btn-warning"><i class="fa fa-edit"></i> </a>
                    <a href="' . $delete_url . '" class="btn btn-danger" onclick="return deleteConfirm()"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users/store/list');
    }
    public function api_category_wise($category_id = '')
    {
        if ($category_id == '') {
            //$categories = Category::with('parent')->whereNull('parent_id')->get();
        } else {
            $Store = Store::select('stores.*', 'categories.name AS category_name', 'subcat.name AS sub_category_name')
                ->leftJoin('categories', 'stores.category_id', '=', 'categories.id')
                ->leftJoin('categories AS subcat', 'stores.sub_category_id', '=', 'subcat.id')
                ->where(function ($query) use ($category_id) {
                    $query->where('stores.category_id', '=', $category_id)->orWhere('stores.sub_category_id', '=', $category_id);
                })
                ->get();
        }
        if (count($Store) > 0) {
            return response()->json(['status' => 200, 'message' => count($Store) . ' records found!', 'data' => $Store], 200);
        } else {
            return response()->json(['status' => 200, 'message' => 'No records found!', 'data' => $Store], 200);
        }
    }



    // public function api_crone_product_lists()
    // {

    //     $token = env('ACCESS_TOKEN', NULL);
    //     $shopId = env('SHOPID', NULL);




    //     $curl = curl_init();
    //     curl_setopt_array(
    //         $curl,
    //         array(
    //             CURLOPT_URL => 'https://api.printify.com/v1/shops/' . $shopId . '/products.json',
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => '',
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 0,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => 'GET',
    //             CURLOPT_HTTPHEADER => array(
    //                 'Authorization: Bearer ' . $token . '',
    //                 'Cookie: __cf_bm=SIx8Uc7sAtFlme5rPPR2.g8uNgb9sVC6IsoLC.Thhxg-1706527439-1-AZScHKnzUVpowPNFw+va3ZB6D3xmzFycNcktT/HHPCw4N/OK14IwJsYkbYsIdSKQk87Y7m640dO9wXR0dLeQzdA='
    //             ),
    //         )
    //     );

    //     $response = curl_exec($curl);


    //     $data = json_decode($response, true);



    //     $productCount = count($data['data']);

    //     curl_close($curl);

    //     // echo "<pre>";
    //     // print_r($data);
    //     // exit;




    //     $productDatas = $data['data'];
    //     foreach ($productDatas as $key => $productData) {

    //         $existingProduct = Product::where('product_id', $productData['id'])->first();


    //         if ($existingProduct) {

    //             $existingProduct->update([
    //                 'title' => $productData['title'],
    //                 'description' => $productData['description'],
    //                 'visible' => $productData['visible'],
    //                 'is_locked' => $productData['is_locked'],
    //                 'product_data' => json_encode($productData),
    //             ]);

    //         } else {
    //             $addData = Product::create([
    //                 'product_id' => $productData['id'],
    //                 'title' => $productData['title'],
    //                 'description' => $productData['description'],
    //                 'visible' => $productData['visible'],
    //                 'is_locked' => $productData['is_locked'],
    //                 'product_data' => json_encode($productData),

    //             ]);

    //         }


    //     }
    //     return true;

    // }

    public function api_crone_product_lists()
    {

        $token = env('ACCESS_TOKEN', NULL);
        $shopId = env('SHOPID', NULL);

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api.printify.com/v1/shops/' . $shopId . '/products.json',
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
        $productCount = count($data['data']);
        curl_close($curl);

        if (isset($data['data'])) {
            $productIdsFromPrintify = array_column($data['data'], 'id');
            $productDatas = $data['data'];
            foreach ($productDatas as &$productData) {
                if (isset($productData['variants'])) {
                    foreach ($productData['variants'] as &$variant) {
                        if ($variant['price'] != 0) {
                            $variant['price'] = $variant['price'] / 100;
                        }
                    }
                }
            }
            // $data['data'] = $productDatas;
            // dd($variant);
            // foreach ($productDatas as $key => $productData) {
            //     if (isset($productData['variants'])) {
            //         $variants = $productData['variants'];

            //         foreach ($variants as $variant) {
            //             if ($variant['price'] != 0) {
            //                 $price = $variant['price'] / 100;
            //                 $data_price['doller']=  $price;
            //             }
            //         }
            //     }
            // }
            // dd($productDatas);

            // dd($data['data'][2]);

            // $productDatas = $data['data'];
            // dd($productDatas);
            foreach ($productDatas as $key => $productData) {

                $existingProduct = Product::where('product_id', $productData['id'])->first();

                if ($existingProduct) {

                    $existingProduct->update([
                        'title' => $productData['title'],
                        'description' => $productData['description'],
                        'visible' => $productData['visible'],
                        'is_locked' => $productData['is_locked'],
                        'product_data' => json_encode($productData),
                    ]);

                } else {
                    $addData = Product::create([
                        'product_id' => $productData['id'],
                        'title' => $productData['title'],
                        'description' => $productData['description'],
                        'visible' => $productData['visible'],
                        'is_locked' => $productData['is_locked'],
                        'product_data' => json_encode($productData),
                    ]);
                }
            }
            Product::whereNotIn('product_id', $productIdsFromPrintify)->delete();
        }
        return true;
    }
    public function api_product_lists()
    {
        // echo "<pre>";
        $ProductDatas = Product::where('visible', 1)->whereNull('deleted_at')->get();
        $data = [];
        foreach ($ProductDatas as $ProductData) {
            $records = json_decode($ProductData->product_data, true);
            $records['title'] = $ProductData->title;
            $records['description'] = $ProductData->description;

            array_push($data, $records);
        }

        if ($data) {
            return response()->json(['status' => 200, 'message' => count($data) . ' records found!', 'data' => $data], 200);
        } else {
            return response()->json(['status' => 200, 'message' => 'No records found!', 'data' => $data], 200);
        }

    }

    public function api_product_info($id)
    {
        $ProductData = Product::where('product_id', $id)->where('visible', 1)->first();

        if ($ProductData) {
            $records = json_decode($ProductData->product_data, true);
            $records['title'] = $ProductData->title;
            $records['description'] = $ProductData->description;


            if ($records) {
                return response()->json(['status' => 200, 'message' => ' Product found!', 'data' => $records], 200);
            }

        } else {
            return response()->json(['status' => 200, 'message' => 'No records found!', 'data' => ''], 200);
        }







        // $token = env('ACCESS_TOKEN', NULL);
        // $curl = curl_init();

        // curl_setopt_array(
        //     $curl,
        //     array(
        //         CURLOPT_URL => 'https://api.printify.com/v1/shops/14058903/products/' . $id . '.json',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'GET',
        //         CURLOPT_HTTPHEADER => array(
        //             'Authorization: Bearer ' . $token . '',
        //             'Cookie: __cf_bm=ZzCOTdDxiYxUL59ixvyJ1cUIR_j1IY5DeRHG0uYqu0w-1706528425-1-ARhQ19RuYOec4dE8Lr7iNl9HSpY/G+zt7SVBFygNAT4+JleXj3NV2mYjGgy1LTEUW8H/UOKrcJWnUDKNoqQ4eus='
        //         ),
        //     )
        // );

        // $response = curl_exec($curl);

        // $data = json_decode($response, true);
        // // $productCount = count($data['data']);

        // curl_close($curl);

        // echo "<pre>";
        // print_r($response);
        // exit;


        // if (count($response->id) > 0) {
        //     return response()->json(['status' => 200, 'message' => count($response->id) . ' records found!', 'data' => $data], 200);
        // } else {
        //     return response()->json(['status' => 200, 'message' => 'No records found!', 'data' => $data], 200);
        // }

    }

    public function api_create_orders(Request $request)
    {
        $this->validate($request, [
            'external_id' => 'required|string',
            'line_items' => 'required|array',
            'line_items.*.product_id' => 'required|string',
            'line_items.*.print_provider_id' => 'required|integer',
            'line_items.*.blueprint_id' => 'required|integer',
            'line_items.*.variant_id' => 'required|integer',
            'line_items.*.print_areas' => 'required|object',
            'line_items.*.quantity' => 'required|integer',
            'shipping_method' => 'required|integer',
            'send_shipping_notification' => 'required|boolean',
            'address_to' => 'required|array',
            'address_to.first_name' => 'required|string',
            'address_to.last_name' => 'required|string',
            'address_to.email' => 'required|email',
            'address_to.phone' => 'required|string',
            'address_to.country' => 'required|string',
            'address_to.region' => 'required|string',
            'address_to.address1' => 'required|string',
            'address_to.city' => 'required|string',
            'address_to.zip' => 'required|string',
        ]);
        dd("hi");
        //dd($request->all());
        $shopId = env('SHOPID', NULL);

        $token = env('ACCESS_TOKEN', NULL);
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
        // dd($response);

        $data = json_decode($response, true);
        curl_close($curl);

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

        echo "<pre>";
        print_r($orderAdd);





        //dd($request->external_id);
    }











    public function create()
    {
        $data['form_title'] = "Create Store";
        $categoryData = Category::with('subcategory')->whereNull('parent_id')->get();
        $Store = null;
        $SubcategoryData = [];
        $SubcategoryDataTemp = Category::with('subcategory')->whereNotNull('parent_id')->get();
        return view('users/store/create-edit', compact('data', 'categoryData', 'Store', 'SubcategoryData', 'SubcategoryDataTemp'));
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'address_store' => 'required|string|max:255',
            'website_name' => 'required|string',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Active,In Active',


        ], [
            'title.required' => 'Title is required.',
            'address_store.required' => 'Address is required.',
            'website_name.required' => 'Website name is required.',
            'category_id' => 'Category is Required',
            'sub_category_id' => 'Sub Category is Required',
            'photo.image' => 'The file must be an image.',
            'photo.mimes' => 'Allowed image formats are: jpeg, png, jpg, gif.',
            'photo.max' => 'Image size must not exceed 2MB.',
        ]);

        if ($request->hasFile('photo')) {
            $imageName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('/images'), $imageName);

            $validatedData['photo'] = $imageName;
        }
        $validatedData['mobile'] = $request->get('mobile');
        $validatedData['category_id'] = $request->get('category_id');
        $validatedData['status'] = $request->get('status');


        Store::create($validatedData);

        return redirect()->route('store-list')->with('success', 'Store created successfully.');
    }


    public function getall()
    {
        $store = Store::all();
        return view('store-list', compact('store'));
    }

    public function edit($id)
    {
        $data['form_title'] = "Edit Store";
        $categoryData = Category::with('subcategory')->whereNull('parent_id')->get();
        $Store = Store::findOrFail($id);
        $SubcategoryData = Category::where('parent_id', $Store->category_id)->get();
        $SubcategoryDataTemp = Category::with('subcategory')->whereNotNull('parent_id')->get();
        return view('users/store/create-edit', compact('Store', 'data', 'id', 'categoryData', 'SubcategoryData', 'SubcategoryDataTemp'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'address_store' => 'required|string|max:255',
            'mobile' => 'nullable',
            'website_name' => 'required|string',
            'category_id' => 'required',
            'sub_category_id' => '',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Active,In Active',

        ]);

        $store = Store::find($id);

        if (!$store) {
            return redirect()->route('store-list')->with('error', 'Store not found');
        }

        $oldImage = $store->photo;
        $store->title = $validatedData['title'];
        $store->address_store = $validatedData['address_store'];
        $store->mobile = $validatedData['mobile'];
        $store->website_name = $validatedData['website_name'];
        $store->category_id = $validatedData['category_id'];
        $store->sub_category_id = $validatedData['sub_category_id'];
        $store->status = $validatedData['status'];

        if ($request->hasFile('photo')) {
            $imageName = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('images'), $imageName);
            $store->photo = '' . $imageName;

            if ($oldImage && file_exists(public_path($oldImage))) {
                unlink(public_path($oldImage));
            }
        }
        $store->save();

        return redirect()->route('store-list')->with('success', 'Store updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->route('store-list')
            ->with('success', 'Store deleted successfully');
    }
}
