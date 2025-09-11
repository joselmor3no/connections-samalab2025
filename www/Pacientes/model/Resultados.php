<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');

class Resultados {

    private $conexion;

    function __construct() {
        $this->conexion = new Conexion();
    }

    function obtenerListaOrdenesImagen($id_paciente){
        $sql="SELECT s.prefijo_imagen,ce.nombre_estudio, o.credito, d.ruta,d.archivo_zip, o.id,o.consecutivo,d.id as dcm,o.saldo_deudor,o.fecha_registro,dr.id as id_resultado, dr.cerrado,d.local FROM orden o 
        inner join dcm d on d.id_orden=o.id 
        inner join cat_estudio ce on ce.id=d.id_categoria  
        inner join sucursal s on s.id=o.id_sucursal 
        left join dcm_resultado dr on dr.id_dcm=d.id where o.id_paciente=".$id_paciente;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function obtenerListaOrdenesImagenMedico($medico){
        echo '***';
        $sql="SELECT GROUP_CONCAT(DISTINCT lde.nombre) as documentos,CONCAT(p.nombre,' ',p.paterno,' ',p.materno) as paciente,s.nombre as nombre_sucursal,s.prefijo_imagen,ce.nombre_estudio, o.credito, d.ruta,d.archivo_zip, o.id,o.consecutivo,d.id as dcm,o.saldo_deudor,o.fecha_registro,dr.id as id_resultado, dr.cerrado,d.local 
        FROM orden o 
        inner join dcm d on d.id_orden=o.id 
        inner join cat_estudio ce on ce.id=d.id_categoria  
        inner join sucursal s on s.id=o.id_sucursal 
        inner join paciente p on p.id=o.id_paciente
        left join doctor doc on doc.id=o.id_doctor 
        left join orden_documentos_externos ode on ode.id_orden=o.id
        left join lista_documentos_externos lde on lde.id=ode.id_documento_externo
        left join dcm_resultado dr on dr.id_dcm=d.id where doc.id=".$medico."  GROUP BY CONCAT(o.id,ce.id)  ORDER BY o.id DESC limit 100";
        //echo $sql;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function obtenerListaOrdenesImagenEmpresa($empresa){
        $sql="SELECT CONCAT(p.nombre,' ',p.paterno,' ',p.materno) as paciente,s.nombre as nombre_sucursal,s.prefijo_imagen,ce.nombre_estudio, o.credito, d.ruta,d.archivo_zip, o.id,o.consecutivo,d.id as dcm,o.saldo_deudor,o.fecha_registro,dr.id as id_resultado, dr.cerrado,d.local FROM orden o 
        inner join dcm d on d.id_orden=o.id 
        inner join cat_estudio ce on ce.id=d.id_categoria  
        inner join sucursal s on s.id=o.id_sucursal 
        inner join paciente p on p.id=o.id_paciente
        left join doctor doc on doc.id=o.id_doctor 
        left join dcm_resultado dr on dr.id_dcm=d.id where o.id_empresa=".$empresa." and dr.cerrado=1 ORDER BY o.id DESC limit 100";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getOrdenes($id_paciente) {

        $sql = "SELECT *,GROUP_CONCAT(DISTINCT documentos) as documentos, GROUP_CONCAT(nombre_estudio SEPARATOR ' | ') AS estudios, COUNT(*) AS no_estudios, SUM(reportado) AS reportado, (SELECT GROUP_CONCAT(pdf SEPARATOR ',') FROM orden_estudio WHERE id_orden = A.id) AS estudios_pdf 
            FROM (SELECT orden.id, orden.consecutivo, cat_estudio.nombre_estudio, paciente.expediente, 
            DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y %H:%i') AS fecha_orden, orden.saldo_deudor, orden.credito,
            (SELECT COUNT(*) FROM log_resultados_impresion WHERE id_orden = orden.id AND id_estudio = orden_estudio.id_estudio AND reset  = 0 ) AS reportado, lde.nombre as documentos 
            FROM orden
            INNER JOIN paciente ON (paciente.id = orden.id_paciente) 
            INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) 
            INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) 
            left join orden_documentos_externos ode on ode.id_orden=orden.id
            left join lista_documentos_externos lde on lde.id=ode.id_documento_externo
            WHERE paciente.expediente = '$id_paciente' and orden.sucursal_maquila IS NULL) A
            WHERE (saldo_deudor = 0 OR credito = 1)
            GROUP BY id
            ORDER BY consecutivo DESC";

        
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getSucursal($id_paciente) {

        $sql = "SELECT sucursal.*, cliente.nombre AS cliente, cliente.direccion AS direccion_cliente, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente 
            FROM paciente
            INNER JOIN sucursal ON (paciente.id_sucursal = sucursal.id)
            INNER JOIN cliente ON (cliente.id = sucursal.id_cliente)
            WHERE paciente.id = '$id_paciente'";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getSucursalEmpresa($id_empresa) {

        $sql = "SELECT sucursal.*, cliente.nombre AS cliente, cliente.direccion AS direccion_cliente, empresa.nombre AS empresa
            FROM empresa
            INNER JOIN sucursal ON (empresa.id_sucursal = sucursal.id)
            INNER JOIN cliente ON (cliente.id = sucursal.id_cliente)
            WHERE empresa.id = '$id_empresa'";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getOrdenesEmpresa($id_empresa) {

        $sql = "SELECT *,GROUP_CONCAT(DISTINCT documentos) as documentos, GROUP_CONCAT(nombre_estudio SEPARATOR ' | ') AS estudios, COUNT(*) AS no_estudios, SUM(reportado) AS reportado, (SELECT GROUP_CONCAT(pdf SEPARATOR ',') FROM orden_estudio WHERE id_orden = A.id) AS estudios_pdf  
            FROM (SELECT orden.id, orden.consecutivo, cat_estudio.nombre_estudio, paciente.expediente,  CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, 
            DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y %H:%i') AS fecha_orden, orden.saldo_deudor, orden.credito,
            (SELECT COUNT(*) FROM log_resultados_impresion WHERE id_orden = orden.id AND id_estudio = orden_estudio.id_estudio AND reset  = 0 ) AS reportado,lde.nombre as documentos
            FROM orden
            INNER JOIN empresa ON (empresa.id = orden.id_empresa) 
            INNER JOIN paciente ON (paciente.id = orden.id_paciente) 
            INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) 
            left join orden_documentos_externos ode on ode.id_orden=orden.id
            left join lista_documentos_externos lde on lde.id=ode.id_documento_externo
            INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio)
            WHERE orden.id_empresa = '$id_empresa' AND DATE_SUB(CURDATE(), INTERVAL 180 DAY) < DATE(orden.fecha_registro )) A
            WHERE A.reportado > 0 AND (saldo_deudor = 0 OR credito = 1)
            GROUP BY id
            ORDER BY consecutivo DESC";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getSucursalDoctor($id_doctor) {

        $sql = "SELECT sucursal.*, cliente.nombre AS cliente, cliente.direccion AS direccion_cliente, CONCAT(doctor.apaterno,' ',doctor.amaterno,' ',doctor.nombre) AS doctor
            FROM doctor
            INNER JOIN sucursal ON (doctor.id_sucursal = sucursal.id)
            INNER JOIN cliente ON (cliente.id = sucursal.id_cliente)
            WHERE doctor.id = '$id_doctor'";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getOrdenesDoctor($id_empresa) { 
        $masSucursales="and orden.id_sucursal=".$_SESSION['id_sucursal']." ";
        if($_SESSION['id_sucursal']==123 || $_SESSION['id_sucursal']==124 || $_SESSION['id_sucursal']==1241){
            $masSucursales=" and (orden.id_sucursal=123 || orden.id_sucursal=124 || orden.id_sucursal=141)";
        }
        $sql = "SELECT *,GROUP_CONCAT(DISTINCT documentos) as documentos, GROUP_CONCAT(nombre_estudio SEPARATOR ' | ') AS estudios, COUNT(*) AS no_estudios, SUM(reportado) AS reportado, (SELECT GROUP_CONCAT(pdf SEPARATOR ',') FROM orden_estudio WHERE id_orden = A.id) AS estudios_pdf  
            FROM (SELECT s.nombre as nombre_sucursal, orden.id, orden.consecutivo, cat_estudio.nombre_estudio, paciente.expediente,  CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, 
            DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y %H:%i') AS fecha_orden, orden.saldo_deudor, orden.credito,
            (SELECT COUNT(*) FROM log_resultados_impresion WHERE id_orden = orden.id AND id_estudio = orden_estudio.id_estudio AND reset  = 0 ) AS reportado,lde.nombre as documentos
            FROM orden
            INNER JOIN doctor ON (doctor.id = orden.id_doctor) 
            INNER JOIN paciente ON (paciente.id = orden.id_paciente) 
            INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) 
            INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) 
            INNER JOIN sucursal s on s.id=orden.id_sucursal 
            left join orden_documentos_externos ode on ode.id_orden=orden.id
            left join lista_documentos_externos lde on lde.id=ode.id_documento_externo 
            WHERE orden.id_doctor = '$id_empresa' ".$masSucursales." ) A
            WHERE A.reportado > 0 AND (saldo_deudor = 0 OR credito = 1 ) 
            GROUP BY id
            ORDER BY consecutivo DESC limit 100";
         
        
        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function close() {

        $this->conexion->close();
    }

    function reporteEstudios($id_orden, $expediente, $id_sucursal) {
        $sql = " SELECT id_tipo_reporte, SUM(reportado) AS reportado FROM (
                SELECT 
                CASE  
                WHEN paquete.id IS NOT NULL AND paquete.id_tipo_reporte IS NOT NULL  THEN 4
                WHEN paquete.id IS NOT NULL AND paquete.id_tipo_reporte IS NULL AND (cat_estudio.alias != 'BH' AND cat_estudio.alias != 'EGO' AND estudio.id_tipo_reporte != 5) THEN 3
                ELSE estudio.id_tipo_reporte
                END AS id_tipo_reporte,
                CASE  
                    WHEN cat_estudio.resultado_componente = 1   
                    THEN (SELECT COUNT(*) FROM resultado_estudio WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio  )
                    ELSE (SELECT COUNT(*) FROM resultado_estudio_texto WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio  )  
                  END AS reportado
                FROM orden_estudio
                INNER JOIN orden ON (orden.id = orden_estudio.id_orden) 
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) 
                INNER JOIN estudio ON (estudio.id_cat_estudio = cat_estudio.id AND estudio.id_sucursal =  $id_sucursal )
                LEFT JOIN materia_biologica ON (materia_biologica.id = cat_estudio.id_materia_biologica) 
                LEFT JOIN paquete ON (paquete.id = orden_estudio.id_paquete) 
                WHERE orden_estudio.id_orden =  $id_orden AND paciente.expediente ='$expediente' 
                ORDER BY orden_estudio.posicion) A  
                GROUP BY id_tipo_reporte";

        $estudios = $this->conexion->getQuery($sql);
        return $estudios;
    }

}

?>
