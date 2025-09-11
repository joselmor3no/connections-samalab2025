<?php
$url = $_SERVER["REQUEST_URI"];
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-success elevation-4">
    <!-- Brand Logo -->
    <a href="sucursales" class="brand-link">
        <img src="../assets/images/favicon.png" alt="logo" class="brand-image img-circle elevation-3 p-1"
             style="background: white;">
        <span class="brand-text font-weight-light">Connections</span>
        <h6 class="text-center mt-2 brand-text font-weight-bold">EMPRESAS</h6>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../assets/images/user.png" class="img-circle elevation-2" alt="User Image">
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
                    <a href="estudios" class="nav-link <?= strpos($url, '/estudio') !== false ? "active" : "" ?>">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Estudios
                        </p>
                    </a>
                </li>

                <li
                    class="nav-item <?= strpos($url, 'sucursal') !== false || strpos($url, 'usuario') !== false ? "menu-open" : "" ?>">
                    <a href="#"
                       class="nav-link <?= strpos($url, 'sucursal') !== false || strpos($url, 'usuario') !== false ? "active" : "" ?>">
                        <i class="nav-icon fas fa-city"></i>
                        <p>
                            Sucursales
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="sucursales"
                               class="nav-link <?= strpos($url, '/sucursal') !== false ? "active" : "" ?>">
                                <i class="fas fa-city nav-icon"></i>
                                <p>Alta de Sucursales</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="clonar-sucursal"
                               class="nav-link  <?= strpos($url, '/clonar-sucursal') !== false ? "active" : "" ?>">
                                <i class="far fa-window-restore nav-icon"></i>
                                <p>Clonar Sucursal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="usuarios"
                               class="nav-link  <?= strpos($url, '/usuario') !== false ? "active" : "" ?>">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Usuarios Sucursales</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="doctores"
                               class="nav-link  <?= strpos($url, 'doctores') !== false ? "active" : "" ?>">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Doctores Sucursales</p>
                            </a>
                        </li>

                    </ul>
                    <hr class="bg-success text-white m-1">
                </li>


                <li class="nav-item <?= strpos($url, 'formato') !== false ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link <?= strpos($url, 'formato') !== false ? "active" : "" ?>">
                        <i class="nav-icon far fa-edit"></i>
                        <p>
                            Editor de Formatos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="formato-laboratorio"
                               class="nav-link <?= strpos($url, '/formato-laboratorio') !== false ? "active" : "" ?>">
                                <i class="fas fa-file-signature nav-icon"></i>
                                <p>Reporte de resultados</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="formato-recibo"
                               class="nav-link  <?= strpos($url, '/formato-recibo') !== false ? "active" : "" ?>">
                                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>Recibos</p>
                            </a>
                        </li>
                    </ul>
                    <hr class="bg-success text-white m-1">
                </li>
                <li class="nav-item <?= strpos($url, 'reporte') !== false ? "menu-open" : "" ?>">
                    <a href="#" class="nav-link <?= strpos($url, 'reporte') !== false ? "active" : "" ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Reportes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="reporte-corte" class="nav-link  <?= strpos($url, '/reporte-corte') !== false ? "active" : "" ?>">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>Cortes</p>
                            </a>
                        </li>

                        <li class="nav-item">
                        <a href="reporte-venta" class="nav-link  <?= strpos($url, '/reporte-venta') !== false ? "active" : "" ?>">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>Venta</p>
                            </a>
                        </li>
                        <!--<li class="nav-item">
                        <a href="reporte-estudios" class="nav-link  <?= strpos($url, '/reporte-estudios') !== false ? "active" : "" ?>">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>Estudios</p>
                            </a>
                        </li>-->
                        <li class="nav-item">
                        <a href="reporte-empresas" class="nav-link  <?= strpos($url, '/reporte-empresas') !== false ? "active" : "" ?>">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>Empresas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a href="reporte-comisiones" class="nav-link  <?= strpos($url, '/reporte-comisiones') !== false ? "active" : "" ?>">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>Comisiones</p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a href="reporte-saldos" class="nav-link  <?= strpos($url, '/reporte-saldos') !== false ? "active" : "" ?>">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>Sucursales </p>
                            </a>
                        </li>
                    </ul>
                    <hr class="bg-success text-white m-1">
                </li>

                <li class="nav-item <?= strpos($url, 'producto') !== false || strpos($url, 'proveedor') !== false ? "menu-open" : "" ?>">

                    <a href="#" class="nav-link <?= strpos($url, 'producto') !== false || strpos($url, 'proveedor') !== false ? "active" : "" ?>">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Inventario
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="productos" class="nav-link  <?= strpos($url, '/producto') !== false ? "active" : "" ?>">
                                <i class="fas fa-prescription-bottle-alt nav-icon"></i>
                                <p>Productos</p>
                            </a>
                        </li>
                        <!--li class="nav-item">
                            <a href="proveedores" class="nav-link  <?= strpos($url, '/proveedor') !== false ? "active" : "" ?>">
                                <i class="fas fa-shipping-fast nav-icon"></i>
                                <p>Proveedores</p>
                            </a>
                        </li-->

                        <li class="nav-item">
                            <a href="combos-productos" class="nav-link <?= strpos($url, '/combos-producto') !== false ? "active" : "" ?>">
                                <i class="fas fa-file-medical nav-icon"></i>
                                <p>Combos por estudio</p>
                            </a>
                        </li>
                        <!--li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-list-ol nav-icon"></i>
                                <p>Existencias</p>
                            </a>
                        </li-->
                    </ul>
                </li>

            </ul>
            <hr class="bg-success text-white m-1">
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>