<?php
require_once("../header.php");
require_once("../sidebar.php");

// Obtener los datos para llenar los select
$equipos = $conexion->query("SELECT id, nombre FROM equipos")->fetchAll(PDO::FETCH_ASSOC);
$fechas = $conexion->query("SELECT id, numero_jornada, fecha FROM fechas")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $id_fecha = $_POST['id_fecha'];
    $numero_jornada = $_POST['numero_jornada']; 
    $equipo_local = $_POST['equipo_local'];
    $equipo_visitante = $_POST['equipo_visitante'];

    try {
        $query = "INSERT INTO partidos (id_fecha, numero_jornada, equipo_local, equipo_visitante, goles_local, goles_visitante) 
                  VALUES (:id_fecha, :numero_jornada, :equipo_local, :equipo_visitante, :goles_local, :goles_visitante)";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':id_fecha', $id_fecha);
        $stmt->bindParam(':numero_jornada', $numero_jornada); 
        $stmt->bindParam(':equipo_local', $equipo_local);
        $stmt->bindParam(':equipo_visitante', $equipo_visitante);
        $stmt->bindValue(':goles_local', 0);
        $stmt->bindValue(':goles_visitante', 0);
        $stmt->execute();
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Partido agregado exitosamente.'
                });
              </script>";
    } catch (PDOException $e) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al agregar el partido: " . $e->getMessage() . "'
                });
              </script>";
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Crear Partido</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Crear Partido</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Título</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <!-- Aquí iría el contenido que deseas mostrar -->
        <form method="POST">
          <div class="form-group">
            <label for="id_fecha">Fecha:</label>
            <select class="form-control" name="id_fecha" id="id_fecha" required>
                <option value="">Seleccionar Fecha</option>
                <?php 
                $jornada = 1;
                foreach ($fechas as $fecha): ?>
                    <option value="<?php echo $fecha['id']; ?>"><?php echo "Jornada " . $jornada . ": " . $fecha['fecha']; ?></option>
                <?php 
                $jornada++;
                endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="numero_jornada">Número de Jornada:</label>
            <input type="text" class="form-control" name="numero_jornada" id="numero_jornada" required>
          </div>
          <div class="form-group">
            <label for="equipo_local">Equipo Local:</label>
            <select class="form-control" name="equipo_local" id="equipo_local" required>
                <option value="">Seleccionar Equipo</option>
                <?php foreach ($equipos as $equipo): ?>
                    <option value="<?php echo $equipo['id']; ?>"><?php echo $equipo['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="equipo_visitante">Equipo Visitante:</label>
            <select class="form-control" name="equipo_visitante" id="equipo_visitante" required>
                <option value="">Seleccionar Equipo</option>
                <?php foreach ($equipos as $equipo): ?>
                    <option value="<?php echo $equipo['id']; ?>"><?php echo $equipo['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Crear Partido</button>
        </form>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        Footer
      </div>
      <!-- /.card-footer-->
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