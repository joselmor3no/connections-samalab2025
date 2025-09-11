<?php
$url = $_SERVER["REQUEST_URI"];
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-success elevation-4">
    <!-- Brand Logo -->
    <a href="usuarios" class="brand-link">
        <img src="../assets/images/favicon.png" alt="logo" class="brand-image img-circle elevation-3 p-1" style="background: white;">
        <span class="brand-text font-weight-light">Connections</span>
        <h6 class="text-center mt-2 brand-text font-weight-bold">ADMINISTADOR</h6>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../assets/images/admin.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php
                    if (isset($usuario)) {
                        echo $usuario;
                    }
                    ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="usuarios" class="nav-link <?= strpos($url, '/usuario') !== false ? "active" : "" ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Usuarios
                        </p>
                    </a>
                </li>
                
                  <li class="nav-item">
                    <a href="clientes" class="nav-link <?= strpos($url, '/clientes') !== false ? "active" : "" ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Clientes
                        </p>
                    </a>
                </li>

                <li class="nav-item <?= strpos($url, 'empresa') !== false || strpos($url, 'eliminar') !== false || strpos($url, 'sucursal') !== false ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link <?= strpos($url, 'empresa') !== false || strpos($url, 'eliminar') !== false || strpos($url, 'sucursal') !== false ? "active" : "" ?>"  >
                        <i class="nav-icon fas fa-city"></i>
                        <p>
                            Empresas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="empresas" class="nav-link  <?= strpos($url, '/empresa') !== false ? "active" : "" ?>">
                                <i class="fas fa-city nav-icon"></i>
                                <p>Alta de Empresa</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="eliminar-empresa" class="nav-link <?= strpos($url, '/eliminar-empresa') !== false ? "active" : "" ?>">
                                <i class="fas fa-store-alt-slash nav-icon"></i>

                                <p>Eliminar Empresa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="clonar-sucursal" class="nav-link  <?= strpos($url, '/clonar-sucursal') !== false ? "active" : "" ?>">
                                <i class="far fa-window-restore nav-icon"></i>
                                <p>Clonar Sucursal</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="eliminar-sucursal-empresa" class="nav-link <?= strpos($url, '/eliminar-sucursal') !== false ? "active" : "" ?>">
                                <i class="far fa-trash-alt nav-icon"></i>
                                <p>Eliminar Sucursal</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="eliminar-orden" class="nav-link <?= strpos($url, '/eliminar-orden') !== false ? "active" : "" ?>">
                                <i class="far fa-file-excel nav-icon"></i>
                                <p>Eliminar Orden</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="eliminar-usuario" class="nav-link <?= strpos($url, '/eliminar-usuario') !== false ? "active" : "" ?>">
                                <i class="fa fa-user-times nav-icon"></i>
                                <p>Eliminar Usuario</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item  <?= strpos($url, 'catalogo') !== false ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link <?= strpos($url, 'catalogo') !== false ? "active" : "" ?>" >
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>
                            Catálogos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="catalogo-estudios" class="nav-link  <?= strpos($url, 'estudio') !== false ? "active" : "" ?>">
                                <i class="fas fa-clipboard-list nav-icon"></i>
                                <p>Estudios</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="catalogo-departamentos" class="nav-link <?= strpos($url, 'departamento') !== false ? "active" : "" ?>">
                                <i class="fas fa-book-medical nav-icon"></i>
                                <p>Departamentos</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="catalogo-especialidades" class="nav-link <?= strpos($url, 'especialidad') !== false ? "active" : "" ?>">
                                <i class="fas fa-notes-medical  nav-icon"></i>
                                <p>Especialidades</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="catalogo-materias-biologicas" class="nav-link <?= strpos($url, 'materia') !== false ? "active" : "" ?>">
                                <i class="fas fa-syringe nav-icon"></i>
                                <p>Materia Biológica</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="catalogo-recipientes-biologicos" class="nav-link <?= strpos($url, 'recipiente') !== false ? "active" : "" ?>">
                                <i class="fas fa-vial nav-icon"></i>
                                <p>Recipiente Biológico</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="catalogo-secciones" class="nav-link <?= strpos($url, 'seccion') !== false ? "active" : "" ?>">
                                <i class="fas fa-th nav-icon"></i>
                                <p>Secciones</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="catalogo-empleados" class="nav-link <?= strpos($url, 'empleados') !== false ? "active" : "" ?>">
                                <i class="fas fa-users nav-icon"></i>
                                <p>Tipo de Empleado</p>
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
