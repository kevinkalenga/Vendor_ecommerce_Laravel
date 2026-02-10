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

    public function EditSubCategory($id)
    {
       $categories = Category::orderBy('category_name', 'ASC')->get();
    
      //Find the specific subcategorie record or fail with 404
      $subcategory = SubCategory::findOrFail($id);

      //Return the edit view with the data
      return view('backend.subcategory.subcategory_edit', compact('categories', 'subcategory'));
    }

  public function UpdateSubCategory(Request $request, $id)
  {
    // Validation des données envoyées
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'subcategory_name' => 'required|string|max:255',
    ]);

    // Récupérer la sous-catégorie existante
    $subcategory = SubCategory::findOrFail($id);

    // Récupérer le nom de la catégorie sélectionnée
    $category = Category::findOrFail($request->category_id);

    // Mise à jour des champs
    $subcategory->update([
        'category_id' => $category->id,
        'subcategory_name' => $request->subcategory_name,
        'category_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
    ]);

    // Notification succès
    $notification = [
        'message' => 'SubCategory updated successfully!',
        'alert-type' => 'success',
    ];

    // Redirection
    return redirect()->route('all.subcategory')->with($notification);
  }

   
  public function DeleteSubCategory($id)
  {
    // Récupérer la sous-catégorie ou renvoyer une 404 si introuvable
    $subcategory = SubCategory::findOrFail($id);

    // Supprimer la sous-catégorie
    $subcategory->delete();

    // Notification succès
    $notification = [
        'message' => 'SubCategory deleted successfully!',
        'alert-type' => 'success'
    ];

    // Redirection vers la liste
    return redirect()->route('all.subcategory')->with($notification);
  }




}
