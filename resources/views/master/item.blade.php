@extends('layouts.index')
@section('title', '- Master Item')
@section('content')
<!-- Loading Overlay -->
<div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; justify-content: center; align-items: center;">
    <i class="fa fa-spinner fa-spin fa-3x text-primary" aria-hidden="true"></i>
</div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Master Item
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('master/item')}}"><i class="fa fa-dashboard"></i> Master</a></li>
        <li class="active">Item</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
      <div class="box box-widget">
        <div class="box-header with-border">
         <h4 class="title"><b>Tambah dengan Scan Barcode / Qrcode</b>
          </h4>  
        </div>
            <form id="scanForm" action="#" method="post" enctype="multipart/form-data">
     <div class="box-body">
        <div class="input-group">
                  
        @csrf
        <input type="text" id="scan-kode" name="kode" accesskey="a" autocomplete="off" class="form-control" autofocus> 
        <span class="input-group-addon"><i class="fa fa-search"></i></span>
        </div>

     </div>
 </form>

</div>
</div> 

        <div class="col-md-12">
           <div class="box box-primary">
            <div style="margin-top: 5px;">
        <!-- button tambah -->
        <a href="#" style="margin-left: 10px;" class="btn btn-primary btn-fill" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i> Tambah Item</a>
            <a href="#" style="margin-left: 10px;" class="btn btn-success btn-fill" data-toggle="modal" data-target="#importExcelModal"><i class="fa fa-upload"></i> Import Excel</a>
        <!-- button ecer-->
         <a href="{{ url('master/item/eceran') }}" style="margin-left: 10px;" class="btn btn-info btn-fill"><i class="fa fa-cubes"></i> Eceran</a>
        <!-- button stok menipis -->
        <a href="{{ url('master/item/stok_menipis') }}" style="margin-left: 10px;" class="btn btn-warning btn-fill"><i class="fa fa-exclamation-triangle"></i> Stok Menipis</a>
        
        </div>
            <div class="box-body">
            <div class="row">
             <div class="content table-responsive table-full-width">
                <table id="datatable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                         <th>No</th>
                         <th>Kode</th>
                          <th>Nama</th>
                          <th>Harga Jual</th>
                          <th>Harga Beli</th>
                          <th>Harga Ecommerse</th>
                          <th>Harga Grosir</th>
                          <th>Min Grosir</th>
                          <th>Satuan</th>
                          <th>Kategori</th>
                          <th>Supplier</th>
                          <th>Stok</th>
                         <th>Stok Minimal</th>
                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                </div>
                </div>


        </div>
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  
        <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{url('master/item/tambah')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label>Kode</label>
                        <input type="text" class="form-control" name="kode" value="{{sprintf('%08d', $kode_terakhir->kode_terakhir + 1)}}" required>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Jual</label>
                        <input type="text" class="form-control" name="harga_jual" onkeyup="convertToRupiah(this);" value="Rp. 0" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input type="text" class="form-control" name="harga_beli" onkeyup="convertToRupiah(this);" value="Rp. 0" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Ecommerse</label>
                        <input type="text" class="form-control" name="harga_reseller" onkeyup="convertToRupiah(this);" value="Rp. 0" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Grosir</label>
                        <input type="text" class="form-control" name="harga_grosir" onkeyup="convertToRupiah(this);" value="Rp. 0" required>
                        <p>* Isi 0 jika bukan barang grosir</p>
                    </div>
                    <div class="form-group">
                        <label>Min Grosir</label>
                        <input type="number" class="form-control" name="min_grosir" value="0" required>
                        <p>* Isi 0 jika bukan barang grosir</p>
                    </div>
                    <div class="form-group">
                        <label>Satuan</label>
                        <select class="form-control" name="satuan" required>
                            @foreach($satuan as $sat)
                            <option value="{{$sat->id}}">{{$sat->nama_satuan}}</option>
                            @endforeach
                        </select>
                        </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" name="id_kategori" required>
                            @foreach($kategori as $kat)
                            <option value="{{$kat->id}}">{{$kat->nama_katagori}}</option>
                            @endforeach
                        </select>
                        </div>
                    <div class="form-group">
                        <label>Supplier</label>
                        <select class="form-control" name="supplier" required>
                            <option value="Kosong">--Tidak Ada Supplier--</option>
                            @foreach($supplier as $sup)
                            <option value="{{$sup->nama}}">{{$sup->nama}}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="form-group">
                        <label>Stok</label>
                        <input type="number" class="form-control" name="stok" value="0" required>
                        </div>
                        <div class="form-group">
                        <label>Stok Minimal</label>
                        <input type="number" class="form-control" name="stok_minimal" value="0" required>
                        <p>* Peringatan jika stok mencapai angka ini</p>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div>
            </div>
        </div>

        <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-labelledby="importExcelModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ url('master/item/import') }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h4 class="modal-title" id="importExcelModalLabel"><b>Import Item dari Excel</b></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label>Pilih File Excel</label>
                                <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls,.csv" required>
                                <p style="margin-top: 8px; margin-bottom: 0;">Format didukung: .xlsx, .xls, .csv (maks. 5MB)</p>
                            </div>
                            <hr style="margin: 10px 0;">
                            <p style="margin-bottom: 8px;"><b>Butuh contoh file?</b></p>
                            <a href="{{ url('master/item/import/template') }}" class="btn btn-default" target="_blank">
                                <i class="fa fa-download"></i> Download Contoh Excel
                            </a>
                            <p style="margin-top: 10px; margin-bottom: 0;">
                                Catatan: Jika <b>kode</b> sudah ada di database, data tersebut akan dilewati dan tidak disimpan ulang.
                            </p>
                            <p style="margin-top: 6px; margin-bottom: 0;">
                                Kolom <b>satuan</b> dan <b>kategori</b> diisi dengan <b>nama</b> (bukan ID). Jika belum ada di master, data satuan/kategori akan dibuat otomatis.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Import Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{url('master/item/edit')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label>Kode</label>
                        <input type="text" class="form-control" name="kode" id="edit-kode" required>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" id="edit-nama" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Jual</label>
                        <input type="text" class="form-control" name="harga_jual" id="edit-harga_jual" onkeyup="convertToRupiah(this);" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input type="text" class="form-control" name="harga_beli" id="edit-harga_beli" onkeyup="convertToRupiah(this);" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Ecommerse</label>
                        <input type="text" class="form-control" name="harga_reseller" id="edit-harga_reseller" onkeyup="convertToRupiah(this);" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Grosir</label>
                        <input type="text" class="form-control" name="harga_grosir" id="edit-harga_grosir" onkeyup="convertToRupiah(this);" required>
                        <p>* Isi 0 jika bukan barang grosir</p>
                    </div>
                    <div class="form-group">
                        <label>Min Grosir</label>
                        <input type="number" class="form-control" name="min_grosir" id="edit-min_grosir" required>
                        <p>* Isi 0 jika bukan barang grosir</p>
                    </div>
                    <div class="form-group">
                        <label>Satuan</label>
                        <select class="form-control" name="satuan" id="edit-satuan" required>
                            @foreach($satuan as $sat)
                            <option value="{{$sat->id}}">{{$sat->nama_satuan}}</option>
                            @endforeach
                        </select>
                        </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" name="id_kategori" id="edit-id_kategori" required>
                            @foreach($kategori as $kat)
                            <option value="{{$kat->id}}">{{$kat->nama_katagori}}</option>
                            @endforeach
                        </select>
                        </div>
                    <div class="form-group">
                        <label>Supplier</label>
                        <select class="form-control" name="supplier" id="edit-supplier" required>
                            <option value="Kosong">--Tidak Ada Supplier--</option>
                            @foreach($supplier as $sup)
                            <option value="{{$sup->nama}}">{{$sup->nama}}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="form-group">
                        <label>Stok</label>
                        <input type="number" class="form-control" name="stok" id="edit-stok" required>
                        </div>
                        <div class="form-group">
                        <label>Stok Minimal</label>
                        <input type="number" class="form-control" name="stok_minimal" id="edit-stok_minimal" required>
                        <p>* Peringatan jika stok mencapai angka ini</p>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    // Intercept scan form submit: open `#tambah` modal with scanned kode (do not save immediately)
    $('#scanForm').on('submit', function(e) {
        e.preventDefault();
        var kode = $('#scan-kode').val().trim();
        if (!kode) return;
        
        // Cek apakah kode sudah ada di database
        $.get('{{ url("master/item/cek_kode") }}/' + kode, function(data) {
            if (data.exists) {
                Swal.fire({
                    title: "Kode Sudah Ada!",
                    text: "Kode " + kode + " sudah terdaftar. Silakan gunakan kode lain.",
                    icon: "warning",
                });
                $('#scan-kode').val('');
                return;
            } else {
                // populate modal input and open modal with static backdrop (can't close with ESC or backdrop)
                $('#tambah input[name="kode"]').val(kode);
                $('#tambah').modal({backdrop: 'static', keyboard: false});
                $('#tambah input[name="nama"]').focus();
                // clear scanner input so next scan is fresh
                $('#scan-kode').val('');
            }
        });
    });

    if ($.fn.DataTable.isDataTable('#datatable1')) {
        $('#datatable1').DataTable().destroy();
        $('#datatable1 tbody').empty();
    }
    $('#datatable1').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url("master/item/data") }}',
        stateSave: true,
        stateDuration: 86400,
        columns: [
            { data: null, orderable: false, searchable: false },
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
            { data: 6 },
            { data: 7 },
            { data: 8 },
            { data: 9 },
            { data: 10 },
            { data: 11 },
            { data: 12, orderable: false, searchable: false }
        ],
        columnDefs: [{
            targets: 0,
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }]
    });

    // Handle edit button click
    $('#datatable1').on('click', '.btn-warning', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id'); // Need to add data-id to button
        $.get('{{ url("master/item/get") }}/' + id, function(data) {
            $('#edit-id').val(data.id_item);
            $('#edit-kode').val(data.kode);
            $('#edit-nama').val(data.nama);
            $('#edit-harga_jual').val('Rp. ' + parseInt(data.harga_jual).toLocaleString('id-ID'));
            $('#edit-harga_beli').val('Rp. ' + parseInt(data.harga_beli).toLocaleString('id-ID'));
            $('#edit-harga_reseller').val('Rp. ' + parseInt(data.harga_reseller).toLocaleString('id-ID'));
            $('#edit-harga_grosir').val('Rp. ' + parseInt(data.harga_grosir).toLocaleString('id-ID'));
            $('#edit-min_grosir').val(data.min_grosir);
            $('#edit-satuan').val(data.satuan);
            $('#edit-id_kategori').val(data.id_kategori);
            $('#edit-supplier').val(data.supplier);
            $('#edit-stok').val(data.stok);
            $('#edit-stok_minimal').val(data.stok_minimal);
            $('#editModal').modal('show');
        });
    });

    // Handle delete with SweetAlert2 confirmation (SweetAlert2 included in layout)
    $('#datatable1').on('click', '.btn-danger', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus data?',
                text: 'Tindakan ini tidak bisa dibatalkan!',
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
        } else {
            if (confirm('Yakin ingin menghapus?')) {
                window.location = href;
            }
        }
    });
});

$(window).on('load', function() {
    $('#loading-overlay').fadeOut();
});


@if(Session::has('success'))
 Swal.fire({
        title:"Berhasil!",
        text:"{{Session::get('success')}}",
        icon:"success",
    })
@endif
@if(Session::has('error'))
 Swal.fire({
        title:"Gagal!",text:"{{Session::get('error')}}",icon:"warning",
    })
@endif
@if($errors->any())
 Swal.fire({
        title:"Gagal!",
        text:"{{ $errors->first() }}",
        icon:"warning",
    })
@endif
@if(Session::has('import_info'))
 Swal.fire({
        title:"Import Selesai",
        text:"{{Session::get('import_info')}}",
        icon:"info",
    })
@endif
//sesion stok menipis
@if(Session::has('stokmenipis'))
 Swal.fire({
        title:"Peringatan Stok!",
        text:"{{Session::get('stokmenipis')}}",
        icon:"warning",
        showCancelButton: true,
        confirmButtonText: 'Lihat Stok Menipis',
        cancelButtonText: 'Tutup'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ url('master/item/stok_menipis') }}";
        }
    })
@endif
</script>
@endsection