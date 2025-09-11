<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Gastos {

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

    function getGastos() {
        $sql = "SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y % %H:%i') AS fecha
                FROM gasto 
                WHERE id_usuario = '" . $_SESSION["id"] . "' AND id_corte IS NULL
                ORDER BY fecha DESC";


        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getGastosDia() {
        $sql = "SELECT  concepto, importe, aclaracion, usuario.usuario, DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fecha
                FROM gasto
                INNER JOIN usuario ON (usuario.id = gasto.id_usuario)
                WHERE usuario.id_sucursal = '" . $_SESSION["id_sucursal"] . "' AND gasto.id_corte IS NULL
                ORDER BY gasto.fecha DESC";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addGasto($data) {

        $sql = "INSERT INTO `gasto`(`concepto`, `importe`, `aclaracion`, `fecha`, `id_usuario`) 
               VALUES ('" . $data["concepto"] . "', " . $data["importe"] . ", '" . $data["aclaracion"] . "', NOW(), " . $_SESSION["id"] . ")";

        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "NUEVO GASTO: " . str_replace("'", "", $sql),
            "tabla" => "gasto",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($datos);
    }

    function close() {

        $this->conexion->close();
    }

}

?>
