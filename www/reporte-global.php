<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$fecha_inicial = $_REQUEST["ini"];
$fecha_final = $_REQUEST["fin"];
$codigo = $_REQUEST["codigo"];

require_once('model/laboratorio/Reportes.php');

//mensaje de operacion exitosa
$msg = $_REQUEST["msg"];

$reporte = new Reportes();
$pacientes = $reporte->getOrdenGlobal($id_sucursal, $fecha_inicial, $fecha_final);

//falta cuando haya guardado con el codigo
if ($codigo != "") {
    $id_orden = $reporte->getIdOrden($codigo, $id_sucursal);
    $paciente = $reporte->getOrdenPaciente($id_orden);
    $estudios = $reporte->getOrdenEstudio($id_orden);
}

//Información para vistas
$page_title = "Reporte Global";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/laboratorio/reporte-global.php");
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>

<!-- TableDnD -->
<script src="assets/plugins/tablednd/jquery.tablednd.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<script src="assets/plugins/toastr/toastr.min.js"></script>

<!-- Summernote -->
<link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.min.css">
<script src="assets/plugins/summernote/summernote-bs4.min.js"></script>

<!-- bs-custom-file-input -->
<script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>


<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/laboratorio/reporte.js"></script>

<?php
//cerrar conexion
$reporte->close();
?>


