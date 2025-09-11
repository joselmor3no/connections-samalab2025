<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/Administrador/model/Usuarios.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Estructuras {

    private $conexion;

    function __construct() {

        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso) {
            header("Location: /Administrador");
        }

        $this->conexion = new Conexion();
    }

    function getDepartamentos() {

        $catalogos = new Catalogos();
        return $catalogos->getDepartamentos();
    }

    function getDepartamento($id) {

        $sql = "SELECT * 
            FROM departamento
            WHERE id = '$id' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function consecutivoDepartamento() {
        $sql = "SELECT MAX(consecutivo) + 1 AS consecutivo
            FROM departamento
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteDepartamento($id) {

        $sql = "UPDATE departamento
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE DEPARTAMENTO: " . str_replace("'", "", $sql),
            "tabla" => "departamento",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addDepartamento($data) {

        $sql = "INSERT INTO departamento (consecutivo, codigo, departamento) "
                . "SELECT MAX(consecutivo) + 1, '" . $data["codigo"] . "', '" . $data["departamento"] . "' FROM departamento";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO DEPARTAMENTO: " . str_replace("'", "", $sql),
            "tabla" => "departamento",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editDepartamento($data) {

        $sql = "UPDATE departamento "
                . "SET codigo = '" . $data["codigo"] . "', departamento = '" . $data["departamento"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE DEPARTAMENTO: " . str_replace("'", "", $sql),
            "tabla" => "departamento",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    //ESPECIALIDAD
    function getEspecialidades() {

        $catalogos = new Catalogos();
        return $catalogos->getEspecialidades();
    }

    function getEspecialidad($id) {

        $sql = "SELECT * 
            FROM especialidad
            WHERE id = '$id' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function consecutivoEspecialidad() {
        $sql = "SELECT MAX(id) + 1 AS consecutivo
            FROM especialidad";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function editEspecialidad($data) {

        $sql = "UPDATE especialidad "
                . "SET especialidad = '" . $data["especialidad"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE ESPECIALIDAD: " . str_replace("'", "", $sql),
            "tabla" => "especialidad",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addEspecialidad($data) {

        $sql = "INSERT INTO especialidad (especialidad) 
                VALUES ('" . $data["especialidad"] . "')";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA ESPECIALIDAD: " . str_replace("'", "", $sql),
            "tabla" => "especialidad",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    //MATERIA BIOLOGICA

    function getMateriasBiologicas() {

        $catalogos = new Catalogos();
        return $catalogos->getMateriaBiologica();
    }

    function consecutivoMateria() {
        $sql = "SELECT MAX(consecutivo) + 1 AS consecutivo
            FROM materia_biologica
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getMateria($id) {

        $sql = "SELECT * 
            FROM materia_biologica
            WHERE id = '$id' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteMateria($id) {

        $sql = "UPDATE materia_biologica
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE MATERIA BIOLOGICA: " . str_replace("'", "", $sql),
            "tabla" => "materia_biologica",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editMateria($data) {

        $sql = "UPDATE materia_biologica "
                . "SET codigo = '" . $data["codigo"] . "', materia = '" . $data["materia"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE MATERIA: " . str_replace("'", "", $sql),
            "tabla" => "materia_biologica",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addMateria($data) {

        $sql = "INSERT INTO materia_biologica (consecutivo, codigo, materia) "
                . "SELECT MAX(consecutivo) + 1, '" . $data["codigo"] . "', '" . $data["materia"] . "' FROM materia_biologica";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA MATERIA: " . str_replace("'", "", $sql),
            "tabla" => "materia_biologica",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    //RECIPENTE BIOLOGICO
    function getRecipientesBiologicos() {

        $catalogos = new Catalogos();
        return $catalogos->getRecipienteBiologico();
    }

    function consecutivoRecipiente() {
        $sql = "SELECT MAX(consecutivo) + 1 AS consecutivo
            FROM recipiente_biologico
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getRecipiente($id) {

        $sql = "SELECT * 
            FROM recipiente_biologico
            WHERE id = '$id' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteRecipiente($id) {

        $sql = "UPDATE recipiente_biologico
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE RECIPIENTE BIOLOGICO: " . str_replace("'", "", $sql),
            "tabla" => "recipiente_biologico",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addRecipiente($data) {

        $sql = "INSERT INTO recipiente_biologico (consecutivo, codigo, recipiente) "
                . "SELECT MAX(consecutivo) + 1, '" . $data["codigo"] . "', '" . $data["recipiente"] . "' FROM recipiente_biologico";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO RECIPIENTE: " . str_replace("'", "", $sql),
            "tabla" => "recipiente_biologico",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editRecipiente($data) {

        $sql = "UPDATE recipiente_biologico "
                . "SET codigo = '" . $data["codigo"] . "', recipiente = '" . $data["recipiente"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE RECIPIENTE: " . str_replace("'", "", $sql),
            "tabla" => "recipiente_biologico",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    //SECCIONES

    function getSecciones() {

        $catalogos = new Catalogos();
        return $catalogos->getSecciones();
    }

    function consecutivoSeccion() {
        $sql = "SELECT MAX(consecutivo) + 1 AS consecutivo
            FROM secciones
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getSeccion($id) {

        $sql = "SELECT * 
            FROM secciones
            WHERE id = '$id' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteSeccion($id) {

        $sql = "UPDATE secciones
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE SECCION: " . str_replace("'", "", $sql),
            "tabla" => "secciones",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addSeccion($data) {

        $sql = "INSERT INTO secciones (consecutivo, codigo, seccion, agenda) "
                . "SELECT MAX(consecutivo) + 1, '" . $data["codigo"] . "', '" . $data["seccion"] . "', '" . $data["agenda"] . "' FROM secciones";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA SECCION: " . str_replace("'", "", $sql),
            "tabla" => "secciones",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editSeccion($data) {

        $sql = "UPDATE secciones "
                . "SET codigo = '" . $data["codigo"] . "', seccion = '" . $data["seccion"] . "', agenda = '" . $data["agenda"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE SECCION: " . str_replace("'", "", $sql),
            "tabla" => "secciones",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    //TIPO DE EMPEADOS
    function getEmpleados() {

        $sql = "SELECT * 
            FROM tipo_empleado ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
