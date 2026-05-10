<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BannerController extends Controller
{
    public function AllBanner()
    {
        $banners = Banner::latest()->get();
        return view('backend.banner.banner_all', compact('banners'));
    }

    public function AddBanner()
    {
        return view('backend.banner.banner_add');
    }

    public function StoreBanner(Request $request)
    {
        // Validation des données envoyées par le formulaire
        $request->validate([
            'banner_title'  => 'required|string|max:255', 
            'banner_url'  => 'required|string|max:255', 
            'banner_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // Image valide requise
        ]);

        try {
            // Récupération de l’image uploadée
            $image = $request->file('banner_image');

            // Génération d’un nom unique pour l’image
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Définition du chemin de sauvegarde
            $uploadPath = public_path('upload/banner');

            // Création du dossier s’il n’existe pas
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            // Initialisation du gestionnaire Intervention Image (driver GD)
            $manager = new ImageManager(new Driver());

            // Lecture, redimensionnement et sauvegarde de l’image
            $manager->read($image)
                ->resize(768, 450, function ($constraint) {
                    $constraint->aspectRatio(); // Conserver le ratio de l’image
                    $constraint->upsize();      // Éviter l’agrandissement excessif
                })
                ->save($uploadPath . '/' . $name_gen);

            // Enregistrement des données dans la base de données
            Banner::create([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,
                'banner_image' => 'upload/banner/' . $name_gen,
            ]);

            
            $notification = array(
            'message' => 'Banner Data Inserted Successfully!',
            'alert-type' => 'success'
            );


        return redirect()->route('all.banner')->with($notification);
        
        } catch (\Exception $e) {
            // Gestion des erreurs
            return back()->withErrors([
                'error' => $e->getMessage(),
            ])->withInput();
        }
    }
}
