@extends('layouts.index')
@section('title', '- CLosing Penjualan')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Laporan Akhir Penjualan
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Closing</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6">
           <div class="box box-primary">
            <div class="box-header with-border">
            <a href="#" class="btn btn-sm btn-success pull-right" style="margin-right: 2px" data-toggle="modal" data-target="#ModalPengeluaran"><i class="fa fa-plus"></i> Tambah</a>
             <h4 class="title"><center><b>Pengeluaran (PO)<p style="color: red">Total Rp. {{ number_format($total_pengeluaran,0,',','.') }}</p></b></center></h4>
            </div>
            <div class="box-body">
            <div class="row">
             <div class="content table-responsive table-full-width">
                <table id="datatable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                         <th>NO</th>
                        <th>Ket</th>
                        <th>Jml</th>
                        <th>Tgl</th>
                        <th>Waktu</th>
                         <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengeluaran as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{$item->ket}}</td>
                            <td>Rp. {{ number_format($item->jml,0,',','.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->waktu)->format('H:i:s') }}</td>
                            <td>
                                <a href="{{url('laporan/pengeluaran/hapus/'.$item->kode_unik)}}" class="btn btn-danger btn-fill">Hapus</a>
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
            <div class="col-md-6">
            <div class="box box-primary">
            <div class="box-header with-border">
            <a href="#" class="btn btn-sm btn-success pull-right" style="margin-right: 2px" data-toggle="modal" data-target="#ModalAwal"><i class="fa fa-plus"></i> Tambah</a>
             <h4 class="title"><center><b>Modal<p style="color: red">Total Rp. {{ number_format($total_modal,0,',','.') }}</p></b></center></h4>
            </div>
            <div class="box-body">
            <div class="row">
             <div class="content table-responsive table-full-width">
                <table id="datatable3" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                         <th>NO</th>
                        <th>Ket</th>
                        <th>Jml</th>
                        <th>Tgl</th>
                        <th>Waktu</th>
                         <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modal as $no => $imodal)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{$imodal->ket}}</td>
                            <td>Rp. {{ number_format($imodal->modal,0,',','.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($imodal->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($imodal->waktu)->format('H:i:s') }}</td>
                            <td>
                                <a href="{{url('laporan/modal/hapus/'.$imodal->kode_unik)}}" class="btn btn-danger btn-fill">Hapus</a>
                               </td>
                        @endforeach
                    </tbody>
                </table>
                </div>
                </div>
                </div>
                </div>
                </div>
  @if(Auth::user()->level == 'admin')
  <div class="col-md-12">
    <div class="box box-primary">
    <div class="box-header with-border">
       <div class="box-body">
        <div class="row">
      <table class="table table-bordered table-striped">
                        <thead>
                                <tr>
                                 <th>Tgl</th>
                                    <th>Tunai</th>
                                    <th>Non Tunai</th>
                                    <th>Total Penjualan</th>
                                    <th>Aksi</th>
                                </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td>{{date('d-m-Y', strtotime($tgl))}}</td>
                                    <td>Rp. {{ number_format($total_akhir,0,',','.') }}</td>
                                    <td>Rp. {{ number_format($total_nontunai,0,',','.') }}</td>
                                    <td>Rp. {{number_format($total_akhir + $total_nontunai,0,',','.') }}</td>
                                    <td><a href="{{url('cetak_transaksi')}}" class="btn btn-info btn-sm">Detail</a></td>

                                </tr>
                        </tbody>
                </table>
            </div>
          </div>
          </div>
                </div>
                </div>
@endif 
            <div class="col-md-12">
            <div class='alert alert-warning'>
            <strong>PEMBERITAHUAN!!</strong> Bagi Kasir Jangan Lupa Tambah Modal Terlebih Dahulu Saat Awal Masuknya Shift dan Tekan Tombol Closing / tutup transaksi Setelah Berakhirnya Shift.
            <br>
            <a href="{{url('cetak_transaksi')}}" class="btn btn-primary btn-fill"><i class="fa fa-print"></i> Cetak</a>
            <a class="btn btn-success btn-fill" data-toggle="modal" data-target="#myModal_closing"><i class="fa fa-power-off"></i> Closing</a>
            </div>
            </div>

       </div><!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <div class="modal fade" id="myModal_closing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><center><b>Yakin Untuk Closing Sekarang</b></center></h4>
                  </div>
                  <form action="{{url('closing')}}" method="post" enctype="multipart/form-data">
                    @csrf
                  <div class="modal-body col-sm-12">
                  <div class="form-group">
  <h4 style="color: red"><center><b>Tunai + Modal - Pengeluaran = Rp. {{ number_format($total_modal + $total_akhir - $total_pengeluaran,0,',','.') }} (aplikasi)</b></center></h4>
  <label>Input Uang yang di Hitung (Manual)</label>
  <input type="text" name="uang_inputan" value="Rp. 0" class="form-control" onkeyup="convertToRupiah(this);" required>
  <input type="hidden" name="total_tunai" value="{{ $total_tunai }}">
  <input type="hidden" name="total_tdiskon" value="{{ $total_tdiskon }}">
  <input type="hidden" name="total_tpajak" value="{{ $total_tpajak }}">
  <input type="hidden" name="total_pengeluaran" value="{{ $total_pengeluaran }}">
  <input type="hidden" name="nontunai" value="{{ $nontunai }}">
  <input type="hidden" name="total_ndiskon" value="{{ $total_ndiskon }}">
  <input type="hidden" name="total_npajak" value="{{ $total_npajak }}">
  <input type="hidden" name="total_nontunai" value="{{ $total_nontunai }}">
  <input type="hidden" name="total_akhir" value="{{ $total_akhir }}">
  <input type="hidden" name="total_modal" value="{{ $total_modal }}">
                </div>
                  </div>
                  <div class="modal-footer">
                    <center>
                    <button type="submit" name="save" class="btn btn-danger btn-fill" onclick="this.form.submit(); this.disabled = true;"><i class="fa fa-check-circle-o"></i> Selesaikan</button>
                    </center>
                  </div>
                </form>
             
                </div>
              </div>
            </div> <!-- batas modal -->

            <!-- /.content-wrapper -->
  <div class="modal fade" id="ModalPengeluaran" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Pengeluaran (PO)</h4>
                  </div>
                  <form action="{{url('laporan/pengeluaran/tambah')}}" method="post" enctype="multipart/form-data">
                  @csrf
                  <div class="modal-body col-sm-12">
                  <div class="form-group">
                  
                  <input type="text" class="form-control border-input" name="ket" placeholder="Keterangan Pengeluaran" required><br>
                  <input type="text" class="form-control border-input" name="jml" onkeyup="convertToRupiah(this);" value="Rp. 0" required>
                </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-fill" data-dismiss="modal">Kembali</button>
                    <button type="submit" name="save" class="btn btn-success btn-fill">Simpan</button>
                  </div>
                </form>
             
                </div>
              </div>
            </div> <!-- batas modal -->

            <div class="modal fade" id="ModalAwal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Tambah MODAL</h4>
                  </div>
                  <form action="{{url('laporan/modal/tambah')}}" method="post" enctype="multipart/form-data">
                  @csrf
                  <div class="modal-body col-sm-12">
                  <div class="form-group">
                    <input type="text" class="form-control border-input" name="ket" placeholder="Keterangan Modal" required><br>
                  <input type="text" class="form-control border-input" name="modal" onkeyup="convertToRupiah(this);" value="Rp. 0" required>
                </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-fill" data-dismiss="modal">Kembali</button>
                    <button type="submit" name="save" class="btn btn-success btn-fill">Simpan</button>
                  </div>
                </form>
             
                </div>
              </div>
            </div> <!-- batas modal -->
   
@endsection

@section('script')
<script>

</script>
@endsection