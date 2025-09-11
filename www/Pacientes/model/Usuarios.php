<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');

class Usuarios {

    private $conexion;

    function __construct() {
        $this->conexion = new Conexion();
    }

    function getUsuario($user, $pass, $tipo) {
        if ($tipo == "paciente") {
            $sql = "SELECT paciente.expediente,sucursal.prefijo_imagen,paciente.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente,  paciente.id_sucursal
            FROM paciente
            inner join sucursal on sucursal.id=paciente.id_sucursal 
            WHERE expediente = '$user'";

            $data = $this->conexion->getQuery($sql);
        } else if ($tipo == "empresa") {
            $sql = "SELECT empresa.id, empresa.nombre AS empresa,  empresa.id_sucursal
            FROM empresa
            WHERE expediente = '$user' AND contrasena = '$pass' ";

            $data = $this->conexion->getQuery($sql);
        } else if ($tipo == "doctor") {
            $sql = "SELECT doctor.id, doctor.nombre AS doctor,  doctor.id_sucursal
            FROM doctor
            WHERE expediente = '$user' AND contrasena = '$pass' ";

            $data = $this->conexion->getQuery($sql);
        }
        return $data;
    }

    function validarSession() {
        session_start();

        $session_activa = 0;

        //Proceso de validacion y permisos pendiente
        if ($_SESSION["id_paciente"] != "" || $_SESSION["id_empresa"] != "" || $_SESSION["id_doctor"] != "") {
            $session_activa = 1;
        }

        return $session_activa;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
