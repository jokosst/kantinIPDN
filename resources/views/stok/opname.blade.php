@extends('layouts.index')
@section('title', '- Stok Opname')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #ccc;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding: 0 8px;
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

/* Rapikan padding tabel pada modal opname multi item */
#tambah_opname .modal-body {
  padding: 20px;
}

#tambah_opname #multi-item-table {
  margin-top: 8px;
}

#tambah_opname #multi-item-table > thead > tr > th,
#tambah_opname #multi-item-table > tbody > tr > td {
  padding: 10px 12px;
  vertical-align: middle;
}

#tambah_opname #multi-item-table .sesudah-input {
  padding: 6px 10px;
  height: 40px;
}

#tambah_opname .dataTables_wrapper .dataTables_length,
#tambah_opname .dataTables_wrapper .dataTables_filter {
  margin-bottom: 10px;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Stok Opname
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Stok Opname</li>
      </ol>
    </section>
     <div class="row" style="margin-bottom: 0px; padding: 15px;">
      <div class="col-md-4">
        <form method="post" action="{{url('stok/opname')}}" style="margin: 0;">
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

    <!-- Main content -->
    <section class="content">
      <div class="row">

      <div class="col-md-12">
      <div class="box box-widget">
        <div class="box-header with-border">
         <h4 class="title"><b>Tambah dengan Scan Barcode / Qrcode</b>
          </h4>  
        </div>
            <form id="scanForm" action="#" method="post" enctype="multipart/form-data">
     <div class="box-body">
        <div class="input-group">
                  
        @csrf
        <input type="text" id="scan-kode" name="kode" accesskey="a" autocomplete="off" class="form-control" autofocus> 
        <span class="input-group-addon"><i class="fa fa-search"></i></span>
        </div>

     </div>
 </form>

</div>
</div> 

        <div class="col-md-12">
           <div class="box box-primary">
            <div class="box-header with-border">
            <a href="#" data-toggle="modal" data-target="#tambah" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</a>
            <a href="#" data-toggle="modal" data-target="#tambah_opname" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> Tambah Multi Item</a>
            </div>
            <div class="box-body">
            <div class="row">
             <div class="content table-responsive table-full-width">
                <table id="datatable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                          <th>No</th>
                         <th>Tgl</th>
                         <th>Kode</th>
                         <th>Item</th>
                          <th>Stok Awal</th>
                          <th>Stok Perbaikan</th>
                          <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($opname as $no => $p)
                        <tr>
                          <!-- nomor -->
                          <td>{{ $no + 1 }}</td>
                          <td>{{date('d-m-Y', strtotime($p->tgl_opname))}}</td>
                          <td>{{$p->kode}}</td>
                          <td>{{$p->nama}}</td>
                          <td>{{number_format($p->sebelum)}}</td>
                          <td>{{number_format($p->sesudah)}}</td>
                          <td>{{number_format($p->sesudah - $p->sebelum)}}</td>
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
  
    <!-- modal tambah -->
    <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Stok Opname</h5>
          </div>
          <form method="POST" action="{{url('stok/opname/tambah')}}">
            @csrf
            <div class="modal-body row">
              <div class="form-group col-md-4">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{date('Y-m-d')}}" required>
              </div>
              <div class="form-group col-md-12">
                <label>Item</label>
                <select name="id_stok" class="form-control select2" required>
                  <option value="">-- Pilih Item --</option>
                  @foreach($item as $i)
                  @php 
                  $satuan = DB::table('satuan')->where('id', $i->satuan)->first();
                  if(!$satuan){
                    $satuan = (object) ['nama_satuan' => 'Tidak Ada'];
                  }
                  @endphp
                  <option value="{{$i->id_item}}">{{$i->nama}} [{{$satuan->nama_satuan}}] [{{$i->kode}}]</option>
                  @endforeach
                </select>
              </div>
              
                <div class="form-group col-md-6">
                <label>Stok Sebenarnya</label>
                <input type="number" name="sesudah" class="form-control" value="0" required>
              </div>
            </div>
            <div id="multi-hidden-fields"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- end modal tambah -->
     <!-- modal tambah multi item-->
    <div class="modal fade" id="tambah_opname" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Stok Opname Multi Item</h5>
          </div>
          <form method="POST" action="{{url('stok/opname/tambah_multi')}}">
            @csrf
            <div class="modal-body row">
              <div class="form-group col-md-4">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{date('Y-m-d')}}" required>
              </div>
              <div class="col-md-12">
                <p class="text-muted" style="margin-top:8px;">Centang item yang ingin di-opname, lalu isi stok perbaikan.</p>
              </div>
              
             <table id="multi-item-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                          <th style="width:40px;"><input type="checkbox" id="check-all-item"></th>
                          <th>No</th>
                         <th>Kode</th>
                         <th>Item</th>
                          <th>Stok Awal</th>
                          <th>Stok Perbaikan</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($item as $no => $i)
                        @php 
                        $satuan = DB::table('satuan')->where('id', $i->satuan)->first();
                        if(!$satuan){
                          $satuan = (object) ['nama_satuan' => 'Tidak Ada'];
                        }
                        @endphp
                        <tr>
                          <!-- nomor -->
                          <td>
                            <input type="checkbox" class="item-check" name="item_ids[]" value="{{$i->id_item}}">
                          </td>
                          <td>{{ $no + 1 }}</td>
                          <td>{{$i->kode}}</td>
                          <td>{{$i->nama}} [{{$satuan->nama_satuan}}]</td>
                          <td>{{number_format($i->stok)}}</td>
                          <td><input type="number" name="sesudah[{{$i->id_item}}]" class="form-control sesudah-input" data-stok="{{$i->stok}}" value="{{$i->stok}}" min="0" disabled></td>
                        </tr>
                      @endforeach
                    </tbody>
                   
                </table>
             
            </div>
            <div id="multi-hidden-fields"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- end modal tambah multi item-->
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$('#tambah').on('shown.bs.modal', function () {
    $(this).find('.select2').select2({
        dropdownParent: $('#tambah'),
        width: '100%'
    });
});



function confirmDelete(url) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

// Scan -> buka modal dan pilih item yang cocok
$(document).ready(function(){
  function handleScan(){
    var kode = $('#scan-kode').val().trim();
    if(!kode) return;
    var $select = $('#tambah').find('select[name="id_stok"]');
    var found = false;
    $select.find('option').each(function(){
      var text = $(this).text();
      if(text.indexOf(kode) !== -1){
        $select.val($(this).val()).trigger('change');
        found = true;
        return false;
      }
    });
    if(found){
      $('#tambah').modal('show');
      setTimeout(function(){
        $('#tambah').find('input[name="sesudah"]').focus();
      }, 300);
    } else {
      Swal.fire({icon:'warning', title:'Item tidak ditemukan', text: 'Kode: ' + kode});
    }
    $('#scan-kode').val('').focus();
  }

  // Submit form atau tekan Enter pada input scan
  $('#scanForm').on('submit', function(e){ e.preventDefault(); handleScan(); });
  $('#scan-kode').on('keypress', function(e){
    if(e.which === 13){ e.preventDefault(); handleScan(); }
  });

    // DataTable untuk multi item
    if ($.fn.DataTable.isDataTable('#multi-item-table')) {
      $('#multi-item-table').DataTable().destroy();
    }
    var multiTable = $('#multi-item-table').DataTable({
      pageLength: 10,
      order: [[1, 'asc']],
      columnDefs: [
        { orderable: false, targets: [0, 5] }
      ]
    });

    $('#check-all-item').on('change', function(){
      var checked = $(this).is(':checked');
      multiTable.$('.item-check').prop('checked', checked).trigger('change');
    });

    $(document).on('change', '.item-check', function(){
      var $row = $(this).closest('tr');
      var $input = $row.find('.sesudah-input');
      if ($(this).is(':checked')) {
        $input.prop('disabled', false).attr('required', true);
      } else {
        $input.prop('disabled', true).removeAttr('required');
      }
    });

    // Pastikan item terpilih dari semua halaman DataTable ikut terkirim saat submit
    $('#tambah_opname form').on('submit', function(e){
      var $form = $(this);
      var $hidden = $form.find('#multi-hidden-fields');
      if (!$hidden.length) {
        $hidden = $('<div id="multi-hidden-fields"></div>').appendTo($form);
      }
      $hidden.empty();

      var checkedItems = multiTable.$('input.item-check:checked');
      if (checkedItems.length < 1) {
        e.preventDefault();
        Swal.fire({icon:'warning', title:'Pilih item dulu', text:'Pilih minimal 1 item untuk disimpan.'});
        return;
      }

      checkedItems.each(function(){
        var $cb = $(this);
        var idItem = $cb.val();
        var $row = $cb.closest('tr');
        var sesudah = $row.find('.sesudah-input').val();

        $hidden.append('<input type="hidden" name="item_ids[]" value="' + idItem + '">');
        $hidden.append('<input type="hidden" name="sesudah[' + idItem + ']" value="' + (sesudah !== undefined ? sesudah : 0) + '">');
      });
    });
});

@if(Session::has('success'))
 Swal.fire({
        title:"Sukses!",text:"{{Session::get('success')}}",icon:"success",
    })
@endif
</script>
@endsection