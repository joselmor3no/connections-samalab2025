<?php

require_once('../model/Empresas.php');

class Empresa {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'delete') {
            $this->delete();
        } else if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'get-sucursales') {
            $this->getSucursales();
        } else if ($opc == 'get-usuarios') {
            $this->getUsuarios();
        }
    }

    function registro() {

        $data = array(
            "id" => $_REQUEST["id_empresa"],
            "prefijo" => $_REQUEST["prefijo"],
            "nombre" => $_REQUEST["nombre"],
            "direccion" => $_REQUEST["direccion"],
            "id_cat_estados" => $_REQUEST["id_cat_estados"],
            "id_cat_municipio" => $_REQUEST["id_cat_municipio"],
            "telefono" => $_REQUEST["telefono"],
            "correo" => $_REQUEST["correo"],
            "max_sucursales" => $_REQUEST["max_sucursales"],
            "fecha_alta" => $_REQUEST["fecha_alta"],
            "fecha_vence" => $_REQUEST["fecha_vence"],
            "laboratorio" => $_REQUEST["laboratorio"] == "on" ? 1 : 0,
            "teleradiologia" => $_REQUEST["teleradiologia"] == "on" ? 1 : 0,
            "inactivo" => $_REQUEST["inactivo"] == "on" ? 1 : 0,
            "maquila" => $_REQUEST["maquila"] == "on" ? 1 : 0,
            "usuario" => $_REQUEST["usuario"],
            "password" => $_REQUEST["password"],
            "id_admin" => $_REQUEST["id_admin"],
        );

        $empresas = new Empresas();
        if ($_REQUEST["id_empresa"] == "") {
            $empresas->addEmpresa($data);
        } else {
            $empresas->editEmpresa($data);
        }

        $empresas->close();
        header("Location: /Administrador/empresas?msg=ok");
    }

    function delete() {
        $id_empresa = $_REQUEST["id_empresa"];

        $empresas = new Empresas();
        $empresas->deleteEmpresa($id_empresa);

        $empresas->close();
        header("Location: /Administrador/empresas?msg=ok");
    }

    function getSucursales() {

        $id_cliente = $_REQUEST["id_cliente"];

        $empresas = new Empresas();
        $data = $empresas->getSucurales($id_cliente);

        $empresas->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function getUsuarios() {

        $id_sucursal = $_REQUEST["id_sucursal"];

        $empresas = new Empresas();
        $data = $empresas->getUsuarios($id_sucursal);

        $empresas->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

}

new Empresa();

