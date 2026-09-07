<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Models\Item;
use App\Models\Meja;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\TStok;
use App\Imports\ItemImport;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Auth;


class MasterController extends Controller
{
    //cara membuat kondisi di controller level admin saja yang bisa mengakses
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    //controlller item1 untuk mode kasir coffee shop
    public function master_item1_tambah(Request $request)
    {
        //Helper function untuk parse harga
        $parseHarga = function($harga) {
            $harga = preg_replace('/[^0-9,]/', '', $harga);
            $harga = str_replace(',', '.', $harga);
            return (float) $harga;
        };
        //penamamaan id kategori dari produksi
        if($request->produksi == 'makanan'){
            $id_kategori = 1;
        }elseif($request->produksi == 'minuman'){
            $id_kategori = 2;
        }elseif($request->produksi == 'lainnya'){
            $id_kategori = 3;
        }else{
            $id_kategori = 0;
        }
        DB::table('menu')->insert([
            'nama_menu' => $request->nama_menu,
            'harga' => $parseHarga($request->harga),
            'id_kategori' => $id_kategori,
            'produksi' => $request->produksi,
            'fav' => $request->fav ?? 0,
            'stok' => $request->stok ?? 0,
        ]);
        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
    }
    public function master_item1_edit(Request $request)
    {
        //Helper function untuk parse harga
        $parseHarga = function($harga) {
            $harga = preg_replace('/[^0-9,]/', '', $harga);
            $harga = str_replace(',', '.', $harga);
            return (float) $harga;
        };
        if($request->produksi == 'makanan'){
            $id_kategori = 1;
        }elseif($request->produksi == 'minuman'){
            $id_kategori = 2;
        }elseif($request->produksi == 'lainnya'){
            $id_kategori = 3;
        }else{
            $id_kategori = 0;
        }
        DB::table('menu')->where('id_menu', $request->id)->update([
            'nama_menu' => $request->nama_menu,
            'harga' => $parseHarga($request->harga),
            'id_kategori' => $id_kategori,
            'produksi' => $request->produksi,
            'fav' => $request->fav ?? 0,
            'stok' => $request->stok ?? 0,
        ]);
        return redirect()->back()->with('success', 'Data Berhasil Diupdate');
    }
    public function master_item1_hapus($id)
    {
        DB::table('menu')->where('id_menu', $id)->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }
    //controller master item tambah edit hapus join dengan kategori dan satuan
    public function master_item()
    {
        $pengaturan = DB::table('pengaturan')->where('id', 1)->first();
        if ($pengaturan && $pengaturan->status == 0) {
        //item join kategori dan satuan - data akan dimuat via AJAX
        $kategori = Kategori::all();
        $satuan = Satuan::all();
        $supplier = Supplier::all();
        //stok menipis
        $stok_min = Item::whereColumn('stok', '<=', 'stok_minimal')->get();
        $stokmenipis = $stok_min->count();
        if ($stokmenipis > 0) {
            session()->flash('stokmenipis', 'Terdapat '.$stokmenipis.' item dengan stok menipis.');
        }
        $kode_terakhir = DB::table('nomorkode')->where('id', 1)->first();
        return view('master.item', compact('kategori', 'satuan', 'supplier', 'kode_terakhir', 'stokmenipis'));
        } else {
        $data = DB::table('menu')->get();
        return view('master.item1', compact('data'));
        }
    }

    public function master_item_stok_menipis()
    {
        $pengaturan = DB::table('pengaturan')->where('id', 1)->first();
        if (!$pengaturan || $pengaturan->status != 0) {
            return redirect('master/item')->with('error', 'Mode kasir saat ini tidak menggunakan master item stok.');
        }

        $items = DB::table('item')
            ->leftJoin('satuan', 'item.satuan', '=', 'satuan.id')
            ->leftJoin('katagori', 'item.id_kategori', '=', 'katagori.id')
            ->select('item.*', 'satuan.nama_satuan', 'katagori.nama_katagori')
            ->whereColumn('item.stok', '<=', 'item.stok_minimal')
            ->orderBy('item.stok', 'asc')
            ->get();

        return view('master.stok_menipis', compact('items'));
    }

    public function master_item_stok_menipis_tambah(Request $request)
    {
        $request->validate([
            'id_item' => 'required|integer',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        $item = Item::find($request->id_item);
        if (!$item) {
            return redirect()->back()->with('error', 'Item tidak ditemukan.');
        }

        $parseHarga = function($harga) {
            $harga = preg_replace('/[^0-9,]/', '', $harga ?? '0');
            $harga = str_replace(',', '.', $harga);
            return (float) $harga;
        };

        TStok::create([
            'id_stok' => $item->id_item,
            'nama_stok' => $item->nama,
            'jumlah' => $request->jumlah,
            'harga_beli' => $parseHarga($request->harga_beli),
            'keterangan' => '[Stok Menipis] ' . ($request->keterangan ?: 'Penambahan stok dari halaman stok menipis'),
            'tanggal' => $request->tanggal,
        ]);

        $item->stok = $item->stok + $request->jumlah;
        $item->save();

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan dan tersimpan di penambahan stok.');
    }

    public function master_item_stok_menipis_hapus(Request $request)
    {
        $request->validate([
            'id_item' => 'required|integer',
        ]);

        $item = Item::find($request->id_item);
        if (!$item) {
            return redirect()->back()->with('error', 'Item tidak ditemukan.');
        }

        $item->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari database.');
    }

    public function master_item_tambah(Request $request)
    {
        //cek apakah kode sudah ada
        $cek_kode = Item::where('kode', $request->kode)->first();
        if($cek_kode){
            return redirect()->back()->with('error', 'Kode sudah ada, silahkan gunakan kode lain.');
        }
        //Helper function untuk parse harga
        $parseHarga = function($harga) {
            $harga = preg_replace('/[^0-9,]/', '', $harga);
            $harga = str_replace(',', '.', $harga);
            return (float) $harga;
        };
        
        //cek dan update kode terakhir di tabel nomorkode
        $kode_terakhir = DB::table('nomorkode')->where('id', 1)->first();
        $kode_numeric = (int)$request->kode; // Konversi ke integer
        if($kode_numeric - 1 == $kode_terakhir->kode_terakhir){
            DB::table('nomorkode')->where('id', 1)->update([
            'kode_terakhir' => $kode_numeric,
        ]);
            
        }
        Item::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'harga_jual' => $parseHarga($request->harga_jual),
            'harga_beli' => $parseHarga($request->harga_beli),
            'harga_reseller' => $parseHarga($request->harga_reseller),
            'satuan' => $request->satuan,
            'id_kategori' => $request->id_kategori,
            'supplier' => $request->supplier,
            'stok' => $request->stok,
            'stok_minimal' => $request->stok_minimal,
            'harga_grosir' => $parseHarga($request->harga_grosir),
            'min_grosir' => $request->min_grosir,
            'created_at' => now(),
            'updated_at' => now(),

        ]);
        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
    }
    public function master_item_edit(Request $request)
    {
        //Helper function untuk parse harga
        $parseHarga = function($harga) {
            $harga = preg_replace('/[^0-9,]/', '', $harga);
            $harga = str_replace(',', '.', $harga);
            return (float) $harga;
        };
        
        Item::where('id_item', $request->id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'harga_jual' => $parseHarga($request->harga_jual),
            'harga_beli' => $parseHarga($request->harga_beli),
            'harga_reseller' => $parseHarga($request->harga_reseller),
            'satuan' => $request->satuan,
            'id_kategori' => $request->id_kategori,
            'supplier' => $request->supplier,
            'stok' => $request->stok,
            'stok_minimal' => $request->stok_minimal,
            'harga_grosir' => $parseHarga($request->harga_grosir),
            'min_grosir' => $request->min_grosir,
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Data Berhasil Diupdate');
    }
    public function master_item_hapus($id)
    {
        Item::where('id_item', $id)->delete();
        return redirect()->back()->with('delete', 'Data Berhasil Dihapus');
    }

    public function master_item_cek_kode($kode)
    {
        $cek = Item::where('kode', $kode)->first();
        return response()->json(['exists' => $cek ? true : false]);
    }

    public function master_item_import_template()
    {
        $candidateFiles = [
            storage_path('app/templates/template-import-item.xlsx'),
            storage_path('app/templates/template-import-item.xls'),
            storage_path('app/templates/template-import-item.csv'),
            public_path('templates/template-import-item.xlsx'),
            public_path('templates/template-import-item.xls'),
            public_path('templates/template-import-item.csv'),
        ];

        $templatePath = null;
        foreach ($candidateFiles as $filePath) {
            if (File::exists($filePath)) {
                $templatePath = $filePath;
                break;
            }
        }

        if (!$templatePath) {
            return redirect('master/item')->with('error', 'File template tidak ditemukan. Simpan file di storage/app/templates/ atau public/templates/ dengan nama template-import-item.xlsx/.xls/.csv');
        }

        return response()->download($templatePath, basename($templatePath));
    }

    public function master_item_import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new ItemImport, $request->file('file_excel'));

        return back()->with('success', 'Data berhasil diimport!');
        
    }

    public function master_item_data(Request $request)
    {
        $query = DB::table('item')
            ->join('katagori', 'item.id_kategori', '=', 'katagori.id')
            ->join('satuan', 'item.satuan', '=', 'satuan.id')
            ->select('item.*', 'katagori.nama_katagori', 'satuan.nama_satuan');

        // Handle search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('item.kode', 'like', '%' . $search . '%')
                  ->orWhere('item.nama', 'like', '%' . $search . '%')
                  ->orWhere('katagori.nama_katagori', 'like', '%' . $search . '%')
                  ->orWhere('satuan.nama_satuan', 'like', '%' . $search . '%');
            });
        }

        // Handle ordering
        if ($request->has('order')) {
            $orderColumn = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            // DataTables on the client includes a leading non-data column (row counter).
            // Map the client column index to the server-side data columns array by subtracting 1.
            $columns = [
                'item.kode',
                'item.nama',
                'item.harga_jual',
                'item.harga_beli',
                'item.harga_reseller',
                'item.harga_grosir',
                'item.min_grosir',
                'satuan.nama_satuan',
                'katagori.nama_katagori',
                'item.supplier',
                'item.stok',
                'item.stok_minimal',
            ];
            $orderIndex = $orderColumn - 1;
            if (isset($columns[$orderIndex])) {
                $query->orderBy($columns[$orderIndex], $orderDir);
            }
        }

        $totalRecords = $query->count();

        // Handle pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $data = $query->skip($start)->take($length)->get();

        $response = [
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data->map(function($item) {
                return [
                    $item->kode,
                    $item->nama,
                    number_format($item->harga_jual),
                    number_format($item->harga_beli),
                    number_format($item->harga_reseller),
                    number_format($item->harga_grosir),
                    number_format($item->min_grosir),
                    $item->nama_satuan,
                    $item->nama_katagori,
                    $item->supplier,
                    $item->stok,
                    $item->stok_minimal,
                    '<a href="' . url('master/item/barcode/' . $item->id_item) . '" class="btn btn-primary btn-fill">Barcode</a> <a href="#" class="btn btn-warning btn-fill" data-id="' . $item->id_item . '">Ubah</a> <a href="' . url('master/item/hapus/' . $item->id_item) . '" class="btn btn-danger btn-fill">Hapus</a>'
                ];
            })
        ];

        return response()->json($response);
    }

    public function master_item_get($id)
    {
        $item = DB::table('item')
            ->join('katagori', 'item.id_kategori', '=', 'katagori.id')
            ->join('satuan', 'item.satuan', '=', 'satuan.id')
            ->select('item.*', 'katagori.nama_katagori', 'satuan.nama_satuan')
            ->where('item.id_item', $id)
            ->first();

        if ($item) {
            $item->ecer = isset($item->ecer) ? (int) $item->ecer : 0;
        }

        return response()->json($item);
    }

    public function master_item_barcode($id)
    {
        $item = Item::where('id_item', $id)->first();

        if (!$item) {
            return redirect('master/item')->with('error', 'Item tidak ditemukan.');
        }

        $generator = new BarcodeGeneratorPNG();
        $kode = (string) $item->kode;

        // Jika 13 digit => EAN-13, selain itu => CODE128
        if (strlen($kode) === 13 && ctype_digit($kode)) {
            $kode_barcode = str_pad(substr($kode, 0, 12), 12, '0', STR_PAD_LEFT);
            $barcode = $generator->getBarcode($kode_barcode, $generator::TYPE_EAN_13, 2, 60);
        } else {
            $kode_barcode = $kode;
            $barcode = $generator->getBarcode($kode_barcode, $generator::TYPE_CODE_128, 2, 60);
        }

        $barcodeBase64 = 'data:image/png;base64,' . base64_encode($barcode);

        return view('master.item_barcode', compact('item', 'barcodeBase64', 'kode_barcode'));
    }

    /**
     * Ecer (resep_stok) management
     */
    public function master_item_ecer()
    {
        $items = DB::table('item')
            ->leftJoin('satuan', 'item.satuan', '=', 'satuan.id')
            ->select('item.*', 'satuan.nama_satuan')
            ->orderBy('item.nama')
            ->get();

        return view('master.ecer', compact('items'));
    }

    public function master_item_ecer_data(Request $request)
    {
        $query = DB::table('resep_stok')
            ->leftJoin('item as utama', 'resep_stok.id_stok_utama', '=', 'utama.id_item')
            ->leftJoin('item as ecer', 'resep_stok.id_stok_ecer', '=', 'ecer.id_item')
                ->leftJoin('satuan as satuan_utama', 'utama.satuan', '=', 'satuan_utama.id')
                ->leftJoin('satuan as satuan_ecer', 'ecer.satuan', '=', 'satuan_ecer.id')
                ->select(
                    'resep_stok.*',
                    'utama.nama as nama_utama',
                    'ecer.nama as nama_ecer',
                    'utama.stok as stok_utama',
                    'ecer.stok as stok_ecer',
                    'satuan_utama.nama_satuan as satuan_utama',
                    'satuan_ecer.nama_satuan as satuan_ecer'
                );

        $total = $query->count();
        $data = $query->get();

        $response = [
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data->map(function($row) {
                    $stokUtamaText = isset($row->stok_utama) ? $row->stok_utama . ' ' . ($row->satuan_utama ?? '') : '-';
                    $stokEcerText = isset($row->stok_ecer) ? number_format($row->stok_ecer) . ' ' . ($row->satuan_ecer ?? '') : '-';
                    return [
                        $row->id,
                        $row->nama_utama ?? '-',
                        $stokUtamaText,
                        $row->nama_ecer ?? '-',
                        $stokEcerText,
                        $row->jumlah ?? 0,
                        '<a href="#" class="btn btn-warning btn-fill btn-edit-ecer" data-id="' . ($row->id ?? '') . '" data-stok-utama="' . ($row->id_stok_utama ?? '') . '" data-stok-ecer="' . ($row->id_stok_ecer ?? '') . '" data-jumlah="' . ($row->jumlah ?? 0) . '">Ubah</a> <a href="' . url('master/item/eceran/hapus/' . ($row->id ?? '')) . '" class="btn btn-danger btn-fill btn-delete-ecer" data-id="' . ($row->id ?? '') . '">Hapus</a>'
                    ];
            })
        ];

        return response()->json($response);
    }

    public function master_item_ecer_tambah(Request $request)
    {
        $request->validate([
            'id_stok_utama' => 'required|integer',
            'id_stok_ecer' => 'required|integer',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::table('resep_stok')->insert([
            'id_stok_utama' => $request->id_stok_utama,
            'id_stok_ecer' => $request->id_stok_ecer,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->back()->with('success', 'Resep ecer berhasil ditambahkan');
    }

    public function master_item_ecer_edit(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'id_stok_utama' => 'required|integer',
            'id_stok_ecer' => 'required|integer',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::table('resep_stok')->where('id', $request->id)->update([
            'id_stok_utama' => $request->id_stok_utama,
            'id_stok_ecer' => $request->id_stok_ecer,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->back()->with('success', 'Resep ecer berhasil diperbarui');
    }

    public function master_item_ecer_hapus($id)
    {
        DB::table('resep_stok')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Resep ecer berhasil dihapus');
    }

    //controller master kategori tambah edit hapus
    public function master_kategori()
    {
        $data = Kategori::all();
        return view('master.kategori', compact('data'));
    }
    public function master_kategori_tambah(Request $request)
    {
        Kategori::create([
            'nama_katagori' => $request->nama_katagori,
        ]);
        return redirect('master/kategori');
    }
    public function master_kategori_edit(Request $request)
    {
        Kategori::where('id', $request->id)->update([
            'nama_katagori' => $request->nama_katagori,
        ]);
        return redirect()->back();
    }
    public function master_kategori_hapus($id)
    {
        Kategori::where('id', $id)->delete();
        return redirect()->back();
    }
    
    //controller master satuan tambah edit hapus
    public function master_satuan()
    {
        $data = Satuan::all();
        return view('master.satuan', compact('data'));
    }
    public function master_satuan_tambah(Request $request)
    {
        Satuan::create([
            'nama_satuan' => $request->nama_satuan,
        ]);
        return redirect()->back();
    }
    public function master_satuan_edit(Request $request)
    {
        Satuan::where('id', $request->id)->update([
            'nama_satuan' => $request->nama_satuan,
        ]);
        return redirect()->back();
    }
    public function master_satuan_hapus($id)
    {
        Satuan::where('id', $id)->delete();
        return redirect()->back();
    }
    //controller master slot tambah edit hapus
    public function master_slot()
    {
        $data = Meja::all();
        return view('master.slot', compact('data'));
    }
    public function master_slot_tambah(Request $request)
    {
        Meja::create([
            'kode_meja' => $request->kode_meja,
        ]);
        return redirect()->back();
    }
    public function master_slot_edit(Request $request)
    {
        Meja::where('id', $request->id)->update([
            'kode_meja' => $request->kode_meja,
        ]);
        return redirect()->back();
    }
    public function master_slot_hapus($id)
    {
        Meja::where('id', $id)->delete();
        return redirect()->back();
    }
    public function master_user()
    {
        $data = User::all();
        return view('master.user', compact('data'));
    }
    public function master_user_tambah(Request $request)
    {
        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'level' => $request->level,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back();
    }
    public function master_user_edit(Request $request)
    {
        if ($request->password) {
            User::where('id', $request->id)->update([
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'level' => $request->level,
                'updated_at' => now(),
            ]);
        } else {
            User::where('id', $request->id)->update([
                'nama' => $request->nama,
                'username' => $request->username,
                'level' => $request->level,
                'updated_at' => now(),
            ]);
        }
        return redirect()->back();
    }
    public function master_user_hapus($id)
    {
        User::where('id', $id)->delete();
        return redirect()->back();
    }
    //controller master supplier tambah edit hapus
    public function master_supplier()
    {
        $data = Supplier::all();
        return view('master.supplier', compact('data'));
    }
    public function master_supplier_tambah(Request $request)
    {
        Supplier::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);
        return redirect()->back();
    }
    public function master_supplier_edit(Request $request)
    {
        Supplier::where('id', $request->id)->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);
        return redirect()->back();
    }
    public function master_supplier_hapus($id)
    {
        Supplier::where('id', $id)->delete();
        return redirect()->back();
    }
//non tunai Master
    public function master_non_tunai()
    {
        $data = DB::table('non_tunai')->get();
        return view('master.non_tunai', compact('data'));
    }
    public function master_non_tunai_tambah(Request $request)
    {
        DB::table('non_tunai')->insert([
            'nama' => $request->nama,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back();
    }
    public function master_non_tunai_edit(Request $request)
    {
        DB::table('non_tunai')->where('id', $request->id)->update([
            'nama' => $request->nama,
            'updated_at' => now(),
        ]);
        return redirect()->back();
    }
    public function master_non_tunai_hapus($id)
    {
        DB::table('non_tunai')->where('id', $id)->delete();
        return redirect()->back();
    }
    public function master_generate_kode()
    {
        $data = DB::table('admin_temp_codes')
            ->where('used_by', '0')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('master.generate_kode', compact('data'));
    }
        
    public function generateKodeRetur(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->level !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya admin yang dapat menghasilkan kode retur.');
        }

        $request->validate([
            'masa_berlaku' => 'nullable|integer|min:1|max:60'
        ]);

        $minutes   = (int) ($request->masa_berlaku ?? 5);
        $expiresAt = Carbon::now()->addMinutes($minutes);

        do {
            $code = 'RTR-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $exists = DB::table('admin_temp_codes')->where('code', $code)->exists();
        } while ($exists);

        DB::table('admin_temp_codes')->insert([
            'code'         => $code,
            'purpose'      => 'retur',
            'generated_by' => $user->id,
            'expires_at'   => $expiresAt,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with('success', 'Kode retur berhasil dibuat');
    }
    public function master_generate_kode_hapus($id)
    {
        DB::table('admin_temp_codes')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Kode berhasil dihapus');
    }


}
