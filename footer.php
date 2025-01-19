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

<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="<?php echo $rutaBase; ?>dist/js/adminlte.js"></script>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo $rutaBase; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/jszip/jszip.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo $rutaBase; ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Script para inicializar DataTables -->
<script>
  $(document).ready(function() {
    // Función para inicializar DataTable con las opciones comunes
    function initializeDataTable(tableId, options) {
      var tableElement = $(`#${tableId}`);
      if (tableElement.length > 0 && tableElement.is('table')) {
        if ($.fn.DataTable.isDataTable(`#${tableId}`)) {
          $(`#${tableId}`).DataTable().destroy();
        }
        $(`#${tableId}`).DataTable({
          responsive: true,
          lengthChange: options.lengthChange !== undefined ? options.lengthChange : false,
          autoWidth: options.autoWidth !== undefined ? options.autoWidth : false,
          buttons: options.buttons || [],
          paging: options.paging !== undefined ? options.paging : true,
          searching: options.searching !== undefined ? options.searching : true,
          ordering: options.ordering !== undefined ? options.ordering : true,
          info: options.info !== undefined ? options.info : true
        }).buttons().container().appendTo(`#${tableId}_wrapper .col-md-6:eq(0)`);
      }
    }

    // Opciones comunes para las tablas
    var commonOptions = {
      buttons: ["copy", "excel", "pdf", "print", "colvis"],
      paging: true,
      searching: true,
      ordering: true,
      info: false
    };

    // Inicializar varias tablas
    var tablesToInitialize = ["marcaciones", "pendientes", "capacitacion", "example1", "example2", "Aceptadas", "calificaciones"];
    tablesToInitialize.forEach(function(tableId) {
      initializeDataTable(tableId, commonOptions);
    });

    // Inicialización para la tabla 'noticias' sin ordenamiento
    var noticiasOptions = {
      buttons: ["copy", "excel", "pdf", "print", "colvis"],
      paging: true,
      searching: true,
      ordering: false,  // Desactiva el ordenamiento
      info: false
    };
    initializeDataTable("noticias", noticiasOptions);
  });
</script>

<script>
  $(document).ready(function () {
    // Función para ajustar la posición del pie de página
    function adjustFooter() {
      var docHeight = $(window).height();
      var footerHeight = $('.main-footer').outerHeight();
      var footerTop = $('.main-footer').position().top + footerHeight;

      if (footerTop < docHeight) {
        $('.main-footer').css({
          position: 'absolute',
          bottom: 0,
          width: '100%',
        });
      } else {
        $('.main-footer').css({
          position: 'relative',
        });
      }
    }

    // Llamar a la función al cargar y redimensionar la ventana
    adjustFooter();
    $(window).resize(adjustFooter);
  });
</script>

</body>
</html>
