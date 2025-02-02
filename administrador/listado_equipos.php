<?php require_once("../header.php"); ?>
<?php require_once("../sidebar.php"); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Listado de Equipos</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Listado de Equipos</li>
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
                    <th>Escudo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM equipos";
                $stmt = $conexion->prepare($query);
                $stmt->execute();
                $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($equipos as $equipo): ?>
                    <tr>
                        <td><?= $equipo['id'] ?></td>
                        <td><?= $equipo['nombre'] ?></td>
                        <td>
                            <!-- Mostrar el escudo desde la carpeta "escudos" -->
                            <img src="../escudos/<?= $equipo['escudo'] ?>" alt="Escudo de <?= $equipo['nombre'] ?>" width="50">
                        </td>
                        <td>
                            <a href="editar_equipo.php?id=<?= $equipo['id'] ?>" class="btn btn-warning">Editar</a>
                            <button class="btn btn-danger" onclick="confirmarEliminacion(<?= $equipo['id'] ?>)">Eliminar</button>
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
                window.location.href = 'eliminar_equipo.php?id=' + id;
            }
        });
    }
</script>
