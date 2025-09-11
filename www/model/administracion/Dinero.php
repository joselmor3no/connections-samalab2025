<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');

class Dinero {

    private $conexion;

    function __construct() {
        //Validación de session
        $this->conexion = new Conexion();
    }

    function addTarjeta($tarjeta, $saldo, $id_sucursal) {

        $sql = "INSERT INTO tarjeta (saldo, numero, id_cliente) SELECT $saldo, '$tarjeta', id_cliente FROM sucursal WHERE id = $id_sucursal";
        $this->conexion->setQuery($sql);
    }

}

?>