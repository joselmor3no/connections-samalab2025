<?php
session_start();
$id_admin = $_SESSION["id_admin"];

require_once('../model/Catalogos.php');
require_once('model/Usuarios.php');

$catalogos = new Catalogos();
$rol = $catalogos->getRol();

$id_usuario = $_REQUEST["id"];
$usuarios = new Usuarios();
$usuario_ = $usuarios->getUsuarioId($id_usuario);

//Información para vistas
$page_title = "Usuario";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/usuario.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>


<!-- Select2 -->
<link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="../assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="../assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
<script src="../assets/plugins/toastr/toastr.min.js"></script>

<link rel="stylesheet" href="../assets/css/style.css">
<script src="js/usuarios.js"></script>

<?php
//cerrar conexion
$usuarios->close();
$catalogos->close();
?>


