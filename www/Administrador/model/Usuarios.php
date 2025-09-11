<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Usuarios {

    private $conexion;

    function __construct() {
        $this->conexion = new Conexion();
    }

    function getUsuario($user, $pass) {

        $sql = "SELECT * 
            FROM usuario_admin
            WHERE usuario = '$user' AND password = '$pass' AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getUsuarioId($id) {

        $sql = "SELECT * 
            FROM usuario_admin
            WHERE id = '$id' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getUsuarios() {

        $sql = "SELECT usuario_admin.*, rol.descripcion AS rol 
            FROM usuario_admin
            INNER JOIN rol ON (rol.id = usuario_admin.id_rol)
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getUsuariosEmpresa($base) {

        $sql = "SELECT * FROM $base.cliente";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getBases() {

        $sql = "SHOW databases";
        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function validarSession() {
        session_start();

        $session_activa = 0;

        //Proceso de validacion y permisos pendiente
        $id_usuario = $_SESSION["id_admin"];
        if ($id_usuario > 0) {
            $session_activa = 1;
        }

        return $session_activa;
    }

    function addUsuario($data) {

        $sql = "INSERT INTO `usuario_admin`(`nombre`, `usuario`, `password`, `fecha_alta`, id_rol) "
                . "VALUES ('" . $data["nombre"] . "', '" . $data["usuario"] . "', '" . $data["password"] . "', NOW(), '" . $data["id_rol"] . "')";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO USUARIO ADMIN" . str_replace("'", "", $sql),
            "tabla" => "usuario_admin",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editUsuario($data) {

        $sql = "UPDATE `usuario_admin` "
                . "SET nombre = '" . $data["nombre"] . "', usuario = '" . $data["usuario"] . "', "
                . "password = '" . $data["password"] . "', id_rol = '" . $data["id_rol"] . "'"
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE USUARIO ADMIN: " . str_replace("'", "", $sql),
            "tabla" => "usuario_admin",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function deleteUsuario($id) {

        $sql = "UPDATE usuario_admin
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE USUARIO ADMIN: " . str_replace("'", "", $sql),
            "tabla" => "usuario_admin",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function close() {

        $this->conexion->close();
    }

}

?>
