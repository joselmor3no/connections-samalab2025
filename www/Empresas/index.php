<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Connections EMPRESAS</title>
        <link rel="shortcut icon" href="../../../../assets/images/favicon.png" type="image/x-icon">

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
        <!-- Theme css -->
        <link rel="stylesheet" type="text/css" href="../assets/css/form-style.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/responsive-form-style.css">

    </head>
    <body>
        <div class="login-page main">
            <div class="water-bg" id="water-js"></div>
            <div class="login-box">
                <div class="login-logo">
                    <!--a href="../../index2.html"><b>Admin</b>LTE</a-->
                </div>
                <!-- /.login-logo -->
                <div class="card">
                    <div class="card-body login-card-body">
                        <!--p class="login-box-msg">Sign in to start your session</p-->
                        <img src="../assets/images/logo.svg" alt="logo" class="img-fluid pb-3">
                        <h4 class="text-center text-primary mb-3">Administador de Empresas</h4>
                        <form class="needs-validation" action="controller/Acceso?opc=login" method="post" novalidate="">
                            <div class="input-group mb-3">
                                <input type="text" name="user" class="form-control" placeholder="Usuario" required="">

                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Favor de ingresar Usuario
                                </div>
                            </div>
                            <div class="input-group">
                                <input type="password" name="pass" class="form-control" placeholder="Contraseña" required="">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Favor de ingresar Contraseña
                                </div>
                            </div>
                            <div class="text-danger text-center">

                                <?php
                                if (isset($_REQUEST["error"]))
                                    echo $_REQUEST["error"] != "" ? "Usuaio y/o Contraseña incorrecto" : "";
                                ?>
                            </div>
                            <div class="row mt-3">

                                <div class="col-4 offset-4 ">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>


                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div>
            <!-- /.login-box -->
        </div>
        <div class="modal fade" id="loading" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-body text-center">

                        <!--h4>Por favor espere</h4-->
                        <h3><i class="fas fa-spinner fa-pulse mr-2 fa-sm"></i>Procesando ...</h3>
                    </div>

                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="../assets/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../assets/dist/js/adminlte.min.js"></script>

        <script src="../assets/js/jquery.ripples.js"></script>
        <!-- Custom js-->
        <script src="../assets/js/login-script.js"></script>

        <script src="../assets/js/index.js"></script>
    </body>
</html>
