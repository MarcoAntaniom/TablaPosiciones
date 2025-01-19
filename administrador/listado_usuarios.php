<?php
require_once("../header.php");
require_once("../sidebar.php");

$sentencia = $conexion->prepare("
    SELECT 
        u.id AS usuario_id, 
        u.nombre, 
        u.apellido_pat, 
        u.apellido_mat, 
        u.rut, 
        u.dv, 
        u.clave, 
        tu.descripcion AS tipo_usuario_descripcion
    FROM usuarios u
    INNER JOIN tipo_usuario tu ON u.tipo_usuario_id = tu.id
");


if ($sentencia->execute()) {
    $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} else {
    $error = $sentencia->errorInfo();
    echo "Error en la consulta: " . $error[2];  // Mostrar mensaje de error si la consulta falla
    exit();
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Listado de Usuarios</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Listado de Usuarios</li>
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
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Rut</th>
                    <th>DV</th>
                    <th>Clave</th>
                    <th>Tipo de Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['usuario_id'] ?></td>
                        <td><?= $usuario['nombre'] ?></td>
                        <td><?= $usuario['apellido_pat'] ?></td>
                        <td><?= $usuario['apellido_mat'] ?></td>
                        <td><?= $usuario['rut'] ?></td>
                        <td><?= $usuario['dv'] ?></td>
                        <td><?= $usuario['clave'] ?></td>
                        <td><?= $usuario['tipo_usuario_descripcion'] ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?= $usuario['usuario_id'] ?>" class="btn btn-warning">Editar</a>
                            <button class="btn btn-danger" onclick="confirmarEliminacion(<?= $usuario['usuario_id'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

<script>
    // Función para mostrar la alerta de confirmación antes de eliminar
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Este cambio no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, redirigir a la página de eliminación
                window.location.href = 'eliminar_usuario.php?id=' + id;
            }
        });
    }
</script>
