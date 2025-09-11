<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$codigo = $_REQUEST["codigo"];

require_once('model/Catalogos.php');
require_once('model/laboratorio/Reportes.php');
require_once('model/admision/Pacientes.php');
require_once('model/catalogos/Empresas.php');
require_once('model/catalogos/Doctores.php');
require_once('model/catalogos/Estructuras.php');
require_once('model/admision/Pagos.php');

$empresas = new Empresas();
$lista_empresas = $empresas->getEmpresas($id_sucursal);

$doctores = new Doctores();
$lista_doctores = $doctores->getDoctores($id_sucursal);

$estructura = new Estructuras();
$descuentos = $estructura->getDescuentos($id_sucursal);

$reportes = new Reportes();
$id_orden = $reportes->getIdOrden($codigo, $id_sucursal);

$catalogos = new Catalogos();
$cfdi = $catalogos->getUsoCfdi();


if ($id_orden == "") {
    $orden = array();
    $detalle = array();
    $fiscal = array();
} else {
    $pacientes = new Pacientes();
    $data = $pacientes->getOrden($id_orden);
    $orden = $data["orden"][0];
    $detalle = $data["detalle"];
    $fiscal = $data["fiscal"][0];
}


$pagos = new Pagos();
$pago = $pagos->getPagos($codigo, $id_sucursal)[0];
$cubierto = $pago->importe - $pago->saldo_deudor;

$reportado = false;
for ($i = 1; $i <= count($detalle); $i++) {
    if ($detalle[$i - 1]->impresion > 0 || $detalle[$i - 1]->reportado > 0) {
        $reportado = true;
    }
}

//mensaje de operacion exitosa
$msg = $_REQUEST["msg"];

//Información para vistas
$page_title = "Modificacion de Pacientes";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/administracion/modificacion-paciente-admin.php" );
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
<script src="assets/js/admision/registro-paciente.js"></script>



<?php
//cerrar conexion
$empresas->close();
$doctores->close();
$estructura->close();
$reportes->close();
$pacientes->close()
?>


