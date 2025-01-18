

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                <a href="index.php" class="nav-link active">
                  <i class="nav-icon fas fa-house-chimney"></i>
                  <p>Escritorio</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fa-solid fa-users"></i>
                  <p>Usuarios</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="crear_usuario.php" class="nav-link">
                      <i class="fa-solid fa-user-plus"></i>
                      <p>Crear Usuario</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="crear_tipo_usuario.php" class="nav-link">
                      <i class="fa-solid fa-user-tie"></i>
                      <p>Crear Tipo de Usuario</p>
                    </a>
                  </li>
                </ul>
                  </a>
                </li>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <img src="../iconos/icono-partido-equipos.png" alt="Partidos y Equipos" class="icono-sidebar" width="30" height="30">
                  <p>
                    Partidos y Equipos
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="crear_equipo.php" class="nav-link">
                      <img src="../iconos/icono-equipo.png" alt="Equipos" class="icono-sidebar" width="30" height="30">
                      <p>Crear Equipo</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="crear_partido.php" class="nav-link">
                      <img src="../iconos/icono-crear-partido.png" alt="Crear Partidos" class="icono-sidebar" width="30" height="30">
                      <p>Crear Partido</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="crear_fecha.php" class="nav-link">
                      <img src="../iconos/icono-crear-fecha.png" alt="Crear Fechas" class="icono-sidebar" width="30" height="30">
                      <p>Crear Fecha</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="listado_equipos.php" class="nav-link">
                      <img src="../iconos/icono-listado-equipos.png" alt="Listado de Equipos" class="icono-sidebar" width="30" height="30">
                      <p>Listado de Equipos</p>
                    </a>
                  </li>
                </ul>
              </li>
          </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>