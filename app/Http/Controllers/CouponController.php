<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Region;
use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\URL;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        // $coupons = Coupons::paginate(8);

        if ($request->ajax()) {
            $coupons = Coupons::select('coupons.*', 'stores.title AS store_title')
                ->leftJoin('stores', 'coupons.store_id', '=', 'stores.id')
                ->get();

            return Datatables::of($coupons)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {

                    $imageurl= Storage::url($row->image);
                    $image = '<img src="'. $imageurl .'" width="150"  />';
                    return $image;
                })

                ->addColumn('action', function ($row) {

                    $edit_url = route('coupon.edit', $row->id);
                    $delete_url = route('coupon.destroy', $row->id);
                    $btn = '<a href="' . $edit_url . '" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                    <a href="' . $delete_url . '" class="btn btn-danger" onclick="return deleteConfirm()"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action','image'])
                ->make(true);
        }

        return view('coupon.coupon-list');
    }
    public function api_store_wise($store_id = ''){
        if ($store_id == '') {
            $coupons = Coupons::select('coupons.*', 'stores.title AS store_title')
                ->leftJoin('stores', 'coupons.store_id', '=', 'stores.id')
                ->get();
        } else {
            $coupons = Coupons::select('coupons.*', 'stores.title AS store_title')
                ->leftJoin('stores', 'coupons.store_id', '=', 'stores.id')
                ->where('coupons.store_id', '=', $store_id)
                ->get();
        }
        $modifiedData = $coupons->map(function ($item) {
            // Modify the variable before sending it in the response
            $item->image = URL::to(Storage::url($item->image));
            return $item;
        });
        if(count($coupons) > 0){
            return response()->json(['status'=>200,'message'=>count($modifiedData).' records found!','data'=>$modifiedData],200);
        } else {
            return response()->json(['status'=>200,'message'=>'No records found!','data'=>$modifiedData],200);
        }
    }
    public function create()
    {
        $data['form_title'] = "Create Coupon";

        $store = Store::all();
        // dd($store);
        return view('coupon.coupon-create-edit', compact('store', 'data'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'required|url',

        ],
        [
            'name.required' => 'Name is required.',
            'url.url' => 'Url format is not Valid',
            'image' => 'Image is Required',
            'image.mimes' => 'Allowed image formats are: jpeg, png, jpg, gif.',
            'image.max' => 'Image size must not exceed 2MB.',

        ]);

        $coupon = new Coupons();
        $coupon->name = $request->name;
        $coupon->status = $request->status;

        if (!empty($request->image)) {
            $filename = $request->image->getClientOriginalExtension();
            $filepath = "image/" . time() . "." . $filename;
            $path = Storage::disk("public")->put(
                $filepath,
                file_get_contents($request->image)
            );
            $coupon->image = $filepath;
        }

        $coupon->url = $request->url;
        $coupon->store_id = $request->store_id;

        $coupon->save();

        // Coupons::create($request->all());

        return redirect()->route('coupon.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupons $coupon)
    {

        $data['form_title'] = "Edit Coupon";

        // dd('Hii');
        $store = Store::all();

        return view('coupon.coupon-create-edit', compact('coupon', 'data', 'store'));
    }

    public function update(Request $request, Coupons $coupon)
    {

        Log::info('Update request received.');

        $request->validate([
            'name' => 'required',
            'image' => '',
            'url' => 'required|url',
            'status' => 'required|in:Active,In Active',
        ],
        [
            'name.required' => 'Name is required.',
            'url.url' => 'Not valid format of url'
        ]);

            $coupon->name = $request->name;
            $coupon->image = $request->image;
            $coupon->url = $request->url;
            $coupon->status = $request->status;
            $coupon->store_id = $request->store_id;


        if ($request->hasFile('image')) {

            $filename = $request->image->getClientOriginalName();
            $filepath = "image/" . time() . $filename;
            $path = Storage::disk("public")->put($filepath, file_get_contents($request->image));
            $coupon->image = $filepath;

        } elseif (!empty($request->old_image)) {
            $coupon->image = $request->old_image;
        }

        $coupon->save();
        Log::info('Update completed successfully.');

        return redirect()->route('coupon.index')->with('success', 'Coupons updated successfully.');
    }

    public function destroy(Coupons $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupon.index')->with('success', 'Coupon deleted successfully.');
    }

    public static function getStoreName($id)
    {
        $store = Store::where('id', '=', $id)->pluck('title');
        $name = $store[0] ?? "Not Found";
        // dd($store);
        return $name;
    }

}
