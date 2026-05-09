<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MultiImg;
use App\Models\Brand;
use App\Models\User;
use Auth;

class VendorProductController extends Controller
{
    public function VendorAllProduct()
    {
        // Which user is logged in
        $id = Auth::user()->id;
        
        $products = Product::where('vendor_id', $id)->latest()->get();
        return view('vendor.backend.product.vendor_product_all', compact('products'));
    }

    public function VendorAddProduct()
    {
     
       $brands = Brand::latest()->get();
       $categories = Category::latest()->get();
      return view('vendor.backend.product.vendor_product_add', compact('brands', 'categories'));
    }

 

    public function VendorGetSubCategory($category_id){
        $subcat = SubCategory::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();
            // return json_encode($subcat);
        return response()->json($subcat);

    }


    public function VendorStoreProduct(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_thambnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {

            $image = $request->file('product_thambnail');

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $uploadPath = public_path('upload/products/thambnail');

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0775, true);
            }

            $manager = new ImageManager(new Driver());

            $manager->read($image)
                ->resize(800, 800)
                ->save($uploadPath.'/'.$name_gen);

           $product = Product::create([

                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,

                'product_name' => $request->product_name,
                'product_slug' => Str::slug($request->product_name),

                'product_code' => $request->product_code,
                'product_qty' => $request->product_qty,

                'product_tags' => $request->product_tags,
                'product_size' => $request->product_size,
                'product_color' => $request->product_color,

                'selling_price' => $request->selling_price,
                'discount_price' => $request->discount_price,

                'short_descp' => $request->short_descp,
                'long_descp' => $request->long_descp,

                'hot_deals' => $request->hot_deals ?? 0,
                'featured' => $request->featured ?? 0,
                'special_offer' => $request->special_offer ?? 0,
                'special_deals' => $request->special_deals ?? 0,

                'product_thambnail' => 'upload/products/thambnail/'.$name_gen,

                'vendor_id' => Auth::user()->id,
                'status' => 1,
            ]);

            // Multiple Image Upload Here

            if ($request->hasFile('multi_img')) {

                $manager = new ImageManager(new Driver());
                $uploadPath = public_path('upload/products/multi-image');

                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true);
                }

                foreach ($request->file('multi_img') as $img) {

                    $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();

                    $manager->read($img)
                        ->resize(800, 800)
                        ->save($uploadPath . '/' . $make_name);

                    MultiImg::create([
                        'product_id' => $product->id,
                        'photo_name' => 'upload/products/multi-image/' . $make_name,
                    ]);
                }
            }
           
            return redirect()
                ->route('vendor.all.product')
                ->with([
                    'message' => 'Vendor Product Inserted Successfully!',
                    'alert-type' => 'success'
                ]);

        } catch (\Exception $e) {

            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }


    public function VendorEditProduct($id)
    {
       
            $brands = Brand::latest()->get();

            $categories = Category::latest()->get();

            $products = Product::findOrFail($id);

            $subcategory = SubCategory::where(
                'category_id',
                $products->category_id
            )->get();

            $multiImgs = MultiImg::where(
                'product_id',
                $id
            )->get();

            return view(
                'vendor.backend.product.vendor_product_edit',
                compact(
                    
                    'brands',
                    'categories',
                    'products',
                    'subcategory',
                    'multiImgs'
                )
            );

    }


    // public function VendorUpdateProductThambnail(Request $request)
    // {
    //         $product = Product::findOrFail($request->id);
           

           



    //         /*
    //         ===========================
    //         DELETE ONE IMAGE
    //         ===========================
    //         */

    //         if($request->delete_img){

    //             $oldImg = MultiImg::find($request->delete_img);

    //             if($oldImg){

    //                 if(file_exists(public_path($oldImg->photo_name))){
    //                     unlink(public_path($oldImg->photo_name));
    //                 }

    //                 $oldImg->delete();
    //             }

             
    //         }



    //         /*
    //         ===========================
    //         UPDATE ONE IMAGE
    //         ===========================
    //         */

             
    //         if($request->update_img){

    //             if($request->hasFile('multi_img')){

    //                 $id = $request->update_img;

    //                 $img = $request->multi_img[$id] ?? null;

    //                 if($img){

    //                     $oldImg = MultiImg::find($id);

    //                     if($oldImg){

    //                         if(file_exists(public_path($oldImg->photo_name))){
    //                             unlink(public_path($oldImg->photo_name));
    //                         }

    //                         $name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();

    //                         $path = public_path('upload/products/multi-image');

    //                         $manager = new ImageManager(new Driver());

    //                         $manager->read($img)
    //                             ->resize(800,800)
    //                             ->save($path.'/'.$name);

    //                         $oldImg->update([
    //                             'photo_name' => 'upload/products/multi-image/'.$name
    //                         ]);
    //                     }
    //                 }
    //             }

    //         //    return back();
    //         }
                                        
                                        
                                        
                                        
            
    //         try {

    //             $save_url = $product->product_thambnail;


    //             /*
    //             ===========================
    //             THUMBNAIL UPDATE
    //             ===========================
    //             */
    //             if ($request->hasFile('product_thambnail')) {

    //                 if (file_exists(public_path($product->product_thambnail))) {

    //                     unlink(public_path($product->product_thambnail));
    //                 }

    //                 $image = $request->file('product_thambnail');

    //                 $name_gen = hexdec(uniqid()) . '.' .
    //                     $image->getClientOriginalExtension();

    //                 $uploadPath = public_path('upload/products/thambnail');

    //                 if (!File::exists($uploadPath)) {

    //                     File::makeDirectory($uploadPath, 0775, true);
    //                 }

    //                 $manager = new ImageManager(new Driver());

    //                 $manager->read($image)
    //                     ->resize(800, 800)
    //                     ->save($uploadPath . '/' . $name_gen);

    //                 $save_url =
    //                     'upload/products/thambnail/' . $name_gen;
    //             }



    //             /*
    //             ===========================
    //             PRODUCT UPDATE
    //             ===========================
    //             */

    //             $product->update([

    //                 'brand_id' => $request->brand_id,
    //                 'category_id' => $request->category_id,
    //                 'subcategory_id' => $request->subcategory_id,

    //                 'product_name' => $request->product_name,
    //                 'product_slug' => Str::slug($request->product_name),

    //                 'product_code' => $request->product_code,
    //                 'product_qty' => $request->product_qty,

    //                 'product_tags' => $request->product_tags,
    //                 'product_size' => $request->product_size,
    //                 'product_color' => $request->product_color,

    //                 'selling_price' => $request->selling_price,
    //                 'discount_price' => $request->discount_price,

    //                 'short_descp' => $request->short_descp,
    //                 'long_descp' => $request->long_descp,

    //                 'hot_deals' => $request->hot_deals ?? 0,
    //                 'featured' => $request->featured ?? 0,
    //                 'special_offer' => $request->special_offer ?? 0,
    //                 'special_deals' => $request->special_deals ?? 0,


    //                 'product_thambnail' => $save_url,

    //                 'status' => 1,

    //             ]);



    //             /*
    //             ===========================
    //             MULTI IMAGE UPDATE
    //             ===========================
    //             */

    //             if ($request->hasFile('multi_img')) {

    //                 $manager = new ImageManager(new Driver());

    //                 $uploadPath = public_path('upload/products/multi-image');

    //                 if (!File::exists($uploadPath)) {
    //                     File::makeDirectory($uploadPath, 0775, true);
    //                 }

    //                 foreach ($request->file('multi_img') as $id => $img) {

    //                     // CAS 1 : UPDATE image existante
    //                     if (is_numeric($id)) {

    //                         $oldImg = MultiImg::find($id);

    //                         if ($oldImg) {

    //                             if (file_exists(public_path($oldImg->photo_name))) {
    //                                 unlink(public_path($oldImg->photo_name));
    //                             }

    //                             $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();

    //                             $manager->read($img)
    //                                 ->resize(800, 800)
    //                                 ->save($uploadPath . '/' . $make_name);

    //                             $oldImg->update([
    //                                 'photo_name' => 'upload/products/multi-image/' . $make_name
    //                             ]);
    //                         }
    //                     }

    //                     // CAS 2 : NOUVELLE image (pas d'ID valide)
    //                     else {

    //                         $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();

    //                         $manager->read($img)
    //                             ->resize(800, 800)
    //                             ->save($uploadPath . '/' . $make_name);

    //                         MultiImg::create([
    //                             'product_id' => $product->id,
    //                             'photo_name' => 'upload/products/multi-image/' . $make_name,
    //                         ]);
    //                     }
    //                 }
    //             }



    //             return redirect()
    //                 ->route('vendor.all.product')
    //                 ->with([

    //                     'message' =>
    //                         'Vendor Product Updated Successfully!',

    //                     'alert-type' => 'success'

    //                 ]);

    //         } catch (\Exception $e) {

    //             return back()
    //                 ->withErrors([

    //                     'error' => $e->getMessage()

    //                 ])
    //                 ->withInput();
    //         }
    // }


    public function VendorUpdateProductThambnail(Request $request)
    {
      $product = Product::findOrFail($request->id);

     try {

        /*
        ===========================
        DELETE ONE IMAGE
        ===========================
        */
        if ($request->delete_img) {

            $oldImg = MultiImg::find($request->delete_img);

            if ($oldImg) {

                if (file_exists(public_path($oldImg->photo_name))) {
                    unlink(public_path($oldImg->photo_name));
                }

                $oldImg->delete();
            }
        }


        /*
        ===========================
        UPDATE SINGLE IMAGE
        ===========================
        */
        if ($request->hasFile('multi_img') && $request->update_img) {

            $img = $request->file('multi_img');

            $oldImg = MultiImg::find($request->update_img);

            if ($oldImg && $img) {

                if (file_exists(public_path($oldImg->photo_name))) {
                    unlink(public_path($oldImg->photo_name));
                }

                $name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
                $path = public_path('upload/products/multi-image');

                if (!file_exists($path)) {
                    mkdir($path, 0775, true);
                }

                $manager = new ImageManager(new Driver());

                $manager->read($img)
                    ->resize(800, 800)
                    ->save($path . '/' . $name);

                $oldImg->update([
                    'photo_name' => 'upload/products/multi-image/' . $name
                ]);
            }
        }


        /*
        ===========================
        THUMBNAIL UPDATE
        ===========================
        */
        $save_url = $product->product_thambnail;

        if ($request->hasFile('product_thambnail')) {

            if (file_exists(public_path($product->product_thambnail))) {
                unlink(public_path($product->product_thambnail));
            }

            $image = $request->file('product_thambnail');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $uploadPath = public_path('upload/products/thambnail');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            $manager = new ImageManager(new Driver());

            $manager->read($image)
                ->resize(800, 800)
                ->save($uploadPath . '/' . $name_gen);

            $save_url = 'upload/products/thambnail/' . $name_gen;
        }


        /*
        ===========================
        PRODUCT UPDATE
        ===========================
        */
        $product->update([

            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,

            'product_name' => $request->product_name,
            'product_slug' => Str::slug($request->product_name),

            'product_code' => $request->product_code,
            'product_qty' => $request->product_qty,

            'product_tags' => $request->product_tags,
            'product_size' => $request->product_size,
            'product_color' => $request->product_color,

            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,

            'short_descp' => $request->short_descp,
            'long_descp' => $request->long_descp,

            'hot_deals' => $request->hot_deals ?? 0,
            'featured' => $request->featured ?? 0,
            'special_offer' => $request->special_offer ?? 0,
            'special_deals' => $request->special_deals ?? 0,

            'product_thambnail' => $save_url,

            'status' => 1,
        ]);


        /*
        ===========================
        MULTI IMAGE UPDATE
        ===========================
        */
        if ($request->hasFile('multi_img')) {

            $manager = new ImageManager(new Driver());
            $uploadPath = public_path('upload/products/multi-image');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            foreach ($request->file('multi_img') as $key => $img) {

                if (is_numeric($key)) {

                    $oldImg = MultiImg::find($key);

                    if ($oldImg) {

                        if (file_exists(public_path($oldImg->photo_name))) {
                            unlink(public_path($oldImg->photo_name));
                        }

                        $name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();

                        $manager->read($img)
                            ->resize(800, 800)
                            ->save($uploadPath . '/' . $name);

                        $oldImg->update([
                            'photo_name' => 'upload/products/multi-image/' . $name
                        ]);
                    }
                } else {

                    $name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();

                    $manager->read($img)
                        ->resize(800, 800)
                        ->save($uploadPath . '/' . $name);

                    MultiImg::create([
                        'product_id' => $product->id,
                        'photo_name' => 'upload/products/multi-image/' . $name,
                    ]);
                }
            }
        }

        return redirect()
            ->route('vendor.all.product')
            ->with([
                'message' => 'Vendor Product Updated Successfully!',
                'alert-type' => 'success'
            ]);

     } catch (\Exception $e) {

        return back()
            ->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
     }
   }


    public function VendorUpdateSingleImage(Request $request)
    {
        $request->validate([
            'img_id' => 'required',
            'image' => 'required|image'
        ]);

        $img = MultiImg::findOrFail($request->img_id);

        if ($request->hasFile('image')) {

            // delete old image
            if (file_exists(public_path($img->photo_name))) {
                unlink(public_path($img->photo_name));
            }

            $file = $request->file('image');
            $name = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();

            $path = public_path('upload/products/multi-image');

            if (!file_exists($path)) {
                mkdir($path, 0775, true);
            }

            $file = $request->file('image');

            $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $file->move($path, $name);

            $img->update([
                'photo_name' => 'upload/products/multi-image/'.$name
            ]);
        }

          return redirect()
                    ->route('vendor.all.product')
                    ->with([

                         'message' => 'Image Updated Successfully!',
                         'alert-type' => 'success'

                    ]);

       
    }

}
