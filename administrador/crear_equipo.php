<?php
require_once("../header.php");
require_once("../sidebar.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $escudo = $_FILES['escudo'];
    $fecha = new DateTime();
    $nombreArchivoEscudo = $fecha->getTimestamp() . "_" . $escudo['name'];
    $tmp_escudo = $escudo['tmp_name'];

    // Validar subida del archivo
    if ($tmp_escudo != '') {
        $rutaDestino = "/opt/lampp/htdocs/TablaPosiciones/escudos/" . $nombreArchivoEscudo;
        if (!move_uploaded_file($tmp_escudo, $rutaDestino)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al subir el archivo.'
                });
            </script>";
            exit();
        }
    }

    try {
        // Insertar datos en la tabla
        $sentencia = $conexion->prepare("INSERT INTO equipos (nombre, escudo) VALUES (:nombre, :escudo)");
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":escudo", $nombreArchivoEscudo);
        $sentencia->execute();

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Equipo creado con éxito.'
            }).then(function() {
                window.location = 'crear_equipo.php';
            });
        </script>";
    } catch (PDOException $e) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al crear el equipo: " . $e->getMessage() . "'
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
          <h1>Crear Equipo</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Crear Equipo</li>
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
        <h3 class="card-title">Nuevo Equipo</h3>
      </div>
      <div class="card-body">
        <form action="crear_equipo.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="nombre">Nombre del Equipo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="form-group">
            <label for="escudo">Escudo del Equipo</label>
            <input type="file" class="form-control" id="escudo" name="escudo" required>
          </div>
          <button type="submit" class="btn btn-primary">Crear Equipo</button>
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