<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');

class Reportes {

    private $conexion;

    function __construct() {
        //Validación de session
        $this->conexion = new Conexion();
    }

    function r1($id_sucursal, $ini, $fin, $palabra) {

        if ($palabra != "") {
            $where = " AND (empresa.id = '$palabra' OR empresa.nombre LIKE '%$palabra%') ";
        }

        $sql = "SELECT SUM(orden.importe) AS total,
        SUM(orden.importe)/1.16 AS iva,
        empresa.id as id, empresa.nombre as nombre, empresa.tipo
        FROM orden	
        INNER JOIN empresa on orden.id_empresa = empresa.id
        where orden.credito=1 and orden.cancelado=0 and orden.id_sucursal = '$id_sucursal'
        and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'
        $where
        GROUP BY empresa.id
        ORDER BY empresa.tipo ,empresa.nombre ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    } 

    function r2($id_sucursal, $ini, $fin, $palabra) {

        if ($palabra != "") {
            $where = " AND (empresa.id = '$palabra' OR empresa.nombre LIKE '%$palabra%') ";
        }

        $sql = "SELECT orden.consecutivo as id_orden, orden.importe,empresa.id as id_empresa, 
        empresa.nombre,concat(paciente.nombre,paciente.paterno,paciente.materno) as nombre_paciente,
        empresa.tipo
        FROM orden	
        INNER JOIN empresa on orden.id_empresa = empresa.id
        INNER JOIN paciente on orden.id_paciente = paciente.id
        where orden.credito=1 and orden.cancelado=0 and orden.id_sucursal = '$id_sucursal'
        and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'
        $where
         
          order by empresa.nombre, orden.consecutivo
        ";
        
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function r3($id_sucursal, $ini, $fin, $palabra) {

        if ($palabra != "") {
            $where = " AND (empresa.id = '$palabra' OR empresa.nombre LIKE '%$palabra%') ";
        }

        $sql = "SELECT orden.consecutivo as id_orden, orden.importe,empresa.id as id_empresa,orden.fecha_registro as fecha, 
        empresa.nombre as nombre_empresa,concat(paciente.nombre,paciente.paterno,paciente.materno) as nombre_paciente,cat_estudio.no_estudio as id,
         orden_estudio.id_estudio as codigo,cat_estudio.nombre_estudio as nombre,orden_estudio.precio_neto_estudio as precio_estudio
        FROM orden	
        INNER JOIN empresa on orden.id_empresa = empresa.id
        INNER JOIN paciente on orden.id_paciente = paciente.id
        inner join orden_estudio on orden.id =orden_estudio.id_orden
        INNER join cat_estudio on orden_estudio.id_estudio = cat_estudio.id
        where orden.credito=1 and orden.cancelado=0 and orden.id_sucursal = '$id_sucursal'
        and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'
        $where
        order by empresa.nombre,orden.consecutivo
        ";
        
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function r4($id_sucursal, $ini, $fin, $palabra) {

        if ($palabra != "") {
            $where = " AND (empresa.id = '$palabra' OR empresa.nombre LIKE '%$palabra%') ";
        }

        $sql = "SELECT SUM(orden.importe) AS total,
        SUM(orden.importe)/1.16 AS iva,
        empresa.id as id, empresa.nombre as nombre, empresa.tipo
        FROM orden	
        INNER JOIN empresa on orden.id_empresa = empresa.id
        where orden.credito=0 and orden.cancelado=0 and orden.id_sucursal = '$id_sucursal'
        and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'
        $where
        GROUP BY empresa.id
        ORDER BY empresa.tipo ,empresa.nombre ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function r5($id_sucursal, $ini, $fin, $palabra) {

        if ($palabra != "") {
            $where = " AND (empresa.id = '$palabra' OR empresa.nombre LIKE '%$palabra%') ";
        }
        $sql = "SELECT SUM(if(orden.saldo_deudor >0, orden.saldo_deudor,0)) AS total,
         SUM(if(orden.saldo_deudor >0, orden.saldo_deudor,0))/1.16 AS iva,
          empresa.id as id, empresa.nombre as nombre, empresa.tipo
          FROM orden	
           INNER JOIN empresa on orden.id_empresa = empresa.id
          where orden.id_empresa >'0' and orden.cancelado=0 and orden.id_sucursal = '$id_sucursal'
          and orden.fecha_registro between  '$ini 00:00:00' and '$fin  23:59:59'
         $where
        GROUP BY empresa.id
          ORDER BY empresa.tipo ,empresa.nombre ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function r6($id_sucursal, $ini, $fin, $palabra) {

        if ($palabra != "") {
            $where = " AND (empresa.id = '$palabra' OR empresa.nombre LIKE '%$palabra%') ";
        }

        $sql = "SELECT sum(if(orden.id_empresa >'0',orden_estudio.precio_neto_estudio,0)) as totala ,orden.importe,empresa.nombre,
        empresa.id,orden_estudio.id_estudio,cat_estudio.id_departamento,departamento.departamento 
        from empresa INNER JOIN orden on orden.id_empresa=empresa.id 
        INNER join orden_estudio on orden_estudio.id_orden=orden.id 
        INNER JOIN cat_estudio on orden_estudio.id_estudio=cat_estudio.id 
        INNER join departamento on cat_estudio.id_departamento= departamento.id  
        where orden.cancelado=0 and orden.id_sucursal='$id_sucursal' and orden.credito='1'
        AND orden.fecha_registro BETWEEN '$ini 00:00:00' AND '$fin 23:59:59'
         $where
        group by (CONCAT(empresa.id,'-',departamento.departamento))  
        ORDER BY empresa.id, `departamento`.`departamento`  DESC";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }


    function r7m($id_sucursal, $ini, $fin, $busqueda){
        if ($busqueda != "") {
            $where = " AND (dr.id = '$busqueda' OR dr.nombre LIKE '%$busqueda%') ";
        }
        $sql = "SELECT dr.id,dr.nombre,o.consecutivo,o.fecha_registro,CONCAT(p.paterno,' ',p.materno,' ',p.nombre ) as paciente,ce.no_estudio,ce.nombre_estudio,oe.precio_neto_estudio,o.importe FROM orden o left join doctor dr on o.id_doctor=dr.id inner join orden_estudio oe on oe.id_orden=o.id inner join cat_estudio ce on ce.id=oe.id_estudio inner join paciente p on p.id=o.id_paciente       
        where o.cancelado=0 and o.id_sucursal='$id_sucursal' 
        AND o.id_doctor IS NOT NULL and o.fecha_registro BETWEEN '$ini 00:00:00' AND '$fin 23:59:59'
         $where
        order by dr.id,o.consecutivo, p.paterno,ce.nombre_estudio";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function r8($id_sucursal, $ini, $fin, $palabra) {

        if ($palabra != "") {
            $where = " AND (doctor.id = '$palabra' OR doctor.nombre LIKE '%$palabra%') ";
        }

        $sql = "SELECT orden.consecutivo, concat(paciente.nombre,' ',paciente.paterno,' ',paciente.materno) as nombre_paciente,
        CONCAT(doctor.nombre,' ',doctor.apaterno,' ',doctor.amaterno) as nombre_medico, doctor.porcentaje as pago, orden.importe as total, orden.saldo_deudor as deuda,
        orden.importe * doctor.porcentaje/ 100 AS porcentaje, doctor.id as id_doctor,doctor.alias as aliasd
        from orden
         INNER join paciente on paciente.id = orden.id_paciente
         INNER join doctor on doctor.id= orden.id_doctor
         where orden.id_sucursal!=121 and doctor.porcentaje > 0        
         and orden.fecha_registro BETWEEN '$ini 00:00:00' AND '$fin 23:59:59'
         $where
         order by doctor.id , orden.consecutivo
         
        ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function r22($id_sucursal, $ini, $fin, $palabra) {

        if ($palabra != "") {
            $where = " AND (corte.corte_numero = '$palabra') ";
        }

        $sql = "SELECT corte.*,usuario.nombre 
        FROM corte 
        INNER JOIN usuario ON (corte.id_usuario = usuario.id) 
        INNER JOIN sucursal ON (usuario.id_sucursal = sucursal.id AND sucursal.id = '$id_sucursal')
        WHERE corte.fecha >= '$ini 00:00:00' AND corte.fecha <='$fin 23:59:59' 
        $where
        ORDER BY corte.corte_numero ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function r24($id_sucursal, $ini, $fin, $palabra) {

       $sql = "SELECT concat(paciente.nombre,' ', paciente.paterno,' ',paciente.materno) as nombreson, orden.consecutivo,orden.importe,orden.observaciones,orden.fecha_registro
       FROM orden 
       INNER join paciente on orden.id_paciente= paciente.id
       WHERE orden.cancelado =1 and orden.id_sucursal='$id_sucursal' and orden.fecha_registro >= '$ini 00:00:00' AND orden.fecha_registro <='$fin 23:59:59' 
       ORDER BY orden.consecutivo";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function r10($id_sucursal, $ini, $fin, $palabra) {

        $sql = "SELECT * FROM doctor WHERE doctor.id_sucursal='$id_sucursal' ORDER BY doctor.id";
         $data = $this->conexion->getQuery($sql);
         return $data;
     }

     function r27($id_sucursal, $ini, $fin, $palabra) {

        if ($palabra != "") {
            $where = " AND (gasto.id_corte = '$palabra' OR gasto.concepto LIKE '%$palabra%') ";
        }

        $sql = "SELECT gasto.concepto,gasto.importe,gasto.fecha,corte.corte_numero,gasto.aclaracion,
        usuario.usuario as nombre_usuario
        FROM gasto
        INNER join usuario on usuario.id=gasto.id_usuario
        inner join corte on corte.id=gasto.id_corte
        where usuario.id_sucursal='$id_sucursal'
        and gasto.fecha between  '$ini 00:00:00' and '$fin 23:59:59'
        $where";
         $data = $this->conexion->getQuery($sql);
         return $data;
     }

    function MedicosTotales($id_sucursal, $ini, $fin) {
        $sql = "SELECT doctor.id, sum(orden.importe)as venta, doctor.nombre
        FROM orden 
        Inner JOIN doctor on doctor.id = orden.id_doctor
        where orden.id_sucursal ='$id_sucursal' 
        and orden.fecha_registro between  '2021-01-01 00:00:00' and '2021-09-01 23:59:59'
        and orden.cancelado=0
        group by doctor.id";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }


    function r44($id_sucursal, $ini, $fin) {
        $sql = "SELECT *
        FROM paquete
        WHERE paquete.id_sucursal='$id_sucursal' 
        ORDER BY no_paquete";
         $data = $this->conexion->getQuery($sql);
         return $data;   

}

function r46($id_sucursal, $ini, $fin) {
    $sql = "SELECT *
    FROM lista_precios
    WHERE  lista_precios.id_sucursal='$id_sucursal' 
    ORDER BY lista_precios.no";
     $data = $this->conexion->getQuery($sql);
     return $data;   

}
    
    function r45($id_sucursal, $ini, $fin, $palabra) {
       
        if ($palabra != "") {
            $where = " AND (paquete.alias = '$palabra' OR paquete.nombre LIKE '%$palabra%') ";
        }

        $sql = "SELECT paquete.nombre,paquete.precio , paquete.alias,paquete.id,
        cat_estudio.nombre_estudio as nombre_estudio,paquete_estudio.precio_neto,cat_estudio.no_estudio
        FROM paquete
        inner join paquete_estudio on paquete.id= paquete_estudio.id_paquete
        inner JOIN cat_estudio on cat_estudio.id = paquete_estudio.id_estudio
        WHERE paquete.id_sucursal='$id_sucursal' 
        $where
        ORDER BY no_paquete";
         $data = $this->conexion->getQuery($sql);
         return $data;   

}

function r47($id_sucursal, $ini, $fin, $palabra) {
       
    if ($palabra != "") {
        $where = " AND (lista_precios.alias = '$palabra' OR lista_precios.nombre LIKE '%$palabra%') ";
    }

    $sql = "SELECT lista_precios.id,lista_precios.nombre,lista_precios.alias,
    cat_estudio.no_estudio,cat_estudio.nombre_estudio,lista_precios_estudio.precio_neto
    from lista_precios
    INNER JOIN lista_precios_estudio on lista_precios_estudio.id_lista_precio=lista_precios.id
    INNER join cat_estudio on cat_estudio.id=lista_precios_estudio.id_estudio
    WHERE lista_precios.id_sucursal='$id_sucursal'
    $where
    order by lista_precios.id";
     $data = $this->conexion->getQuery($sql);
     return $data;   

}


function r48($id_sucursal, $ini, $fin, $palabra) {

    if ($palabra != "") {
        $where = " AND (orden.id_doctor = '$palabra' OR doctor.nombre LIKE '%$palabra%') ";
    }

$sql = "SELECT doctor.id as consecutivo,  SUM(orden.importe) AS total ,doctor.alias as alias, doctor.nombre as nombre
    FROM orden
    INNER JOIN doctor on  doctor.id = orden.id_doctor
    where orden.id_sucursal ='$id_sucursal'  and orden.cancelado=0
    and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'
    $where
    GROUP BY orden.id_doctor";
    $data = $this->conexion->getQuery($sql);
    return $data;
}


function r9($id_sucursal, $ini, $fin) {
$sql = "SELECT  orden.consecutivo , orden.nombre_doctor, 
   concat(paciente.nombre,'',paciente.paterno,'',paciente.materno) as nombrepaciente, orden.importe as total,
    orden.fecha_registro as fecha, usuario.usuario as nombre_usuario
    FROM orden
     INNER join paciente on paciente.id = orden.id_paciente
      INNER JOIN usuario on usuario.id = orden.id_usuario
     where orden.id_doctor is null and orden.cancelado=0 and
      orden.id_sucursal='$id_sucursal'
      and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'";
    $data = $this->conexion->getQuery($sql);
    return $data;
}

function r29($id_sucursal, $ini, $fin) {
    $sql = "SELECT orden.consecutivo, orden.fecha_registro,
    concat(paciente.nombre,'',paciente.paterno,'',paciente.materno) as nombre,
    orden.importe ,orden.saldo_deudor
    FROM orden
    INNER JOIN paciente on paciente.id=orden.id_paciente
    where orden.id_sucursal='$id_sucursal' and orden.credito=0 and orden.saldo_deudor>0 and orden.cancelado=0
     and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function r14($id_sucursal, $ini, $fin,$palabra) {
      
        if ($palabra != "") {
            $where = " AND (cat_estudio.no_estudio = '$palabra' OR cat_estudio.nombre_estudio LIKE '%$palabra%') ";
        }
           
        $sql = "SELECT cat_estudio.no_estudio as codigo, cat_estudio.nombre_estudio as nombre, count(orden_estudio.id_estudio) as cantidad,
        sum(orden_estudio.precio_neto_estudio) as venta, sum(orden_estudio.precio_neto_estudio) / count(orden_estudio.id_estudio) as costo_promedio,
        sum(if(orden.credito=1,1,0)) as cuenta_credito,sum(if(orden.credito=1,orden_estudio.precio_neto_estudio,0)) as venta_credito,
        sum(if(orden.credito=0,1,0)) as cuenta_contado,sum(if(orden.credito=0,orden_estudio.precio_neto_estudio,0)) as venta_contado
        from orden_estudio
        inner join orden on orden_estudio.id_orden =orden.id
        INNER join cat_estudio on cat_estudio.id= orden_estudio.id_estudio
        where orden.id_sucursal='$id_sucursal' and orden.cancelado=0
        and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'
        $where
        group by cat_estudio.id";
            $data = $this->conexion->getQuery($sql);
            return $data;
        }
        

        function r33($id_sucursal, $ini, $fin,$palabra) {
            $sql = "SELECT estudio.precio_publico, ce.no_estudio,ce.nombre_estudio
            FROM estudio
            INNER join cat_estudio ce on ce.id=estudio.id_cat_estudio
            where estudio.id_sucursal='$id_sucursal'";
                $data = $this->conexion->getQuery($sql);
                return $data;
            }

            function r26($id_sucursal, $ini, $fin) {
                $sql = "SELECT bitacora_paciente.*, 
                CONCAT(paciente.nombre, ' ', paciente.paterno, ' ', paciente.materno) as paciente, 
                 usuario.usuario as usuario, orden.consecutivo  
                FROM bitacora_paciente
                INNER JOIN paciente ON paciente.id = bitacora_paciente.id_paciente
                INNER JOIN usuario ON (bitacora_paciente.id_usuario = usuario.id)
                INNER JOIN orden ON orden.id = bitacora_paciente.id_orden
                WHERE bitacora_paciente.id_sucursal = '$id_sucursal'
                AND bitacora_paciente.concepto= 'DESAPLICACION DE PAGO'
                AND bitacora_paciente.fecha BETWEEN '$ini 00:00:00' AND '$fin 23:59:59'
                ORDER BY bitacora_paciente.fecha ";
                $data = $this->conexion->getQuery($sql);
                return $data;
                }


                function r13($id_sucursal, $ini, $fin,$palabra) {
      
                    if ($palabra != "") {
                        $where = " AND ( secciones.seccion LIKE '%$palabra%') ";
                    }
                       
                    $sql = "SELECT cat_estudio.no_estudio as codigo, cat_estudio.nombre_estudio as nombre, count(orden_estudio.id_estudio) as cantidad,
                    sum(orden_estudio.precio_neto_estudio) as venta, sum(orden_estudio.precio_neto_estudio) / count(orden_estudio.id_estudio) as costo_promedio,
                    sum(if(orden.credito=1,1,0)) as cuenta_credito,sum(if(orden.credito=1,orden_estudio.precio_neto_estudio,0)) as venta_credito,
                    sum(if(orden.credito=0,1,0)) as cuenta_contado,sum(if(orden.credito=0,orden_estudio.precio_neto_estudio,0)) as venta_contado,
                    secciones.seccion as nombre_seccion , secciones.id as seccion
                    from orden_estudio
                    inner join orden on orden_estudio.id_orden =orden.id
                    INNER join cat_estudio on cat_estudio.id= orden_estudio.id_estudio
                    INNER join secciones on cat_estudio.id_secciones = secciones.id
                    where orden.id_sucursal='$id_sucursal' and orden.cancelado=0
                    and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'
                    $where
                    group by cat_estudio.id
                    order by secciones.id";
                        $data = $this->conexion->getQuery($sql);
                        return $data;
                    }


         function r30($id_sucursal, $ini, $fin,$palabra) {
         $sql = "SELECT DATE(orden.fecha_registro) as fecha ,sum(orden.importe), sum(if(orden.credito=0,orden.importe,0)) as venta_contado,
          sum(if(orden.credito=0,1,0)) as cuenta_contado,
          sum(if(orden.credito=1,orden.importe,0)) as venta_credito, sum(if(orden.credito=1,1,0)) as cuenta_credito
          From orden
          where orden.id_sucursal='$id_sucursal'
          and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59' and orden.cancelado=0 
          group by DATE(orden.fecha_registro)";
          $data = $this->conexion->getQuery($sql);
          return $data;
                        }


        function r28($id_sucursal, $ini, $fin,$palabra) {
        $sql = "SELECT orden.consecutivo,  DATE(orden.fecha_registro) as fecha, concat(paciente.nombre,' ',paciente.paterno,' ' ,paciente.materno) as nombre_paciente,
        orden.importe, empresa.nombre,orden.credito
        From orden
        INNER join paciente on paciente.id = orden.id_paciente
        LEFT join empresa on empresa.id = orden.id_empresa
        where orden.id_sucursal='$id_sucursal'
        and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'";
        $data = $this->conexion->getQuery($sql);
        return $data;
          }

   function r23($id_sucursal, $ini, $fin,$palabra) {

            if ($palabra != "") {
                $where = " AND (corte.corte_numero = '$palabra') ";
            }
            $sql = "SELECT corte.id as id_corte,corte.corte_numero,corte.fecha,corte.ingresos,corte.gastos,corte.total,usuario.usuario,
            pago.pago,concat(paciente.nombre,' ',paciente.paterno,' ',paciente.materno) as nombre_paciente,pago.fecha_pago,orden.consecutivo,
            forma_pago.descripcion
            from corte
            INNER join usuario on usuario.id= corte.id_usuario
            inner join pago on pago.id_corte=corte.id
            inner JOIN orden on pago.id_orden= orden.id
            inner join paciente on paciente.id =orden.id_paciente
            inner join forma_pago on forma_pago.id = pago.id_forma_pago
            where usuario.id_sucursal='$id_sucursal'
            and corte.fecha BETWEEN '$ini 00:00:00' and '$fin 23:59:59'
            $where
            ";
            $data = $this->conexion->getQuery($sql);
            return $data;
              } 
              
              

              function r701($id_sucursal, $ini, $fin, $palabra) {

                if ($palabra != "") {
                    $where = " AND (empresa.id = '$palabra' OR empresa.nombre LIKE '%$palabra%') ";
                }
        
                $sql = "SELECT orden.consecutivo as id_orden, orden.importe,empresa.id as id_empresa,orden.fecha_registro as fecha, 
                empresa.nombre as nombre_empresa,concat(paciente.nombre,paciente.paterno,paciente.materno) as nombre_paciente,cat_estudio.no_estudio as id,
                 orden_estudio.id_estudio as codigo,cat_estudio.nombre_estudio as nombre,orden_estudio.precio_neto_estudio as precio_estudio
                FROM orden	
                INNER JOIN empresa on orden.id_empresa = empresa.id
                INNER JOIN paciente on orden.id_paciente = paciente.id
                inner join orden_estudio on orden.id =orden_estudio.id_orden
                INNER join cat_estudio on orden_estudio.id_estudio = cat_estudio.id
                where orden.credito=0 and orden.cancelado=0 and orden.id_sucursal = '$id_sucursal'
                and orden.fecha_registro between  '$ini 00:00:00' and '$fin 23:59:59'
                $where
                order by empresa.nombre,orden.consecutivo
                ";
                
                $data = $this->conexion->getQuery($sql);
                return $data;
            } 

            function r15($id_sucursal, $ini, $fin, $palabra) {
                $sql = "SELECT * FROM (
                    SELECT CONCAT(paciente.nombre,' ',paciente.paterno,' ',paciente.materno) as nombre_paciente, orden.consecutivo as id_orden, DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y'), cat_estudio.nombre_estudio, orden.importe,orden.saldo_deudor,orden.fecha_registro as fecha,cat_estudio.no_estudio,
                        CASE  
                                WHEN cat_estudio.resultado_componente = 1   
                                THEN (SELECT COUNT(*) FROM resultado_estudio WHERE id_orden = orden.id AND id_estudio = orden_estudio.id_estudio  )
                                ELSE (SELECT COUNT(*) FROM resultado_estudio_texto WHERE id_orden =  orden.id  AND id_estudio = orden_estudio.id_estudio  )  
                            END AS reportado,
                            (SELECT COUNT(*) FROM log_resultados_impresion WHERE id_orden =  orden.id  AND id_estudio = orden_estudio.id_estudio AND reset  = 0 ) AS impresion
                    FROM orden
                    INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) 
                    INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio)
                    INNER JOIN paciente on (orden.id_paciente= paciente.id)
                    WHERE orden.id_sucursal = '$id_sucursal' AND  orden.cancelado=0 AND DATE(orden.fecha_registro) >= '$ini 00:00:00' AND DATE(orden.fecha_registro) <= '$fin 23:59:59' order by orden.consecutivo
                    ) a WHERE reportado = 0 order by id_orden;
                ";
                
                $data = $this->conexion->getQuery($sql);
                return $data;
            } 
            
            function r16($id_sucursal, $ini, $fin, $palabra) {
                $sql = "SELECT * FROM (
                    SELECT CONCAT(paciente.nombre,' ',paciente.paterno,' ',paciente.materno) as nombre_paciente, orden.consecutivo as id_orden, DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y'), cat_estudio.nombre_estudio, orden.importe,orden.saldo_deudor,orden.fecha_registro as fecha,cat_estudio.no_estudio,
                        CASE  
                                WHEN cat_estudio.resultado_componente = 1   
                                THEN (SELECT COUNT(*) FROM resultado_estudio WHERE id_orden = orden.id AND id_estudio = orden_estudio.id_estudio  )
                                ELSE (SELECT COUNT(*) FROM resultado_estudio_texto WHERE id_orden =  orden.id  AND id_estudio = orden_estudio.id_estudio  )  
                            END AS reportado,
                            (SELECT COUNT(*) FROM log_resultados_impresion WHERE id_orden =  orden.id  AND id_estudio = orden_estudio.id_estudio AND reset  = 0 ) AS impresion
                    FROM orden
                    INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) 
                    INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio)
                    INNER JOIN paciente on (orden.id_paciente= paciente.id)
                    WHERE orden.id_sucursal = '$id_sucursal' AND  orden.cancelado=0 AND DATE(orden.fecha_registro) >= '$ini 00:00:00' AND DATE(orden.fecha_registro) <= '$fin 23:59:59' order by orden.consecutivo
                    ) a WHERE impresion = 0 order by id_orden;
                ";
                
                $data = $this->conexion->getQuery($sql);
                return $data;
            } 
 
                function r32($id_sucursal, $ini, $fin, $palabra) {
                    $sql = "SELECT orden.consecutivo,orden.importe, CONCAT(paciente.nombre, ' ', paciente.paterno, ' ', paciente.materno) as paciente, 
                    descuento.nombre as nombre_descuento, descuento.descuento, orden.observaciones,
                    orden.importe * descuento.descuento/ 100 AS porcentaje
                    FROM orden
                    INNER JOIN orden_estudio ON orden.id = orden_estudio.id_orden
                    INNER JOIN paciente ON orden.id_paciente = paciente.id
                    INNER JOIN descuento ON descuento.id = orden.id_descuento
                    WHERE orden.fecha_registro >= '$ini 00:00:00' AND orden.fecha_registro <='$fin 23:59:00' AND orden.cancelado=0 AND orden.id_sucursal = '$id_sucursal'
                    GROUP BY orden.id;
                    ";
                    
                    $data = $this->conexion->getQuery($sql);
                    return $data;
    
    
                }      
                
                function r11($id_sucursal, $ini, $fin, $palabra) {
                    $sql = " SELECT  d.departamento,count(oe.id_estudio) as estudios,sum(oe.precio_neto_estudio) as venta,
                    count(if(orden.credito=0, oe.id, NULL)) as estudios_contado,
                    sum(if(orden.credito=0,oe.precio_neto_estudio,0)) as venta_contado,
                    count(if(orden.credito=1, oe.id, NULL)) as etudios_credito,
                    sum(if(orden.credito=1,oe.precio_neto_estudio,0)) as venta_credito
                    FROM orden
                    INNER JOIN orden_estudio oe on oe.id_orden = orden.id
                    INNER JOIN  cat_estudio ce on ce.id = oe.id_estudio
                    INNER JOIN departamento d on d.id = ce.id_departamento
                    WHERE orden.fecha_registro >= '$ini 00:00:00' AND orden.fecha_registro <='$fin 23:59:59' 
                    AND orden.id_sucursal = '$id_sucursal' and orden.cancelado=0
                    GROUP by d.id;
                    ";
                    
                    $data = $this->conexion->getQuery($sql);
                    return $data;
    
    
                } 
                
                
                function r34($id_sucursal, $ini, $fin,$palabra) {

                    if ($palabra != "") {
                        $where = " AND ( se.seccion LIKE '%$palabra%') ";
                    }
                    $sql = "SELECT estudio.precio_publico, ce.no_estudio,ce.nombre_estudio,se.seccion as nombre_seccion,se.id as id_seccion
                    FROM estudio
                    INNER join cat_estudio ce on ce.id=estudio.id_cat_estudio
                    inner join secciones se on se.id= ce.id_secciones
                    where estudio.id_sucursal='$id_sucursal'
                    $where
                    ORDER by id_seccion";
                        $data = $this->conexion->getQuery($sql);
                        return $data;
                    } 

                    function r31($id_sucursal, $ini, $fin) {
           $sql = "SELECT DATE(orden.fecha_registro) AS fecha, 
           orden.consecutivo as consecutivo,orden.importe, orden.credito, orden.id_empresa, 
           orden.id_paciente, orden.aumento as aumento, orden.observaciones as observaciones, 
           CONCAT(paciente.nombre, ' ', paciente.paterno, ' ', paciente.materno) AS paciente 
           FROM orden
           INNER JOIN paciente ON paciente.id = orden.id_paciente
           WHERE orden.aumento>'0' 
           and orden.fecha_registro  BETWEEN '$ini 00:00:00' and '$fin 23:59:59 '
            AND orden.id_sucursal = '$id_sucursal'";
           $data = $this->conexion->getQuery($sql);
            return $data;
                        }  
                        
      function r704($id_sucursal, $ini, $fin) {
       $sql = "SELECT o.consecutivo, concat(p.nombre,'',p.paterno,'',p.materno) as nombre_paciente,o.fecha_registro,
       ce.no_estudio,ce.nombre_estudio
       FROM orden o
       INNER JOIN paciente p on o.id_paciente = p.id
       INNER JOIN orden_estudio oe on o.id = oe.id_orden
       INNER JOIN cat_estudio ce on oe.id_estudio= ce.id
       WHERE o.fecha_registro  BETWEEN '$ini 00:00:00' and '$fin 23:59:59 '
        AND o.id_sucursal = '$id_sucursal'";
        $data = $this->conexion->getQuery($sql);
        return $data;
         } 
         
         
        function r709($id_sucursal, $ini, $fin) {
      $sql = "SELECT o.consecutivo, concat(p.nombre,'',p.paterno,'',p.materno) as nombre_paciente,
              o.fecha_registro,o.direccion,o.importe,d.nombre as nombre_medico
            FROM orden o
            INNER JOIN paciente p on o.id_paciente = p.id
            LEFT JOIN doctor d on o.id_doctor= d.id

            WHERE o.fecha_registro  BETWEEN '$ini 00:00:00' and '$fin 23:59:59 '
             AND o.id_sucursal = '$id_sucursal' ORDER BY o.direccion
           
            ";
             $data = $this->conexion->getQuery($sql);
             return $data;
              } 

              function r12($id_sucursal, $ini, $fin,$palabra) {
                $where=0;
                if ($palabra != "" ) {
                    $where = $palabra;
                }
                $sql = "SELECT o.id,ce.id as id_estudio,oe.precio_neto_estudio,
                format(sum(oe.precio_neto_estudio*(100*p.pago/o.importe)/100),2) as pp_pago,
                p.pago,o.importe, d.departamento,c.corte_numero as corteson from corte c inner join pago p on c.id=p.id_corte
                inner join orden o on o.id=p.id_orden 
                inner join orden_estudio oe on oe.id_orden=o.id 
                inner join cat_estudio ce on ce.id=oe.id_estudio 
                inner join departamento d on d.id=ce.id_departamento
                where c.corte_numero=$where  and o.id_sucursal=$id_sucursal
                
                GROUP BY departamento ";
                $data = $this->conexion->getQuery($sql);
                return $data;
                } 
                
                
                
       function r711($id_sucursal, $ini, $fin) {
       $sql = "SELECT o.cancelado,oe.id_paquete,paq.alias,paq.nombre as nombre_paquete, lpms2.precio_maquila as precio_maquila_paquete,lpms.precio_maquila,o.consecutivo, concat(p.nombre,' ',p.paterno,' ',p.materno) as nombre_paciente,o.fecha_registro,
       ce.no_estudio,ce.nombre_estudio,o.consecutivo_matriz,o.consecutivo_matriz_imagen 
       FROM orden o
       INNER JOIN paciente p on o.id_paciente = p.id
       LEFT JOIN orden_estudio oe on o.id = oe.id_orden and oe.envio_maquila=1
       INNER JOIN cat_estudio ce on oe.id_estudio= ce.id 
       INNER JOIN lprecios_maquila_sucursales lpms on lpms.id_cat_estudio=ce.id and lpms.id_sucursal='$id_sucursal'
       left join paquete paq on paq.id=oe.id_paquete 
       left join lprecios_maquila_sucursales lpms2 on lpms2.id_paquete_sucursal=paq.id 
       WHERE  o.fecha_registro  BETWEEN '$ini 00:00:00' and '$fin 23:59:59 '
        AND o.id_sucursal = '$id_sucursal' order by o.id";
        $data = $this->conexion->getQuery($sql);
        return $data;
         }
         
         
          function r712($id_sucursal, $ini, $fin) {
       $sql = "SELECT o.consecutivo, concat(p.nombre,' ',p.paterno,' ',p.materno) as nombre_paciente,o.fecha_registro,
       ce.no_estudio,ce.nombre_estudio,o.consecutivo_matriz,su.nombre
       FROM orden o
       INNER JOIN paciente p on o.id_paciente = p.id
       INNER JOIN orden_estudio oe on o.id = oe.id_orden
       INNER JOIN cat_estudio ce on oe.id_estudio= ce.id
       INNER JOIN sucursal su on su.id=o.sucursal_maquila 
       WHERE  o.fecha_registro  BETWEEN '$ini 00:00:00' and '$fin 23:59:59 '
        AND o.id_sucursal = '$id_sucursal' ORDER BY su.nombre";
        $data = $this->conexion->getQuery($sql);
        return $data;
         }
}
?>