@extends('layouts.index')
@section('title', '- Laporan Closing')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single {
    height: 35px;
    border: 1px solid #ccc;
    margin-top: 5px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding: 0 5px;
    color: #333;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px;
}
.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: #888 transparent transparent transparent;
    border-width: 8px 6px 0 6px;
}
.select2-dropdown {
    z-index: 1050 !important;
}
.select2-search__field {
    padding: 8px;   
}
.select2-results__option {
    padding: 10px;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #007bff;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Laporan Closing
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Laporan Closing</li>
      </ol>
    </section>
     <div class="row" style="margin-bottom: 0px; padding: 15px;">
      <div class="col-md-6">
        <form method="post" action="{{url('laporan/closing')}}" style="margin: 0;">
           @csrf
          <div class="row">
            <div class="col-md-3" style="padding-right: 10px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-control" value="{{isset($tgl_awal) ? $tgl_awal : ''}}" style="margin-top: 5px;">
              </div>
            </div>
            <div class="col-md-3" style="padding: 0 10px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-control" value="{{isset($tgl_akhir) ? $tgl_akhir : ''}}" style="margin-top: 5px;">
              </div>
            </div>
            
              <div class="col-md-3" style="padding-left: 10px; display: flex; flex-direction: column; justify-content: flex-end;margin-top: 30px;">
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
                        <button onclick="exportToXLSX()" class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i> Export Excel
                        </button>
                        <button onclick="exportToPDF()" class="btn btn-danger">
                            <i class="fa fa-file-pdf-o"></i> Export PDF
                        </button>
                    </div>
                </div>


                <table class="table table-bordered table-striped">
                        <thead>
                                <tr>
                                 <th>Tgl</th>
                                 <th>Jam</th>
                                 <th>User</th>
                                 <th>Kode</th>
                                    <th>Modal</th>
                                    <th>Tunai</th>
                                    <th>Non Tunai</th>
                                    <th>Total<br>(Tunai + Non Tunai)</th>
                                    <th>Aksi</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach($closing as $p)
                                <tr>
                                    <td>{{date('d-m-Y', strtotime($p->tanggal))}}</td>
                                    <td>{{date('H:i:s', strtotime($p->waktu))}}</td>
                                    <td>{{$p->nama_closing}}</td>
                                    <td>{{$p->kode_closing}}</td>
                                    <td>{{number_format($p->modal)}}</td>
                                    <td>{{number_format($p->net_total)}}</td>
                                    <td>{{number_format($p->total_debet)}}</td>
                                    <td>{{number_format($p->net_total + $p->total_debet)}}</td>
                                    <td>
                                        <a href="{{url('laporan/closing/detail/'.$p->kode_closing)}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Detail</a>
                                        <a href="{{url('laporan/closing/transaksi/'.$p->kode_closing)}}" class="btn btn-warning btn-sm"><i class="fa fa-list"></i> Cek Transaksi</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold; background-color: #f4f4f4;">
                                <th colspan="4" style="text-align: right;">Total:</th>
                                <th>{{ number_format($closing->sum('modal')) }}</th>
                                <th>{{ number_format($closing->sum('net_total')) }}</th>
                                <th>{{ number_format($closing->sum('total_debet')) }}</th>
                                <th>{{ number_format($closing->sum('net_total') + $closing->sum('total_debet')) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
  $(document).ready(function() {
      $('.select2').select2({
          width: '100%'
      });
  });

function exportToXLSX() {
      const table = document.querySelector('table');
      const wb = XLSX.utils.table_to_book(table);
      XLSX.writeFile(wb, 'closing.xlsx');
  }

  function exportToPDF() {
      const element = document.querySelector('table');
      html2pdf().from(element).save('closing.pdf');
  }
</script>
@endsection