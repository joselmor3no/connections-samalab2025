<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once('Conexion.php');

class Home {

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

    function getOrdenesDia($id_sucursal) {

        $sql = "SELECT count(*)  AS total
            FROM orden 
            WHERE id_sucursal = $id_sucursal AND cancelado = 0 AND DATE(fecha_registro ) = CURDATE()";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getCotizacionDia($id_sucursal) {

        $sql = "SELECT count(*) AS total
            FROM orden_cotizacion 
            WHERE id_sucursal = $id_sucursal AND cancelado = 0 AND DATE(fecha_registro ) = CURDATE()";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getResultados($id_sucursal) {

        $sql = "SELECT COUNT(*) AS total, SUM(reportado) AS reportados 
        FROM (SELECT 
        CASE WHEN cat_estudio.resultado_componente = 1   
                 THEN (SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END FROM resultado_estudio WHERE id_orden = orden.id AND id_estudio = orden_estudio.id_estudio  )
                 ELSE (SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END FROM resultado_estudio_texto WHERE id_orden = orden.id AND id_estudio = orden_estudio.id_estudio  )  
        END AS reportado
        FROM  orden 
        INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden)
        INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio)
        WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) < DATE(orden.fecha_registro ) AND cancelado = 0 AND  orden.id_sucursal = $id_sucursal
        ) A";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getSeccionesOrdenDia($id_sucursal) {

        $sql = "SELECT secciones.seccion, COUNT(*) AS total 
        FROM orden 
        INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) 
        INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) 
        INNER JOIN secciones ON (secciones.id = cat_estudio.id_secciones) 
        WHERE DATE(orden.fecha_registro ) = CURDATE() AND cancelado = 0 AND orden.id_sucursal = $id_sucursal
        GROUP BY secciones.id";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getHorarioOrdenDia($id_sucursal) {

        $sql = "SELECT HOUR(orden.fecha_registro ) AS hora, COUNT(*) AS total
	FROM orden 
	WHERE DATE(orden.fecha_registro ) = CURDATE() AND cancelado = 0 AND orden.id_sucursal = $id_sucursal
	GROUP BY HOUR(orden.fecha_registro )";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
