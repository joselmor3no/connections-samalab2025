<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once('model/Catalogos.php');
require_once('model/laboratorio/Reportes.php');

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursales($id_sucursal);

$reporte = new Reportes();

if (count($_POST) > 0) {
    $codigo = $_REQUEST["codigo"];
    $id_sucursal = $_REQUEST["id_sucursal"];
    $fecha_inicial = $_REQUEST["fecha_inicial"];
    $fecha_final = $_REQUEST["fecha_final"];

    $laboratorio = $reporte->getLaboratorio($codigo, $fecha_inicial, $fecha_final, $id_sucursal);
  
}

//Información para vistas
$page_title = "Reporte de laboratorio sucursales";
$usuario = $_SESSION["usuario"];

require("view/_blocks/header.php");
require("view/_blocks/menu.php");
require("view/_blocks/navbar.php");
require("view/laboratorio/reporte-laboratorio-sucursales.php");
require("view/_blocks/copy.php");
require("view/_blocks/footer.php");

//cerrar conexion
$reporte->close();
