<?php

require_once('../../model/catalogos/Listas.php');

class Lista {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'alias') {
            $this->alias();
        } else if ($opc == 'estudios-paquetes') {
            $this->estudiosPaquetes();
        } else if ($opc == 'delete') {
            $this->delete();
        } else if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'add-estudio') {
            $this->addEstudio();
        } else if ($opc == 'delete-estudio') {
            $this->deleteEstudio();
        }
    }

    function alias() {

        $alias = $_REQUEST["alias"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $listas = new Listas();
        $data = $listas->aliasLista($alias, $id_sucursal);

        $listas->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function estudiosPaquetes() {

        $estudio = $_REQUEST["estudio"];
        $id_sucursal = $_REQUEST["id_sucursal"];
        $listas = new Listas();
        $data = $listas->estudiosPaquetesDescripcion($estudio, $id_sucursal);
        $datos = [];
        foreach ($data AS $row) {
            $datos[] = array(
                "value" => $row->alias,
                "label" => $row->nombre,
                "id" => $row->id,
                "tipo" => $row->tipo,
                "precio" => number_format($row->precio, 2)
            );
        }
        $listas->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

    function delete() {
        $id_lista = $_REQUEST["id_lista"];

        $listas = new Listas();
        $listas->deleteLista($id_lista);

        $listas->close();
        header("Location: /listas-precios?msg=ok");
    }

    function registro() {
        $data = array(
            "id" => $_REQUEST["id_lista"],
            "alias" => $_REQUEST["alias"],
            "nombre" => $_REQUEST["nombre"],
            "codigo" => $_REQUEST["codigo"],
            "precio_publico" => $_REQUEST["precio_publico"],
            "precio_neto" => $_REQUEST["precio_neto"],
            "paquete" => $_REQUEST["paquete"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $listas = new Listas();
        if ($_REQUEST["id_lista"] == "") {
            $listas->addLista($data);
        } else {
            $listas->editLista($data);
        }

        $listas->close();
        header("Location: /listas-precios?msg=ok");
    }

    function addEstudio() {

        $data = $_REQUEST;

        $listas = new Listas();
        $listas->addEstudio($data);

        $listas->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("");
    }

    function deleteEstudio() {

        $data = $_REQUEST;

        $listas = new Listas();
        $listas->deleteEstudio($data);

        $listas->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("");
    }

}

new Lista();

