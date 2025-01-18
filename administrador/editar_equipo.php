<?php
require_once("../header.php");
require_once("../sidebar.php");

// Verificar si se ha pasado un id por GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar el equipo a editar
    $query = "SELECT * FROM equipos WHERE id = :id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipo) {
        // Si no se encuentra el equipo, redirigir o mostrar un error
        echo "Equipo no encontrado.";
        exit;
    }
} else {
    echo "ID no especificado.";
    exit;
}

// Procesar el formulario de edición
// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $escudo = $_FILES['escudo']['name'];

    // Si se subió una nueva imagen de escudo, moverla a la carpeta "escudos"
    if ($escudo) {
        $escudo_temp = $_FILES['escudo']['tmp_name'];
        $escudo_path = "../escudos/" . $escudo;
        move_uploaded_file($escudo_temp, $escudo_path);
    } else {
        // Si no se subió un nuevo escudo, mantener el escudo actual
        $escudo = $equipo['escudo'];
    }

    // Actualizar la base de datos con los nuevos datos
    $query = "UPDATE equipos SET nombre = :nombre, escudo = :escudo WHERE id = :id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':escudo', $escudo);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Redirigir con mensaje de éxito usando SweetAlert2
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'El equipo se ha actualizado correctamente.',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php'; // Redirigir al listado de equipos
            }
        });
    </script>";
    exit;
}

?>

<?php require_once("../header.php"); ?>
<?php require_once("../sidebar.php"); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Editar Equipo</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Editar Equipo</li>
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
        <h3 class="card-title">Formulario de Edición</h3>
      </div>
      <div class="card-body">
        <!-- Formulario de edición -->
        <form action="editar_equipo.php?id=<?= $equipo['id'] ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del Equipo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $equipo['nombre'] ?>" required>
            </div>
            <div class="form-group">
                <label for="escudo">Escudo del Equipo</label><br>
                <img src="../escudos/<?= $equipo['escudo'] ?>" alt="Escudo actual" width="100"><br><br>
                <input type="file" class="form-control" id="escudo" name="escudo">
                <small>Deja este campo vacío si no deseas cambiar el escudo.</small>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
      </div>
      <!-- /.card-body -->
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
