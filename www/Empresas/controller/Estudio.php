<?php

require_once('../model/Estudios.php');

class Estudio {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'alias') {
            $this->alias();
        }else if ($opc == 'no-estudio') {
            $this->noEstudio();
        }
    }

    function registro() {

        $data = array(
            "id" => $_REQUEST["id_estudio"],
            "no_estudio" => $_REQUEST["no_estudio"],
            "nombre_estudio" => $_REQUEST["nombre_estudio"],
            "alias" => $_REQUEST["alias"],
            "tipo" => $_REQUEST["tipo"],
            "id_departamento" => $_REQUEST["id_departamento"],
            "id_secciones" => $_REQUEST["id_secciones"],
            "id_materia_biologica" => $_REQUEST["id_materia_biologica"],
            "id_recipiente_biologico" => $_REQUEST["id_recipiente_biologico"],
            "muestras" => $_REQUEST["muestras"],
            "etiquetas" => $_REQUEST["etiquetas"],
            "sat" => $_REQUEST["sat"],
            "resultado_componente" => $_REQUEST["resultado"] == "componente" ? 1 : 0,
            "resultado_texto" => $_REQUEST["resultado"] == "texto" ? 1 : 0,
            "orden_impresion" => $_REQUEST["orden_impresion"],
            "id_tipo_reporte" => $_REQUEST["id_tipo_reporte"],
            "fur" => $_REQUEST["fur"] == "on" ? 1 : 0,
            "fud" => $_REQUEST["fud"] == "on" ? 1 : 0,
            "id_admin" => $_REQUEST["id_admin"],
        );

        $estudios = new Estudios();
        //

        if ($_REQUEST["id_estudio"] == "") {
            $estudios->addEstudio($data);
        } else {
            $estudios->editEstudio($data);
        }

        $estudios->close();
        header("Location: /Empresas/estudios?msg=ok");
    }
    
    function alias() {

        $alias = $_REQUEST["alias"];

        $estudios = new Estudios();
        $data = $estudios->aliasEstudio($alias);

        $estudios->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

}

new Estudio();

