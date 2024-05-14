<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        $Store = Store::all();
        return view('users/Store/list', compact('Store'));
    }

    public function create()
    {
        $data['form_title'] = "Create Store";
        $Store = null;
        return view('users/Store/create-edit', compact('data', 'Store'));
    }

    public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'title' => 'required|string|max:255',
        'address_store' => 'required|string|max:255',
        'mobile' => 'required|string|max:20',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the image validation rules as needed
    ]);

    // Handle image upload and storage
    if ($request->hasFile('photo')) {
        $imagePath = $request->file('photo')->store('store_images', 'public'); // Store the image in the 'public/store_images' directory
    } else {
        $imagePath = null;
    }

    // Create a new Store instance and save it to the database
    Store::create([
        'title' => $request->input('title'),
        'address_store' => $request->input('address_store'),
        'mobile' => $request->input('mobile'),
        'photo' => $imagePath, // Store the image path in the database
    ]);

    return redirect()->route('store-list')->with('success', 'Store created successfully.');
}
    public function getall()
    {
        $store = Store::all();
        return view('store-list', compact('store'));
    }

    public function edit($id)
    {
        $data['form_title'] = "edit store";
        $store = Store::findOrFail($id);
        return view('users/store/create-edit', compact('store', 'data', 'id'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'address_store' => 'required|string|max:255',
            'website_name' => 'required|string',
            'mobile' => 'required|integer|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $store = Store::find($id);

        if (!$store) {
            return redirect()->route('store-list')->with('error', 'store not found');
        }

        $oldImage = $store->photo;
        $store->title = $validatedData['title'];
        $store->address_store = $validatedData['address_store'];
        $store->website_name = $validatedData['website_name'];
        $store->mobile = $validatedData['mobile'];

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $store->image = 'images/' . $imageName;

            if ($oldImage && file_exists(public_path($oldImage))) {
                unlink(public_path($oldImage));
            }
        }
        $store->save();

        return redirect()->route('store-list')->with('success', 'store updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->route('store-list')
            ->with('success', 'store deleted successfully');
    }
}

