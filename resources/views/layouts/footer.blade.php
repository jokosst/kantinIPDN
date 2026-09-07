
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="https://www.itpontianak.my.id" target="_blank">ZONA IT PONTIANAK</a>.</strong>
  </footer>

  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="{{ asset('assets/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{ asset('assets/plugins/fastclick/fastclick.js')}}"></script>
<!-- DataTables -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/app.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset('assets/plugins/morris/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- SlimScroll 1.3.0 -->
<script src="{{ asset('assets/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- ChartJS 1.0.1 -->
<script src="{{ asset('assets/plugins/chartjs/Chart.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('assets/dist/js/demo.js')}}"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{ asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<!-- InputMask -->
<script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>

<!-- SweetAlert2 (permanent include) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- AdminLTE for demo purposes -->
</body>
</html>
<script type="text/javascript">
  function convertToRupiah(objek) {
      separator = ".";
      a = objek.value;
      b = a.replace(/[^\d]/g,"");
      c = "";
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

    } 
     
  $(function () {
    $("#datatable1").DataTable( {
        stateSave: true
    } );
    $("#datatable3").DataTable( {
        stateSave: true
    } );
    $('#datatable2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
@php
  $modal = \App\Models\Modal::where('closing', 0)->first();
@endphp
@if(!$modal)
<script>
  $(document).ready(function(){
    $("#InputModal").modal('show');
  });
</script>
@endif

  <div class="modal fade" id="InputModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><center><b>PEMBERITAHUAN</b></center></h4>
                  </div>
                  
                  <div class="modal-body col-sm-12">
                  <div class="form-group">                  
                  <p>SEGERA INPUT MODAL TERLEBIH DAHULU DI MENU LAPORAN</p>
                </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-fill" data-dismiss="modal">Tutup</button>
                    
                  </div>
             
                </div>
              </div>
            </div> <!-- batas modal -->