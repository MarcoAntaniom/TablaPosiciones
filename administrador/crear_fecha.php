<?php 
require_once("../header.php");
require_once("../sidebar.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_jornada = $_POST['numero_jornada'];
    $fecha = $_POST['fecha'];

    // Verificar los datos recibidos
    if (empty($numero_jornada) || empty($fecha)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Todos los campos son obligatorios.'
            });
        </script>";
        exit();
    }

    try {
        $query = "INSERT INTO fechas (numero_jornada, fecha) VALUES (:numero_jornada, :fecha)";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':numero_jornada', $numero_jornada, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Fecha agregada exitosamente.'
                }).then(function() {
                    window.location = 'crear_fecha.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al crear la fecha.'
                });
            </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de PDO.'
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
          <h1>Crear Fecha</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Crear Fecha</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Nueva Fecha</h3>
      </div>
      <div class="card-body">
        <form action="crear_fecha.php" method="post">
          <div class="form-group">
            <label for="numero_jornada">Número de Jornada</label>
            <input type="number" class="form-control" id="numero_jornada" name="numero_jornada" required>
          </div>
          <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
          </div>
          <button type="submit" class="btn btn-primary">Crear Fecha</button>
        </form>
      </div>
    </div>
  </section>
</div>
<!-- /.content-wrapper -->

<?php require_once("../footer.php"); ?>