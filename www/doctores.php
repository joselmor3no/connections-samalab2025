<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];
//echo Date('Y-m-d h:i:s');
require_once('model/catalogos/Doctores.php');
require_once('model/Catalogos.php');
$doctores = new Doctores();
$promotor=$zona=$tipo=$prefijo=null;
$alias=-1;
$texcoco=1;
if(isset($_REQUEST['tex']))
	$texcoco=$_REQUEST['tex'];
if($id_sucursal!=121)
	$texcoco=0;
if(isset($_REQUEST['fpromotores'])){
	$promotor=$_REQUEST['fpromotores']; 
	$zona=$_REQUEST['fzonas'];
	$alias=$_REQUEST['falias'];
	$tipo=$_REQUEST['ftipo'];
	$prefijo=$_REQUEST['prefijo'];
		$datos = $doctores->getDoctoresFiltros($id_sucursal,$promotor,$zona,$alias,$tipo,$prefijo);
}
else{
	$promotor=$zona=$alias=$tipo=$prefijo=null;
	$datos = $doctores->getDoctoresFiltros($id_sucursal,$promotor,$zona,$alias,$tipo,$prefijo);
}
$prefijos=$doctores->getPrefijos();


$listaPromotores= $doctores->getPromotores($id_sucursal);
$listaZonas= $doctores->getZonas($id_sucursal);

$catalogos=new Catalogos();
$listaEspecialidades = $catalogos->getEspecialidades(); 

//mensaje de operacion exitosa
$msg ="";
if(isset($_REQUEST["msg"]))
	$msg = $_REQUEST["msg"]; 

//Información para vistas
$page_title = "Doctores";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/catalogos/doctores.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>

<!-- DataTables -->
<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- DataTables  & Plugins -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jszip/jszip.min.js"></script>
<script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<script src="assets/plugins/toastr/toastr.min.js"></script>

<script src="assets/js/catalogos/doctores.js"></script>

<?php
//cerrar conexion
$doctores->close();
?>

