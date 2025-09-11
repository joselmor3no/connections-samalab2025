<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$id_lista = $_REQUEST["id"];


require_once('model/Catalogos.php');
require_once('model/catalogos/Listas.php');

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal);

$listas = new Listas();
$lista = $listas->getLista($id_lista);
$consecutivo_lista = $listas->consecutivoLista($id_sucursal);
$estudios_paquetes = $listas->getEstudiosPaquetesLista($id_lista, $id_sucursal);

//Información para vistas
$page_title = "Listas de Precios";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/catalogos/lista.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>

<!-- autocomplete 
Falta descargarlo y ver compatibilidad con jquery actual
-->

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Select2 -->
<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<script src="assets/plugins/toastr/toastr.min.js"></script>

<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/catalogos/listas.js"></script>

<?php
//cerrar conexion
$listas->close();
$catalogos->close();
?>


