<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$id_estudio = $_REQUEST["id"];

require_once('model/Catalogos.php');
require_once('model/catalogos/Estudios.php');
require_once('model/catalogos/Componentes.php');

$catalogos = new Catalogos();
$reporte = $catalogos->getTipoReporte();
$indicaciones = $catalogos->getIndicaciones($id_sucursal);
$referencia = $catalogos->getReferencia($id_sucursal);
$cat_componentes = $catalogos->getCatComponentes();

$lista_clases_estudio=$catalogos->getClasesEstudio();


$estudios = new Estudios();
$estudio = $estudios->getEstudio($id_estudio, $id_sucursal);
$componentesConnections = $estudios->getComponentesConnections($id_estudio);

$componets = new Componentes();
$componentes = $componets->getComponentes($id_estudio, $id_sucursal);

//mensaje de operacion exitosa
if(isset($_REQUEST["msg"]))
  $msg = $_REQUEST["msg"];
else
  $msg="";

//Información para vistas
$page_title = "Estudio";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/catalogos/estudio.php" );
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

<!-- Summernote -->
<link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.min.css">
<script src="assets/plugins/summernote/summernote-bs4.min.js"></script>

<!-- TableDnD -->
<script src="assets/plugins/tablednd/jquery.tablednd.js"></script>

<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/catalogos/estudios.js"></script>

<?php
//cerrar conexion
//$doctores->close(); lo comente porque causa un fatal error
$catalogos->close();
$componentes->close();
?>


