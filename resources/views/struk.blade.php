@extends('layouts.index')
@section('title', '- Transaksi')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6">
<!-- STRUK AREA -->
          <div id="print-area" class="struk-box">
              <div class="struk">
                  <!-- Header Toko -->
                  <div class="toko text-center">
                      <h2>{{ $nama_usaha->nama }}</h2>
                      <div>{{ $nama_usaha->alamat }}</div>
                      <div>Telp: {{ $nama_usaha->kontak }}</div>
                  </div>

                  <div class="line"></div>

                  <!-- Info Struk -->
                  <table class="table-struk">
                      <tr>
                          <td>Kode</td>
                          <td class="right">{{ $data->kode_struk }}</td>
                      </tr>
                      <tr>
                          <td>Tanggal</td>
                          <td class="right">{{ \Carbon\Carbon::parse($data->tgl)->format('d-m-Y') }}</td>
                      </tr>
                      <tr>
                          <td>Waktu</td>
                          <td class="right">{{ \Carbon\Carbon::parse($data->waktu)->format('H:i:s') }}</td>
                      </tr>
                      <tr>
                          <td>Kasir</td>
                          <td class="right">{{ $data->nama_kasir ?? 'Admin' }}</td>
                      </tr>
                      <tr>
                          <td>Metode</td>
                          <td class="right">{{ $metode_pembayaran ?? '-' }}</td>
                      </tr>
                  </table>

                  <div class="line"></div>

                  <!-- Item Transaksi -->
                  <table class="table-struk">
                    <tr>
                        <th>Item</th>
                        <th class="right">Total</th>
                    </tr>
                      @foreach ($transaksi as $t)
                      <tr>
                          <td colspan="2">{{ $t->nama_pesanan }}</td>
                      </tr>
                      <tr>
                        @if($t->status_note > 0)
                            <td>{{ $t->jumlah }} x {{ number_format($t->note,0,',','.') }} [Disc {{ $t->status_note }}%]</td>
                        @else
                          <td>{{ $t->jumlah }} x {{ number_format($t->harga,0,',','.') }}</td>
                        @endif
                          <td class="right">{{ number_format($t->jumlah * $t->harga,0,',','.') }}</td>
                          
                      </tr>
                    <!-- jika catatan tidak kosong tampilkan -->
                           @if(!empty($t->jenis))
                            <tr>
                             <td colspan="2" style="padding-bottom: 15px;">Catatan: {{ $t->jenis }} </td>
                            </tr>
                        @endif
                      @endforeach
                  </table>

                  <div class="line"></div>

                  <!-- Total -->
                  <table class="table-struk">
                      <tr>
                          <td>Subtotal</td>
                          <td class="right">{{ number_format($data->sub_total,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td>Diskon</td>
                          <td class="right">-{{ number_format($data->diskon,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td>Pajak</td>
                          <td class="right">{{ number_format($data->pajak,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td><b>Total</b></td>
                          <td class="right"><b>{{ number_format($data->total,0,',','.') }}</b></td>
                      </tr>
                      <tr>
                          <td>Bayar</td>
                          <td class="right">{{ number_format($data->uang_chas,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td>Kembali</td>
                          <td class="right">{{ number_format($data->kembalian,0,',','.') }}</td>
                      </tr>
                  </table>

                  <div class="line"></div>

                  <!-- Footer -->
                  <div class="center">
                      <p>--- Terima Kasih ---<br>
                      {{ $nama_usaha->ucapan ?? 'Berbelanja di toko kami!' }}</p>
                  </div>
              </div>
          </div>
          <!-- /STRUK AREA -->

          <button class="btn btn-primary" style="margin-top:10px" onclick="printStruk()"><i class="fa fa-print"></i> Cetak Struk (CTRL+P)</button>
        </div>
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
    <!-- /.content-wrapper -->
@endsection

@section('script')
<style>
    /* Styling struk */
    .struk-box {
        font-family: monospace;
        font-size: 13px;
        width: 280px;
        background: #fff;
        padding: 10px;
        border: 1px solid #ccc;
    }
    .toko h2 { margin: 0; font-size: 16px; }
    .line { border-top: 1px dashed #000; margin: 5px 0; }
    .table-struk { width: 100%; border-collapse: collapse; }
    .table-struk td { padding: 2px 0; font-size: 13px; }
    .right { text-align: right; }
    .center { text-align: center; }

    /* Saat print hanya struk saja */
    @media print {
        body * {
            visibility: hidden;
        }
        #print-area, #print-area * {
            visibility: visible;
        }
        #print-area {
          width: 70mm; /* lebar kertas thermal */
    font-size: 12px;
    line-height: 1.2em;
    font-family: monospace; /* agar rapih */
    margin: 0 auto;           /* ini yang bikin ke tengah */
    padding: 3px;
    box-sizing: border-box;
    text-align: left;
    box-sizing: border-box;
    border: none !important;   /* hapus border */
    box-shadow: none !important; /* hapus shadow */
    background: #fff;          /* biar putih bersih */
        }
         #print-area .text-center {
    text-align: center;
  }
    }
</style>

<script>
function printStruk() {
    window.print();
}

window.print();
</script>
@endsection