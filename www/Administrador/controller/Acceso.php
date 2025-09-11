<?php

require_once('../model/Usuarios.php');

class Acceso {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'login') {
            $this->login();
        } else if ($opc == 'logout') {
            $this->logout();
        } else if ($opc == 'session') {
            $this->session();
        }
    }

    function login() {

        //Falta hacer todas las validaciones de acceso
        $user = $_REQUEST["user"];
        $pass = $_REQUEST["pass"];

        $usuario = new Usuarios();
        $datos = $usuario->getUsuario($user, $pass);

        if (count($datos) > 0) {
            session_start();
            //Ultima petición
            $_SESSION["ultimo_acceso"] = date("Y-m-d G:i:s");
            $_SESSION["id_admin"] = $datos[0]->id;
            $_SESSION["usuario"] = $datos[0]->usuario;
            header("Location: /Administrador/usuarios");
        } else {
            header("Location: /Administrador/?error=1");
        }
        
        $usuario->close();
    }

    function logout() {
        session_start();
        session_destroy();
        header("Location: /Administrador");
    }

    function session() {

        session_start();

        $ultimo_acceso = $_SESSION["ultimo_acceso"];

        $now = date("Y-m-d G:i:s");
        $tiempo = strtotime($now) - strtotime($ultimo_acceso);
        $limite = 45 * 60; //45 min 
        if ($tiempo <= $limite) {

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

