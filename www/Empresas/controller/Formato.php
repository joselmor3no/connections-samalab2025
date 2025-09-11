<?php

require_once('../model/Formatos.php');

class Formato {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'laboratorio') {
            $this->labortorio();
        } else if ($opc == 'recibo') {
            $this->recibo();
        }
    }

    function labortorio() {

        $data = array(
            "posicion_logo" => $_REQUEST["posicion_logo"],
            "fuente" => $_REQUEST["fuente"],
            "punto" => $_REQUEST["punto"],
            "tipo" => $_REQUEST["tipo"],
            "head" => $_REQUEST["head"],
            "footer" => $_REQUEST["footer"],
            "color_linea" => $_REQUEST["color_linea"],
            "separador" => $_REQUEST["separador"],
            "color" => $_REQUEST["color"],
            "asterisco" => $_REQUEST["asterisco"],
            "horafecha" => $_REQUEST["horafecha"] == "on" ? 1 : 0,
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        $formatos = new Formatos();

        $aux = $formatos->getFormatoLab($_REQUEST["id_cliente"]);
        if (count($aux) == 0) {
            $formatos->addLabortorio($data);
        } else {
            $formatos->editLabortorio($data);
        }


        $formatos->close();
        header("Location: /Empresas/formato-laboratorio?msg=ok");
    }

    function recibo() {

        //var_dump($_REQUEST);

        $data = array(
            "logotipo_posicion" => $_REQUEST["logotipo_posicion"],
            "logotipo_tamano" => $_REQUEST["logotipo_tamano"],
            "campo_fuente" => $_REQUEST["campo_fuente"],
            "campo_tipo" => $_REQUEST["campo_tipo"],
            "campo_tamano" => $_REQUEST["campo_tamano"],
            "alineacion1" => $_REQUEST["alineacion1"],
            "nombre_clinica1" => $_REQUEST["nombre_clinica1"] == "on" ? 1 : 0,
            "sucursal1" => $_REQUEST["sucursal1"] == "on" ? 1 : 0,
            "orden1" => $_REQUEST["orden1"] == "on" ? 1 : 0,
            "fecha1" => $_REQUEST["fecha1"] == "on" ? 1 : 0,
            "no_paciente1" => $_REQUEST["no_paciente1"] == "on" ? 1 : 0,
            "nombre_paciente1" => $_REQUEST["nombre_paciente1"] == "on" ? 1 : 0,
            "domicilio1" => $_REQUEST["domicilio1"] == "on" ? 1 : 0,
            "domicilio2" => $_REQUEST["domicilio2"] == "on" ? 1 : 0,
            "medico1" => $_REQUEST["medico1"] == "on" ? 1 : 0,
            "empresa1" => $_REQUEST["empresa1"] == "on" ? 1 : 0,
            "edad1" => $_REQUEST["edad1"] == "on" ? 1 : 0,
            "tipo_edad1" => $_REQUEST["tipo_edad1"] == "on" ? 1 : 0,
            "sexo1" => $_REQUEST["sexo1"] == "on" ? 1 : 0,
            "fecha_entrega1" => $_REQUEST["fecha_entrega1"] == "on" ? 1 : 0,
            "dir_hospital1" => $_REQUEST["dir_hospital1"] == "on" ? 1 : 0,
            "telefono_g1" => $_REQUEST["telefono_g1"] == "on" ? 1 : 0,
            "telefono2_g1" => $_REQUEST["telefono2_g1"] == "on" ? 1 : 0,
            "web1" => $_REQUEST["web1"] == "on" ? 1 : 0,
            "descripcion_g1" => $_REQUEST["descripcion_g1"] == "on" ? 1 : 0,
            "informacion1" => $_REQUEST["informacion1"] == "on" ? 1 : 0,
            "medida" => $_REQUEST["medida"] == "on" ? 1 : 0,
            "orden_interna" => $_REQUEST["orden_interna"] == "on" ? 1 : 0,
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        $formatos = new Formatos();

        $aux = $formatos->getFormatoRecibo($_REQUEST["id_cliente"]);
        if (count($aux) == 0) {
            $formatos->addRecibo($data);
        } else {
            $formatos->editRecibo($data);
        }


        $formatos->close();
        header("Location: /Empresas/formato-recibo?msg=ok");
    }

}

new Formato();

