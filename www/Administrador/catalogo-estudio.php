<?php
session_start();
$id_admin = $_SESSION["id_admin"];

require_once('../model/Catalogos.php');
require_once('model/Estudios.php');

$catalogos = new Catalogos();
$reporte = $catalogos->getTipoReporte();
$departamentos = $catalogos->getDepartamentos();
$secciones = $catalogos->getSecciones();
$materia = $catalogos->getMateriaBiologica();
$recipiente = $catalogos->getRecipienteBiologico();

$id_estudio = $_REQUEST["id"];
$estudios = new Estudios();
$estudio = $estudios->getEstudio($id_estudio);
//var_dump($estudio);

//Información para vistas
$page_title = "Estudio";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/estudio.php" );
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

<link rel="stylesheet" href="../assets/css/style.css">
<script src="js/estudios.js"></script>

<?php
//cerrar conexion
$estudios->close();
$catalogos->close();
?>


