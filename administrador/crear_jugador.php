<?php 
require_once("../header.php"); 
require_once("../sidebar.php"); 

// Consulta para obtener los equipos
$query = "SELECT id, nombre FROM equipos";
$equipos = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $posicion = $_POST['posicion'];
    $edad = $_POST['edad'];
    $equipo_id = $_POST['equipo_id'];

    // Subir la imagen
    $imagen = $_FILES['imagen'];
    $imagen_nombre = $imagen['name'];
    $imagen_tmp = $imagen['tmp_name'];
    $imagen_error = $imagen['error'];
    
    if ($imagen_error === 0) {
        // Verificar la extensión de la imagen
        $extension = pathinfo($imagen_nombre, PATHINFO_EXTENSION);
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array(strtolower($extension), $extensiones_permitidas)) {
            echo "<script>Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Solo se permiten imágenes con extensiones JPG, JPEG, PNG o GIF.',
                confirmButtonText: 'Aceptar'
            }).then(() => { window.history.back(); });</script>";
        } else {
            // Obtener el nombre del equipo desde la base de datos
            $query_equipo = "SELECT nombre FROM equipos WHERE id = :equipo_id";
            $stmt_equipo = $conexion->prepare($query_equipo);
            $stmt_equipo->bindParam(':equipo_id', $equipo_id);
            $stmt_equipo->execute();
            $equipo = $stmt_equipo->fetch(PDO::FETCH_ASSOC);

            // Crear una carpeta para el equipo si no existe
            $carpeta_equipo = '../imagenes_jugadores/' . strtolower(str_replace(' ', '_', $equipo['nombre']));
            if (!file_exists($carpeta_equipo)) {
                mkdir($carpeta_equipo, 0777, true);
            }

            // Define la ruta de destino para la imagen dentro de la carpeta del equipo
            $imagen_destino = $carpeta_equipo . '/' . basename($imagen_nombre);
            
            // Verifica si el archivo fue cargado correctamente
            if (is_uploaded_file($imagen_tmp)) {
                if (move_uploaded_file($imagen_tmp, $imagen_destino)) {
                    // Insertar los datos en la base de datos
                    try {
                        $sql = "INSERT INTO jugadores (nombre, posicion, edad, equipo_id, imagen) 
                                VALUES (:nombre, :posicion, :edad, :equipo_id, :imagen)";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bindParam(':nombre', $nombre);
                        $stmt->bindParam(':posicion', $posicion);
                        $stmt->bindParam(':edad', $edad);
                        $stmt->bindParam(':equipo_id', $equipo_id);
                        $stmt->bindParam(':imagen', $imagen_destino);

                        if ($stmt->execute()) {
                            echo "<script>Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Jugador creado exitosamente.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => { window.location.href = 'crear_jugador.php'; });</script>";
                        } else {
                            echo "<script>Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear el jugador.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => { window.history.back(); });</script>";
                        }
                    } catch (PDOException $e) {
                        echo "<script>Swal.fire({
                            icon: 'error',
                            title: 'Error en la base de datos',
                            text: 'Error al insertar en la base de datos: " . addslashes($e->getMessage()) . "',
                            confirmButtonText: 'Aceptar'
                        }).then(() => { window.history.back(); });</script>";
                    }
                } else {
                    echo "<script>Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al mover la imagen al destino.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => { window.history.back(); });</script>";
                }
            } else {
                echo "<script>Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El archivo no fue cargado correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => { window.history.back(); });</script>";
            }
        }
    } else {
        echo "<script>Swal.fire({
            icon: 'error',
            title: 'Error al subir la imagen',
            text: 'Código de error: " . $imagen_error . "',
            confirmButtonText: 'Aceptar'
        }).then(() => { window.history.back(); });</script>";
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
          <h1>Página en Blanco</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Página en Blanco</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Título</h3>
      </div>
      <div class="card-body">
        <!-- Formulario -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>
            <div class="form-group">
                <label for="posicion">Posición:</label>
                <input type="text" class="form-control" name="posicion" id="posicion" required>
            </div>
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" class="form-control" name="edad" id="edad" required>
            </div>
            <div class="form-group">
                <label for="equipo_id">Equipo:</label>
                <select name="equipo_id" id="equipo_id" class="form-control" required>
                    <option value="">Seleccionar Equipo</option>
                    <?php
                    foreach ($equipos as $equipo): ?>
                        <option value="<?= htmlspecialchars($equipo['id']) ?>">
                            <?= htmlspecialchars($equipo['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*" required>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Crear Jugador</button>
            </div>
        </form>
      </div>
      <div class="card-footer">
        Footer
      </div>
    </div>
  </section>
</div>

<?php require_once("../footer.php"); ?>