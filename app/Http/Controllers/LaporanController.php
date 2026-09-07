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

        $stok_masuk = DB::table('beli_stok')
            ->select(
                'id_stok',
                DB::raw('SUM(jumlah + 0) as stok_masuk'),
                DB::raw('SUM((harga_beli + 0) * (jumlah + 0)) as total_masuk')
            )
            ->whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);

        if(!empty($item)) {
            $stok_masuk->where('id_stok', $item);
        }

        $stok_masuk = $stok_masuk->groupBy('id_stok')->get()->keyBy('id_stok');

        $stok_keluar = DB::table('transaksi')
            ->select(
                'id_item',
                DB::raw('SUM(jumlah + 0) as stok_keluar'),
                DB::raw('SUM((harga + 0) * (jumlah + 0)) as total_keluar')
            )
            ->where('closing', 1)
            ->whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);

        if(!empty($item)) {
            $stok_keluar->where('id_item', $item);
        }

        $stok_keluar = $stok_keluar->groupBy('id_item')->get()->keyBy('id_item');

        $arus_stok = collect();
        foreach($stok as $s) {
            if(!empty($item) && $item != $s->id_item) {
                continue;
            }

            $masuk = $stok_masuk->get($s->id_item);
            $keluar = $stok_keluar->get($s->id_item);
            $jumlah_masuk = (int) ($masuk->stok_masuk ?? 0);
            $jumlah_keluar = (int) ($keluar->stok_keluar ?? 0);

            $arus_stok->push((object)[
                'id_item' => $s->id_item,
                'kode' => $s->kode,
                'nama' => $s->nama,
                'stok_masuk' => $jumlah_masuk,
                'stok_keluar' => $jumlah_keluar,
                'selisih_stok' => $jumlah_masuk - $jumlah_keluar,
                'total_masuk' => (float) ($masuk->total_masuk ?? 0),
                'total_keluar' => (float) ($keluar->total_keluar ?? 0),
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