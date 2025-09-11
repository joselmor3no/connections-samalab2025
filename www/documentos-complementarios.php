<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];


require_once('model/Catalogos.php');
require_once('model/laboratorio/Reportes.php');

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursales($id_sucursal);
$reporte = new Reportes();
$documentosComplementarios=$catalogos->getComplementarios(); 



$fecha_inicial=Date('Y-m-d');
$fecha_final=Date('Y-m-d');

if (count($_POST) > 0) {
    $id_orden = $_REQUEST["id_orden"];
    $fecha_inicial = $_REQUEST["fecha_inicial"];
    $fecha_final = $_REQUEST["fecha_final"];
}
$tablaPacientes = $reporte->getPacientesTabla($id_orden, $fecha_inicial, $fecha_final, $id_sucursal); 
 
//Información para vistas
$page_title = "Documentos Complementarios";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/laboratorio/documentos-complementarios.php");
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");

//cerrar conexion
$reporte->close(); 
?>

<!-- Select2 -->
<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


<!-- DataTables  & Plugins -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>



<script src="assets/js/laboratorio/complementarios.js"></script>
<!-- Select2 -->
<script src="assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">

<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>

<link rel="stylesheet" href="assets/css/style.css">