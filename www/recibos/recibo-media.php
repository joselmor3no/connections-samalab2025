<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$codigo = $_REQUEST["codigo"];

if ($_SESSION['ruta'] == 'lindavista' || $_SESSION['ruta'] == 'sanmiguel' || $_SESSION['ruta'] == 'qa' )
    include('recibo-media-lindavista.php');
else if ( $_SESSION['ruta'] == 'adn' )
    include('recibo-media-adn.php');
else
    include('recibo-media-general.php');

