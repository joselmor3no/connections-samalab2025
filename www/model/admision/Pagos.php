<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Pagos {

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

    function getPagos($codigo, $id_sucursal) {
        $sql = "SELECT CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y') AS fecha_orden,
                orden.*
                FROM orden 
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                WHERE orden.consecutivo = '$codigo' AND orden.id_sucursal = '$id_sucursal'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addPago($data) {
        $hoy = date("Y-m-d H:i:s"); //es necesario que sea asi para poder relacionar con bitacora_paciente

        $sql = "INSERT INTO `pago`(`monto_pagar`, `pago`, `saldo_deudor`, `aclaraciones`, `id_orden`, 
                `id_forma_pago`, `fecha_pago`) 
                SELECT saldo_deudor, " . $data["pago"] . ", saldo_deudor-" . $data["pago"] . ", '" . $data["aclaraciones"] . "', " . $data["id_orden"] . ", "
                . $data["id_forma_pago"] . ", '$hoy' FROM orden WHERE id = " . $data["id_orden"] . " AND id_sucursal = " . $data["id_sucursal"] . "";

        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "NUEVO PAGO: " . str_replace("'", "", $sql),
            "tabla" => "pago",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($datos);

        $sql = "UPDATE orden 
                SET  saldo_deudor = saldo_deudor-" . $data["pago"] . " 
                WHERE id = " . $data["id_orden"];
        $this->conexion->setQuery($sql);

        $sql = "INSERT INTO `bitacora_paciente`(`id_paciente`, `nombre`, `fecha`, `concepto`, `monto`, 
                `id_usuario`, `id_sucursal`, `id_orden`) 
                SELECT paciente.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno), '$hoy', 'PAGO REALIZADO', " . $data["pago"] . ", "
                . $_SESSION["id"] . ", " . $data["id_sucursal"] . ", " . $data["id_orden"] . " 
                FROM orden 
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                WHERE orden.id = " . $data["id_orden"];
        $this->conexion->setQuery($sql);
    }

    function getPagosDia() {
        $sql = "SELECT pago.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, DATE_FORMAT(pago.fecha_pago, '%d/%m/%Y %H:%i') AS fecha,
                pago.pago, forma_pago.descripcion AS forma_pago, usuario.usuario, orden.consecutivo AS codigo
                FROM bitacora_paciente
                INNER JOIN orden ON orden.id = bitacora_paciente.id_orden
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                INNER JOIN pago ON (orden.id = pago.id_orden)
                INNER JOIN forma_pago ON (pago.id_forma_pago = forma_pago.id)
                INNER JOIN usuario ON (usuario.id = bitacora_paciente.id_usuario)
                LEFT JOIN bitacora_tarjeta ON bitacora_tarjeta.id_orden = orden.id
                WHERE bitacora_paciente.concepto = 'PAGO REALIZADO' AND  pago.fecha_pago = bitacora_paciente.fecha AND bitacora_paciente.id_sucursal = '" . $_SESSION["id_sucursal"] . "'  
                 AND pago.id_corte IS NULL 
                ORDER BY pago.fecha_pago DESC";
                //AND pago.fecha_pago = bitacora_paciente.fecha   | algo no coincide aque DATE_FORMAT(pago.fecha_pago, '%d/%m/%Y %H:%i')

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deletePagos($id_pagos) {

        $sql = "UPDATE orden
                SET  saldo_deudor = saldo_deudor+(SELECT pago FROM pago WHERE id =  $id_pagos ) 
                WHERE id = (SELECT id_orden FROM pago WHERE id =  $id_pagos ) ";
        $this->conexion->setQuery($sql);

        $sql = "INSERT INTO `bitacora_paciente`(`id_paciente`, `nombre`, `fecha`, `concepto`, `monto`, 
                `id_usuario`, `id_sucursal`, `id_orden`) 
                SELECT paciente.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno), NOW(), 'DESAPLICACION DE PAGO', pago.pago, "
                . $_SESSION["id"] . ", orden.id_sucursal, orden.id 
                FROM orden 
                INNER JOIN pago ON (orden.id = pago.id_orden)
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                WHERE pago.id = " . $id_pagos;
        $this->conexion->setQuery($sql);


        $sql = "DELETE FROM  pago 
                WHERE id= $id_pagos";
        $this->conexion->setQuery($sql);
    }

    function close() {

        $this->conexion->close();
    }

}

?>
