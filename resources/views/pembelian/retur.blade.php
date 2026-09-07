@extends('layouts.index')
@section('title', '- Pembelian Retur')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Pembelian Retur
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pembelian Retur</li>
      </ol>
    </section>
    <!-- filter tanggal awal dan akhir -->
    <div class="row" style="margin-bottom: 0px; padding: 15px;">
      <div class="col-md-4">
        <form method="POST" action="{{url('pembelian/retur')}}" style="margin: 0;">
             @csrf
          <div class="row">
            <div class="col-md-4" style="padding-right: 10px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-control" value="{{isset($tgl_awal) ? $tgl_awal : ''}}" style="margin-top: 5px;">
              </div>
            </div>
            <div class="col-md-4" style="padding: 0 10px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-control" value="{{isset($tgl_akhir) ? $tgl_akhir : ''}}" style="margin-top: 5px;">
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
            
            <div class="box-body">
            <div class="row">
             <div class="content table-responsive table-full-width">
                <table id="datatable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                         <th>Tgl</th>
                         <th>Supplier</th>
                         <th>Item</th>
                          <th>Jumlah</th>
                          <th>Harga Beli</th>
                          <th>Harga Total</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($returs as $p)
                        <tr>
                          <td>{{date('d-m-Y', strtotime($p->tanggal))}}</td>
                          <td>{{$p->nama_supplier}}</td>
                          <td>{{$p->nama_stok}}</td>
                          <td>{{$p->jumlah}}</td>
                          <td>Rp. {{number_format($p->harga_beli)}}</td>
                          <td>Rp. {{number_format($p->harga_beli * $p->jumlah)}}</td>
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
  
    
@endsection

@section('script')

<script>

</script>
@endsection