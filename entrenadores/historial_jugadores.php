<?php require_once("../header.php"); ?>
<?php require_once("../sidebar.php"); ?>

<style>
    .carousel-container {
        display: flex;
        overflow: hidden;
        width: 100%;
        position: relative;
        height: 300px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .carousel-slide {
        flex: 1;
        position: relative;
        overflow: hidden;
        transition: flex 0.5s ease;
    }
    .carousel-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .carousel-slide:hover {
        flex: 2;
    }
    .carousel-slide:hover img {
        transform: scale(1.1);
    }
    .player-info {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 14px;
        opacity: 0; /* Ocultar por defecto */
        transition: opacity 0.3s ease; /* Añadir transición para el efecto */
        text-align: center;
    }
    .carousel-slide:hover .player-info {
        opacity: 1; /* Mostrar al pasar el mouse */
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Listado Jugadores</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Listado Jugadores</li>
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
         <?php
        if (isset($_SESSION['equipo_id'])) {
    $equipo_id = $_SESSION['equipo_id'];

    try {
        $sql = "SELECT id, nombre, imagen, posicion FROM jugadores WHERE equipo_id = :equipo_id ORDER BY 
                CASE 
                    WHEN posicion IN ('Portero (PT)') THEN 1 
                    WHEN posicion IN ('Defensa Central (CT)', 'Lateral Izquierdo (LI)', 'Lateral Derecho (LD)') THEN 2 
                    WHEN posicion IN ('Medio Centro (MC)', 'Medio Centro Defensivo (MCD)', 'Media Punta (MP)', 'Interior Izquierdo (II)', 'Interior Derecho (ID)') THEN 3 
                    WHEN posicion IN ('Delantero Centro (DC)', 'Extremo Izquierdo (EI)', 'Extremo Derecho (ED)') THEN 4 
                    ELSE 5 
                END";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':equipo_id', $equipo_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categorias = [
                'Porteros' => ['Portero (PT)'],
                'Defensas' => ['Defensa Central (CT)', 'Lateral Izquierdo (LI)', 'Lateral Derecho (LD)'],
                'Mediocampistas' => ['Medio Centro (MC)', 'Medio Centro Defensivo (MCD)', 'Media Punta (MP)', 'Interior Izquierdo (II)', 'Interior Derecho (ID)'],
                'Delanteros' => ['Delantero Centro (DC)', 'Extremo Izquierdo (EI)', 'Extremo Derecho (ED)']
            ];

            echo "<div class='container-fluid'>";
            foreach ($categorias as $categoria => $posiciones) {
                $filtrados = array_filter($jugadores, function ($jugador) use ($posiciones) {
                    return in_array($jugador['posicion'], $posiciones);
                });
                
                if (!empty($filtrados)) {
                    echo "<h3 class='mt-3'>$categoria</h3>";
                    echo "<div class='carousel-container'>";
                    foreach ($filtrados as $row) {
                        echo "<div class='carousel-slide'>";
                        echo "<img src='" . htmlspecialchars($row['imagen']) . "' class='img-fluid' alt='" . htmlspecialchars($row['nombre']) . "'>";
                        echo "<div class='player-info'>" . htmlspecialchars($row['nombre']) . "<br>" . htmlspecialchars($row['posicion']) . "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
            }
            echo "</div>";
        } else {
            echo "<p class='text-center text-muted'>No se encontraron jugadores para este equipo.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='text-danger text-center'>Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='text-center text-warning'>No hay un equipo seleccionado en la sesión.</p>";
}
?>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const slides = document.querySelectorAll(".carousel-slide");
        slides.forEach(slide => {
            slide.addEventListener("mouseover", () => {
                slides.forEach(s => s.style.flex = "1");
                slide.style.flex = "2";
            });
            slide.addEventListener("mouseout", () => {
                slides.forEach(s => s.style.flex = "1");
            });
        });
    });
</script>

<!-- Main Footer -->
<?php require_once("../footer.php"); ?>
