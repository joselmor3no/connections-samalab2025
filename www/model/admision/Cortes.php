<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/admision/Gastos.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/admision/Pagos.php');

class Cortes {

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

    function getIngresos() {
        $pagos = new Pagos();
        return $pagos->getPagosDia();
    }

    function getEgresos() {

        $gastos = new Gastos();
        return $gastos->getGastosDia();
    }

    function getCorte($id_sucursal) {
        $sql = "SELECT MAX(corte_numero)+1 AS no_corte
                FROM corte 
                INNER JOIN usuario ON (corte.id_usuario = usuario.id) 
                WHERE usuario.id_sucursal = '$id_sucursal'
                ORDER BY corte.id DESC";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addCorte($data) {

        $catalogos = new Catalogos();
        $id_sucursal = $data["id_sucursal"];

        $sql = "INSERT INTO corte ( corte_numero, fecha, ingresos, gastos, total, id_usuario )
        VALUES ( '" . $data["no_corte"] . "', NOW(), '" . $data["ingresos"] . "', '" . $data["egresos"] . "', '" . ( $data["ingresos"] - $data["egresos"]) . "', '" . $_SESSION["id"] . "' ) ";
        $this->conexion->setQuery($sql);

        $sql = "SELECT corte.id
                FROM corte 
                WHERE corte.id_usuario = '" . $_SESSION["id"] . "'
                ORDER BY corte.id DESC";

        $corte = $this->conexion->getQuery($sql);
        $id_corte = $corte[0]->id;

        //log_activity
        $datos = array(
            "observaciones" => "NUEVO CORTE: " . str_replace("'", "", $sql),
            "tabla" => "corte",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos->logActivity($datos);

        $sql = "UPDATE pago
            INNER JOIN orden ON pago.id_orden = orden.id
            SET pago.id_corte = '$id_corte'
            WHERE orden.id_sucursal = '$id_sucursal' AND pago.id_corte IS NULL ";
        $this->conexion->setQuery($sql);

        $sql = "UPDATE gasto
            INNER JOIN usuario ON usuario.id = gasto.id_usuario
            SET id_corte = '$id_corte'
            WHERE usuario.id_sucursal = '$id_sucursal' AND gasto.id_corte IS NULL ";
        $this->conexion->setQuery($sql);


        //Verificar si tiene un pago
        $sql = "UPDATE orden
        SET id_corte = '$id_corte'
        WHERE id_sucursal = '$id_sucursal' AND id_corte IS NULL AND (SELECT COUNT(*) FROM pago WHERE id_orden = orden.id AND id_corte IS NULL ) > 0 ";
        $this->conexion->setQuery($sql);
    }

    function getIngresosUltimoCorte() {
        $id_sucursal = $_SESSION["id_sucursal"];

        $sql = "SELECT pago.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, DATE_FORMAT(pago.fecha_pago, '%d/%m/%Y %H:%i') AS fecha,
                pago.pago, forma_pago.descripcion AS forma_pago, usuario.usuario, orden.consecutivo AS codigo
                FROM bitacora_paciente
                INNER JOIN orden ON orden.id = bitacora_paciente.id_orden
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                INNER JOIN pago ON (orden.id = pago.id_orden)
                INNER JOIN forma_pago ON (pago.id_forma_pago = forma_pago.id)
                INNER JOIN usuario ON (usuario.id = bitacora_paciente.id_usuario)
                LEFT JOIN bitacora_tarjeta ON bitacora_tarjeta.id_orden = orden.id
                WHERE bitacora_paciente.concepto = 'PAGO REALIZADO'
                AND pago.fecha_pago = bitacora_paciente.fecha AND pago.id_corte = (SELECT MAX(id) FROM corte WHERE id_usuario IN (SELECT id FROM usuario WHERE id_sucursal = $id_sucursal))
                ORDER BY pago.fecha_pago DESC";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getMetodoIngresosUltimoCorte() {
        $id_sucursal = $_SESSION["id_sucursal"];

        $sql = "SELECT forma_pago.descripcion AS metodo, SUM(pago.pago) AS total
                FROM bitacora_paciente
                INNER JOIN orden ON orden.id = bitacora_paciente.id_orden
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                INNER JOIN pago ON (orden.id = pago.id_orden)
                INNER JOIN forma_pago ON (pago.id_forma_pago = forma_pago.id)
                INNER JOIN usuario ON (usuario.id = bitacora_paciente.id_usuario)
                LEFT JOIN bitacora_tarjeta ON bitacora_tarjeta.id_orden = orden.id
                WHERE bitacora_paciente.concepto = 'PAGO REALIZADO'  
                AND pago.fecha_pago = bitacora_paciente.fecha AND pago.id_corte = (SELECT MAX(id) FROM corte WHERE id_usuario IN (SELECT id FROM usuario WHERE id_sucursal = $id_sucursal))
                GROUP BY forma_pago.id";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getDepartamentosIngresosUltimoCorte() {
        $id_sucursal = $_SESSION["id_sucursal"];

        $sql = "SELECT departamento.consecutivo AS codigo, departamento.departamento, (sum(pago.pago) * ((100 * sum(orden_estudio.precio_neto_estudio))/ sum(orden.importe)))/100 AS total
                FROM bitacora_paciente
                INNER JOIN orden ON (orden.id = bitacora_paciente.id_orden)
                INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden)
                INNER JOIN cat_estudio ON (orden_estudio.id_estudio = cat_estudio.id)
                INNER JOIN departamento ON (departamento.id = cat_estudio.id_departamento)
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                INNER JOIN pago ON (orden.id = pago.id_orden)
                INNER JOIN forma_pago ON (pago.id_forma_pago = forma_pago.id)
                INNER JOIN usuario ON (usuario.id = bitacora_paciente.id_usuario)
                LEFT JOIN bitacora_tarjeta ON bitacora_tarjeta.id_orden = orden.id
                WHERE bitacora_paciente.concepto = 'PAGO REALIZADO' 
                AND pago.fecha_pago = bitacora_paciente.fecha AND pago.id_corte = (SELECT MAX(id) FROM corte WHERE id_usuario IN (SELECT id FROM usuario WHERE id_sucursal = $id_sucursal))
                GROUP BY departamento.id";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getGastosUltimoCorte() {
        $id_sucursal = $_SESSION["id_sucursal"];

        $sql = "SELECT  concepto, importe, aclaracion, usuario.usuario, DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fecha
                FROM gasto
                INNER JOIN usuario ON (usuario.id = gasto.id_usuario)
                WHERE gasto.id_corte = (SELECT MAX(id) FROM corte WHERE id_usuario IN (SELECT id FROM usuario WHERE id_sucursal = $id_sucursal))
                ORDER BY gasto.fecha DESC";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getCorteUsuario($id_usuario) {
        $sql = "SELECT MAX(corte_numero) AS no_corte
                FROM corte 
                INNER JOIN usuario ON (corte.id_usuario = usuario.id) 
                WHERE  usuario.id_sucursal IN (SELECT id_sucursal FROM usuario WHERE id = $id_usuario)
                ORDER BY corte.id DESC";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
