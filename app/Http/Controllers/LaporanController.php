<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Models\Item;
use App\Models\Transaksi;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\TStok;
use App\Models\Closing;
use App\Models\TStruk;

class LaporanController extends Controller
{
    //cara membuat kondisi di controller level admin saja yang bisa mengakses
    public function __construct()
    {
        $this->middleware('admin');
    }
    //laporan penjualan
    public function laporan_penjualan(Request $request)
    {
        $tgl_awal = $request->tgl_awal ?? date("Y-m-01");
        $tgl_akhir = $request->tgl_akhir ?? date("Y-m-d");
        $item = $request->item ?? "";
        $mode_kasir = DB::table('pengaturan')->where('id', 1)->first();
        $is_swalayan = $mode_kasir && $mode_kasir->status == 0;
        
        $query = Transaksi::with('item')->where('closing', 1)->whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);
        
        if(!empty($item)) {
            $query->where('id_item', $item);
        }
        
        $transaksi = $query->get();
        $stok = Item::all();
        return view('laporan.penjualan', compact('transaksi', 'tgl_awal', 'tgl_akhir', 'item', 'stok', 'is_swalayan'));
    }
    //laporan pembelian request tgl_awal, tgl_akhir, item dan supplier
    public function laporan_pembelian(Request $request)
    {
        $tgl_awal = $request->tgl_awal ?? date("Y-m-01");
        $tgl_akhir = $request->tgl_akhir ?? date("Y-m-d");
        $item = $request->item ?? "";
        $supplier = $request->supplier ?? "";
        $query = Pembelian::whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);
        if(!empty($item)) {
            $query->where('id_stok', $item);
        }
        if($supplier !== "") {
            $query->where('id_supplier', $supplier);
        }
        $pembelian = $query->get();
        $stok = Item::all();
        $suppliers = Supplier::all();
        return view('laporan.pembelian', compact('pembelian', 'tgl_awal', 'tgl_akhir', 'item', 'supplier', 'stok', 'suppliers'));
    }
    //laporan stok
    public function laporan_stok(Request $request)
    {
        $tgl_awal = $request->tgl_awal ?? date("Y-m-01");
        $tgl_akhir = $request->tgl_akhir ?? date("Y-m-d");
        $item = $request->item ?? "";
        $query = TStok::whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);
        if(!empty($item)) {
            $query->where('id_stok', $item);
        }
        $transaksi = $query->get();
        $stok = Item::all();
        return view('laporan.stok', compact('transaksi', 'stok', 'tgl_awal', 'tgl_akhir', 'item'));
    }
    //laporan arus stok
    public function laporan_arus_stok(Request $request)
    {
        $tgl_awal = $request->tgl_awal ?? date("Y-m-01");
        $tgl_akhir = $request->tgl_akhir ?? date("Y-m-d");
        $item = $request->item ?? "";
        $stok = Item::all();

        // 1. Stok Masuk Periode (beli_stok + stok_supplier)
        $beli_stok_periode = DB::table('beli_stok')
            ->select('id_stok', DB::raw('SUM(jumlah + 0) as total'))
            ->whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);
        if (!empty($item)) {
            $beli_stok_periode->where('id_stok', $item);
        }
        $beli_stok_periode = $beli_stok_periode->groupBy('id_stok')->pluck('total', 'id_stok');

        $stok_supplier_periode = DB::table('stok_supplier')
            ->select('id_stok', DB::raw('SUM(jumlah + 0) as total'))
            ->whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);
        if (!empty($item)) {
            $stok_supplier_periode->where('id_stok', $item);
        }
        $stok_supplier_periode = $stok_supplier_periode->groupBy('id_stok')->pluck('total', 'id_stok');

        // 2. Stok Masuk Setelah tgl_akhir (beli_stok + stok_supplier)
        $beli_stok_after = DB::table('beli_stok')
            ->select('id_stok', DB::raw('SUM(jumlah + 0) as total'))
            ->where('tanggal', '>', $tgl_akhir);
        if (!empty($item)) {
            $beli_stok_after->where('id_stok', $item);
        }
        $beli_stok_after = $beli_stok_after->groupBy('id_stok')->pluck('total', 'id_stok');

        $stok_supplier_after = DB::table('stok_supplier')
            ->select('id_stok', DB::raw('SUM(jumlah + 0) as total'))
            ->where('tanggal', '>', $tgl_akhir);
        if (!empty($item)) {
            $stok_supplier_after->where('id_stok', $item);
        }
        $stok_supplier_after = $stok_supplier_after->groupBy('id_stok')->pluck('total', 'id_stok');

        // 3. Stok Keluar Periode (transaksi closing)
        $transaksi_periode = DB::table('transaksi')
            ->select('id_item', DB::raw('SUM(jumlah + 0) as total'))
            ->where('closing', 1)
            ->whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);
        if (!empty($item)) {
            $transaksi_periode->where('id_item', $item);
        }
        $transaksi_periode = $transaksi_periode->groupBy('id_item')->pluck('total', 'id_item');

        // 4. Stok Keluar Setelah tgl_akhir (transaksi closing)
        $transaksi_after = DB::table('transaksi')
            ->select('id_item', DB::raw('SUM(jumlah + 0) as total'))
            ->where('closing', 1)
            ->where('tanggal', '>', $tgl_akhir);
        if (!empty($item)) {
            $transaksi_after->where('id_item', $item);
        }
        $transaksi_after = $transaksi_after->groupBy('id_item')->pluck('total', 'id_item');

        $arus_stok = collect();
        foreach ($stok as $s) {
            if (!empty($item) && $item != $s->id_item) {
                continue;
            }

            $masuk_periode = (int) (($beli_stok_periode[$s->id_item] ?? 0) + ($stok_supplier_periode[$s->id_item] ?? 0));
            $masuk_after = (int) (($beli_stok_after[$s->id_item] ?? 0) + ($stok_supplier_after[$s->id_item] ?? 0));

            $keluar_periode = (int) ($transaksi_periode[$s->id_item] ?? 0);
            $keluar_after = (int) ($transaksi_after[$s->id_item] ?? 0);

            $stok_saat_ini = (int) ($s->stok ?? 0);
            $stok_akhir = $stok_saat_ini + $keluar_after - $masuk_after;
            $stok_awal = $stok_akhir - $masuk_periode + $keluar_periode;

            $arus_stok->push((object)[
                'id_item' => $s->id_item,
                'kode' => $s->kode,
                'nama' => $s->nama,
                'stok_awal' => $stok_awal,
                'stok_masuk' => $masuk_periode,
                'stok_keluar' => $keluar_periode,
                'stok_akhir' => $stok_akhir,
            ]);
        }

        return view('laporan.arus_stok', compact('arus_stok', 'stok', 'tgl_awal', 'tgl_akhir', 'item'));
    }
    //laporan closing
    public function laporan_closing(Request $request)
    {
        $tgl_awal = $request->tgl_awal ?? date("Y-m-01");
        $tgl_akhir = $request->tgl_akhir ?? date("Y-m-d");
        $closing = Closing::whereBetween('tanggal', [$tgl_awal, $tgl_akhir])->get();
        return view('laporan.closing', compact('closing', 'tgl_awal', 'tgl_akhir'));
    }
    //detail laporan closing
    public function laporan_closing_detail($kode)
    {
        $closing = Closing::where('kode_closing', $kode)->first();
        $debet = DB::table('non_tunai')->get();
        $debet_transaksi = TStruk::where('closing', 1)->where('kode_closing', $kode)->where('bayar',1)->get();
        $nama_usaha = DB::table('nama_usaha')->where('id', 1)->first();
        return view('laporan.detail_closing', compact('closing', 'debet', 'debet_transaksi', 'nama_usaha'));
    }
    //daftar transaksi per closing
    public function laporan_closing_transaksi($kode)
    {
        $closing = Closing::where('kode_closing', $kode)->first();
        if (!$closing) {
            return redirect()->back()->with('error', 'Data closing tidak ditemukan.');
        }

        $transaksi = DB::table('transaksi')
            ->join('tabel_struk', 'transaksi.kode_struk', '=', 'tabel_struk.kode_struk')
            ->leftJoin('item', 'transaksi.id_item', '=', 'item.id_item')
            ->where('tabel_struk.kode_closing', $kode)
            ->select(
                'transaksi.*',
                'item.harga_beli',
                'tabel_struk.kode_closing',
                'tabel_struk.metode_pembayaran',
                'tabel_struk.bayar'
            )
            ->orderBy('transaksi.id_transaksi', 'asc')
            ->get();

        $non_tunai = DB::table('non_tunai')->get()->keyBy('id');

        return view('laporan.transaksi_closing', compact('closing', 'transaksi', 'non_tunai'));
    }
    //laporan pendapatan
    public function laporan_pendapatan(Request $request)
    {
       //mendapatkan tahun sekarang sebagai default
       $tahun_pilih = $request->tahun ?? date("Y");
    //pendapatan dihitung per bulan dari transaksi yang sudah closing
    //total penjualan ialah harga dikali jumlah
       //ambil data pembelian perbulan yang nanti akan di kurangi dari pendapatan
         //mendapatkan total pembelian = harga_beli + jumlah
       //sebutkan dahulu bulanya di pendapatan dan pembelian agar tidak ada yang meloncat bulan, jika di di antar pendapatan dan pembelian tidak ada bulan nya maka di anggap 0
       //ambil data bulanan 1 -12
       $bulan_array = range(1, 12);
       $pendapatan = collect();
       foreach($bulan_array as $bulan) {
           $data = DB::table('transaksi')
               ->select(DB::raw('SUM(harga * jumlah) as total_pendapatan'))
               ->where('closing', 1)
               ->whereYear('tanggal', $tahun_pilih)
               ->whereMonth('tanggal', $bulan)
               ->first();
           $pendapatan->push((object)[
               'bulan' => $bulan,
               'total_pendapatan' => $data->total_pendapatan ?? 0
           ]);
       }
       //ambil data modal/HPP perbulan dari item yang terjual
       $pembelian = collect();
       foreach($bulan_array as $bulan) {
           $data = DB::table('transaksi')
               ->join('item', 'item.id_item', '=', 'transaksi.id_item')
               ->select(DB::raw('SUM(item.harga_beli * transaksi.jumlah) as total_pembelian'))
               ->where('transaksi.closing', 1)
               ->whereYear('transaksi.tanggal', $tahun_pilih)
               ->whereMonth('transaksi.tanggal', $bulan)
               ->first();
              $pembelian->push((object)[
                  'bulan' => $bulan,
                  'total_pembelian' => $data->total_pembelian ?? 0
              ]);
       }
       //mengirim data ke view

         $tahun = DB::table('closing')
            ->select(DB::raw('DISTINCT YEAR(tanggal) as tahun'))
            ->orderBy('tahun', 'DESC')
            ->get();
         
         return view('laporan.pendapatan', compact('pendapatan', 'tahun_pilih', 'pembelian', 'tahun'));
    }
    //grafik penjualan ambil dari tabel closing dari real_total dengan filter tanggal
    public function grafik_penjualan(Request $request)
    {
        $tgl_awal = $request->tgl_awal ?? date("Y-m-01");
        $tgl_akhir = $request->tgl_akhir ?? date("Y-m-d");
        $data = DB::table('closing')
            ->select(DB::raw('DATE(tanggal) as tanggal'), DB::raw('SUM(real_total) as total'))
            ->whereBetween('tanggal', [$tgl_awal, $tgl_akhir])
            ->groupBy('tanggal')
            ->get();
        return view('grafik.penjualan', compact('data', 'tgl_awal', 'tgl_akhir'));
    }
    //grafik penjualan bulanan
    public function grafik_penjualan_bulanan(Request $request)
    {
        $tahun_pilih = $request->tahun ?? date("Y");
        $data = DB::table('closing')
            ->select(DB::raw('MONTH(tanggal) as bulan'), DB::raw('SUM(real_total) as total'))
            ->whereYear('tanggal', $tahun_pilih)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->get();
        $tahun = DB::table('closing')
            ->select(DB::raw('DISTINCT YEAR(tanggal) as tahun'))
            ->orderBy('tahun', 'DESC')
            ->get();
        return view('grafik.penjualan_bulanan', compact('data', 'tahun','tahun_pilih'));
    }
    //grafik penjualan tahunan
    public function grafik_penjualan_tahunan(Request $request)
    {
        $data = DB::table('closing')
            ->select(DB::raw('YEAR(tanggal) as tahun'), DB::raw('SUM(real_total) as total'))
            ->groupBy(DB::raw('YEAR(tanggal)'))
            ->get();
        return view('grafik.penjualan_tahunan', compact('data'));
    }
    }