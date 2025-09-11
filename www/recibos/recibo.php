<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$codigo = $_REQUEST["codigo"];

if ($_SESSION['ruta'] == 'analizate')
    include('recibo-analizate.php');
elseif ($_SESSION['ruta'] == 'central' || $_SESSION['ruta'] == 'savi')
    include('recibo-central.php');
elseif ($_SESSION['ruta'] == "vilar" && $id_sucursal == 141 )
    include('recibo-xico.php');
else
    include('recibo-general.php');



   
