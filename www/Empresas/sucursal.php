<?php
session_start();
$id_cliente = $_SESSION["id_cliente"];

require_once('../model/Catalogos.php');
require_once('model/Sucursales.php');

$catalogos = new Catalogos();
$estados = $catalogos->getEstados();

$id_sucursal = $_REQUEST["id"];
$sucursales = new Sucursales();
$cliente = $sucursales->getCliente($id_cliente)[0];
$datos_sucursales = $sucursales->getSucursales($id_cliente);
$sucursal = $sucursales->getSucursal($id_sucursal);
$consecutivo = $sucursales->getConsecutivoSucursal($id_cliente);
$municipios = $catalogos->getMunicipios($sucursal[0]->estado);

if (!($cliente->max_sucursales > count($datos_sucursales)) && $id_sucursal == "") {
    header("Location: /Empresas/sucursales");
}

//Información para vistas
$page_title = "Sucursal";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/sucursal.php");
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>


<!-- Select2 -->
<link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="../assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="../assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
<script src="../assets/plugins/toastr/toastr.min.js"></script>

<!-- bs-custom-file-input -->
<script src="../assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>


<link rel="stylesheet" href="../assets/css/style.css">
<script src="js/sucursales.js"></script>

<?php
//cerrar conexion
$sucursales->close();
$catalogos->close();
?>