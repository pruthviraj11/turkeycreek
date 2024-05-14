<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
                $number_of_sub_cat = Category::with('subcategory')->where('parent_id', $category->id)->count();

                if ($number_of_sub_cat > 0) {
                    $category->has_child = true;
                } else {
                    $category->has_child = false;
                }
            }
        } else {
            $categories = Category::with('subcategory')->where('parent_id', $id)->get();

        }


        if (count($categories) > 0) {
            return response()->json(['status' => 200, 'message' => count($categories) . ' records found!', 'data' => $categories], 200);
        } else {
            return response()->json(['status' => 200, 'message' => 'No records found!', 'data' => $categories], 200);
        }

    }

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
            // Create the category with the slug
            Category::create([
                'name' => $request->name,
                'slug' => $slug,
                'parent_id' => $request->parent_id,
                'status' => $request->status,
            ]);

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
                'status' => 'required|in:Active,InActive'
            ]);
        } else {
            $validatedData = $this->validate($request, [
                'name' => 'required|min:3|max:255|string'
            ]);
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
}
