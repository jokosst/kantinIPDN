@extends('layouts.index')
@section('title', '- Stok Ecer')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Stok Ecer</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('master/item')}}"><i class="fa fa-dashboard"></i> Master</a></li>
            <li class="active">Stok Ecer</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahEcer"><i class="fa fa-plus"></i> Tambah Resep Ecer</button>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="datatable_ecer" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Item Utama</th>
                                    <th>Stok Utama</th>
                                    <th>Item Ecer</th>
                                    <th>Stok Ecer</th>
                                    <th>Jumlah / Paket</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahEcer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('master/item/eceran/tambah') }}" method="POST">
                @csrf
                <div class="modal-header"><h4 class="modal-title">Tambah Resep Ecer</h4></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Item Utama</label>
                        <select name="id_stok_utama" class="form-control select2" required>
                            <option value="">-- Pilih Item Utama --</option>
                            @foreach($items as $it)
                            <option value="{{ $it->id_item }}">{{ $it->nama }} [{{ $it->stok }} {{ $it->nama_satuan ?? '' }}]</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Item Ecer</label>
                        <select name="id_stok_ecer" class="form-control select2" required>
                            <option value="">-- Pilih Item Ecer --</option>
                            @foreach($items as $it)
                            <option value="{{ $it->id_item }}">{{ $it->nama }} [{{ $it->stok }} {{ $it->nama_satuan ?? '' }}]</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Ecer dalam 1 Paket</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEditEcer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('master/item/eceran/edit') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header"><h4 class="modal-title">Edit Resep Ecer</h4></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Item Utama</label>
                        <select name="id_stok_utama" id="edit-stok-utama" class="form-control select2" required>
                            <option value="">-- Pilih Item Utama --</option>
                            @foreach($items as $it)
                            <option value="{{ $it->id_item }}">{{ $it->nama }} [{{ $it->stok }} {{ $it->nama_satuan ?? '' }}]</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Item Ecer</label>
                        <select name="id_stok_ecer" id="edit-stok-ecer" class="form-control select2" required>
                            <option value="">-- Pilih Item Ecer --</option>
                            @foreach($items as $it)
                            <option value="{{ $it->id_item }}">{{ $it->nama }} [{{ $it->stok }} {{ $it->nama_satuan ?? ''}}]</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Ecer dalam 1 Paket</label>
                        <input type="number" name="jumlah" id="edit-jumlah" class="form-control" min="1" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var table = $('#datatable_ecer').DataTable({
        processing: true,
        serverSide: false,
        ajax: '{{ url("master/item/eceran/data") }}',
        columns: [
            { data: null, orderable: false, searchable: false },
            { data: 1 }, // nama utama
            { data: 2 }, // stok utama + satuan
            { data: 3 }, // nama ecer
            { data: 4 }, // stok ecer + satuan
            { data: 5 }, // jumlah
            { data: 6 }  // aksi
        ],
        columnDefs: [{
            targets: 0,
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }]
    });

    // delegate edit button
    $(document).on('click', '.btn-edit-ecer', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var id_utama = $(this).data('stok-utama');
        var id_ecer = $(this).data('stok-ecer');
        var jumlah = $(this).data('jumlah');
        $('#edit-id').val(id);
        $('#edit-stok-utama').val(id_utama);
        $('#edit-stok-ecer').val(id_ecer);
        $('#edit-jumlah').val(jumlah);
        $('#modalEditEcer').modal('show');
    });
    // confirm delete with SweetAlert2
    $(document).on('click', '.btn-delete-ecer', function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        Swal.fire({
            title: 'Hapus resep ecer?',
            text: 'Tindakan ini tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = href;
            }
        });
    });
    // initialize Select2 when modal opens so dropdownParent works correctly
    $('#modalTambahEcer').on('shown.bs.modal', function() {
        $(this).find('select.select2').select2({
            width: '100%',
            dropdownParent: $(this)
        });
    });
    $('#modalTambahEcer').on('hidden.bs.modal', function() {
        $(this).find('select.select2').select2('destroy');
    });
    $('#modalEditEcer').on('shown.bs.modal', function() {
        $(this).find('select.select2').select2({
            width: '100%',
            dropdownParent: $(this)
        });
    });
    $('#modalEditEcer').on('hidden.bs.modal', function() {
        $(this).find('select.select2').select2('destroy');
    });
});

@if(Session::has('success'))
Swal.fire({title: 'Sukses!', text: '{{ Session::get('success') }}', icon: 'success'});
@endif

@if(Session::has('error'))
Swal.fire({title: 'Gagal!', text: '{{ Session::get('error') }}', icon: 'warning'});
@endif
</script>
@endsection
