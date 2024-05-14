<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Region;
use App\Models\Coupons;
use App\Models\VipMemberships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\URL;

class VipMembershipController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $VipMembership = VipMemberships::select('id', 'title', 'description', 'image', 'status')->get();
                return Datatables::of($VipMembership)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $image = '<img src="' . $row->image . '" width="150"  />';
                    return $image;
                })

                ->addColumn('action', function ($row) {
                    $edit_url = route('vip_membership.edit', $row->id);
                    // dump($row->id)

                    $delete_url = route('vip_membership.destroy', $row->id);
                    $btn = '<a href="' . $edit_url . '" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                <a href="' . $delete_url . '" class="btn btn-danger" onclick="return deleteConfirm()"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        return view('VipMembership.VipMembership-list');
    }

    public function create()
    {
        $data['form_title'] = "Create Vip Membership";

        $store = Store::all();
        // dd($store);
        return view('VipMembership.VipMembership-create-edit', compact('store', 'data'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate(
            [
                'title' => 'required',
                'image' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required',

            ],
            [
                'title.required' => 'title is required.',
                'description.required' => 'Description is required',
                'image' => 'Image is Required',
                'image.mimes' => 'Allowed image formats are: jpeg, png, jpg, gif.',
                'image.max' => 'Image size must not exceed 2MB.',

            ]
        );

        $VipMembership = new VipMemberships();
        $VipMembership->title = $request->title;
        $VipMembership->status = $request->status;

        // if (!empty($request->image)) {
        //     $filename = $request->image->getClientOriginalExtension();
        //     $filepath = "image/" . time() . "." . $filename;
        //     $path = Storage::disk("public")->put(
        //         $filepath,
        //         file_get_contents($request->image)
        //     );
        //     $VipMembership->image = $filepath;
        // }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/VipMembers'), $filename);
            $VipMembership->image = 'images/VipMembers/' . $filename;
        }

        $VipMembership->description = $request->description;

        $VipMembership->save();

        // VipMemberships::create($request->all());

        return redirect()->route('vip_membership.index')->with('success', 'Vip Membership created successfully.');
    }

    public function edit($VipMembership)
    {

        $data['form_title'] = "Edit Vip Membership";
        $VipMembership = VipMemberships::where('id',$VipMembership)->first();
        // dd('Hii');
        $store = Store::all();

        return view('VipMembership.VipMembership-create-edit', compact('VipMembership', 'data', 'store'));
    }

    public function update(Request $request, VipMemberships $VipMembership)
    {
        Log::info('Update request received.');

        $request->validate([
            'title' => 'required',
            'image' => '',
            'description' => 'required',
            'status' => 'required|in:Active,Inactive', // Fixed typo in 'Inactive'
        ], [
            'title.required' => 'Title is required.',
            'url.url' => 'Not valid format of URL', // Fixed typo in 'URL'
        ]);

        $newVipMembership = new VipMemberships(); // Create a new instance

        // Assign updated values to the new instance
        $newVipMembership->title = $request->title;
        $newVipMembership->description = $request->description;
        $newVipMembership->status = $request->status;

        if ($request->hasFile('image')) {
            // Upload new image
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/VipMembers'), $filename);
            $newVipMembership->image = 'images/VipMembers/' . $filename;
        }

        $newVipMembership->save(); // Save the new record

        Log::info('New Vip Membership record created.');

        return redirect()->route('vip_membership.index')->with('success', 'New Vip Membership record created successfully.');
    }
    public function destroy(VipMemberships $VipMembership)
    {
        dd($VipMembership);
        $VipMembership->delete();

        return redirect()->route('vip_membership.index')->with('success', 'Vip Membership deleted successfully.');
    }

    public static function getStoreName($id)
    {
        $store = Store::where('id', '=', $id)->pluck('title');
        $name = $store[0] ?? "Not Found";
        return $name;
    }

}
