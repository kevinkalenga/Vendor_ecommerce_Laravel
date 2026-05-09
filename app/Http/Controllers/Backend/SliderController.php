<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Slider;

class SliderController extends Controller
{
    public function AllSlider()
    {
        $sliders = Slider::latest()->get();
        return view('backend.slider.slider_all', compact('sliders'));
    }

    public function AddSlider()
    {
        return view('backend.slider.slider_add');
    }

    public function StoreSlider(Request $request)
    {
        // Validation des données envoyées par le formulaire
        $request->validate([
            'slider_title'  => 'required|string|max:255', 
            'short_title'  => 'required|string|max:255', 
            'slider_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // Image valide requise
        ]);

        try {
            // Récupération de l’image uploadée
            $image = $request->file('slider_image');

            // Génération d’un nom unique pour l’image
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Définition du chemin de sauvegarde
            $uploadPath = public_path('upload/slider');

            // Création du dossier s’il n’existe pas
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            // Initialisation du gestionnaire Intervention Image (driver GD)
            $manager = new ImageManager(new Driver());

            // Lecture, redimensionnement et sauvegarde de l’image
            $manager->read($image)
                ->resize(2376, 807, function ($constraint) {
                    $constraint->aspectRatio(); // Conserver le ratio de l’image
                    $constraint->upsize();      // Éviter l’agrandissement excessif
                })
                ->save($uploadPath . '/' . $name_gen);

            // Enregistrement des données dans la base de données
            Slider::create([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                'slider_image' => 'upload/slider/' . $name_gen,
            ]);

            
            $notification = array(
            'message' => 'Slider Data Inserted Successfully!',
            'alert-type' => 'success'
            );


        return redirect()->route('all.slider')->with($notification);
        
        } catch (\Exception $e) {
            // Gestion des erreurs
            return back()->withErrors([
                'error' => $e->getMessage(),
            ])->withInput();
        }
    }


}
