@extends('layouts.index')
@section('title', '- Penambahan Stok')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #ccc;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding: 0 8px;
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Penambahan Stok
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Penambahan Stok</li>
      </ol>
    </section>
     <div class="row" style="margin-bottom: 0px; padding: 15px;">
      <div class="col-md-4">
        <form method="post" action="{{url('stok/penambahan')}}" style="margin: 0;">
           @csrf
          <div class="row">
            <div class="col-md-4" style="padding-right: 10px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal</label>
                <input type="date" name="tgl" class="form-control" value="{{isset($tgl) ? $tgl : ''}}" style="margin-top: 5px;">
              </div>
            </div>
            <div class="col-md-4" style="padding-left: 10px; display: flex; flex-direction: column; justify-content: flex-end;margin-top: 30px;">
              <button type="submit" class="btn btn-primary" style="margin: 0;">Filter</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
           <div class="box box-primary">
            <div class="box-header with-border">
            <a href="#" data-toggle="modal" data-target="#tambah" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Stok</a>
            </div>
            <div class="box-body">
            <div class="row">
             <div class="content table-responsive table-full-width">
                <table id="datatable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                          <th>No</th>
                         <th>Tgl</th>
                         <th>Item</th>
                          <th>Jumlah</th>
                          <th>Harga Beli</th>
                          <th>Harga Total</th>
                          <th>Keterangan</th>
                          <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($stok as $no => $p)
                        <tr>
                          <!-- nomor -->
                          <td>{{ $no + 1 }}</td>
                          <td>{{date('d-m-Y', strtotime($p->tanggal))}}</td>
                          <td>{{$p->nama_stok}}</td>
                          <td>{{$p->jumlah}}</td>
                          <td>Rp. {{number_format($p->harga_beli)}}</td>
                          <td>Rp. {{number_format($p->harga_beli * $p->jumlah)}}</td>
                          <td style="white-space: pre-wrap;">{{ strip_tags($p->keterangan) }}</td>
                          <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{url('stok/penambahan/hapus/'.$p->id)}}')"><i class="fa fa-trash"></i> Hapus</button>
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
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  
    <!-- modal tambah -->
    <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Stok</h5>
          </div>
          <form method="POST" action="{{url('stok/penambahan/tambah')}}">
            @csrf
            <div class="modal-body row">
              <div class="form-group col-md-4">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{date('Y-m-d')}}" required>
              </div>
              <div class="form-group col-md-12">
                <label>Item</label>
                <select name="id_stok" class="form-control select2" required>
                  <option value="">-- Pilih Item --</option>
                  @foreach($item as $i)
                  @php 
                  $satuan = DB::table('satuan')->where('id', $i->satuan)->first();
                  if(!$satuan){
                    $satuan = (object) ['nama_satuan' => 'Tidak Ada'];
                  }
                  @endphp
                  <option value="{{$i->id_item}}">{{$i->nama}} [{{$satuan->nama_satuan}}] [{{$i->kode}}]</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-6">
                <label>Jumlah</label>
                <input type="number" name="jumlah" class="form-control" value="1" required>
              </div>
                <div class="form-group col-md-6">
                <label>Harga Beli</label>
                <input type="text" name="harga_beli" class="form-control" onkeyup="convertToRupiah(this);" value="Rp. 0" required>
              </div>
              <div class="form-group col-md-12">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- end modal tambah -->
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$('#tambah').on('shown.bs.modal', function () {
    $(this).find('.select2').select2({
        dropdownParent: $('#tambah'),
        width: '100%'
    });
});



function confirmDelete(url) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

@if(Session::has('success'))
 Swal.fire({
        title:"Sukses!",text:"{{Session::get('msg')}}",icon:"success",
    })
@endif
@if(Session::has('error'))
 Swal.fire({
        title:"Gagal!",text:"{{Session::get('error')}}",icon:"warning",
    })
@endif
</script>
@endsection