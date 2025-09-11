<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Secciones {

    private $conexion;

    function __construct() {
        //Validación de session
        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso) {
            header("Location: /");
        }

        $this->conexion = new Conexion();
    }

    function getSecciones($id_sucursal) {

        $sql = "SELECT * 
            FROM secciones_agenda
            WHERE id_sucursal = '$id_sucursal' AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getSeccion($id) {

        $sql = "SELECT * 
            FROM secciones_agenda
            WHERE id = '$id'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addSeccion($data) {

        $sql = "INSERT INTO `secciones_agenda`(`seccion`, `tipo`, `id_sucursal`) "
                . "VALUES ('" . $data["seccion"] . "', '" . $data["tipo"] . "', '" . $data["id_sucursal"] . "')";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO SECCIÓN DE AGENDA: " . str_replace("'", "", $sql),
            "tabla" => "doctor",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function deleteSeccion($id) {

        $sql = "UPDATE secciones_agenda
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE SECCIÓN DE AGENDA: " . str_replace("'", "", $sql),
            "tabla" => "secciones_agenda",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editSeccion($data) {

        $sql = "UPDATE secciones_agenda "
                . "SET seccion = '" . $data["seccion"] . "', tipo = '" . $data["tipo"] . "'"
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE DOCTOR: " . str_replace("'", "", $sql),
            "tabla" => "doctor",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function close() {

        $this->conexion->close();
    }

}

?>
