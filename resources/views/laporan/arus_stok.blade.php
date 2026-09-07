@extends('layouts.index')
@section('title', '- Laporan Arus Stok')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single {
    height: 35px;
    border: 1px solid #ccc;
    margin-top: 5px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding: 0 5px;
    color: #333;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px;
}
.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: #888 transparent transparent transparent;
    border-width: 8px 6px 0 6px;
}
.select2-dropdown {
    z-index: 1050 !important;
}
.select2-search__field {
    padding: 8px;
}
.select2-results__option {
    padding: 10px;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #007bff;
}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Laporan Arus Stok</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Laporan Arus Stok</li>
        </ol>
    </section>

    <div class="row" style="margin-bottom: 0px; padding: 15px;">
        <div class="col-md-6">
            <form method="post" action="{{url('laporan/arus_stok')}}" style="margin: 0;">
                @csrf
                <div class="row">
                    <div class="col-md-3" style="padding-right: 10px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tgl_awal" class="form-control" value="{{isset($tgl_awal) ? $tgl_awal : ''}}" style="margin-top: 5px;">
                        </div>
                    </div>
                    <div class="col-md-3" style="padding: 0 10px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" class="form-control" value="{{isset($tgl_akhir) ? $tgl_akhir : ''}}" style="margin-top: 5px;">
                        </div>
                    </div>
                    <div class="col-md-3" style="padding: 0 10px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Item</label>
                            <select name="item" class="form-control select2" style="margin-top: 5px;">
                                <option value="">Semua Item</option>
                                @foreach($stok as $s)
                                <option value="{{$s->id_item}}" {{isset($item) && $item == $s->id_item ? 'selected' : ''}}>{{$s->nama}} [{{$s->kode}}]</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" style="padding-left: 10px; display: flex; flex-direction: column; justify-content: flex-end;margin-top: 30px;">
                        <button type="submit" class="btn btn-primary" style="margin: 0;">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="content table-responsive table-full-width">
                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-md-6 col-xs-12">
                                        <button onclick="exportToXLSX()" class="btn btn-success">
                                            <i class="fa fa-file-excel-o"></i> Export Excel
                                        </button>
                                        <button onclick="exportToPDF()" class="btn btn-danger">
                                            <i class="fa fa-file-pdf-o"></i> Export PDF
                                        </button>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="input-group pull-right" style="max-width: 320px; width: 100%;">
                                            <input type="text" id="searchTable" class="form-control" placeholder="Cari kode atau nama barang...">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <table id="tabel_arus_stok" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px; text-align: center;">No</th>
                                            <th>Kode Barang</th>
                                            <th>Item / Nama Barang</th>
                                            <th style="text-align: right;">Stok Awal</th>
                                            <th style="text-align: right;">Stok Masuk</th>
                                            <th style="text-align: right;">Stok Keluar</th>
                                            <th style="text-align: right;">Saldo / Stok Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel_arus_stok_body">
                                        @forelse($arus_stok as $index => $p)
                                        <tr class="item-row" data-awal="{{ $p->stok_awal }}" data-masuk="{{ $p->stok_masuk }}" data-keluar="{{ $p->stok_keluar }}" data-akhir="{{ $p->stok_akhir }}">
                                            <td style="text-align: center;" class="row-number">{{ $index + 1 }}</td>
                                            <td class="col-kode">{{$p->kode}}</td>
                                            <td class="col-nama">{{$p->nama}}</td>
                                            <td style="text-align: right;">{{number_format($p->stok_awal, 0, ',', '.')}}</td>
                                            <td style="text-align: right;">{{number_format($p->stok_masuk, 0, ',', '.')}}</td>
                                            <td style="text-align: right;">{{number_format($p->stok_keluar, 0, ',', '.')}}</td>
                                            <td style="text-align: right; font-weight: bold;">{{number_format($p->stok_akhir, 0, ',', '.')}}</td>
                                        </tr>
                                        @empty
                                        <tr id="empty_row">
                                            <td colspan="7" class="text-center">Tidak ada data arus stok.</td>
                                        </tr>
                                        @endforelse
                                        <tr id="no_match_row" style="display: none;">
                                            <td colspan="7" class="text-center text-muted">Data item tidak ditemukan dengan pencarian tersebut.</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold; background-color: #f9f9f9;">
                                            <th colspan="3" style="text-align: right;">Total:</th>
                                            <th id="total_awal" style="text-align: right;">{{number_format($arus_stok->sum('stok_awal'), 0, ',', '.')}}</th>
                                            <th id="total_masuk" style="text-align: right;">{{number_format($arus_stok->sum('stok_masuk'), 0, ',', '.')}}</th>
                                            <th id="total_keluar" style="text-align: right;">{{number_format($arus_stok->sum('stok_keluar'), 0, ',', '.')}}</th>
                                            <th id="total_akhir" style="text-align: right;">{{number_format($arus_stok->sum('stok_akhir'), 0, ',', '.')}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%'
    });

    function formatRibuan(val) {
        return new Intl.NumberFormat('id-ID').format(val);
    }

    function updateTableTotals() {
        let totalAwal = 0;
        let totalMasuk = 0;
        let totalKeluar = 0;
        let totalAkhir = 0;
        let visibleCount = 0;

        $('.item-row:visible').each(function() {
            visibleCount++;
            $(this).find('.row-number').text(visibleCount);
            totalAwal += parseFloat($(this).data('awal')) || 0;
            totalMasuk += parseFloat($(this).data('masuk')) || 0;
            totalKeluar += parseFloat($(this).data('keluar')) || 0;
            totalAkhir += parseFloat($(this).data('akhir')) || 0;
        });

        if (visibleCount === 0 && $('.item-row').length > 0) {
            $('#no_match_row').show();
        } else {
            $('#no_match_row').hide();
        }

        $('#total_awal').text(formatRibuan(totalAwal));
        $('#total_masuk').text(formatRibuan(totalMasuk));
        $('#total_keluar').text(formatRibuan(totalKeluar));
        $('#total_akhir').text(formatRibuan(totalAkhir));
    }

    $('#searchTable').on('keyup input', function() {
        const keyword = $(this).val().toLowerCase().trim();

        $('.item-row').each(function() {
            const kode = $(this).find('.col-kode').text().toLowerCase();
            const nama = $(this).find('.col-nama').text().toLowerCase();

            if (kode.indexOf(keyword) > -1 || nama.indexOf(keyword) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        updateTableTotals();
    });
});

function exportToXLSX() {
    const table = document.getElementById('tabel_arus_stok');
    const clone = table.cloneNode(true);
    $(clone).find('tr:hidden, #no_match_row').remove();
    const wb = XLSX.utils.table_to_book(clone);
    XLSX.writeFile(wb, 'arus_stok.xlsx');
}

function exportToPDF() {
    const table = document.getElementById('tabel_arus_stok');
    const clone = table.cloneNode(true);
    $(clone).find('tr:hidden, #no_match_row').remove();
    const wrapper = document.createElement('div');
    wrapper.appendChild(clone);
    html2pdf().from(wrapper).save('arus_stok.pdf');
}
</script>
@endsection
