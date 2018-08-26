<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;
use Alert;
use App\sidebar;
use App\File;
use App\Alat;
use Chart;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view_cabang($id)
    {
      $side = sidebar::orderBy('id_cabang')->get();
      $file = File::orderBy('id_file')->get();
      $alat = Alat::orderBy('id_alat')->get();
      session()->put('file', $file);
      session()->put('id', $id);
      session()->put('alat', $alat);
      return view('kapus/agam', compact('side'));
    }

    public function view_profile()
    {
      $side = sidebar::orderBy('id_cabang')->get();
      return view('kapus/profile', compact('side'));
    }

    public function gantiPassword(Request $request)
    {
        // dd($request->get('current-password'));
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
          // The passwords matches
          session()->flash('invalidPassword', 'Your current password does not matches with the password you provided. Please try again.');
          return redirect()->back();

        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
          //Current password and new password are same
          session()->flash('invalidPassword', 'New Password cannot be same as your current password. Please choose a different password.');
          return redirect()->back();
        }

        $validatedData = $request->validate([
          'current-password' => 'required',
          'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->name = $request->name;
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password changed successfully !");
        session()->flash('passwordChanged', 'Your Password Has Been Changed.');
        // return redirect()->back();
    }
}
