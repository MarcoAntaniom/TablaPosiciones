<?php 
require_once("../header.php"); 
require_once("../sidebar.php"); 

// Conexión a la base de datos
require_once("../conexion.php");

// Obtener todas las fechas disponibles
$queryFechas = "SELECT id, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha_formateada FROM fechas ORDER BY fecha ASC";
$fechas = $conexion->query($queryFechas)->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Actualizar Goles</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Actualizar Goles</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Formulario para actualizar goles -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Actualizar Goles</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="procesar_actualizacion.php">
          <div class="card-body">
            <!-- Seleccionar Fecha -->
            <div class="form-group">
              <label for="fecha">Selecciona una Fecha</label>
              <select id="fecha" name="fecha" class="form-control">
                <option value="">-- Selecciona una Fecha --</option>
                <?php foreach ($fechas as $fecha): ?>
                  <option value="<?= $fecha['id']; ?>"><?= $fecha['fecha_formateada']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Seleccionar Partido -->
            <div class="form-group">
              <label for="partido">Selecciona un Partido</label>
              <select id="partido" name="partido_id" class="form-control" disabled>
                <option value="">-- Selecciona un Partido --</option>
              </select>
            </div>

            <!-- Goles Local -->
            <div class="form-group">
              <label for="goles_local">Goles Local</label>
              <input type="number" id="goles_local" name="goles_local" class="form-control" placeholder="Ingresa los goles del equipo local" required>
            </div>

            <!-- Goles Visitante -->
            <div class="form-group">
              <label for="goles_visitante">Goles Visitante</label>
              <input type="number" id="goles_visitante" name="goles_visitante" class="form-control" placeholder="Ingresa los goles del equipo visitante" required>
            </div>
          </div>
          <button type="submit" class="btn btn-info">Actualizar</button>
        </form>
      </div>
    </div>
    <!-- /.card -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<?php require_once("../footer.php"); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Cargar partidos dinámicamente al seleccionar una fecha
  $('#fecha').change(function () {
    const fechaId = $(this).val();
    const $partidoSelect = $('#partido');

    if (fechaId) {
      $.ajax({
        url: 'obtener_partidos.php',
        method: 'GET',
        data: { fecha_id: fechaId },
        success: function (response) {
          const partidos = JSON.parse(response);
          $partidoSelect.empty().append('<option value="">-- Selecciona un Partido --</option>');

          partidos.forEach(partido => {
            $partidoSelect.append(`<option value="${partido.id}">
              ${partido.equipo_local} vs ${partido.equipo_visitante}
            </option>`);
          });

          $partidoSelect.prop('disabled', false);
        },
        error: function () {
          alert('Error al cargar los partidos.');
        }
      });
    } else {
      $partidoSelect.empty().append('<option value="">-- Selecciona un Partido --</option>').prop('disabled', true);
    }
  });
</script>
