<?php
require_once("../header.php");
require_once("../sidebar.php");

// Consultar equipos
$query = "SELECT id, nombre FROM equipos";
$equipos = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
if (!$equipos) {
    die("No se encontraron equipos en la base de datos.");
}

// Consultar jugadores
$stmt_jugadores = $conexion->query("SELECT id, nombre, posicion, edad, equipo_id, imagen FROM jugadores");
$jugadores = $stmt_jugadores->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Listado de Jugadores</h1>
        </div>
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filtrar Jugadores</h3>
            </div>
            <div class="card-body">
                <!-- Filtro por equipo -->
                <label for="equipo_id">Seleccionar equipo:</label>
                <select id="equipo_id" name="equipo_id" onchange="filterPlayers()">
                    <option value="">Seleccione un equipo</option>
                    <?php foreach ($equipos as $equipo): ?>
                        <option value="<?= htmlspecialchars($equipo['id']) ?>">
                            <?= htmlspecialchars($equipo['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Contenedor de la tabla de jugadores -->
            <div id="jugadores-lista">
                <table class="table table-bordered table-striped" style="display: none;" id="tabla-jugadores">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Posición</th>
                            <th>Edad</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jugadores as $jugador): ?>
                            <tr class="jugador" data-equipo-id="<?= htmlspecialchars($jugador['equipo_id']) ?>" style="display: none;">
                                <td><?= htmlspecialchars($jugador['nombre']) ?></td>
                                <td><?= htmlspecialchars($jugador['posicion']) ?></td>
                                <td><?= htmlspecialchars($jugador['edad']) ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($jugador['imagen']) ?>" alt="<?= htmlspecialchars($jugador['nombre']) ?>" style="width: 70px; height: 70px; object-fit: cover; border-radius: 30%;">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
    function filterPlayers() {
        const equipoId = document.getElementById('equipo_id').value.trim();
        const jugadores = document.querySelectorAll('.jugador');
        const tablaJugadores = document.getElementById('tabla-jugadores');
        let hayJugadoresVisibles = false;

        jugadores.forEach(jugador => {
            const jugadorEquipoId = jugador.getAttribute('data-equipo-id');
            if (equipoId && jugadorEquipoId === equipoId) {
                jugador.style.display = '';
                hayJugadoresVisibles = true;
            } else {
                jugador.style.display = 'none';
            }
        });

        // Mostrar u ocultar la tabla según corresponda
        if (hayJugadoresVisibles) {
            tablaJugadores.style.display = 'table';
        } else {
            tablaJugadores.style.display = 'none';
        }
    }
</script>

<?php require_once("../footer.php"); ?>