<?php

namespace App\Http\Controllers;
use Auth;
use Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function UserDashboard()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);
         return view('index', compact('userData'));
    }

    public function UserProfileStore(Request $request)
    {
        // get the authenticated user id
       $id = Auth::user()->id;
       $data = User::find($id);
       $data->username = $request->username;
       $data->name = $request->name;
       $data->email = $request->email;
       $data->phone = $request->phone;
       $data->address = $request->address;

       if($request->file('photo')) {
          $file = $request->file('photo');
        //   to remove an existing img before downloading
          @unlink(public_path('upload/user_images/'.$data->photo));
          $filename = date('YmdHi').$file->getClientOriginalName();
          $file->move(public_path('upload/user_images'), $filename);
        // insert in db and field name is photo  
          $data['photo'] = $filename;
       }

       $data->save();

        $notification = array(
           'message' => 'User Profile Updated Successfully',
           'alert-type' => 'success'
        );


       return redirect()->back()->with($notification);
     
    }

    public function UserLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

         $notification = array(
           'message' => 'User Logout Successfully',
           'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    }


    public function UserPasswordUpdate(Request $request) 
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

}
