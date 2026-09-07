@extends('layouts.index')
@section('title', '- Transaksi')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ITEM TRANSAKSI
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li class="active">Item Transaksi</li>
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
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#semua" aria-controls="semua" role="tab" data-toggle="tab">
                                <i class="fa fa-list"></i> Semua
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#makanan" aria-controls="makanan" role="tab" data-toggle="tab">
                                <i class="fa fa-cutlery"></i> Makanan
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#minuman" aria-controls="minuman" role="tab" data-toggle="tab">
                                <i class="fa fa-coffee"></i> Minuman
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#lainnya" aria-controls="lainnya" role="tab" data-toggle="tab">
                                <i class="fa fa-shopping-bag"></i> Lainnya
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#favorite" aria-controls="favorite" role="tab" data-toggle="tab">
                                <i class="fa fa-star"></i> Rekomendasi
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" style="margin-top: 10px;">
                        <!-- Tab Semua -->
                        <div role="tabpanel" class="tab-pane active" id="semua">
                          <div class="row">
                            @foreach($item as $i => $p)
                          <div class="col-md-4" style="padding-left: 8px; padding-right: 8px;">
                                <div class="box box-widget" style="box-shadow: 0 4px 12px rgba(0,0,0,0.3); border-radius: 8px; background: rgba(66, 139, 202, 0.1); transition: all 0.3s ease;">
                                    <div class="box-body text-center">
                                        <img src="{{ asset('assets/img/defaultitem.svg') }}" alt="{{ $p->nama_menu }}" class="img-responsive" style="max-height: 150px; margin: 0 auto 10px;">
                                        <h4><b>{{ $p->nama_menu }}</b></h4>
                                        <p><b>Rp. {{ number_format($p->harga) }}</b></p>
                                        @if($p->stok == 0)
                                        <div style="height: 32px; font-size: 13px; line-height: 32px; color: #ff3b3b; font-weight: 600;">Item Close</div>
                                        @else
                                      <a href="#" data-toggle="modal" data-target="#tambah{{$p->id_menu}}" class="btn btn-primary btn-block" style="height: 32px; font-size: 13px; padding: 6px 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: #fff;">Tambah</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            </div>
                        </div>

                        <!-- Tab Makanan -->
                        <div role="tabpanel" class="tab-pane" id="makanan">
                          <div class="row">
                            @foreach($item->where('produksi', 'makanan') as $i => $p)
                          <div class="col-md-4" style="padding-left: 8px; padding-right: 8px;">
                                <div class="box box-widget" style="box-shadow: 0 4px 12px rgba(0,0,0,0.3); border-radius: 8px; background: rgba(66, 139, 202, 0.1); transition: all 0.3s ease;">
                                    <div class="box-body text-center">
                                        <img src="{{ asset('assets/img/defaultitem.svg') }}" alt="{{ $p->nama_menu }}" class="img-responsive" style="max-height: 150px; margin: 0 auto 10px;">
                                        <h4><b>{{ $p->nama_menu }}</b></h4>
                                        <p><b>Rp. {{ number_format($p->harga) }}</b></p>
                                        @if($p->stok == 0)
                                        <div style="height: 32px; font-size: 13px; line-height: 32px; color: #ff3b3b; font-weight: 600;">Item Close</div>
                                        @else
                                       <a href="#" data-toggle="modal" data-target="#tambah{{$p->id_menu}}" class="btn btn-primary btn-block" style="height: 32px; font-size: 13px; padding: 6px 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: #fff;">Tambah</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            </div>
                        </div>

                        <!-- Tab Minuman -->
                        <div role="tabpanel" class="tab-pane" id="minuman">
                          <div class="row">
                            @foreach($item->where('produksi', 'minuman') as $i => $p)
                          <div class="col-md-4" style="padding-left: 8px; padding-right: 8px;">
                                <div class="box box-widget" style="box-shadow: 0 4px 12px rgba(0,0,0,0.3); border-radius: 8px; background: rgba(66, 139, 202, 0.1); transition: all 0.3s ease;">
                                    <div class="box-body text-center">
                                        <img src="{{ asset('assets/img/defaultitem.svg') }}" alt="{{ $p->nama_menu }}" class="img-responsive" style="max-height: 150px; margin: 0 auto 10px;">
                                        <h4><b>{{ $p->nama_menu }}</b></h4>
                                        <p><b>Rp. {{ number_format($p->harga) }}</b></p>
                                        @if($p->stok == 0)
                                        <div style="height: 32px; font-size: 13px; line-height: 32px; color: #ff3b3b; font-weight: 600;">Item Close</div>
                                        @else
                                        <a href="#" data-toggle="modal" data-target="#tambah{{$p->id_menu}}" class="btn btn-primary btn-block" style="height: 32px; font-size: 13px; padding: 6px 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: #fff;">Tambah</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            </div>
                        </div>

                        <!-- Tab Lainnya -->
                        <div role="tabpanel" class="tab-pane" id="lainnya">
                          <div class="row">
                            @foreach($item->where('produksi', 'lainnya') as $i => $p)
                          <div class="col-md-4" style="padding-left: 8px; padding-right: 8px;">
                                <div class="box box-widget" style="box-shadow: 0 4px 12px rgba(0,0,0,0.3); border-radius: 8px; background: rgba(66, 139, 202, 0.1); transition: all 0.3s ease;">
                                    <div class="box-body text-center">
                                        <img src="{{ asset('assets/img/defaultitem.svg') }}" alt="{{ $p->nama_menu }}" class="img-responsive" style="max-height: 150px; margin: 0 auto 10px;">
                                        <h4><b>{{ $p->nama_menu }}</b></h4>
                                        <p><b>Rp. {{ number_format($p->harga) }}</b></p>
                                        @if($p->stok == 0)
                                        <div style="height: 32px; font-size: 13px; line-height: 32px; color: #ff3b3b; font-weight: 600;">Item Close</div>
                                        @else
                                        <a href="#" data-toggle="modal" data-target="#tambah{{$p->id_menu}}" class="btn btn-primary btn-block" style="height: 32px; font-size: 13px; padding: 6px 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: #fff;">Tambah</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            </div>
                        </div>
                        <!-- tab recomendasi dari fav = 1 -->
                        <div role="tabpanel" class="tab-pane" id="favorite">
                          <div class="row">
                            @foreach($item->where('fav', 1) as $i => $p)
                          <div class="col-md-4" style="padding-left: 8px; padding-right: 8px;">
                                <div class="box box-widget" style="box-shadow: 0 4px 12px rgba(0,0,0,0.3); border-radius: 8px; background: rgba(66, 139, 202, 0.1); transition: all 0.3s ease;">
                                    <div class="box-body text-center">
                                        <img src="{{ asset('assets/img/defaultitem.svg') }}" alt="{{ $p->nama_menu }}" class="img-responsive" style="max-height: 150px; margin: 0 auto 10px;">
                                        <h4><b>{{ $p->nama_menu }}</b></h4>
                                        <p><b>Rp. {{ number_format($p->harga) }}</b></p>
                                        @if($p->stok == 1)
                                        <div style="height: 32px; font-size: 13px; line-height: 32px; color: #ff3b3b; font-weight: 600;">Item Close</div>
                                        @else
                                        <a href="#" data-toggle="modal" data-target="#tambah{{$p->id_menu}}" class="btn btn-primary btn-block" style="height: 32px; font-size: 13px; padding: 6px 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: #fff;">Tambah</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            </div>
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

                <!-- item -->
                <div class="col-md-12" style="border-radius: 12px; padding: 16px 18px; box-shadow: 0 3px 10px rgba(0,0,0,0.08); margin-bottom: 12px; background: #fff;">
                <!-- <div class="box box-widget"> -->
                    <!-- /.box-header -->
                    <!-- <div class="box-body"> -->
                    <div class="row">
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                    @php $no = 1; @endphp
                    @foreach($transaksi as $dp)
                    @php
                    $total_item = $dp->jumlah * $dp->harga;
                    @endphp
                    <div style="background: #fff; padding: 5px 18px; border-bottom: 1px solid #ccc;">
                      <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                        <div>
                          <div style="font-weight: 700; font-size: 16px; color: #222; cursor: pointer;" onclick="bukaModalEdit('{{$dp->id_transaksi}}')">{{ $dp->nama }}</div>
                          <div style="margin-top: 6px; font-weight: 700; color: #ff7a00;">Rp {{ number_format($dp->harga) }}/item</div>
                          <div style="margin-top: 12px; color: #888;">Subtotal:</div>
                        </div>
                        <div style="text-align: right; min-width: 120px;">
                          <button type="button" onclick="konfirmasiHapus('{{url('transaksi/hapus/'.$dp->id_transaksi.'/'.$dp->ket)}}')"
                             style="color: #ff3b3b; font-size: 18px; background: none; border: none; cursor: pointer; padding: 0;">
                             <i class="fa fa-trash"></i>
                          </button>
                          <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                            <button type="button" onclick="bukaModalEdit('{{$dp->id_transaksi}}')" style="width: 32px; height: 32px; border-radius: 50%; background: #f2f2f2; color: #999; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; border: none; cursor: pointer;">-</button>
                            <span style="font-weight: 700; color: #333;">{{ $dp->jumlah }}</span>
                            <button type="button" onclick="bukaModalEdit('{{$dp->id_transaksi}}')" style="width: 36px; height: 36px; border-radius: 50%; background: #ff7a00; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; border: none; cursor: pointer;">+</button>
                          </div>
                          <div style="margin-top: 10px; font-weight: 700; color: #333;">Rp {{ number_format($total_item) }}</div>
                        </div>
                      </div>
                    </div>
                    @endforeach
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->
                    <!-- tutup boxheader -->
                </div>
                </div>

                <div class="col-md-12" style="border-radius: 12px; padding: 16px 18px; box-shadow: 0 3px 10px rgba(0,0,0,0.08); margin-bottom: 12px; background: #fff;">
                
                <div>
                    <div>
                    <h5 class="box-title">TOTAL PEMBAYARAN</h5>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <div class="row">
                    <!-- mulai col 12 -->
                    <div class="col-md-12">
                    <form action="{{url('transaksi_simpan_struk1')}}" method="post" enctype="multipart/form-data">
                    @csrf   
                    <input type="hidden" name="id_meja" value="{{$id_meja}}">
                    <input type="hidden" name="total" value="{{$jlakhir}}">
                    <input type="hidden" name="total_transaksi" value="{{$total_transaksi}}">

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                      <div style="font-weight: 700; color: #666;">Nomor Transaksi:</div>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                      <input type="text" name="pembeli" class="form-control" placeholder="Nomor antrian atau nama pembeli" value="-" onkeyup="no_hp(this);" required
                        style="border-radius: 10px; height: 42px;">
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin: 12px 0 6px;">
                      <div style="font-weight: 800; font-size: 18px;">Total:</div>
                      <div style="font-weight: 800; font-size: 22px; color: #ff7a00;">Rp {{ number_format((float)$jlakhir) }}</div>
                    </div>

                    

                    <div class="form-group" style="margin-bottom: 12px;">
                      <label>Uang Pembayaran</label>
                      <input type="text" accesskey="a" autocomplete="off" name="uang_chas" class="form-control" onkeyup="convertToRupiah1(this);" required value="Rp. {{ number_format((float)$total_transaksi, 0, ',', '.') }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                      <label>Kembalian Uang</label>
                      <input type="text" id="result" class="form-control border-input input-lg" value="Rp. 0" disabled>
                    </div>
                    <div style="font-weight: 700; margin: 14px 0 8px;">Metode Pembayaran</div>

                    <div style="display: flex; gap: 10px; margin-bottom: 12px;">
                      <button type="submit" class="btn btn-warning" style="flex: 1; height: 44px; font-weight: 800; border-radius: 10px;">
                        <i class="fa fa-credit-card"></i> TUNAI
                      </button>
                      <a href="#" data-toggle="modal" data-target="#non_tunai" class="btn btn-danger" style="flex: 1; height: 44px; font-weight: 800; border-radius: 10px; padding-top: 10px;">
                        <i class="fa fa-credit-card"></i> NON TUNAI
                      </a>
                    </div>

                    </form>
                    </div>
                    </div>
                    </div>
                    <!-- tutup col 12 -->
                    </div>
                    </div>
                    </div>
                    <!-- tutup boxheader -->
                 

                @php 
                $diskon = DB::table('pajak')->where('id', 2)->first();
                $diskon_p     = $diskon->persen ?? 0;
                $hasil_diskon = $diskon_p * 100;
                $sdiskon      = $diskon->status ?? 0;
                @endphp
                <!-- col 12 diskon -->
                 <hr>
                <div class="col-md-12">
                <div style="background: #fff; border-radius: 12px; padding: 16px 18px; box-shadow: 0 3px 10px rgba(0,0,0,0.08);">
                <div style="font-weight: 800; margin-bottom: 12px;">DISKON</div>
                <form method="post" action="{{url('transaksi_ubah_diskon')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">            
                <div class="col-md-6">
                <div class="form-group">
                <label>Diskon (%)</label>
                <input type="number" name="persen" class="form-control" value="{{ $hasil_diskon }}" required style="border-radius: 10px; height: 40px;">
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" style="border-radius: 10px; height: 40px;" required>
                  <option @php if($sdiskon == 1){echo"selected";} @endphp value="1">Aktif</option>
                  <option @php if($sdiskon == 0){echo"selected";} @endphp value="0">Tidak</option>
                </select>
                </div>
                </div>
                <div class="col-md-12" style="margin-top: 8px;">
                <button type="submit" name="save" class="btn btn-success btn-block" style="height: 44px; font-weight: 800; border-radius: 10px;">SIMPAN DISKON</button> 
                </div>
                </div>
                </form>
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
            
<!-- modal tambah dengan pilihan jumlah -->
@foreach($item as $i => $p)
<div class="modal fade" id="tambah{{$p->id_menu}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 15px; border: none;">
          <div class="modal-header" style="border: none; position: relative;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 20px; top: 15px; font-size: 30px; color: #999;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="padding: 30px;">
            <div class="text-center" style="margin-bottom: 30px;">
              <h3 style="color: #6c757d; font-weight: 600; margin-bottom: 10px;">{{ $p->nama_menu }}</h3>
              <h4 style="color: #6c757d; font-weight: 500;">Rp. {{ number_format($p->harga) }}</h4>
            </div>
            
            <form method="POST" action="{{ url('transaksi_menu') }}" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="id_meja" value="{{ $id_meja }}">
              <input type="hidden" name="id_menu" value="{{ $p->id_menu }}">
              
              <!-- Input Jumlah dengan Tombol + - -->
              <div class="form-group" style="margin-bottom: 30px;">
                <label style="font-weight: 600; margin-bottom: 10px;">Jumlah</label>
                <div style="display: flex; justify-content: center; align-items: center; gap: 0;">
                  <button type="button" class="btn btn-primary" onclick="decrementJumlah{{$p->id_menu}}()" 
                    style="width: 80px; height: 60px; font-size: 30px; border-radius: 8px 0 0 8px; background: #007bff; border: none;">
                    -
                  </button>
                  <input type="number" name="jumlah" id="jumlah_{{$p->id_menu}}" class="form-control text-center" 
                    value="1" min="1" required readonly
                    style="width: 120px; height: 60px; font-size: 24px; border: 1px solid #ddd; border-radius: 0; margin: 0; padding: 0;">
                  <button type="button" class="btn btn-primary" onclick="incrementJumlah{{$p->id_menu}}()" 
                    style="width: 80px; height: 60px; font-size: 30px; border-radius: 0 8px 8px 0; background: #007bff; border: none;">
                    +
                  </button>
                </div>
              </div>

              <!-- Input Catatan -->
              <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; margin-bottom: 10px;">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Masukkan catatan (contoh: tanpa garam, extra pedas, dll)" 
                  style="border-radius: 8px;"></textarea>
              </div>

              <!-- Subtotal -->
              <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-weight: 600; color: #666;">Subtotal:</span>
                  <span style="font-weight: 800; font-size: 18px; color: #ff7a00;">Rp <span id="subtotal_tambah_{{$p->id_menu}}">{{ number_format($p->harga) }}</span></span>
                </div>
              </div>

              <!-- Tombol Pesan -->
              <button type="submit" class="btn btn-block" 
                style="height: 60px; font-size: 20px; font-weight: 600; border-radius: 50px; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                border: none; color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                Simpan Pesanan
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

<script>
function incrementJumlah{{$p->id_menu}}() {
  var input = document.getElementById('jumlah_{{$p->id_menu}}');
  input.value = parseInt(input.value) + 1;
  updateSubtotalTambah{{$p->id_menu}}();
}

function decrementJumlah{{$p->id_menu}}() {
  var input = document.getElementById('jumlah_{{$p->id_menu}}');
  if (parseInt(input.value) > 1) {
    input.value = parseInt(input.value) - 1;
    updateSubtotalTambah{{$p->id_menu}}();
  }
}

function updateSubtotalTambah{{$p->id_menu}}() {
  var jumlah = parseInt(document.getElementById('jumlah_{{$p->id_menu}}').value);
  var harga = {{ $p->harga }};
  var subtotal = jumlah * harga;
  document.getElementById('subtotal_tambah_{{$p->id_menu}}').textContent = subtotal.toLocaleString('id-ID');
}
</script>
@endforeach

<!-- Modal Edit Transaksi -->
@foreach($transaksi as $dp)
<div class="modal fade" id="editTransaksi{{$dp->id_transaksi}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="border: none; position: relative;">
        <h5 class="modal-title">Edit {{ $dp->nama }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 20px; top: 15px; font-size: 30px; color: #999;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding: 30px;">
        <form method="POST" action="{{ url('transaksi_menu/ubah') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id_transaksi" value="{{ $dp->id_transaksi }}">
          
          <!-- Item Info -->
          <div class="text-center" style="margin-bottom: 30px;">
            <h3 style="color: #6c757d; font-weight: 600; margin-bottom: 10px;">{{ $dp->nama }}</h3>
            <h4 style="color: #6c757d; font-weight: 500;">Rp. {{ number_format($dp->harga) }}/item</h4>
          </div>

          <!-- Input Jumlah dengan Tombol +/- -->
          <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; margin-bottom: 10px;">Jumlah</label>
            <div style="display: flex; justify-content: center; align-items: center; gap: 0;">
              <button type="button" class="btn btn-primary" onclick="decrementEdit{{$dp->id_transaksi}}()" 
                style="width: 80px; height: 60px; font-size: 30px; border-radius: 8px 0 0 8px; background: #007bff; border: none;">
                -
              </button>
              <input type="number" name="jumlah" id="jumlah_edit_{{$dp->id_transaksi}}" class="form-control text-center" 
                value="{{ $dp->jumlah }}" min="1" required readonly
                style="width: 120px; height: 60px; font-size: 24px; border: 1px solid #ddd; border-radius: 0; margin: 0; padding: 0;">
              <button type="button" class="btn btn-primary" onclick="incrementEdit{{$dp->id_transaksi}}()" 
                style="width: 80px; height: 60px; font-size: 30px; border-radius: 0 8px 8px 0; background: #007bff; border: none;">
                +
              </button>
            </div>
          </div>

          <!-- Input Catatan -->
          <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; margin-bottom: 10px;">Catatan</label>
            <textarea name="catatan" class="form-control" rows="3" placeholder="Masukkan catatan (contoh: tanpa garam, extra pedas, dll)" 
              style="border-radius: 8px;">{{ $dp->jenis }}</textarea>
          </div>

          <!-- Subtotal -->
          <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-weight: 600; color: #666;">Subtotal:</span>
              <span style="font-weight: 800; font-size: 18px; color: #ff7a00;">Rp <span id="subtotal_{{$dp->id_transaksi}}">{{ number_format($dp->jumlah * $dp->harga) }}</span></span>
            </div>
          </div>

          <!-- Tombol Submit -->
          <button type="submit" class="btn btn-block" 
            style="height: 60px; font-size: 18px; font-weight: 600; border-radius: 50px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            border: none; color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
            Perbarui Pesanan
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function incrementEdit{{$dp->id_transaksi}}() {
  var input = document.getElementById('jumlah_edit_{{$dp->id_transaksi}}');
  input.value = parseInt(input.value) + 1;
  updateSubtotal{{$dp->id_transaksi}}();
}

function decrementEdit{{$dp->id_transaksi}}() {
  var input = document.getElementById('jumlah_edit_{{$dp->id_transaksi}}');
  if (parseInt(input.value) > 1) {
    input.value = parseInt(input.value) - 1;
    updateSubtotal{{$dp->id_transaksi}}();
  }
}

function updateSubtotal{{$dp->id_transaksi}}() {
  var jumlah = parseInt(document.getElementById('jumlah_edit_{{$dp->id_transaksi}}').value);
  var harga = {{ $dp->harga }};
  var subtotal = jumlah * harga;
  document.getElementById('subtotal_{{$dp->id_transaksi}}').textContent = subtotal.toLocaleString('id-ID');
}
</script>
@endforeach


<!-- Modal non tunai -->
<div class="modal fade" id="non_tunai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document" style="top: 30%;">
                <div class="modal-content">
                  <div class="modal-header">
                   
                    <h4 class="modal-title" id="myModalLabel">Pembayaran Non Tunai</h4>
                  </div>
          <form action="{{url('transaksi_non_tunai1')}}" method="post" enctype="multipart/form-data">
          @csrf

          <input type="hidden" name="id_meja" value="{{$id_meja}}">
                    <input type="hidden" name="total" value="{{$jlakhir}}">
                    <input type="hidden" name="total_transaksi" value="{{$total_transaksi}}">
                  <div class="modal-body col-sm-12">
                    <div class="form-group">
                  <label>Nomor Transaksi</label>
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
 
@endsection

@section('script')
<script>
function bukaModalEdit(idTransaksi) {
  $('#editTransaksi' + idTransaksi).modal('show');
}

function konfirmasiHapus(url) {
  Swal.fire({
    title: 'Hapus Item',
    text: 'Apakah Anda yakin ingin menghapus item ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
}

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
  
    @if(Session::has('success'))
 Swal.fire({
        title:"Sukses!",text:"{{Session::get('success')}}",icon:"success",
    })
@endif
@if(Session::has('error'))
 Swal.fire({
        title:"Gagal!",text:"{{Session::get('error')}}",icon:"warning",
    })
@endif

</script>
@endsection