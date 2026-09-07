@extends('layouts.index')
@section('title', '- Master Non Tunai')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Master Non Tunai
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('master/satuan')}}"><i class="fa fa-dashboard"></i> Master</a></li>
        <li class="active">Non Tunai</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
           <div class="box box-primary">
            <div class="box-body">
                <form action="{{url('master/non_tunai/tambah')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama" required>
                            </div>
                        </div>
                                <div class="col-md-4"> 
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary btn-fill"><i class="fa fa-plus"></i> Tambah</button>
                                    </div>
                                </div>
            
                </div>
             </form>

            </div>
        </div>
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
                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{$item->nama}}</td>
                            <td>
                                <a href="#" class="btn btn-warning btn-fill" data-toggle="modal" data-target="#edit-{{$item->id}}">Ubah</a>
                                <a href="{{url('master/non_tunai/hapus/'.$item->id)}}" class="btn btn-danger btn-fill">Hapus</a>
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
    <!-- modal untuk edit -->
     @foreach($data as $item)
    <div class="modal fade" id="edit-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
           <form action="{{url('master/non_tunai/edit')}}" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
              @csrf
                <input type="hidden" name="id" value="{{$item->id}}">
              <div class="form-group">
                <label>Nama Satuan</label>
                <input type="text" class="form-control" name="nama" value="{{$item->nama}}" required>
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
@endsection

@section('script')
<script>

</script>
@endsection