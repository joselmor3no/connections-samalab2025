<?php
session_start();
$id_admin = $_SESSION["id_admin"];

require_once('../model/Catalogos.php');
require_once('model/Empresas.php');

$catalogos = new Catalogos();
$estados = $catalogos->getEstados();

$id_empresa = $_REQUEST["id"];
$empresas = new Empresas();
$empresa = $empresas->getEmpresa($id_empresa);

//Información para vistas
$page_title = "Empresa";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/empresa.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>


<!-- Select2 -->
<link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="../assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="../assets/plugins/select2/js/select2.full.min.js"></script>

<!-- bs-custom-file-input -->
<script src="../assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
<script src="../assets/plugins/toastr/toastr.min.js"></script>

<link rel="stylesheet" href="../assets/css/style.css">
<script src="js/empresas.js"></script>

<?php
//cerrar conexion
$empresas->close();
$catalogos->close();
?>


