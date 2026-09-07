<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Item;


class PembelianController extends Controller
{
    //cara membuat kondisi di controller level admin saja yang bisa mengakses
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function pembelian_masuk(Request $request)
    {
        if(empty($request->tgl_awal) || empty($request->tgl_akhir)){
            $tgl_awal = date('Y-m-d');
            $tgl_akhir = date('Y-m-d');
        } else {
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
        }
        $suppliers =  Supplier::all();
        $stok = Item::all();
        $pembelians = Pembelian::whereBetween('tanggal', [$tgl_awal, $tgl_akhir])->orderBy('tanggal', 'desc')->get();
        return view('pembelian.masuk', compact('pembelians', 'tgl_awal', 'tgl_akhir', 'suppliers','stok'));
    }
    public function pembelian_masuk_tambah(Request $request)
    {
        //Helper function untuk parse harga
        $parseHarga = function($harga) {
            $harga = preg_replace('/[^0-9,]/', '', $harga);
            $harga = str_replace(',', '.', $harga);
            return (float) $harga;
        };
        
        //ambil nama supplier dan stok dari id dan jika request supplier_id null kembalikan jika supplier null dibuat tidak ada dengan id 0
        if(empty($request->id_supplier)){
            $nama_supplier = 'Tidak Ada';
            $id_supplier = 0;
        }else{
            $supplier = Supplier::find($request->id_supplier);
            $nama_supplier = $supplier->nama;
            $id_supplier = $supplier->id;
        }
        if(empty($request->id_stok)){
            return redirect()->back()->with('error', 'Item harus dipilih.');
        }
        
        $item = Item::find($request->id_stok);

        $pembelian = new Pembelian();
        $pembelian->id_supplier = $id_supplier;
        $pembelian->nama_supplier = $nama_supplier;
        $pembelian->id_stok = $request->id_stok;
        $pembelian->nama_stok = $item->nama;
        $pembelian->jumlah = $request->jumlah;
        $pembelian->harga_beli = $parseHarga($request->harga_beli);
        $pembelian->tanggal = $request->tanggal;
        $pembelian->save();

        //tambah stok di tabel item
        $item->stok = $item->stok + $request->jumlah;
        $item->save();

        return redirect()->back()->with('success', 'Data pembelian masuk berhasil ditambahkan.');
    }
    public function pembelian_masuk_hapus($id)
    {
        //pindahkan dulu data ke tabel stok_supplier_retur sebelum dihapus
        $item = Pembelian::find($id);
        DB::table('stok_supplier_retur')->insert([
            'id_supplier' => $item->id_supplier,
            'nama_supplier' => $item->nama_supplier,
            'id_stok' => $item->id_stok,
            'nama_stok' => $item->nama_stok,
            'jumlah' => $item->jumlah,
            'harga_beli' => $item->harga_beli,
            'tanggal' => $item->tanggal,
        ]);
        $pembelian = Pembelian::find($id);
        $pembelian->delete();
        //berhasil hapus kurangi stok di tabel item
        $stok = Item::find($item->id_stok);
        $stok->stok = $stok->stok - $item->jumlah;
        $stok->save();

        return redirect()->back()->with('success', 'Data pembelian masuk berhasil dihapus.');
    }
    public function pembelian_retur(Request $request)
    {
        if(empty($request->tgl_awal) || empty($request->tgl_akhir)){
            $tgl_awal = date('Y-m-d');
            $tgl_akhir = date('Y-m-d');
        } else {
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
        }
        $returs = DB::table('stok_supplier_retur')->whereBetween('tanggal', [$tgl_awal, $tgl_akhir])->orderBy('tanggal', 'desc')->get();
        return view('pembelian.retur', compact('returs', 'tgl_awal', 'tgl_akhir'));
    }

}
