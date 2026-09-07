<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjualanExport implements FromCollection, WithHeadings
{
    protected $tgl_awal;
    protected $tgl_akhir;
    protected $item;

    public function __construct($tgl_awal, $tgl_akhir, $item)
    {
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
        $this->item = $item;
    }

    public function collection()
    {
        $query = Transaksi::whereBetween('tanggal', [$this->tgl_awal, $this->tgl_akhir]);

        if(!empty($this->item)) {
            $query->where('id_item', $this->item);
        }

        return $query->get()->map(function($p) {
            return [
                'Tgl' => date('d-m-Y', strtotime($p->tanggal)),
                'Item' => $p->nama_pesanan,
                'Jumlah' => $p->jumlah,
                'Harga Jual' => 'Rp. ' . number_format($p->harga),
                'Harga Total' => 'Rp. ' . number_format($p->harga * $p->jumlah),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tgl',
            'Item',
            'Jumlah',
            'Harga Jual',
            'Harga Total',
        ];
    }
}