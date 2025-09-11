<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/model/administracion/Dinero.php');

class Monedero {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'registro') {
            $this->registro();
        }
    }

    function registro() {
        //var_dump($_REQUEST);
        $no_tarjeta = $_REQUEST["no_tarjeta"];
        $monto = $_REQUEST["monto"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $dinero = new Dinero();
        $dinero->addTarjeta($no_tarjeta, $monto, $id_sucursal);

        header("Location: /dinero-electronico?msg=ok");
    }

}

new Monedero();

