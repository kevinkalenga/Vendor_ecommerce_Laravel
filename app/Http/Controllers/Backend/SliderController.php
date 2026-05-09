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

    public function EditSlider($id)
    {
      //Find the specific brand record or fail with 404
      $slider = Slider::findOrFail($id);


      //Return the edit view with the brand data
      return view('backend.slider.slider_edit', compact('slider'));
    }


    public function UpdateSlider(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'slider_title'  => 'required|string|max:255', 
            'short_title'  => 'required|string|max:255', 
            'slider_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Image facultative
        ]);

        try {
            // Récupération de la marque à modifier
            $slider = Slider::findOrFail($id);

            // Mise à jour du nom de la marque
            $slider->slider_title = $request->slider_title;
            $slider->short_title = $request->short_title;

            // Vérifier si une nouvelle image est envoyée
            if ($request->hasFile('slider_image')) {

                // Supprimer l’ancienne image si elle existe
                if ($slider->slider_image && file_exists(public_path($slider->slider_image))) {
                    unlink(public_path($slider->slider_image));
                }

                // Traitement de la nouvelle image
                $image = $request->file('slider_image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                $uploadPath = public_path('upload/slider');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }

                // Redimensionnement et sauvegarde avec Intervention Image
                $manager = new ImageManager(new Driver());
                $manager->read($image)
                    ->resize(2376, 807, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($uploadPath . '/' . $name_gen);

                // Mise à jour du chemin de l’image
                $slider->slider_image = 'upload/slider/' . $name_gen;
            }

            // Sauvegarde en base de données
            $slider->save();

            // Redirection avec message de succès
            $notification = array(
            'message' => 'Slider Data Updated Successfully!',
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

    public function DeleteSlider($id)
    {
        try {
            // Récupération de la marque
            $slider = Slider::findOrFail($id);

            // Vérifier et supprimer l’image du dossier public
            if ($slider->slider_image && file_exists(public_path($slider->slider_image))) {
                unlink(public_path($slider->slider_image));
            }

            // Supprimer la marque de la base deslider
            $slider->delete();

            // Redirection avec message de succès
            $notification = array(
            'message' => 'Slider Data Deleted Successfully!',
            'alert-type' => 'success'
            );


        return redirect()->route('all.slider')->with($notification);

        } catch (\Exception $e) {
            // Gestion des erreurs
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }




}
