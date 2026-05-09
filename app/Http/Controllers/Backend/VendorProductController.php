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
}
