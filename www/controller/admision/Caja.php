<?php

require_once('../../model/admision/Pagos.php');
require_once('../../model/admision/Gastos.php');
require_once('../../model/admision/Cortes.php');

class Caja {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'pagos') {
            $this->pagos();
        } else if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'registro-gasto') {
            $this->registroGasto();
        } else if ($opc == 'corte') {
            $this->corte();
        } else if ($opc == 'delete-pago') {
            $this->deletePago();
        }
    }

    function pagos() {

        $codigo = $_REQUEST["codigo"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $data = [];
        $pagos = new Pagos();
        $datos = $pagos->getPagos($codigo, $id_sucursal); 
        foreach ($datos AS $row) {
            $data = $row;
        }

        $pagos->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function registro() {

        $data = array(
            "id_orden" => $_REQUEST["id_orden"],
            "pago" => $_REQUEST["pago"],
            "aclaraciones" => $_REQUEST["aclaraciones"],
            "id_forma_pago" => $_REQUEST["id_forma_pago"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );
        $pagos = new Pagos();
        $pagos->addPago($data);
        $pagos->close();

        header("Location: /caja?msg=ok&codigo=" . $_REQUEST["codigo"]);
    }

    function registroGasto() {

        $data = array(
            "concepto" => $_REQUEST["concepto"],
            "importe" => $_REQUEST["importe"],
            "aclaracion" => $_REQUEST["aclaracion"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );
        $gastos = new Gastos();
        $gastos->addGasto($data);
        $gastos->close();

        header("Location: /gastos?msg=ok");
    }

    function corte() {

        $data = array(
            "no_corte" => $_REQUEST["no_corte"],
            "egresos" => $_REQUEST["egresos"],
            "ingresos" => $_REQUEST["ingresos"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );
        $corte = new Cortes();
        $corte->addCorte($data);
        $corte->close();

        header("Location: /corte?msg=ok");
    }

    function deletePago() {

        $id_pago = $_REQUEST["id_pago"];

        $pagos = new Pagos();
        $pagos->deletePagos($id_pago);

        $pagos->close();
        header("Location: /desaplicacion?msg=ok");
    }

}

new Caja();

