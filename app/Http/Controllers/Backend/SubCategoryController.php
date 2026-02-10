<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;

class SubCategoryController extends Controller
{
    
    public function AllSubCategory()
    {
        $subcategories = SubCategory::latest()->get();
        return view('backend.subcategory.subcategory_all', compact('subcategories'));
    }

    public function AddSubCategory()
    {
        // Get all categories
        $categories = Category::orderBy('category_name', 'ASC')->get();
        return view('backend.subcategory.subcategory_add', compact('categories'));
    }

  

    
    public function StoreSubCategory(Request $request)
    {
       // Validation
        $request->validate([
          'category_id'      => 'required|exists:categories,id',
          'subcategory_name' => 'required|string|max:255',
       ]);

       $category = Category::findOrFail($request->category_id);


       SubCategory::create([
         'category_id' => $category->id,
         'subcategory_name' => $request->subcategory_name,
         'category_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
       ]);


         $notification = array(
           'message' => 'SubCategory Data Inserted Successfully!',
           'alert-type' => 'success'
        );


       return redirect()->route('all.subcategory')->with($notification);

    
    }


}
