<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Models\TStok;
use App\Models\Item;
use App\Models\Opname;

class StokController extends Controller
{
    //cara membuat kondisi di controller level admin saja yang bisa mengakses
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function stok_penambahan(Request $request)
    {
        if(empty($request->tgl)) {
            $tgl = date("Y-m-d");
        } else {
            $tgl = $request->tgl;
        }
        $item = Item::all();
        $stok = TStok::whereDate('tanggal', $tgl)->get();
        return view('stok.penambahan', compact('stok', 'item', 'tgl'));
    }
    public function stok_penambahan_tambah(Request $request)
    {
        //Helper function untuk parse harga
        $parseHarga = function($harga) {
            $harga = preg_replace('/[^0-9,]/', '', $harga);
            $harga = str_replace(',', '.', $harga);
            return (float) $harga;
        };
        $item = Item::find($request->id_stok);


        $stok = new TStok();
        $stok->id_stok = $request->id_stok;
        $stok->nama_stok= $item->nama;
        $stok->jumlah = $request->jumlah;
        $stok->harga_beli = $parseHarga($request->harga_beli);
        $stok->keterangan = $request->keterangan;
        $stok->tanggal = $request->tanggal;
        $stok->save();

        //tambahkan juga ke stok item
        $item->stok = $item->stok + $request->jumlah;
        $item->save();

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan.');
    }
    //hapus penambahan stok buat juga mengembalikan ke stok item
    public function stok_penambahan_hapus($id)
    {
        $stok = TStok::find($id);
        if($stok) {
            //kurangi juga dari stok item
            $item = Item::find($stok->id_stok);
            if($item) {
                $item->stok = $item->stok - $stok->jumlah;
                if($item->stok < 0) {
                    $item->stok = 0;
                }
                $item->save();
            }
            $stok->delete();
            return redirect()->back()->with('success', 'Stok penambahan berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Data stok tidak ditemukan.');
        }
        
    }
    //stok opname
    public function stok_opname(Request $request)
    {
        if(empty($request->tgl)) {
            $tgl = date("Y-m-d");
        } else {
            $tgl = $request->tgl;
        }
        $item = Item::all();
        $opname = Opname::whereDate('tgl_opname', $tgl)->get();
        return view('stok.opname', compact('opname', 'item', 'tgl'));
    }
    public function stok_opname_tambah(Request $request)
    {
        //ambil item berdasarkan id_stok
        $item = Item::find($request->id_stok);
        
        $opname = new Opname();
        $opname->kode = $item->kode;
        $opname->nama = $item->nama;
        $opname->harga = $item->harga_jual;
        $opname->sebelum = $item->stok;
        $opname->sesudah = $request->sesudah;
        $opname->tgl_opname = date('Y-m-d');
        $opname->save();
        //update stok item
        $item->stok = $request->sesudah;
        $item->save();

        return redirect()->back()->with('success', 'Stok opname berhasil ditambahkan.');
    }

    public function stok_opname_tambah_multi(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'item_ids' => 'nullable|array',
            'item_ids.*' => 'integer',
            'sesudah' => 'required|array|min:1',
        ]);

        $tanggal = $request->tanggal;
        $itemIds = collect($request->item_ids ?? [])->map(function ($id) {
            return (int) $id;
        })->filter()->unique()->values()->all();
        $sesudahMap = $request->sesudah;

        // fallback: jika item_ids kosong, ambil dari key sesudah
        if (empty($itemIds) && is_array($sesudahMap)) {
            $itemIds = collect(array_keys($sesudahMap))->map(function ($id) {
                return (int) $id;
            })->filter()->unique()->values()->all();
        }

        if (empty($itemIds)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 item untuk opname.');
        }

        $savedCount = 0;

        DB::transaction(function () use ($itemIds, $sesudahMap, $tanggal, &$savedCount) {
            foreach ($itemIds as $idItem) {
                $item = Item::find($idItem);
                if (!$item) {
                    continue;
                }

                if (!array_key_exists($idItem, $sesudahMap)) {
                    continue;
                }

                $stokSesudah = max(0, (int) $sesudahMap[$idItem]);

                $opname = new Opname();
                $opname->kode = $item->kode;
                $opname->nama = $item->nama;
                $opname->harga = $item->harga_jual;
                $opname->sebelum = $item->stok;
                $opname->sesudah = $stokSesudah;
                $opname->tgl_opname = $tanggal;
                $opname->save();

                $item->stok = $stokSesudah;
                $item->save();

                $savedCount++;
            }
        });

        if ($savedCount < 1) {
            return redirect()->back()->with('error', 'Tidak ada item yang berhasil disimpan.');
        }

        return redirect()->back()->with('success', 'Stok opname multi item berhasil disimpan (' . $savedCount . ' item).');
    }

}
