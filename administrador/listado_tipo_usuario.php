<?php
require_once("../header.php");
require_once("../sidebar.php");

// Consulta para obtener los tipos de usuario
$sentencia = $conexion->prepare("SELECT id, descripcion FROM tipo_usuario");
$sentencia->execute();
$tipos_usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Listado de Tipos de Usuario</h1>
  </section>
  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Tipos de Usuario</h3>
      </div>
      <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Descripción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tipos_usuarios as $tipo) : ?>
              <tr>
                <td><?= $tipo['id'] ?></td>
                <td><?= $tipo['descripcion'] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<script>
  $(function () {
    $(".table").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('.card-header .col-md-6:eq(0)');
  });
</script>

<?php require_once("../footer.php"); ?>