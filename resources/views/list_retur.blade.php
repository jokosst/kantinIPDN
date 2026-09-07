@extends('layouts.index')
@section('title', '- List Retur')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>List Retur</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">List Retur</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar Retur</h3>
                        <a href="{{ url('/retur_sebelum_bayar') }}" class="btn btn-primary btn-fill pull-right">Retur Sebelum Bayar</a>

                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="datatable1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Akses</th>
                                        <th>Kode Struk</th>
                                        <th>Total</th>
                                        <th>Waktu</th>
                                        <th>Alasan Retur</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($returs as $no => $item)
                                    <tr>
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $item->nama_akun }}</td>
                                        <td>{{ $item->kode_struk }}</td>
                                        <td>Rp. {{ number_format($item->total,0,',','.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->waktu)->format('d-m-Y H:i:s') }}</td>
                                        <td>{!! nl2br($item->alasan) !!}</td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#detailModal{{ $item->id }}" class="btn btn-success btn-fill">Detail</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Detail Modal -->
@foreach($returs as $item)
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $item->id }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="detailModalLabel{{ $item->id }}">Detail Retur - Kode Struk: {{ $item->kode_struk }}</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Item</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $details = DB::table('log_hapus')->where('kode_struk', $item->kode_struk)->get();
                        @endphp
                        @foreach($details as $detail)
                        <tr>
                            <td>{{ $detail->nama_item }}</td>
                            <td>{{ $detail->jumlah }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('script')
<script>
// initialize datatable if needed
</script>
@endsection
