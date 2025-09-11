<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/catalogos/Empresas.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/administracion/Inventario.php');

class Pacientes {

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

    function modificacionPacienteUsuarioMaestroM(){
        if($_REQUEST['empresa']!=''  && $_REQUEST['doctor']!=''){
            $empresas = new Empresas();
            $empresa=$empresas->getEmpresa($_REQUEST['empresa']);
            $credito=$empresa[0]->credito;
            if($credito=='')
                $credito=0;
            $sql = "UPDATE orden SET id_empresa=".$_REQUEST['empresa'].", credito=".$credito.", id_doctor=".$_REQUEST['doctor']." WHERE id=".$_REQUEST['id_orden'];
        }
        elseif($_REQUEST['empresa']==''  && $_REQUEST['doctor']!=''){
            $sql = "UPDATE orden SET  id_doctor=".$_REQUEST['doctor']." WHERE id=".$_REQUEST['id_orden'];
        }
        elseif($_REQUEST['empresa']!=''  && $_REQUEST['doctor']==''){
            $empresas = new Empresas();
            $empresa=$empresas->getEmpresa($_REQUEST['empresa']);
            $credito=$empresa[0]->credito;
            if($credito=='')
                $credito=0;
            $sql = "UPDATE orden SET  id_empresa=".$_REQUEST['empresa'].", credito=".$credito." WHERE id=".$_REQUEST['id_orden'];
        }
      
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function getOrdenMaquila($tipo,$sucursal,$consecutivo){
        if($tipo=="matriz"){
            $sql = "SELECT CONCAT(s.codigo,'-',o.consecutivo) as om from orden o inner join sucursal s on o.id_sucursal=s.id where o.id_sucursal=".$sucursal." and o.consecutivo_matriz=".$consecutivo;
        }
        else{
            $sql = "SELECT CONCAT(s.codigo,'-',o.consecutivo) as om from orden o inner join sucursal s on o.id_sucursal=s.id where o.id_sucursal=".$sucursal." and o.consecutivo=".$consecutivo;
        }

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getOrdenes($palabra, $fecha_ini, $fecha_fin, $id_sucursal) {

        $sql = "SELECT orden.*, CONCAT(paciente.nombre, ' ', paciente.paterno , ' ', paciente.materno) AS paciente, DATE_FORMAT(paciente.fecha_nac, '%d/%m/%Y') AS fecha_nacimiento,
        DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y %H:%i') AS fecha_orden, paciente.expediente
        FROM orden
        INNER JOIN paciente ON (paciente.id = orden.id_paciente)
        WHERE (((paciente.nombre LIKE '%$palabra%' OR paciente.paterno LIKE '%$palabra%' OR paciente.materno LIKE '%$palabra%' OR CONCAT(paciente.nombre,' ',paciente.paterno,' ',paciente.materno) LIKE REPLACE('%$palabra%', ' ', '%'))
        AND orden.fecha_registro BETWEEN '$fecha_ini' AND '$fecha_fin 23:59:59')  OR orden.consecutivo = '$palabra') AND  orden.id_sucursal = '$id_sucursal'";



        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPaciente($id_paciente) {
        $sql = "SELECT * "
                . "FROM paciente "
                . "WHERE id = '$id_paciente'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getOrden($id_orden) {
        $sql = "SELECT orden.id_empresa as empresa_id, fp.descripcion,orden.*,paciente.*, DATE_FORMAT(orden.fecha_registro, '%d/%m/%Y %H:%i') AS fecha_orden,CONCAT(doctor.apaterno,' ',doctor.amaterno,' ',doctor.nombre) as nombre_cdoctor, usuario.nombre as nombre_usuario "
                . "FROM orden "
                . "INNER JOIN paciente ON (paciente.id = orden.id_paciente) "
                . "LEFT JOIN doctor ON (doctor.id = orden.id_doctor) "
                . "LEFT JOIN usuario ON (usuario.id = orden.id_usuario) 
                    LEFT JOIN pago p on p.id_orden=orden.id 
                    LEFT JOIN forma_pago fp on fp.id=p.id_forma_pago "
                . "WHERE orden.id = '$id_orden'";

        $orden = $this->conexion->getQuery($sql);


        $sql = "SELECT lpm.precio_maquila, orden_estudio.*, cat_estudio.no_estudio, cat_estudio.alias, cat_estudio.nombre_estudio, estudio.precio_publico, paquete.alias AS paquete, paquete.nombre AS nombre_paquete, paquete.precio AS precio_paquete, paquete_estudio.precio_neto AS precio_detalle_paquete, DATE_FORMAT(orden_estudio.fecha_entrega, '%d/%m/%Y') AS fecha_entrega,  
                CASE  
                    WHEN cat_estudio.resultado_componente = 1   
                    THEN (SELECT COUNT(*) FROM resultado_estudio WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio  )
                    ELSE (SELECT COUNT(*) FROM resultado_estudio_texto WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio  )  
                 END AS reportado,
                (SELECT COUNT(*) FROM log_resultados_impresion WHERE id_orden = $id_orden AND id_estudio = orden_estudio.id_estudio AND reset  = 0 ) AS impresion, IF(orden_estudio.envio_maquila=1,'SI','NO') AS envio_maquila  
                FROM orden_estudio "
                . "INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) "
                . "INNER JOIN estudio ON (estudio.id_cat_estudio = orden_estudio.id_estudio AND estudio.id_sucursal = (SELECT id_sucursal FROM orden WHERE id = $id_orden ) AND estudio.activo = 1) "
                . "LEFT JOIN paquete ON (paquete.id = orden_estudio.id_paquete) "
                . "LEFT JOIN paquete_estudio ON (paquete_estudio.id_paquete = orden_estudio.id_paquete AND paquete_estudio.id_estudio = orden_estudio.id_estudio )  " 
                . "LEFT JOIN lprecios_maquila_sucursales lpm on lpm.id_cat_estudio=cat_estudio.id and lpm.id_sucursal=".$_SESSION['id_sucursal']." "
                . "WHERE orden_estudio.id_orden = '$id_orden'";

        $detalle = $this->conexion->getQuery($sql);

        $sql = "SELECT * "
                . "FROM datos_fiscales_paciente "
                . "WHERE id_paciente = (SELECT id_paciente FROM orden WHERE id = $id_orden )";

        $fiscal = $this->conexion->getQuery($sql);


        $datos = array(
            "orden" => $orden,
            "detalle" => $detalle,
            "fiscal" => $fiscal,
        );


        return $datos;
    }

    function estudiosPaquetesDescripcion($palabra, $id_sucursal, $id_empresa) {

        /**
         * Validar si la empresa tiene lista de precios o aumento o descuento
         * Validar horario de urgencias
         * 
         */
        $sucursal = $this->getSucursal($id_sucursal);

        $sql = "SELECT * FROM sucursal "
                . "WHERE id = $id_sucursal AND NOW() >= inicio_urgencias AND NOW() <= fin_urgencias";
        $horario = $this->conexion->getQuery($sql);
        $aumento = 0;
        if ($horario > 0) {
            $aumento = $horario[0]->aumento_urgencias;
            if ($aumento == "") {
                $aumento = 0;
            }
        }

        if ($id_empresa == "") {
            $sql = "SELECT * FROM (
                SELECT id, no_paquete AS codigo, nombre, alias, 'paquete' AS tipo, precio, precio AS precio_neto, '' AS fecha_entrega, 0 AS porcentaje  FROM paquete WHERE id_sucursal = '$id_sucursal' AND activo = 1
                UNION
                SELECT cat_estudio.id, cat_estudio.no_estudio, cat_estudio.nombre_estudio, cat_estudio.alias, 'estudio', estudio.precio_publico, estudio.precio_publico*(1+(" . $aumento . "/100)) AS precio_neto, DATE_FORMAT(ADDDATE(NOW(),INTERVAL estudio.montaje + estudio.procesos DAY), '%d/%m/%Y') AS fecha_entrega, estudio.porcentaje 
                FROM estudio
                INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)
                WHERE estudio.id_sucursal = '$id_sucursal' AND estudio.activo = 1
            ) AS consulta
            WHERE alias = '$palabra' OR nombre LIKE '%$palabra%' OR codigo = '$palabra' ";
        } else {
            $empresas = new Empresas();
            $empresa = $empresas->getEmpresa($id_empresa);

            if ($empresa[0]->id_lista_precios != "") {

                $sql = "SELECT * FROM (
                SELECT paquete.id, no_paquete AS codigo, nombre, alias, 'paquete' AS tipo, precio, precio AS precio_neto, '' AS fecha_entrega 
                FROM paquete
                INNER JOIN lista_precios_estudio ON (lista_precios_estudio.id_paquete = paquete.id AND lista_precios_estudio.id_sucursal = '$id_sucursal' AND lista_precios_estudio.activo = 1)
                WHERE paquete.id_sucursal = '$id_sucursal'  AND lista_precios_estudio.id_lista_precio = '" . $empresa[0]->id_lista_precios . "' AND paquete.activo = 1
                UNION
                SELECT cat_estudio.id, cat_estudio.no_estudio, cat_estudio.nombre_estudio, cat_estudio.alias, 'estudio', lista_precios_estudio.precio_publico, lista_precios_estudio.precio_neto, DATE_FORMAT(ADDDATE(NOW(),INTERVAL estudio.montaje + estudio.procesos DAY), '%d/%m/%Y') AS fecha_entrega FROM estudio
                INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)
                INNER JOIN lista_precios_estudio ON (lista_precios_estudio.id_estudio = cat_estudio.id AND  lista_precios_estudio.id_sucursal = '$id_sucursal')
                WHERE estudio.id_sucursal = '$id_sucursal' AND lista_precios_estudio.id_lista_precio = '" . $empresa[0]->id_lista_precios . "' AND estudio.activo = 1
            ) AS consulta
            WHERE alias = '$palabra' OR nombre LIKE '%$palabra%' OR codigo = '$palabra' ";
            } else if ($empresa[0]->porcentaje != "" && $empresa[0]->porcentaje > 0) {
                $sql = "SELECT * FROM (
                SELECT id, no_paquete AS codigo, nombre, alias, 'paquete' AS tipo, precio, precio AS precio_neto, '' AS fecha_entrega FROM paquete WHERE id_sucursal = '$id_sucursal' AND activo = 1
                UNION
                SELECT cat_estudio.id, cat_estudio.no_estudio, cat_estudio.nombre_estudio, cat_estudio.alias, 'estudio', estudio.precio_publico, estudio.precio_publico*(1-(" . $empresa[0]->porcentaje . "/100)) AS precio_neto, DATE_FORMAT(ADDDATE(NOW(),INTERVAL estudio.montaje + estudio.procesos DAY), '%d/%m/%Y') AS fecha_entrega FROM estudio
                INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)
                WHERE estudio.id_sucursal = '$id_sucursal' AND estudio.activo = 1
            ) AS consulta
            WHERE alias = '$palabra' OR nombre LIKE '%$palabra%' OR codigo = '$palabra' ";
            } else {
                $sql = "SELECT * FROM (
                SELECT id, no_paquete AS codigo, nombre, alias, 'paquete' AS tipo, precio, precio AS precio_neto, '' AS fecha_entrega FROM paquete WHERE id_sucursal = '$id_sucursal' AND activo = 1
                UNION
                SELECT cat_estudio.id, cat_estudio.no_estudio, cat_estudio.nombre_estudio, cat_estudio.alias, 'estudio', estudio.precio_publico, estudio.precio_publico*(1+(" . $empresa[0]->aumento . "/100)) AS precio_neto, DATE_FORMAT(ADDDATE(NOW(),INTERVAL estudio.montaje + estudio.procesos DAY), '%d/%m/%Y') AS fecha_entrega FROM estudio
                INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)
                WHERE estudio.id_sucursal = '$id_sucursal' AND estudio.activo = 1
            ) AS consulta
            WHERE alias = '$palabra' OR nombre LIKE '%$palabra%' OR codigo = '$palabra' ";
            }
        }

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPaquete($id_paquete, $id_sucursal, $id_empresa) {
        if ($id_empresa == "") {
            $sql = "SELECT cat_estudio.id, cat_estudio.no_estudio, cat_estudio.nombre_estudio, cat_estudio.alias, estudio.precio_publico, paquete_estudio.precio_neto, DATE_FORMAT(ADDDATE(NOW(),INTERVAL estudio.montaje + estudio.procesos DAY), '%d/%m/%Y') AS fecha_entrega, estudio.porcentaje  "
                    . "FROM paquete "
                    . "INNER JOIN paquete_estudio ON (paquete.id = paquete_estudio.id_paquete)"
                    . "INNER JOIN estudio ON (estudio.id_cat_estudio = paquete_estudio.id_estudio AND estudio.id_sucursal = (SELECT id_sucursal FROM paquete WHERE id = $id_paquete ))"
                    . "INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)"
                    . "WHERE paquete.id ='$id_paquete' AND paquete.id_sucursal = '$id_sucursal'";
            $data = $this->conexion->getQuery($sql);
        } else {
            $empresas = new Empresas();
            $empresa = $empresas->getEmpresa($id_empresa);

            if ($empresa[0]->id_lista_precios != "") {
                $sql = "SELECT cat_estudio.id, cat_estudio.no_estudio, cat_estudio.nombre_estudio, cat_estudio.alias, estudio.precio_publico, (lista_precios_estudio.precio_neto/lista_precios_estudio.precio_publico)*paquete_estudio.precio_neto AS precio_neto, DATE_FORMAT(ADDDATE(NOW(),INTERVAL estudio.montaje + estudio.procesos DAY), '%d/%m/%Y') AS fecha_entrega "
                        . "FROM paquete "
                        . "INNER JOIN paquete_estudio ON (paquete.id = paquete_estudio.id_paquete)"
                        . "INNER JOIN estudio ON (estudio.id_cat_estudio = paquete_estudio.id_estudio AND estudio.id_sucursal = '$id_sucursal')"
                        . "INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)"
                        . "INNER JOIN lista_precios_estudio ON (lista_precios_estudio.id_paquete = paquete.id AND lista_precios_estudio.id_sucursal = '$id_sucursal' AND lista_precios_estudio.activo = 1)"
                        . "WHERE paquete.id ='$id_paquete' AND paquete.id_sucursal = '$id_sucursal' AND lista_precios_estudio.id_lista_precio = '" . $empresa[0]->id_lista_precios . "'";
            } else if ($empresa[0]->porcentaje != "" && $empresa[0]->porcentaje > 0) {
                $sql = "SELECT cat_estudio.id, cat_estudio.no_estudio, cat_estudio.nombre_estudio, cat_estudio.alias, estudio.precio_publico, paquete_estudio.precio_neto*(1-(" . $empresa[0]->porcentaje . "/100)) AS precio_neto, DATE_FORMAT(ADDDATE(NOW(),INTERVAL estudio.montaje + estudio.procesos DAY), '%d/%m/%Y') AS fecha_entrega "
                        . "FROM paquete "
                        . "INNER JOIN paquete_estudio ON (paquete.id = paquete_estudio.id_paquete)"
                        . "INNER JOIN estudio ON (estudio.id_cat_estudio = paquete_estudio.id_estudio AND estudio.id_sucursal = (SELECT id_sucursal FROM paquete WHERE id = $id_paquete ))"
                        . "INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)"
                        . "WHERE paquete.id ='$id_paquete' AND paquete.id_sucursal = '$id_sucursal'";
            } else {
                $sql = "SELECT cat_estudio.id, cat_estudio.no_estudio, cat_estudio.nombre_estudio, cat_estudio.alias, estudio.precio_publico, paquete_estudio.precio_neto*(1+(" . $empresa[0]->aumento . "/100)) AS precio_neto, DATE_FORMAT(ADDDATE(NOW(),INTERVAL estudio.montaje + estudio.procesos DAY), '%d/%m/%Y') AS fecha_entrega "
                        . "FROM paquete "
                        . "INNER JOIN paquete_estudio ON (paquete.id = paquete_estudio.id_paquete)"
                        . "INNER JOIN estudio ON (estudio.id_cat_estudio = paquete_estudio.id_estudio AND estudio.id_sucursal = (SELECT id_sucursal FROM paquete WHERE id = $id_paquete ))"
                        . "INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)"
                        . "WHERE paquete.id ='$id_paquete' AND paquete.id_sucursal = '$id_sucursal'";
            }
        }

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getInstrucciones($alias, $id_sucursal) {
        $sql = "SELECT indicaciones.*
                FROM estudio
                INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)
                 INNER JOIN indicaciones ON (indicaciones.id = estudio.id_indicaciones)
                WHERE cat_estudio.alias = '$alias' AND estudio.id_sucursal = '$id_sucursal' AND estudio.activo = 1";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPrecioMaquilaEstudio($id_estudio,$id_sucursal){
        $sql = "SELECT precio_maquila,id_paquete_matriz FROM lprecios_maquila_sucursales 
                where (id_cat_estudio=".$id_estudio." or id_paquete_sucursal=".$id_estudio.") and id_sucursal=".$id_sucursal;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    #---------------Función para insertar pacientes de maquila------------------------------ 
    function addPacienteMaquila($data){
        $anio = date("Y");
        $catalogos = new Catalogos();
        $total=array_sum($data['precio_maquila']);
        // obteniendo el id de la sucursal matriz
        $sql="SELECT id from sucursal where tipo='MATRIZ' and id_cliente = 
             (SELECT id_cliente from sucursal where id=".$data["id_sucursal"].")";
        $res=$this->conexion->getQuery($sql);
        $idMatriz=$res[0]->id;


        //--------------Se inserta el paciente
        $expediente = $this->getToken(8) . "-" . $this->getToken(4);

        $sql = "INSERT INTO paciente (paterno, materno, nombre, fecha_nac, edad, tipo_edad, sexo, direccion, tel, cpEmail, filacion, observaciones, fur, fud, id_empresa, id_sucursal, RFC, email, fecha_registro, expediente)"
                . "VALUES ('" . $data["paterno"] . "', '" . $data["materno"] . "', '" . $data["nombre"] . "', '" . $data["fecha_nac"] . "', '" . $data["edad"] . "', '" . $data["tipo_edad"] . "', '" . $data["sexo"] . "', '" . $data["direccion"] . "', '" . $data["tel"] . "', '" . $data["cpEmail"] . "', '" . $data["filiacion"] . "', '" . $data["observaciones"] . "', '" . $data["fur"] . "', '" . $data["fud"] . "', " . $data["id_empresa"] . ", " . $idMatriz . ", '" . $data["rfcfactura"] . "', '" . $data["mailfactura"] . "', NOW(), '$expediente');";
        $this->conexion->setQuery($sql);
        $data["id_paciente"] = $catalogos->maxTable("paciente", $idMatriz);
        
        //Se declara el nombre del doctor
        $nombre_doctor = "" . $data["medico"] . "";
        $credito=1;
        $empresa_=NULL;
        if($data["id_sucursal"]==123){//--otumba
            $empresa_=591;
        }
        elseif($data["id_sucursal"]==124){//----teo
            $empresa_=590;
        }
        elseif($data["id_sucursal"]==140){//------acoman
            $empresa_=691;
        }
        elseif($data["id_sucursal"]==141){//------calpulalpan
            $empresa_=647;
        }
        elseif($data["id_sucursal"]==143){//------calpulalpan
            $empresa_=724;
        }
        elseif($data["id_sucursal"]==154){//------chimalhuacan
            $empresa_=776;
        }
        elseif($data["id_sucursal"]==152){//------2DE MARZO
            $empresa_=777;
        }


        $sql = "INSERT INTO orden (consecutivo, id_paciente, id_descuento, id_doctor, id_empresa, id_sucursal, filacion, observaciones, direccion, telefono, aumento, anio, importe, saldo_deudor, nombre_doctor, fecha_registro, credito, id_usuario,sucursal_maquila,tipo_orden)"
                        . " SELECT CASE WHEN MAX(consecutivo) IS NOT NULL THEN MAX(consecutivo)+ 1 ELSE 1 END, '" . $data["id_paciente"] . "', " . $data["id_descuento"] . ", " . $data["id_doctor"] . ", " . $empresa_ . ", " . $idMatriz . ", '" . $data["filiacion"] . "', '" . $data["observaciones"] . "', '" . $data["direccion"] . "', '" . $data["tel"] . "', '" . $data["aumento"] . "', '" . $anio . "', '" . $total . "', '" . $total . "', '" . $nombre_doctor . "', NOW(), $credito, " . $_SESSION["id"] . "," . $data["id_sucursal"] . " , '".$data['tipo_orden']."' FROM orden WHERE id_sucursal='" . $idMatriz . "';";

        $this->conexion->setQuery($sql);
        $data["id_orden"] = $catalogos->maxTable("orden", $idMatriz);
        //log_activity
        $datos = array(
                    "observaciones" => "NUEVA ORDEN DE MAQUILA: " . str_replace("'", "", $sql),
                    "tabla" => "orden",
                    "id_tabla" => 0,
                    "usuario" => $_SESSION["usuario"]); 

        $catalogos->logActivity($datos);

        //registrar el detalle de la orden
        for ($i = 0; $i < count($data["codigo"]); $i++) {
                if($data["maquila"][$i]>0){
                    $precio_neto = str_replace(",", "", $data["precio_maquila"][$i]);
                    if($data["paquete"][$i] == "" ){
                        $sql = "INSERT INTO orden_estudio (id_estudio, id_paquete, id_orden, precio_neto_estudio, fecha_entrega, fecha_registro, posicion,envio_maquila) 
                            SELECT id, null, '" . $data["id_orden"] . "', '" . $precio_neto . "', '" . $data["fecha_entrega"][$i] . "', NOW(), " . ($i + 1) . ", 1 FROM cat_estudio WHERE alias = '" . $data["codigo"][$i] . "' ";
                            $this->conexion->setQuery($sql);
                    }
                    else{
                        $sql="select p.id,pe.id_estudio,pe.posicion from paquete p inner join paquete_estudio pe on p.id=pe.id_paquete  WHERE p.alias = '" . $data["paquete"][$i] . "' AND p.id_sucursal = '" . $idMatriz . "' AND p.activo = 1";
                        $paquete=$this->conexion->getQuery($sql);
                        $precio_neto=$precio_neto/count($paquete);
                        $numeroEstudios=count($paquete);
                        foreach ($paquete as $row => $item) { 
                            $sql = "INSERT INTO orden_estudio (id_estudio, id_paquete, id_orden, precio_neto_estudio, fecha_entrega, fecha_registro, posicion,envio_maquila) 
                             VALUES($item->id_estudio,$item->id,".$data["id_orden"].",$precio_neto,'".$data["fecha_entrega"][$i]."', NOW(),$item->posicion,1)";
                            $this->conexion->setQuery($sql);
                        }
                    }
                    
                }           
        }

        //codigo
        $sql = "SELECT * FROM orden WHERE id = " . $data["id_orden"] . "";
        $consecutivo = $this->conexion->getQuery($sql);

        return array(
            "id_orden" => $data["id_orden"] ,
            "codigo" => $consecutivo[0]->consecutivo);

    }

    function modificacionMaquilaPaciente($data){
        $sql = "SELECT id,importe FROM orden WHERE consecutivo= " . $data["ordenmatriz"] . " and id_sucursal=121";
        $orden_matriz = $this->conexion->getQuery($sql)[0];
        $importe=$orden_matriz->importe;
        for ($i=0; $i < count($data['maquila']); $i++) { 
            if(!in_array($data['maquila'][$i],$data['enviado_maquila']) && $data["maquila"][$i]>0){
                $precio_neto = str_replace(",", "", $data["precio_maquila"][$i]);
                $importe=$importe+$precio_neto;
                if($data["paquete"][$i] == "" ){
                    $sql = "INSERT INTO orden_estudio (id_estudio, id_paquete, id_orden, precio_neto_estudio, fecha_entrega, fecha_registro, posicion,envio_maquila) 
                        SELECT id, null, '" . $orden_matriz->id . "', '" . $precio_neto . "', '" . $data["fecha_entrega"][$i] . "', NOW(), " . ($i + 1) . ", 1 FROM cat_estudio WHERE alias = '" . $data["codigo"][$i] . "' ";
                        $this->conexion->setQuery($sql);
                }
                else{
                    $sql="select p.id,pe.id_estudio,pe.posicion from paquete p inner join paquete_estudio pe on p.id=pe.id_paquete  WHERE p.alias = '" . $data["paquete"][$i] . "' AND p.id_sucursal = 121 AND p.activo = 1";
                    $paquete=$this->conexion->getQuery($sql);
                    $precio_neto=number_format($precio_neto/count($paquete),2);
                    $numeroEstudios=count($paquete);
                    foreach ($paquete as $row => $item) { 
                        $sql = "INSERT INTO orden_estudio (id_estudio, id_paquete, id_orden, precio_neto_estudio, fecha_entrega, fecha_registro, posicion,envio_maquila) 
                         VALUES($item->id_estudio,$item->id,".$orden_matriz->id.",$precio_neto,'".$data["fecha_entrega"][$i]."', NOW(),$item->posicion,1)";

                        $this->conexion->setQuery($sql);
                    }
                }
                $sql='UPDATE orden SET importe='.$importe.' where id='.$orden_matriz->id;
                $this->conexion->setQuery($sql);
            }
        }
    }

#--------------------------------------Termina maquila -------------------------------------#

    function addPaciente($data) {
        //$fecha = date("Y-m-d H:i:s");
        $anio = date("Y");

        $catalogos = new Catalogos();

        $empresas = new Empresas();
        $empresa = $empresas->getEmpresa($data["id_empresa"]);
        $credito = $empresa[0]->credito == "" ? 0 : $empresa[0]->credito;


        if ($data["id_paciente"] != "") {
            $sql = "UPDATE paciente "
                    . "SET paterno = '" . $data["paterno"] . "', materno = '" . $data["materno"] . "', nombre = '" . $data["nombre"] . "', fecha_nac = '" . $data["fecha_nac"] . "', edad = '" . $data["edad"] . "', tipo_edad = '" . $data["tipo_edad"] . "', sexo = '" . $data["sexo"] . "', direccion = '" . $data["direccion"] . "', tel='" . $data["tel"] . "',cpEmail='" . $data["cpEmail"] . "',filacion='" . $data["filiacion"] . "',observaciones='" . $data["observaciones"] . "',fur='" . $data["fur"] . "',fud='" . $data["fud"] . "',id_empresa=" . $data["id_empresa"] . ",id_sucursal='" . $data["id_sucursal"] . "', RFC='" . $data["rfc"] . "',email='" . $data["mail"] . "' WHERE id='" . $data["id_paciente"] . "'";
            $this->conexion->setQuery($sql);
        } else {
            $expediente = $this->getToken(8) . "-" . $this->getToken(4);

            $sql = "INSERT INTO paciente (paterno, materno, nombre, fecha_nac, edad, tipo_edad, sexo, direccion, tel, cpEmail, filacion, observaciones, fur, fud, id_empresa, id_sucursal, RFC, email, fecha_registro, expediente)"
                    . "VALUES ('" . $data["paterno"] . "', '" . $data["materno"] . "', '" . $data["nombre"] . "', '" . $data["fecha_nac"] . "', '" . $data["edad"] . "', '" . $data["tipo_edad"] . "', '" . $data["sexo"] . "', '" . $data["direccion"] . "', '" . $data["tel"] . "', '" . $data["cpEmail"] . "', '" . $data["filiacion"] . "', '" . $data["observaciones"] . "', '" . $data["fur"] . "', '" . $data["fud"] . "', " . $data["id_empresa"] . ", " . $data["id_sucursal"] . ", '" . $data["rfcfactura"] . "', '" . $data["mailfactura"] . "', NOW(), '$expediente');";
            $this->conexion->setQuery($sql);

            $data["id_paciente"] = $catalogos->maxTable("paciente", $data["id_sucursal"]);

            //Fiscal
            if ($data["rfc"] != "" && $data["cliente"] != "" && $data["cp"] != "") {
                $sql = "INSERT INTO datos_fiscales_paciente (id_paciente, rfc, nombre_fiscal, direccion_fiscal, codigo_postal, correo, uso_cfdi)"
                        . "VALUES ('" . $data["id_paciente"] . "', '" . $data["rfc"] . "', '" . $data["cliente"] . "', '" . $data["domicilio"] . "', '" . $data["cp"] . "', '" . $data["mail"] . "', " . $data["cfdi"] . ")";
                $this->conexion->setQuery($sql);
            }
        }


        //ya que se registro el paciente se registra el orden

        if ($data["id_doctor"] != "NULL") {
            $sql = "SELECT nombre FROM doctor WHERE id = '" . $data["id_doctor"] . "'";
            $doctores = $this->conexion->getQuery($sql);
            $nombre_doctor = "" . $doctores[0]->nombre . "";
        } else {
            $nombre_doctor = "" . $data["medico"] . "";
        }

        $total = str_replace(",", "", $data["total"]);

        if ($data["cotizacion"] == 1) {
            $sql = "INSERT INTO orden_cotizacion (consecutivo, id_paciente, id_descuento, id_doctor, id_empresa, id_sucursal, filacion, observaciones, direccion, telefono, aumento, anio, importe, saldo_deudor, nombre_doctor, fecha_registro, credito, id_usuario)"
                    . " SELECT CASE WHEN MAX(consecutivo) IS NOT NULL THEN MAX(consecutivo)+ 1 ELSE 1 END, '" . $data["id_paciente"] . "', " . $data["id_descuento"] . ", " . $data["id_doctor"] . ", " . $data["id_empresa"] . ", " . $data["id_sucursal"] . ", '" . $data["filiacion"] . "', '" . $data["observaciones"] . "', '" . $data["direccion"] . "', '" . $data["tel"] . "', '" . $data["aumento"] . "', '" . $anio . "', '" . $total . "', '" . $total . "', '" . $nombre_doctor . "', NOW(), $credito, " . $_SESSION["id"] . "  FROM orden_cotizacion WHERE id_sucursal='" . $data["id_sucursal"] . "';";

            $this->conexion->setQuery($sql);

            $data["id_orden"] = $catalogos->maxTable("orden_cotizacion", $data["id_sucursal"]);

            //log_activity
            $datos = array(
                "observaciones" => "NUEVA COTIZACION: " . str_replace("'", "", $sql),
                "tabla" => "orden",
                "id_tabla" => 0,
                "usuario" => $_SESSION["usuario"]);

            $catalogos->logActivity($datos);

            //registrar el detalle de la orden
            for ($i = 0; $i < count($data["codigo"]); $i++) {
                $precio_neto = str_replace(",", "", $data["precio_neto"][$i]);
                $id_paquete = $data["paquete"][$i] == "" ? "NULL" : "(SELECT id FROM paquete WHERE alias = '" . $data["paquete"][$i] . "' AND id_sucursal = '" . $data["id_sucursal"] . "' AND activo = 1)";

                $sql = "INSERT INTO orden_estudio_cotizacion (id_estudio, id_paquete, id_orden_cotizacion, precio_neto_estudio, fecha_entrega, fecha_registro) 
                SELECT id, " . $id_paquete . ", '" . $data["id_orden"] . "', '" . $precio_neto . "', '" . $data["fecha_entrega"][$i] . "', NOW() FROM cat_estudio WHERE alias = '" . $data["codigo"][$i] . "' ";

                $this->conexion->setQuery($sql);
            }

            //codigo
            $sql = "SELECT * FROM orden_cotizacion "
                    . "WHERE id = " . $data["id_orden"] . "";
            $consecutivo = $this->conexion->getQuery($sql);
        } else {

            //validar que no hayan enviado vacio
            if ($data["precio_neto"][0] != "" && $data["nombre"] != "") {

                $sql = "INSERT INTO orden (consecutivo, id_paciente, id_descuento, id_doctor, id_empresa, id_sucursal, filacion, observaciones, direccion, telefono, aumento, anio, importe, saldo_deudor, nombre_doctor, fecha_registro, credito, id_usuario,consecutivo_matriz, tipo_orden)"
                        . " SELECT CASE WHEN MAX(consecutivo) IS NOT NULL THEN MAX(consecutivo)+ 1 ELSE 1 END, '" . $data["id_paciente"] . "', " . $data["id_descuento"] . ", " . $data["id_doctor"] . ", " . $data["id_empresa"] . ", " . $data["id_sucursal"] . ", '" . $data["filiacion"] . "', '" . $data["observaciones"] . "', '" . $data["direccion"] . "', '" . $data["tel"] . "', '" . $data["aumento"] . "', '" . $anio . "', '" . $total . "', '" . $total . "', '" . $nombre_doctor . "', NOW(), $credito, " . $_SESSION["id"] . "," . $data["ordenmatriz"] . ", '" . $data["tipo_orden"] . "'   FROM orden WHERE id_sucursal='" . $data["id_sucursal"] . "';";

                $this->conexion->setQuery($sql);

                $data["id_orden"] = $catalogos->maxTable("orden", $data["id_sucursal"]);

                //log_activity
                $datos = array(
                    "observaciones" => "NUEVA ORDEN: " . str_replace("'", "", $sql),
                    "tabla" => "orden",
                    "id_tabla" => 0,
                    "usuario" => $_SESSION["usuario"]);

                $catalogos->logActivity($datos);

                //registrar el detalle de la orden
                for ($i = 0; $i < count($data["codigo"]); $i++) {
                    $maquila=0;
                    if($data["maquila"][$i]>0)
                        $maquila=1;
                    $precio_neto = str_replace(",", "", $data["precio_neto"][$i]);
                    $precio_publicoh=str_replace(",","",$data["precio_publico"][$i]);
                    $id_paquete = $data["paquete"][$i] == "" ? "NULL" : "(SELECT id FROM paquete WHERE alias = '" . $data["paquete"][$i] . "' AND id_sucursal = '" . $data["id_sucursal"] . "' AND activo = 1)";

                    $sql = "INSERT INTO orden_estudio (id_estudio, id_paquete, id_orden, precio_neto_estudio, precio_publicoh, descuento_individual,fecha_entrega, fecha_registro, posicion, envio_maquila) 
                    SELECT id, " . $id_paquete . ", '" . $data["id_orden"] . "', '" . $precio_neto . "', '" . $precio_publicoh . "','" . $data["pdescuento"][$i] . "',        '" . $data["fecha_entrega"][$i] . "', NOW(), " . ($i + 1) . ",'" . $maquila . "'  FROM cat_estudio WHERE alias = '" . $data["codigo"][$i] . "' ";

                    $this->conexion->setQuery($sql);

                    //SALIDA DE PRODUCTOS DE INVENTARIO
                    /*$sql = "SELECT combos_estudio.id_producto, productos.consecutivo, combos_estudio.cantidad * (productos.cantidad/productos.cant_pruebas) AS cantidad
                    FROM combos_estudio
                    INNER JOIN cat_estudio ON (cat_estudio.id = combos_estudio.id_estudio)
                    LEFT JOIN productos ON (productos.id = combos_estudio.id_producto)
                    LEFT JOIN cat_presentacion_producto ON (cat_presentacion_producto.id = productos.id_presentacion_producto)
                    LEFT JOIN cat_unidades_producto ON (cat_unidades_producto.id = productos.id_unidad_producto)
                    WHERE cat_estudio.alias = '" . $data["codigo"][$i] . "' AND combos_estudio.id_cliente = (SELECT id_cliente FROM sucursal WHERE id= " . $data["id_sucursal"] . " )";
                    $productos = $this->conexion->getQuery($sql);

                    if (count($productos) > 0) {
                        $sql = "SELECT orden.consecutivo, CONCAT(paciente.nombre, ' ', paciente.paterno , ' ', paciente.materno) AS paciente  "
                                . "FROM orden "
                                . "INNER JOIN paciente ON (paciente.id = orden.id_paciente) "
                                . "WHERE orden.id = " . $data["id_orden"];

                        $pacienteOrden = $this->conexion->getQuery($sql)[0];

                        foreach ($productos AS $producto) {

                            $codigoProucto[] = $producto->consecutivo;
                            $cantidadProucto[] = $producto->cantidad;
                        }
                        $dataProductos = array(
                            "observaciones" => "SALIDA POR PACIENTE " . $pacienteOrden->consecutivo . " (" . $pacienteOrden->paciente . ")",
                            "codigo" => $codigoProucto,
                            "egreso" => $cantidadProucto,
                        );
                        $inventario = new Inventario();
                        $inventario->addValeSalida($dataProductos);
                        $inventario->close;
                    }*/
                }

                //Guardar en bitacora paciente

                $estudios = implode(", ", $data["codigo"]);
                $generales = "Nombre: " . ($data["nombre"] . " " . $data["paterno"] . " " . $data["materno"] ) . "<br>" .
                        "Edad: " . ($data["edad"] . " " . ( $data["tipo_edad"] != "Anios" ? $data["tipo_edad"] : "Años" ) ) . "<br>" .
                        "Sexo: " . ( $data["sexo"] == "Nino" ? "Niño" : $data["sexo"] ) . "<br>" .
                        "Fecha nacimiento: " . $data["fecha_nac"] . "<br>" .
                        "Médico: " . $nombre_doctor . "<br>" .
                        "Empresa: " . $empresa[0]->nombre . "<br>";

                $sql = "INSERT INTO `bitacora_paciente`(`id_paciente`, `nombre`, `fecha`, `concepto`, `monto`, estudios, generales,
                `id_usuario`, `id_sucursal`, `id_orden`) 
                SELECT paciente.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno), NOW(), 'REGISTRO PACIENTE', " . $total . ", '$estudios', '$generales',"
                        . $_SESSION["id"] . ", " . $data["id_sucursal"] . ", " . $data["id_orden"] . " 
                FROM orden 
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                WHERE orden.id = " . $data["id_orden"];
                $this->conexion->setQuery($sql);

                //codigo
                $sql = "SELECT * FROM orden "
                        . "WHERE id = " . $data["id_orden"] . "";
                $consecutivo = $this->conexion->getQuery($sql);
            }
        }

        return array(
            "cotizacion" => $data["cotizacion"],
            "credito" => $credito,
            "codigo" => $consecutivo[0]->consecutivo);
    }

    function modificacionPaciente($data) {
        $anio = date("Y");

        $catalogos = new Catalogos();

        $empresas = new Empresas();
        $empresa = $empresas->getEmpresa($data["id_empresa"]);
        $credito = $empresa[0]->credito == "" ? 0 : $empresa[0]->credito;

        //Modificar el paciente
        $sql = "UPDATE paciente "
                . "SET paterno='" . $data["paterno"] . "', materno='" . $data["materno"] . "', nombre='" . $data["nombre"] . "', fecha_nac='" . $data["fecha_nac"] . "', edad='" . $data["edad"] . "', tipo_edad='" . $data["tipo_edad"] . "', sexo='" . $data["sexo"] . "',direccion='" . $data["direccion"] . "', tel='" . $data["tel"] . "', cpEmail='" . $data["cpEmail"] . "', filacion='" . $data["filiacion"] . "', observaciones='" . $data["observaciones"] . "', fur='" . $data["fur"] . "', fud='" . $data["fud"] . "', id_empresa=" . $data["id_empresa"] . ", id_sucursal='" . $data["id_sucursal"] . "', RFC='" . $data["rfcfactura"] . "', email='" . $data["mailfactura"]
                . "' WHERE id='" . $data["id_paciente"] . "'";
        $this->conexion->setQuery($sql);

        //Modificascion de datos fiscales 
        $sql = "SELECT * FROM datos_fiscales_paciente 
                WHERE id_paciente = '" . $data["id_paciente"] . "'";
        $fiscal = $this->conexion->getQuery($sql);

        if ($data["rfc"] != "" && $data["cliente"] != "" && $data["cp"] != "") {
            if (count($fiscal) == 0) {
                $sql = "INSERT INTO datos_fiscales_paciente (id_paciente, rfc, nombre_fiscal, direccion_fiscal, codigo_postal, correo, uso_cfdi)"
                        . "VALUES ('" . $data["id_paciente"] . "', '" . $data["rfc"] . "', '" . $data["cliente"] . "', '" . $data["domicilio"] . "', '" . $data["cp"] . "', '" . $data["mail"] . "', " . $data["cfdi"] . ")";
                $this->conexion->setQuery($sql);
            } else {
                $sql = "UPDATE datos_fiscales_paciente
                SET rfc = '" . $data["rfc"] . "', nombre_fiscal = '" . $data["cliente"] . "', direccion_fiscal = '" . $data["domicilio"] . "', codigo_postal = '" . $data["cp"] . "', correo = '" . $data["mail"] . "', uso_cfdi = " . $data["cfdi"] . " 
                WHERE id_paciente = '" . $data["id_paciente"] . "'";
                $this->conexion->setQuery($sql);
            }
        }

        //log_activity
        $datos = array(
            "observaciones" => "MODIFICACION DE PACIENTE: " . str_replace("'", "", $sql),
            "tabla" => "paciente",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos->logActivity($datos);

        if ($data["id_doctor"] != "NULL") {
            $sql = "SELECT nombre FROM doctor WHERE id = '" . $data["id_doctor"] . "'";
            $doctores = $this->conexion->getQuery($sql);
            $nombre_doctor = "" . $doctores[0]->nombre . "";
        } else {
            $nombre_doctor = "" . $data["medico"] . "";
        }

        $total = str_replace(",", "", $data["total"]);

        /*         * **************REVISAR LO DEL PAGO******************* */
        //Modificar orden

        $sql = "UPDATE orden "
                . "SET id_doctor = " . $data["id_doctor"] . ", id_empresa=" . $data["id_empresa"] . ", filacion='" . $data["filacion"] . "', observaciones='" . $data["observaciones"] . "',tipo_orden='" . $data["tipo_orden"] . "', direccion='" . $data["direccion"] . "', telefono='" . $data["tel"] . "', aumento='" . $data["aumento"] . "', importe='" . $total . "', saldo_deudor = " . $total . "-(SELECT CASE WHEN sum(pago) IS NULL THEN 0 ELSE sum(pago) END FROM pago WHERE id_orden= '" . $data["id_orden"] . "'), nombre_doctor='" . $nombre_doctor . "', credito='" . $credito . "', id_descuento = " . $data["id_descuento"] . " "
                . "WHERE id='" . $data["id_orden"] . "'";
        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "MODIFICACION DE ORDEN: " . str_replace("'", "", $sql),
            "tabla" => "orden",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos->logActivity($datos);

        //Estudios de orden
        $estudios = [];
        $sql = "SELECT * FROM orden_estudio 
                INNER JOIN cat_estudio ON (orden_estudio.id_estudio = cat_estudio.id)"
                . "WHERE id_orden ='" . $data["id_orden"] . "'";
        $estudios_orden = $this->conexion->getQuery($sql);
        foreach ($estudios_orden AS $row) {
            $estudios[] = "'" . $row->alias . "'";
        }

        //Estudios de orden modificados
        $estudios_actuales = [];
        for ($i = 0; $i < count($data["codigo"]); $i++) {
            $id_detalle = $data["id_detalle"][$i];
            if ($id_detalle != "" && $data["codigo"][$i] != "") {
                $estudios_actuales[] = "'" . $data["codigo"][$i] . "'";
            }
        }

        //eliminar los que se hayan borrado
        $eliminar = implode(",", array_diff($estudios, $estudios_actuales));

        if ($eliminar != "") {
            $sql = "DELETE orden_estudio.* FROM orden_estudio
                INNER JOIN cat_estudio ON (orden_estudio.id_estudio = cat_estudio.id)
                WHERE cat_estudio.alias in ($eliminar) AND id_orden ='" . $data["id_orden"] . "'";
            $this->conexion->setQuery($sql);
        }

        //agrgar o acualizar el detalle de la orden
        for ($i = 0; $i < count($data["codigo"]); $i++) {
            $maquila=0;
            if($data["maquila"][$i]>0)
                $maquila=1;
            $precio_neto = str_replace(",", "", $data["precio_neto"][$i]);
            $id_detalle = $data["id_detalle"][$i];
            $id_paquete = $data["paquete"][$i] == "" ? "NULL" : "(SELECT id FROM paquete WHERE alias = '" . $data["paquete"][$i] . "' AND id_sucursal = '" . $data["id_sucursal"] . "' AND activo = 1)";

            if ($id_detalle == "" && $data["codigo"][$i] != "") {
                $sql = "INSERT INTO orden_estudio (id_estudio, id_paquete, id_orden, precio_neto_estudio, fecha_entrega, fecha_registro, posicion, envio_maquila) 
                SELECT id, " . $id_paquete . ", '" . $data["id_orden"] . "', '" . $precio_neto . "', '" . $data["fecha_entrega"][$i] . "', NOW(), " . ($i + 1) . ",'" . $maquila . "' FROM cat_estudio WHERE alias = '" . $data["codigo"][$i] . "' ";
                $this->conexion->setQuery($sql);
            } else if ($id_detalle != "" && $data["codigo"][$i] != "") {
                $sql = "UPDATE orden_estudio  
                       SET precio_neto_estudio = $precio_neto, fecha_entrega = '" . $data["fecha_entrega"][$i] . "', posicion = " . ($i + 1) . " WHERE id= $id_detalle";
                $this->conexion->setQuery($sql);
                $estudios_actuales[] = "'" . $data["codigo"][$i] . "'";
            }
        }
        //Guardar en bitacora paciente

        $estudios = implode(", ", $data["codigo"]);
        $generales = "Nombre: " . ($data["nombre"] . " " . $data["paterno"] . " " . $data["materno"] ) . "<br>" .
                "Edad: " . ($data["edad"] . " " . ( $data["tipo_edad"] != "Anios" ? $data["tipo_edad"] : "Años" ) ) . "<br>" .
                "Sexo: " . ( $data["sexo"] == "Nino" ? "Niño" : $data["sexo"] ) . "<br>" .
                "Fecha nacimiento: " . $data["fecha_nac"] . "<br>" .
                "Médico: " . $nombre_doctor . "<br>" .
                "Empresa: " . $empresa[0]->nombre . "<br>";

        $sql = "INSERT INTO `bitacora_paciente`(`id_paciente`, `nombre`, `fecha`, `concepto`, `monto`, estudios, generales,
                `id_usuario`, `id_sucursal`, `id_orden`) 
                SELECT paciente.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno), NOW(), 'MODIFICACION DE PACIENTE', " . $total . ", '$estudios',  '$generales', "
                . $_SESSION["id"] . ", " . $data["id_sucursal"] . ", " . $data["id_orden"] . " 
                FROM orden 
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                WHERE orden.id = " . $data["id_orden"];
        $this->conexion->setQuery($sql);
    }

#------------------ Modificación de los datos generales del paciente
       function modificacionPacienteGenerales($data) {
        $anio = date("Y");
        $catalogos = new Catalogos();
        //Modificar el paciente
        $sql = "UPDATE paciente "
                . "SET paterno='" . $data["paterno"] . "', materno='" . $data["materno"] . "', nombre='" . $data["nombre"] . "', fecha_nac='" . $data["fecha_nac"] . "', edad='" . $data["edad"] . "', tipo_edad='" . $data["tipo_edad"] . "', sexo='" . $data["sexo"] . "',direccion='" . $data["direccion"] . "', tel='" . $data["tel"] . "', cpEmail='" . $data["cpEmail"] . "'
                    WHERE id='" . $data["id_paciente"] . "'";
        $this->conexion->setQuery($sql);


        if ($data["id_doctor"] != "NULL") {
            $sql = "SELECT nombre FROM doctor WHERE id = '" . $data["id_doctor"] . "'";
            $doctores = $this->conexion->getQuery($sql);
            $nombre_doctor = "" . $doctores[0]->nombre . "";
        } else {
            $nombre_doctor = "" . $data["medico"] . "";
        }

        //Modificar orden
        $sql = "UPDATE orden "
                . "SET id_doctor = " . $data["id_doctor"] . ", direccion='" . $data["direccion"] . "', telefono='" . $data["tel"] . "', nombre_doctor='" . $nombre_doctor . "' "
                . "WHERE id='" . $data["id_orden"] . "'";
        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "MODIFICACION DE ORDEN: " . str_replace("'", "", $sql),
            "tabla" => "orden",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos->logActivity($datos);

        //Guardar en bitacora paciente
        $total="";
        $estudios="";
        $generales = "Nombre: " . ($data["nombre"] . " " . $data["paterno"] . " " . $data["materno"] ) . "<br>" .
                "Edad: " . ($data["edad"] . " " . ( $data["tipo_edad"] != "Anios" ? $data["tipo_edad"] : "Años" ) ) . "<br>" .
                "Sexo: " . ( $data["sexo"] == "Nino" ? "Niño" : $data["sexo"] ) . "<br>" .
                "Fecha nacimiento: " . $data["fecha_nac"] . "<br>" .
                "Médico: " . $nombre_doctor . "<br>" .
                "Empresa: " . $empresa[0]->nombre . "<br>";

        $sql = "INSERT INTO `bitacora_paciente`(`id_paciente`, `nombre`, `fecha`, `concepto`, `monto`, estudios, generales,`id_usuario`, `id_sucursal`, `id_orden`) 
                SELECT paciente.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno), NOW(), 'MODIFICACION DE PACIENTE Generales', 0, '$estudios',  '$generales', "
                . $_SESSION["id"] . ", " . $data["id_sucursal"] . ", " . $data["id_orden"] . " 
                FROM orden 
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                WHERE orden.id = " . $data["id_orden"];
        $this->conexion->setQuery($sql);
    }

    function getSucursal($id) {

        $sql = "SELECT *
        FROM sucursal
        WHERE id = '$id'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getBitacoraPaciente($id_orden, $id_sucursal) {

        $sql = "SELECT bitacora_paciente.nombre, bitacora_paciente.concepto, bitacora_paciente.monto, DATE_FORMAT(bitacora_paciente.fecha, '%d/%m/%Y %H:%i') AS fecha, usuario.usuario,
        bitacora_paciente.estudios, bitacora_paciente.generales
        FROM bitacora_paciente
        INNER JOIN usuario ON (bitacora_paciente.id_usuario = usuario.id) 
        WHERE bitacora_paciente.id_orden = '$id_orden' AND  bitacora_paciente.id_sucursal = '$id_sucursal'
        ORDER BY bitacora_paciente.id";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addTarjeta($tarjeta, $id_sucursal) {
        $data = $this->getTarjeta($tarjeta, $id_sucursal);

        if (count($data) == 0) {
            $sql = "INSERT INTO tarjeta (saldo, numero, id_cliente) SELECT 0, '$tarjeta', id_cliente FROM sucursal WHERE id = $id_sucursal";
            $this->conexion->setQuery($sql);
            $data = $this->getTarjeta($tarjeta, $id_sucursal);
        }
        return $data;
    }

    function getTarjeta($tarjeta, $id_sucursal) {
        $sql = "SELECT * FROM tarjeta WHERE id_cliente = (SELECT id_cliente FROM sucursal WHERE id = $id_sucursal ) AND numero = '$tarjeta'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getToken($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        //$codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[rand(0, $max - 1)];
        }

        return $token;
        //return 10;
    }

    function cancelarOrden($id_orden) {


        $sql = "UPDATE orden "
                . "SET cancelado = 1 "
                . "WHERE id='$id_orden'";
        $this->conexion->setQuery($sql);

        $sql = "INSERT INTO `bitacora_paciente`(`id_paciente`, `nombre`, `fecha`, `concepto`, `monto`, estudios, generales,
                `id_usuario`, `id_sucursal`, `id_orden`) 
                SELECT paciente.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno), NOW(), 'PACIENTE CANCELADO', 0, '', '', "
                . $_SESSION["id"] . ", orden.id_sucursal , orden.id  
                FROM orden 
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                WHERE orden.id = " . $id_orden;
        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "PACIENTE CANCELADO: " . str_replace("'", "", $sql),
            "tabla" => "orden",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos = new Catalogos();
        $catalogos->logActivity($datos);
    }

    function activarOrden($id_orden) {


        $sql = "UPDATE orden "
                . "SET cancelado = 0 "
                . "WHERE id='$id_orden'";
        $this->conexion->setQuery($sql);

        $sql = "INSERT INTO `bitacora_paciente`(`id_paciente`, `nombre`, `fecha`, `concepto`, `monto`, estudios, generales,
                `id_usuario`, `id_sucursal`, `id_orden`) 
                SELECT paciente.id, CONCAT(paciente.nombre,' ', paciente.paterno, ' ', paciente.materno), NOW(), 'PACIENTE ACTIVADO', 0, '', '', "
                . $_SESSION["id"] . ", orden.id_sucursal , orden.id  
                FROM orden 
                INNER JOIN paciente ON (paciente.id = orden.id_paciente)
                WHERE orden.id = " . $id_orden;
        $this->conexion->setQuery($sql);

        //log_activity
        $datos = array(
            "observaciones" => "PACIENTE ACTIADO: " . str_replace("'", "", $sql),
            "tabla" => "orden",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos = new Catalogos();
        $catalogos->logActivity($datos);
    }

    function buscarPacienteXNombreM($paterno,$materno,$nombre,$fecha_nacimiento,$id_sucursal){
        $sql="SELECT  p.expediente, p.id,count(o.consecutivo) as numero_ordenes,p.id,CONCAT(p.paterno,' ',p.materno, ' ', p.nombre) as nombre, p.fecha_registro,p.fecha_nac from paciente p inner join orden o on o.id_paciente=p.id where p.paterno like '%".$paterno."%' and p.materno like '%".$materno."%' and p.nombre like '%".$nombre."%' and p.fecha_nac like '%".$fecha_nacimiento."%' and  p.id_sucursal=".$id_sucursal."  GROUP BY p.expediente  order by count(o.consecutivo) DESC LIMIT 10";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
