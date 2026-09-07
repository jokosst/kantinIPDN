@extends('layouts.index')
@section('title', '- Stok Menipis')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Stok Menipis</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('master/item')}}"><i class="fa fa-dashboard"></i> Master</a></li>
            <li class="active">Stok Menipis</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar Item Stok Menipis</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ url('master/item') }}" class="btn btn-default btn-sm">Kembali ke Master Item</a>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="datatable_stok_menipis" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Stok</th>
                                    <th>Stok Minimal</th>
                                    <th>Harga Jual</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Ecommerse</th>
                                    <th>Harga Grosir</th>
                                    <th>Min Grosir</th>
                                    <th>Satuan</th>
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td><span class="label label-danger">{{ $item->stok }}</span></td>
                                    <td>{{ $item->stok_minimal }}</td>
                                    <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->harga_reseller, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->harga_grosir, 0, ',', '.') }}</td>
                                    <td>{{ $item->min_grosir }}</td>
                                    <td>{{ $item->nama_satuan ?? '-' }}</td>
                                    <td>{{ $item->nama_katagori ?? '-' }}</td>
                                    <td>{{ $item->supplier }}</td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-success btn-sm btn-tambah-stok"
                                                data-id="{{ $item->id_item }}"
                                                data-nama="{{ $item->nama }}">
                                            <i class="fa fa-plus"></i> Tambah Stok
                                        </button>
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-kurangi-stok"
                                                data-id="{{ $item->id_item }}"
                                                data-nama="{{ $item->nama }}"
                                                data-stok="{{ $item->stok }}">
                                            <i class="fa fa-trash"></i> Hapus Item
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalTambahStok" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('master/item/stok_menipis/tambah') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Stok Item Menipis</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_item" id="tambah-id-item">
                    <div class="form-group">
                        <label>Nama Item</label>
                        <input type="text" class="form-control" id="tambah-nama-item" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Tambah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input type="text" name="harga_beli" class="form-control" onkeyup="convertToRupiah(this);" value="Rp. 0">
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan penambahan stok"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKurangiStok" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('master/item/stok_menipis/hapus') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Item dari Database</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_item" id="kurangi-id-item">
                    <div class="form-group">
                        <label>Nama Item</label>
                        <input type="text" class="form-control" id="kurangi-nama-item" readonly>
                    </div>
                    <div class="form-group">
                        <label>Stok Saat Ini (Info)</label>
                        <input type="number" class="form-control" id="kurangi-stok-saat-ini" readonly>
                    </div>
                    <p class="text-danger" style="margin:0;">
                        Item akan dihapus permanen dari tabel item.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('#datatable_stok_menipis').DataTable();

    // Use delegated event handlers so clicks still work when DataTable manipulates DOM
    $(document).on('click', '.btn-tambah-stok', function() {
        $('#tambah-id-item').val($(this).data('id'));
        $('#tambah-nama-item').val($(this).data('nama'));
        $('#modalTambahStok').modal('show');
    });

    $(document).on('click', '.btn-kurangi-stok', function() {
        var stokSaatIni = parseInt($(this).data('stok')) || 0;
        $('#kurangi-id-item').val($(this).data('id'));
        $('#kurangi-nama-item').val($(this).data('nama'));
        $('#kurangi-stok-saat-ini').val(stokSaatIni);
        $('#modalKurangiStok').modal('show');
    });
});

@if(Session::has('success'))
Swal.fire({
    title: "Sukses!",
    text: "{{ Session::get('success') }}",
    icon: "success",
});
@endif

@if(Session::has('error'))
Swal.fire({
    title: "Gagal!",
    text: "{{ Session::get('error') }}",
    icon: "warning",
});
@endif
</script>
@endsection
