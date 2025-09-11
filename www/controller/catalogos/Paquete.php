<?php

require_once('../../model/catalogos/Paquetes.php');

class Paquete {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'alias') {
            $this->alias();
        } else if ($opc == 'delete') {
            $this->delete();
        } else if ($opc == 'registro') {
            $this->registro();
        }
    }

    function alias() {

        $alias = $_REQUEST["alias"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $paquetes = new Paquetes();
        $data = $paquetes->aliasPaquete($alias, $id_sucursal);

        $paquetes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function delete() {
        $id_paquete = $_REQUEST["id_paquete"];

        $paquetes = new Paquetes();
        $paquetes->deletePaquete($id_paquete);

        $paquetes->close();
        header("Location: /paquetes-y-perfiles?msg=ok");
    }

    function registro() {

        $data = array(
            "id" => $_REQUEST["id_paquete"],
            "alias" => $_REQUEST["alias"],
            "nombre" => $_REQUEST["nombre"],
            "id_tipo_reporte" => $_REQUEST["id_tipo_reporte"] != "" ? $_REQUEST["id_tipo_reporte"] : "NULL",
            "metodo" => $_REQUEST["metodo"],
            "precio" => $_REQUEST["precio"],
            "codigo" => $_REQUEST["codigo"],
            "precio_publico" => $_REQUEST["precio_publico"],
            "precio_neto" => $_REQUEST["precio_neto"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $paquetes = new Paquetes();
        if ($_REQUEST["id_paquete"] == "") {
            $paquetes->addPaquete($data);
        } else {
            $paquetes->editPaquete($data);
        }

        $paquetes->close();
        header("Location: /paquetes-y-perfiles?msg=ok");
    }

}

new Paquete();

