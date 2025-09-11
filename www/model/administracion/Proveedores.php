<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Proveedores {

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

    function getProveedores($id_sucursal) {

        $sql = "SELECT proveedor.*, cat_municipio.municipio, tipo_proveedor.nombre AS tipo_proveedor
        FROM proveedor
        LEFT JOIN cat_municipio ON (cat_municipio.id = proveedor.id_municipio)
        LEFT JOIN tipo_proveedor ON (tipo_proveedor.id = proveedor.id_tipo_proveedor)
        WHERE proveedor.id_sucursal = '$id_sucursal'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getTipoProveedor($id_sucursal) {

        $sql = "SELECT *
        FROM tipo_proveedor
        WHERE  id_cliente = (SELECT id_cliente FROM sucursal WHERE id = " . $id_sucursal . ")
        ORDER BY nombre";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getProveedor($id) {

        $sql = "SELECT *
        FROM proveedor
        WHERE id = '$id'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addTipoProveedor($data) {
        $sql = "INSERT INTO tipo_proveedor (nombre, id_cliente) "
                . "SELECT '" . $data["nombre"] . "', id_cliente FROM sucursal WHERE id = " . $data["id_sucursal"] ."";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO TIPO PROEEDOR: " . str_replace("'", "", $sql),
            "tabla" => "proveedor",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addProveedor($data) {

        $sql = "INSERT INTO proveedor (codigo, nombre, id_estado, id_municipio, contacto, telefono, id_tipo_proveedor, id_sucursal, id_cliente) "
                . "SELECT COALESCE(MAX(proveedor.codigo ), 0) + 1  , '" . $data["nombre"] . "', '" . $data["id_estado"] . "', '" . $data["id_municipio"] . "', '" . $data["contacto"] . "', '" . $data["telefono"] . "', "
                . "'" . $data["id_tipo_proveedor"] . "', '" . $data["id_sucursal"] . "', sucursal.id_cliente "
                . "FROM sucursal "
                . "INNER JOIN proveedor ON (sucursal.id = proveedor.id_sucursal) "
                . "WHERE id_sucursal = " . $data["id_sucursal"] . " ";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO PROVEEDOR: " . str_replace("'", "", $sql),
            "tabla" => "proveedor",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editProveedor($data) {

        $sql = "UPDATE proveedor SET "
                . "nombre = '" . $data["nombre"] . "', id_estado = '" . $data["id_estado"] . "', id_municipio = '" . $data["id_municipio"] . "', contacto = '" . $data["contacto"] . "' , "
                . "telefono = '" . $data["telefono"] . "', id_tipo_proveedor = '" . $data["id_tipo_proveedor"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE PROVEEDOR: " . str_replace("'", "", $sql),
            "tabla" => "proveedor",
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
