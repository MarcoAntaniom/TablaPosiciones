<?php
require_once("../header.php");
require_once("../sidebar.php");
require_once("../funciones.php");

//Llama al function que tiene a la tabla de posiciones automática
$tabla_posiciones = obtenerPosiciones($conexion);

// Obtener el equipo_id del entrenador desde la sesión
$equipoEntrenador = $_SESSION['equipo_id'] ?? null;
$cantidadJugadores = 0;

if ($equipoEntrenador) {
    // Consultar la cantidad de jugadores del equipo
    $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM jugadores WHERE equipo_id = ?");
    $stmt->execute([$equipoEntrenador]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $cantidadJugadores = $resultado['total'] ?? 0;
}
?>

<style>
    .highlight {
        background-color: #f9c74f !important; /* Fondo amarillo */
        font-weight: bold; /* Texto en negrita */
        color: #fff; /* Texto blanco */
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div><!-- /.col -->
          <div class="col-sm-6">
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <a href="historial_jugadores.php">
            <div class="info-box">
    <span class="info-box-icon bg-orange elevation-1"><img src="../iconos/icono-crear-fecha.png" alt="Crear Fechas" class="icono-sidebar" width="30" height="30"></span>

    <div class="info-box-content">
        <span class="info-box-text">Observar Jugadores</span>
        <span class="info-box-number">
          <small>Cantidad De Jugadores: <?= $cantidadJugadores ?></small>
        </span>
    </div>
    <!-- /.info-box-content -->
</div>

            </a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <a href="crear_partido.php">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><img src="../iconos/icono-crear-partido.png" alt="Crear Partidos" class="icono-sidebar" width="30" height="30"></span>

              <div class="info-box-content">
                <span class="info-box-text">Crear Partido</span>
                <span class="info-box-number">Crea un partido</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <a href="actualizar_goles.php">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><img src="../iconos/icono-actualizar-goles.png" alt="Actualizar Goles" class="icono-sidebar" width="30" height="30"></span>

              <div class="info-box-content">
                <span class="info-box-text">Actualizar Goles</span>
                <span class="info-box-number">Actualiza los goles</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <a href="copa_chile.php">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-dark elevation-1"><img src="../Logo-Copa-Chile.svg" alt="Copa Chile" width="50" height="60"></span>

              <div class="info-box-content">
                <span class="info-box-text">Copa Chile</span>
                <span class="info-box-number">Accede a la Copa</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <div class="col-md-8">

            <!-- TABLA DE POSICIONES -->
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Tabla de Posiciones</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <!-- CONTENIDO DE LA TABLA DE POSICIONES -->
                  <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Equipo</th>
            <th>PJ</th>
            <th>V</th>
            <th>E</th>
            <th>D</th>
            <th>GF</th>
            <th>GC</th>
            <th>DF</th>
            <th>PTS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tabla_posiciones as $fila): ?>
            <tr class="<?= $fila['equipo_id'] == $_SESSION['equipo_id'] ? 'highlight' : '' ?>">
                <td>
                    <img src="../escudos/<?= htmlspecialchars($fila['escudo']) ?>" alt="Escudo" style="width: 30px; height: 30px; margin-right: 10px;">
                    <?= htmlspecialchars($fila['equipo']) ?>
                </td>
                <td><?= htmlspecialchars($fila['partidos_jugados']) ?></td>
                <td><?= htmlspecialchars($fila['victorias']) ?></td>
                <td><?= htmlspecialchars($fila['empates']) ?></td>
                <td><?= htmlspecialchars($fila['derrotas']) ?></td>
                <td><?= htmlspecialchars($fila['goles_a_favor']) ?></td>
                <td><?= htmlspecialchars($fila['goles_en_contra']) ?></td>
                <td><?= htmlspecialchars($fila['diferencia_goles']) ?></td>
                <td><?= htmlspecialchars($fila['puntos']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
              <!-- TERMINO DE TABLA DE POSICIONES -->
                </div>
                <!-- /.table-responsive -->
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

          <div class="col-md-4">
            <!-- Info Boxes Style 2 -->
            <div class="info-box mb-3 bg-warning">
              <span class="info-box-icon"><i class="fas fa-tag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Inventory</span>
                <span class="info-box-number">5,200</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-success">
              <span class="info-box-icon"><i class="far fa-heart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Mentions</span>
                <span class="info-box-number">92,050</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-danger">
              <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Downloads</span>
                <span class="info-box-number">114,381</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-info">
              <span class="info-box-icon"><i class="far fa-comment"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Direct Messages</span>
                <span class="info-box-number">163,921</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Browser Usage</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <div class="chart-responsive">
                      <canvas id="pieChart" height="150"></canvas>
                    </div>
                    <!-- ./chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <ul class="chart-legend clearfix">
                      <li><i class="far fa-circle text-danger"></i> Chrome</li>
                      <li><i class="far fa-circle text-success"></i> IE</li>
                      <li><i class="far fa-circle text-warning"></i> FireFox</li>
                      <li><i class="far fa-circle text-info"></i> Safari</li>
                      <li><i class="far fa-circle text-primary"></i> Opera</li>
                      <li><i class="far fa-circle text-secondary"></i> Navigator</li>
                    </ul>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      United States of America
                      <span class="float-right text-danger">
                        <i class="fas fa-arrow-down text-sm"></i>
                        12%</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      India
                      <span class="float-right text-success">
                        <i class="fas fa-arrow-up text-sm"></i> 4%
                      </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      China
                      <span class="float-right text-warning">
                        <i class="fas fa-arrow-left text-sm"></i> 0%
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.footer -->
            </div>
            <!-- /.card -->

            <!-- PRODUCT LIST -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recently Added Products</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  <li class="item">
                    <div class="product-img">
                      <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">Samsung TV
                        <span class="badge badge-warning float-right">$1800</span></a>
                      <span class="product-description">
                        Samsung 32" 1080p 60Hz LED Smart HDTV.
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">Bicycle
                        <span class="badge badge-info float-right">$700</span></a>
                      <span class="product-description">
                        26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">
                        Xbox One <span class="badge badge-danger float-right">
                        $350
                      </span>
                      </a>
                      <span class="product-description">
                        Xbox One Console Bundle with Halo Master Chief Collection.
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">PlayStation 4
                        <span class="badge badge-success float-right">$399</span></a>
                      <span class="product-description">
                        PlayStation 4 500GB Console (PS4)
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                </ul>
              </div>
              <!-- /.card-body -->
              <div class="card-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Products</a>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

<?php require_once("../footer.php"); ?>