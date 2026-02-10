<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Category;

class CategoryController extends Controller
{
    public function AllCategory()
    {
        $categories = Category::latest()->get();
        return view('backend.category.category_all', compact('categories'));
    }
    public function AddCategory()
    {
        return view('backend.category.category_add');
    }


    public function StoreCategory(Request $request)
    {
      // Validation des données envoyées par le formulaire
      $request->validate([
        'category_name'  => 'required|string|max:255', // Nom de la marque obligatoire
        'category_image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Image valide requise
      ]);

    try {
        // Récupération de l’image uploadée
        $image = $request->file('category_image');

        // Génération d’un nom unique pour l’image
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

        // Définition du chemin de sauvegarde
        $uploadPath = public_path('upload/category');

        // Création du dossier s’il n’existe pas
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        // Initialisation du gestionnaire Intervention Image (driver GD)
        $manager = new ImageManager(new Driver());

        // Lecture, redimensionnement et sauvegarde de l’image
        $manager->read($image)
            ->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio(); // Conserver le ratio de l’image
                $constraint->upsize();      // Éviter l’agrandissement excessif
            })
            ->save($uploadPath . '/' . $name_gen);

        // Enregistrement des données dans la base de données
        Category::create([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
            'category_image' => 'upload/category/' . $name_gen,
        ]);

        
        $notification = array(
           'message' => 'Category Data Inserted Successfully!',
           'alert-type' => 'success'
        );


       return redirect()->route('all.category')->with($notification);
    
    } catch (\Exception $e) {
        // Gestion des erreurs
        return back()->withErrors([
            'error' => $e->getMessage(),
        ])->withInput();
    }
   }
}
