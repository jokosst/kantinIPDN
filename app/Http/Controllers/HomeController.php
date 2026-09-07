<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Carbon;
use App\Models\TStruk;
use App\Models\TStrukSementara;
use App\Models\Pengeluaran;
use App\Models\Meja;
use App\Models\TSementara;
use App\Models\Pajak;
use App\Models\Item;
use App\Models\Transaksi;
use App\Models\Modal;
use App\Models\LogReturn;
use App\Models\LogHapus;
use Auth;
use App\Models\Closing;

class HomeController extends Controller
{
    public function home()
    {
    $struk = TStruk::select('tgl', DB::raw('SUM(total) as total'))
    ->groupBy('tgl')
    ->get();
    $data = TStrukSementara::select(
        'bayar',
        DB::raw('SUM(sub_total) as jmltransaksi'),
        DB::raw('SUM(pajak) as jmlpajak'),
        DB::raw('SUM(diskon) as jmldiskon')
    )
    ->where('closing', 0)
    ->groupBy('bayar')
    ->get()
    ->keyBy('bayar'); // supaya gampang diakses dengan $data[0], $data[1]

    // akses hasil
    $jmltransaksi0 = isset($data[0]) ? $data[0]->jmltransaksi : 0;
    $jmlpajak0     = isset($data[0]) ? $data[0]->jmlpajak : 0;
    $jmldiskon0    = isset($data[0]) ? $data[0]->jmldiskon : 0;

    $jmltransaksi1 = isset($data[1]) ? $data[1]->jmltransaksi : 0;
    $jmlpajak1     = isset($data[1]) ? $data[1]->jmlpajak : 0;
    $jmldiskon1    = isset($data[1]) ? $data[1]->jmldiskon : 0;

    // hitung real_total
    $jml_pengeluaran = Pengeluaran::where('closing', 0)->sum('jml');

    $tunai  = $jmltransaksi0 + $jmlpajak0 - $jmldiskon0 - $jml_pengeluaran;
    $nontunai = $jmltransaksi1 + $jmlpajak1 - $jmldiskon1;
    //pengaturan mode kasir
    $pengaturan = DB::table('pengaturan')->where('id', 1)->first();
    //cek stok yang menipis
    $stok_min = Item::whereColumn('stok', '<=', 'stok_minimal')->get();
    $stokmenipis = $stok_min->count();
        return view('beranda', ['struk'=>$struk,'tunai'=>$tunai,'nontunai'=>$nontunai, 'stokmenipis' => $stokmenipis, 'pengaturan' => $pengaturan]);
    }
    public function transaksi()
    {
        $id_kasir = Auth::user()->id;
        // Ambil semua meja
        $mejaList = Meja::all();

        $data = $mejaList->map(function ($meja) use ($id_kasir) {
            // Cek transaksi sementara
            $transaksi = TSementara::where('slot', $meja->id)
                //->where('ket', 0)
                ->where('pesanan', 1)
                ->where('id_kasir', $id_kasir)
                ->first();

            // Tambahkan status
            $meja->status = $transaksi ? 'terisi' : 'kosong';

            return $meja;
        });
        return view('meja', compact('data'));
    }
    public function transaksiMeja($id)
    {
        
        $id_kasir = Auth::user()->id;
        $transaksi = TSementara::select(
        DB::raw('SUM(jumlah) as jumlah'),
        'harga',
        'nama',
        DB::raw('MAX(id_transaksi) as id_transaksi'),
        'id_item',
        'status_note',
        'note',
        'slot',
        'ket',
        'jenis',
        'id_kategori'
        )
        //->where('ket', 0)
        ->where('pesanan', 1)
        ->where('slot', $id)
        ->where('id_kasir', $id_kasir)
        ->groupBy('id_item','harga')
        ->orderBy('id_transaksi', 'desc')
        ->get();
        // Ambil pajak persen
        $pajak = Pajak::where('id', 1)
        ->where('status', 1)
        ->value('persen');
        // Default pajak = 0 kalau tidak ada
        $pajak = ($pajak !== null) ? $pajak : 0;
        // Ambil diskom persen
        $diskon = Pajak::where('id', 2)
        ->where('status', 1)
        ->value('persen');
        // Default diskon = 0 kalau tidak ada
        $diskon = ($diskon !== null) ? $diskon : 0;
        // Hitung jumlah total
        $jlh = $transaksi->reduce(function ($carry, $dt) use ($pajak) {
        $harga   = $dt->harga;
        $jumlah  = $dt->jumlah;
        // Hitung total + pajak
        $hpajak  = $harga * $pajak;
        $total   = ($harga + $hpajak) * $jumlah;
        return $carry + $total;
        }, 0);
        // Hitung diskon
        $jlakhir = $jlh - ($jlh * $diskon);
    // total transaksi tanpa pajak dan diskon
    $total_transaksi = $transaksi->reduce(function ($carry, $dt) {
        $harga   = $dt->harga;
        $jumlah  = $dt->jumlah;
        $total   = $harga * $jumlah;
        return $carry + $total;
        }, 0);
        // dd($total_transaksi);
        //data bank non tunai
        $non_tunai = DB::table('non_tunai')->get();

        //pengaturan mode kasir
        $pengaturan = DB::table('pengaturan')->where('id', 1)->first();
        if ($pengaturan && $pengaturan->status == 0) {
        return view('transaksi', ['id_meja'=>$id],compact('transaksi','jlakhir','total_transaksi','non_tunai'));
         } else {
        $item = DB::table('menu')->get();
        return view('transaksi1', ['id_meja'=>$id], compact('item','transaksi','jlakhir','total_transaksi','non_tunai'));
        }
    }
    //transaksi tambah kode
    public function transaksiTambahKode(Request $request)
    {
        $kode = $request->kode;
        $id_meja = $request->id_meja;
        $id_kasir = Auth::user()->id;
        // Cari item berdasarkan kode
    $item = Item::where('kode', $kode)->first();

    if ($item) {
        $id_item     = $item->id_item;
        $nama        = $item->nama;
        $harga_jual  = $item->harga_jual;
        $id_kategori = $item->id_kategori;
        $stok        = $item->stok;
        $jumlah_awal = 1;

        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");

        // Ambil data grosir
        $min_grosir   = $item->min_grosir;
        $harga_grosir = $item->harga_grosir;

        // Hitung jumlah di transaksi sementara
        $jumlah_transaksi = TSementara::where('id_item', $id_item)
            ->where('slot', $id_meja)
            ->where('id_kasir', $id_kasir)
            ->where('ket', 0)
            ->sum('jumlah');

        $jumlah = $jumlah_transaksi + $jumlah_awal;

        // Tentukan harga grosir atau harga normal
        if ($jumlah >= $min_grosir && $min_grosir > 1) {
            $harga = $harga_grosir;
        } else {
            $harga = $harga_jual;
        }

        // Hapus data lama
        TSementara::where('id_item', $id_item)
            ->where('slot', $id_meja)
            ->where('ket', 0)
            ->where('id_kasir', $id_kasir)
            ->delete();

        // Insert ulang data
        TSementara::create([
            'id_item'       => $id_item,
            'id_kasir'      => $id_kasir,
            'nama'          => $nama,
            'harga'         => $harga,
            'jenis'         => '',
            'jumlah'        => $jumlah,
            'slot'          => $id_meja,
            'id_kategori'   => $id_kategori,
            'tanggal'       => $tanggal,
            'waktu'         => $waktu,
            'ket'           => 0,
            'pesanan'       => 1,
            'note'          => '',
            'status_note'   => 0,
            'closing'       => 0,
        ]);
    }   
    return redirect()->back();    
    }
    //transaksi tambah item lainnya
    public function transaksiTambahLainnya(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|string',
            'id_meja' => 'required|integer',
        ]);

        $id_kasir = Auth::user()->id;
        $id_meja = $request->id_meja;
        $nama = trim($request->nama);

        $harga_bersih = preg_replace('/[^0-9]/', '', $request->harga);
        $harga = (int) ($harga_bersih ?: 0);
        $jumlah_total = (int) $request->jumlah;

        TSementara::create([
            'id_item'       => 0,
            'id_kasir'      => $id_kasir,
            'nama'          => $nama,
            'harga'         => $harga,
            'jenis'         => '',
            'jumlah'        => $jumlah_total,
            'slot'          => $id_meja,
            'id_kategori'   => 0,
            'tanggal'       => date("Y-m-d"),
            'waktu'         => date("H:i:s"),
            'ket'           => 0,
            'pesanan'       => 1,
            'note'          => '',
            'status_note'   => 0,
            'closing'       => 0,
        ]);

        return redirect()->back()->with('success', 'Item lainnya berhasil ditambahkan.');
    }
    public function cari_item(Request $request)
    {
        $nama = $request->nama;
        $id_meja = $request->kode_meja;

        $data = Item::select(
                    'item.kode',
                    'item.nama',
                    'item.harga_jual',
                    'item.harga_beli',
                    'item.id_item',
                    'item.stok',
                    'katagori.nama_katagori'
                )
                ->join('katagori', 'katagori.id', '=', 'item.id_kategori')
                ->where('item.nama', 'like', "%{$nama}%")
                ->orWhere('item.kode', 'like', "%{$nama}%")
                ->get();

        return view('cari_item', compact('data', 'id_meja'));
    }
    public function cari_item_member(Request $request)
    {
        $nama = $request->nama;
        $id_meja = $request->kode_meja;

        $data = Item::select(
                    'item.kode',
                    'item.nama',
                    'item.harga_reseller',
                    'item.harga_beli',
                    'item.id_item',
                    'item.stok',
                    'katagori.nama_katagori'
                )
                ->join('katagori', 'katagori.id', '=', 'item.id_kategori')
                ->where('item.nama', 'like', "%{$nama}%")
                ->orWhere('item.kode', 'like', "%{$nama}%")
                ->get();

        return view('cari_item_member', compact('data', 'id_meja'));
    }
    public function cariItemTambah($meja, $id)
    {
        $id_item = $id;
        $id_meja = $meja;
        $id_kasir = Auth::user()->id;
        // Cari item berdasarkan kode
    $item = Item::where('id_item', $id_item)->first();

    if ($item) {
        $id_item     = $item->id_item;
        $nama        = $item->nama;
        $harga_jual  = $item->harga_jual;
        $id_kategori = $item->id_kategori;
        $stok        = $item->stok;
        $jumlah_awal = 1;

        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");

        // Ambil data grosir
        $min_grosir   = $item->min_grosir;
        $harga_grosir = $item->harga_grosir;

        // Hitung jumlah di transaksi sementara
        $jumlah_transaksi = TSementara::where('id_item', $id_item)
            ->where('slot', $id_meja)
            ->where('id_kasir', $id_kasir)
            ->where('ket', 0)
            ->sum('jumlah');

        $jumlah = $jumlah_transaksi + $jumlah_awal;

        // Tentukan harga grosir atau harga normal
        if ($jumlah >= $min_grosir && $min_grosir > 1) {
            $harga = $harga_grosir;
        } else {
            $harga = $harga_jual;
        }

        // Hapus data lama
        TSementara::where('id_item', $id_item)
            ->where('slot', $id_meja)
            ->where('ket', 0)
            ->where('id_kasir', $id_kasir)
            ->delete();

        // Insert ulang data
        TSementara::create([
            'id_item'       => $id_item,
            'id_kasir'      => $id_kasir,
            'nama'          => $nama,
            'harga'         => $harga,
            'jenis'         => '',
            'jumlah'        => $jumlah,
            'slot'          => $id_meja,
            'id_kategori'   => $id_kategori,
            'tanggal'       => $tanggal,
            'waktu'         => $waktu,
            'ket'           => 0,
            'pesanan'       => 1,
            'note'          => '',
            'status_note'   => 0,
            'closing'       => 0,
        ]);
    }   
    return redirect()->back(); 
}
public function cariItemTambahMember($meja, $id)
    {
        $id_item = $id;
        $id_meja = $meja;
        $id_kasir = Auth::user()->id;
        // Cari item berdasarkan kode
    $item = Item::where('id_item', $id_item)->first();

    if ($item) {
        $id_item     = $item->id_item;
        $nama        = $item->nama;
        $harga       = $item->harga_reseller;
        $id_kategori = $item->id_kategori;
        $stok        = $item->stok;
        $jumlah_awal = 1;

        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");

        // Hitung jumlah di transaksi sementara
        $jumlah_transaksi = TSementara::where('id_item', $id_item)
            ->where('slot', $id_meja)
            ->where('id_kasir', $id_kasir)
            ->where('ket', 1)
            ->sum('jumlah');

        $jumlah = $jumlah_transaksi + $jumlah_awal;


        // Hapus data lama
        TSementara::where('id_item', $id_item)
            ->where('slot', $id_meja)
            ->where('ket', 1)
            ->where('id_kasir', $id_kasir)
            ->delete();

        // Insert ulang data
        TSementara::create([
            'id_item'       => $id_item,
            'id_kasir'      => $id_kasir,
            'nama'          => $nama,
            'harga'         => $harga,
            'jenis'         => '',
            'jumlah'        => $jumlah,
            'slot'          => $id_meja,
            'id_kategori'   => $id_kategori,
            'tanggal'       => $tanggal,
            'waktu'         => $waktu,
            'ket'           => 1,
            'pesanan'       => 1,
            'note'          => '',
            'status_note'   => 0,
            'closing'       => 0,
        ]);
    }   
    return redirect()->back(); 
}
    public function transaksiHapusItem($id,$ket)
    {
        $item = TSementara::where('id_transaksi', $id)
            ->where('ket', $ket)
            ->first();

        if ($item) {
            LogHapus::create([
                'kode_struk' => '',
                'nama_item'  => $item->nama,
                'jumlah'     => $item->jumlah,
                'nama'       => Auth::user()->nama ?? '-',
                'waktu'      => date("Y-m-d H:i:s"),
                'alasan'     => 'hapus dari sebelum bayar',
            ]);
        }

        // Hapus item berdasarkan id_transaksi
        TSementara::where('id_transaksi', $id)->where('ket',$ket)->delete();
        return redirect()->back();
    }
    public function transaksiUbahItem(Request $request)
    {
        $id_kasir     = Auth::user()->id;
        $id_transaksi = $request->id_transaksi;
        $id_item      = $request->id_item;
        $id_meja      = $request->id_meja;       
        $nama         = $request->nama;
        $harga        = $request->harga;
        $id_kategori  = $request->id_kategori;
        $jumlah       = $request->jumlah;
        $persen       = $request->persen;
        $ket          = $request->ket;

        // Item custom/lainnya (id_item = 0)
        if ((int)$id_item === 0) {
            $harga_final = (int)$harga;
            $harga_persen = ($persen / 100) * $harga_final;
            $total = $harga_final - $harga_persen;

            TSementara::where('id_transaksi', $id_transaksi)
                ->where('id_kasir', $id_kasir)
                ->update([
                    'nama' => $nama,
                    'harga' => $total,
                    'jumlah' => $jumlah,
                    'note' => $harga,
                    'status_note' => $persen,
                ]);

            return redirect()->back();
        }

        // Ambil data grosir
        $item = Item::where('id_item', $id_item)->first();
            $min_grosir   = $item->min_grosir;
            $harga_grosir = $item->harga_grosir;
            // Tentukan harga grosir atau harga normal
            if ($jumlah >= $min_grosir && $min_grosir > 1) {
                $harga_final = $harga_grosir;
            } else {
                $harga_final = $harga;
            }
            // hitung diskon persen
        $harga_persen = ($persen / 100) * $harga_final;
        $total        = $harga_final - $harga_persen;

           // hapus data lama
        TSementara::where('id_item', $id_item)
            ->where('slot', $id_meja)
            ->where('ket', $ket)
            ->where('id_kasir', $id_kasir)
            ->delete();
        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");
        // Insert ulang data
        TSementara::create([
            'id_item'       => $id_item,
            'id_kasir'      => $id_kasir,
            'nama'          => $nama,
            'harga'         => $total,
            'jenis'         => '',
            'jumlah'        => $jumlah,
            'slot'          => $id_meja,
            'id_kategori'   => $id_kategori,
            'tanggal'       => $tanggal,
            'waktu'         => $waktu,
            'ket'           => $ket,
            'pesanan'       => 1,
            'note'          => $harga,
            'status_note'   => $persen,
            'closing'       => 0,
            ]);
                 return redirect()->back();
    }
    public function transaksiUbahDiskon(Request $request)
    {
        $persen = $request->persen;
        $status = $request->status;
        $hasil = ($persen / 100);
        // Update semua data di transaksi sementara
        Pajak::where('id', 2)->update(['persen' => $hasil, 'status' => $status]);
        return redirect()->back();
    }
    // status bayar 2 = panding, 1=debet, 0=tunai
    public function transaksiSimpanStruk(Request $request)
    {
        $kode_struk = 'TN'.date("YmdHis");
        $id_meja = $request->id_meja;
        $total   = $request->total;
        $total_transaksi   = $request->total_transaksi;
        $id_kasir = Auth::user()->id;
        $nama_kasir = Auth::user()->nama;
        $pembeli = $request->pembeli ?: '-';
        $uang_chas = !empty($request->uang_chas) ? (int) str_replace(['.', 'Rp'], '', $request->uang_chas) : $total;
        $kembalian = $uang_chas - $total;
        $metode = "Tunai";
        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");

        //input data transaksi
        $sementara = TSementara::where('pesanan', 1)
            ->where('slot', $id_meja)
            ->where('id_kasir', $id_kasir)->get();
        foreach ($sementara as $d) {
            // simpan ke transaksi
            Transaksi::create([
                'kode_struk' => $kode_struk,
                'id_item'      => $d->id_item,
                'nama_pesanan' => $d->nama,
                'harga'        => $d->harga,
                'jenis'        => $d->jenis,
                'jumlah'       => $d->jumlah,
                'meja'         => $d->slot,
                'id_kategori'  => $d->id_kategori,
                'tanggal'      => $d->tanggal,
                'waktu'        => $d->waktu,
                'ket'          => $d->ket,
                'pesanan'      => 1,
                'note'         => $d->note,
                'status_note'  => $d->status_note,
                'closing'        => 0,
            ]);
            // hapus dari transaksi_sementara
            TSementara::where('id_transaksi', $d->id_transaksi)->delete();

            // update stok item
            $this->kurangiStokItemDariResep((int) $d->id_item, (int) $d->jumlah);
        }

        // Ambil pajak persen
        $pajak = Pajak::where('id', 1)
        ->where('status', 1)
        ->value('persen');
        // Default pajak = 0 kalau tidak ada
        $pajak = ($pajak !== null) ? $pajak : 0;
        // Ambil diskom persen
        $diskon = Pajak::where('id', 2)
        ->where('status', 1)
        ->value('persen');
         // Default diskon = 0 kalau tidak ada
        $diskon = ($diskon !== null) ? $diskon : 0;
        $persen_diskon = $diskon * 100;
        // hitung diskon
        $jlhdiskon = $total_transaksi * $diskon;
        // hitung pajak
        $jlhpajak = $total_transaksi * $pajak;
              
        

        // simpan ke struk
    $struk = new TStrukSementara();
    $struk->id_kasir            = $id_kasir;
    $struk->nama_kasir          = $nama_kasir;
    $struk->meja                = $id_meja;
    $struk->pembeli             = $pembeli;
    $struk->kode_struk          = $kode_struk;
    $struk->sub_total           = $total_transaksi;
    $struk->total               = $total;
    $struk->uang_chas           = $uang_chas;
    $struk->kembalian           = $kembalian;
    $struk->tgl           = $tanggal;
    $struk->waktu            = $waktu;
    $struk->pajak            = $jlhpajak;
    $struk->persen_diskon       = $persen_diskon;
    $struk->diskon           = $jlhdiskon;
    $struk->metode_pembayaran = 'Tunai';
    $struk->bayar              = 0;
    $struk->closing            = 0;
    $struk->save();

    return redirect('transaksi/struk/'.$kode_struk);
        
        }
// status bayar 2 = panding, 1=debet, 0=tunai
        public function transaksiSimpanNonTunai(Request $request)
    {
        $kode_struk = 'D'.date("YmdHis");
        $id_non_tunai = $request->id_non_tunai;
        $id_meja = $request->id_meja;
        $total   = $request->total;
        $total_transaksi = $request->total_transaksi;
        $id_kasir = Auth::user()->id;
        $nama_kasir = Auth::user()->nama;
        $pembeli = $request->pembeli ?: '-';
        $uang_chas = $request->total;
        $kembalian = $uang_chas - $total;
        $metode = "Non Tunai";
        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");

        //input data transaksi
        $sementara = TSementara::where('ket', 0)
            ->where('pesanan', 1)
            ->where('slot', $id_meja)
            ->where('id_kasir', $id_kasir)->get();
        foreach ($sementara as $d) {
            // simpan ke transaksi
            Transaksi::create([
                'kode_struk' => $kode_struk,
                'id_item'      => $d->id_item,
                'nama_pesanan' => $d->nama,
                'harga'        => $d->harga,
                'jenis'        => $d->jenis,
                'jumlah'       => $d->jumlah,
                'meja'         => $d->slot,
                'id_kategori'  => $d->id_kategori,
                'tanggal'      => $d->tanggal,
                'waktu'        => $d->waktu,
                'ket'          => $d->ket,
                'pesanan'      => 1,
                'note'         => $d->note,
                'status_note'  => $d->status_note,
                'closing'        => 0,
            ]);
            // hapus dari transaksi_sementara
            TSementara::where('id_transaksi', $d->id_transaksi)->delete();

            // update stok item
            $this->kurangiStokItemDariResep((int) $d->id_item, (int) $d->jumlah);
        }

        // Ambil pajak persen
        $pajak = Pajak::where('id', 1)
        ->where('status', 1)
        ->value('persen');
        // Default pajak = 0 kalau tidak ada
        $pajak = ($pajak !== null) ? $pajak : 0;
        // Ambil diskom persen
        $diskon = Pajak::where('id', 2)
        ->where('status', 1)
        ->value('persen');
         // Default diskon = 0 kalau tidak ada
        $diskon = ($diskon !== null) ? $diskon : 0;
        $persen_diskon = $diskon * 100;
        // hitung diskon
        $jlhdiskon = $total_transaksi * $diskon;
        // hitung pajak
        $jlhpajak = $total_transaksi * $pajak;
              
        

        // simpan ke struk
    $struk = new TStrukSementara();
    $struk->id_kasir            = $id_kasir;
    $struk->nama_kasir          = $nama_kasir;
    $struk->meja                = $id_meja;
    $struk->pembeli             = $pembeli;
    $struk->kode_struk          = $kode_struk;
    $struk->sub_total           = $total_transaksi;
    $struk->total               = $total;
    $struk->uang_chas           = $uang_chas;
    $struk->kembalian           = $kembalian;
    $struk->tgl           = $tanggal;
    $struk->waktu            = $waktu;
    $struk->pajak            = $jlhpajak;
    $struk->persen_diskon       = $persen_diskon;
    $struk->diskon           = $jlhdiskon;
    $struk->metode_pembayaran = $id_non_tunai;
    $struk->bayar              = 1;
    $struk->closing            = 0;
    $struk->save();

    return redirect('transaksi/struk/'.$kode_struk);
        
        }
        // status bayar 2 = panding, 1=debet, 0=tunai
        public function transaksiSimpanPending(Request $request)
    {
        $kode_struk = 'P'.date("YmdHis");
        $id_meja = $request->id_meja;
        $total   = $request->total;
        $total_transaksi = $request->total_transaksi;
        $id_kasir = Auth::user()->id;
        $nama_kasir = Auth::user()->nama;
        $pembeli = $request->pembeli ?: '-';
        $uang_chas = $request->total;
        $kembalian = $uang_chas - $total;
        $metode = "Pending";
        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");

        //input data transaksi
        $sementara = TSementara::where('ket', 0)
            ->where('pesanan', 1)
            ->where('slot', $id_meja)
            ->where('id_kasir', $id_kasir)->get();
        foreach ($sementara as $d) {
            // simpan ke transaksi
            Transaksi::create([
                'kode_struk' => $kode_struk,
                'id_item'      => $d->id_item,
                'nama_pesanan' => $d->nama,
                'harga'        => $d->harga,
                'jenis'        => $d->jenis,
                'jumlah'       => $d->jumlah,
                'meja'         => $d->slot,
                'id_kategori'  => $d->id_kategori,
                'tanggal'      => $d->tanggal,
                'waktu'        => $d->waktu,
                'ket'          => $d->ket,
                'pesanan'      => 1,
                'note'         => $d->note,
                'status_note'  => $d->status_note,
                'closing'        => 0,
            ]);
            // hapus dari transaksi_sementara
            TSementara::where('id_transaksi', $d->id_transaksi)->delete();

            // update stok item
            $this->kurangiStokItemDariResep((int) $d->id_item, (int) $d->jumlah);
        }

        // Ambil pajak persen
        $pajak = Pajak::where('id', 1)
        ->where('status', 1)
        ->value('persen');
        // Default pajak = 0 kalau tidak ada
        $pajak = ($pajak !== null) ? $pajak : 0;
        // Ambil diskom persen
        $diskon = Pajak::where('id', 2)
        ->where('status', 1)
        ->value('persen');
         // Default diskon = 0 kalau tidak ada
        $diskon = ($diskon !== null) ? $diskon : 0;
        $persen_diskon = $diskon * 100;
        // hitung diskon
        $jlhdiskon = $total_transaksi * $diskon;
        // hitung pajak
        $jlhpajak = $total_transaksi * $pajak;
              
        

        // simpan ke struk
    $struk = new TStrukSementara();
    $struk->id_kasir            = $id_kasir;
    $struk->nama_kasir          = $nama_kasir;
    $struk->meja                = $id_meja;
    $struk->pembeli             = $pembeli;
    $struk->kode_struk          = $kode_struk;
    $struk->sub_total           = $total_transaksi;
    $struk->total               = $total;
    $struk->uang_chas           = $uang_chas;
    $struk->kembalian           = $kembalian;
    $struk->tgl           = $tanggal;
    $struk->waktu            = $waktu;
    $struk->pajak            = $jlhpajak;
    $struk->persen_diskon       = $persen_diskon;
    $struk->diskon           = $jlhdiskon;
    $struk->metode_pembayaran = 'Pending';
    $struk->bayar              = 2;
    $struk->closing            = 0;
    $struk->save();

    return redirect('transaksi/struk/'.$kode_struk);
        
        }
    public function transaksiStruk($kode)
    {
        $data = TStrukSementara::where('kode_struk', $kode)->first();
        $transaksi = Transaksi::where('kode_struk', $kode)->orderBy('id_transaksi', 'desc')->get();
        //metode pembayaran
        if ($data->bayar == 0) {
            $metode_pembayaran = "Tunai";
        } elseif ($data->bayar == 2) {
            $metode_pembayaran = "Pending [" . $data->pembeli . "]";
        } else {
            // ambil nama bank dari tabel non_tunai
            $bank = DB::table('non_tunai')->where('id', $data->metode_pembayaran)->first();
            $metode_pembayaran = $bank ? ($bank->nama . ' [' . $data->pembeli . ']') : ('Non Tunai [' . $data->pembeli . ']');
        }
        $nama_usaha = DB::table('nama_usaha')->where('id', 1)->first();
        return view('struk', compact('data','transaksi','metode_pembayaran', 'nama_usaha')); 
    }
    public function ListStruk(Request $request)
    {
        // status bayar 2 = panding, 1=debet, 0=tunai
        if(empty($request->tgl)) {
            $tgl = date("Y-m-d");
        } else {
            $tgl = $request->tgl;
        }
        $id_kasir = Auth::user()->id;
        if(Auth::user()->level == 'admin'){
            $struks = TStrukSementara::where('closing', 0)->where('bayar','!=', 2)->whereDate('tgl',$tgl)->get();
        }else{
            $struks = TStrukSementara::where('closing', 0)->where('bayar','!=', 2)->where('id_kasir',$id_kasir)->whereDate('tgl',$tgl)->get();
        }
        return view('list_struks', compact('struks', 'tgl'));
    }
    public function ListStruk_panding()
    {
         // status bayar 2 = panding, 1=debet, 0=tunai
        $id_kasir = Auth::user()->id;
        $struks = TStrukSementara::where('closing', 0)->where('bayar', 2)->where('id_kasir',$id_kasir)->get();
        return view('list_struks', compact('struks'));
    }
    public function TransaksiLunasiPending(Request $request)
    {
        $id = $request->id;
        $metode_pembayaran = $request->metode_pembayaran;
        if($metode_pembayaran == 0){
            $metode = "Tunai";
            $bayar = 0;
        }else{
            $metode = $metode_pembayaran;
            $bayar = 1;
        }
        // Update struk pending menjadi tunai
        TStrukSementara::where('id', $id)
            ->update([
                'bayar' => $bayar,
                'metode_pembayaran' => $metode,
            ]);

        return redirect()->back();
    }
    public function transaksiRetur(Request $request)
    {
        $request->validate([
        'id' => 'required|integer',
        'kode_struk' => 'required|string',
        'alasan' => 'required|string|max:500',
        'kode_admin' => 'nullable|string'
    ]);
        $id = $request->id;
        $kode_struk = $request->kode_struk;
        $akun = auth()->user()->nama;
        $alasan = $request->alasan;
        $kode_admin = $request->kode_admin;

        $user = auth()->user();
        if ($user && $user->level === 'kasir') {
        $request->validate([
            'kode_admin' => 'required|string'
        ]);
        $kode = DB::table('admin_temp_codes')
            ->where('code', $request->kode_admin)
            ->where('purpose', 'retur')
            ->whereNull('used_at')
            ->first();
            if (!$kode) {
            return redirect()->back()->with('error', 'Kode admin tidak valid atau sudah digunakan.');
            }

        DB::table('admin_temp_codes')
            ->where('id', $kode->id)
            ->update([
                'used_at' => Carbon::now(),
                'used_by' => $user->id
            ]);
        }
        // if kode_struk not provided, derive it from the struk record
        if (empty($kode_struk) && $id) {
            $kode_struk = TStrukSementara::where('id', $id)->value('kode_struk');
        }
        //hapus tabel transaksi berdasarkan kode struk dan kembalikan stok item
        $transaksis = Transaksi::where('kode_struk', $kode_struk)->get();
        foreach ($transaksis as $d) {
            // kembalikan stok item dengan mempertimbangkan resep
            $this->tambahStokItemDariResep((int) $d->id_item, (int) $d->jumlah);
        }
        // simpan di log_return untuk struknya dan log_hapus untuk itemnya
        LogReturn::create([
            'kode_struk' => $kode_struk,
            'total'      => TStrukSementara::where('id', $id)->value('total'),
            'nama_akun' => $akun,
            'waktu'      => date("Y-m-d H:i:s"),
            'alasan'      => $alasan,
        ]);
        $transaksis = Transaksi::where('kode_struk', $kode_struk)->get();
        foreach ($transaksis as $d) {
            LogHapus::create([
                'kode_struk' => $kode_struk,
                'nama_item' => $d->nama_pesanan,
                'jumlah'      => $d->jumlah,
                'nama'        => $akun,
                'waktu'      => date("Y-m-d H:i:s"),
                'alasan'      => $alasan,
            ]);
        }

        //hapus data transaksi
        Transaksi::where('kode_struk', $kode_struk)->delete();
        //hapus data struk
        TStrukSementara::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil diretur.');
    }
    public function Laporan()
    {
        $tgl = date("Y-m-d");
        $id_kasir = Auth::user()->id;
        $pengeluaran = Pengeluaran::where('closing', 0)->get();
        $modal = Modal::where('closing', 0)->get();
        $total_modal = Modal::where('closing', 0)->sum('modal');
        $total_pengeluaran = Pengeluaran::where('closing', 0)->sum('jml');
        $total_tunai = TStrukSementara::where('closing', 0)->where('bayar',0)->sum('sub_total');
        $total_tdiskon = TStrukSementara::where('closing', 0)->where('bayar',0)->sum('diskon');
        $total_tpajak = TStrukSementara::where('closing', 0)->where('bayar',0)->sum('pajak');
        $nontunai = TStrukSementara::where('closing', 0)->where('bayar',1)->sum('sub_total');
        $total_ndiskon = TStrukSementara::where('closing', 0)->where('bayar',1)->sum('diskon');
        $total_npajak = TStrukSementara::where('closing', 0)->where('bayar',1)->sum('pajak');
        $total_akhir = $total_tunai + $total_tpajak - $total_tdiskon;
        $total_nontunai = $nontunai + $total_npajak - $total_ndiskon;
        return view('laporan', compact('pengeluaran','modal','total_pengeluaran','total_modal','total_akhir','total_tunai','total_nontunai','total_tdiskon','total_tpajak','total_ndiskon','total_npajak','nontunai','tgl'));
    }
    public function cetak_transaksi()
    {
        $tgl = date("Y-m-d H:i:s");
        $id_kasir = Auth::user()->id;
        $nama_kasir = Auth::user()->nama;
        $total_modal = Modal::where('closing', 0)->sum('modal');
        $debet = DB::table('non_tunai')->get();
        //ambil data modal pertama yang belum di closing
        $modal = Modal::where('closing', 0)->first();
        $tgl_mulai_modal = $modal ? $modal->tanggal : date("Y-m-d");
        $waktu_mulai_modal = $modal ? $modal->waktu : date("H:i:s");
        $total_pengeluaran = Pengeluaran::where('closing', 0)->sum('jml');
        $total_tdiskon = TStrukSementara::where('closing', 0)->where('bayar',0)->sum('diskon');
        $total_tpajak = TStrukSementara::where('closing', 0)->where('bayar',0)->sum('pajak');
        $total_tunai = TStrukSementara::where('closing', 0)->where('bayar',0)->sum('sub_total');
        $total_nontunai = TStrukSementara::where('closing', 0)->where('bayar',1)->sum('sub_total');
        $total_ndiskon = TStrukSementara::where('closing', 0)->where('bayar',1)->sum('diskon');
        $total_npajak = TStrukSementara::where('closing', 0)->where('bayar',1)->sum('pajak');
        $tunai = $total_tunai + $total_tpajak - $total_tdiskon - $total_pengeluaran;
        $nontunai = $total_nontunai + $total_npajak - $total_ndiskon;
        $total_takhir = $tunai + $total_modal;

        $debet_transaksi = TStrukSementara::where('closing', 0)->where('bayar',1)->get();
        $nama_usaha = DB::table('nama_usaha')->where('id', 1)->first();

        return view('cetak_transaksi', compact('total_pengeluaran','total_modal','total_tunai','total_nontunai','nama_kasir','tgl','modal','tgl_mulai_modal','waktu_mulai_modal','total_takhir','tunai','nontunai','total_tpajak','total_tdiskon','total_npajak','total_ndiskon','debet','debet_transaksi', 'nama_usaha'));
    }
    public function tambah_modal(Request $request)
    {
        $modal = str_replace(['.', 'Rp'], '', $request->modal) ?: 0;
        $ket = $request->ket ?: '-';
        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");
        $tambah = new Modal();
        $tambah->modal = $modal;
        $tambah->ket = $ket;
        $tambah->tanggal = $tanggal;
        $tambah->waktu = $waktu;
        $tambah->closing = 0;
        $tambah->save();
        
        return redirect()->back();
    }
    public function tambah_pengeluaran(Request $request)
    {
        $jml = str_replace(['.', 'Rp'], '', $request->jml) ?: 0;
        $ket = $request->ket ?: '-';
        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");
        Pengeluaran::create([
            'jml' => $jml,
            'ket'   => $ket,
            'tanggal' => $tanggal,
            'waktu'   => $waktu,
            'closing' => 0,
        ]);
        return redirect()->back();
    }
    public function hapus_modal($kode)
    {
        // Hapus item berdasarkan kode_unik
        Modal::where('kode_unik', $kode)->delete();
        return redirect()->back();
    }
    public function hapus_pengeluaran($kode)
    {
        // Hapus item berdasarkan kode_unik
        Pengeluaran::where('kode_unik', $kode)->delete();
        return redirect()->back();
    }
//list retur
    public function ListRetur()
    {
        $returs = LogReturn::orderBy('waktu', 'desc')->get();
        return view('list_retur', compact('returs'));
    }

    public function returSebelumBayar(Request $request)
    {
        $tgl_awal = $request->tgl_awal ?: Carbon::now()->startOfMonth()->toDateString();
        $tgl_akhir = $request->tgl_akhir ?: Carbon::now()->toDateString();

        if ($tgl_awal > $tgl_akhir) {
            [$tgl_awal, $tgl_akhir] = [$tgl_akhir, $tgl_awal];
        }

        $returSebelumBayar = LogHapus::whereRaw("NULLIF(TRIM(kode_struk), '') IS NULL")
            ->whereBetween(DB::raw('DATE(waktu)'), [$tgl_awal, $tgl_akhir])
            ->orderBy('waktu', 'desc')
            ->get();

        return view('retur_sebelum_bayar', compact('returSebelumBayar', 'tgl_awal', 'tgl_akhir'));
    }
    //closing
    public function closingTransaksi(Request $request)
    {
        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");
        $kode = 'CL'.date("YmdHis");
        $modal = str_replace(['.', 'Rp'], '', $request->total_modal) ?: 0;
        $pengeluaran = str_replace(['.', 'Rp'], '', $request->total_pengeluaran) ?: 0;
        $transaksi_tunai = str_replace(['.', 'Rp'], '', $request->total_tunai) ?: 0;
        $pajak_tunai = str_replace(['.', 'Rp'], '', $request->total_tpajak) ?: 0;
        $diskon_tunai = str_replace(['.', 'Rp'], '', $request->total_tdiskon) ?: 0;
        $transaksi_nontunai = str_replace(['.', 'Rp'], '', $request->nontunai) ?: 0;
        $pajak_nontunai = str_replace(['.', 'Rp'], '', $request->total_npajak) ?: 0;
        $diskon_nontunai = str_replace(['.', 'Rp'], '', $request->total_ndiskon) ?: 0;
        $total_tunai = str_replace(['.', 'Rp'], '', $request->total_akhir) ?: 0;
        $total_nontunai = str_replace(['.', 'Rp'], '', $request->total_nontunai) ?: 0;
        $total_transaksi = $total_tunai + $total_nontunai;
        $nama_kasir = Auth::user()->nama;
        $uang_inputan = str_replace(['.', 'Rp'], '', $request->uang_inputan) ?: 0;

        //cek kode di table Closing
        $closing = Closing::where('kode_closing', $kode)->first();
        if (!$closing) {
             //simpan ke tabel closing
        Closing::create([
            'kode_closing' => $kode,
            'tanggal' => $tanggal,
            'waktu'   => $waktu,
            'modal'   => $modal,
            'transaksi' => $transaksi_tunai,
            'pajak' => $pajak_tunai,
            'diskon' => $diskon_tunai,
            'pengeluaran' => $pengeluaran,
            'net_total' => $total_tunai,
            'transaksi_debet' => $transaksi_nontunai,
            'pajak_debet' => $pajak_nontunai,
            'diskon_debet' => $diskon_nontunai,
            'total_debet' => $total_nontunai,
            'real_total' => $total_transaksi,
            'nama_closing' => $nama_kasir,
            'uang_inputan' => $uang_inputan,
        ]);
        //ambil semua TStrukSementara yang closing 0 ke Tstruk
        $struks = TStrukSementara::where('closing', 0)->get();
        foreach ($struks as $s) {
            TStruk::create([
                'id_kasir'            => $s->id_kasir,
                'kode_closing'         => $kode,
                'kode_struk'          => $s->kode_struk,
                'sub_total'           => $s->sub_total,
                'total'               => $s->total,
                'uang_chas'           => $s->uang_chas,
                'kembalian'           => $s->kembalian,
                'tgl'           => $s->tgl,
                'waktu'            => $s->waktu,
                'pajak'            => $s->pajak,
                'persen_diskon'       => $s->persen_diskon,
                'diskon'           => $s->diskon,
                'metode_pembayaran' => $s->metode_pembayaran,
                'bayar'              => $s->bayar,
                'closing'            => 1,
            ]);
            //hapus dari TStrukSementara
            TStrukSementara::where('id', $s->id)->delete();
        }
        //update Modal closing menjadi 1 dan tanggal $tanggal dan $waktu
        Modal::where('closing', 0)->update(['closing' => 1, 'tanggal' => $tanggal, 'waktu' => $waktu]);
        //update Pengeluaran closing menjadi 1 dan tanggal $tanggal dan $waktu
        Pengeluaran::where('closing', 0)->update(['closing' => 1, 'tanggal' => $tanggal, 'waktu' => $waktu]);
        // transaksi closing menjadi 1 dan tanggal $tanggal dan $waktu
        Transaksi::where('closing', 0)->update(['closing' => 1, 'tanggal' => $tanggal, 'waktu' => $waktu]);
        return redirect()->back()->with('success', 'Closing berhasil disimpan.');

        }else{
            //jika sudah ada  kode kemblikan
            return redirect()->back();
        }
        

        
    }

    public function transaksiMenu(Request $request)
    {
        $id_menu = $request->id_menu;
        $id_meja = $request->id_meja;
        $jumlah_pesanan = $request->jumlah;
        $catatan = $request->catatan ?: '';
        $id_kasir = Auth::user()->id;
        // Cari item berdasarkan kode
    $item = DB::table('menu')
        ->where('id_menu', $id_menu)
        ->first();

    if ($item) {
        $id_item     = $item->id_menu;
        $nama        = $item->nama_menu;
        $harga  = $item->harga;
        $id_kategori = $item->id_kategori;
        $jumlah_awal = $jumlah_pesanan;

        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");

        // Hitung jumlah di transaksi sementara
        $jumlah_transaksi = TSementara::where('id_item', $id_item)
            ->where('slot', $id_meja)
            ->where('id_kasir', $id_kasir)
            ->where('ket', 0)
            ->sum('jumlah');

        $jumlah = $jumlah_transaksi + $jumlah_awal;

        // Hapus data lama
        TSementara::where('id_item', $id_item)
            ->where('slot', $id_meja)
            ->where('ket', 0)
            ->where('id_kasir', $id_kasir)
            ->delete();

        // Insert ulang data
        TSementara::create([
            'id_item'       => $id_item,
            'id_kasir'      => $id_kasir,
            'nama'          => $nama,
            'harga'         => $harga,
            'jenis'         => $catatan,
            'jumlah'        => $jumlah,
            'slot'          => $id_meja,
            'id_kategori'   => $id_kategori,
            'tanggal'       => $tanggal,
            'waktu'         => $waktu,
            'ket'           => 0,
            'pesanan'       => 1,
            'note'          => '',
            'status_note'   => 0,
            'closing'       => 0,
        ]);
    }   
    return redirect()->back(); 
}
//transaksi menu ubah
public function transaksiMenuUbah(Request $request)
{
    $id_transaksi = $request->id_transaksi;
    $jumlah = $request->jumlah;
    $catatan = $request->catatan ?: '';

    TSementara::where('id_transaksi', $id_transaksi)->update([
        'jumlah' => $jumlah,
        'jenis' => $catatan
    ]);
        return redirect()->back()->with('success', 'Pesanan berhasil diubah.');
}
public function transaksiSimpanStruk1(Request $request)
    {
        $kode_struk = 'TN'.date("YmdHis");
        $id_meja = $request->id_meja;
        $total   = $request->total;
        $total_transaksi   = $request->total_transaksi;
        $id_kasir = Auth::user()->id;
        $nama_kasir = Auth::user()->nama;
        $pembeli = $request->pembeli ?: '-';
        $uang_chas = !empty($request->uang_chas) ? (int) str_replace(['.', 'Rp'], '', $request->uang_chas) : $total;
        $kembalian = $uang_chas - $total;
        $metode = "Tunai";
        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");

        //input data transaksi
        $sementara = TSementara::where('pesanan', 1)
            ->where('slot', $id_meja)
            ->where('id_kasir', $id_kasir)->get();
        foreach ($sementara as $d) {
            // simpan ke transaksi
            Transaksi::create([
                'kode_struk' => $kode_struk,
                'id_item'      => $d->id_item,
                'nama_pesanan' => $d->nama,
                'harga'        => $d->harga,
                'jenis'        => $d->jenis,
                'jumlah'       => $d->jumlah,
                'meja'         => $d->slot,
                'id_kategori'  => $d->id_kategori,
                'tanggal'      => $d->tanggal,
                'waktu'        => $d->waktu,
                'ket'          => $d->ket,
                'pesanan'      => 1,
                'note'         => $d->note,
                'status_note'  => $d->status_note,
                'closing'        => 0,
            ]);
            // hapus dari transaksi_sementara
            TSementara::where('id_transaksi', $d->id_transaksi)->delete();

        }

        // Ambil pajak persen
        $pajak = Pajak::where('id', 1)
        ->where('status', 1)
        ->value('persen');
        // Default pajak = 0 kalau tidak ada
        $pajak = ($pajak !== null) ? $pajak : 0;
        // Ambil diskom persen
        $diskon = Pajak::where('id', 2)
        ->where('status', 1)
        ->value('persen');
         // Default diskon = 0 kalau tidak ada
        $diskon = ($diskon !== null) ? $diskon : 0;
        $persen_diskon = $diskon * 100;
        // hitung diskon
        $jlhdiskon = $total_transaksi * $diskon;
        // hitung pajak
        $jlhpajak = $total_transaksi * $pajak;
              
        

        // simpan ke struk
    $struk = new TStrukSementara();
    $struk->id_kasir            = $id_kasir;
    $struk->nama_kasir          = $nama_kasir;
    $struk->meja                = $id_meja;
    $struk->pembeli             = $pembeli;
    $struk->kode_struk          = $kode_struk;
    $struk->sub_total           = $total_transaksi;
    $struk->total               = $total;
    $struk->uang_chas           = $uang_chas;
    $struk->kembalian           = $kembalian;
    $struk->tgl           = $tanggal;
    $struk->waktu            = $waktu;
    $struk->pajak            = $jlhpajak;
    $struk->persen_diskon       = $persen_diskon;
    $struk->diskon           = $jlhdiskon;
    $struk->metode_pembayaran = 'Tunai';
    $struk->bayar              = 0;
    $struk->closing            = 0;
    $struk->save();

    return redirect('transaksi/struk/'.$kode_struk);
        
        }

        // status bayar 2 = panding, 1=debet, 0=tunai
        public function transaksiSimpanNonTunai1(Request $request)
    {
        $kode_struk = 'D'.date("YmdHis");
        $id_non_tunai = $request->id_non_tunai;
        $id_meja = $request->id_meja;
        $total   = $request->total;
        $total_transaksi = $request->total_transaksi;
        $id_kasir = Auth::user()->id;
        $nama_kasir = Auth::user()->nama;
        $pembeli = $request->pembeli ?: '-';
        $uang_chas = $request->total;
        $kembalian = $uang_chas - $total;
        $metode = "Non Tunai";
        $tanggal = date("Y-m-d");
        $waktu   = date("H:i:s");

        //input data transaksi
        $sementara = TSementara::where('ket', 0)
            ->where('pesanan', 1)
            ->where('slot', $id_meja)
            ->where('id_kasir', $id_kasir)->get();
        foreach ($sementara as $d) {
            // simpan ke transaksi
            Transaksi::create([
                'kode_struk' => $kode_struk,
                'id_item'      => $d->id_item,
                'nama_pesanan' => $d->nama,
                'harga'        => $d->harga,
                'jenis'        => $d->jenis,
                'jumlah'       => $d->jumlah,
                'meja'         => $d->slot,
                'id_kategori'  => $d->id_kategori,
                'tanggal'      => $d->tanggal,
                'waktu'        => $d->waktu,
                'ket'          => $d->ket,
                'pesanan'      => 1,
                'note'         => $d->note,
                'status_note'  => $d->status_note,
                'closing'        => 0,
            ]);
            // hapus dari transaksi_sementara
            TSementara::where('id_transaksi', $d->id_transaksi)->delete();

        }

        // Ambil pajak persen
        $pajak = Pajak::where('id', 1)
        ->where('status', 1)
        ->value('persen');
        // Default pajak = 0 kalau tidak ada
        $pajak = ($pajak !== null) ? $pajak : 0;
        // Ambil diskom persen
        $diskon = Pajak::where('id', 2)
        ->where('status', 1)
        ->value('persen');
         // Default diskon = 0 kalau tidak ada
        $diskon = ($diskon !== null) ? $diskon : 0;
        $persen_diskon = $diskon * 100;
        // hitung diskon
        $jlhdiskon = $total_transaksi * $diskon;
        // hitung pajak
        $jlhpajak = $total_transaksi * $pajak;
              
        

        // simpan ke struk
    $struk = new TStrukSementara();
    $struk->id_kasir            = $id_kasir;
    $struk->nama_kasir          = $nama_kasir;
    $struk->meja                = $id_meja;
    $struk->pembeli             = $pembeli;
    $struk->kode_struk          = $kode_struk;
    $struk->sub_total           = $total_transaksi;
    $struk->total               = $total;
    $struk->uang_chas           = $uang_chas;
    $struk->kembalian           = $kembalian;
    $struk->tgl           = $tanggal;
    $struk->waktu            = $waktu;
    $struk->pajak            = $jlhpajak;
    $struk->persen_diskon       = $persen_diskon;
    $struk->diskon           = $jlhdiskon;
    $struk->metode_pembayaran = $id_non_tunai;
    $struk->bayar              = 1;
    $struk->closing            = 0;
    $struk->save();

    return redirect('transaksi/struk/'.$kode_struk);
        
        }


    private function kurangiStokItemDariResep(int $idItem, int $jumlah): void
    {
        if ($idItem <= 0 || $jumlah <= 0) {
            return;
        }

        // Cek apakah item yang dijual merupakan item ecer di resep_stok
        $resepEcer = DB::table('resep_stok')
            ->where('id_stok_ecer', $idItem)
            ->first();

        // Jika item ecer ditemukan, kurangi stok item utama berdasarkan konversi resep
        if ($resepEcer && !empty($resepEcer->id_stok_utama)) {
            $itemUtama = Item::where('id_item', $resepEcer->id_stok_utama)->first();

            if ($itemUtama) {
                $jumlahPerPaket = max(1, (int) ($resepEcer->jumlah ?? 1));
                $penguranganStokUtama = $jumlah / $jumlahPerPaket;
                $stokBaruUtama = max(0, $itemUtama->stok - $penguranganStokUtama);

                $itemUtama->stok = $stokBaruUtama;
                $itemUtama->save();
            }

            // juga kurangi stok item ecer yang dijual
            $itemEcer = Item::where('id_item', $idItem)->first();
            if ($itemEcer) {
                $stokBaruEcer = max(0, $itemEcer->stok - $jumlah);
                $itemEcer->stok = $stokBaruEcer;
                $itemEcer->save();
            }

            return;
        }

        // Jika tidak ditemukan sebagai item ecer, cek juga apakah ini item utama di resep_stok
        $resepUtama = DB::table('resep_stok')
            ->where('id_stok_utama', $idItem)
            ->first();
            if ($resepUtama && !empty($resepUtama->id_stok_ecer)) {
                $itemEcer = Item::where('id_item', $resepUtama->id_stok_ecer)->first();
                if ($itemEcer) {
                    $jumlahPerPaket = max(1, (int) ($resepUtama->jumlah ?? 1));
                    $penguranganStokEcer = $jumlah * $jumlahPerPaket;
                    $stokBaruEcer = max(0, $itemEcer->stok - $penguranganStokEcer);

                    $itemEcer->stok = $stokBaruEcer;
                    $itemEcer->save();
                }
                //juga kurangi stok item utama yang dijual
            $itemUtama = Item::where('id_item', $idItem)->first();
            if ($itemUtama) {
                $stokBaruUtama = max(0, $itemUtama->stok - $jumlah);
                $itemUtama->stok = $stokBaruUtama;
                $itemUtama->save();
            }
            return;
        }


        // Jika bukan item ecer, gunakan pengurangan stok normal pada item itu sendiri
        $item = Item::where('id_item', $idItem)->first();
        if ($item) {
            $stokBaru = max(0, $item->stok - $jumlah);
            $item->stok = $stokBaru;
            $item->save();
        }
    }

    private function tambahStokItemDariResep(int $idItem, int $jumlah): void
    {
        if ($idItem <= 0 || $jumlah <= 0) {
            return;
        }

        // Cek apakah item yang diretur merupakan item ecer di resep_stok
        $resepEcer = DB::table('resep_stok')
            ->where('id_stok_ecer', $idItem)
            ->first();

        // Jika item ecer ditemukan, tambah stok item utama berdasarkan konversi resep
        if ($resepEcer && !empty($resepEcer->id_stok_utama)) {
            $itemUtama = Item::where('id_item', $resepEcer->id_stok_utama)->first();

            if ($itemUtama) {
                $jumlahPerPaket = max(1, (int) ($resepEcer->jumlah ?? 1));
                $penambahanStokUtama = $jumlah / $jumlahPerPaket;
                $stokBaruUtama = $itemUtama->stok + $penambahanStokUtama;

                $itemUtama->stok = $stokBaruUtama;
                $itemUtama->save();
            }

            // juga tambah stok item ecer yang diretur
            $itemEcer = Item::where('id_item', $idItem)->first();
            if ($itemEcer) {
                $stokBaruEcer = $itemEcer->stok + $jumlah;
                $itemEcer->stok = $stokBaruEcer;
                $itemEcer->save();
            }

            return;
        }

        // Jika tidak ditemukan sebagai item ecer, cek juga apakah ini item utama di resep_stok
        $resepUtama = DB::table('resep_stok')
            ->where('id_stok_utama', $idItem)
            ->first();
        if ($resepUtama && !empty($resepUtama->id_stok_ecer)) {
            $itemEcer = Item::where('id_item', $resepUtama->id_stok_ecer)->first();
            if ($itemEcer) {
                $jumlahPerPaket = max(1, (int) ($resepUtama->jumlah ?? 1));
                $penambahanStokEcer = $jumlah * $jumlahPerPaket;
                $stokBaruEcer = $itemEcer->stok + $penambahanStokEcer;

                $itemEcer->stok = $stokBaruEcer;
                $itemEcer->save();
            }
            // juga tambah stok item utama yang diretur
            $itemUtama = Item::where('id_item', $idItem)->first();
            if ($itemUtama) {
                $stokBaruUtama = $itemUtama->stok + $jumlah;
                $itemUtama->stok = $stokBaruUtama;
                $itemUtama->save();
            }
            return;
        }


        // Jika bukan item ecer, gunakan penambahan stok normal pada item itu sendiri
        $item = Item::where('id_item', $idItem)->first();
        if ($item) {
            $stokBaru = $item->stok + $jumlah;
            $item->stok = $stokBaru;
            $item->save();
        }
    }
}
