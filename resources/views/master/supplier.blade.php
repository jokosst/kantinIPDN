@extends('layouts.index')
@section('title', '- Master Supplier')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Master Supplier
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('master/supplier')}}"><i class="fa fa-dashboard"></i> Master</a></li>
        <li class="active">Suppliers</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12" style="margin-bottom: 3px;">
        <!-- button tambah -->
        <a href="#" class="btn btn-primary btn-fill" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i> Tambah Supplier</a>

        </div>

        <div class="col-md-12">
           <div class="box box-primary">
            
            <div class="box-body">
            <div class="row">
             <div class="content table-responsive table-full-width">
                <table id="datatable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                         <th>No</th>
                         <th>Nama</th>
                         <th>Kontak</th>
                         <th>Alamat</th>
                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{$item->nama}}</td>
                            <td><p class="text-primary">
                                {{$item->kontak}}
                                </p>
                            </td>
                            <td>
                               {!! nl2br(e($item->alamat)) !!}
                            </td>
                            <td>
                               <a href="#" class="btn btn-warning btn-fill" data-toggle="modal" data-target="#edit-{{$item->id}}">Ubah</a>
                                <a href="{{url('master/supplier/hapus/'.$item->id)}}" class="btn btn-danger btn-fill">Hapus</a>
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
  
        <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{url('master/supplier/tambah')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Kontak</label>
                        <input type="number" class="form-control" name="kontak" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="alamat" required></textarea>
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
@endsection
<!-- edit modal -->
 @foreach($data as $item)
    <div class="modal fade" id="edit-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
           <form action="{{url('master/supplier/edit')}}" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
              @csrf
                <input type="hidden" name="id" value="{{$item->id}}">
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" value="{{$item->nama}}" required>  
                </div>
                <div class="form-group">
                <label>Kontak</label>
                <input type="number" class="form-control" name="kontak" value="{{$item->kontak}}" required>
              </div>
                <div class="form-group">
                <label>Alamat</label>
                <textarea class="form-control" name="alamat" required>{{$item->alamat}}</textarea>
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
     @endforeach

@section('script')
<script>

</script>
@endsection