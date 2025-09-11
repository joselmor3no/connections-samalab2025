<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Citas {

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

    function getCitas($id_seccion, $id_sucursal) {
        $sql = "SELECT *
                FROM agenda 
                WHERE id_seccion = $id_seccion AND id_sucursal = $id_sucursal AND  YEAR(inicio) >= YEAR(CURDATE()) AND activo = 1;";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addCita($data) {

        $datos = $this->disponible($data);
        if (count($datos) == 0) {

            $sql = "INSERT INTO `agenda`(`paciente`, `inicio`, `fin`, telefono, observaciones, "
                    . "id_usuario, id_seccion, id_sucursal, fecha) "
                    . "VALUES ('" . $data["paciente"] . "', '" . $data["inicio"] . "', '" . $data["final"] . "',  '" . $data["telefono"] . "',  '" . $data["observaciones"] . "', "
                    . "'" . $_SESSION["id"] . "', '" . $data["id_seccion"] . "', '" . $data["id_sucursal"] . "', NOW())";

            $this->conexion->setQuery($sql);

            //log_activity
            $data = array(
                "observaciones" => "REGISTRO EN AGENDA: " . str_replace("'", "", $sql),
                "tabla" => "agenda",
                "id_tabla" => 0,
                "usuario" => $_SESSION["usuario"]);
            $catalogos = new Catalogos();
            $catalogos->logActivity($data);
        }
        return count($datos);
    }

    function horario($data) {
        $datos = $this->disponible($data);
        if (count($datos) == 0) {

            $sql = "UPDATE `agenda` "
                    . "SET inicio = '" . $data["inicio"] . "', fin = '" . $data["final"] . "' "
                    . "WHERE id = " . $data["id"] . " AND DATE('" . $data["inicio"] . "') >= CURDATE() AND DATE('" . $data["final"] . "')  >= CURDATE() AND cancelado = 0";
            $this->conexion->setQuery($sql);

            //log_activity
            $data = array(
                "observaciones" => "CAMBIO DE HORARIO EN AGENDA: " . str_replace("'", "", $sql),
                "tabla" => "agenda",
                "id_tabla" => $data["id"],
                "usuario" => $_SESSION["usuario"]);
            $catalogos = new Catalogos();
            $catalogos->logActivity($data);
        }
        return count($datos);
    }

    function getEvento($id) {
        $sql = "SELECT *
                FROM agenda 
                WHERE id = $id AND activo=1;";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function editCita($data) {

        $datos = $this->disponible_id($data);
        if (count($datos) == 0) {

            $sql = "UPDATE `agenda` "
                    . "SET paciente = '" . $data["paciente"] . "', telefono = '" . $data["telefono"] . "', observaciones = '" . $data["observaciones"] . "', "
                    . "inicio = '" . $data["inicio"] . "', fin = '" . $data["final"] . "' "
                    . "WHERE id = " . $data["id"];
            $this->conexion->setQuery($sql);

            //log_activity
            $data = array(
                "observaciones" => "EDICIÓN DE AGENDA: " . str_replace("'", "", $sql),
                "tabla" => "agenda",
                "id_tabla" => $data["id"],
                "usuario" => $_SESSION["usuario"]);
            $catalogos = new Catalogos();
            $catalogos->logActivity($data);
        }
        return count($datos);
    }

    function deleteCita($id) {

        $sql = "UPDATE `agenda` "
                . "SET activo = 0 "
                . "WHERE id = " . $id;
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE AGENDA: " . str_replace("'", "", $sql),
            "tabla" => "agenda",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function cancelarCita($id) {

        $sql = "UPDATE `agenda` "
                . "SET cancelado = 1 "
                . "WHERE id = " . $id;
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "CANCELACION DE AGENDA: " . str_replace("'", "", $sql),
            "tabla" => "agenda",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function disponible($data) {
        $sql = "SELECT * "
                . "FROM agenda "
                . "WHERE (inicio BETWEEN '" . $data["inicio"] . "' AND  '" . $data["final"] . "' OR fin BETWEEN '" . $data["inicio"] . "' AND  '" . $data["final"] . "') "
                . "AND id_seccion = " . $data["id_seccion"] . "  AND id_sucursal = " . $data["id_sucursal"] . "  AND activo = 1 AND cancelado = 0;";
        return $this->conexion->getQuery($sql);
    }

    function disponible_id($data) {
        $sql = "SELECT * "
                . "FROM agenda "
                . "WHERE (inicio BETWEEN '" . $data["inicio"] . "' AND  '" . $data["final"] . "' OR fin BETWEEN '" . $data["inicio"] . "' AND  '" . $data["final"] . "') "
                . "AND id_seccion = " . $data["id_seccion"] . "  AND id_sucursal = " . $data["id_sucursal"] . "  AND id != " . $data["id"] . "  AND activo = 1;";
        return $this->conexion->getQuery($sql);
    }

    function close() {

        $this->conexion->close();
    }

}

?>
