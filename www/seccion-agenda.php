<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once('model/catalogos/Secciones.php');


if (isset($_REQUEST["id"])) {
    $id_seccion = $_REQUEST["id"];
    $secciones = new Secciones();
    $seccion = $secciones->getSeccion($id_seccion);
    
} else {
    $id_seccion = "";
}

//Información para vistas
$page_title = "Sección Agenda";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/catalogos/seccion.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>


<!-- Select2 -->
<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<script src="assets/plugins/toastr/toastr.min.js"></script>

<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/catalogos/secciones.js"></script>

<?php
//cerrar conexion
$secciones->close();
?>


