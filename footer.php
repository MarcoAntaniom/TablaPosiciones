<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
  <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 3.2.0
  </div>
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="<?php echo $rutaBase; ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo $rutaBase; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo $rutaBase; ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $rutaBase; ?>dist/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="<?php echo $rutaBase; ?>plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/raphael/raphael.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo $rutaBase; ?>plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo $rutaBase; ?>dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo $rutaBase; ?>dist/js/pages/dashboard2.js"></script>

<!-- TABLES PLUGINS -->
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?php echo $rutaBase; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo $rutaBase; ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

<!-- DataTables JS -->
<script src="<?php echo $rutaBase; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script>
  $(document).ready(function () {
    // Inicializar DataTables solo para la tabla con id "example1"
    $('#example1').DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
    });

    // Script para ajustar el pie de página
    function adjustFooter() {
      var docHeight = $(window).height();
      var footerHeight = $('.main-footer').outerHeight();
      var footerTop = $('.main-footer').position().top + footerHeight;

      if (footerTop < docHeight) {
        $('.main-footer').css('position', 'absolute').css('bottom', 0).css('width', '100%');
      } else {
        $('.main-footer').css('position', 'relative');
      }
    }

    adjustFooter();
    $(window).resize(adjustFooter);
  });
</script>

</body>
</html>
