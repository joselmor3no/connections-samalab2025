<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once('model/administracion/Inventario.php');
require_once('model/Catalogos.php');

//mensaje de operacion exitosa
$msg = $_REQUEST["msg"];


$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];

$inventario = new Inventario();
$proveedores = $inventario->getProveedores($id_sucursal);
$vale = $inventario->getVale("ENTRADA", $id_sucursal);
$inventario->setExistenciaProductos($id_sucursal);
$productos = $inventario->productoDescripcion("", $id_sucursal);


//Información para vistas
$page_title = "Inventario Entrada";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/administracion/inventario-entrada.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Select2 -->
<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<script src="assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<script src="assets/plugins/toastr/toastr.min.js"></script>

<script src="assets/js/administracion/inventario-entrada.js"></script>

<?php
//cerrar conexion
$catalogos->close();
$inventario->close();
?>

<script type="text/javascript"  >


    $(document).ready(function () {
        if ($('.dataTable').html()) {
            $('.dataTable').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay informaci&oacute;n",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ resultados",
                    "infoEmpty": "Mostrando 0 a 0 de 0 resultados",
                    "infoFiltered": "(Filtrado de _MAX_ total resultados)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Resultados",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "&Uacute;ltimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                initComplete: function () {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                },
                "searching": true,
                // "autoFill": true,
                order: [[0, "asc"]]
            });
        }
    });
</script>


