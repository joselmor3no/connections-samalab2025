<?php

require_once('../model/Proveedores.php');
require_once('../model/Productos.php');

class Inventario {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'registro-tipo-proveedor') {
            $this->addTipoProveedor();
        } else if ($opc == 'registro-proveedor') {
            $this->addProveedor();
        } else if ($opc == 'registro-producto') {
            $this->addProducto();
        } else if ($opc == 'delete-producto-combo') {
            $this->deleteCombo();
        } else if ($opc == 'registro-combo') {
            $this->addCombo();
        } else if ($opc == 'registro-combo-producto') {
            $this->addComboProducto();
        } else if ($opc == 'registro-presentacion') {
            $this->addPresentacionProducto();
        } else if ($opc == 'registro-unidad') {
            $this->addUnidadProducto();
        }
    }

    function addTipoProveedor() {

        $data = array(
            "nombre" => $_REQUEST["nombre"],
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        $proveedores = new Proveedores();
        $proveedores->addTipoProveedor($data);
        $datos = $proveedores->getTipoProveedor($_REQUEST["id_cliente"]);

        $proveedores->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

    function addProveedor() {

        $data = array(
            "id" => $_REQUEST["id_proveedor"],
            "nombre" => $_REQUEST["nombre"],
            "id_estado" => $_REQUEST["id_estado"],
            "id_municipio" => $_REQUEST["id_municipio"],
            "contacto" => $_REQUEST["contacto"],
            "telefono" => $_REQUEST["telefono"],
            "id_tipo_proveedor" => $_REQUEST["id_tipo_proveedor"],
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        $proveedores = new Proveedores();
        if ($_REQUEST["id_proveedor"] == "") {
            $proveedores->addProveedor($data);
        } else {
            $proveedores->editProveedor($data);
        }

        $proveedores->close();
        header("Location: /Empresas/proveedores?msg=ok");
    }

    function addProducto() {

        $data = array(
            "id" => $_REQUEST["id_producto"],
            "nombre" => $_REQUEST["nombre"],
            "id_presentacion_producto" => $_REQUEST["id_presentacion_producto"],
            "cantidad" => $_REQUEST["cantidad"],
            "id_unidad_producto" => $_REQUEST["id_unidad_producto"],
            "stock_min" => $_REQUEST["stock_min"],
            "cant_pruebas" => $_REQUEST["cant_pruebas"],
            "id_departamento_producto" => $_REQUEST["id_departamento_producto"],
            "id_cliente" => $_REQUEST["id_cliente"],
        );


        $productos = new Productos();
        if ($_REQUEST["id_producto"] == "") {
            $productos->addProducto($data);
        } else {
            $productos->editProducto($data);
        }

        $productos->close();
        header("Location: /Empresas/productos?msg=ok");
    }

    function deleteCombo() {

        $id = $_REQUEST["id"];

        $productos = new Productos();
        $productos->deleteCombo($id);

        $productos->close();
        header("Location: /Empresas/combos-productos?msg=ok");
    }

    function addCombo() {

        $data = array(
            "cantidad" => $_REQUEST["cantidad"],
            "id_estudio" => $_REQUEST["id_estudio"],
            "id_producto" => $_REQUEST["id_producto"],
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        $productos = new Productos();
        $productos->addCombo($data);

        $productos->close();
        header("Location: /Empresas/combos-productos?msg=ok");
    }

    function addComboProducto() {

        $data = array(
            "cantidad" => $_REQUEST["cantidad"],
            "id_estudio" => $_REQUEST["id_estudio"],
            "id_producto" => $_REQUEST["id_producto"],
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        $productos = new Productos();
        $productos->addComboProducto($data);

        $productos->close();
        header("Location: /Empresas/combos-productos?msg=ok");
    }

    function addPresentacionProducto() {

        $data = array(
            "nombre" => $_REQUEST["nombre"],
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        $productos = new Productos();
        $productos->addPresentacionProducto($data);
        $datos = $productos->getPresentacion();

        $productos->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }
    
    function addUnidadProducto() {

        $data = array(
            "nombre" => $_REQUEST["nombre"],
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        $productos = new Productos();
        $productos->addUnidadProducto($data);
        $datos = $productos->getUnidades();

        $productos->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

}

new Inventario();

