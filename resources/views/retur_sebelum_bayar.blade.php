@extends('layouts.index')
@section('title', '- Retur Sebelum Bayar')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Retur Sebelum Bayar</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/retur') }}">List Retur</a></li>
            <li class="active">Retur Sebelum Bayar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <form method="GET" action="{{ url('/retur_sebelum_bayar') }}" class="form-inline">
                    <div class="form-group" style="margin-right: 10px;">
                        <label for="tgl_awal" style="margin-right: 6px;">Dari Tanggal</label>
                        <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" value="{{ $tgl_awal }}">
                    </div>
                    <div class="form-group" style="margin-right: 10px;">
                        <label for="tgl_akhir" style="margin-right: 6px;">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" value="{{ $tgl_akhir }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar Retur Sebelum Bayar</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="datatable1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Item</th>
                                        <th>Jumlah</th>
                                        <th>Akses</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($returSebelumBayar as $no => $item)
                                    <tr>
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $item->nama_item }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->waktu)->format('d-m-Y') }}</td>
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
@endsection

@section('script')
<script>
// initialize datatable if needed
</script>
@endsection
