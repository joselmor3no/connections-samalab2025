<?php
session_start();
$id_usuario = $_SESSION["id"];
$id_sucursal = $_SESSION["id_sucursal"];

require('model/catalogos/Users.php');
require('model/catalogos/Estudios.php');
$estudios=new Estudios();
$listaEstudios=$estudios->getEstudiosActuales($id_sucursal);


$usuarios = new Users();
$permisos = $usuarios->getPermisosUsuario($id_usuario);
$listaUsuario=$usuarios->getUsuariosReporteGlobal($id_sucursal);  
$listaEmpresa=$usuarios->getEmpresasReporteGlobal($id_sucursal); 
$listaDoctor=$usuarios->getDoctoresReporteGlobal($id_sucursal); 
$listaFormaP=$usuarios->getFormaPagoReporteGlobal($id_sucursal); 
$empresa=-1;
$usuario=-1;
$doctor=-1;
$estatus=-1;
$tipop=-1;
$respuestaRG=$usuarios->obtenerReporteGlobal(date("Y-m-d"),date("Y-m-d"),$id_sucursal,$empresa,$usuario,$doctor,$estatus,$tipop); 
$respuestaRGC=$usuarios->obtenerReporteGlobalCaja(date("Y-m-d"),date("Y-m-d"),$id_sucursal,$usuario);
$respuestaRGCP=$usuarios->obtenerReporteGlobalCajaPago(date("Y-m-d"),date("Y-m-d"),$id_sucursal,$usuario);



//----------------------------------------------------
//mensaje de operacion exitosa
$msg = $_REQUEST["msg"];

//Información para vistas
$page_title = "Reportes";
$usuario = $_SESSION["usuario"]; 

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
include "view/administracion/reportes.php";
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

<script src="assets/js/administracion/reporteador.js"></script>

<?php
//cerrar conexion
$usuarios->close();
?>

