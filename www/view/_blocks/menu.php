<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
$url = $_SERVER["REQUEST_URI"];

$id_usuario = $_SESSION["id"];

//echo '<pre>---------------------------'; print_r($_SESSION["id"]); echo '</pre>';
$id_sesion_activa = session_id();

$usuarios = new Usuarios();
$user_sesion = $usuarios->getUsuarioSesion($id_usuario);

//VALIDACION DE DOBLE SESION
if ($user_sesion[0]->sesion_activa != $_SESSION["sesion_activa"]) {
    echo '<input type="hidden" id="msg" name="msg" value="error" class="d-none">';

    session_start();
    session_destroy();
}

if ($id_usuario == "") {
    header("Location: /");
}

//temporal
function validarPermisos($permiso) {
    $permisos = $_SESSION["permisos"];
    foreach ($permisos AS $row) {

        if ($row->siglas == $permiso || strpos(strtoupper($_SESSION["usuario"]), "CONNECTIONS") !== false) {
            return true;
        }
    }
    return false;
}
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-success elevation-4">
    <!-- Brand Logo -->

    <div class="row">
        <div class="col-md-12 text-center pt-2">
            <img src="assets/images/favicon.png" alt="logo" class="img-fluid img-circle p-1 w-50" style="background: white;">
        </div>
    </div>

    <a href="inicio" class="brand-link text-center">
        <!--img src="assets/images/favicon.png" alt="logo" class="brand-image img-circle elevation-3 p-1" style="background: white;"-->
        <span class="brand-text font-weight-bold">Connections Lab</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="assets/images/user.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php
                    if (isset($usuario)) {
                        echo $usuario;
                    }
                    ?></a>
            </div>
        </div>

        <div class="user-panel mt-3 p-2 mb-3 d-flex" style="color:white;">
            <div class="form-group">
                <label for="sucursal">Sucursal:</label>
                <select id="btn-cambio-sucursal" name="sucursal" class="form-control w-100" id="sucursal" style="font-size:12px;">
                    <?php
                        $opciones=$usuarios->listaSucursalesAcceso($_SESSION['acceso_sucursales']);
                        echo $opciones;
                    ?>
                </select>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="inicio" class="nav-link <?= $url == '/inicio' ? "active" : "" ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Inicio
                            <!--span class="right badge badge-danger">New</span-->
                        </p>
                    </a>
                    <hr class="bg-success text-white m-1">
                </li>
                <?php
                if (validarPermisos("r1") || validarPermisos("r2") || validarPermisos("r3") || validarPermisos("r4") || validarPermisos("r5") || validarPermisos("r6")) {
                    ?>
                    <li class="nav-item <?= strpos($url, '/registro') !== false || strpos($url, '/gastos') !== false || strpos($url, 'corte') !== false || strpos($url, '/caja') !== false || strpos($url, 'etiquetas') !== false || strpos($url, 'cotizacion') !== false || strpos($url, 'resultado') !== false ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= strpos($url, '/registro') !== false || strpos($url, '/gastos') !== false || strpos($url, 'corte') !== false || strpos($url, '/caja') !== false || strpos($url, 'etiquetas') !== false || strpos($url, 'cotizacion') !== false || strpos($url, 'resultado') !== false ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-address-card"></i>
                            <p>
                                Admisión
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php
                            if (validarPermisos("r1")) {
                                ?>
                                <li class="nav-item">
                                    <a href="registro-paciente" class="nav-link <?= strpos($url, '/registro') !== false ? "active" : "" ?>">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Registro Paciente</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("r2")) {
                                ?>
                                <li class="nav-item">
                                    <a href="caja" class="nav-link <?= strpos($url, '/caja') !== false || strpos($url, '/gastos') !== false || strpos($url, 'corte') !== false ? "active" : "" ?>">
                                        <i class="far fa-credit-card nav-icon"></i>
                                        <p>Caja</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("r6")) {
                                ?>
                                <li class="nav-item">
                                    <a href="agenda" class="nav-link">
                                        <i class="far fa-calendar-alt nav-icon"></i>
                                        <p>Agenda</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("r3")) {
                                ?>
                                <li class="nav-item">
                                    <a href="etiquetas-estudios" class="nav-link <?= strpos($url, '/etiquetas-estudios') !== false ? "active" : "" ?>">
                                        <i class="far fa-bookmark nav-icon"></i>
                                        <p>Etiquetas</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("r4")) {
                                ?>
                                <li class="nav-item">
                                    <a href="cotizaciones" class="nav-link <?= strpos($url, '/cotizaciones') !== false ? "active" : "" ?>">
                                        <i class="fas fa-calculator nav-icon"></i>
                                        <p>Cotizaciones</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="registro-cotizacion" class="nav-link <?= strpos($url, '/registro-cotizacion') !== false ? "active" : "" ?>">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Registro Cotizaciones</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("r5")) {
                                ?>
                                <li class="nav-item">
                                    <a href="resultados" class="nav-link <?= strpos($url, '/resultado') !== false ? "active" : "" ?>">
                                        <i class="far fa-paper-plane nav-icon"></i>
                                        <p>Resultados</p>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <hr class="bg-success text-white m-1">
                    </li>
                    <?php
                }

                if (validarPermisos("l1") || validarPermisos("l2") || validarPermisos("l3") || validarPermisos("l4")) {
                    ?>
                    <li class="nav-item  <?= strpos($url, '/reporte-lab') !== false ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= strpos($url, '/reporte-lab') !== false ? "active" : "" ?>">
                            <i class="nav-icon fas fa-microscope"></i>
                            <p>
                                Laboratorio
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php
                            if (validarPermisos("l1")) {
                                ?>
                                <li class="nav-item">
                                    <a href="reporte-laboratorio" class="nav-link <?= ($url == '/reporte-laboratorio' || strpos($url, '/reporte-estudio') !== false || strpos($url, '/reporte-global') !== false) ? "active" : "" ?>">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>Reporte</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("l3")) {
                                ?>
                                <li class="nav-item">
                                    <a href="reporte-laboratorio-sucursales" class="nav-link <?= $url == '/reporte-laboratorio-sucursales' ? "active" : "" ?>">
                                        <i class="fas fa-list-ol nav-icon"></i>
                                        <p>Reporte Sucursales</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("l2")) {
                                ?>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-prescription-bottle-alt nav-icon"></i>
                                        <p>Inventario</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("l4")) {
                                ?>
                                <li class="nav-item">
                                    <a href="documentos-complementarios" class="nav-link">
                                        <i class="fas fa-prescription-bottle-alt nav-icon"></i>
                                        <p>Documentos <br> <span style="margin-left:30px;">Complementarios</span> </p>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>

                        </ul>
                        <hr class="bg-success text-white m-1">
                    </li>
                    <?php
                }
                if (validarPermisos("t1") || validarPermisos("t2") || validarPermisos("t3") || validarPermisos("t4") || validarPermisos("t5") || validarPermisos("t6") || validarPermisos("t7")) {
                    ?>

                    <li class="nav-item <?= (strpos($url, '/imagen') !== false || strpos($url, '/tecnicos') !== false || strpos($url, '/formatos') !== false || strpos($url, '/conclusion') !== false) ? "menu-open" : (strpos($url, '/reporte-local') !== false ? "menu-open" : (strpos($url, '/visor-anterior') !== false ? "menu-open" : "")) ?>">


                        <a href="#" class="nav-link <?= (strpos($url, '/imagen') !== false || strpos($url, '/tecnicos') !== false || strpos($url, '/formatos') !== false || strpos($url, '/conclusion') !== false) ? "active" : ((strpos($url, '/reporte-local') !== false) ? "active" : ((strpos($url, '/visor-anterior') !== false) ? "active" : "")) ?>">

                            <i class="nav-icon fas fa-skull-crossbones"></i>
                            <p>
                                Imagenología
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php
                            if (validarPermisos("t1")) {
                                ?>
                                <li class="nav-item">
                                    <a href="imagen" class="nav-link <?= strpos($url, '/imagen') !== false ? "active" : "" ?>">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>Lista de Reporte</p>
                                    </a>
                                </li>
                                <?php
                            }
                            if (validarPermisos("t2")) {
                                ?>
                                <li class="nav-item">
                                    <a href="tecnicos" class="nav-link <?= strpos($url, '/tecnicos') !== false ? "active" : "" ?>">
                                        <i class="fas fa-list-ol nav-icon"></i>
                                        <p>Lista Tecnicos</p>
                                    </a>
                                </li>
                                <?php
                            }
                            if (validarPermisos("t3")) {
                                ?>
                                <li class="nav-item">
                                    <a href="formatos" class="nav-link <?= strpos($url, '/formatos') !== false ? "active" : "" ?>">
                                        <i class="far fa-edit nav-icon"></i>
                                        <p>Formatos</p>
                                    </a>
                                </li>
                                <?php
                            }
                            if (validarPermisos("t4")) {
                                ?>
                                <li class="nav-item">
                                    <a href="conclusion" class="nav-link <?= strpos($url, '/conclusion') !== false ? "active" : "" ?>">
                                        <i class="fas fa-user-times nav-icon"></i>
                                        <p>Quitar Conclusion

                                        </p>
                                    </a>
                                </li>
                                <?php
                            }
                            if (validarPermisos("t5")) {
                                ?>
                                <li class="nav-item">
                                    <a href="reporte-local" class="nav-link <?= strpos($url, '/reporte-local') !== false ? "active" : "" ?>">
                                        <i class="fas fa-chalkboard-teacher  nav-icon"></i>
                                        <p>Reporte Local

                                        </p>
                                    </a>
                                </li>
                                <?php
                            }
                            if (validarPermisos("t6")) {
                                ?>
                                <li class="nav-item">
                                    <a href="visor-anterior" class="nav-link <?= strpos($url, '/visor-anterior') !== false ? "active" : "" ?>">
                                        <i class="fas fa-history  nav-icon"></i>
                                        <p>Visor Anterior

                                        </p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("t7")) {
                                ?>
                                <li class="nav-item">
                                    <a href="estudios-dentales" class="nav-link <?= strpos($url, '/visor-anterior') !== false ? "active" : "" ?>">
                                        <i class="fas fa-history  nav-icon"></i>
                                        <p>Estudios Dentales

                                        </p>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <hr class="bg-success text-white m-1">
                    </li>
                    <?php
                }
                if (validarPermisos("a1") || validarPermisos("a2") || validarPermisos("a3") || validarPermisos("a4") || validarPermisos("a5") || validarPermisos("a6") || validarPermisos("a7") || validarPermisos("a8") || validarPermisos("a9") || validarPermisos("a10")) {
                    ?>

                    <li class="nav-item <?= strpos($url, '/pago-empresa') !== false || strpos($url, '/facturacion') !== false || strpos($url, '/desaplicacion') !== false || strpos($url, '/bitacora') !== false || strpos($url, '/modificacion-paciente') !== false || strpos($url, '/modificacion-paciente-generales') !== false || strpos($url, '/dinero-electronico') !== false || strpos($url, '/reporteador') !== false || strpos($url, '/inventario-') !== false ? "menu-open" : "" ?>">
                        <a href="#" class="nav-link <?= strpos($url, '/pago-empresa') !== false || strpos($url, '/facturacion') !== false || strpos($url, '/desaplicacion') !== false || strpos($url, '/bitacora') !== false || strpos($url, '/modificacion-paciente') !== false || strpos($url, '/modificacion-paciente-generales') !== false || strpos($url, '/dinero-electronico') !== false || strpos($url, '/reporteador') !== false || strpos($url, '/inventario-') !== false ? "active" : "" ?>">
                            <i class="nav-icon fas fa-donate"></i>
                            <p>
                                Administración
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php
                            if (validarPermisos("a8")) {
                                ?>
                                <li class="nav-item">
                                    <a href="pago-empresa" class="nav-link <?= strpos($url, '/pago-empresa') !== false ? "active" : "" ?>">
                                        <i class="fas fa-cash-register nav-icon"></i>
                                        <p>Pagos a Empresas</p>
                                    </a>
                                </li>

                                <?php
                            }

                            if (validarPermisos("a1")) {
                                ?>
                                <li class="nav-item">
                                    <a href="facturacion" class="nav-link <?= strpos($url, '/facturacion') !== false ? "active" : "" ?>">
                                        <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                        <p>Facturación Electrónica</p>
                                    </a>
                                </li>

                                <?php
                            }

                            if (validarPermisos("a2")) {
                                ?>
                                <li class="nav-item">
                                    <a href="desaplicacion" class="nav-link <?= strpos($url, '/desaplicacion') !== false ? "active" : "" ?>">
                                        <i class="fas fa-handshake-slash nav-icon"></i>
                                        <p>Desaplicación de Pagos</p>
                                    </a>
                                </li>

                                <?php
                            }

                            if (validarPermisos("a4")) {
                                ?>
                                <li class="nav-item">
                                    <a href="bitacora" class="nav-link <?= strpos($url, '/bitacora') !== false ? "active" : "" ?>">
                                        <i class="fas fa-user-secret nav-icon"></i>
                                        <p>Bitacora por Paciente</p>
                                    </a>
                                </li>

                                <?php
                            }

                            if (validarPermisos("a3")) {
                                ?>
                                <li class="nav-item">
                                    <a href="modificacion-paciente" class="nav-link <?= strpos($url, '/modificacion-paciente') !== false ? "active" : "" ?>">
                                        <i class="fas fa-user-edit nav-icon"></i>
                                        <p>Modificación de Paciente</p>
                                    </a>
                                </li>

                                <?php
                            }

                            if (validarPermisos("a9")) {
                                ?>

                                <li class="nav-item">
                                    <a href="modificacion-paciente-generales" class="nav-link <?= strpos($url, '/modificacion-paciente-generales') !== false ? "active" : "" ?>">
                                        <i class="fas fa-user-edit nav-icon"></i>
                                        <p>Modificación Paciente <br> <span style="margin-left:30px;">Datos Generales</span></p>
                                    </a>
                                </li>

                                <?php
                            }

                            if (validarPermisos("a10")) {
                                ?>

                                <li class="nav-item">
                                    <a href="modificacion-paciente-admin" class="nav-link <?= strpos($url, '/modificacion-paciente-admin') !== false ? "active" : "" ?>">
                                        <i class="fas fa-user-edit nav-icon"></i>
                                        <p>Modificación Paciente <br> <span style="margin-left:30px;">(ADMIN)</span></p>
                                    </a>
                                </li>

                                <?php
                            }

                            if (validarPermisos("a5")) {
                                ?>
                                <li class="nav-item">
                                    <a href="reporteador" class="nav-link <?= strpos($url, 'reporteador') !== false ? "active" : "" ?>">

                                        <i class="fas fa-chart-line nav-icon"></i>
                                        <p>Reportes</p>
                                    </a>
                                </li>

                                <?php
                            }

                            if (validarPermisos("a6")) {
                                ?>
                                <li class="nav-item">
                                    <a href="inventario-salida" class="nav-link <?= strpos($url, '/inventario-') !== false ? "active" : "" ?>">
                                        <i class="fas fa-prescription-bottle-alt nav-icon"></i>
                                        <p>Inventario</p>
                                    </a>
                                </li>

                                <?php
                            }

                            if (validarPermisos("a7")) {
                                ?>

                                <li class="nav-item">
                                    <a href="dinero-electronico" class="nav-link <?= strpos($url, '/dinero-electronico') !== false ? "active" : "" ?>">
                                        <i class="fas fa-money-check-alt nav-icon"></i>
                                        <p>Dinero Electrónico</p>
                                    </a>
                                </li>

                                <?php
                            }
                            

                            ?>

                        </ul>
                        <hr class="bg-success text-white m-1">
                    </li>

                    <?php
                }
                if (validarPermisos("p1") || validarPermisos("p2") || validarPermisos("p3") || validarPermisos("p4") || validarPermisos("p5") || validarPermisos("p6") || validarPermisos("p7") || validarPermisos("p8") || validarPermisos("p9")) {
                    ?>

                    <li class="nav-item <?= strpos($url, '/doctor') !== false || strpos($url, '/empresa') !== false || strpos($url, '/estudio') !== false || strpos($url, 'paquete') !== false || strpos($url, 'lista') !== false || strpos($url, 'estructura') !== false || strpos($url, 'usuario') !== false || strpos($url, 'seccion') !== false ? "menu-open" : "" ?>">
                        <a href="#" class="nav-link <?= strpos($url, '/doctor') !== false || strpos($url, '/empresa') !== false || strpos($url, '/estudio') !== false || strpos($url, 'paquete') !== false || strpos($url, 'lista') !== false || strpos($url, 'estructura') !== false || strpos($url, 'usuario') !== false || strpos($url, 'seccion') !== false ? "active" : "" ?>">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>
                                Catálogos 
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php
                            if (validarPermisos("p1")) {
                                ?>
                                <li class="nav-item">
                                    <a href="doctores" class="nav-link <?= strpos($url, '/doctor') !== false ? "active" : "" ?>">
                                        <i class="fas fa-user-md nav-icon"></i>
                                        <p>Doctores</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("p9")) {
                                ?>
                                <li class="nav-item">
                                    <a href="zonas-promotores" class="nav-link <?= strpos($url, '/zonas-promotores') !== false ? "active" : "" ?>">
                                        <i class="fas fa-user-md nav-icon"></i>
                                        <p>Zonas y Promotores</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("p2")) {
                                ?>
                                <li class="nav-item">
                                    <a href="empresas" class="nav-link <?= strpos($url, '/empresa') !== false ? "active" : "" ?>">
                                        <i class="far fa-building nav-icon"></i>
                                        <p>Empresas</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("p3")) {
                                ?>

                                <li class="nav-item">
                                    <a href="estudios" class="nav-link <?= strpos($url, '/estudio') !== false ? "active" : "" ?>">
                                        <i class="fas fa-clipboard-list nav-icon"></i>
                                        <p>Estudios</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("p4")) {
                                ?>

                                <li class="nav-item">
                                    <a href="usuarios" class="nav-link <?= strpos($url, '/usuario') !== false ? "active" : "" ?>">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Usuarios</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("p5")) {
                                ?>
                                <li class="nav-item">
                                    <a href="estructura-descuentos" class="nav-link <?= strpos($url, '/estructura') !== false ? "active" : "" ?>">
                                        <i class="fas fa-cog nav-icon"></i>
                                        <p>Estructura</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("p6")) {
                                ?>

                                <li class="nav-item">
                                    <a href="paquetes-y-perfiles" class="nav-link <?= strpos($url, '/paquete') !== false ? "active" : "" ?>">
                                        <i class="fas fa-box nav-icon"></i>
                                        <p>Paquetes y Perfiles</p>
                                    </a>
                                </li>
                                <?php
                            }

                            if (validarPermisos("p7")) {
                                ?>

                                <li class="nav-item">
                                    <a href="listas-precios" class="nav-link <?= strpos($url, '/lista') !== false ? "active" : "" ?>">
                                        <i class="fas fa-list-ol nav-icon"></i>
                                        <p>Listas de Precios</p>
                                    </a>
                                </li>
                                <?php
                            }
                            
                            if (validarPermisos("p8")) {
                                ?>

                                <li class="nav-item">
                                    <a href="secciones-agenda" class="nav-link <?= strpos($url, '/seccion') !== false ? "active" : "" ?>">
                                        <i class="fas fa-calendar-alt nav-icon"></i>
                                        <p>Secciones Agenda</p>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <hr class="bg-success text-white m-1">
                    </li>

                    <?php
                }
                ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
