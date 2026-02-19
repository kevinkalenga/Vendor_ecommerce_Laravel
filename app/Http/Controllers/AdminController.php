<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Hash;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        // return view('admin.admin_dashboard');
        return view('admin.index');
    }
    public function AdminLogin()
    {
        return view('admin.admin_login');
    }
    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
    public function AdminProfile()
    {
        // get the user who is logged in 
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    }

    public function AdminProfileStore(Request $request)
    {
        // get the authenticated user id
       $id = Auth::user()->id;
       $data = User::find($id);
       $data->name = $request->name;
       $data->email = $request->email;
       $data->phone = $request->phone;
       $data->address = $request->address;

       if($request->file('photo')) {
          $file = $request->file('photo');
        //   to remove an existing img before downloading
          @unlink(public_path('upload/admin_images/'.$data->photo));
          $filename = date('YmdHi').$file->getClientOriginalName();
          $file->move(public_path('upload/admin_images'), $filename);
        // insert in db and field name is photo  
          $data['photo'] = $filename;
       }

       $data->save();

        $notification = array(
           'message' => 'Admin Profile Updated Successfully',
           'alert-type' => 'success'
        );


       return redirect()->back()->with($notification);
     
    }


    public function AdminChangePassword()
    {
        // get the authenticated user id
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_change_password', compact('adminData'));
    }


    public function AdminPasswordUpdate(Request $request) 
    {
        // validation
       $request->validate([
          'old_password' => 'required',
          'new_password' => 'required|confirmed',
          
       ]);
        
      // check that old pwd and the new authenticated pwd match   
       if(!Hash::check($request->old_password, auth::user()->password)) {

            $notification = array(
              'message' => 'Old Password Does not Match!',
              'alert-type' => 'error'
            );

          return back()->with($notification);
    
    
        }

        // Update the new pwd 
        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
           'message' => 'Your Password Is Updated Successfully',
           'alert-type' => 'success'
        );

       return back()->with($notification);
    }

    public function InactiveVendor()
    {
        $inactiveVendor = User::where('status', 'inactive')->where('role', 'vendor')->latest()->get(); 
        return view('backend.vendor.inactive_vendor', compact('inactiveVendor'));
    }
    public function ActiveVendor()
    {
        $activeVendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get(); 
        return view('backend.vendor.active_vendor', compact('activeVendor'));
    }

    public function InactiveVendorDetails($id)
    {
      $inactiveVendorDetails = User::findOrFail($id);
      return view('backend.vendor.inactive_vendor_details', compact('inactiveVendorDetails'));
    }

    public function ActiveVendorApprove(Request $request)
    {
        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'active',
        ]);

        $notification = array(
            'message' => 'Vendor Active Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('active.vendor')->with($notification);
    }


}
