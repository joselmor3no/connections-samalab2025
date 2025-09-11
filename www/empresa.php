<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$id_empresa = $_REQUEST["id"];

require_once('model/Catalogos.php');
require_once('model/catalogos/Empresas.php');


$catalogos = new Catalogos();
$estados = $catalogos->getEstados();
$lista_precios = $catalogos->getLitasPrecios($id_sucursal);
$sucursal = $catalogos->getSucursal($id_sucursal);


$empresas = new Empresas();
if(!empty($id_empresa)){
    $empresa = $empresas->getEmpresa($id_empresa); 
    $clasesEstudio=$empresas->getClasesEstudio();
    $clasesEstudioEmpresa=$empresas->getClasesEstudioEmpresa($id_empresa);
    $municipios = $catalogos->getMunicipios($empresa[0]->estado);
}



//Información para vistas
$page_title = "Empresas";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/catalogos/empresa.php" );
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

<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/catalogos/empresas.js"></script>

<?php
//cerrar conexion

$catalogos->close();
$empresas->close();
?>


