<?php
$url = $_SERVER["REQUEST_URI"];
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-success elevation-4">
    <!-- Brand Logo -->
    <a href="resultados-doctor" class="brand-link">
        <img src="../assets/images/favicon.png" alt="logo" class="brand-image img-circle elevation-3 p-1"
             style="background: white;">
        <span class="brand-text font-weight-light">Connections</span>
        <h6 class="text-center mt-2 brand-text font-weight-bold">MÉDICO</h6>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <!--div class="image">
                <img src="../assets/images/user.png" class="img-circle elevation-2" alt="User Image">
            </div-->
            <div class="info">
                <a href="#" class="d-block"><?php
                    if (isset($usuario)) {
                        echo $usuario ;
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
                    <a href="resultados-doctor" class="nav-link <?=  strpos($url, 'resultados') !== false ? "active" : "" ?>">
                        <i class="nav-icon fas fa-microscope"></i>
                        <p>
                            Resultados
                            <!--span class="right badge badge-danger">New</span-->
                        </p>
                    </a>
                    <hr class="bg-success text-white m-1">
                </li>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<style>

    .user-panel, .user-panel .info {

        white-space: normal;
    }
</style>