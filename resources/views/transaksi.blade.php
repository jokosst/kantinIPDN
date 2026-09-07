@extends('layouts.index')
@section('title', '- Transaksi')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        TRANSAKSI
        <br>
        <small>
          <u>shortcut</u><br>
         <span class="badge bg-navy"> ALT + X</span> : MENUJU KE BARCODE / QRCODE,
        <span class="badge bg-navy">ALT + S</span> : MENCARI ITEM TANPA CODE,
        <span class="badge bg-navy">ALT + Z</span> : INPUT DATA MEMBER,
        <span class="badge bg-navy">ALT + A</span> : INPUT DATA PEMBAYARAN LALU TEKAN ENTER,
        <span class="badge bg-navy">ALT + C</span> : PEMBAYARAN NON TUNAI
        </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li class="active">Transaksi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
            <div class="row"> 
                <!-- col 12 pertama didalam 8 -->
                <div class="col-md-12">
                <div class="box box-widget">
                    <!-- /.box-header -->
                    <div class="box-body">
                    <div class="row">
                    <div class="table-responsive table-full-width">
                    <table class="table">
                    <thead>
                    <tr>
                    <th>KODE</th>
                    <th>Aksi</th>
                    </tr>
                    </thead>                                       
                    <tbody>
                    <tr>
                    <form action="{{url('transaksi/tambah_kode')}}" method="post" enctype="multipart/form-data">
                    @csrf             
                    <td>
                    <div class="input-group">             
                    <input type="text" accesskey="x" name="kode" class="form-control" autocomplete="off" autofocus placeholder="Alt+x"> 
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="hidden" name="id_meja" value="{{$id_meja}}">  
                    </div>
                    </td>                    
                    <td>
                    
                    <a href="#" accesskey="s" data-toggle="modal" data-target="#CariItem" class="btn btn-warning btn-fill btn-wd">CARI ITEM(Alt+s)</a>
                    <a href="#" accesskey="y" data-toggle="modal" data-target="#InputEcommerce" class="btn btn-primary btn-fill btn-wd">Ecommerse(Alt+e)</a>
                    <a href="#" accesskey="w" data-toggle="modal" data-target="#lainnya" class="btn btn-default btn-fill btn-wd">Lainnya(Alt+w)</a>
                    </td>
                    </form>                                        
                    </tr>                                      
                    </tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                    <!-- tutup boxheader -->
                </div>
                </div>
                <!-- col 12 kedua di dalam 8 -->
                <div class="col-md-12">
                <div class="box box-widget">
                    <!-- /.box-header -->
                    <div class="box-body">
                    <div class="row">
                    <div class="content table-responsive table-full-width">
                    <table class="table table-striped">
                    <thead>
                    <th>shortcut</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    </thead>
                    <tbody>
                    @foreach($transaksi as $no => $dp)
                    @php
                    $total_item = $dp->jumlah * $dp->harga;
                    @endphp
                    <tr>
                    <td>
                    ALT + {{ $no + 1 }}
                    </td>
                    <td>
                    <a href="#"
                    accesskey="{{ $no + 1 }}"
                    data-toggle="modal"
                    data-target="#myModal_pilih{{ $dp->id_item }}slot{{ $dp->slot }}ket{{$dp->ket}}">
                    {{ $dp->nama }}
                    </a>
                    </td>
                    <td>
                    @if($dp->status_note > 0)
                    {{ number_format((float)$dp->note) }} (x{{ $dp->status_note }}%)
                    @else
                    {{ number_format((float)$dp->harga) }}
                    @endif
                    </td>
                    <td>{{ $dp->jumlah }}</td>
                    <td>{{ number_format((float)$total_item) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                    <!-- tutup boxheader -->
                </div>
                </div>


            </div>
            </div>
            <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                <div class="box box-widget">
                    <div class="box-header with-border">
                    <h4 class="title"><center><b>TOTAL PEMBAYARAN</b></center></h4>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <div class="row">
                    <!-- mulai col 12 -->
                    <div class="col-md-12">
                    <h2 class="title"><b>Rp. {{ number_format((float)$jlakhir) }}</b></h2>
                    <form action="{{url('transaksi_simpan_struk')}}" method="post" enctype="multipart/form-data">
                    @csrf   
                    <input type="hidden" name="id_meja" value="{{$id_meja}}">
                    <input type="hidden" name="total" value="{{$jlakhir}}">
                    <input type="hidden" name="total_transaksi" value="{{$total_transaksi}}">
                    <div class="form-group">
                    <div class="col-md-2">
                    <label>MEMBER</label>
                    </div>
                    <div class="col-md-10">
                    <input type="text" name="pembeli" autocomplete="off" value="" accesskey="z" class="form-control border-input" onkeyup="no_hp(this);"><br>
                    </div>
                    </div>
                    <div class="col-md-8">
                    <input type="text" accesskey="a" autocomplete="off" name="uang_chas" class="form-control border-input input-lg" placeholder="Uang Pembayaran(Alt+a)" onkeyup="convertToRupiah1(this);" required>
                    </div>
                    <div class="col-md-2 col-xs-2">
                    <button type="submit" name="save" class="btn btn-warning btn-lg" onclick="this.form.submit(); this.disabled = true;">Cetak</button>    
                    </div> 
                    </form>
                    <div class="col-md-12">
                    <h3><b>
                    KEMBALIAN <br>
                    <input type="text" id="result" class="form-control border-input input-lg" disabled>
                    </b></h3>
                    </div>
                    <div class="col-md-12">
                    <a href="#" data-toggle="modal" data-target="#non_tunai" accesskey="c" class="btn btn-lg btn-danger"><i class="fa fa-credit-card"></i> Non Tunai</a>
                    <a href="#" data-toggle="modal" data-target="#pending" accesskey="q" class="btn btn-lg btn-info"><i class="fa fa-credit-card"></i> Pending</a>
                    </div>
                    <!-- tutup col 12 -->
                    </div>
                    </div>
                    </div>
                    <!-- tutup boxheader -->

                </div>
                </div>
                @php 
                $diskon = DB::table('pajak')->where('id', 2)->first();
                $diskon_p     = $diskon->persen ?? 0;
                $hasil_diskon = $diskon_p * 100;
                $sdiskon      = $diskon->status ?? 0;
                @endphp
                <!-- col 12 diskon -->
                <div class="col-md-12">
                <div class="box box-solid box-primary">
                <div class="box-header with-border">
                <h3 class="box-title">DISKON</h3>
                </div>
                <div class="box-body">
                <form method="post" action="{{url('transaksi_ubah_diskon')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">            
                <div class="col-md-6">
                <div class="form-group">
                <label>Diskon (%)</label>
                <input type="number" name="persen" class="form-control" value="{{ $hasil_diskon }}" required>
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control border-input" required>
                <option @php if($sdiskon == 0){echo"selected";} @endphp value="0">Tidak Aktif</option>
                <option @php if($sdiskon == 1){echo"selected";} @endphp value="1">Aktif</option>
                </select>
                </div>
                </div>
                <div class="col-md-3 col-xs-3">
                <button type="submit" name="save" class="btn btn-danger btn-fill">Ubah</button> 
                </div>
                </div>
                </form>
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
    <!-- /.content-wrapper -->

<!-- modal item lainnya -->
<div class="modal fade" id="lainnya" tabindex="-1" role="dialog" aria-labelledby="lainnyaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="top: 20%;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="lainnyaLabel">Input Item Lainnya</h4>
      </div>
      <form action="{{url('transaksi/tambah_lainnya')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body col-sm-12">
          <input type="hidden" name="id_meja" value="{{$id_meja}}">
          <div class="form-group">
            <label>Nama Item</label>
            <input type="text" name="nama" class="form-control" autocomplete="off" required>
          </div>
          <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
          </div>
          <div class="form-group">
            <label>Harga</label>
            <input type="text" name="harga" class="form-control" onkeyup="convertToRupiah(this);" value="Rp. 0" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">TUTUP</button>
          <button type="submit" class="btn btn-primary btn-fill">SIMPAN</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- modal cari item -->
<div class="modal fade" id="CariItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title" id="myModalLabel"><center><b>ITEM</b></center></h4>
                  </div>
                  
                  <div class="modal-body col-sm-12">
                    <p>Klik <span class="badge bg-navy">TAB 1x</span> untuk Sebelum mencari item.</p>
                  <div class="input-group">                  
    <input type="text" name="cari_item" id="txtItem" onkeyup="cari_item()" class="form-control"> 
    <span class="input-group-addon"><i class="fa fa-search"></i></span>
    </div>

    <table class="table table-bordered table-striped">
    <thead>
     <tr>
  <th>Item</th>
  <th>Harga</th>
  <th>Kategori</th>
  <th>Stok</th>
  <th>Aksi</th>
   </tr>
 </thead>
     <tbody id="hasil_item">
     </tbody>
   </table>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-fill" data-dismiss="modal">Kembali</button> 
                    
                  </div>
             
                </div>
              </div>
            </div> <!-- batas modal --> 

<!-- modal cari item -->
<div class="modal fade" id="InputEcommerce" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title" id="myModalLabel"><center><b>ITEM</b></center></h4>
                  </div>
                  
                  <div class="modal-body col-sm-12">
                    <p>Klik <span class="badge bg-navy">TAB 1x</span> untuk Sebelum mencari item.</p>
                  <div class="input-group">                  
    <input type="text" name="cari_item_member" id="txtItemMember" onkeyup="cari_item_member()" class="form-control"> 
    <span class="input-group-addon"><i class="fa fa-search"></i></span>
    </div>

    <table class="table table-bordered table-striped">
    <thead>
     <tr>
  <th>Item</th>
  <th>Harga</th>
  <th>Kategori</th>
  <th>Stok</th>
  <th>Aksi</th>
   </tr>
 </thead>
     <tbody id="hasil_item_member">
     </tbody>
   </table>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-fill" data-dismiss="modal">Kembali</button> 
                    
                  </div>
             
                </div>
              </div>
            </div> <!-- batas modal --> 

<!-- Modal non tunai -->
<div class="modal fade" id="non_tunai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document" style="top: 30%;">
                <div class="modal-content">
                  <div class="modal-header">
                   
                    <h4 class="modal-title" id="myModalLabel">Pembayaran Non Tunai</h4>
                  </div>
          <form action="{{url('transaksi_non_tunai')}}" method="post" enctype="multipart/form-data">
          @csrf

          <input type="hidden" name="id_meja" value="{{$id_meja}}">
                    <input type="hidden" name="total" value="{{$jlakhir}}">
                    <input type="hidden" name="total_transaksi" value="{{$total_transaksi}}">
                  <div class="modal-body col-sm-12">
                    <div class="form-group">
                  <label>Nama Pembeli</label>
                  <input type="text" name="pembeli" class="form-control" required>
                </div>

                  <div class="form-group">
                  <label>Metode Non Tunai</label>
                  <select name="id_non_tunai" class="form-control" required>
                  <option value="0">--Pilih Metode--</option>
                  @foreach($non_tunai as $nt)
                  <option value="{{$nt->id}}">{{$nt->nama}}</option>
                  @endforeach
                  </select>
                </div>
                  </div>
                  <div class="modal-footer">
                    
                <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
                <button type="submit" name="save" class="btn btn-success btn-fill">SELESAIKAN</button>
                  </div>
                </form>
               </div>
              </div>
            </div> <!-- batas modal --> 

            <!-- Modal pending -->
<div class="modal fade" id="pending" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document" style="top: 30%;">
                <div class="modal-content">
                  <div class="modal-header">
                   
                    <h4 class="modal-title" id="myModalLabel">Pembayaran Pending</h4>
                  </div>
          <form action="{{url('transaksi_pending')}}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id_meja" value="{{$id_meja}}">
                    <input type="hidden" name="total" value="{{$jlakhir}}">
                    <input type="hidden" name="total_transaksi" value="{{$total_transaksi}}">
                  <div class="modal-body col-sm-12">
                    <div class="form-group">
                  <label>Nama Pembeli</label>
                  <input type="text" name="pembeli" class="form-control" required>
                </div>
                  </div>
                  <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
                <button type="submit" name="save" class="btn btn-success btn-fill">SELESAIKAN</button>
                  </div>
                </form>
               </div>
              </div>
            </div> <!-- batas modal -->
            


 @foreach($transaksi as $dp)
  <div class="modal fade" id="myModal_pilih{{$dp->id_item}}slot{{$dp->slot}}ket{{$dp->ket}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document" style="top: 30%;">
                <div class="modal-content">
                  <div class="modal-header">
                   
                    <h4 class="modal-title" id="myModalLabel">Item {{$dp->nama}}</h4>
                  </div>
          <form action="{{url('transaksi/ubah')}}" method="post" enctype="multipart/form-data">
          @csrf
                  <div class="modal-body col-sm-12">
                  <div class="form-group">
                  <label>JUMLAH</label>
                  <input type="number" name="jumlah" class="form-control" value="{{$dp->jumlah}}" required>
                  
                </div>
                <div class="form-group">
                  <label>DISKON</label>
                  <div class="input-group">
                  <input type="number" name="persen" value="0" class="form-control"> 
                  <span class="input-group-addon">%</span>
                </div> 
                 <input type="hidden" name="id_item" value="{{$dp->id_item}}"> 
                 <input type="hidden" name="id_meja" value="{{$id_meja}}">
                <input type="hidden" name="nama" value="{{$dp->nama}}"> 
                 <input type="hidden" name="harga" value="{{$dp->harga}}"> 
                 <input type="hidden" name="id_kategori" value="{{$dp->id_kategori}}"> 
                 <input type="hidden" name="ket" value="{{$dp->ket}}"> 
                </div>
                  </div>
                  <div class="modal-footer">
                <button type="submit" name="save" class="btn btn-success btn-fill">SIMPAN</button>
                <a href="{{url('transaksi/hapus/'.$dp->id_transaksi)}}/{{$dp->ket}}" class="btn btn-default btn-fill">&times;HAPUS ITEM</a>
                  </div>
                </form>
             
                </div>
              </div>
            </div> <!-- batas modal -->
 @endforeach
@endsection

@section('script')
<script>
function formatThousands(n, dp) {
  var s = ''+(Math.floor(n)), d = n % 1, i = s.length, r = '';
  while ( (i -= 3) > 0 ) { r = '.' + s.substr(i, 3) + r; }
  return s.substr(0, i + 3) + r + (d ? ',' + Math.round(d * Math.pow(10,dp||2)) : '');
}
function convertToRupiah1(objek) {
      separator = ".";
      a = objek.value;
      b = a.replace(/[^\d]/g,"");
      c = "";
      d = "<?php echo $jlakhir; ?>";
      var result = document.getElementById('result');
      e = b - d;
      panjang = b.length;
      j = 0;
      for (i = panjang; i > 0; i--) {
        j = j + 1;
        if (((j % 3) == 1) && (j != 1)) {
          c = b.substr(i-1,1) + separator + c;
        } else {
          c = b.substr(i-1,1) + c;
        }
      }
      objek.value = "Rp. " + c;
 
      result.value = "Rp. " + formatThousands(e);
    } 
    function no_hp(objek){
      a = objek.value;
      var member = document.getElementById('member');
      member.value = a;

    }
    
    function cari_item(){
    const item = $("#txtItem").val().trim();
    const kode = "{{$id_meja}}";;

    if(item === ""){
        $("#hasil_item").empty();
        return;
    }

    $.ajax({
        type: "POST",
        url: "{{ route('cari_item') }}",
        data: {
            nama: item,
            kode_meja: kode,
            _token: "{{ csrf_token() }}"
        },
        beforeSend: function(){
            $("#hasil_item").html('<tr><td colspan="5" class="text-center">Memuat…</td></tr>');
        },
        success: function(html){
            $("#hasil_item").html(html); // partial <tr>…</tr>
        },
        error: function(){
            $("#hasil_item").html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>');
        }
    });
}

function cari_item_member(){
    const item = $("#txtItemMember").val().trim();
    const kode = "{{$id_meja}}";;

    if(item === ""){
        $("#hasil_item_member").empty();
        return;
    }

    $.ajax({
        type: "POST",
        url: "{{ route('cari_item_member') }}",
        data: {
            nama: item,
            kode_meja: kode,
            _token: "{{ csrf_token() }}"
        },
        beforeSend: function(){
            $("#hasil_item_member").html('<tr><td colspan="5" class="text-center">Memuat…</td></tr>');
        },
        success: function(html){
            $("#hasil_item_member").html(html); // partial <tr>…</tr>
        },
        error: function(){
            $("#hasil_item_member").html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>');
        }
    });
}

// (opsional) debounce supaya tidak banjir request saat mengetik cepat
let t; 
$("#txtItem").on("keyup", function(){
    clearTimeout(t);
    t = setTimeout(cari_item, 300);
});

let r; 
$("#txtItemMember").on("keyup", function(){
    clearTimeout(r);
    r = setTimeout(cari_item_member, 300);
});

</script>
@endsection