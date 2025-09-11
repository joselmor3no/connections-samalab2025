<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once('model/administracion/Proveedores.php');

$catalogos = new Catalogos();
$estados = $catalogos->getEstados();

$id_proveedor = $_REQUEST["id"];
$proveedores = new Proveedores();
$tipo = $proveedores->getTipoProveedor($id_sucursal);
$proveedor = $proveedores-> getProveedor($id_proveedor);
//var_dump($proveedor);

//Información para vistas
$page_title = "Proveedor";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/administracion/proveedor.php" );
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

<!-- bs-custom-file-input -->
<script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>


<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/administracion/inventario-entrada.js"></script>

<?php
//cerrar conexion
$proveedores->close();
?>