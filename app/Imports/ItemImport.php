<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Kategori;
use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $normalize = function ($value) {
            return trim((string) $value);
        };

        $parsePrice = function ($value) {
            $value = preg_replace('/[^0-9,]/', '', (string) $value);
            $value = str_replace(',', '.', $value);
            return (float) ($value === '' ? 0 : $value);
        };

        $parseInt = function ($value) {
            $value = preg_replace('/[^0-9-]/', '', (string) $value);
            return (int) ($value === '' ? 0 : $value);
        };

        $kode = $normalize($row['kode'] ?? '');
        $nama = $normalize($row['nama'] ?? '');
        $namaSatuan = $normalize($row['satuan'] ?? '');
        $namaKategori = $normalize($row['kategori'] ?? '');

        if ($kode === '' || $nama === '' || $namaSatuan === '' || $namaKategori === '') {
            return null;
        }

        if (Item::where('kode', $kode)->exists()) {
            return null;
        }

        $satuan = Satuan::whereRaw('TRIM(nama_satuan) = ?', [$namaSatuan])->first();
        if (!$satuan) {
            $satuan = Satuan::create([
                'nama_satuan' => $namaSatuan,
            ]);
        }

        $kategori = Kategori::whereRaw('TRIM(nama_katagori) = ?', [$namaKategori])->first();
        if (!$kategori) {
            $kategori = Kategori::create([
                'nama_katagori' => $namaKategori,
            ]);
        }

        return new Item([
            'kode' => $kode,
            'nama' => $nama,
            'harga_jual' => $parsePrice($row['harga_jual'] ?? 0),
            'harga_beli' => $parsePrice($row['harga_beli'] ?? 0),
            'harga_reseller' => $parsePrice($row['harga_reseller'] ?? ($row['harga_ecommerse'] ?? 0)),
            'harga_grosir' => $parsePrice($row['harga_grosir'] ?? 0),
            'min_grosir' => $parseInt($row['min_grosir'] ?? 0),
            'satuan' => $satuan->id,
            'id_kategori' => $kategori->id,
            'supplier' => $normalize($row['supplier'] ?? '') ?: 'Kosong',
            'stok' => $parseInt($row['stok'] ?? 0),
            'stok_minimal' => $parseInt($row['stok_minimal'] ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
