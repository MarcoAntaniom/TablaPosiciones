<?php
require_once("../header.php");
require_once("../sidebar.php");

// Conexión a la base de datos
try {
    // Asegúrate de que $conexion esté correctamente configurado
    if (!isset($conexion)) {
        throw new Exception("La conexión a la base de datos no está establecida.");
    }

    // Activar errores de PDO para depuración
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener fechas para el primer select
    $fechas = $conexion->query("SELECT id, numero_jornada FROM fechas")->fetchAll(PDO::FETCH_ASSOC);

    // Variables para los selects
    $partidos = [];
    $equipos = [];
    $jugadores = [];

    // Verificación y procesamiento del formulario
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fecha_id = $_POST['fecha_id'] ?? null;
        $partido_id = $_POST['partido_id'] ?? null;
        $equipo_id = $_POST['equipo_id'] ?? null;
        $jugador_id = $_POST['jugador_id'] ?? null;
        $goles = $_POST['goles'] ?? 0;
        $asistencias = $_POST['asistencias'] ?? 0;
        $tarjetas_amarillas = $_POST['tarjetas_amarillas'] ?? 0;
        $tarjetas_rojas = $_POST['tarjetas_rojas'] ?? 0;
        $pases_interceptados = $_POST['pases_interceptados'] ?? 0;

        // Consultar partidos según la fecha seleccionada
        if ($fecha_id) {
            $query = $conexion->prepare("
                SELECT p.id, el.nombre AS equipo_local, ev.nombre AS equipo_visitante 
                FROM partidos p
                JOIN equipos el ON p.equipo_local = el.id
                JOIN equipos ev ON p.equipo_visitante = ev.id
                WHERE p.id_fecha = :fecha_id
            ");
            $query->bindParam(':fecha_id', $fecha_id, PDO::PARAM_INT);
            $query->execute();
            $partidos = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        // Consultar equipos según el partido seleccionado
        if ($partido_id) {
            $query = $conexion->prepare("
                SELECT equipo_local, equipo_visitante 
                FROM partidos 
                WHERE id = :partido_id
            ");
            $query->bindParam(':partido_id', $partido_id, PDO::PARAM_INT);
            $query->execute();
            $partido = $query->fetch(PDO::FETCH_ASSOC);

            if ($partido) {
                $equipos = $conexion->query("
                    SELECT id, nombre 
                    FROM equipos 
                    WHERE id IN ({$partido['equipo_local']}, {$partido['equipo_visitante']})
                ")->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        // Consultar jugadores según el equipo seleccionado
        if ($equipo_id) {
            $query = $conexion->prepare("
                SELECT id, nombre 
                FROM jugadores 
                WHERE equipo_id = :equipo_id
            ");
            $query->bindParam(':equipo_id', $equipo_id, PDO::PARAM_INT);
            $query->execute();
            $jugadores = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        // Inserción de estadísticas y manejo de advertencias
        if ($jugador_id && $equipo_id && $partido_id) {
            try {
                // Query de inserción
                $query = $conexion->prepare("
                    INSERT INTO estadisticas (jugador_id, equipo_id, goles, asistencias, 
                                              tarjetas_amarillas, tarjetas_rojas, pases_interceptados, partidos_jugados) 
                    VALUES (:jugador_id, :equipo_id, :goles, :asistencias, :tarjetas_amarillas, 
                            :tarjetas_rojas, :pases_interceptados, 1)
                ");
                $query->bindParam(':jugador_id', $jugador_id, PDO::PARAM_INT);
                $query->bindParam(':equipo_id', $equipo_id, PDO::PARAM_INT);
                $query->bindParam(':goles', $goles, PDO::PARAM_INT);
                $query->bindParam(':asistencias', $asistencias, PDO::PARAM_INT);
                $query->bindParam(':tarjetas_amarillas', $tarjetas_amarillas, PDO::PARAM_INT);
                $query->bindParam(':tarjetas_rojas', $tarjetas_rojas, PDO::PARAM_INT);
                $query->bindParam(':pases_interceptados', $pases_interceptados, PDO::PARAM_INT);

                // Ejecutar la consulta
                $query->execute();

                // Mensaje de éxito
                $mensaje = "Estadísticas actualizadas correctamente";

                // Manejo de advertencias
                $query = $conexion->prepare("
                    SELECT SUM(tarjetas_amarillas) AS total_tarjetas_amarillas, SUM(tarjetas_rojas) AS total_tarjetas_rojas 
                    FROM estadisticas 
                    WHERE jugador_id = :jugador_id
                ");
                $query->bindParam(':jugador_id', $jugador_id, PDO::PARAM_INT);
                $query->execute();
                $estadistica = $query->fetch(PDO::FETCH_ASSOC);

                // Verificar advertencias
                $query = $conexion->prepare("SELECT advertencia_amarillas FROM jugadores WHERE id = :jugador_id");
                $query->bindParam(':jugador_id', $jugador_id, PDO::PARAM_INT);
                $query->execute();
                $advertencia = $query->fetch(PDO::FETCH_ASSOC)['advertencia_amarillas'];

                if ($estadistica['total_tarjetas_amarillas'] >= 4 && !$advertencia) {
                    $mensaje .= " - Advertencia: El jugador ha acumulado 4 tarjetas amarillas y se perderá 1 partido.";
                    $update = $conexion->prepare("UPDATE jugadores SET advertencia_amarillas = 1 WHERE id = :jugador_id");
                    $update->bindParam(':jugador_id', $jugador_id, PDO::PARAM_INT);
                    $update->execute();
                }
                if ($estadistica['total_tarjetas_rojas'] >= 1) {
                    $mensaje .= " - Advertencia: El jugador ha recibido una tarjeta roja y se perderá 4 partidos.";
                }

                // Mostrar alerta de éxito
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: '$mensaje'
                    }).then(function() {
                        window.location.href = 'actualizar_estadisticas.php';
                    });
                </script>";

            } catch (PDOException $e) {
                // Mostrar errores de inserción
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al insertar datos: " . $e->getMessage() . "'
                    });
                </script>";
            }
        }
    }
} catch (Exception $e) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error: " . $e->getMessage() . "'
        });
    </script>";
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

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Título</h3>

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
        <form method="POST">
                    <div class="card-body">
                        <?php if (isset($message)) : ?>
                            <div class="alert alert-info"><?php echo $message; ?></div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="fecha_id">Fecha</label>
                            <select name="fecha_id" class="form-control" onchange="this.form.submit()">
                                <option value="">Seleccionar Fecha</option>
                                <?php foreach ($fechas as $fecha): ?>
                                    <option value="<?php echo $fecha['id']; ?>" <?php echo isset($fecha_id) && $fecha_id == $fecha['id'] ? 'selected' : ''; ?>>
                                        Jornada <?php echo $fecha['numero_jornada']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if (!empty($partidos)): ?>
                        <div class="form-group">
                            <label for="partido_id">Partido</label>
                            <select name="partido_id" class="form-control" onchange="this.form.submit()">
                                <option value="">Seleccionar Partido</option>
                                <?php foreach ($partidos as $partido): ?>
                                    <option value="<?php echo $partido['id']; ?>" <?php echo isset($partido_id) && $partido_id == $partido['id'] ? 'selected' : ''; ?>>
                                        <?php echo $partido['equipo_local']; ?> vs <?php echo $partido['equipo_visitante']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($equipos)): ?>
                        <div class="form-group">
                            <label for="equipo_id">Equipo</label>
                            <select name="equipo_id" class="form-control" onchange="this.form.submit()">
                                <option value="">Seleccionar Equipo</option>
                                <?php foreach ($equipos as $equipo): ?>
                                    <option value="<?php echo $equipo['id']; ?>" <?php echo isset($equipo_id) && $equipo_id == $equipo['id'] ? 'selected' : ''; ?>>
                                        <?php echo $equipo['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($jugadores)): ?>
                        <div class="form-group">
                            <label for="jugador_id">Jugador</label>
                            <select name="jugador_id" class="form-control">
                                <option value="">Seleccionar Jugador</option>
                                <?php foreach ($jugadores as $jugador): ?>
                                    <option value="<?php echo $jugador['id']; ?>">
                                        <?php echo $jugador['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($jugadores)): ?>
                        <div class="form-group">
                            <label for="goles">Goles</label>
                            <input type="number" name="goles" class="form-control" value="0">
                        </div>
                        <div class="form-group">
                            <label for="asistencias">Asistencias</label>
                            <input type="number" name="asistencias" class="form-control" value="0">
                        </div>
                        <div class="form-group">
                            <label for="tarjetas_amarillas">Tarjetas Amarillas</label>
                            <input type="number" name="tarjetas_amarillas" class="form-control" value="0">
                        </div>
                        <div class="form-group">
                            <label for="tarjetas_rojas">Tarjetas Rojas</label>
                            <input type="number" name="tarjetas_rojas" class="form-control" value="0">
                        </div>
                        <div class="form-group">
                            <label for="pases_interceptados">Pases Interceptados</label>
                            <input type="number" name="pases_interceptados" class="form-control" value="0">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Estadísticas</button>
                        <?php endif; ?>
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