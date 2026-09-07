<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    <ul class="sidebar-menu">
        <li class="header">MENU</li>
<li>
<a href="{{url('/')}}">
            <i><img src="{{ asset('assets/img/icon/dashboard.png')}}"></i> <span> BERANDA</span>
          </a>
    </li>

<!-- user level kasir -->
 @if(Auth::user()->level == 'kasir')
 <li>
      <a href="{{url('transaksi')}}">
            <i><img src="{{ asset('assets/img/icon/printer.png')}}"></i> <span> TRANSAKSI</span>
          </a>
    </li>
    <li>
          <a href="{{url('liststruk')}}" accesskey="w">
            <i><img src="{{ asset('assets/img/icon/hdd.png')}}"></i> <span> STRUK</span>
          </a>
        </li>  
         <li>
          <a href="{{url('laporan')}}" accesskey="e">
            <i><img src="{{ asset('assets/img/icon/calculator.png')}}"></i> <span> LAPORAN</span>
          </a>
        </li> 
        
  @elseif(Auth::user()->level == 'admin')
<!-- user level admin -->
  
          
        <li class="treeview">
          <a href="#">
            <i><img src="{{ asset('assets/img/icon/medical-history.png')}}"></i> <span> MASTER</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <!-- jika pengaturan mode kasir diaktifkan, maka menu master item akan disembunyikan digantikan dengan menu item lain-->
             <li><a href="{{url('master/item')}}"><i class="fa fa-bullseye"></i> Item</a></li>
            <li><a href="{{url('master/kategori')}}"><i class="fa fa-bullseye"></i> Kategori</a></li>
            <li><a href="{{url('master/satuan')}}"><i class="fa fa-bullseye"></i> Satuan</a></li>
            <li><a href="{{url('master/slot')}}"><i class="fa fa-bullseye"></i> Slot</a></li>
            <li><a href="{{url('master/supplier')}}"><i class="fa fa-bullseye"></i> Suplier</a></li>
            <li><a href="{{url('master/non_tunai')}}"><i class="fa fa-bullseye"></i> Non Tunai</a></li>
            <li><a href="{{url('master/user')}}"><i class="fa fa-bullseye"></i> User</a></li>
            <li><a href="{{url('master/generate_kode')}}"><i class="fa fa-bullseye"></i> Generate Kode Retur</a></li>
          </ul>
        </li>
       <li>
        <li class="treeview">
          <a href="#">
            <i><img src="{{ asset('assets/img/icon/printer.png')}}"></i> <span> PENJUALAN</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{url('transaksi')}}"><i class="fa fa-bullseye"></i> Kasir</a></li>
            <li><a href="{{url('liststruk')}}"><i class="fa fa-bullseye"></i> Penjualan Masuk</a></li>
            <li><a href="{{url('retur')}}"><i class="fa fa-bullseye"></i> Retur</a></li>
            <li><a href="{{url('laporan')}}"><i class="fa fa-bullseye"></i> Closing</a></li>
          </ul>
        </li>
       <li>
        <li class="treeview">
          <a href="#">
            <i><img src="{{ asset('assets/img/icon/shopping-cart.png')}}"></i> <span> PEMBELIAN</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{url('pembelian/masuk')}}"><i class="fa fa-bullseye"></i> Pembelian Masuk</a></li>
            <li><a href="{{url('pembelian/retur')}}"><i class="fa fa-bullseye"></i> Retur</a></li>
          </ul>
        </li>
       <li>
        <li class="treeview">
          <a href="#">
            <i><img src="{{ asset('assets/img/icon/3d.png')}}"></i> <span> STOK</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{url('stok/penambahan')}}"><i class="fa fa-bullseye"></i> Penambahan Stok</a></li>
            <li><a href="{{url('stok/opname')}}"><i class="fa fa-bullseye"></i> Stok Opname</a></li>
          </ul>
        </li>
       <li>
        <li>
          <a href="{{url('grafik/penjualan')}}">
            <i><img src="{{ asset('assets/img/icon/line-chart.png')}}"></i> <span> GRAFIK</span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i><img src="{{ asset('assets/img/icon/calculator.png')}}"></i> <span> LAPORAN</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{url('laporan/penjualan')}}"><i class="fa fa-bullseye"></i> Penjualan</a></li>
            <li><a href="{{url('laporan/pembelian')}}"><i class="fa fa-bullseye"></i> Pembelian</a></li>
            <li><a href="{{url('laporan/closing')}}"><i class="fa fa-bullseye"></i> Closing</a></li>
            <li><a href="{{url('laporan/arus_stok')}}"><i class="fa fa-bullseye"></i> Arus Stok</a></li>
             <li><a href="{{url('laporan/stok')}}"><i class="fa fa-bullseye"></i> Penambahan Stok</a></li>
            <li><a href="{{url('laporan/pendapatan')}}"><i class="fa fa-bullseye"></i> Pendapatan</a></li>
          </ul>
        </li>
       <li>
        <li>
          <a href="{{url('pengaturan')}}">
            <i><img src="{{ asset('assets/img/icon/ui.png')}}"></i> <span> PENGATURAN</span>
          </a>
        </li>
       
       @endif
       <li>
          <a href="{{url('logout')}}" accesskey="r">
            <i><img src="{{ asset('assets/img/icon/down-arrow.png')}}"></i> <span> KELUAR</span>
          </a>
        </li> 
        
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
    