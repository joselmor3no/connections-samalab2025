<?php

require_once('../model/Catalogos.php');

class Generales {

    function __construct() {

        $opc = $_REQUEST["opc"];

        if ($opc == 'municipios') {
            $estado = $_REQUEST["estado"];
            $this->municipios($estado);
        }
    }

    function municipios($estado) {
        $catalogos = new Catalogos();
        $datos = $catalogos->getMunicipios($estado);
        $municipios = [];
        foreach ($datos AS $row) {
            $municipios[] = array(
                "id" => $row->id,
                "municipio" => $row->municipio
            );
        }
        
        $catalogos->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($municipios);
    }

}

new Generales();
