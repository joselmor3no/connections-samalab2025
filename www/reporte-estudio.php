<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$no_estudio = $_REQUEST["estudio"];
$fecha_inicial = $_REQUEST["ini"];
$fecha_final = $_REQUEST["fin"];

require_once('model/laboratorio/Reportes.php');

//mensaje de operacion exitosa
$msg = $_REQUEST["msg"];

$reporte = new Reportes();
$estudios = $reporte->getOrdenNoEstudio($no_estudio, $id_sucursal, $fecha_inicial, $fecha_final);
//var_dump($estudios);

//Información para vistas
$page_title = "Reporte esudio";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/laboratorio/reporte-estudio.php");
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

<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/laboratorio/reporte.js"></script>

<?php
//cerrar conexion
$reporte->close();
?>


