<?php
session_start();
$id_admin = $_SESSION["id_admin"];

require_once('model/Estructuras.php');

$id_recipiente = $_REQUEST["id"];
$estructuras = new Estructuras();
$consecutivo = $estructuras->consecutivoRecipiente();
$datos = $estructuras->getRecipiente($id_recipiente);

//Información para vistas
$page_title = "Recipiente Biológico";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/recipiente-biologico.php" );
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
<script src="js/estructura.js"></script>

<?php
//cerrar conexion
$estructuras->close();
?>


