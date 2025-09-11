<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once('model/laboratorio/Reportes.php');

$reporte = new Reportes();

if (count($_POST) > 0) {
    $codigo = $_REQUEST["codigo"];
    $fecha_inicial = $_REQUEST["fecha_inicial"];
    $fecha_final = $_REQUEST["fecha_final"];


    $laboratorio = $reporte->getLaboratorio($codigo, $fecha_inicial, $fecha_final, $id_sucursal);
    if ($codigo == "") {
        header("Location: reporte-global?ini=" . $fecha_inicial . "&fin=" . $fecha_final);
    } else if ($laboratorio["tipo"] == "estudio") {

        header("Location: reporte-estudio?estudio=" . $laboratorio["estudio"]->no_estudio . "&ini=" . $fecha_inicial . "&fin=" . $fecha_final);
    }
}

//Información para vistas
$page_title = "Reporte de laboratorio";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/laboratorio/reporte-laboratorio.php");
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");

//cerrar conexion
$reporte->close();
