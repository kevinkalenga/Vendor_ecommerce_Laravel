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
}
