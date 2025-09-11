<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once('model/Catalogos.php');

$catalogos = new Catalogos();
$secciones = $catalogos->getSeccionesAgenda($id_sucursal);

//mensaje de operacion exitosa
$msg = $_REQUEST["msg"];

//Información para vistas
$page_title = "Agenda";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/admision/agenda.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>

<!-- jQuery UI -->
<script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- Select2 -->
<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<!-- Select2 -->
<script src="assets/plugins/select2/js/select2.full.min.js"></script>

<!-- fullCalendar -->
<link rel="stylesheet" href="assets/plugins/fullcalendar/main.css">
<!-- fullCalendar 2.2.5 -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/fullcalendar/main.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<script src="assets/plugins/toastr/toastr.min.js"></script>

<link rel="stylesheet" href="../assets/css/style.css">
<script src="assets/js/admision/agenda.js"></script>

<?php
//cerrar conexion
$catalogos->close();
?>

