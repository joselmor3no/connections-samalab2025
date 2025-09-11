<?php

require_once('../model/Estructuras.php');

class Estructura {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'delete-departamento') {
            $this->deleteDepartamento();
        } else if ($opc == 'registro-departamento') {
            $this->registroDepartamento();
        } else if ($opc == 'registro-especialidad') {
            $this->registroEspecialidad();
        } else if ($opc == 'delete-materia') {
            $this->deleteMateria();
        } else if ($opc == 'registro-materia') {
            $this->registroMateria();
        } else if ($opc == 'delete-recipiente') {
            $this->deleteRecipiente();
        } else if ($opc == 'registro-recipiente') {
            $this->registroRecipiente();
        } else if ($opc == 'delete-seccion') {
            $this->deleteSeccion();
        } else if ($opc == 'registro-seccion') {
            $this->registroSeccion();
        }
    }

    function registroDepartamento() {

        $data = array(
            "id" => $_REQUEST["id"],
            "codigo" => $_REQUEST["codigo"],
            "departamento" => $_REQUEST["departamento"],
            "id_admin" => $_REQUEST["id_admin"],
        );

        $estructuras = new Estructuras();
        if ($_REQUEST["id"] == "") {
            $estructuras->addDepartamento($data);
        } else {
            $estructuras->editDepartamento($data);
        }

        $estructuras->close();
        header("Location: /Administrador/catalogo-departamentos?msg=ok");
    }

    function deleteDepartamento() {
        $id = $_REQUEST["id"];

        $estructuras = new Estructuras();
        $estructuras->deleteDepartamento($id);

        $estructuras->close();
        header("Location: /Administrador/catalogo-departamentos?msg=ok");
    }

    function registroEspecialidad() {

        $data = array(
            "id" => $_REQUEST["id"],
            "especialidad" => $_REQUEST["especialidad"],
            "id_admin" => $_REQUEST["id_admin"],
        );

        $estructuras = new Estructuras();
        if ($_REQUEST["id"] == "") {
            $estructuras->addEspecialidad($data);
        } else {
            $estructuras->editEspecialidad($data);
        }

        $estructuras->close();
        header("Location: /Administrador/catalogo-especialidades?msg=ok");
    }

    function deleteMateria() {
        $id = $_REQUEST["id"];

        $estructuras = new Estructuras();
        $estructuras->deleteMateria($id);

        $estructuras->close();
        header("Location: /Administrador/catalogo-materias-biologicas?msg=ok");
    }

    function registroMateria() {

        $data = array(
            "id" => $_REQUEST["id"],
            "codigo" => $_REQUEST["codigo"],
            "materia" => $_REQUEST["materia"],
            "id_admin" => $_REQUEST["id_admin"],
        );

        $estructuras = new Estructuras();
        if ($_REQUEST["id"] == "") {
            $estructuras->addMateria($data);
        } else {
            $estructuras->editMateria($data);
        }

        $estructuras->close();
        header("Location: /Administrador/catalogo-materias-biologicas?msg=ok");
    }

    function deleteRecipiente() {
        $id = $_REQUEST["id"];

        $estructuras = new Estructuras();
        $estructuras->deleteRecipiente($id);

        $estructuras->close();
        header("Location: /Administrador/catalogo-recipientes-biologicos?msg=ok");
    }

    function registroRecipiente() {

        $data = array(
            "id" => $_REQUEST["id"],
            "codigo" => $_REQUEST["codigo"],
            "recipiente" => $_REQUEST["recipiente"],
            "id_admin" => $_REQUEST["id_admin"],
        );

        $estructuras = new Estructuras();
        if ($_REQUEST["id"] == "") {
            $estructuras->addRecipiente($data);
        } else {
            $estructuras->editRecipiente($data);
        }

        $estructuras->close();
        header("Location: /Administrador/catalogo-recipientes-biologicos?msg=ok");
    }

    function deleteSeccion() {
        $id = $_REQUEST["id"];

        $estructuras = new Estructuras();
        $estructuras->deleteSeccion($id);

        $estructuras->close();
        header("Location: /Administrador/catalogo-secciones?msg=ok");
    }

    function registroSeccion() {
        //var_dump($_REQUEST);

        $data = array(
            "id" => $_REQUEST["id"],
            "codigo" => $_REQUEST["codigo"],
            "seccion" => $_REQUEST["seccion"],
            "agenda" => $_REQUEST["agenda"] == "on" ? 1 : 0,
            "id_admin" => $_REQUEST["id_admin"],
        );

        $estructuras = new Estructuras();
        if ($_REQUEST["id"] == "") {
            $estructuras->addSeccion($data);
        } else {
            $estructuras->editSeccion($data);
        }

        $estructuras->close();
        header("Location: /Administrador/catalogo-secciones?msg=ok");
    }

}

new Estructura();

