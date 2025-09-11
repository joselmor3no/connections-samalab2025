<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once('model/Home.php');

$home = new Home();
$ordenes = $home->getOrdenesDia($id_sucursal)[0]->total;
$cotizaciones = $home->getCotizacionDia($id_sucursal)[0]->total;
$resultados = $home->getResultados($id_sucursal);
$total = $resultados[0]->total;
$reportados = $resultados[0]->reportados;
$sinReportar = $total - $reportados;

$secciones = $home->getSeccionesOrdenDia($id_sucursal);
$horario = $home->getHorarioOrdenDia($id_sucursal);

foreach ($secciones AS $row)
    echo "<input type='hidden' class='estudios' data-estudio='$row->seccion' data-total='$row->total'>";

foreach ($horario AS $row)
    echo "<input type='hidden' class='horario' data-horario='$row->hora' data-total='$row->total'>";


//Información para vistas
$page_title = "Inicio";

if (isset($_SESSION["usuario"]))
    $usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/inicio.php" );
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");
?>

<!-- Toastr -->
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<script src="assets/plugins/toastr/toastr.min.js"></script>

<script src="assets/plugins/google-chart/loader.js"></script>
<!--script src="assets/js/home.js"></script-->
<script>

    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChart2);
    function drawChart() {
        var tmp = [];
        tmp.push(["Estudios", "total"]);
        var estudios = $(".estudios");
        for (var i = 0; i < estudios.length; i++) {
            tmp.push([estudios[i].dataset.estudio + " (" + estudios[i].dataset.total + ")", parseFloat(estudios[i].dataset.total)]);
        }

        if (estudios.length == 0) {
            tmp.push(["Sin estudios", 0]);
        }

        var data = google.visualization.arrayToDataTable(tmp);

        var options = {
            title: 'Estudios más vendidos durante el día',
            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }


    function drawChart2() {

        var tmp = [];
        tmp.push(["Horario", "Ventas"]);
        var horario = $(".horario");
        for (var i = 0; i < horario.length; i++) {
            tmp.push([horario[i].dataset.horario + ":00", parseFloat(horario[i].dataset.total)]);
        }
        if (horario.length == 0) {
            tmp.push(["00:00", 0]);
        }

        var data = google.visualization.arrayToDataTable(tmp);

        var options = {
            title: 'Horarios de ventas',
            hAxis: {title: 'Horario', titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }


</script>


<?php

//cerrar conexion
$home->close();
?>



