<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class BrandController extends Controller
{
    public function AllBrand()
    {
      $brands = Brand::latest()->get();
      return view('backend.brand.brand_all', compact('brands'));
    }
    public function AddBrand()
    {
       return view('backend.brand.brand_add');
    }
   
  public function StoreBrand(Request $request)
  {
    // Validation des données envoyées par le formulaire
    $request->validate([
        'brand_name'  => 'required|string|max:255', // Nom de la marque obligatoire
        'brand_image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Image valide requise
    ]);

    try {
        // Récupération de l’image uploadée
        $image = $request->file('brand_image');

        // Génération d’un nom unique pour l’image
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

        // Définition du chemin de sauvegarde
        $uploadPath = public_path('upload/brand');

        // Création du dossier s’il n’existe pas
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        // Initialisation du gestionnaire Intervention Image (driver GD)
        $manager = new ImageManager(new Driver());

        // Lecture, redimensionnement et sauvegarde de l’image
        $manager->read($image)
            ->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio(); // Conserver le ratio de l’image
                $constraint->upsize();      // Éviter l’agrandissement excessif
            })
            ->save($uploadPath . '/' . $name_gen);

        // Enregistrement des données dans la base de données
        Brand::create([
            'brand_name' => $request->brand_name,
            'brand_slug' => strtolower(str_replace(' ', '-', $request->brand_name)),
            'brand_image' => 'upload/brand/' . $name_gen,
        ]);

        
        $notification = array(
           'message' => 'Brand Data Inserted Successfully!',
           'alert-type' => 'success'
        );


       return redirect()->route('all.brand')->with($notification);
        
        
        
        
        // Redirection avec message de succès
        // return redirect()->route('brand.all')->with([
        //     'message' => 'Brand Data Inserted Successfully!',
        //     'alert-type' => 'success',
        // ]);

    } catch (\Exception $e) {
        // Gestion des erreurs
        return back()->withErrors([
            'error' => $e->getMessage(),
        ])->withInput();
    }
  }

}
