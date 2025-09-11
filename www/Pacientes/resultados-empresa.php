<?php
session_start();

$id_empresa = $_SESSION["id_empresa"];
if ($id_empresa == "") {
    header("Location: /Pacientes");
}

require_once('../model/Catalogos.php');
require_once('model/Resultados.php');

$resultados = new Resultados();
$ordenes = $resultados->getOrdenesEmpresa($id_empresa);
$sucursal = $resultados->getSucursalEmpresa($id_empresa)[0];


//var_dump($ordenes);
//var_dump($sucursal);
//Información para vistas
$page_title = "Resultados";
$usuario = $_SESSION["empresa"];

require_once('controller/imagen.php'); 
$contoladorImagen=new Imagen(); 

require("view/_blocks/header.php");
require("view/_blocks/menu-empresa.php");
require("view/_blocks/navbar.php");
require("view/resultados-empresa.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>

<!-- DataTables -->
<link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- DataTables  & Plugins -->
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../assets/plugins/jszip/jszip.min.js"></script>
<script src="../assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="js/resultados.js"></script>
