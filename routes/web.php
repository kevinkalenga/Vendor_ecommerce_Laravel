<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubCategoryController;

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

 Route::get('/admin/login', [AdminController::class, 'AdminLogin']);

// Vendor Dashboard
Route::middleware(['auth', 'role:vendor'])->group(function(){
    Route::get('/vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
    Route::get('/vendor/logout', [VendorController::class, 'VendorLogout'])->name('vendor.logout');
    Route::get('/vendor/profile', [VendorController::class, 'VendorProfile'])->name('vendor.profile');
    Route::post('/vendor/profile/store', [VendorController::class, 'VendorProfileStore'])->name('vendor.profile.store');
    Route::get('/vendor/change/password', [VendorController::class, 'VendorChangePassword'])->name('vendor.change.profile');
    Route::post('/vendor/password/update', [VendorController::class, 'VendorPasswordUpdate'])->name('vendor.password.update');
});

Route::get('/vendor/login', [VendorController::class, 'VendorLogin']);

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
    Route::get('/edit/brand/{id}', 'EditCategory')->name('edit.category');
    Route::post('/update/category/{id}', 'UpdateCategory')->name('update.category');
    Route::get('/delete/category/{id}', 'DeleteCategory')->name('delete.category');
  });
  // All SubCategory

  Route::controller(SubCategoryController::class)->group(function(){
    Route::get('/all/subcategory', 'AllSubCategory')->name('all.subcategory');
  
  });



});




require __DIR__.'/auth.php';
