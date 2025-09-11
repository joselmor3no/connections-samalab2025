<?php

require_once('../../model/catalogos/Secciones.php');

class Seccion {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'delete') {
            $this->delete();
        }
    }

    function registro() {
        $data = array(
            "id" => $_REQUEST["id_seccion"],
            "seccion" => $_REQUEST["seccion"],
            "tipo" => $_REQUEST["tipo"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $secciones = new Secciones();
        if ($_REQUEST["id_seccion"] == "") {
            $secciones->addSeccion($data);
        } else {
            $secciones->editSeccion($data);
        }

        $secciones->close();
        header("Location: /secciones-agenda?msg=ok");
    }

    function delete() {
        $id_seccion = $_REQUEST["id_seccion"];

        $secciones = new Secciones();
        $secciones->deleteSeccion($id_seccion);

        $secciones->close();
        header("Location: /secciones-agenda?msg=ok");
    }

}

new Seccion();

