<?php

require_once('../model/Usuarios.php');

class Acceso {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'login') {
            $this->login();
        } if ($opc == 'expediente') {
            $this->expediente();
        } else if ($opc == 'logout') {
            $this->logout();
        } else if ($opc == 'session') {
            $this->session();
        }
    }

    function expediente() {

        $user = $_REQUEST["user"];
        $tipo = "paciente";

        $usuario = new Usuarios();
        $datos = $usuario->getUsuario($user, $user, "paciente");


        if (count($datos) > 0 && $tipo == "paciente") {
            session_start();
            //Ultima petición
            $_SESSION["ultimo_acceso"] = date("Y-m-d G:i:s");
            $_SESSION["id_paciente"] = $datos[0]->id;
            $_SESSION["expediente"] = $datos[0]->expediente;
            $_SESSION["id_sucursal"] = $datos[0]->id_sucursal;
            $_SESSION["paciente"] = $datos[0]->paciente;
            $_SESSION["prefijo_imagen"] = $datos[0]->prefijo_imagen;
            //$_SESSION["ruta"] = $datos[0]->ruta;
            header("Location: /Pacientes/resultados");
        } else {
            header("Location: /Pacientes/?error=1");
        }

        $usuario->close();
    }

    function login() {

        //Falta hacer todas las validaciones de acceso
        $user = $_REQUEST["user"];
        $pass = $_REQUEST["pass"];
        $tipo = $_REQUEST["tipo"];

        $usuario = new Usuarios();
        $datos = $usuario->getUsuario($user, $pass, $tipo);

        if (count($datos) > 0 && $tipo == "paciente") {
            session_start();
            //Ultima petición
            $_SESSION["ultimo_acceso"] = date("Y-m-d G:i:s");
            $_SESSION["id_paciente"] = $datos[0]->id;
            $_SESSION["id_sucursal"] = $datos[0]->id_sucursal;
            $_SESSION["paciente"] = $datos[0]->paciente;
            $_SESSION["expediente"] = $datos[0]->expediente;
            $_SESSION["tipo"] = $tipo;
            $_SESSION["prefijo_imagen"] = $datos[0]->prefijo_imagen;
            header("Location: /Pacientes/resultados");
        } else if (count($datos) > 0 && $tipo == "empresa") {
            session_start();
            //Ultima petición
            $_SESSION["ultimo_acceso"] = date("Y-m-d G:i:s");
            $_SESSION["id_empresa"] = $datos[0]->id;
            $_SESSION["id_sucursal"] = $datos[0]->id_sucursal;
            $_SESSION["empresa"] = $datos[0]->empresa;
            $_SESSION["tipo"] = $tipo;
            header("Location: /Pacientes/resultados-empresa");
        }  else if (count($datos) > 0 && $tipo == "doctor") {
            session_start();
            //Ultima petición
            $_SESSION["ultimo_acceso"] = date("Y-m-d G:i:s");
            $_SESSION["id_doctor"] = $datos[0]->id;
            $_SESSION["id_sucursal"] = $datos[0]->id_sucursal;
            $_SESSION["doctor"] = $datos[0]->doctor;
            $_SESSION["tipo"] = $tipo;
            header("Location: /Pacientes/resultados-doctor");
        } else {
            header("Location: /Pacientes/?error=1");
        }

        $usuario->close();
    }

    function logout() {
        session_start();
        session_destroy();
        header("Location: /Pacientes");
    }

    function session() {

        session_start();

        $ultimo_acceso = $_SESSION["ultimo_acceso"];

        $now = date("Y-m-d G:i:s");
        $tiempo = strtotime($now) - strtotime($ultimo_acceso);
        $limite = 45 * 60; //45 min 
        if ($tiempo <= $limite && ($_SESSION["id_paciente"] != "" || $_SESSION["id_empresa"] != "" || $_SESSION["id_doctor"] != "")) {

            $data["stop"] = false;
        } else {
            $data["stop"] = true;
        }

        $data["tiempo"] = $tiempo;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

}

new Acceso();

