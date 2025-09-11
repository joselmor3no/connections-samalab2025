<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/model/Conexion.php');

class Usuarios {

    private $conexion;

    function __construct() {
        $this->conexion = new Conexion();
    }

    function getUsuario($user, $pass) {

        $sql = "SELECT * 
            FROM cliente
            WHERE usuario = '$user' AND password = '$pass' AND activo = 1 AND inactivo = 0";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function validarSession() {
        session_start();

        $session_activa = 0;

        //Proceso de validacion y permisos pendiente
        $id_usuario = $_SESSION["id_cliente"];
        if ($id_usuario > 0) {
            $session_activa = 1;
        }

        return $session_activa;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
