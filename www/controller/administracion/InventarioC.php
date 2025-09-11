<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/model/administracion/Inventario.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/administracion/Proveedores.php');

class InventarioC {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'productos') {
            $this->productos();
        } else if ($opc == 'registro-proveedor') {
            $this->addProveedor();
        } else if ($opc == 'registro-vale') {
            $this->registroVale();
        } else if ($opc == 'reportes') {
            $this->reportes();
        } else if ($opc == 'registro-toma') {
            $this->registroToma();
        }else if ($opc == 'registro-tipo-proveedor') {
            $this->addTipoProveedor();
        } 
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
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $proveedores = new Proveedores();
        if ($_REQUEST["id_proveedor"] == "") {
            $proveedores->addProveedor($data);
        } else {
            $proveedores->editProveedor($data);
        }

        $proveedores->close();
        header("Location: /proveedores?msg=ok");
    }

    function productos() {

        $producto = $_REQUEST["producto"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $inventario = new Inventario();
        $data = $inventario->productoDescripcion($producto, $id_sucursal);
        $datos = [];
        foreach ($data AS $row) {
            if ($row->nombre != "") {
                $datos[] = array(
                    "value" => $row->consecutivo,
                    "label" => $row->nombre,
                    "id" => $row->id,
                    "presentacion" => $row->presentacion,
                    "unidad" => $row->unidad,
                    "unidad_egreso" => $row->unidad_egreso,
                    "caducidad" => $row->caducidad,
                    "existencia" => $row->existencia,
                );
            }
        }

        $inventario->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

    function registroVale() {

        $data = array(
            "observaciones" => $_REQUEST["observaciones"],
            "factura" => $_REQUEST["factura"],
            "id_proveedor" => $_REQUEST["id_proveedor"] != "" ? $_REQUEST["id_proveedor"] : "NULL",
            "codigo" => $_REQUEST["codigo"],
            "marca" => $_REQUEST["marca"],
            "ingreso" => $_REQUEST["ingreso"],
            "egreso" => $_REQUEST["egreso"],
            "precio" => $_REQUEST["precio"],
            "caducidad" => $_REQUEST["caducidad"],
            "iva" => $_REQUEST["iva"],
            "descuento" => $_REQUEST["descuento"],
            "subtotal" => $_REQUEST["subtotal"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
            "id_sucursal_salida" => $_REQUEST["id_sucursal_salida"],
        );

        $inventario = new Inventario();
        if ($_REQUEST["movimiento"] == "Entrada") {
            $inventario->addValeEntrada($data);
            header("Location: /inventario-entrada?msg=ok");
        } else if ($_REQUEST["movimiento"] == "Salida") {
            $inventario->addValeSalida($data);
            header("Location: /inventario-salida?msg=ok");
        }
    }

    function reportes() {

        $reporte = $_REQUEST["reporte"];
        $fecha_ini = $_REQUEST["fecha_ini"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $inventario = new Inventario();
        if ($reporte == "entradas")
            $datos = $inventario->getValeEntradas($fecha_ini, $fecha_fin, $id_sucursal);
        else if ($reporte == "salidas")
            $datos = $inventario->getValeSalidas($fecha_ini, $fecha_fin, $id_sucursal);


        $inventario->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

    function registroToma() {

        $data = array(
            "codigo" => $_REQUEST["codigo"],
            "existencia" => $_REQUEST["existencia"],
            "conteo" => $_REQUEST["conteo"],
            "diferencia" => $_REQUEST["diferencia"],
            "subtotal" => $_REQUEST["subtotal"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $inventario = new Inventario();

        $inventario->addTomaInventario($data);
        header("Location: /toma-inventario?msg=ok");
    }
    
    function addTipoProveedor() {

        $data = array(
            "nombre" => $_REQUEST["nombre"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $proveedores = new Proveedores();
        $proveedores->addTipoProveedor($data);
        $datos = $proveedores->getTipoProveedor($_REQUEST["id_sucursal"]);

        $proveedores->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

}

new InventarioC();

