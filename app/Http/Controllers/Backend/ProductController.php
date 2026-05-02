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

class ProductController extends Controller
{
    public function AllProduct()
    {
        $products = Product::latest()->get();
        return view('backend.product.product_all', compact('products'));
    }


    public function AddProduct()
    {
       $activeVendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get();
       $brands = Brand::latest()->get();
       $categories = Category::latest()->get();
      return view('backend.product.product_add', compact('brands', 'categories', 'activeVendor'));
    }
    

    public function StoreProduct(Request $request)
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

                'vendor_id' => $request->vendor_id,
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
                ->route('all.product')
                ->with([
                    'message' => 'Product Data Inserted Successfully!',
                    'alert-type' => 'success'
                ]);

        } catch (\Exception $e) {

            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function EditProduct($id)
    {
        // $activeVendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get();
        // $brands = Brand::latest()->get();
        // $categories = Category::latest()->get();
        // $subcategory = SubCategory::latest()->get();
        // $products = Product::findOrFail($id);
        // $multiImgs = MultiImg::where('product_id', $id)->get();
        // return view('backend.product.product_edit', compact('activeVendor', 'brands', 'categories', 'products', 'subcategory', 'multiImgs'));

 
            $activeVendor = User::where('status', 'active')
                ->where('role', 'vendor')
                ->latest()
                ->get();

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
                'backend.product.product_edit',
                compact(
                    'activeVendor',
                    'brands',
                    'categories',
                    'products',
                    'subcategory',
                    'multiImgs'
                )
            );

    }
}
