@extends('layouts.index')
@section('title', '- Pendapatan')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Laporan Closing
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Laporan Pendapatan</li>
      </ol>
    </section>
     <div class="row" style="margin-bottom: 0px; padding: 15px;">
      <div class="col-md-6">
        <form method="post" action="{{url('laporan/pendapatan')}}" style="margin: 0;">
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

              <div class="col-md-3" style="padding-left: 10px; display: flex; flex-direction: column; justify-content: flex-end;margin-top: 25px;">
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

                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-12">
                        <button onclick="printout()" class="btn btn-danger">
                            <i class="fa fa-file-pdf-o"></i> Export PDF
                        </button>
                    </div>
                </div>
               <!-- buat 2 tabel col-md-6 dari pendapatan dan pengeluaran lalu 1 tabel 12 untuk mengurangi pendapatan - pengeluaran -->
                <div class="row">
                    <div class="col-md-4">
                        <h2>Pendapatan</h2>
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>Bulan</th>
                            <th>Jumlah Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendapatan as $p)
                            <tr>
                            <td>{{$p->bulan}}</td>
                            <td>Rp. {{number_format($p->total_pendapatan,0,',','.')}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td><strong>Total</strong></td>
                                <td><strong>Rp. {{number_format($pendapatan->sum('total_pendapatan'),0,',','.')}}</strong></td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h2>Pembelian</h2>
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>Bulan</th>
                            <th>Jumlah Pembelian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelian as $p)
                            <tr>
                            <td>{{$p->bulan}}</td>
                            <td>Rp. {{number_format($p->total_pembelian,0,',','.')}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td><strong>Total</strong></td>
                                <td><strong>Rp. {{number_format($pembelian->sum('total_pembelian'),0,',','.')}}</strong></td>
                        </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h2>Saldo Bersih</h2>
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>Bulan</th>
                            <th>Saldo Bersih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendapatan as $p)
                            <tr>
                            <td>{{$p->bulan}}</td>
                            <td>Rp. {{number_format($p->total_pendapatan - ($pembelian->where('bulan', $p->bulan)->first()->total_pembelian ?? 0),0,',','.')}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td><strong>Total</strong></td>
                                <td><strong>Rp. {{number_format($pendapatan->sum('total_pendapatan') - $pembelian->sum('total_pembelian'),0,',','.')}}</strong></td>
                        </tbody>
                        </table>
                    </div>
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
  
   
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
//printout atau tangkap layar pada tabel pendapatan, pembelian dan saldo bersih
function printout() {
    //windows print hanya pada bagian tabel
    var element = document.querySelector('.table-full-width');
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = element.outerHTML;
    window.print();
    document.body.innerHTML = originalContent;
    
}

</script>
@endsection