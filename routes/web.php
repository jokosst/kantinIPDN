<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//login route
Route::get('login', [LoginController::class, 'login'])->name('login');
Route::post('login', [LoginController::class, 'loginPost']);

// Route::post('tambahuser', [LoginController::class, 'tambah']);
//tutup login route

Route::middleware(['auth'])->group(function () {
Route::get('/', [HomeController::class, 'home']);
Route::get('logout', [LoginController::class, 'logout']);

//transaksi routes
Route::get('transaksi', [HomeController::class, 'transaksi']);
Route::get('transaksi/{id}', [HomeController::class, 'transaksiMeja']);
Route::post('transaksi/tambah_kode', [HomeController::class, 'transaksiTambahKode']);
Route::post('transaksi/tambah_lainnya', [HomeController::class, 'transaksiTambahLainnya']);
Route::post('/cari_item', [HomeController::class, 'cari_item'])->name('cari_item');
Route::post('/cari_item_member', [HomeController::class, 'cari_item_member'])->name('cari_item_member');
Route::get('cari_item/tambah/{meja}/{id}', [HomeController::class, 'cariItemTambah']);
Route::get('cari_item_member/tambah/{meja}/{id}', [HomeController::class, 'cariItemTambahMember']);
Route::get('transaksi/hapus/{id}/{ket}', [HomeController::class, 'transaksiHapusItem']);
Route::post('transaksi/ubah', [HomeController::class, 'transaksiUbahItem']);
Route::post('transaksi_ubah_diskon', [HomeController::class, 'transaksiUbahDiskon']);
Route::post('transaksi_simpan_struk', [HomeController::class, 'transaksiSimpanStruk']);
Route::post('transaksi_pending', [HomeController::class, 'transaksiSimpanPending']);
Route::post('transaksi_non_tunai', [HomeController::class, 'transaksiSimpanNonTunai']);
Route::get('transaksi/struk/{kode}', [HomeController::class, 'transaksiStruk']);
Route::get('liststruk', [HomeController::class, 'ListStruk']);
Route::post('liststruk', [HomeController::class, 'ListStruk']);
Route::get('liststruk_panding', [HomeController::class, 'ListStruk_panding']);
Route::post('transaksi/lunasi', [HomeController::class, 'transaksiLunasiPending']);
Route::post('transaksi/return', [HomeController::class, 'transaksiRetur']);
Route::post('transaksi/retur', [HomeController::class, 'transaksiRetur']);
Route::get('laporan', [HomeController::class, 'Laporan']);
Route::get('cetak_transaksi', [HomeController::class, 'cetak_transaksi']);
Route::post('laporan/modal/tambah', [HomeController::class, 'tambah_modal']);
Route::post('laporan/pengeluaran/tambah', [HomeController::class, 'tambah_pengeluaran']);
Route::get('laporan/modal/hapus/{kode}', [HomeController::class, 'hapus_modal']);
Route::get('laporan/pengeluaran/hapus/{kode}', [HomeController::class, 'hapus_pengeluaran']);
Route::get('retur', [HomeController::class, 'ListRetur']);
Route::get('retur_sebelum_bayar', [HomeController::class, 'returSebelumBayar']);
Route::post('closing', [HomeController::class, 'closingTransaksi']);
//master kategori, item, satuan, slot
//transaksi menu tipe kasir coffee shop
Route::post('transaksi_menu', [HomeController::class, 'transaksiMenu']);
Route::post('transaksi_menu/ubah', [HomeController::class, 'transaksiMenuUbah']);
Route::post('transaksi_simpan_struk1', [HomeController::class, 'transaksiSimpanStruk1']);
Route::post('transaksi_non_tunai1', [HomeController::class, 'transaksiSimpanNonTunai1']);
//user master item
Route::get('master/item', [MasterController::class, 'master_item']);
//stok ecer (resep_stok)
Route::get('master/item/eceran', [MasterController::class, 'master_item_ecer']);
Route::get('master/item/eceran/data', [MasterController::class, 'master_item_ecer_data']);
Route::post('master/item/eceran/tambah', [MasterController::class, 'master_item_ecer_tambah']);
Route::post('master/item/eceran/edit', [MasterController::class, 'master_item_ecer_edit']);
Route::get('master/item/eceran/hapus/{id}', [MasterController::class, 'master_item_ecer_hapus']);
Route::get('master/item/data', [MasterController::class, 'master_item_data']);
Route::get('master/item/get/{id}', [MasterController::class, 'master_item_get']);
Route::get('master/item/barcode/{id}', [MasterController::class, 'master_item_barcode']);
Route::post('master/item/tambah', [MasterController::class, 'master_item_tambah']);
Route::post('master/item/edit', [MasterController::class, 'master_item_edit']);
Route::get('master/item/hapus/{id}', [MasterController::class, 'master_item_hapus']);
Route::post('master/item/scan', [MasterController::class, 'master_item_scan']);
Route::get('master/item/cek_kode/{kode}', [MasterController::class, 'master_item_cek_kode']);
Route::post('master/item/import', [MasterController::class, 'master_item_import']);
Route::get('master/item/import/template', [MasterController::class, 'master_item_import_template']);
Route::get('master/item/stok_menipis', [MasterController::class, 'master_item_stok_menipis']);
Route::post('master/item/stok_menipis/tambah', [MasterController::class, 'master_item_stok_menipis_tambah']);
Route::post('master/item/stok_menipis/hapus', [MasterController::class, 'master_item_stok_menipis_hapus']);
//master item1 untuk mode kasir coffee shop
Route::post('master/item1/tambah', [MasterController::class, 'master_item1_tambah']);
Route::post('master/item1/edit', [MasterController::class, 'master_item1_edit']);
Route::get('master/item1/hapus/{id}', [MasterController::class, 'master_item1_hapus']);
//master kategori
Route::get('master/kategori', [MasterController::class, 'master_kategori']);
Route::post('master/kategori/tambah', [MasterController::class, 'master_kategori_tambah']);
Route::post('master/kategori/edit', [MasterController::class, 'master_kategori_edit']);
Route::get('master/kategori/hapus/{id}', [MasterController::class, 'master_kategori_hapus']);
//master satuan
Route::get('master/satuan', [MasterController::class, 'master_satuan']);
Route::post('master/satuan/tambah', [MasterController::class, 'master_satuan_tambah']);
Route::post('master/satuan/edit', [MasterController::class, 'master_satuan_edit']);
Route::get('master/satuan/hapus/{id}', [MasterController::class, 'master_satuan_hapus']);
//master slot
Route::get('master/slot', [MasterController::class, 'master_slot']);
Route::post('master/slot/tambah', [MasterController::class, 'master_slot_tambah']);
Route::post('master/slot/edit', [MasterController::class, 'master_slot_edit']);
Route::get('master/slot/hapus/{id}', [MasterController::class, 'master_slot_hapus']);
//user master
Route::get('master/user', [MasterController::class, 'master_user']);
Route::post('master/user/tambah', [MasterController::class, 'master_user_tambah']);
Route::post('master/user/edit', [MasterController::class, 'master_user_edit']);
Route::get('master/user/hapus/{id}', [MasterController::class, 'master_user_hapus']);
//supplier master
Route::get('master/supplier', [MasterController::class, 'master_supplier']);
Route::post('master/supplier/tambah', [MasterController::class, 'master_supplier_tambah']);
Route::post('master/supplier/edit', [MasterController::class, 'master_supplier_edit']);
Route::get('master/supplier/hapus/{id}', [MasterController::class, 'master_supplier_hapus']);
//non tunai master
Route::get('master/non_tunai', [MasterController::class, 'master_non_tunai']);
Route::post('master/non_tunai/tambah', [MasterController::class, 'master_non_tunai_tambah']);
Route::post('master/non_tunai/edit', [MasterController::class, 'master_non_tunai_edit']);
Route::get('master/non_tunai/hapus/{id}', [MasterController::class, 'master_non_tunai_hapus']);
//tutup master
//generate kode
Route::get('master/generate_kode', [MasterController::class, 'master_generate_kode']);
Route::post('master/generateKodeRetur', [MasterController::class, 'generateKodeRetur']);
Route::get('master/generate_kode/hapus/{id}', [MasterController::class, 'master_generate_kode_hapus']);

//pembelian masuk
Route::get('pembelian/masuk', [PembelianController::class, 'pembelian_masuk']);
Route::post('pembelian/masuk', [PembelianController::class, 'pembelian_masuk']);
Route::post('pembelian/masuk/tambah', [PembelianController::class, 'pembelian_masuk_tambah']);
Route::get('pembelian/masuk/hapus/{id}', [PembelianController::class, 'pembelian_masuk_hapus']);
Route::get('pembelian/retur', [PembelianController::class, 'pembelian_retur']);
Route::post('pembelian/retur', [PembelianController::class, 'pembelian_retur']);
//tutup pembelian masuk

//penambahan stok, stok opname
Route::get('stok/penambahan', [StokController::class, 'stok_penambahan']);
Route::post('stok/penambahan', [StokController::class, 'stok_penambahan']);
Route::post('stok/penambahan/tambah', [StokController::class, 'stok_penambahan_tambah']);
Route::get('stok/penambahan/hapus/{id}', [StokController::class, 'stok_penambahan_hapus']);
Route::get('stok/opname', [StokController::class, 'stok_opname']);
Route::post('stok/opname', [StokController::class, 'stok_opname']);
Route::post('stok/opname/tambah', [StokController::class, 'stok_opname_tambah']);
Route::post('stok/opname/tambah_multi', [StokController::class, 'stok_opname_tambah_multi']);
//tutup penambahan stok, stok opname

//laporan penjualan, pembelian dan stok
Route::get('laporan/penjualan', [LaporanController::class, 'laporan_penjualan']);
Route::post('laporan/penjualan', [LaporanController::class, 'laporan_penjualan']);
Route::get('laporan/arus_stok', [LaporanController::class, 'laporan_arus_stok']);
Route::post('laporan/arus_stok', [LaporanController::class, 'laporan_arus_stok']);
Route::get('laporan/closing', [LaporanController::class, 'laporan_closing']);
Route::post('laporan/closing', [LaporanController::class, 'laporan_closing']);
Route::get('laporan/closing/detail/{kode}', [LaporanController::class, 'laporan_closing_detail']);
Route::get('laporan/closing/transaksi/{kode}', [LaporanController::class, 'laporan_closing_transaksi']);
Route::get('laporan/pembelian', [LaporanController::class, 'laporan_pembelian']);
Route::post('laporan/pembelian', [LaporanController::class, 'laporan_pembelian']);
Route::get('laporan/stok', [LaporanController::class, 'laporan_stok']);
Route::post('laporan/stok', [LaporanController::class, 'laporan_stok']);
//pendapatan
Route::get('laporan/pendapatan', [LaporanController::class, 'laporan_pendapatan']);
Route::post('laporan/pendapatan', [LaporanController::class, 'laporan_pendapatan']);
//tutup laporan

//pengaturan
Route::get('pengaturan', [PengaturanController::class, 'pengaturan']);
Route::post('pengaturan/simpan', [PengaturanController::class, 'pengaturan_simpan']);
//pengaturan pajak
Route::post('pengaturan/pajak/simpan', [PengaturanController::class, 'pengaturan_pajak_simpan']);
//reset kode item
Route::get('pengaturan/reset_kode_item', [PengaturanController::class, 'pengaturan_reset_kode_item']);
//pengaturan mode kasir
Route::post('pengaturan/mode/simpan', [PengaturanController::class, 'pengaturan_mode_simpan']);
//tutup pengaturan

//grafik
Route::get('grafik/penjualan', [LaporanController::class, 'grafik_penjualan']);
Route::post('grafik/penjualan', [LaporanController::class, 'grafik_penjualan']);
//grafik bulanan
Route::get('grafik/penjualan_bulanan', [LaporanController::class, 'grafik_penjualan_bulanan']);
Route::post('grafik/penjualan_bulanan', [LaporanController::class, 'grafik_penjualan_bulanan']);
//grafik tahunan
Route::get('grafik/penjualan_tahunan', [LaporanController::class, 'grafik_penjualan_tahunan']);
//tutup grafik

});