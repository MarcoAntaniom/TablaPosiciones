<?php
require_once("../header.php");
require_once("../sidebar.php");

try {
    $query = "SELECT id, numero_jornada, fecha FROM fechas";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener las fechas: " . $e->getMessage());
}

// Obtener los partidos si se seleccionó una fecha
$partidos = [];
$selectedFecha = $_POST['fecha_id'] ?? ''; // Mantener la fecha seleccionada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $selectedFecha) {
    $fechaId = intval($selectedFecha);
    try {
        $query = "SELECT p.id, p.goles_local, p.goles_visitante, e1.nombre AS equipo_local, e2.nombre AS equipo_visitante 
                  FROM partidos p
                  JOIN equipos e1 ON p.equipo_local = e1.id
                  JOIN equipos e2 ON p.equipo_visitante = e2.id
                  WHERE p.id_fecha = :fecha_id";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':fecha_id', $fechaId, PDO::PARAM_INT);
        $stmt->execute();
        $partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al obtener los partidos: " . $e->getMessage());
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Actualizar Goles</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Actualizar Goles</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Seleccionar Fecha</h3>
            </div>
            <div class="card-body">
                <!-- Formulario para seleccionar fecha -->
                <form method="POST">
                    <div class="form-group">
                        <label for="fecha_id">Fecha:</label>
                        <select class="form-control" name="fecha_id" id="fecha_id">
                            <option value="">-- Seleccione una fecha --</option>
                            <?php foreach ($fechas as $fecha): ?>
                                <option value="<?= htmlspecialchars($fecha['id']) ?>" <?= $selectedFecha == $fecha['id'] ? 'selected' : '' ?>>
                                    Jornada <?= htmlspecialchars($fecha['numero_jornada']) ?> - <?= htmlspecialchars($fecha['fecha']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
            </div>
        </div>

        <?php if (!empty($partidos)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Partidos</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Equipo Local</th>
                                <th>Goles Local</th>
                                <th>Equipo Visitante</th>
                                <th>Goles Visitante</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($partidos as $partido): ?>
                                <tr>
                                    <td><?= htmlspecialchars($partido['equipo_local']) ?></td>
                                    <td><?= htmlspecialchars($partido['goles_local']) ?></td>
                                    <td><?= htmlspecialchars($partido['equipo_visitante']) ?></td>
                                    <td><?= htmlspecialchars($partido['goles_visitante']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm" onclick="editarGoles(
                                            '<?= htmlspecialchars($partido['id']) ?>',
                                            '<?= htmlspecialchars($partido['goles_local']) ?>',
                                            '<?= htmlspecialchars($partido['goles_visitante']) ?>',
                                            '<?= htmlspecialchars($partido['equipo_local']) ?>',
                                            '<?= htmlspecialchars($partido['equipo_visitante']) ?>'
                                        )">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-circle"></i> No se encontraron partidos para la fecha seleccionada.
            </div>
        <?php endif; ?>
    </section>
</div>

<!-- Footer -->
<?php require_once("../footer.php"); ?>

<script>
    function editarGoles(partidoId, golesLocal, golesVisitante, equipoLocal, equipoVisitante) {
        Swal.fire({
            title: 'Editar Goles',
            html: `
                <div>
                    <h5>${equipoLocal}</h5>
                    <input type="number" id="goles_local" class="swal2-input" value="${golesLocal}" placeholder="Goles Local">
                    <h5>${equipoVisitante}</h5>
                    <input type="number" id="goles_visitante" class="swal2-input" value="${golesVisitante}" placeholder="Goles Visitante">
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            preConfirm: () => {
                const golesLocalInput = document.getElementById('goles_local').value;
                const golesVisitanteInput = document.getElementById('goles_visitante').value;

                if (!golesLocalInput || !golesVisitanteInput) {
                    Swal.showValidationMessage('Por favor, ingresa los goles.');
                }

                return {
                    goles_local: golesLocalInput,
                    goles_visitante: golesVisitanteInput
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('partido_id', partidoId);
                formData.append('goles_local', result.value.goles_local);
                formData.append('goles_visitante', result.value.goles_visitante);

                fetch('procesar_actualizacion.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    Swal.fire('¡Actualizado!', 'Los goles se han actualizado correctamente.', 'success')
                        .then(() => location.reload());
                })
                .catch(error => {
                    Swal.fire('Error', 'Hubo un problema al actualizar los goles.', 'error');
                });
            }
        });
    }
</script>
