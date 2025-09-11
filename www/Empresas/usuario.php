<?php
session_start();
$id_cliente = $_SESSION["id_cliente"];

require_once('../model/Catalogos.php');
require_once('model/Sucursales.php');


$catalogos = new Catalogos();
$estados = $catalogos->getEstados();
//$sucursal = $catalogos->getSucursal($id_sucursal);

$id_usuario = $_REQUEST["id"];
$sucursales = new Sucursales();
$usuario_sucursal = $sucursales->getUsuario($id_usuario);
$lista_sucursales = $sucursales->getSucursales($id_cliente);
//var_dump($lista_sucursales);

//Información para vistas
$page_title = "Usuario";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/usuario.php");
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