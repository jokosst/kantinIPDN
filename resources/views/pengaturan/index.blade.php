@extends('layouts.index')
@section('title', '- Pengaturan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Pengaturan
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pengaturan</li>
      </ol>
    </section>


    <!-- Main content -->
    <section class="content">
      <div class="row">
<div class="col-md-4">
     <div class="row">

        <div class="col-md-12">
          <div class="box box-widget">
        <div class="box-header with-border">
          <h4 class="title"><center><b>PROFIL STRUK</b></center></h4>  							
        </div>
<div class="content">
<form action="{{ url('pengaturan/simpan') }}" method="post" enctype="multipart/form-data">
    @csrf
  
                            <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Nama Usaha</label>
                                                <input type="text" name="nama" class="form-control border-input" value="{{ $nama_usaha->nama }}">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Alamat</label>
                                                <textarea type="text" name="alamat" class="form-control border-input">{{ $nama_usaha->alamat }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Kontak</label>                                                
                                                <input type="text" name="kontak" class="form-control border-input" value="{{ $nama_usaha->kontak }}">
                                            </div>
                                        </div>
                                    </div> 
                                            
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Ucapan</label>
                                                <textarea type="text" name="ucapan" class="form-control border-input">{{ $nama_usaha->ucapan }}</textarea>
                                            </div>
                                        </div>
                                    </div> 


 <div class="text-center">
 <button type="submit"  class="btn btn-info btn-fill btn-wd">Ubah</button>
  </div>
</form>
</div>
 </div>
</div>
</div>
</div>

    <!-- buat box disini untuk pengaturan on off pajak dan reset kode item -->
     <div class="col-md-4">
     <div class="row">
        
<div class="col-md-12">
  <div class="box 
    @if($pajak->status == 1) box-success 
    @else box-danger 
    @endif">

    <div class="box-header with-border">
      <h3 class="box-title">Pajak</h3>
    </div>

    <div class="box-body">
      @if($pajak->status == 1)
        <p>Pajak Saat Ini : <b>Aktif</b></p>
      @else
        <p>Pajak Saat Ini : <b>Nonaktif</b></p>
      @endif
      <a href="#" data-toggle="modal" data-target="#pajakModal" class="btn 
        @if($pajak->status == 1) btn-danger 
        @else btn-success 
        @endif">
        @if($pajak->status == 1)
          Nonaktifkan Pajak
        @else
          Aktifkan Pajak
        @endif
      </a>
    </div>

  </div>
</div>

<!-- mode kasir -->
<div class="col-md-12">
  <div class="box box-primary">

    <div class="box-header with-border">
      <h3 class="box-title">Mode Kasir</h3>
    </div>

    <div class="box-body">
      <p>{{ $mode_kasir->ket }}</p>
      @if($mode_kasir->status == 0)
        <p>Mode Kasir Saat Ini : <b>Swalayan</b></p>
      @elseif($mode_kasir->status == 1)
        <p>Mode Kasir Saat Ini : <b>Coffee Shop</b></p>
      @endif
      <a href="#" data-toggle="modal" data-target="#modeModal" class="btn btn-primary">
        Ubah Mode Kasir
      </a>
    </div>

  </div>
</div>

<!-- reset kode item -->
<div class="col-md-12">
  <div class="box box-warning">
    <div class="box-header with-border">
      <h3 class="box-title">Reset Kode Item</h3>
      </div>
    <div class="box-body">
      <p>Reset kode item akan mengatur ulang kode item mulai dari 1 kembali.</p>
      <!-- buat comfirm alert swetSweetAlert2  -->
      <button onclick="confirmReset('{{ url('pengaturan/reset_kode_item') }}')" class="btn btn-warning">Reset Kode Item</button>

      
      </div>
      </div>
      </div>
      </div>


          
    </div>
    </div>
            
      


      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- Model Mode Kasir -->
<div class="modal fade" id="modeModal" tabindex="-1" role="dialog" aria-labelledby="modeModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modeModalLabel">Konfirmasi Mode Kasir</h4>
      </div>
      <form action="{{ url('pengaturan/mode/simpan') }}" method="post">
        @csrf
      <div class="modal-body">
        <div class="form-group">
          <label for="mode">Pilih Mode Kasir</label>
          <select class="form-control" id="mode" name="mode">
            <option value="0" @if($mode_kasir->status == 0) selected @endif>Swalayan</option>
            <option value="1" @if($mode_kasir->status == 1) selected @endif>Coffee Shop</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Ubah Mode Kasir</button>
      </div>
</form>
    </div>
  </div>
</div>

<!-- Modal Pajak -->
<div class="modal fade" id="pajakModal" tabindex="-1" role="dialog" aria-labelledby="pajakModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="pajakModalLabel">Konfirmasi Pajak</h4>
      </div>
      <form action="{{ url('pengaturan/pajak/simpan') }}" method="post">
        @csrf
      <div class="modal-body">
        @if($pajak->status == 1)
          <p>Apakah Anda yakin ingin menonaktifkan pajak?</p>
        @else
          <p>Apakah Anda yakin ingin mengaktifkan pajak?</p>
        @endif
        
        @if($pajak->status == 1)
          <input type="hidden" name="persen" value="{{ $pajak->persen }}">
          <input type="hidden" name="status" value="0">
        @else
            <input type="hidden" name="status" value="1">
          <div class="form-group">
            <label for="persen">Masukkan Persentase Pajak (%)</label>
            <input type="text" class="form-control" value="{{ $pajak->persen }}" name="persen" id="persen" required>
          </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn 
          @if($pajak->status == 1) btn-danger 
          @else btn-success 
          @endif">
          @if($pajak->status == 1)
            Nonaktifkan
          @else
            Aktifkan
          @endif
        </button>
      </div>
</form>
    </div>
  </div>
</div>

  
   
@endsection

@section('script')

<script>
function confirmReset(url) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Tindakan ini akan mengatur ulang kode item mulai dari 1 kembali.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, reset!',
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