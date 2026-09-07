@extends('layouts.index')
@section('title', '- Master User')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Master User
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('master/user')}}"><i class="fa fa-dashboard"></i> Master</a></li>
        <li class="active">User</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
           <div class="box box-primary">
            <div class="box-body">
                <form action="{{url('master/user/tambah')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- inputan role user admin atau kasir -->
                         <div class="col-md-2">
                            <div class="form-group">
                                <label>Level</label>
                                <select class="form-control" name="level">
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Masukkan Username" required>
                            </div>
                        </div>
                        <!-- input password dengan nilai bintang dan bisa di lihat saat di klik --> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Password" required>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" id="togglePassword">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                                <div class="col-md-1"> 
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
                         <th>Username</th>
                         <th>Level</th>
                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{$item->nama}}</td>
                            <td>{{$item->username}}</td>
                            <td><p class="text-primary">
                                {{$item->level}}
                                </p>
                            </td>
                            <td>
                               <a href="#" class="btn btn-warning btn-fill" data-toggle="modal" data-target="#edit-{{$item->id}}">Ubah</a>
                                <a href="{{url('master/user/hapus/'.$item->id)}}" class="btn btn-danger btn-fill">Hapus</a>
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
  <!-- modal edit  -->
    @foreach($data as $item)
        <div class="modal fade" id="edit-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{url('master/user/edit')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" value="{{$item->id}}">
                    <div class="form-group">
                        <label>Level</label>
                        <select class="form-control" name="level">
                            <option value="kasir" @if($item->level == 'kasir') selected @endif>Kasir</option>
                            <option value="admin" @if($item->level == 'admin') selected @endif>Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" value="{{$item->nama}}" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" value="{{$item->username}}" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak diubah">
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
document.getElementById('togglePassword').addEventListener('click', function () {
    var passwordInput = document.getElementById('password');
    var icon = this.querySelector('i');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>
@endsection