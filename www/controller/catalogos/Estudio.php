<?php

session_start();

require_once('../../model/catalogos/Estudios.php');
require_once('../../model/catalogos/Componentes.php');

class Estudio {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'registro') {
            $this->registro();
        } elseif ($opc == 'delete') {
            $this->delete();
        } elseif ($opc == 'get-estudio') {
            $this->getEstudio();
        } elseif ($opc == 'components') {
            $this->components();
        } elseif ($opc == 'alias-component') {
            $this->aliasComponent();
        } elseif ($opc == 'save-component') {
            $this->saveComponent();
        } elseif ($opc == 'get-component') {
            $this->getComponent();
        } elseif ($opc == 'delete-component') {
            $this->deleteComponent();
        } elseif ($opc == 'position-componets') {
            $this->positionComponets();
        } elseif ($opc == 'components-numericas') {
            $this->componentsNumericas();
        } elseif ($opc == 'get-component-numerica') {
            $this->getComponentNumerico();
        } elseif ($opc == 'save-component-numerica') {
            $this->saveComponetNumerica();
        } elseif ($opc == 'delete-component-numerica') {
            $this->deleteComponetNumerica();
        } elseif ($opc == 'components-lista') {
            $this->getComponentLista();
        } elseif ($opc == 'save-component-lista') {
            $this->saveComponetLista();
        } elseif ($opc == 'delete-component-lista') {
            $this->deleteComponetLista();
        } elseif ($opc == 'get-formula') {
            $this->getComponentFormula();
        } elseif ($opc == 'save-component-formula') {
            $this->saveComponetFormula();
        } elseif ($opc == 'components-tabla') {
            $this->componentsTabla();
        } elseif ($opc == 'delete-component-tabla') {
            $this->deleteComponetTabla();
        } elseif ($opc == 'get-component-tabla') {
            $this->getComponentTabla();
        } elseif ($opc == 'save-component-tabla') {
            $this->saveComponetTabla();
        } else if ($opc == 'formato-texto') {
            $this->addFormatoTexto();
        } else if ($opc == 'clonar-componentes') {
            $this->clonarEstudio();
        }
    }

    //Algo improvisado, falta arreglar
    function clonarEstudio() {
        $estudios = new Estudios();
        $estudios->clonarEstudio();

        $estudios->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

    function registro() {

        $data = array(
            "id" => $_REQUEST["id_cat_estudio"],
            "id_estudio" => $_REQUEST["id_estudio"],
            "publico" => $_REQUEST["publico"],
            "maquila" => $_REQUEST["maquila"] != "" ? $_REQUEST["maquila"] : 0,
            "id_indicaciones" => $_REQUEST["id_indicaciones"] != "" ? $_REQUEST["id_indicaciones"] : "NULL",
            "montaje" => $_REQUEST["montaje"] != "" ? $_REQUEST["montaje"] : 0,
            "procesos" => $_REQUEST["procesos"] != "" ? $_REQUEST["procesos"] : 0,
            "id_formato" => $_REQUEST["id_formato"],
            "id_referencia" => $_REQUEST["id_referencia"] != "" ? $_REQUEST["id_referencia"] : "NULL",
            "metodo" => $_REQUEST["metodo"],
            "volumen" => $_REQUEST["volumen"],
            "porcentaje" => $_REQUEST["porcentaje"] != "" ? $_REQUEST["porcentaje"] : 0,
            "id_sucursal" => $_REQUEST["id_sucursal"],
            "clase" => $_REQUEST["clase"],
        );

        $estudios = new Estudios(); 
        if ($_REQUEST["id_estudio"] == "") {
            $estudios->addEstudio($data);
        } else {
            $estudios->editEstudio($data);
        }

        $estudios->close();
        header("Location: /estudio?msg=ok&id=" . $_REQUEST["id_cat_estudio"]);
    }

    function delete() {
        $id_estudio = $_REQUEST["id_estudio"];

        $estudios = new Estudios();
        $estudios->deleteEstudio($id_estudio);

        $estudios->close();
        header("Location: /estudios?msg=ok");
    }

    function getEstudio() {
        $estudio = $_REQUEST["estudio"];
        $id_sucursal = $_REQUEST["id_sucursal"];
        $estudios = new Estudios();
        $data = $estudios->getEstudioDescripcion($estudio, $id_sucursal);
        $datos = [];
        foreach ($data AS $row) {
            $datos[] = array(
                "value" => $row->alias,
                "label" => $row->nombre_estudio,
                "id" => $row->id,
                "precio" => number_format($row->precio_publico, 2)
            );
        }
        $estudios->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

    function components() {

        $id_estudio = $_REQUEST["id_estudio"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $componentes = new Componentes();
        $data = $componentes->getComponentes($id_estudio, $id_sucursal);
        $components = [];
        foreach ($data AS $row) {
            $components[] = array(
                "id" => $row->id,
                "componente" => $row->componente,
                "alias" => $row->alias,
                "capturable" => $row->capturable,
                "imprimible" => $row->imprimible,
                "tipo_componente" => $row->tipo_componente,
            );
        }

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($components);
    }

    function aliasComponent() {
        $alias = $_REQUEST["alias"];
        $id_sucursal = $_REQUEST["id_sucursal"];
        $id_estudio = $_REQUEST["id_cat_estudio"];

        $componentes = new Componentes();
        $data = $componentes->aliasComponente($alias, $id_estudio, $id_sucursal);
        $componentes->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function saveComponent() {

        //var_dump($_REQUEST);

        $data = array(
            "id" => $_REQUEST["id_componente"],
            "componente" => $_REQUEST["componente"],
            "alias" => $_REQUEST["alias"],
            "leyenda" => $_REQUEST["leyenda"],
            "absoluto" => $_REQUEST["absoluto"] == "absoluto" ? "1" : "0",
            "total_absoluto" => $_REQUEST["absoluto"] == "total" ? "1" : "0",
            "capturable" => $_REQUEST["capturable"] == "on" ? "1" : "0",
            "imprimible" => $_REQUEST["imprimible"] == "on" ? "1" : "0",
            "linea" => $_REQUEST["linea"] == "on" ? "1" : "0",
            "observaciones" => $_REQUEST["observaciones"] == "on" ? "1" : "0",
            "unidad" => $_REQUEST["unidad"],
            "referencia" => $_REQUEST["referencia"],
            "id_cat_componente" => $_REQUEST["id_cat_componente"] != "" ? $_REQUEST["id_cat_componente"] : "NULL",
            "id_sucursal" => $_REQUEST["id_sucursal"],
            "id_cat_estudio" => $_REQUEST["id_cat_estudio"],
        );

        $componentes = new Componentes();
        if ($_REQUEST["id_componente"] == "0") {
            $componentes->addComponente($data);
        } else {
            $componentes->editComponente($data);
        }

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function getComponent() {

        $id_componente = $_REQUEST["id_componente"];

        $componentes = new Componentes();
        $data = $componentes->getComponente($id_componente);
        $component = [];
        foreach ($data AS $row) {
            $component = array(
                "id" => $row->id,
                "componente" => $row->componente,
                "alias" => $row->alias,
                "leyenda" => $row->leyenda,
                "absoluto" => $row->absoluto,
                "total_absoluto" => $row->total_absoluto,
                "capturable" => $row->capturable,
                "imprimible" => $row->imprimible,
                "linea" => $row->linea,
                "observaciones" => $row->observaciones,
                "unidad" => $row->unidad,
                "referencia" => $row->referencia,
                "id_cat_componente" => $row->id_cat_componente,
            );
        }

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($component);
    }

    function deleteComponent() {
        $id_componente = $_REQUEST["id_componente"];

        $componentes = new Componentes();
        $componentes->deleteComponente($id_componente);

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function positionComponets() {
        // var_dump($_REQUEST["components"]);

        $componentes = new Componentes();
        $componentes->positionComponets($_REQUEST);

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function componentsNumericas() {

        $id_componente = $_REQUEST["id_componente"];

        $componentes = new Componentes();
        $data = $componentes->getComponentesNumericas($id_componente);
        $components = [];
        foreach ($data AS $row) {
            $components[] = array(
                "id" => $row->id,
                "referencia" => $row->referencia == "Nino" ? "Niño(a)" : $row->referencia,
                "edad_inicio" => $row->edad_inicio,
                "edad_fin" => $row->edad_fin,
                "alta_aceptable" => $row->alta_aceptable,
                "bajo_aceptable" => $row->bajo_aceptable,
                "alta" => $row->alta,
                "baja" => $row->baja,
                "tipo_edad" => $row->tipo_edad == "Anios" ? "Años" : $row->tipo_edad,
            );
        }

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($components);
    }

    function getComponentNumerico() {

        $id_componente = $_REQUEST["id_componente"];

        $componentes = new Componentes();
        $data = $componentes->getComponentNumerico($id_componente);
        $component = [];
        foreach ($data AS $row) {
            $component = array(
                "id" => $row->id,
                "referencia" => $row->referencia,
                "edad_inicio" => $row->edad_inicio,
                "edad_fin" => $row->edad_fin,
                "alta_aceptable" => $row->alta_aceptable,
                "bajo_aceptable" => $row->bajo_aceptable,
                "alta" => $row->alta,
                "baja" => $row->baja,
                "tipo_edad" => $row->tipo_edad,
                "valores_decimales" => $row->valores_decimales,
                "valores_unidades" => $row->valores_unidades,
            );
        }

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($component);
    }

    function saveComponetNumerica() {

        $data = array(
            "id" => $_REQUEST["id"],
            "referencia" => $_REQUEST["referencia"],
            "edad_inicio" => $_REQUEST["edad_inicio"],
            "edad_fin" => $_REQUEST["edad_fin"],
            "tipo_edad" => $_REQUEST["tipo_edad"],
            "valores_decimales" => $_REQUEST["valores_decimales"],
            "valores_unidades" => $_REQUEST["valores_unidades"],
            "alta_aceptable" => $_REQUEST["alta_aceptable"] != "" ? $_REQUEST["alta_aceptable"] : "0",
            "alta" => $_REQUEST["alta"] != "" ? $_REQUEST["alta"] : "0",
            "baja" => $_REQUEST["baja"] != "" ? $_REQUEST["baja"] : "0",
            "bajo_aceptable" => $_REQUEST["bajo_aceptable"] != "" ? $_REQUEST["bajo_aceptable"] : "0",
            "id_componente" => $_REQUEST["id_componente"],
        );

        $componentes = new Componentes();
        if ($_REQUEST["id"] == "") {
            $componentes->addComponenteNumerico($data);
        } else {
            $componentes->editComponenteNumerico($data);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function deleteComponetNumerica() {
        $id_componente = $_REQUEST["id"];

        $componentes = new Componentes();
        $componentes->deleteComponenteNumerico($id_componente);

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function getComponentLista() {

        $id_componente = $_REQUEST["id_componente"];

        $componentes = new Componentes();
        $data = $componentes->getComponentLista($id_componente);
        $component = [];
        foreach ($data AS $row) {
            $component[] = array(
                "id" => $row->id,
                "elemento" => $row->elemento,
                "predeterminado" => $row->predeterminado,
            );
        }

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($component);
    }

    function saveComponetLista() {

        $data = array(
            "id" => $_REQUEST["id"],
            "id_componente" => $_REQUEST["id_componente"],
            "elemento" => $_REQUEST["valor"],
            "predeterminado" => $_REQUEST["predeterminado"] == "on" ? "1" : "0"
        );

        $componentes = new Componentes();
        if ($_REQUEST["id"] == "") {
            $componentes->addComponenteLista($data);
        } else {
            //$componentes->editComponenteLista($data);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function deleteComponetLista() {
        $id_componente = $_REQUEST["id_componente"];

        $componentes = new Componentes();
        $componentes->deleteComponenteLista($id_componente);

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function getComponentFormula() {
        $id_componente = $_REQUEST["id_componente"];

        $componentes = new Componentes();
        $data = $componentes->getComponenteFormula($id_componente);
        $formula = "";
        foreach ($data AS $row) {
            $formula = $row->formula;
        }

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($formula);
    }

    function saveComponetFormula() {
        $id_componente = $_REQUEST["id_componente"];
        $formula = $_REQUEST["formula"];

        $componentes = new Componentes();
        $componentes->addComponenteFormula($formula, $id_componente);


        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function componentsTabla() {

        $id_componente = $_REQUEST["id_componente"];

        $componentes = new Componentes();
        $data = $componentes->componentsTabla($id_componente);
        $component = [];
        foreach ($data AS $row) {
            $component[] = array(
                "id" => $row->id,
                "sexo" => $row->sexo == "Nino" ? "Niño(a)" : $row->sexo,
            );
        }

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($component);
    }

    function deleteComponetTabla() {
        $id_componente = $_REQUEST["id"];

        $componentes = new Componentes();
        $componentes->deleteComponenteTabla($id_componente);

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function getComponentTabla() {

        $id_componente = $_REQUEST["id_componente"];

        $componentes = new Componentes();
        $data = $componentes->getComponentTabla($id_componente);
        $component = [];
        foreach ($data AS $row) {
            $component = array(
                "id" => $row->id,
                "sexo" => $row->sexo,
                "tabla" => $row->valor,
            );
        }

        $componentes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($component);
    }

    function saveComponetTabla() {

        $data = array(
            "id" => $_REQUEST["id"],
            "id_componente" => $_REQUEST["id_componente"],
            "sexo" => $_REQUEST["sexo"],
            "valor" => $_REQUEST["component_tabla"],
        );

        $componentes = new Componentes();
        if ($_REQUEST["id"] == "") {
            $componentes->addComponenteTabla($data);
        } else {
            $componentes->editComponenteTabla($data);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function addFormatoTexto() {

        $data = array(
            "formato" => $_REQUEST["resultado_texto"],
            "id_estudio" => $_REQUEST["id_cat_estudio"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );


        $estudios = new Estudios();
        $estudios->addFormato($data);

        $estudios->close();
        header("Location: /estudio?msg=ok&id=" . $_REQUEST["id_cat_estudio"]);

        //$datos = $paciente = $reportes->getOrdenPaciente($id_orden);
        //header('Content-Type: application/json; charset=utf-8');
        //echo json_encode($datos[0]);
    }

}

new Estudio();

