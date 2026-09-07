@extends('layouts.index')
@section('title', '- Transaksi')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Meja Transaksi
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li class="active">Meja Transaksi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        @foreach($data as $m)
         <a href="{{url('transaksi/'.$m->id)}}">
         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box {{ $m->status == 'terisi' ? 'bg-red' : 'bg-aqua' }}">
            <div class="inner">
               <p style="font-size: 40px;"><b>{{$m->kode_meja}}</b></p>

              <p>Order</p>
            </div>
            <div class="icon">
              <i class="fa fa-cube"></i>
            </div>
            <div class="small-box-footer">
              Masuk <i class="fa fa-arrow-circle-right"></i>
            </div>
          </div>
        </div> 
        </a> 
       @endforeach


      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
    <!-- /.content-wrapper -->
@endsection

@section('script')
<script>

</script>
@endsection