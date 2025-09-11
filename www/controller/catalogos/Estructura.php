<?php

require_once('../../model/catalogos/Estructuras.php');

class Estructura {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'alias-descuento') {
            $this->aliasDescuento();
        } else if ($opc == 'delete-descuento') {
            $this->deleteDescuento();
        } else if ($opc == 'registro-descuento') {
            $this->registroDescuento();
        } else if ($opc == 'alias-forma-pago') {
            $this->aliasFormaPago();
        } else if ($opc == 'delete-forma-pago') {
            $this->deleteFormaPago();
        } else if ($opc == 'registro-forma-pago') {
            $this->registroFormaPago();
        } else if ($opc == 'delete-indicacion') {
            $this->deleteIndicacion();
        } else if ($opc == 'registro-indicacion') {
            $this->registroIndicacion();
        } else if ($opc == 'delete-referencia') {
            $this->deleteReferencia();
        } else if ($opc == 'registro-referencia') {
            $this->registroReferencia();
        } else if ($opc == 'registro-bonificacion') {
            $this->registroBonificacion();
        }
    }

    function aliasDescuento() {

        $alias = $_REQUEST["alias"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $estructuras = new Estructuras();
        $data = $estructuras->aliasDescuento($alias, $id_sucursal);

        $estructuras->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function deleteDescuento() {

        $id = $_REQUEST["id"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $estructuras = new Estructuras();
        $estructuras->deleteDescuento($id, $id_sucursal);

        $estructuras->close();
        header("Location: /estructura-descuentos?msg=ok");
    }

    function registroDescuento() {
        $data = array(
            "id" => $_REQUEST["id"],
            "codigo" => $_REQUEST["codigo"],
            "nombre" => $_REQUEST["nombre"],
            "descuento" => $_REQUEST["porcentaje"],
            "autorizacion" => $_REQUEST["autorizacion"] == "on" ? "1" : "0",
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $estructuras = new Estructuras();
        if ($_REQUEST["id"] == "") {
            $estructuras->addDescuento($data);
        } else {
            $estructuras->editDescuento($data);
        }

        $estructuras->close();
        header("Location: /estructura-descuentos?msg=ok");
    }

    function aliasFormaPago() {

        $alias = $_REQUEST["alias"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $estructuras = new Estructuras();
        $data = $estructuras->aliasFormaPago($alias, $id_sucursal);

        $estructuras->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function deleteFormaPago() {
        $id = $_REQUEST["id"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $estructuras = new Estructuras();
        $estructuras->deleteFormaPago($id, $id_sucursal);

        $estructuras->close();
        header("Location: /estructura-formas-pago?msg=ok");
    }

    function registroFormaPago() {

        $data = array(
            "id" => $_REQUEST["id"],
            "codigo" => $_REQUEST["codigo"],
            "nombre" => $_REQUEST["nombre"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $estructuras = new Estructuras();
        if ($_REQUEST["id"] == "") {
            $estructuras->addFormaPago($data);
        } else {
            $estructuras->editFormaPago($data);
        }

        $estructuras->close();
        header("Location: /estructura-formas-pago?msg=ok");
    }

    function deleteIndicacion() {
        $id = $_REQUEST["id"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $estructuras = new Estructuras();
        $estructuras->deleteIndicacion($id, $id_sucursal);

        $estructuras->close();
        header("Location: /estructura-indicaciones?msg=ok");
    }

    function registroIndicacion() {

        $data = array(
            "id" => $_REQUEST["id"],
            "nombre" => $_REQUEST["nombre"],
            "indicacion" => $_REQUEST["indicacion"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $estructuras = new Estructuras();
        if ($_REQUEST["id"] == "") {
            $estructuras->addIndicacion($data);
        } else {
            $estructuras->editIndicacion($data);
        }

        $estructuras->close();
        header("Location: /estructura-indicaciones?msg=ok");
    }

    function deleteReferencia() {
        $id = $_REQUEST["id"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $estructuras = new Estructuras();
        $estructuras->deleteReferencia($id, $id_sucursal);

        $estructuras->close();
        header("Location: /estructura-referencias?msg=ok");
    }

    function registroReferencia() {

        $data = array(
            "id" => $_REQUEST["id"],
            "codigo" => $_REQUEST["codigo"],
            "nombre" => $_REQUEST["nombre"],
            "direccion" => $_REQUEST["direccion"],
            "ciudad" => $_REQUEST["ciudad"],
            "id_cat_estado" => $_REQUEST["id_cat_estado"] == "" ? 'NULL' : $_REQUEST["id_cat_estado"],
            "cp" => $_REQUEST["cp"],
            "telefono" => $_REQUEST["telefono"],
            "email" => $_REQUEST["email"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $estructuras = new Estructuras();
        if ($_REQUEST["id"] == "") {
            $estructuras->addReferencia($data);
        } else {
            $estructuras->editReferencia($data);
        }

        $estructuras->close();
        header("Location: /estructura-referencias?msg=ok");
    }

    function registroBonificacion() {

        $data = array(
            "id_departamento" => $_REQUEST["id_departamento"],
            "monedero" => $_REQUEST["monedero"],
            "aumento" => $_REQUEST["aumento"],
            "descuento" => $_REQUEST["descuento"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $estructuras = new Estructuras();
        $estructuras->addBonificacion($data);
        $estructuras->close();
        header("Location: /estructura-bonificacion?msg=ok");
    }

}

new Estructura();

