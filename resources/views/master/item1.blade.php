@extends('layouts.index')
@section('title', '- Master Item')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Master Item
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Master Item</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
           <div class="box box-primary">
            <div class="box-header with-border">
            <a href="#" data-toggle="modal" data-target="#tambah" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Item</a>
            </div>
            <div class="box-body">
            <div class="row">
             <div class="content table-responsive table-full-width">
                <table id="datatable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                         <th>Nama Item</th>
                         <th>Harga Jual</th>
                          <th>Produksi</th>
                          <th>Ketersedian</th>
                          <th>Menu Favorit</th>
                          <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $i => $p)
                        <tr>
                          <td>{{$p->nama_menu}}</td>
                          <td>Rp. {{number_format($p->harga)}}</td>
                          <td>{{$p->produksi}}</td>
                          <td>@if($p->stok == 0) <span class="label label-danger">Habis</span> @else <span class="label label-success">Tersedia</span> @endif</td>
                          <td>@if($p->fav == 0) <span class="label label-warning">Tidak</span> @else <span class="label label-success">Ya</span> @endif</td>
                          <td>
                            <a href="#" data-toggle="modal" data-target="#edit{{$p->id_menu}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{url('master/item1/hapus/'.$p->id_menu)}}')"><i class="fa fa-trash"></i> Hapus</button>
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
            <h5 class="modal-title" id="exampleModalLabel">Tambah Item</h5>
          </div>
          <form method="POST" action="{{url('master/item1/tambah')}}">
            @csrf
            <div class="modal-body row">
              <div class="form-group col-md-12">
                <label>Nama Item</label>
                <input type="text" name="nama_menu" class="form-control" required>
              </div>
              <div class="form-group col-md-6">
                <label>Produksi</label>
                <select name="produksi" class="form-control" required>
                  <option value="makanan">Makanan</option>
                  <option value="minuman">Minuman</option>
                  <option value="lainnya">Lainnya</option>
                </select>
              </div>
                <div class="form-group col-md-6">
                <label>Harga Jual</label>
                <input type="text" name="harga" class="form-control" onkeyup="convertToRupiah(this);" value="Rp. 0" required>
              </div>
              <div class="form-group col-md-6">
                <label>Ketersediaan</label>
                <select name="stok" class="form-control" required>
                  <option value="0">Habis</option>
                  <option value="1">Tersedia</option>
                </select>
                </div>
                <div class="form-group col-md-6">
                <label>Menu Favorit</label>
                <select name="fav" class="form-control" required>
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
                </select>
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

    <!-- Modal edit -->
     @foreach($data as $i => $p)
    <div class="modal fade" id="edit{{$p->id_menu}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
          </div>
          <form method="POST" action="{{url('master/item1/edit/')}}">
            @csrf
            <input type="hidden" name="id" value="{{$p->id_menu}}">
            <div class="modal-body row">
              <div class="form-group col-md-12">
                <label>Nama Item</label>
                <input type="text" name="nama_menu" class="form-control" value="{{$p->nama_menu}}" required>
              </div>
              <div class="form-group col-md-6">
                <label>Produksi</label>
                <select name="produksi" class="form-control" required>
                  <option value="makanan" @if($p->produksi == 'makanan') selected @endif>Makanan</option>
                  <option value="minuman" @if($p->produksi == 'minuman') selected @endif>Minuman</option>
                  <option value="lainnya" @if($p->produksi == 'lainnya') selected @endif>Lainnya</option>
                </select>
              </div>
                <div class="form-group col-md-6">
                <label>Harga Jual</label>
                <input type="text" name="harga" class="form-control" onkeyup="convertToRupiah(this);" value="Rp. {{number_format($p->harga, 0, ',', '.')}}" required>
              </div>
              <div class="form-group col-md-6">
                <label>Ketersediaan</label>
                <select name="stok" class="form-control" required>
                  <option value="0" @if($p->stok == 0) selected @endif>Habis</option>
                  <option value="1" @if($p->stok == 1) selected @endif>Tersedia</option>
                </select>
                </div>
                <div class="form-group col-md-6">
                <label>Menu Favorit</label>
                <select name="fav" class="form-control" required>
                  <option value="0" @if($p->fav == 0) selected @endif>Tidak</option>
                  <option value="1" @if($p->fav == 1) selected @endif>Ya</option>
                </select>
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
    @endforeach

@endsection

@section('script')
<script>
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
        title:"Sukses!",text:"{{Session::get('success')}}",icon:"success",
    })
@endif
@if(Session::has('error'))
 Swal.fire({
        title:"Gagal!",text:"{{Session::get('error')}}",icon:"warning",
    })
@endif
</script>
@endsection