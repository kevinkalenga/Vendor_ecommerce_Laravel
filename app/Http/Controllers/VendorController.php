<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class VendorController extends Controller
{
    public function VendorDashboard()
    {
        return view('vendor.index');
    }

    public function VendorLogin()
    {
        return view('vendor.vendor_login');
    }

    public function VendorLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/vendor/login');
    }

    public function VendorProfile()
    {
        // get the user who is logged in 
        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return view('vendor.vendor_profile_view', compact('vendorData'));
    }


    public function VendorProfileStore(Request $request)
    {
        // get the authenticated user id
       $id = Auth::user()->id;
       $data = User::find($id);
       $data->name = $request->name;
       $data->email = $request->email;
       $data->phone = $request->phone;
       $data->address = $request->address;
       $data->vendor_join = $request->vendor_join;
       $data->vendor_short_info = $request->vendor_short_info;

       if($request->file('photo')) {
          $file = $request->file('photo');
        //   to remove an existing img before downloading
          @unlink(public_path('upload/vendor_images/'.$data->photo));
          $filename = date('YmdHi').$file->getClientOriginalName();
          $file->move(public_path('upload/vendor_images'), $filename);
        // insert in db and field name is photo  
          $data['photo'] = $filename;
       }

       $data->save();

        $notification = array(
           'message' => 'Vendor Profile Updated Successfully',
           'alert-type' => 'success'
        );


       return redirect()->back()->with($notification);
     
    }

}
