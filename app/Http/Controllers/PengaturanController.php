<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Models\Pajak;

class PengaturanController extends Controller
{
    //cara membuat kondisi di controller level admin saja yang bisa mengakses
    public function __construct()
    {
        $this->middleware('admin');
    }
    //menampilkan halaman pengaturan
    public function pengaturan()
    {
        $pajak = Pajak::first();
        $nama_usaha = DB::table('nama_usaha')->where('id', 1)->first();
        $nomor = DB::table('nomorkode')->where('id', 1)->first();
        $mode_kasir = DB::table('pengaturan')->where('id', 1)->first();
        return view('pengaturan.index', compact('pajak', 'nama_usaha', 'nomor', 'mode_kasir'));
    }
    //menyimpan pengaturan
    public function pengaturan_simpan(Request $request)
    {
        $nama = $request->nama;
        $alamat = $request->alamat;
        $kontak = $request->kontak;
        $ucapan = $request->ucapan;
        DB::table('nama_usaha')->where('id', 1)->update([
            'nama' => $nama,
            'alamat' => $alamat,
            'kontak' => $kontak,
            'ucapan' => $ucapan
        ]);
        return redirect()->back()->with('success', 'Data Profil Berhasil Disimpan');
    }
    //simpan pajak aktif
    public function pengaturan_pajak_simpan(Request $request)
    {
        $status = $request->status;
        $persen = $request->persen;
        Pajak::where('id', 1)->update([
            'persen' => $persen,
            'status' => $status
        ]);
        return redirect()->back()->with('success', 'Pengaturan Pajak Berhasil Disimpan');
    }
    //reset nomorkode
    public function pengaturan_reset_kode_item()
    {
        DB::table('nomorkode')->where('id', 1)->update([
            'kode_terakhir' => 0,
            'tgl_terakhir' => date('Y-m-d')
        ]);
        return redirect()->back()->with('success', 'Nomor Kode Berhasil Direset');
    }
    //simpan mode kasir
    public function pengaturan_mode_simpan(Request $request)
    {        $mode = $request->mode;
        DB::table('pengaturan')->where('id', 1)->update([
            'status' => $mode
        ]);
        return redirect()->back()->with('success', 'Pengaturan Mode Kasir Berhasil Disimpan');
    }
}
