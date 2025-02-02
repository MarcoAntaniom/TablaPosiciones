<?php
require_once("../header.php");
require_once("../sidebar.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $descripcion = $_POST['descripcion'];

    try {
        // Insertar en la tabla tipo_usuario
        $query = "INSERT INTO tipo_usuario (descripcion) VALUES (:descripcion)";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Tipo de usuario agregado exitosamente.'
                });
              </script>";
    } catch (PDOException $e) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al agregar el tipo de usuario: " . $e->getMessage() . "'
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
          <h1>Crear Tipo de Usuario</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Crear Tipo de Usuario</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Formulario de Registro de Tipo de Usuario</h3>
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Ingrese el tipo de usuario (ej. Administrador, Editor)" required>
          </div>
          <button type="submit" class="btn btn-primary">Crear Tipo de Usuario</button>
        </form>
      </div>
      <div class="card-footer">
        <!-- Footer opcional -->
      </div>
    </div>
  </section>
</div>

<?php require_once("../footer.php"); ?>
