<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once('model/Catalogos.php');
require_once('model/catalogos/Users.php');

//mensaje de operacion exitosa
$msg = $_REQUEST["msg"];


//Información para vistas
$page_title = "Dinero Electrónico";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/administracion/dinero.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>


<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<script src="assets/plugins/toastr/toastr.min.js"></script>

<script src="assets/js/administracion/dinero.js"></script>

<?php
//cerrar conexion

?>


