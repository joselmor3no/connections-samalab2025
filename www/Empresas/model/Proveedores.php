<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/Empresas/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Proveedores {

    private $conexion;

    function __construct() {
        //Validación de session
        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso) {
            header("Location: /Empresas");
        }

        $this->conexion = new Conexion();
    }

    function getProveedores($id_cliente) {

        $sql = "SELECT proveedor.*, cat_municipio.municipio, tipo_proveedor.nombre AS tipo_proveedor
        FROM proveedor
        LEFT JOIN cat_municipio ON (cat_municipio.id = proveedor.id_municipio)
        LEFT JOIN tipo_proveedor ON (tipo_proveedor.id = proveedor.id_tipo_proveedor)
        WHERE proveedor.id_cliente = '$id_cliente' AND proveedor.id_sucursal IS NULL";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getTipoProveedor($id_cliente) {

        $sql = "SELECT *
        FROM tipo_proveedor
        WHERE id_cliente = $id_cliente
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
                . "VALUES('" . $data["nombre"] . "', '" . $data["id_cliente"] . "')";

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

        $sql = "INSERT INTO proveedor (nombre, id_estado, id_municipio, contacto, telefono, id_tipo_proveedor, id_cliente) "
                . "VALUES('" . $data["nombre"] . "', '" . $data["id_estado"] . "', '" . $data["id_municipio"] . "', '" . $data["contacto"] . "', '" . $data["telefono"] . "', '" . $data["id_tipo_proveedor"] . "', '" . $data["id_cliente"] . "')";

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
            "id_tabla" => $data["id_cliente"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function close() {

        $this->conexion->close();
    }

}

?>
