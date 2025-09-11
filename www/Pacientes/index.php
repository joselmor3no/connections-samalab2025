<?php
session_start();
require_once('../model/Conexion.php');

$conexion = new Conexion();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Consulta de Resultados</title>
        <link rel="shortcut icon" href="../../../../assets/images/favicon.png" type="image/x-icon">

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/css/form-style.css">
        <link rel="stylesheet" href="assets/css/responsive-form-style.css">
        <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">

        <script>
            function habilitarEm(value)
            {
                if (value == false) {
                    $("#empresas").prop("checked", false);

                }
            }

            function habilitarCo(value)
            {
                if (value == false) {
                    $("#doctores").prop("checked", false);

                }
            }

        </script>

    </head>
    <body style="background:transparent; overflow:hidden;">
        <div class="container-fluid"  >
            <div class="row">
                      <div class="col-md-6 p-0 d-none d-md-block" >
                    <div class="login login-page-main" style="background-color: white!important;height: 102vh; ">
                        <div class="text-detail">
                            <div class="imgcontainer">
                                  <img style="height:350px;width:350px" src="../images-clientes/<?= $_SESSION["cliente"]->img_empresa ?>" alt="Avatar" class="avatar">
                            </div>
                           <center> <h1>Bienvenido</h1></center>
                           <center> <p>Consulta tus resultados en línea</p></center>
                            <center><p> <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Connections</a>.<br></strong> All rights reserved.</p></center>


                        </div>

                    </div>
                </div> <!-- fin el col-->


                <div class="col-md-6 p-0">
                    <div id="particles-js" style="width:100%;height:100vh;position:absolute;">


                    </div>
                    <div class="login-page" style="background-repeat: round; height: 102vh;">
                        <form class="login-form" action="controller/Acceso?opc=login" method="post" novalidate="">
                            <div class="input-control">
                                <center><h2><?= utf8_encode($_SESSION["cliente"]->cliente) ?></h2></center>
                                <input type="checkbox" name="tipo" value="paciente" checked="" class="d-none">
                                <!--input type="hidden" name="tipo" value="paciente" -->
                                <input type="text" name="user" class="form-control" placeholder="Usuario" required="">

                                <span class="password-field-show">
                                    <input type="password" name="pass" class="password-field" placeholder="Contraseña" required="">
                                    <span data-toggle=".password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                </span>

                                <div class="text-danger text-center">

                                    <?php
                                    if (isset($_REQUEST["error"]))
                                        echo $_REQUEST["error"] != "" ? "Usuaio y/o Contraseña incorrecto" : ""
                                        ?>
                                </div>

                                <label class="label-container">Empresa
                                    <input type="checkbox" id="empresas" name="tipo"  value="empresa" onfocus="habilitarCo(this.checked)">
                                    <span class="checkmark"></span>
                                </label>
                                <br>
                                <label class="label-container">Doctor
                                    <input type="checkbox" id="doctores" name="tipo" value="doctor" onfocus="habilitarEm(this.checked)">
                                    <span class="checkmark"></span>
                                </label>
                                <div class="login-btns">
                                    <button type="submit">Login</button>
                                </div>
                                <div class="division-lines"><p>Redes Sociales</p></div>
                                <div class="login-with-btns">
                                    <button type="button" class="facebook"><i class="fab fa-facebook-f"></i></button>
                                </div>
                            </div>
                        </form>
                    </div><!-- particulas-->
                </div><!-- fin col-->



            </div><!-- fin el row-->
        </div>

        <!-- jQuery -->
        <script src="../assets/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../assets/dist/js/adminlte.min.js"></script>
        <!-- theme particles script -->
        <script src="../assets/js/particles.min.js"></script>
        <script src="../assets/js/app.js"></script>

        <script src="../assets/js/index.js"></script>
    </body>
</html>
