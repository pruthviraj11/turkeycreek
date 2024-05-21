<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subcategory')->whereNull('parent_id')->get();
        return view('users/Category/index')->with([
            'categories' => $categories
        ]);
    }

    public function api_parent($id = '')
{
    if ($id == '') {
        $categories = Category::with('parent')->whereNull('parent_id')->get();
        foreach ($categories as $category) {
            // Set the categoriesimage path using asset and Storage::url
            $category->categoriesimage = asset(Storage::url($category->categoriesimage));

            // Check if the category has subcategories
            $number_of_sub_cat = Category::with('subcategory')->where('parent_id', $category->id)->count();
            $category->has_child = $number_of_sub_cat > 0;
        }
    } else {
        $categories = Category::with('subcategory')->where('parent_id', $id)->get();

        foreach ($categories as $category) {
            // Set the categoriesimage path using asset and Storage::url
            $category->categoriesimage = asset(Storage::url($category->categoriesimage));
        }
    }

    if (count($categories) > 0) {
        return response()->json(['status' => 200, 'message' => count($categories) . ' records found!', 'data' => $categories], 200);
    } else {
        return response()->json(['status' => 200, 'message' => 'No records found!', 'data' => $categories], 200);
    }
}

    // public function api_parent($id = '')
    // {
    //     if ($id == '') {
    //         $categories = Category::with('parent')->whereNull('parent_id')->get();
    //         foreach ($categories as $category) {
    //             $number_of_sub_cat = Category::with('subcategory')->where('parent_id', $category->id)->count();

    //             if ($number_of_sub_cat > 0) {
    //                 $category->has_child = true;
    //             } else {
    //                 $category->has_child = false;
    //             }
    //         }
    //     } else {
    //         $categories = Category::with('subcategory')->where('parent_id', $id)->get();

    //     }

    //     if (count($categories) > 0) {
    //         return response()->json(['status' => 200, 'message' => count($categories) . ' records found!', 'data' => $categories], 200);
    //     } else {
    //         return response()->json(['status' => 200, 'message' => 'No records found!', 'data' => $categories], 200);
    //     }

    // }

    public function createCategory(Request $request)
    {
        $data['form_title'] = "Create Category";
        $Store = null;
        $categories = Category::with('subcategory')->whereNull('parent_id')->get();

        $category = new Category();  // Create a new Category instance or fetch it from the database

        return view('users/Category/create-edit', compact('data', 'Store', 'categories', 'category'));
    }



    public function store(Request $request)
    {
        if ($request->method() == 'POST') {
            $validator = $request->validate([
                'name' => 'required|unique:categories',
                'parent_id' => 'nullable|numeric',
                'status' => 'required|in:Active,InActive',
            ]);

            // Create a slug from the 'name' input
            $slug = str::slug($request->name, '-');

            // Check for any existing slugs and make it unique if needed
            $count = Category::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            // dd($request->categoriesimage);
            // Create the category with the slug
            $validatedData = $request->only(['name', 'parent_id', 'status']);
            $validatedData['slug'] = $slug;

            // Handle file upload
            if ($request->hasFile('categoriesimage')) {
                // Store the image in the specified directory
                $image = $request->file('categoriesimage');
                $directory = 'public/images/CategoryImage/';
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(storage_path('app/' . $directory), $imageName);
                // Set the image path in the validated data
                $validatedData['categoriesimage'] = 'images/CategoryImage/' . $imageName;
            }

            Category::create($validatedData);

            return redirect('category')->with('success', 'Category has been created successfully.');
        }
    }


    public function edit($Category)
    {
        $data['form_title'] = "Edit Category";
        $category = Category::findOrFail($Category);
        $categories = Category::with('subcategory')->whereNull('parent_id')->get();

        return view('users/Category/create-edit', compact('category', 'data', 'categories'));
    }



    public function update(Request $request, Category $category)
    {
        if ($category->parent_id) {
            $validatedData = $this->validate($request, [
                'name' => 'required|min:3|max:255|string',
                'parent_id' => 'nullable|numeric',
                'status' => 'required|in:Active,InActive',
                'categoriesimage' => 'nullable'
            ]);
        } else {
            $validatedData = $this->validate($request, [
                'name' => 'required|min:3|max:255|string'
            ]);
        }

        $slug = str::slug($request->name, '-');

        // Check for any existing slugs and make it unique if needed
        $count = Category::where('slug', $slug)->where('id', '!=', $category->id)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        $validatedData['slug'] = $slug;

        // Handle file upload
        if ($request->hasFile('categoriesimage')) {
            // Delete the old image if it exists
            if ($category->categoriesimage) {
                $oldImagePath = storage_path('app/public/' . $category->categoriesimage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Store the new image in the specified directory
            $image = $request->file('categoriesimage');
            $directory = 'public/images/CategoryImage/';
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(storage_path('app/' . $directory), $imageName);

            // Set the new image path in the validated data
            $validatedData['categoriesimage'] = 'images/CategoryImage/' . $imageName;
        }

        $category->update($validatedData);

        return redirect()->route('category')->withSuccess('You have successfully updated a Category!');
    }


    public function destroy(Category $category)
    {
        if ($category->subcategory) {
            $category->subcategory()->delete();
        }

        $category->delete();

        return redirect()->route('category')->withSuccess('You have successfully deleted a Category!');
    }

    public function removeCategoryPhoto($encryptedId)
    {
        // Decrypt the encrypted ID
        $id = decrypt($encryptedId);

        // Retrieve the category by ID
        $category = Category::find($id);

        // Check if the category exists
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found');
        }

        // Store the photo path for deletion
        $photoPath = $category->categoriesimage;

        // Delete the photo from storage if it exists
        if ($photoPath && Storage::exists($photoPath)) {
            Storage::delete($photoPath);
        }

        // Update the category image field to an empty string
        $category->categoriesimage = '';
        $updated = $category->save();

        // Check if the update was successful
        if ($updated) {
            return redirect()->back()->with('success', 'Photo deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Error while deleting photo');
        }
    }

}
