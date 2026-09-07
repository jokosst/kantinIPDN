@extends('layouts.index')
@section('title', '- Grafik Penjualan Bulanan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Grafik Penjualan
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Laporan Grafik Penjualan Bulanan</li>
      </ol>
    </section>

<div class="row" style ="margin-top: 15px;margin-left: 5px">
     <a href="{{url('grafik/penjualan')}}">
          <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            
            <span class="info-box-icon bg-navy"><i class="fa fa-line-chart"></i></span>

            <div class="info-box-content">
               <h4 class="info-box-text"><b>GRAFIK HARIAN</b></h4>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        </a>
             <a href="{{url('grafik/penjualan_bulanan')}}">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-olive"><i class="fa fa-line-chart"></i></span>

            <div class="info-box-content">
               <h4 class="info-box-text"><b>GRAFIK BULANAN</b></h4>
            </div>
          </div>
        </div>
        </a>
        <!-- /.col -->
        <!-- /.col -->
            <a href="{{url('grafik/penjualan_tahunan')}}">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-line-chart"></i></span>
            <div class="info-box-content">
               <h4 class="info-box-text"><b>GRAFIK TAHUNAN</b></h4>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
    </a>

    </div>

     <div class="row" style="margin-bottom: 0px; padding: 15px;">
      <div class="col-md-6">
        <form method="post" action="{{url('grafik/penjualan_bulanan')}}" style="margin: 0;">
           @csrf
          <div class="row">
            <div class="col-md-3" style="padding-right: 10px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label>Tahun</label>
                <select class="form-control select2" name="tahun" required>
                  <option value="">-- Pilih Tahun --</option>
                  @foreach($tahun as $t)
                  <option value="{{$t->tahun}}" @if(isset($tahun_pilih) && $tahun_pilih == $t->tahun) selected @endif>{{$t->tahun}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4" style="padding-left: 10px; display: flex; flex-direction: column; justify-content: flex-end;margin-top: 25px;">
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

             <!-- LINE CHART -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <h3 class="box-title">PENDAPATAN</h3>

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
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  
   
@endsection

@section('script')
<script>
    // LINE CHART
    var line = new Morris.Line({
      element: 'line-chart',
      resize: true,
      data: [
      
   @foreach($data as $hasil)
     @php
      $total = $hasil->total;
      $waktu = $hasil->bulan;
      @endphp
        {waktu: 'Bulan {{$waktu}}', total :' {{$total}}' },
    @endforeach
       
      ],
      xkey: 'waktu',
      ykeys: ['total'],
      labels: ['Total'],
      lineColors: ['#3c8dbc'],
      hideHover: 'auto'
    });

</script>
@endsection