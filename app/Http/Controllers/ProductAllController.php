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

class ProductAllController extends Controller
{

    public function index(Request $request)
    {
        // $events = Event::paginate(8);

        if ($request->ajax()) {
            $products = Product::select('products.*')->get();
            //dd($orders);


            return Datatables::of($products)
                ->addIndexColumn()


                ->addColumn('action', function ($row) {

                    $edit_url = route('product.edit', $row->id);
                    $view_url = route('product.view', $row->id);
                    $btn = '<a href="' . $edit_url . '" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                    <a href="' . $view_url . '" class="btn btn-warning mt-2"><i class="fa fa-eye"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        return view('products.product-list');
    }

    public function edit(Product $product)
    {
        $productDetails = $product;

        $data['form_title'] = "Edit Product";
        return view('products.product-edit', compact('productDetails', 'data'));
    }

    public function update(Request $request, Product $product)
    {

        $request->validate([
            'title' => 'required',
            'description' => 'required',

        ], [
            'title.required' => 'Product Title is required.',
            'description.required' => 'Product Description is required.',

        ]);


        $updatedArray = [
            'title' => $request->title,
            'description' => $request->description,
            'visible' => $request->status,
        ];

        $product->update($updatedArray);

        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }




    public function product_view(Product $product)
    {
        $productDetails = $product;


        $data['form_title'] = "View Product Information";
        return view('products.product-view', compact('productDetails', 'data'));
    }






}
