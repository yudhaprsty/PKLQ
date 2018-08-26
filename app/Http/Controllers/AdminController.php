<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\Userinfo;
use App\File;
use App\Alat;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SendPassword;
use Session;
use App\sidebar;
use App\Cabang;
use Chart;

session()->regenerate();
error_reporting(0);

class AdminController extends UserController
{
    public function index()
    {
     return view('admin');
    }

    public function view()
    {
      $side   = sidebar::orderBy('id_cabang')->get();
      return view('/tambahStaff', compact('side'));
    }

    public function create(Request $request)
    {
      $pswd = Session::get('password');
      $this->validate($request, array(
              'name'          => 'required|max:100',
              'email'         => 'required|unique:users,email,',
  //            'password'      => 'required|max:100',
              'identitas'     => 'required',
              'jabatan'        => 'required',
              //'avatar'        => 'mimes:jpeg,jpg,png,gif|max:10000'
          ));

      $user   = User::create([
              'name'           => $request->input('name'),
              'email'          => $request->input('email'),
              'password'       => Hash::make($pswd),
              'identitas'      => $request->input('identitas'),
              'isAdmin'        => $request->input('jabatan'),
          ]);

      $info = Userinfo::latest()->first($info);
      session()->put('nama', $request->input('name'));
      session()->put('password', $pswd);
      $info->notify(new SendPassword());

      session()->flash('success', 'Tambah Anggota Berhasil!');
      return redirect()->back();
      //echo 'aaa';
    }

    public function viewCabang($id)
    {
        $side = sidebar::orderBy('id_cabang')->get();
        $file = File::orderBy('id_file')->get();
        $alat = Alat::orderBy('id_alat')->get();
        session()->put('file', $file);
        session()->put('id', $id);
        session()->put('alat', $alat);
        // dd((integer)$id);
        return view('Agam', compact('side'));

    }

    public function readAll()
    {
        $readUser = User::orderBy('id')->get();
        $side   = sidebar::orderBy('id_cabang')->get();
        return view('/lihatStaff', compact('readUser'), compact('side'));
    }

    public function destroy($id)
    {
        $user   = User::find($id);
        $user->delete();
        session()->flash('deleteNotif', 'Delete Succesful!');
        return redirect()->route('lihatStaff.readAll');
    }

    public function profilAdmin()
    {
      $side   = sidebar::orderBy('id_cabang')->get();
      return view('profil', compact('side'));
    }

    public function DaftarCabang()
    {
      $side   = sidebar::orderBy('id_cabang')->get();
      $cabang = Cabang::orderBy('id_cabang')->get();
      return view('DaftarCabang', compact('cabang'), compact('side'));
    }

    public function tambahCabang()
    {
      $side   = sidebar::orderBy('id_cabang')->get();
      return view('tambahCabang', compact('side'));
    }

    public function createCabang(Request $request)
    {
      $this->validate($request, array(
              'nama_cabang'    => 'required|max:100',
              'ip_server'      => 'required',
          ));
          $address = $request->input('nama_cabang');
          // Get JSON results from this request
          $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
          $geo = json_decode($geo, true); // Convert the JSON to an array
          if (isset($geo['status']) && ($geo['status'] == 'OK')) {
            $latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
            $longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
          }
      $user   = Cabang::create([
              'nama_cabang'   => $request->input('nama_cabang'),
              'ip_server'     => $request->input('ip_server'),
              'longitude'     => $longitude,
              'latitude'      => $latitude,
          ]);
      session()->flash('success', 'Cabang Berhasil Ditambahkan');
      return redirect()->back();
    }

    public function lihatFile()
    {
      $side   = sidebar::orderBy('id_cabang')->get();
      $file = File::orderBy('id_file')->get();
      $cabang = Cabang::orderBy('id_cabang')->get();
      $alat = Alat::orderBy('id_alat')->get();
      return view('lihatFile', compact('file'), compact('cabang'), compact('alat'), compact('side'));
    }

    public function DaftarAlat()
    {
      $side = sidebar::orderBy('id_cabang')->get();
      $alat = Alat::orderBy('id_alat')->get();
      $cabang = Cabang::orderBy('id_cabang')->get();
      return view('DaftarAlat', compact('alat'), compact('cabang'), compact('side'));
    }

    public function HapusAlat($id)
    {
        $alat   = Alat::where('id_alat', $id);
        $alat->delete();
        session()->flash('deleteNotif', 'Alat Berhasil Dihapus!');
        return redirect()->route('DaftarAlat');
    }

    public function tambahAlat()
    {
      $side   = sidebar::orderBy('id_cabang')->get();
      return view('tambahAlat', compact('side'));
    }

    public function createAlat(Request $request)
    {
      $this->validate($request, array(
              'nama_alat'           => 'required|max:100',
              'identitas_alat'      => 'required',
          ));
      $user   = Alat::create([
              'nama_alat'          => $request->input('nama_alat'),
              'identitas_alat'     => $request->input('identitas_alat'),
          ]);
      session()->flash('success', 'Alat Berhasil Ditambahkan');
      return redirect()->back();
    }

    public function Laporan()
    {
      $side   = sidebar::orderBy('id_cabang')->get();
      return view('laporan', compact('side'));
    }
}
