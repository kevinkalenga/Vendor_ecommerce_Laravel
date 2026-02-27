<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MultiImg;
use App\Models\Brand;

class ProductController extends Controller
{
    public function AllProduct()
    {
        $products = Product::latest()->get();
        return view('backend.product.product_all', compact('products'));
    }


    public function AddProduct()
    {
    //   $propertyType = PropertyType::latest()->get();
    //   $amenities = Amenities::latest()->get();
    //   $activeAgent = User::where('status', 'active')->where('role', 'agent')->latest()->get();
      return view('backend.product.product_add');
    }
}
