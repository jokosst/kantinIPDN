@extends('layouts.index')
@section('title', '- Transaksi Closing')
@section('content')
@php
    $sum_penjualan = 0;
    $sum_modal = 0;
    $sum_keuntungan = 0;
    $sum_qty = 0;
    foreach($transaksi as $tr) {
        $ch = (float) preg_replace('/[^0-9.]/', '', $tr->harga);
        $cj = (float) preg_replace('/[^0-9.]/', '', $tr->jumlah);
        $cb = (float) preg_replace('/[^0-9.]/', '', $tr->harga_beli ?? 0);
        $sub = $ch * $cj;
        $disc = ($tr->status_note > 0) ? ($sub * $tr->status_note / 100) : 0;
        $pj = $sub - $disc;
        $mb = $cb * $cj;
        $sum_penjualan += $pj;
        $sum_modal += $mb;
        $sum_keuntungan += ($pj - $mb);
        $sum_qty += $cj;
    }
@endphp
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Daftar Transaksi Closing: <small>{{ $closing->kode_closing }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('laporan/closing')}}">Laporan Closing</a></li>
            <li class="active">Transaksi Closing</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info Ringkasan Closing -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info-circle"></i> Informasi Closing</h3>
                        <div class="box-tools pull-right">
                            <a href="{{url('laporan/closing')}}" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Kembali ke Laporan Closing
                            </a>
                            <a href="{{url('laporan/closing/detail/'.$closing->kode_closing)}}" class="btn btn-info btn-sm">
                                <i class="fa fa-print"></i> Lihat Struk Closing
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6" style="margin-bottom: 10px;">
                                <strong>Kode Closing:</strong><br>
                                <span class="text-primary" style="font-size: 16px; font-weight: bold;">{{ $closing->kode_closing }}</span>
                                <br>
                                <small class="text-muted"><i class="fa fa-calendar"></i> {{ date('d-m-Y', strtotime($closing->tanggal)) }} {{ date('H:i:s', strtotime($closing->waktu)) }}</small>
                            </div>
                            <div class="col-md-3 col-sm-6" style="margin-bottom: 10px;">
                                <strong>Kasir:</strong><br>
                                <span style="font-size: 15px;">{{ $closing->nama_closing }}</span>
                                <br>
                                <small class="text-muted">Total Transaksi: <strong>{{ count($transaksi) }} item</strong></small>
                            </div>
                            <div class="col-md-2 col-sm-4" style="margin-bottom: 10px;">
                                <strong>Total Penjualan:</strong><br>
                                <span class="text-info" style="font-size: 16px; font-weight: bold;">
                                    Rp. {{ number_format($sum_penjualan, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="col-md-2 col-sm-4" style="margin-bottom: 10px;">
                                <strong>Total Modal (Beli):</strong><br>
                                <span class="text-danger" style="font-size: 16px; font-weight: bold;">
                                    Rp. {{ number_format($sum_modal, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="col-md-2 col-sm-4" style="margin-bottom: 10px;">
                                <strong>Total Keuntungan:</strong><br>
                                <span class="text-success" style="font-size: 16px; font-weight: bold;">
                                    Rp. {{ number_format($sum_keuntungan, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Rincian Penjualan & Keuntungan Transaksi</h3>
                    </div>
                    <div class="box-body">
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-12">
                                <button onclick="exportToXLSX()" class="btn btn-success">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </button>
                                <button onclick="exportToPDF()" class="btn btn-danger">
                                    <i class="fa fa-file-pdf-o"></i> Export PDF
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tabel-transaksi">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th style="width: 35px; text-align: center;">No</th>
                                        <th>Waktu</th>
                                        <th>Kode Struk</th>
                                        <th>Nama Pesanan / Item</th>
                                        <th style="text-align: center;">Qty</th>
                                        <th style="text-align: right;">Harga Jual</th>
                                        <th style="text-align: right;">Diskon</th>
                                        <th style="text-align: right;">Total Jual</th>
                                        <th style="text-align: right;">Harga Beli</th>
                                        <th style="text-align: right;">Total Modal</th>
                                        <th style="text-align: right;">Keuntungan</th>
                                        <th style="text-align: center;">Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksi as $index => $t)
                                        @php
                                            $clean_harga = (float) preg_replace('/[^0-9.]/', '', $t->harga);
                                            $clean_jumlah = (float) preg_replace('/[^0-9.]/', '', $t->jumlah);
                                            $clean_harga_beli = (float) preg_replace('/[^0-9.]/', '', $t->harga_beli ?? 0);

                                            $subtotal = $clean_harga * $clean_jumlah;
                                            $diskon = 0;
                                            if ($t->status_note > 0) {
                                                $diskon = ($subtotal * $t->status_note) / 100;
                                            }
                                            $total_jual = $subtotal - $diskon;
                                            $total_modal = $clean_harga_beli * $clean_jumlah;
                                            $keuntungan = $total_jual - $total_modal;
                                        @endphp
                                        <tr>
                                            <td style="text-align: center;">{{ $index + 1 }}</td>
                                            <td>{{ date('d-m-Y', strtotime($t->tanggal)) }} {{ date('H:i:s', strtotime($t->waktu)) }}</td>
                                            <td><span class="badge bg-navy">{{ $t->kode_struk }}</span></td>
                                            <td>
                                                <strong>{{ $t->nama_pesanan }}</strong>
                                                @if(!empty($t->note))
                                                    <br><small class="text-muted"><i class="fa fa-sticky-note-o"></i> {{ $t->note }}</small>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">{{ $t->jumlah }}</td>
                                            <td style="text-align: right;">Rp. {{ number_format($clean_harga, 0, ',', '.') }}</td>
                                            <td style="text-align: right;">
                                                @if($t->status_note > 0)
                                                    <span class="text-danger">-Rp. {{ number_format($diskon, 0, ',', '.') }} ({{ $t->status_note }}%)</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td style="text-align: right;"><strong>Rp. {{ number_format($total_jual, 0, ',', '.') }}</strong></td>
                                            <td style="text-align: right;">Rp. {{ number_format($clean_harga_beli, 0, ',', '.') }}</td>
                                            <td style="text-align: right;">Rp. {{ number_format($total_modal, 0, ',', '.') }}</td>
                                            <td style="text-align: right;">
                                                <strong class="{{ $keuntungan >= 0 ? 'text-success' : 'text-danger' }}">
                                                    Rp. {{ number_format($keuntungan, 0, ',', '.') }}
                                                </strong>
                                            </td>
                                            <td style="text-align: center;">
                                                @if($t->bayar == 0)
                                                    <span class="label label-success">Tunai</span>
                                                @elseif($t->bayar == 2)
                                                    <span class="label label-warning">Pending</span>
                                                @else
                                                    <span class="label label-primary">{{ isset($non_tunai[$t->metode_pembayaran]) ? $non_tunai[$t->metode_pembayaran]->nama : ($t->metode_pembayaran ?: 'Non Tunai') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">Tidak ada transaksi untuk kode closing ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr style="font-weight: bold; background-color: #f4f4f4;">
                                        <td colspan="4" style="text-align: right;">Total Keseluruhan:</td>
                                        <td style="text-align: center;">{{ $sum_qty }}</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right;">Rp. {{ number_format($sum_penjualan, 0, ',', '.') }}</td>
                                        <td></td>
                                        <td style="text-align: right;">Rp. {{ number_format($sum_modal, 0, ',', '.') }}</td>
                                        <td style="text-align: right;" class="text-success">Rp. {{ number_format($sum_keuntungan, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function exportToXLSX() {
    const table = document.getElementById('tabel-transaksi');
    const wb = XLSX.utils.table_to_book(table);
    XLSX.writeFile(wb, 'transaksi_closing_{{ $closing->kode_closing }}.xlsx');
}

function exportToPDF() {
    const element = document.getElementById('tabel-transaksi');
    html2pdf().from(element).save('transaksi_closing_{{ $closing->kode_closing }}.pdf');
}
</script>
@endsection
