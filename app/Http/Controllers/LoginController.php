<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function loginPost(Request $request)
    {
       $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->level === 'admin') {
            return redirect()->intended('/')->with('msg', 'Berhasil Masuk Akun Admin');
        } elseif ($user->level === 'kasir') {
            return redirect()->intended('/')->with('msg', 'Berhasil Masuk akun Kasir');
        }

        return redirect()->intended('/');
    }

    return back()->with('msg', 'Cek lagi email atau password Anda');
    }
    //logout function
    public function logout(Request $request)
    {
    Auth::logout();

    return redirect('/login');
    }
    public function tambah(Request $request)
    {

    $user = new User();
    $user->nama = 'Admin';
    $user->username = $request->username;
    $user->password = Hash::make($request->password);
    $user->level = 'admin'; // Set default role to 'kasir'
    $user->save();

    return redirect('/login')->with('msg', 'Akun berhasil dibuat. Silakan login.'); 
    }
public function ubah_pass(Request $request)
{
    $request->validate([
        'password' => 'required',
        'password_baru' => 'required',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->password, $user->password)) {
        return redirect('/')->with('error', 'Password lama tidak sesuai.');
    }

    $user->password = Hash::make($request->password_baru);
    $user->save();

    return redirect('/')->with('msg', 'Password berhasil diubah.');
}
}
