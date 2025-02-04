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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="<?php echo $rutaBase; ?>dist/js/adminlte.js"></script>

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
        // Destruir la tabla solo si ya está inicializada
        if ($.fn.DataTable.isDataTable(`#${tableId}`)) {
          $(`#${tableId}`).DataTable().clear().destroy();
        }
        // Inicializar la tabla
        $(`#${tableId}`).DataTable({
          responsive: true,
          lengthChange: options.lengthChange !== undefined ? options.lengthChange : false,
          autoWidth: options.autoWidth !== undefined ? options.autoWidth : false,
          buttons: options.buttons || [],
          paging: options.paging !== undefined ? options.paging : true,
          searching: options.searching !== undefined ? options.searching : true,
          ordering: options.ordering !== undefined ? options.ordering : true,
          info: options.info !== undefined ? options.info : true,
          order: options.order || [], // Orden inicial
          columnDefs: options.columnDefs || [] // Definiciones de columnas
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
      ordering: false, // Desactiva el ordenamiento
      info: false
    };
    initializeDataTable("noticias", noticiasOptions);

    // Inicializar la tabla de posiciones
    var posicionesOptions = {
      buttons: ['pdf'], // Solo PDF
      paging: false, // No paginar
      searching: false, // Sin barra de búsqueda
      ordering: true, // Habilitar el ordenamiento
      info: false, // Sin información de entradas
      order: [
        [2, 'desc'], // Ordenar primero por puntos (columna índice 2) de mayor a menor
        [1, 'asc']   // Si no hay puntos, ordenar por nombre de equipo (columna índice 1) de forma alfabética
      ],
      columnDefs: [
        { targets: 0, orderable: false } // Desactiva el ordenamiento para la columna de posiciones (#)
      ]
    };
    initializeDataTable("tablaPosiciones", posicionesOptions);
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

<script>
  $(document).ready(function() {
    // Personalización del PDF para la tabla de posiciones
    $('#tablaPosiciones').DataTable({
      dom: 'Bfrtip',
      buttons: [{
        extend: 'pdfHtml5',
        text: 'Generar PDF',
        orientation: 'landscape',
        pageSize: 'A4',
        customize: function (doc) {
          doc.content[1].table.widths = ['10%', '40%', '40%', '10%']; // Ajustar el ancho de las columnas
          
          // Iterar sobre las filas de la tabla
          doc.content[1].table.body.forEach(function(row, index) {
            // Si es la cabecera, personalizar el estilo
            if (index === 0) {
              row.forEach(function(cell) {
                cell.alignment = 'center';
                cell.fillColor = '#f2f2f2';
              });
            } else {
              // Para las filas de datos
              var escudoUrl = row[1].escudo; // Obtener la URL del escudo
              if (escudoUrl) {
                // Asegurarse de que sea una URL válida
                row[1].image = escudoUrl; // Asignar la imagen al campo
                row[1].width = 20;  // Ajustar el tamaño de la imagen (escudo)
                row[1].height = 20; // Ajustar la altura de la imagen (escudo)
              }
              // Alineación de las celdas
              row[0].alignment = 'center';  // Alineación de la posición
              row[1].alignment = 'center';  // Alineación del escudo y nombre del equipo
              row[2].alignment = 'center';  // Alineación de los puntos
              row[3].alignment = 'center';  // Alineación de los goles
            }
          });
        }
      }]
    });
  });
</script>

</body>
</html>
