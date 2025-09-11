<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');


class Cotizaciones {

    private $conexion;

    function __construct() {
        //Validación de session
        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso ) {
            header("Location: /");
        }

        $this->conexion = new Conexion();
    }

    function getCotizaciones($fecha_ini, $fecha_fin, $id_sucursal) {

        $sql = "SELECT orden.*, CONCAT(paciente.nombre, ' ', paciente.paterno , ' ', paciente.materno) AS paciente, DATE_FORMAT(paciente.fecha_nac, '%d/%m/%Y') AS fecha_nacimiento,
        DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y') AS fecha_orden, paciente.expediente
        FROM orden_cotizacion orden 
        INNER JOIN paciente ON (paciente.id = orden.id_paciente)
        WHERE orden.fecha_registro BETWEEN '$fecha_ini' AND '$fecha_fin 23:59:59' AND  orden.id_sucursal = '$id_sucursal'
        ORDER BY orden.id DESC";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getIdOrden($codigo, $id_sucursal) {

        $sql = "SELECT * 
            FROM orden_cotizacion
            WHERE consecutivo = '$codigo' AND id_sucursal = $id_sucursal";

        $datos = $this->conexion->getQuery($sql);
        $data = "";
        foreach ($datos AS $row) {
            $data = $row->id;
        }
        return $data;
    }

    function getOrdenPaciente($id_orden) {

        $sql = "SELECT paciente.id, orden.consecutivo AS codigo, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, paciente.expediente, "
                . "DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y') AS fecha, orden.id AS id_orden, DATE_FORMAT(paciente.fecha_nac, '%d/%m/%Y') AS fecha_nac, paciente.tel AS telefono, "
                . "paciente.sexo, paciente.edad, paciente.tipo_edad, orden.observaciones, orden.importe,
                CASE  
                    WHEN orden.id_doctor IS NOT NULL THEN doctor.nombre  
                    ELSE orden.nombre_doctor  
                END AS doctor "
                . "FROM orden_cotizacion orden "
                . "INNER JOIN paciente ON (paciente.id = orden.id_paciente) "
                . "LEFT JOIN doctor ON (doctor.id = orden.id_doctor) "
                . "WHERE orden.id = '$id_orden'";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getOrdenDetalle($id_orden) {

        $sql = "SELECT orden_estudio.*, cat_estudio.no_estudio, cat_estudio.alias, cat_estudio.nombre_estudio, cat_estudio.tipo, estudio.precio_publico, paquete.alias AS paquete, paquete.nombre AS nombre_paquete, paquete.precio AS precio_paquete, paquete_estudio.precio_neto AS precio_detalle_paquete, DATE_FORMAT(orden_estudio.fecha_entrega, '%d/%m/%Y') AS fecha_entrega
                FROM orden_estudio_cotizacion orden_estudio "
                . "INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) "
                . "INNER JOIN estudio ON (estudio.id_cat_estudio = orden_estudio.id_estudio AND estudio.id_sucursal = (SELECT id_sucursal FROM orden_cotizacion WHERE id = $id_orden ) ) "
                . "LEFT JOIN paquete ON (paquete.id = orden_estudio.id_paquete) "
                . "LEFT JOIN paquete_estudio ON (paquete_estudio.id_paquete = orden_estudio.id_paquete AND paquete_estudio.id_estudio = orden_estudio.id_estudio )  "
                . "WHERE orden_estudio.id_orden_cotizacion = '$id_orden'";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getOrdenIndicaciones($id_orden) {

        $sql = "SELECT cat_estudio.nombre_estudio, indicaciones.indicacion
                FROM orden_estudio_cotizacion orden_estudio "
                . "INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) "
                . "INNER JOIN estudio ON (estudio.id_cat_estudio = orden_estudio.id_estudio AND estudio.id_sucursal = (SELECT id_sucursal FROM orden_cotizacion WHERE id = $id_orden ) ) "
                . "INNER JOIN indicaciones ON (indicaciones.id = estudio.id_indicaciones) "
                . "WHERE orden_estudio.id_orden_cotizacion = '$id_orden'";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }
    
      function getSucursalPaciente($expediente) {

        $sql = "SELECT *
                FROM paciente "
                . "WHERE expediente = '$expediente'";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
