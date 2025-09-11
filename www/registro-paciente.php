<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once('model/Catalogos.php');
require_once('model/catalogos/Empresas.php');
require_once('model/catalogos/Doctores.php');
require_once('model/catalogos/Estructuras.php');

$empresas = new Empresas();
$lista_empresas = $empresas->getEmpresas($id_sucursal);

$doctores = new Doctores();
$lista_doctores = $doctores->getDoctores($id_sucursal);

$estructura = new Estructuras();
$descuentos = $estructura->getDescuentos($id_sucursal);

$catalogos = new Catalogos();
$cfdi = $catalogos->getUsoCfdi();

$sucursal = $catalogos->getSucursal($id_sucursal)[0];

//Información para vistas
$page_title = "Registro de Pacientes";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/admision/registro-paciente.php" );
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

<!-- Select2 -->
<script src="assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">

<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>

<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/admision/registro-paciente.js?v=1.2"></script>



<?php
//cerrar conexion
$empresas->close();
$doctores->close();
$estructura->close();
 
?>


