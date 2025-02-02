<?php
require_once("../header.php");
require_once("../sidebar.php");

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];
    
    $sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $sentencia->execute([$usuario_id]);
    $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Usuario no encontrado.'
            });
        </script>";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido_pat = $_POST['apellido_pat'];
    $apellido_mat = $_POST['apellido_mat'];
    $rut = $_POST['rut'];
    $dv = $_POST['dv'];
    $tipo_usuario_id = $_POST['tipo_usuario_id'];

    $sentencia = $conexion->prepare("
        UPDATE usuarios 
        SET nombre = ?, apellido_pat = ?, apellido_mat = ?, rut = ?, dv = ?, tipo_usuario_id = ?
        WHERE id = ?
    ");
    if ($sentencia->execute([$nombre, $apellido_pat, $apellido_mat, $rut, $dv, $tipo_usuario_id, $usuario_id])) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Usuario actualizado',
                text: 'Los datos del usuario han sido actualizados con éxito.'
            }).then(() => {
                window.location.href = 'listado_usuarios.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo actualizar el usuario.'
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
          <h1>Editar Usuario</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Editar Usuario</li>
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
        <form method="POST" action="editar_usuario.php">
        <div class="card-body">
          <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
          <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= $usuario['nombre'] ?>" required>
          </div>
          <div class="form-group">
            <label>Apellido Paterno</label>
            <input type="text" name="apellido_pat" class="form-control" value="<?= $usuario['apellido_pat'] ?>" required>
          </div>
          <div class="form-group">
            <label>Apellido Materno</label>
            <input type="text" name="apellido_mat" class="form-control" value="<?= $usuario['apellido_mat'] ?>" required>
          </div>
          <div class="form-group">
            <label>RUT</label>
            <input type="number" name="rut" class="form-control" value="<?= $usuario['rut'] ?>" required>
          </div>
          <div class="form-group">
            <label>DV</label>
            <input type="text" name="dv" class="form-control" value="<?= $usuario['dv'] ?>" required>
          </div>
          <div class="form-group">
            <label>Tipo de Usuario</label>
            <select name="tipo_usuario_id" class="form-control">
              <?php
              $tipos = $conexion->query("SELECT * FROM tipo_usuario")->fetchAll(PDO::FETCH_ASSOC);
              foreach ($tipos as $tipo) {
                  $selected = $tipo['id'] == $usuario['tipo_usuario_id'] ? 'selected' : '';
                  echo "<option value='{$tipo['id']}' $selected>{$tipo['descripcion']}</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
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