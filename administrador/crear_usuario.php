<?php
require_once("../header.php");
require_once("../sidebar.php");

// Obtener tipos de usuario desde la base de datos
try {
    $queryTiposUsuario = "SELECT id, descripcion FROM tipo_usuario";
    $stmtTiposUsuario = $conexion->prepare($queryTiposUsuario);
    $stmtTiposUsuario->execute();
    $tiposUsuario = $stmtTiposUsuario->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se obtuvieron registros
    if (empty($tiposUsuario)) {
        echo "<script>
                Swal.fire({
                    icon: 'info',
                    title: 'Sin registros',
                    text: 'No se encontraron tipos de usuario en la base de datos.'
                });
              </script>";
    }
} catch (PDOException $e) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al obtener los tipos de usuario: " . $e->getMessage() . "'
            });
          </script>";
}

// Procesar el formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido_pat = $_POST['apellido_pat'];
    $apellido_mat = $_POST['apellido_mat'];
    $rut = $_POST['rut'];
    $dv = $_POST['dv'];
    $clave = $_POST['clave'];
    $tipo_usuario_id = $_POST['tipo_usuario_id'];

    try {
        $query = "INSERT INTO usuarios (nombre, apellido_pat, apellido_mat, rut, dv, clave, tipo_usuario_id) 
                  VALUES (:nombre, :apellido_pat, :apellido_mat, :rut, :dv, :clave, :tipo_usuario_id)";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido_pat', $apellido_pat);
        $stmt->bindParam(':apellido_mat', $apellido_mat);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':dv', $dv);
        $stmt->bindParam(':clave', $clave);
        $stmt->bindParam(':tipo_usuario_id', $tipo_usuario_id);
        $stmt->execute();
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Usuario agregado exitosamente.'
                });
              </script>";
    } catch (PDOException $e) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al agregar el usuario: " . $e->getMessage() . "'
                });
              </script>";
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Crear Usuario</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Crear Usuario</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Formulario de Registro de Usuario</h3>
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese el nombre" required>
          </div>
          <div class="form-group">
            <label for="apellido_pat">Apellido Paterno</label>
            <input type="text" id="apellido_pat" name="apellido_pat" class="form-control" placeholder="Ingrese el apellido paterno" required>
          </div>
          <div class="form-group">
            <label for="apellido_mat">Apellido Materno</label>
            <input type="text" id="apellido_mat" name="apellido_mat" class="form-control" placeholder="Ingrese el apellido materno" required>
          </div>
          <div class="form-group">
            <label for="rut">RUT</label>
            <input type="text" id="rut" name="rut" class="form-control" placeholder="Ingrese el RUT sin dígito verificador" required>
          </div>
          <div class="form-group">
            <label for="dv">Dígito Verificador</label>
            <input type="text" id="dv" name="dv" class="form-control" placeholder="Ingrese el dígito verificador" required>
          </div>
          <div class="form-group">
            <label for="clave">Contraseña</label>
            <input type="password" id="clave" name="clave" class="form-control" placeholder="Ingrese la contraseña" required>
          </div>
          <div class="form-group">
            <label for="tipo_usuario_id">Tipo de Usuario</label>
            <select id="tipo_usuario_id" name="tipo_usuario_id" class="form-control" required>
              <option value="">Seleccione un tipo de usuario</option>
              <?php foreach ($tiposUsuario as $tipo): ?>
                <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['descripcion']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
      </div>
      <div class="card-footer">
        <!-- Pie de tarjeta -->
      </div>
    </div>
  </section>
</div>

<?php require_once("../footer.php"); ?>