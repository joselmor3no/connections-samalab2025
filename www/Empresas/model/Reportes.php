<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/Empresas/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Reportes {

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

    function getCorte($id_sucursal, $fecha_ini, $fecha_fin) {

        $sql = "SELECT corte.*, usuario.usuario, sucursal.nombre "
                . "FROM `corte` "
                . "INNER JOIN usuario ON corte.id_usuario = usuario.id "
                . "INNER JOIN sucursal ON sucursal.id = usuario.id_sucursal "
                . "WHERE sucursal.id = '$id_sucursal' AND "
                . "corte.fecha BETWEEN '$fecha_ini' AND '$fecha_fin 23:59:59' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }


    function getSucursales($id_cliente, $fecha_ini, $fecha_fin) {
        $sql = "SELECT  sucursal.nombre as nombresuc, 
        sum(IF (orden.credito=1 and orden.cancelado=0, orden.importe,0)) as costo_credito,
        sum(IF (orden.credito=0 and orden.cancelado=0, orden.importe,0)) as costo_contado,
        sum(IF (orden.saldo_deudor>0 and orden.cancelado=0 and orden.credito=0,
        orden.saldo_deudor,0)) as saldo_deudor,
        sum(IF (orden.cancelado=1 , orden.importe,0)) as saldo_cancelado
        from sucursal
        INNER join orden on sucursal.id = orden.id_sucursal
        where sucursal.id_cliente = '$id_cliente'
        and orden.fecha_registro between  '$fecha_ini 00:00:00' and '$fecha_fin 23:59:59'
        group by sucursal.id
        ";
     
        $data = $this->conexion->getQuery($sql);
        return $data;
    }


    function ComisionesMedicas($id_sucursal, $fecha_ini, $fecha_fin) {
        $sql = "SELECT sum(orden.importe) as total,sum(orden.saldo_deudor) as deuda,doctor.id, doctor.nombre, doctor.porcentaje ,orden.consecutivo FROM doctor
        INNER JOIN orden ON orden.id_doctor = doctor.id
        WHERE doctor.porcentaje > 0 and orden.cancelado=0
        AND orden.fecha_registro >= '$fecha_ini' AND orden.fecha_registro <= '$fecha_fin 23:59:59'
        AND doctor.id_sucursal = '$id_sucursal'
        GROUP BY doctor.id";
     
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function EmpresasCredito($id_sucursal, $fecha_ini, $fecha_fin) {
        $sql = "SELECT empresa.id, empresa.nombre, sum(orden_estudio.precio_neto_estudio) as total 
        FROM empresa 
        INNER JOIN orden ON (empresa.id = orden.id_empresa) 
        INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) 
        INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) 
        WHERE empresa.id_sucursal ='$id_sucursal' AND orden.fecha_registro >= date('$fecha_ini') AND orden.fecha_registro <= date('$fecha_fin') AND orden.credito=1 
        GROUP BY empresa.id";
     
        $data = $this->conexion->getQuery($sql);
        return $data;
    }



    function getEstudios($id_sucursal, $fecha_ini, $fecha_fin) {
        $sql = "SELECT ce.no_estudio, ce.nombre_estudio,oe.precio_neto_estudio,o.credito,
        COUNT(oe.id) as vendidos,
        sum(IF (o.credito=1, oe.precio_neto_estudio,0)) as costo_credito,
        sum(IF (o.credito=0, oe.precio_neto_estudio,0)) as costo_contado
        FROM orden o 
        inner join orden_estudio oe on o.id=oe.id_orden 
        inner join cat_estudio ce on ce.id=oe.id_estudio
        where o.fecha_registro BETWEEN '$fecha_ini' and '$fecha_fin 23:59:59' and o.cancelado=0 and o.id_sucursal='$id_sucursal'
        group by ce.id";
     
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getVenta($id_sucursal, $fecha_ini, $fecha_fin) {

        $sql = "SELECT cat_estudio.id,cat_estudio.no_estudio,cat_estudio.nombre_estudio,
        COUNT(IF(orden.credito='1',1, NULL)) as creditoSI,COUNT(IF(orden.credito='0',1, NULL)) as creditoNo,
        COUNT(orden_estudio.id_estudio) as ventas ,
        sum(IF (orden.credito=0, orden_estudio.precio_neto_estudio,0)) as costo_contado,
        sum(IF (orden.credito=1, orden_estudio.precio_neto_estudio,0)) as costo_credito
         FROM orden
        INNER JOIN orden_estudio ON orden.id=orden_estudio.id_orden
        INNER JOIN cat_estudio ON cat_estudio.id=orden_estudio.id_estudio
        INNER JOIN sucursal ON sucursal.id=orden.id_sucursal
        WHERE sucursal.id='$id_sucursal' 
        AND orden.fecha_registro BETWEEN '$fecha_ini' AND '$fecha_fin 23:59:59'
        GROUP BY cat_estudio.id
        ORDER BY ventas DESC";
      

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getCorteDepartamento($id_sucursal, $fecha_ini, $fecha_fin) {

        $sql = "SELECT SUM(orden_estudio.precio_neto_estudio) AS total, departamento.departamento, departamento.id 
        FROM orden 
        INNER JOIN orden_estudio ON orden.id = orden_estudio.id_orden 
        INNER JOIN cat_estudio ON cat_estudio.id = orden_estudio.id_estudio 
        INNER JOIN departamento ON departamento.id = cat_estudio.id_departamento 
        INNER JOIN sucursal ON sucursal.id = orden.id_sucursal 
        WHERE sucursal.id = '$id_sucursal' AND orden.fecha_registro BETWEEN '$fecha_ini' AND '$fecha_fin 23:59:59'
        GROUP BY departamento.id";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
