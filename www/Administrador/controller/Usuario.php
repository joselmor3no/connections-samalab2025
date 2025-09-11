<?php

require_once('../model/Usuarios.php');

class Usuario {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'delete') {
            $this->delete();
        } else if ($opc == 'registro') {
            $this->registro();
        }
    }

    function registro() {

        $data = array(
            "id" => $_REQUEST["id_usuario"],
            "nombre" => $_REQUEST["nombre"],
            "usuario" => $_REQUEST["usuario"],
            "password" => $_REQUEST["password"],
            "id_rol" => $_REQUEST["id_rol"],
            "id_admin" => $_REQUEST["id_admin"],
        );

        $usuarios = new Usuarios();
        if ($_REQUEST["id_usuario"] == "") {
            $usuarios->addUsuario($data);
        } else {
            $usuarios->editUsuario($data);
        }

        $usuarios->close();
        header("Location: /Administrador/usuarios?msg=ok");
    }

    function delete() {
        $id_usuario = $_REQUEST["id_usuario"];

        $usuarios = new Usuarios();
        $usuarios->deleteUsuario($id_usuario);

        $usuarios->close();
        header("Location: /Administrador/usuarios?msg=ok");
    }

}

new Usuario();

