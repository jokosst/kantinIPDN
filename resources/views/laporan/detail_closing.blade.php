@extends('layouts.index')
@section('title', '- Closing Detail')
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
                          <td>Kode Closing</td>
                          <td class="right">{{ $closing->kode_closing }}</td>
                      </tr>
                      <tr>
                          <td>Waktu Tutup</td>
                          <td class="right">{{date('d-m-Y', strtotime($closing->tanggal))}} {{date('H:i:s', strtotime($closing->waktu))}}</td>
                      </tr>
                      <tr>
                          <td>Kasir</td>
                          <td class="right">{{ $closing->nama_closing }}</td>
                      </tr>
                  </table>

                  <div class="line"></div>

                  <!-- Item modal -->
                  <table class="table-struk">
                    <tr>
                        <th>Modal Awal</th>
                        <th class="right">{{ number_format($closing->modal,0,',','.') }}</th>
                    </tr>
                  </table>
                  <div class="line"></div>

                  <!-- Total -->
                  <table class="table-struk">
                      <tr>
                          <td>Tunai</td>
                          <td class="right">{{ number_format($closing->transaksi,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td>Diskon</td>
                          <td class="right">-{{ number_format($closing->diskon,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td>Pajak</td>
                          <td class="right">{{ number_format($closing->pajak,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td>Total</td>
                          <td class="right">{{ number_format($closing->net_total,0,',','.') }}</td>
                      </tr>
                    </table>

                    <div class="line"></div>
                    <table class="table-struk">
                      <tr>
                          <th>Pengeluaran</th>
                          <th class="right"><b>-{{ number_format($closing->pengeluaran,0,',','.') }}</b></th>
                      </tr>
                  </table>

                  <div class="line"></div>
                  <!-- Item Total uang -->
                  <table class="table-struk">
                    <tr>
                        <th>Total<br>(Tunai+Modal-Pengeluaran)</th>
                        <th class="right">{{ number_format($closing->net_total + $closing->modal - $closing->pengeluaran,0,',','.') }}</th>
                        </tr>
                        <tr>
                        <th>Uang Tutup</th>
                        <th class="right">{{ number_format($closing->uang_inputan,0,',','.') }}</th>
                        </tr>
                        <tr>
                        <th>Selisih</th>
                        <th class="right">{{ number_format($closing->uang_inputan - ($closing->net_total + $closing->modal - $closing->pengeluaran),0,',','.') }}</th>
                    </tr>
                  </table>
                  <div class="line"></div>
                  <br>
                  <table class="table-struk">
                      <tr>
                          <td>Non Tunai</td>
                          <td class="right">{{ number_format($closing->transaksi_debet,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td>Diskon</td>
                          <td class="right">-{{ number_format($closing->diskon_debet,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td>Pajak</td>
                          <td class="right">{{ number_format($closing->pajak_debet,0,',','.') }}</td>
                      </tr>
                      <tr>
                          <td>Total</td>
                          <td class="right">{{ number_format($closing->total_debet,0,',','.') }}</td>
                      </tr>
                  </table>
                  <div class="line"></div>
                  <!-- Rincian non tunai -->
                  <table class="table-struk">
                    <tr>
                        <th>Rincian Non Tunai</th>
                    </tr>
                  </table>
                  <div class="line"></div>
                  <!-- jumlah debet transaksi berdasarkan master debet jika 0 id di transaksi  0 pisahkan dibagian lainnnya -->
                  <table class="table-struk">
                        @foreach($debet as $d)
                      <tr>
                          <td>{{ $d->nama }}</td>
                            <td class="right">
                                {{
                                    number_format(
                                        $debet_transaksi->where('metode_pembayaran', $d->id)->sum('total'),
                                        0,
                                        ',',
                                        '.'
                                    )
                                }}</td>
                      </tr>
                        @endforeach
                        <tr>
                          <td>Lainnya</td>
                            <td class="right">
                                {{
                                    number_format(
                                        $debet_transaksi->where('metode_pembayaran', 0)->sum('total'),
                                        0,
                                        ',',
                                        '.'
                                    )
                                }}</td>
                        </tr>
                  </table>
                  <div class="line"></div>
                  <br>
                  <div class="line"></div>
                   <table class="table-struk">
                    <tr>
                        <th>Total Tunai + Nontunai</th>
                        <th class="right">{{ number_format($closing->net_total + $closing->total_debet,0,',','.') }}</th>
                    </tr>
                  </table>
                  <div class="line"></div>

                  <!-- Footer -->
                  <div class="center">
                      <p>--- Terima Kasih ---</p>
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

// window.print();
</script>
@endsection