@extends('layouts.index')
@section('title', '- Beranda')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Beranda
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Beranda</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        
        <!-- /.col -->
         <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
             <a href="#">
            <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span></a>

            <div class="info-box-content">
               <h4 class="info-box-text"><b>{{Auth::user()->level}}</b></h4>
               <span class="info-box-text">{{Auth::user()->nama}}</span>
               <span class="info-box-text"><?php echo "Tanggal : ". date('d-m-Y'); ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
             <a href="#">
            <span class="info-box-icon bg-orange"><i class="fa fa-money"></i></span></a>

            <div class="info-box-content">
               <h4 class="info-box-text"><b>Penghasilan Tunai</b></h4>
               <span class="info-box-text">Rp. {{number_format($tunai,0,',','.')}}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <a href="#">
            <span class="info-box-icon bg-maroon"><i class="fa fa-credit-card"></i></span></a>
            <div class="info-box-content">
               <h4 class="info-box-text"><b>Penghasilan Non Tunai</b></h4>
               <span class="info-box-text">Rp. {{number_format($nontunai,0,',','.')}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
             <a href="#">
            <span class="info-box-icon bg-purple"><i class="fa fa-shopping-cart"></i></span></a>

            <div class="info-box-content">
              <h4 class="info-box-text"><b>Total</b></h4>
              <span class="info-box-text"><b>Rp. {{number_format($tunai+$nontunai,0,',','.')}}</b></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-12">
          <!-- LINE CHART -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <h3 class="box-title">TRANSAKSI PENJUALAN</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="line-chart" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>

        <!-- alert stok menipis keluar saat total dari stok menipis diatas 0-->
        @if($stokmenipis > 0 && $pengaturan && $pengaturan->status == 0)
        <div class="col-md-12">
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i>
              Stok Menipis</h4>
              <p>Terdapat {{$stokmenipis}} Produk Stok Menipis, segera cek untuk menghindari kehabisan stok</p>
              <a href="{{url('master/item/stok_menipis')}}" class="btn btn-warning btn-fill">Lihat Produk</a>
          </div>
        </div>
        @endif
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
    <!-- /.content-wrapper -->
@endsection

@section('script')
<script>
  // LINE CHART
    var line = new Morris.Line({
      element: 'line-chart',
      resize: true,
      data: [
      
   @foreach($struk as $hasil)
     @php
      $total = $hasil->total;
      $waktu = $hasil->tgl;
      @endphp
        {tanggal: '{{$waktu}}', net_total :' {{$total}}' },
    @endforeach
       
      ],
      xkey: 'tanggal',
      ykeys: ['net_total'],
      labels: ['Total'],
      lineColors: ['#3c8dbc'],
      hideHover: 'auto'
    });
</script>
@endsection