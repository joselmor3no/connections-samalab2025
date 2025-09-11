<?php
session_start();

if (isset($_SESSION["id_usuario"])) {

    header("Location: https://" . $_SERVER['SERVER_NAME'] . "/contenido");
    echo '<script>console.log("si: ' . $_SESSION["id_usuario"] . '")</script>';
    //echo 'no redireccionó';
} else {
    echo '<script>console.log("no: ' . $_SESSION["id_usuario"] . '")</script>';
}

$rutaPath = 'Administrador/estados_images/login/';
$id_cat_estados = 33; // LO ESTABLEZCO ASI PORQUE ES LA IMAGEN PARA TODOS LOS ESTADOS, YA QUE AUN NO SABEMOS LA MANERA DE SABER DE QUE ESTADO ES LA SUCURSAL

$NombrePng = $rutaPath . 'cliente_' . $id_cat_estados . '.png';
$NombreJpg = $rutaPath . 'cliente_' . $id_cat_estados . '.jpg';
$NombreSvg = $rutaPath . 'cliente_' . $id_cat_estados . '.svg';
$NombreGif = $rutaPath . 'cliente_' . $id_cat_estados . '.gif';
$it = '../img/Fondo-login.png';
if (file_exists($NombrePng)) {
    $it = $NombrePng;
}

if (file_exists($NombreJpg)) {
    $it = $NombreJpg;
}

if (file_exists($NombreSvg)) {
    $it = $NombreSvg;
}

if (file_exists($NombreGif)) {
    $it = $NombreGif;
}
?>
<!--=================== -->
<!DOCTYPE html>
<html lang="en">

    <head>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Connections - Plataforma Integral para Laboratorios Clínicos.">
        <meta name="keywords" content="Inicio de Sesion">
        <meta name="author" content="Connections">
        <link rel="icon" href="../img/favicon.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon"/>
        <title>Connections</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">

        <!-- Theme css -->
        <link rel="stylesheet" type="text/css" href="assets/css/form-style.css">
        <link rel="stylesheet" type="text/css" href="assets/css/responsive-form-style.css">
    </head>
    <body>
        <div class="login-page">
            <div id="particles-js"></div>
            <div class="form-position">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">

                        	
							
							<div class="login-form">
                            <form class="needs-validation" action="controller/Acceso?opc=login" method="post" novalidate="">
								  <div class="imgcontainer">
									 <object data="assets/images/logo.svg" width="350" height="300"> </object>
								   </div>
							       <div class="input-control">
									<input type="text" placeholder="Usuario" id="user" name="user" required>
									<span class="password-field-show">
										<input type="password" placeholder="Contraseña" name="pass" id="pass" class="password-field" value="" required>
										<span data-toggle=".password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
									</span>

                                    <div class="text-danger text-center">

                                        <?php
                                        if (isset($_REQUEST["error"]))
                                           echo  $_REQUEST["error"] != "" ? "Usuaio y/o Contraseña incorrecto" : "";
                                            ?>
                                    </div>
								
									
							        <div class="login-btns">
                                    <button type="submit">Login</button>
							      		
									</div>
                                  </form>
							      	<div class="division-lines"><p style="align:center;">Connections © Copyright <?php echo date("Y"); ?></p></div>
							      	<div class="login-with-btns">
							      		
							      	
									</div>
								</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>
<!--Particulas-->
<script src="assets/js/particles.min.js"></script>
<script src="assets/js/app.js"></script>
<!-- Custom js-->
<script src="assets/js/login-script.js"></script>
<!-- theme particles script -->
<script src="assets/js/particles.min.js"></script>
<script src="assets/js/app.js"></script>

<script src="assets/js/index.js"></script>


</body>
</html>
