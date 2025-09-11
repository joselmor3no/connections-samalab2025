<?php

require_once('model/Conexion.php');

session_start();
$id_sucursal = $_SESSION["id_sucursal"];

$conexion = new Conexion();

$sql = "SELECT * 
       FROM paquete 
       WHERE activo = 1 AND id_sucursal = $id_sucursal";
$query = $conexion->getQuery($sql);

echo 'ACTUALIZACIÓN DE PAQUETES<br><br>';
$i=0;
foreach ($query AS $row) {
    $id_paquete = $row->id;
    $precio_paquete = $row->precio;

    $sql = "SELECT SUM(estudio.precio_publico) AS total
    FROM paquete_estudio 
    INNER JOIN estudio ON (estudio.id_cat_estudio = paquete_estudio.id_estudio AND estudio.id_sucursal = $id_sucursal)
    WHERE paquete_estudio.id_paquete = $id_paquete";
    $precio_publico = $conexion->getQuery($sql)[0]->total;

    $porc = $precio_paquete / $precio_publico;

    $sql = "UPDATE paquete_estudio
    INNER JOIN estudio ON (estudio.id_cat_estudio = paquete_estudio.id_estudio AND estudio.id_sucursal = $id_sucursal)
    SET paquete_estudio.precio_neto = estudio.precio_publico*$porc
    WHERE paquete_estudio.id_paquete = $id_paquete";
    $conexion->setQuery($sql);

    echo $row->nombre."<br>";
}

echo '<br><br>ACTUALIZACIÓN LISTA DE PRECIOS<br><br>';

//estudios
$sql = "UPDATE lista_precios_estudio
INNER JOIN lista_precios ON (lista_precios.id = lista_precios_estudio.id_lista_precio)
INNER JOIN estudio ON (estudio.id_cat_estudio = lista_precios_estudio.id_estudio AND estudio.id_sucursal = $id_sucursal)
SET lista_precios_estudio.precio_publico = estudio.precio_publico
WHERE lista_precios.id_sucursal = $id_sucursal AND lista_precios_estudio.activo=1";
$conexion->setQuery($sql);


//paquete
$sql = "UPDATE lista_precios_estudio
INNER JOIN lista_precios ON (lista_precios.id = lista_precios_estudio.id_lista_precio)
INNER JOIN paquete ON (paquete.id = lista_precios_estudio.id_paquete)
SET lista_precios_estudio.precio_publico = paquete.precio
WHERE lista_precios.id_sucursal = $id_sucursal AND lista_precios_estudio.activo=1";
$conexion->setQuery($sql);

?>

