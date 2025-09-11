<?php

require_once('../../model/admision/Citas.php');

class Agenda {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'citas') {
            $this->citas();
        } else if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'edit') {
            $this->edit();
        } else if ($opc == 'event') {
            $this->event();
        } else if ($opc == 'horario') {
            $this->horario();
        } else if ($opc == 'delete') {
            $this->delete();
        }else if ($opc == 'cancelar') {
            $this->cancelado();
        }
    }

    function citas() {

        session_start();
        $id_sucursal = $_SESSION["id_sucursal"];
        $id_seccion = $_REQUEST["id_seccion"];

        $data = [];
        $citas = new Citas();
        $datos = $citas->getCitas($id_seccion, $id_sucursal);

        foreach ($datos AS $row) {
            $data[] = array(
                "id" => $row->id,
                "title" => $row->paciente,
                "telefono" => $row->telefono,
                "observaciones" => $row->observaciones,
                "start" => str_replace(" ", "T", $row->inicio),
                "end" => str_replace(" ", "T", $row->fin),
                "backgroundColor" => $row->cancelado == 0 ? "#0073b7" : "#dc3545"
            );
        }

        $citas->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function registro() {
        session_start();
        $id_sucursal = $_SESSION["id_sucursal"];

        $data = array(
            "paciente" => $_REQUEST["paciente"],
            "telefono" => $_REQUEST["telefono"],
            "observaciones" => $_REQUEST["observaciones"],
            "id_seccion" => $_REQUEST["id_seccion"],
            "inicio" => $_REQUEST["inicio"],
            "final" => $_REQUEST["final"],
            "id_sucursal" => $id_sucursal);


        $citas = new Citas();
        $valido = $citas->addCita($data);
        $citas->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($valido);
    }

    function edit() {

        session_start();
        $id_sucursal = $_SESSION["id_sucursal"];

        $data = array(
            "paciente" => $_REQUEST["paciente"],
            "telefono" => $_REQUEST["telefono"],
            "observaciones" => $_REQUEST["observaciones"],
            "id_seccion" => $_REQUEST["id_seccion"],
            "inicio" => str_replace("T", " ", $_REQUEST["inicio"]),
            "final" => str_replace("T", " ", $_REQUEST["final"]),
            "id_sucursal" => $id_sucursal,
            "id" => $_REQUEST["id"]);

        $citas = new Citas();
        $valido = $citas->editCita($data);
        $citas->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($valido);
    }

    function event() {

        $id = $_REQUEST["id"];

        $data = [];
        $citas = new Citas();
        $datos = $citas->getEvento($id);

        foreach ($datos AS $row) {
            $data = array(
                "id" => $row->id,
                "title" => $row->paciente,
                "telefono" => $row->telefono,
                "observaciones" => $row->observaciones,
                "start" => str_replace(" ", "T", $row->inicio),
                "end" => str_replace(" ", "T", $row->fin),
                "backgroundColor" => $row->cancelado == 0 ? "#0073b7" : "#dc3545"
            );
        }

        $citas->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function horario() {

        session_start();
        $id_sucursal = $_SESSION["id_sucursal"];

        $data = array(
            "inicio" => str_replace("T", " ", $_REQUEST["inicio"]),
            "final" => str_replace("T", " ", $_REQUEST["final"]),
            "id_seccion" => $_REQUEST["id_seccion"],
            "id_sucursal" => $id_sucursal,
            "id" => $_REQUEST["id"]);

        $citas = new Citas();
        $valido = $citas->horario($data);
        $citas->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($valido);
    }

    function delete() {

        $id = $_REQUEST["id"];

        $citas = new Citas();
        $citas->deleteCita($id);
        $citas->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }
    
      function cancelado() {

        $id = $_REQUEST["id"];

        $citas = new Citas();
        $citas->CancelarCita($id);
        $citas->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

}

new Agenda();

