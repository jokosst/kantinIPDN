@extends('layouts.index')
@section('title', '- Struk')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Struk Transaksi
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">List Struks</li>
      </ol>
    </section>
    @if(request()->is('liststruk'))
    <div class="row" style="margin-bottom: 0px; padding: 15px;">
      <div class="col-md-4">
        <form method="post" action="{{url('liststruk')}}" style="margin: 0;">
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
    @endif

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
           <div class="box box-primary">
            <div class="box-header with-border">

              @if(request()->is('liststruk_panding'))
              <a href="{{url('liststruk')}}" class="btn btn-sm btn-success pull-right" style="margin-right: 2px"><i class="fa fa-credit-card"></i> Pembayaran Lunas</a>
              @else
              <a href="{{url('liststruk_panding')}}" class="btn btn-sm btn-danger pull-right" style="margin-right: 2px"><i class="fa fa-credit-card"></i> Pembayaran Belum Lunas</a>
              @endif
            </div>
            <div class="box-body">
              @if(session('kode_retur_baru'))
              <div class="alert alert-info">
                Kode retur admin: <strong>{{ session('kode_retur_baru') }}</strong>
                <br>
                Berlaku sampai: <strong>{{ \Carbon\Carbon::parse(session('kode_retur_expired_at'))->format('d-m-Y H:i:s') }}</strong>
              </div>
              @endif
            <div class="row">
             <div class="content table-responsive table-full-width">
                <table id="datatable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                         <th>No</th>
                         <th>Pembayaran</th>
                          <th>Kasir</th>
                          <th>Pelanggan</th>
                          <th>Kode Struk</th>
                           <th>Total Penjualan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($struks as $no => $item)
                        
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>
                              @if($item->bayar == 0)
                              Tunai
                              @elseif($item->bayar == 1)
                              @php
                              $bank = DB::table('non_tunai')->where('id', $item->metode_pembayaran)->first();
                              @endphp
                              Non Tunai [{{ $bank->nama ?? 'lainnya' }}]
                              @elseif($item->bayar == 2)
                              Belum Lunas
                              @endif
                              </td>
                            <td>{{ $item->nama_kasir ?? 'Admin' }}</td>
                            <td>{{ $item->pembeli ?? '-' }}</td>
                            <td>{{ $item->kode_struk }}</td>
                            <td>Rp. {{ number_format($item->total,0,',','.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tgl)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->waktu)->format('H:i:s') }}</td>
                            <td>
                               <a href="#" class="btn btn-info btn-fill" data-toggle="modal" data-target="#myModal{{$item->id}}">Detail</a>
                                <a href="{{url('transaksi/struk/'.$item->kode_struk)}}" class="btn btn-success btn-fill">Cetak</a>
                                <a href="#" data-toggle="modal" data-target="#Retur{{$item->id}}" class="btn btn-danger btn-fill">Retur</a>
                                @if($item->bayar == 2)
                                <a href="#" data-toggle="modal" data-target="#Bayar{{$item->id}}" class="btn btn-warning btn-fill">Bayar</a>
                                @endif
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
    <!-- /.content-wrapper -->
    @foreach($struks as $item)
           <div class="modal fade" id="myModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><b>Detail Struk Kode #{{$item->kode_struk}}</b> [{{$item->nama_kasir}}]</h4>
                  </div>
                  <div class="modal-body col-sm-12">
                    
                          
                             <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Tgl</label>
                                  <div class="col-lg-8">
                                      <p>: {{ \Carbon\Carbon::parse($item->tgl)->format('d-m-Y') }}</p>
                                  </div>
                              </div>
                              <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Meja</label>
                                  <div class="col-lg-8">
                                    @php
                                    $meja = DB::table('meja')->where('id', $item->meja)->first();
                                    @endphp
                                      <p>: {{$meja->kode_meja}}</p>
                                  </div>
                              </div>
                              
                               <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Total</label>
                                  <div class="col-lg-8">
                                      <p>: Rp. {{ number_format($item->total,0,',','.') }}</p>
                                  </div>
                              </div>
                              
                               <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Uang Pembayaran</label>
                                  <div class="col-lg-8">
                                      <p>: Rp. {{ number_format($item->uang_chas,0,',','.') }}</p>
                                  </div>
                              </div>
                               <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Kembalian</label>
                                  <div class="col-lg-8">
                                      <p>: Rp. {{ number_format($item->kembalian,0,',','.') }}</p>
                                  </div>
                              </div>
                              <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Metode Pembayaran</label>
                                  <div class="col-lg-8">
                                    <p>:
                              @if($item->bayar == 0)
                               Tunai
                              @elseif($item->bayar == 1)
                              @php
                              $bank = DB::table('non_tunai')->where('id', $item->metode_pembayaran)->first();
                              @endphp
                              Non Tunai [{{ $bank->nama ?? 'Lainnya' }}]
                              @elseif($item->bayar == 2)
                              Belum Lunas
                              @endif
                                      </p>
                                  </div>
                              </div>
                              <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Pelanggan</label>
                                  <div class="col-lg-8">
                                      <p>: {{$item->pembeli}}</p>
                                  </div>
                              </div>
                               <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Pembelian</label>
                                  <!-- <div class="col-lg-8">
                                      <p>: </p>
                                  </div> -->
                              </div>
                              <?php
                                $transaksi = \App\Models\Transaksi::where('kode_struk', $item->kode_struk)->get();
                                  ?>
                                  @foreach($transaksi as $drelasi)
                                  <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4"></label>
                                  <div class="col-lg-8">
                                      <p> + <?php 
                                      if($drelasi['status_note'] > 0){
                                      echo $drelasi['nama_pesanan']," (",$drelasi['jumlah'],") [Disc ",$drelasi['status_note'],"%]";
                                    }else{
                                      echo $drelasi['nama_pesanan']," (",$drelasi['jumlah'],")";
                                    }
                                    ?>  </p>
                                  </div>
                              </div>
                              @endforeach


                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-fill" data-dismiss="modal">Kembali</button>
                    <a href="{{url('transaksi/struk/'.$item->kode_struk)}}" class="btn btn-success btn-fill">cetak</a>
                  </div>
             
                </div>
              </div>
            </div> <!-- batas modal -->

            <!-- Modal bayar tunai atau non tunai -->
             <div class="modal fade" id="Bayar{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><b>Bayar Struk Kode #{{$item->kode_struk}}</b> </h4>
                  </div>
                  <div class="modal-body col-sm-12">
                    <form action="{{ url('transaksi/lunasi') }}" method="POST">
                      @csrf
                          <input type="hidden" name="id" value="{{$item->id}}">
                             <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Total Yang Harus Dibayar</label>
                                  <div class="col-lg-8">
                                      <p>: Rp. {{ number_format($item->total,0,',','.') }}</p>
                                  </div>
                              </div>
                              
                               <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Pelanggan</label>
                                  <div class="col-lg-8">
                                      <p>: {{$item->pembeli}}</p>
                                  </div>
                              </div>
                              <!-- pilih metode pembayaran -->
                              <div class="form-group has-success">
                                  <label class="col-sm-4 control-label col-lg-4" for="inputSuccess">Metode Pembayaran</label>
                                  <div class="col-lg-8">
                                      <select name="metode_pembayaran" class="form-control" required>
                                        <option value="0">Tunai</option>
                                        @php
                                        $non_tunais = DB::table('non_tunai')->get();
                                        @endphp
                                        @foreach($non_tunais as $nt)
                                        <option value="{{ $nt->id }}">{{ $nt->nama }}</option>
                                        @endforeach
                                      </select>
                                  </div>
                                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-fill">Bayar Sekarang</button>
                  </div>
                  </form>
                </div>
              </div>
            </div> <!-- batas modal bayar tunai atau non tunai -->
            <!-- Modal Retur -->
             <div class="modal fade" id="Retur{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="{{ url('transaksi/retur') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <input type="hidden" name="kode_struk" value="{{ $item->kode_struk }}">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center" id="myModalLabel"><b>Retur Struk Kode #{{$item->kode_struk}}</b></h4>
                  </div>
                  <div class="modal-body col-sm-12 text-center">
                    <p>Apakah anda yakin ingin mengembalikan/retur struk dengan kode <strong>#{{$item->kode_struk}}</strong> ?</p>
                  </div>
                  
                  <div class="modal-body col-sm-12">
                    <div class="form-group">
                      <label for="reason">Alasan Retur:</label>
                      <textarea class="form-control" name="alasan" id="reason" rows="3" required></textarea>
                    </div>
                  </div>
@if(auth()->check() && auth()->user()->level === 'kasir')
<div class="modal-body col-sm-12">
  <div class="form-group">
    <label for="kode_admin_{{ $item->id }}">Kode Sementara Admin</label>
    <input type="text"
           class="form-control"
           id="kode_admin_{{ $item->id }}"
           name="kode_admin"
           placeholder="Masukkan kode dari admin"
           required>
    <small class="text-muted">Kasir wajib meminta kode sementara ke admin.</small>
  </div>
  </div>
  @endif

                  <div class="modal-footer" style="text-align:center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-danger btn-fill">SELESAIKAN RETUR</button>
                  </div>
                  </form>
                </div>
              </div>
            </div> <!-- batas modal Retur -->
    @endforeach
@endsection

@section('script')
<script>
@if(Session::has('success'))
Swal.fire({title: 'Sukses!', text: '{{ Session::get('success') }}', icon: 'success'});
@endif

@if(Session::has('error'))
Swal.fire({title: 'Gagal!', text: '{{ Session::get('error') }}', icon: 'warning'});
@endif
</script>
@endsection