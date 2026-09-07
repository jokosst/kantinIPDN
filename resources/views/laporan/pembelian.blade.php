@extends('layouts.index')
@section('title', '- Laporan Pembelian')
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
        Laporan Pembelian
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Laporan Pembelian</li>
      </ol>
    </section>
     <div class="row" style="margin-bottom: 0px; padding: 15px;">
      <div class="col-md-8">
        <form method="post" action="{{url('laporan/pembelian')}}" style="margin: 0;">
           @csrf
          <div class="row">
            <div class="col-md-2" style="padding-right: 10px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-control" value="{{isset($tgl_awal) ? $tgl_awal : ''}}" style="margin-top: 5px;">
              </div>
            </div>
            <div class="col-md-2" style="padding: 0 10px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-control" value="{{isset($tgl_akhir) ? $tgl_akhir : ''}}" style="margin-top: 5px;">
              </div>
            </div>
            <!-- input supplier -->

            <div class="col-md-3" style="padding: 0 10px;">
                 <div class="form-group" style="margin-bottom: 0;">
                    <label>Supplier</label>
              <select name="supplier" class="form-control select2" style="margin-top: 5px;">
                <option value="">Semua Supplier</option>
                <option value="0" {{isset($supplier) && $supplier == '0' ? 'selected' : ''}}>Tidak Ada</option>
                @foreach($suppliers as $s)
                  <option value="{{$s->id}}" {{isset($supplier) && $supplier == $s->id ? 'selected' : ''}}>{{$s->nama}}</option>
                @endforeach
              </select>
              </div> 
            </div>
            <div class="col-md-3" style="padding: 0 10px;">
                 <div class="form-group" style="margin-bottom: 0;">
                    <label>Item</label>
              <select name="item" class="form-control select2" style="margin-top: 5px;">
                <option value="">Semua Item</option>
                @foreach($stok as $s)
                  <option value="{{$s->id_item}}" {{isset($item) && $item == $s->id_item ? 'selected' : ''}}>{{$s->nama}} [{{$s->kode}}]</option>
                @endforeach
              </select>
              </div> 
            </div>
              <div class="col-md-2" style="padding-left: 10px; display: flex; flex-direction: column; justify-content: flex-end;margin-top: 30px;">
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
                                 <th>Supplier</th>
                                 <th>Item</th>
                                    <th>Jumlah</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Total</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelian as $p)
                                <tr>
                                    <td>{{date('d-m-Y', strtotime($p->tanggal))}}</td>
                                    <td>{{ $p->nama_supplier ? $p->nama_supplier : 'Tidak Ada' }}</td>
                                    <td>{{$p->nama_stok}}</td>
                                    <td>{{$p->jumlah}}</td>
                                    <td>Rp. {{number_format($p->harga_beli)}}</td>
                                    <td>Rp. {{number_format($p->harga_beli * $p->jumlah)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                                <tr>
                                        <th colspan="5" style="text-align: right;">Total:</th>
                                        <th>Rp. {{number_format($pembelian->sum(function($pembelian) { return $pembelian->harga_beli * $pembelian->jumlah; }))}}</th>
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
      XLSX.writeFile(wb, 'pembelian.xlsx');
  }

  function exportToPDF() {
      const element = document.querySelector('table');
      html2pdf().from(element).save('pembelian.pdf');
  }
</script>
@endsection