<?php

require_once('../../model/catalogos/Users.php');

class Usuario {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'alias') {
            $this->alias();
        } else if ($opc == 'delete') {
            $this->delete();
        } else if ($opc == 'permisos') {
            $this->permisos();
        } else if ($opc == 'save-permisos') {
            $this->savePermisos();
        } else if ($opc == 'save-informes') {
            $this->savePermisosInformes(); 
        }
    }

    function registro() {
        //var_dump($_REQUEST);
    
        $data = array(
            "id" => $_REQUEST["id_usuario"],
            "user" => $_REQUEST["user"],
            "pass" => $_REQUEST["pass"],
            "nombre" => $_REQUEST["nombre"],
            "prefijo" => $_REQUEST["prefijo"],
            "entrada" => $_REQUEST["entrada"] != "" ? "'" . $_REQUEST["entrada"] . "'" : "NULL",
            "salida" => $_REQUEST["salida"] != "" ? "'" . $_REQUEST["salida"] . "'" : "NULL",
            "lista_sucursales" => (isset($_REQUEST["lista_sucursales"]) && count($_REQUEST["lista_sucursales"])) >0 ? $_REQUEST["lista_sucursales"]  : "NULL",
            "id_tipo_empleado" => $_REQUEST["id_tipo_empleado"] != "" ? $_REQUEST["id_tipo_empleado"] : "NULL",
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $usuarios = new Users();
        if ($_REQUEST["id_usuario"] == "") {
            $usuarios->addUsuario($data);
        } else {
            $usuarios->editUsuario($data);
        }

        $usuarios->close();
        header("Location: /usuarios?msg=ok");
    }

    function delete() {
        $id_usuario = $_REQUEST["id_usuario"];

        $usuarios = new Users();
        $usuarios->deleteUsuario($id_usuario);

        $usuarios->close();
        header("Location: /usuarios?msg=ok");
    }

    function alias() {

        $alias = $_REQUEST["alias"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $usuarios = new Users();
        $data = $usuarios->aliasUsuario($alias, $id_sucursal);

        $usuarios->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function permisos() {

        $id_usuario = $_REQUEST["id_usuario"];

        $usuarios = new Users();
        $data = $usuarios->getPermisosUsuario($id_usuario);

        $usuarios->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function savePermisos() {

        $usuarios = new Users();
        $usuarios->addPermisosUsuario($_REQUEST);

        $usuarios->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function savePermisosInformes() {

        $usuarios = new Users();
        $usuarios->addPermisosInformesUsuario($_REQUEST);

        $usuarios->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

}

new Usuario();

