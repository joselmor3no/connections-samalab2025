<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Reportes {

    private $conexion;

    function __construct() {
        //Validación de session
        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso) {
            //header("Location: /");
        }

        $this->conexion = new Conexion();
    }

     function eliminaComplementarioOrdenM($id_orden,$id_estudio){
        $sql="DELETE FROM orden_documentos_externos where id_orden=".$id_orden." and id_estudio=".$id_estudio;
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function eliminaComplementarioM($id_documento){
        $sql="DELETE FROM lista_documentos_externos where id=".$id_documento;
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function guardaNuevoComplementario($nombre_documento){
    $sql="INSERT INTO lista_documentos_externos(nombre) VALUES('".$nombre_documento."')";
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function editaComplementario($id_documento,$nombre_documento){
        $sql="UPDATE lista_documentos_externos SET nombre='".$nombre_documento."' where id=".$id_documento;
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function guardaOrdenComplementario($id_orden, $id_estudio, $id_complementario){
        $sql="INSERT INTO orden_documentos_externos(id_orden,id_estudio,id_documento_externo) VALUES(".$id_orden.",".$id_estudio.",".$id_complementario.")";
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function buscarDComplementariosOrdenM($id_orden,$id_documento){
        if($id_documento!=null){
            $sql="SELECT * from lista_documentos_externos ld left join orden_documentos_externos od on ld.id=od.id_documento_externo where od.id_documento_externo=".$id_documento." and od.id_orden=".$id_orden;
        }
        else{
            $sql="SELECT * from lista_documentos_externos ld left join orden_documentos_externos od on ld.id=od.id_documento_externo where od.id_orden=".$id_orden;
        }
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function buscarPacienteM($busqueda){
        $sql="SELECT o.fecha_registro, o.id as id_orden,o.consecutivo, concat(o.consecutivo,'-',p.paterno,' ',p.materno) as paciente 
        from orden o 
        inner join paciente p on o.id_paciente=p.id AND p.id_sucursal=".$_SESSION["id_sucursal"]."
        where concat(o.consecutivo,'-',p.paterno,' ',p.materno) like '%".$busqueda."%'  and o.id_sucursal=".$_SESSION["id_sucursal"]." ORDER BY o.fecha_registro DESC LIMIT 10";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPacientesTabla($id_orden,$fechaInicial,$fechaFinal,$id_sucursal){ 
        if($id_orden==null or $id_orden==""){
            $sql="SELECT lde.id as id_documento,ce.id as id_estudio,ce.nombre_estudio,o.id, concat(p.paterno,' ',p.materno, ' ', p.nombre) as paciente,o.fecha_registro, o.consecutivo,p.expediente, GROUP_CONCAT(lde.nombre) as documentos  
            from orden o inner join paciente p on o.id_paciente=p.id 
            inner join orden_estudio oe on oe.id_orden=o.id 
            inner join cat_estudio ce on ce.id=oe.id_estudio 
            left join orden_documentos_externos ode on ode.id_orden=o.id and ce.id=ode.id_estudio
            left join lista_documentos_externos lde on lde.id=ode.id_documento_externo
            where o.fecha_registro BETWEEN '".$fechaInicial."' and '".$fechaFinal." 23:59:59' and o.id_sucursal=".$id_sucursal."  group by concat(o.id,ce.id)";
        }
        else{
            $sql="SELECT lde.id as id_documento,ce.id as id_estudio,ce.nombre_estudio,o.id, concat(p.paterno,' ',p.materno, ' ', p.nombre) as paciente,o.fecha_registro, o.consecutivo,p.expediente, GROUP_CONCAT(lde.nombre) as documentos  
            from orden o inner join paciente p on o.id_paciente=p.id 
            inner join orden_estudio oe on oe.id_orden=o.id 
            inner join cat_estudio ce on ce.id=oe.id_estudio 
            left join orden_documentos_externos ode on ode.id_orden=o.id and ce.id=ode.id_estudio
            left join lista_documentos_externos lde on lde.id=ode.id_documento_externo
            where o.id=".$id_orden." and o.id_sucursal=".$id_sucursal."  group by concat(o.id,ce.id)";
        }

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getLaboratorio($codigo, $fecha_ini, $fecha_fin, $id_sucursal) {
        $sql = "SELECT cat_estudio.no_estudio
                FROM cat_estudio 
                INNER JOIN estudio ON (estudio.id_cat_estudio = cat_estudio.id AND estudio.id_sucursal = $id_sucursal ) 
                WHERE (cat_estudio.no_estudio = '$codigo' OR cat_estudio.alias = '$codigo') AND estudio.id_sucursal = '$id_sucursal' AND cat_estudio.tipo='Estudios'";
        $estudio = $this->conexion->getQuery($sql);
        $data = [];
        if (count($estudio) > 0) {
            $data["tipo"] = "estudio";
            $data["estudio"] = $estudio[0];
        } else if ($codigo == "") {
            $sql = "SELECT orden.id, orden.consecutivo AS codigo, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y') AS fecha_orden, "
                    . "paciente.expediente, paciente.sexo, paciente.edad, paciente.tipo_edad, orden.observaciones "
                    . "FROM orden "
                    . "INNER JOIN paciente ON (paciente.id = orden.id_paciente) "
                    . "WHERE ( CONCAT(paciente.nombre,' ',paciente.paterno,' ',paciente.materno) LIKE REPLACE('%$codigo%', ' ', '%') OR orden.consecutivo = '$codigo'  ) AND orden.cancelado = 0 "
                    . "AND orden.fecha_registro BETWEEN '$fecha_ini' AND '$fecha_fin 23:59:59' AND orden.id_sucursal = $id_sucursal "
                    . "ORDER BY orden.consecutivo DESC";

            $data["tipo"] = "paciente";
            $data["laboratorio"] = $this->conexion->getQuery($sql);
        } else {
            $sql = "SELECT orden.id, orden.consecutivo AS codigo, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y') AS fecha_orden, "
                    . "paciente.expediente, paciente.sexo, paciente.edad, paciente.tipo_edad, orden.observaciones "
                    . "FROM orden "
                    . "INNER JOIN paciente ON (paciente.id = orden.id_paciente) "
                    . "WHERE ( CONCAT(paciente.nombre,' ',paciente.paterno,' ',paciente.materno) LIKE REPLACE('%$codigo%', ' ', '%') OR orden.consecutivo = '$codigo'  ) AND orden.cancelado = 0 "
                    . "AND orden.id_sucursal = $id_sucursal "
                    . "ORDER BY orden.consecutivo DESC";

            $data["tipo"] = "paciente";
            $data["laboratorio"] = $this->conexion->getQuery($sql);
        }

        return $data;
    }

    function getOrdenPaciente($id_orden) {

        $sql = "SELECT orden.sucursal_maquila,paciente.id, orden.consecutivo AS codigo, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, paciente.expediente, "
                . "DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y') AS fecha, orden.id AS id_orden, DATE_FORMAT(paciente.fecha_nac, '%d/%m/%Y') AS fecha_nac, paciente.tel AS telefono, "
                . "DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y %H:%i hrs') AS fecha_hora,"
                . "paciente.sexo, paciente.edad, paciente.tipo_edad, paciente.direccion, orden.observaciones, orden.ingles,
                (SELECT DATE_FORMAT(log_activity_resultados.fecha, '%d/%m/%Y') 
                FROM log_activity_resultados 
                WHERE id_orden_estudio in (SELECT id FROM orden_estudio WHERE id_orden = orden.id ) AND observaciones = 'ESTUDIO VALIDADO' ORDER BY id DESC LIMIT 1) 
                AS fecha_validacion, 
                (SELECT DATE_FORMAT(log_activity_resultados.fecha, '%d/%m/%Y %H:%i hrs') 
                FROM log_activity_resultados 
                WHERE id_orden_estudio in (SELECT id FROM orden_estudio WHERE id_orden = orden.id ) AND observaciones = 'ESTUDIO VALIDADO' ORDER BY id DESC LIMIT 1) 
                AS fecha_hora_validacion, 
                CASE  
                    WHEN orden.id_doctor IS NOT NULL THEN CONCAT (doctor.apaterno,' ',doctor.amaterno,' ',doctor.nombre)   
                    ELSE orden.nombre_doctor  
                END AS doctor,  
                empresa.nombre AS empresa,
                CASE 
                    WHEN empresa.direccion = '' THEN '' 
                    ELSE CONCAT('CALLE ',empresa.direccion,', ', empresa.ciudad,', ',empresa.estado,' TEL: ', empresa.telefono) 
                END AS direccion,
                CASE  
                    WHEN empresa.mostrarlogo IS NOT NULL THEN empresa.mostrarlogo 
                    ELSE 1  
                END AS mostrarlogo  "
                . "FROM orden "
                . "INNER JOIN paciente ON (paciente.id = orden.id_paciente) "
                . "LEFT JOIN doctor ON (doctor.id = orden.id_doctor) "
                . "LEFT JOIN empresa ON (empresa.id = orden.id_empresa) "
                . "WHERE orden.id = '$id_orden' AND orden.cancelado = 0 ";
        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getOrdenEstudio($id_orden) {

        $sql = "SELECT orden_estudio.envio_maquila,orden_estudio.id, cat_estudio.nombre_estudio AS estudio, cat_estudio.resultado_componente, "
                . " CASE  
                    WHEN cat_estudio.resultado_componente = 1   
                    THEN (SELECT COUNT(*) FROM resultado_estudio WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio  )
                    ELSE (SELECT COUNT(*) FROM resultado_estudio_texto WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio  )  
                  END AS reportado, "
                . "(SELECT COUNT(*) FROM log_resultados_impresion WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio AND reset  = 0 ) AS impresion, "
                . "orden_estudio.pagina, orden_estudio.impreso AS imprimir "
                . "FROM orden "
                . "INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) "
                . "INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) "
                . "WHERE orden.id = '$id_orden'  AND cat_estudio.tipo='Estudios'"
                . "ORDER BY orden_estudio.posicion";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getOrdenNoEstudio($no_estudio, $id_sucursal, $fecha_ini, $fecha_fin) {

        $sql = "SELECT orden_estudio.id, cat_estudio.nombre_estudio AS estudio, cat_estudio.resultado_componente, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente,"
                . " CASE  
                    WHEN cat_estudio.resultado_componente = 1   
                    THEN (SELECT COUNT(*) FROM resultado_estudio WHERE id_orden = orden.id AND id_estudio = orden_estudio.id_estudio  )
                    ELSE (SELECT COUNT(*) FROM resultado_estudio_texto WHERE id_orden = orden.id AND id_estudio = orden_estudio.id_estudio  )  
                  END AS reportado, "
                . "(SELECT COUNT(*) FROM log_resultados_impresion WHERE id_orden = orden.id  AND id_estudio = orden_estudio.id_estudio AND reset  = 0 ) AS impresion, "
                . "orden_estudio.pagina, orden.id AS id_orden "
                . "FROM orden "
                . "INNER JOIN paciente ON (paciente.id = orden.id_paciente) "
                . "INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) "
                . "INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) "
                . "WHERE cat_estudio.no_estudio = '$no_estudio' AND cat_estudio.tipo='Estudios' AND orden.cancelado = 0  AND "
                . "orden.fecha_registro BETWEEN '$fecha_ini' AND '$fecha_fin 23:59:59' AND orden.id_sucursal = $id_sucursal "
                . "ORDER BY orden.consecutivo";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getOrdenGlobal($id_sucursal, $fecha_ini, $fecha_fin) {

        $sql = "SELECT orden.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente "
                . "FROM orden "
                . "INNER JOIN paciente ON (paciente.id = orden.id_paciente) "
                . "INNER JOIN orden_estudio ON (orden.id = orden_estudio.id_orden) "
                . "INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) "
                . "WHERE cat_estudio.tipo='Estudios' AND orden.cancelado = 0 AND "
                . "orden.fecha_registro BETWEEN '$fecha_ini' AND '$fecha_fin 23:59:59' AND orden.id_sucursal = $id_sucursal "
                . "GROUP BY orden.consecutivo "
                . "ORDER BY orden.consecutivo";
        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getOrdenEstudioComponentes($id_orden_estudio, $id_sucursal) {

        $sql = "SELECT cat_estudio.no_estudio as numero_estudio, orden.consecutivo as consecutivo_orden, componente.*, CONCAT(componente.unidad,' ', componente.referencia) AS unidad, componente.unidad AS unidad_ , orden_estudio.observaciones_generales AS observaciones, orden_estudio.pdf,
        CASE  
          WHEN resultado_estudio.resultado != '' OR resultado_estudio.resultado IS NOT NULL   THEN resultado_estudio.resultado
          ELSE ''  
        END AS resultado,
        (SELECT usuario FROM log_activity_resultados WHERE id_orden_estudio = $id_orden_estudio AND observaciones = 'ESTUDIO RESTABLECIDO' ORDER BY id DESC LIMIT 1) AS restablecio 
        FROM orden_estudio
        INNER JOIN componente ON (orden_estudio.id_estudio = componente.id_estudio AND componente.id_sucursal = $id_sucursal)
        LEFT JOIN resultado_estudio ON (componente.id = resultado_estudio.id_componente AND componente.id_estudio = resultado_estudio.id_estudio  
        AND resultado_estudio.id_orden = orden_estudio.id_orden) 
        INNER JOIN orden ON orden.id=orden_estudio.id_orden  
        INNER JOIN cat_estudio on cat_estudio.id=componente.id_estudio 
        WHERE orden_estudio.id = '$id_orden_estudio' AND  componente.id_sucursal = $id_sucursal AND componente.activo = 1
        ORDER BY componente.orden";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getOrdenEstudioTexto($id_orden_estudio, $id_sucursal) {

        $sql = "SELECT   CASE  
          WHEN resultado_estudio_texto.resultado != '' OR resultado_estudio_texto.resultado  IS NOT NULL THEN resultado_estudio_texto.resultado
          ELSE (SELECT texto FROM resultado_texto WHERE id_sucursal = $id_sucursal AND id_estudio = (SELECT id_estudio FROM orden_estudio WHERE id = $id_orden_estudio )) 
        END AS resultado, orden_estudio.observaciones_generales AS observaciones
        FROM orden_estudio
        LEFT JOIN resultado_estudio_texto ON (resultado_estudio_texto.id_estudio = resultado_estudio_texto.id_estudio 
        AND resultado_estudio_texto.id_orden = orden_estudio.id_orden AND resultado_estudio_texto.id_estudio = (SELECT id_estudio FROM orden_estudio WHERE id = $id_orden_estudio ) ) 
        WHERE orden_estudio.id = $id_orden_estudio ";
        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getReferenciaNumerico($data) {

        $sql = "SELECT alta, baja, valores_unidades AS unidad, valores_decimales AS decimales, referencia, tipo_edad, edad_inicio, edad_fin
	FROM `componente_numerico` 
        WHERE id_componente = '" . $data["id_componente"] . "' AND tipo_edad = '" . $data["tipo_edad"] . "' AND activo = '1' 
        AND (referencia = '" . $data["sexo"] . "' OR referencia = 'General') AND edad_inicio <= " . $data["edad"] . " AND " . $data["edad"] . " <= edad_fin
        ORDER BY FIELD(referencia, 'Masculino', 'Femenino', 'Nino', 'Embarazada', 'Fumador', 'General') ";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getReferenciaLista($id_componente) {
        $sql = "SELECT * 
	FROM `componente_lista` 
	WHERE id_componente = '$id_componente' AND activo = '1'
        ORDER BY predeterminado DESC";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getFormula($id_componente) {
        $sql = "SELECT * 
	FROM `componente_formula` 
	WHERE id_componente = '$id_componente'";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getTabla($id_componente, $sexo) {
        $sql = "SELECT * 
	FROM `componente_tabla` 
        WHERE id_componente = '" . $id_componente . "' AND (sexo = '" . $sexo . "' OR sexo = 'General') AND activo = '1' 
        ORDER BY FIELD(sexo, 'Masculino', 'Femenino', 'Nino', 'Embarazada', 'Fumador', 'General') ";

        $data = $this->conexion->getQuery($sql);

        return $data;
    }

    function getIdOrden($codigo, $id_sucursal) {

        $sql = "SELECT * 
            FROM orden
            WHERE consecutivo = '$codigo' AND id_sucursal = $id_sucursal";

        $datos = $this->conexion->getQuery($sql);
        $data = "";
        foreach ($datos AS $row) {
            $data = $row->id;
        }
        return $data;
    }

    function addResultadosLab($data) {

        if ($data["hide_tipo"] == "componente") {
            $componentes = $this->getOrdenEstudioComponentes($data["id_estudio"], $data["id_sucursal"]);

            $sql = "DELETE FROM resultado_estudio 
                WHERE id_orden = " . $data["id_orden"] . " AND id_estudio = (
                SELECT id_estudio
                FROM orden_estudio WHERE id = " . $data["id_estudio"] . ");";
            $this->conexion->setQuery($sql);

            foreach ($componentes AS $row) {
                $id = $row->id;

                $aux = str_replace(",", "", $data["componente_" . $id]);

                if (is_numeric($aux)) {
                    $resultado = str_replace(",", "", $data["componente_" . $id]);
                } else {
                    $resultado = $data["componente_" . $id];
                }

                //componente
                $val_leyenda = $data["val_leyenda_" . $id];
                $val_componente = $data["val_componente_" . $id];

                //Numerico y formula
                $val_referencia = $data["val_referencia_" . $id];
                $val_tipo_edad = $data["val_tipo_edad_" . $id];
                $val_edad_inicio = $data["val_edad_inicio_" . $id];
                $val_edad_fin = $data["val_edad_fin_" . $id];
                $val_alta = $data["val_alta_" . $id];
                $val_baja = $data["val_baja_" . $id];
                $val_unidad = $data["val_unidad_" . $id];
                $val_decimales = $data["val_decimales_" . $id];

                //Lista
                $val_lista = $data["val_lista_" . $id];

                //Texto
                $val_unidad_componente = $data["val_unidad_componente_" . $id];
                $val_referencia_componente = $data["val_referencia_componente_" . $id];

                //Tabla
                $val_tabla = $data["val_tabla_" . $id];

                /*$sql = "INSERT INTO `resultado_estudio`(`resultado`, `id_orden`, `id_estudio`, `id_componente`) 
                    SELECT '" . $resultado . "', '" . $data["id_orden"] . "', id_estudio, '" . $id . "' FROM orden_estudio WHERE id = " . $data["id_estudio"];

                $this->conexion->setQuery($sql);*/

                //Save de valores de referencia 
                $sql = "INSERT INTO `resultado_estudio`(`resultado`, `id_orden`, `id_estudio`, `id_componente`, 
                referencia_componente, unidad_componente,
                referencia_numerica, edad_inicio_numerica, edad_fin_numerica, valores_decimales_numerica, valores_unidades_numerica, alta_numerica, baja_numerica, tipo_edad_numerica,
                elemento_lista,
                tabla, leyenda, componente) 
                SELECT '" . $resultado . "', '" . $data["id_orden"] . "', id_estudio, '" . $id . "',
                '$val_referencia_componente', '$val_unidad_componente',    
                '$val_referencia', '$val_edad_inicio', '$val_edad_fin', '$val_decimales', '$val_unidad', '$val_alta','$val_baja', '$val_tipo_edad',
                '$val_lista',
                '$val_tabla', '$val_leyenda','$val_componente'        
                FROM orden_estudio WHERE id = " . $data["id_estudio"];

                $this->conexion->setQuery($sql);

                //log_activity
                $datos = array(
                    "observaciones" => "NUEVO RESULTADO: " . str_replace("'", "", $sql),
                    "tabla" => "resultado_estudio",
                    "id_tabla" => 0,
                    "usuario" => $_SESSION["usuario"]);

                $catalogos = new Catalogos();
                $catalogos->logActivity($datos);
            }

            $sql = "INSERT INTO `log_resultados`(`id_usuario`, `id_orden`, `id_estudio`, `id_sucursal`, `fecha`, `ip`, `concepto`) 
                SELECT '" . $_SESSION["id"] . "', '" . $data["id_orden"] . "', id_estudio, '" . $data["id_sucursal"] . "', NOW(), '" . $_SERVER["REMOTE_ADDR"] . "', 'Insert'
                FROM orden_estudio WHERE id = " . $data["id_estudio"];
            $this->conexion->setQuery($sql);

            //NUEVO LOG
            $sql = "INSERT INTO `log_activity_resultados`(`observaciones`, `usuario`, `id_orden_estudio`, `fecha`) 
                SELECT 'ESTUDIO GUARDADO', '" . $_SESSION["usuario"] . "',id,  NOW() 
                FROM orden_estudio WHERE id = " . $data["id_estudio"];
            $this->conexion->setQuery($sql);

            $sql = "UPDATE orden_estudio SET 
                observaciones_generales = '" . $data["hide_observaciones"] . "', impreso = 1 
                WHERE id = " . $data["id_estudio"];

            $this->conexion->setQuery($sql);
        } else {

            $sql = "DELETE FROM resultado_estudio_texto 
                WHERE id_orden = " . $data["id_orden"] . " AND id_estudio = (
                SELECT id_estudio
                FROM orden_estudio WHERE id = " . $data["id_estudio"] . ");";
            $this->conexion->setQuery($sql);

            $sql = "INSERT INTO `resultado_estudio_texto`(`resultado`, `id_orden`, `id_estudio`) 
                    SELECT '" . $data["reporte-texto"] . "', '" . $data["id_orden"] . "', id_estudio FROM orden_estudio WHERE id = " . $data["id_estudio"];
            $this->conexion->setQuery($sql);

            $datos = array(
                "observaciones" => "NUEVO RESULTADO: " . str_replace("'", "", $sql),
                "tabla" => "resultado_estudio_texto",
                "id_tabla" => 0,
                "usuario" => $_SESSION["usuario"]);

            $catalogos = new Catalogos();
            $catalogos->logActivity($datos);

            $sql = "INSERT INTO `log_resultados`(`id_usuario`, `id_orden`, `id_estudio`, `id_sucursal`, `fecha`, `ip`, `concepto`) 
                SELECT '" . $_SESSION["id"] . "', '" . $data["id_orden"] . "', id_estudio, '" . $data["id_sucursal"] . "', NOW(), '" . $_SERVER["REMOTE_ADDR"] . "', 'Insert'
                FROM orden_estudio WHERE id = " . $data["id_estudio"];
            $this->conexion->setQuery($sql);

            //NUEVO LOG
            $sql = "INSERT INTO `log_activity_resultados`(`observaciones`, `usuario`, `id_orden_estudio`, `fecha`) 
                SELECT 'ESTUDIO GUARDADO', '" . $_SESSION["usuario"] . "',id,  NOW() 
                FROM orden_estudio WHERE id = " . $data["id_estudio"];
            $this->conexion->setQuery($sql);

            $sql = "UPDATE orden_estudio SET 
                observaciones_generales = '" . $data["hide_observaciones"] . "', impreso = 1  
                WHERE id = " . $data["id_estudio"];
            $this->conexion->setQuery($sql);
        }

        //resultado ingles
        /* $sql = "UPDATE orden SET 
          ingles = CAST(" . $data["hide_ingles"] . " AS SIGNED)
          WHERE id = " . $data["id_orden"];
          $this->conexion->setQuery($sql);] */
    }

    function imprimirReporte($data) {

        
        $impresion = [];
        for ($i = 0; $i < count($data["imprimir"]); $i++) {
            $id_detalle_orden = $data["id_orden_detalle"][$i];
            if ($data["imprimir"][$i] == "true") {

                $sql = "SELECT * FROM log_resultados_impresion "
                        . "WHERE id_estudio = (SELECT id_estudio FROM orden_estudio WHERE id = " . $id_detalle_orden . "  ) AND id_orden='" . $data["id_orden"] . "' AND reset  = 0 "
                        . "ORDER BY id DESC "
                        . "LIMIT 1";

                $existe = $this->conexion->getQuery($sql);
                if (count($existe) == 0) {
                    $sql = "INSERT INTO `log_resultados_impresion`(`id_usuario`, `id_orden`, `id_estudio`, `fecha`,  `ip`) 
                        SELECT '" . $_SESSION["id"] . "', '" . $data["id_orden"] . "', id_estudio, NOW(), '" . $_SERVER["REMOTE_ADDR"] . "' FROM orden_estudio WHERE id = " . $id_detalle_orden;
                    $this->conexion->setQuery($sql);

                    //NUEVO LOG
                    $sql = "INSERT INTO `log_activity_resultados`(`observaciones`, `usuario`, `id_orden_estudio`, `fecha`) 
                    SELECT 'ESTUDIO VALIDADO', '" . $_SESSION["usuario"] . "',id,  NOW() 
                    FROM orden_estudio WHERE id = " . $id_detalle_orden;
                    $this->conexion->setQuery($sql);

                    //log_activity
                    $datos = array(
                        "observaciones" => "NUEVA IMPRESION: " . str_replace("'", "", $sql),
                        "tabla" => "log_resultados_impresion",
                        "id_tabla" => 0,
                        "usuario" => $_SESSION["usuario"]);

                    $catalogos = new Catalogos();
                    $catalogos->logActivity($datos);
                } else {
                    //log_activity
                    $datos = array(
                        "observaciones" => "IMPRESION DE REPORTE: " . str_replace("'", "", $sql),
                        "tabla" => "log_resultados_impresion",
                        "id_tabla" => 0,
                        "usuario" => $_SESSION["usuario"]);

                    $catalogos = new Catalogos();
                    $catalogos->logActivity($datos);

                    //NUEVO LOG
                    $sql = "INSERT INTO `log_activity_resultados`(`observaciones`, `usuario`, `id_orden_estudio`, `fecha`) 
                    SELECT 'ESTUDIO REIMPRESO', '" . $_SESSION["usuario"] . "',id,  NOW() 
                    FROM orden_estudio WHERE id = " . $id_detalle_orden;
                    $this->conexion->setQuery($sql);
                }

                //Datos a imprimir
                $sql = "SELECT cat_estudio.*, 
                  CASE
                  WHEN paquete.id IS NOT NULL AND paquete.id_tipo_reporte IS NOT NULL  THEN 4
                  WHEN paquete.id IS NOT NULL AND paquete.id_tipo_reporte IS NULL AND (cat_estudio.alias != 'BH' AND cat_estudio.alias != 'EGO' AND estudio.id_tipo_reporte != 5 ) THEN 3
                  ELSE estudio.id_tipo_reporte
                  END AS id_tipo_reporte,
                  paquete.nombre AS paquete
                  FROM orden_estudio
                  INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio)
                  INNER JOIN estudio ON (estudio.id_cat_estudio = cat_estudio.id AND estudio.id_sucursal = " . $data["id_sucursal"] . " )
                  LEFT JOIN paquete ON (paquete.id = orden_estudio.id_paquete)
                  WHERE orden_estudio.id =  $id_detalle_orden ";

                $estudios = $this->conexion->getQuery($sql);


                $impresion[] = array(
                    "id_detalle_orden" => $id_detalle_orden,
                    "no_estudio" => $estudios[0]->no_estudio,
                    "estudio" => $estudios[0]->nombre_estudio,
                    "imprimir" => $data["imprimir"][$i],
                    "pagina" => $data["pagina"][$i],
                    "id_tipo_reporte" => $estudios[0]->id_tipo_reporte,
                    "paquete" => $estudios[0]->paquete,
                );

                //Reordernar
                $sql = "UPDATE orden_estudio SET 
                posicion = " . ($i + 1) . ", pagina = " . ($data["pagina"][$i] == "true" ? 1 : 0) . ", impreso = " . ($data["imprimir"][$i] == "true" ? 1 : 0) . "
                WHERE id = " . $id_detalle_orden;
                $this->conexion->setQuery($sql);
            } else {

                //Reordernar
                $sql = "UPDATE orden_estudio SET 
                posicion = " . ($i + 1) . ", pagina = " . ($data["pagina"][$i] == "true" ? 1 : 0) . ",  impreso = " . ($data["imprimir"][$i] == "true" ? 1 : 0) . "
                WHERE id = " . $id_detalle_orden;
                $this->conexion->setQuery($sql);
            }
        };

        //resultado ingles
        $sql = "UPDATE orden SET 
                ingles = CAST(" . $data["hide_ingles"] . " AS SIGNED) 
                WHERE id = " . $data["id_orden"];
        $this->conexion->setQuery($sql);

        return $impresion;
    }

    function estudiosPacientesImprimir($id_orden, $expediente, $id_sucursal) {
        //Datos a imprimir
        $sql = "SELECT cat_estudio.*,orden_estudio.observaciones_generales, orden_estudio.id AS id_detalle_orden, orden_estudio.pagina, orden_estudio.impreso AS imprimir, materia_biologica.materia, 
                CASE  
                WHEN paquete.id IS NOT NULL AND paquete.id_tipo_reporte IS NOT NULL  THEN paquete.metodo
                ELSE estudio.metodo_utilizado
                END AS metodo_utilizado,
                CASE  
                WHEN paquete.id IS NOT NULL AND paquete.id_tipo_reporte IS NOT NULL  THEN 4
                WHEN paquete.id IS NOT NULL AND paquete.id_tipo_reporte IS NULL AND (cat_estudio.alias != 'BH' AND cat_estudio.alias != 'EGO' AND estudio.id_tipo_reporte != 5) THEN 3
                ELSE estudio.id_tipo_reporte
                END AS id_tipo_reporte,
                CASE  
                    WHEN cat_estudio.resultado_componente = 1   
                    THEN (SELECT COUNT(*) FROM resultado_estudio WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio  )
                    ELSE (SELECT COUNT(*) FROM resultado_estudio_texto WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio  )  
                  END AS reportado, 
                (SELECT COUNT(*) FROM log_resultados_impresion WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio AND reset  = 0 ) AS impresion,
                paquete.nombre AS paquete 
                FROM orden_estudio
                INNER JOIN orden ON (orden.id = orden_estudio.id_orden) 
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) 
                INNER JOIN estudio ON (estudio.id_cat_estudio = cat_estudio.id AND estudio.id_sucursal =  $id_sucursal )
                LEFT JOIN materia_biologica ON (materia_biologica.id = cat_estudio.id_materia_biologica) 
                LEFT JOIN paquete ON (paquete.id = orden_estudio.id_paquete) 
                WHERE orden_estudio.id_orden =  $id_orden AND paciente.expediente ='$expediente' 
                ORDER BY orden_estudio.posicion";
       // echo $sql;
        $estudios = $this->conexion->getQuery($sql);
        return $estudios;
    }

    function restablecerReporte($id_detalle_orden, $id_orden) {

        $sql = "UPDATE log_resultados_impresion SET 
                reset = 1
                WHERE id_estudio = (SELECT id_estudio FROM orden_estudio WHERE id = " . $id_detalle_orden . "  ) AND id_orden='" . $id_orden . "' ";
        $this->conexion->setQuery($sql);

        $sql = "UPDATE orden_estudio 
            SET impreso = 0 
                WHERE id = " . $id_detalle_orden;
        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "RESET DE REPORTE: " . str_replace("'", "", $sql),
            "tabla" => "log_resultados_impresion",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos = new Catalogos();
        $catalogos->logActivity($datos);

        $sql = "INSERT INTO `log_resultados`(`id_usuario`, `id_orden`, `id_estudio`, `id_sucursal`, `fecha`, `ip`, `concepto`) 
                SELECT '" . $_SESSION["id"] . "', '" . $id_orden . "', id_estudio, '" . $_SESSION["id_sucursal"] . "', NOW(), '" . $_SERVER["REMOTE_ADDR"] . "', 'Reset'
                FROM orden_estudio WHERE id = " . $id_detalle_orden;
        $this->conexion->setQuery($sql);

        //NUEVO LOG
        $sql = "INSERT INTO `log_activity_resultados`(`observaciones`, `usuario`, `id_orden_estudio`, `fecha`) 
        SELECT 'ESTUDIO RESTABLECIDO', '" . $_SESSION["usuario"] . "',id,  NOW() 
        FROM orden_estudio WHERE id = " . $id_detalle_orden;
        $this->conexion->setQuery($sql);
    }

    function borarResultado($id_detalle_orden, $id_orden) {

        $sql = "DELETE FROM resultado_estudio 
                WHERE id_orden = " . $id_orden . " AND id_estudio = (
                SELECT id_estudio
                FROM orden_estudio WHERE id = " . $id_detalle_orden . ");";
        $this->conexion->setQuery($sql);

        $sql = "UPDATE orden_estudio 
            SET impreso = 0 
                WHERE id = " . $id_detalle_orden;
        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "BORRAR RESULTADO DEL REPORTE: " . str_replace("'", "", $sql),
            "tabla" => "resultado_estudio",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos = new Catalogos();
        $catalogos->logActivity($datos);

        $sql = "INSERT INTO `log_resultados`(`id_usuario`, `id_orden`, `id_estudio`, `id_sucursal`, `fecha`, `ip`, `concepto`) 
                SELECT '" . $_SESSION["id"] . "', '" . $id_orden . "', id_estudio, '" . $_SESSION["id_sucursal"] . "', NOW(), '" . $_SERVER["REMOTE_ADDR"] . "', 'Reset'
                FROM orden_estudio WHERE id = " . $id_detalle_orden;
        $this->conexion->setQuery($sql);

        //NUEVO LOG
        $sql = "INSERT INTO `log_activity_resultados`(`observaciones`, `usuario`, `id_orden_estudio`, `fecha`) 
        SELECT 'RESULTADO DE ESTUDIO ELIMINADO', '" . $_SESSION["usuario"] . "',id,  NOW() 
        FROM orden_estudio WHERE id = " . $id_detalle_orden;
        $this->conexion->setQuery($sql);
    }

    function borarResultadoTexto($id_detalle_orden, $id_orden) {

        $sql = "DELETE FROM resultado_estudio_texto 
          WHERE id_orden = " . $id_orden . " AND id_estudio = (
          SELECT id_estudio
          FROM orden_estudio WHERE id = " . $id_detalle_orden . ");";
        $this->conexion->setQuery($sql);

        $sql = "UPDATE orden_estudio 
            SET impreso = 0 
                WHERE id = " . $id_detalle_orden;
        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "BORRAR RESULTADO TEXTO DEL REPORTE: " . str_replace("'", "", $sql),
            "tabla" => "resultado_estudio",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos = new Catalogos();
        $catalogos->logActivity($datos);

        //NUEVO LOG
        $sql = "INSERT INTO `log_activity_resultados`(`observaciones`, `usuario`, `id_orden_estudio`, `fecha`) 
        SELECT 'RESULTADO DE ESTUDIO ELIMINADO', '" . $_SESSION["usuario"] . "',id,  NOW() 
        FROM orden_estudio WHERE id = " . $id_detalle_orden;
        $this->conexion->setQuery($sql);


        $sql = "INSERT INTO `log_resultados`(`id_usuario`, `id_orden`, `id_estudio`, `id_sucursal`, `fecha`, `ip`, `concepto`)
          SELECT '" . $_SESSION["id"] . "', '" . $id_orden . "', id_estudio, '" . $_SESSION["id_sucursal"] . "', NOW(), '" . $_SERVER["REMOTE_ADDR"] . "', 'Reset'
          FROM orden_estudio WHERE id = " . $id_detalle_orden;
        $this->conexion->setQuery($sql);
    }

    function getFormatoLab($id_sucursal) {

        $sql = "SELECT * 
            FROM formato_lab
            WHERE id_cliente = (SELECT id_cliente FROM sucursal WHERE id = $id_sucursal)";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getBitacoraEstudio($id_detalle_orden) {
        $sql = "SELECT id, observaciones, usuario, DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fecha 
        FROM log_activity_resultados
        WHERE id_orden_estudio = $id_detalle_orden
        ORDER BY id DESC";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function uplodResultado($id_estudio, $expediente, $file) {

        //Resultado pdf

        $archivo = "RESULTADO-$expediente-$id_estudio.pdf";
        if (move_uploaded_file($file["tmp_name"], "../../reportes/" . $_SESSION["ruta"] . "/resultados/" . $archivo)) {
            $sql = "UPDATE `orden_estudio` "
                    . "SET pdf = '" . $archivo . "' "
                    . "WHERE id = " . $id_estudio;
            $this->conexion->setQuery($sql);

            //log_activity
            $datos = array(
                "observaciones" => "SUBIR PDF DE RESULTADO: " . str_replace("'", "", $sql),
                "tabla" => "orden_estudio",
                "id_tabla" => $id_estudio,
                "usuario" => $_SESSION["usuario"]);

            $catalogos = new Catalogos();
            $catalogos->logActivity($datos);
        }
        return $archivo;
    }

    function deleteResultado($id_estudio) {

        $sql = "UPDATE `orden_estudio` "
                . "SET pdf = NULL "
                . "WHERE id = " . $id_estudio;

        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "BORRAR PDF DE RESULTADO: " . str_replace("'", "", $sql),
            "tabla" => "orden_estudio",
            "id_tabla" => $id_estudio,
            "usuario" => $_SESSION["usuario"]);

        $catalogos = new Catalogos();
        $catalogos->logActivity($datos);
    }

    function getQuimicoReporta($id_detalle_orden) {
        $sql = "SELECT usuario.*
        FROM log_activity_resultados
        INNER JOIN usuario ON (usuario.usuario = log_activity_resultados.usuario)
        WHERE log_activity_resultados.id_orden_estudio = $id_detalle_orden AND observaciones LIKE '%ESTUDIO VALIDADO%'
        ORDER BY fecha DESC";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function infoEnvioResultados($expediente, $codigo) {
        $sql = "SELECT CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno) AS paciente, paciente.expediente, paciente.tel, paciente.cpEmail AS correo, sucursal.nombre AS sucursal, sucursal.tel1 AS tel_sucursal,
        orden.id, sucursal.img    
        FROM paciente
        INNER JOIN orden ON (paciente.id = orden.id_paciente)
        INNER JOIN sucursal ON (sucursal.id = orden.id_sucursal)
        WHERE paciente.expediente = '$expediente' AND orden.consecutivo = '$codigo' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function resultadoMaquila($id_orden_maquila, $id_sucursal_maquila, $id_sucursal) {

        $sql = "SELECT oe.*,o.consecutivo FROM  orden_estudio oe inner join orden o on o.id=oe.id_orden WHERE oe.id_orden = $id_orden_maquila";
        $estudios = $this->conexion->getQuery($sql);
        foreach ($estudios AS $estudio) {
            //------------obteniendo el id_orden de la sucursal destino
            $sqlOS="SELECT id from orden where consecutivo_matriz=".$estudio->consecutivo." and id_sucursal=".$id_sucursal;
            $arrayOrden=$this->conexion->getQuery($sqlOS);
            $id_orden_sucursal=$arrayOrden[0]->id;
            //---------------------------------------------------------
            $sql="UPDATE orden_estudio SET impreso=1 WHERE id_orden=".$id_orden_sucursal." and id_estudio=".$estudio->id_estudio;
            $this->conexion->setQuery($sql);

            $id_orden_estudio_maquila = $estudio->id;
            $componetes = $this->getOrdenEstudioComponentes($id_orden_estudio_maquila, $id_sucursal_maquila);
            $sql="DELETE FROM resultado_estudio WHERE id_orden=".$id_orden_sucursal." and id_estudio=".$estudio->id_estudio;
            $this->conexion->setQuery($sql);

            foreach ($componetes AS $row) {
                $alias = $row->alias;
                $id_estudio = $row->id_estudio;
                $resultado = $row->resultado;
                
                $sql = "INSERT INTO `resultado_estudio`(`resultado`, `id_orden`, `id_estudio`, `id_componente`) 
                    SELECT '" . $resultado . "', '".$id_orden_sucursal."', id_estudio, (SELECT id FROM componente WHERE alias = '$alias' AND id_estudio = $id_estudio AND id_sucursal = $id_sucursal AND activo = 1 ) as id_componente "
                        . "FROM orden_estudio WHERE id = " . $id_orden_estudio_maquila . "";
                $this->conexion->setQuery($sql);

                
            }
            $sql = "INSERT INTO `log_resultados_impresion`(`id_usuario`, `id_orden`, `id_estudio`, `fecha`,  `ip`) 
                        SELECT '".$_SESSION["id"]."' , '" . $id_orden_sucursal . "', id_estudio, NOW(), '" . $_SERVER["REMOTE_ADDR"] . "' FROM orden_estudio WHERE id = " . $id_orden_estudio_maquila ;
                $this->conexion->setQuery($sql);

            
        }
    }

    function close() {
        $this->conexion->close();
    }

}

?>
