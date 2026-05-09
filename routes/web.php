<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\ProductController;

use App\Http\Controllers\Backend\VendorProductController;

Route::get('/', function () {
    return view('frontend.index');
});

Route::middleware(['auth'])->group(function(){
  Route::get('/dashboard', [UserController::class, 'UserDashboard'])->name('dashboard');
  Route::get('/user/logout', [UserController::class, 'UserLogout'])->name('user.logout');
  Route::post('/user/profile/store', [UserController::class, 'UserProfileStore'])->name('user.profile.store');
   Route::post('/user/password/update', [UserController::class, 'UserPasswordUpdate'])->name('user.password.update');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


// Admin Dashboard

Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.profile');
    Route::post('/admin/password/update', [AdminController::class, 'AdminPasswordUpdate'])->name('admin.password.update');
});

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'AdminLogin']);
});

// Vendor Dashboard
Route::middleware(['auth', 'role:vendor'])->group(function(){
    Route::get('/vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
    Route::get('/vendor/logout', [VendorController::class, 'VendorLogout'])->name('vendor.logout');
    Route::get('/vendor/profile', [VendorController::class, 'VendorProfile'])->name('vendor.profile');
    Route::post('/vendor/profile/store', [VendorController::class, 'VendorProfileStore'])->name('vendor.profile.store');
    Route::get('/vendor/change/password', [VendorController::class, 'VendorChangePassword'])->name('vendor.change.profile');
    Route::post('/vendor/password/update', [VendorController::class, 'VendorPasswordUpdate'])->name('vendor.password.update');

    // Vendor All Product Routes
    Route::controller(VendorProductController::class)->group(function(){
      Route::get('/vendor/all/product', 'VendorAllProduct')->name('vendor.all.product');
      Route::get('/vendor/add/product', 'VendorAddProduct')->name('vendor.add.product');
      Route::post('/vendor/store/product', 'VendorStoreProduct')->name('vendor.store.product');
      Route::get('/vendor/edit/product/{id}', 'VendorEditProduct')->name('vendor.edit.product');
      Route::post('/vendor/update/product/thambnail/{id}', 'VendorUpdateProductThambnail')->name('vendor.update.product.thambnail');
      Route::post('/vendor/update/single/image', 'VendorUpdateSingleImage')->name('vendor.update.single.image');
      Route::get('/vendor/delete/single/image/{id}', 'VendorDeleteSingleImage')->name('vendor.delete.single.image');
      Route::get('/vendor/product/inactive/{id}', 'VendorProductInactive')->name('vendor.product.inactive');
      Route::get('/vendor/product/active/{id}', 'VendorProductActive')->name('vendor.product.active');
      Route::get('/vendor/delete/product/{id}', 'VendorDeleteProduct')->name('vendor.delete.product');
      Route::get('/vendor/subcategory/ajax/{category_id}' , 'VendorGetSubCategory');

     
    
    });
});

Route::middleware('guest')->group(function () {
  Route::get('/vendor/login', [VendorController::class, 'VendorLogin'])->name('vendor.login');
});
Route::get('/become/vendor', [VendorController::class, 'BecomeVendor'])->name('become.vendor');
Route::post('/vendor/register', [VendorController::class, 'VendorRegister'])->name('vendor.register');

// Brand will be accessible when the role willbe admin
Route::middleware(['auth', 'role:admin'])->group(function(){
// Brand and BrandController will manage all the routes inside
  Route::controller(BrandController::class)->group(function(){
    Route::get('/all/brand', 'AllBrand')->name('all.brand');
    Route::get('/add/brand', 'AddBrand')->name('add.brand');
    Route::post('/store/brand', 'StoreBrand')->name('store.brand');
    Route::get('/edit/brand/{id}', 'EditBrand')->name('edit.brand');
    Route::post('/update/brand/{id}', 'UpdateBrand')->name('update.brand');
    Route::get('/delete/brand/{id}', 'DeleteBrand')->name('delete.brand');
  });

  // All Category

  Route::controller(CategoryController::class)->group(function(){
    Route::get('/all/category', 'AllCategory')->name('all.category');
    Route::get('/add/category', 'AddCategory')->name('add.category');
    Route::post('/store/category', 'StoreCategory')->name('store.category');
    Route::get('/edit/category/{id}', 'EditCategory')->name('edit.category');
    Route::post('/update/category/{id}', 'UpdateCategory')->name('update.category');
    Route::get('/delete/category/{id}', 'DeleteCategory')->name('delete.category');

   
  });
  // All SubCategory

  Route::controller(SubCategoryController::class)->group(function(){
    Route::get('/all/subcategory', 'AllSubCategory')->name('all.subcategory');
    Route::get('/add/subcategory', 'AddSubCategory')->name('add.subcategory');
    Route::post('/store/subcategory', 'StoreSubCategory')->name('store.subcategory');
    Route::get('/edit/subcategory/{id}', 'EditSubCategory')->name('edit.subcategory');
    Route::post('/update/subcategory/{id}', 'UpdateSubCategory')->name('update.subcategory');
    Route::get('/delete/subcategory/{id}', 'DeleteSubCategory')->name('delete.subcategory');

    Route::get('/subcategory/ajax/{category_id}' , 'GetSubCategory');
  
  });
  // Vendor Active and Inactive

  Route::controller(AdminController::class)->group(function(){
    Route::get('/inactive/vendor', 'InactiveVendor')->name('inactive.vendor');
    Route::get('/active/vendor', 'ActiveVendor')->name('active.vendor');
    Route::get('/inactive/vendor/details/{id}', 'InactiveVendorDetails')->name('inactive.vendor.details');
    Route::post('/active/vendor/approve', 'ActiveVendorApprove')->name('active.vendor.approve');
    Route::get('/active/vendor/details/{id}' , 'ActiveVendorDetails')->name('active.vendor.details');
    Route::post('/inactive/vendor/approve', 'InactiveVendorApprove')->name('inactive.vendor.approve');
    
  
  });


  //Product All Routes

  Route::controller(ProductController::class)->group(function(){
    Route::get('/all/product', 'AllProduct')->name('all.product');
    Route::get('/add/product', 'AddProduct')->name('add.product');
    Route::post('/store/product', 'StoreProduct')->name('store.product');
    Route::get('/edit/product/{id}', 'EditProduct')->name('edit.product');
    Route::post('/update/product/thambnail' , 'UpdateProductThambnail')->name('update.product.thambnail');
    Route::get('/delete/product/{id}', 'DeleteProduct')->name('delete.product');
    Route::post('/product/multi-image/update', 'UpdateSingleImage')->name('update.single.image');
    Route::get('/product/multi-image/delete/{id}','DeleteSingleImage')->name('delete.single.image');
    Route::get('/product/inactive/{id}', 'ProductInactive')->name('product.inactive');
    Route::get('/product/active/{id}', 'ProductActive')->name('product.active');
  
  });



});




require __DIR__.'/auth.php';
