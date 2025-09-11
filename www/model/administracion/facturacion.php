<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');

class FacturacionModel {
	private $conexion;

    function __construct() {
        //Validación de session
        $this->conexion = new Conexion(); 
    }

    public function listaRegimenFiscalM(){
        $sql="SELECT * from regimen";
		$data = $this->conexion->getQuery($sql);
        return $data;
    } 

    public function listaOrdenesIndividualesM($mes_busqueda,$anio_busqueda){
    	$sql="SELECT p.id as id_paciente,em.nombre as empresa_nombre,o.id_empresa,o.fecha_registro, o.consecutivo as orden_c,o.id as orden_id,concat(p.paterno,' ',p.materno,' ',p.nombre) as nombre,o.importe FROM orden o INNER JOIN paciente p on o.id_paciente=p.id left join empresa em on em.id=o.id_empresa 
		   where (o.id_sucursal=".$_SESSION['id_sucursal']." and YEAR(o.fecha_registro)=".$anio_busqueda." and MONTH( o.fecha_registro) = ".$mes_busqueda.") and o.id_factura IS NULL  order by o.fecha_registro desc";

		$data = $this->conexion->getQuery($sql);
        return $data;
    }

    public function guardarDatosFacturaPacienteM(){
    	$sql = 'INSERT INTO datos_fiscales_paciente (id_paciente, rfc, nombre_fiscal, regimen_fiscal, direccion_fiscal, codigo_postal, correo, condiciones_pago, metodo_pago, forma_pago, uso_cfdi,cfdi_relacionado, observaciones) 
    	VALUES (
    	'.$_REQUEST['m_id_paciente'].',
    	"'.strtoupper($_REQUEST['m_rfc']).'",
    	"'.strtoupper($_REQUEST['m_nombre_fiscal']).'",
        "'.$_REQUEST['m_regimen'].'",
    	"'.$_REQUEST['m_direccion'].'",
    	"'.$_REQUEST['m_codigo_postal'].'",
    	"'.$_REQUEST['m_correo'].'",
    	'.$_REQUEST['m_condiciones_pago'].',
    	"'.$_REQUEST['m_metodo_pago'].'",
    	'.$_REQUEST['m_forma_pago'].',
    	'.$_REQUEST['m_usocfdi'].',
        "'.$_REQUEST['m_cfdir'].'",
    	"'.$_REQUEST['m_observaciones'].'"
    	)';
        $this->conexion->setQuery($sql);
        return "success";
    }

    public function actualizaDatosFacturaPacienteM(){
        $sql = 'UPDATE datos_fiscales_paciente SET 
        rfc="'.strtoupper($_REQUEST['m_rfc']).'", 
        nombre_fiscal="'.strtoupper($_REQUEST['m_nombre_fiscal']).'",
        regimen_fiscal="'.$_REQUEST['m_regimen'].'",
        direccion_fiscal="'.$_REQUEST['m_direccion'].'", 
        codigo_postal="'.$_REQUEST['m_codigo_postal'].'", 
        correo="'.$_REQUEST['m_correo'].'", 
        condiciones_pago='.$_REQUEST['m_condiciones_pago'].', 
        metodo_pago="'.$_REQUEST['m_metodo_pago'].'", 
        forma_pago='.$_REQUEST['m_forma_pago'].', 
        uso_cfdi='.$_REQUEST['m_usocfdi'].',
        cfdi_relacionado="'.$_REQUEST['m_cfdir'].'", 
        observaciones="'.$_REQUEST['m_observaciones'].'"
        WHERE id_paciente='.$_REQUEST['m_id_paciente'].' ';
        

        $this->conexion->setQuery($sql);
        return "success"; 
    }

    public function guardarDatosFacturaMasivaM(){
        $usar_descripcion=0;
        if(isset($_REQUEST['fm_udescripcion']) && $_REQUEST['fm_udescripcion']=="on"){
            $usar_descripcion=1;
        }
        $sql = 'INSERT INTO datos_fiscales_forma_masiva (rfc, nombre_fiscal,regimen_fiscal, direccion_fiscal, codigo_postal, correo, condiciones_pago, metodo_pago, forma_pago, uso_cfdi,cfdi_relacionado, observaciones,usar_descripcion,descripcion) 
        VALUES (
        "'.strtoupper($_REQUEST['fm_rfc']).'",
        "'.strtoupper($_REQUEST['fm_nombre_fiscal']).'",
        "'.$_REQUEST['fm_regimen'].'",
        "'.$_REQUEST['fm_direccion'].'",
        '.$_REQUEST['fm_codigo_postal'].',
        "'.$_REQUEST['fm_correo'].'",
        '.$_REQUEST['fm_condiciones_pago'].',
        "'.$_REQUEST['fm_metodo_pago'].'",
        '.$_REQUEST['fm_forma_pago'].',
        '.$_REQUEST['fm_usocfdi'].',
        "'.$_REQUEST['fm_cfdir'].'",
        "'.$_REQUEST['fm_observaciones'].'",
        "'.$usar_descripcion.'",
        "'.$_REQUEST['fm_ddescripcion'].'"
        )';
        $this->conexion->setQuery($sql);
        return "success";
    }


    public function actualizaDatosFacturaMasivaM(){
        $usar_descripcion=0;
        if(isset($_REQUEST['fm_udescripcion']) && $_REQUEST['fm_udescripcion']=="on"){
            $usar_descripcion=1;
        }
        $sql = 'UPDATE datos_fiscales_forma_masiva SET 
        rfc="'.strtoupper($_REQUEST['fm_rfc']).'", 
        nombre_fiscal="'.strtoupper($_REQUEST['fm_nombre_fiscal']).'",
        regimen_fiscal="'.$_REQUEST['fm_regimen'].'",
        direccion_fiscal="'.$_REQUEST['fm_direccion'].'", 
        codigo_postal='.$_REQUEST['fm_codigo_postal'].', 
        correo="'.$_REQUEST['fm_correo'].'", 
        condiciones_pago='.$_REQUEST['fm_condiciones_pago'].', 
        metodo_pago="'.$_REQUEST['fm_metodo_pago'].'", 
        forma_pago='.$_REQUEST['fm_forma_pago'].', 
        uso_cfdi='.$_REQUEST['fm_usocfdi'].', 
        cfdi_relacionado="'.$_REQUEST['fm_cfdir'].'", 
        observaciones="'.$_REQUEST['fm_observaciones'].'",
        usar_descripcion="'.$usar_descripcion.'",
        descripcion="'.$_REQUEST['fm_ddescripcion'].'"
        ';
        
        $this->conexion->setQuery($sql);
        return "success"; 
    }

    public function datosFiscalesPacienteM($id_paciente){
    	$sql="SELECT fp.clave as forma_pagocfdi,uc.clave as uso_cfdi3,p.* from datos_fiscales_paciente p left join formas_pago fp on fp.id=p.forma_pago left join usocfdi uc on uc.id=p.uso_cfdi where id_paciente=".$id_paciente;
		$data = $this->conexion->getQuery($sql);
        return $data;
    }

    public function datosFiscalesEmpresaM($id_empresa){
        $sql="SELECT fp.clave as forma_pagocfdi,uc.clave as uso_cfdi3,p.* from empresa p inner join formas_pago fp on fp.id=p.forma_pago inner join usocfdi uc on uc.id=p.uso_cfdi where p.id=".$id_empresa;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }


    public function datosFacturaMasivaM(){
        $sql="SELECT fp.clave as forma_pagocfdi,uc.clave as uso_cfdi3,p.* from datos_fiscales_forma_masiva p inner join formas_pago fp on fp.id=p.forma_pago inner join usocfdi uc on uc.id=p.uso_cfdi";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    public function datosFiscalesSucursalM(){
        $sql="select dfc.regimen,dfc.nombre_fiscal,dfc.rfc,dfc.cp,dfc.certificado,dfc.llave, dfc.contrasena, dfc.serie, s.iva_incluido, s.iva_frontera 
            from datos_fiscales_cliente dfc inner join sucursal s on s.id=dfc.id_sucursal 
            where  id_sucursal=".$_SESSION['id_sucursal']." and registrado=1";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    public function datosFolioInternoFacturaM(){
         $sql="select folio_consecutivo from cfdi_pacientes where id_sucursal=".$_SESSION['id_sucursal']." order by folio_consecutivo desc limit 1";
         $data = $this->conexion->getQuery($sql);
         return $data;
    }

    public function itemsOrdenCFDIM($miva,$ivaIncluido){

        if($ivaIncluido==1){
            $sql="SELECT ce.sat AS productCode,ce.no_estudio AS identNumber,ce.nombre_estudio AS description,FORMAT(oe.precio_neto_estudio/1".$miva.",2) AS unitPrice, FORMAT((oe.precio_neto_estudio/1".$miva.")*0".$miva.",2) AS total, 
            FORMAT(oe.precio_neto_estudio/1".$miva.",2) AS subtotal, 
            FORMAT(oe.precio_neto_estudio/1".$miva.",2) AS base,
            '0".$miva."' AS rate, 
            FORMAT(oe.precio_neto_estudio,2) as totalM 
            FROM orden o INNER JOIN orden_estudio oe ON oe.id_orden=o.id 
            INNER JOIN cat_estudio ce ON ce.id=oe.id_estudio 
            WHERE o.id_sucursal=".$_SESSION['id_sucursal']." AND o.id=".$_REQUEST['id_orden']; 
        }
        else{
             $sql="SELECT ce.sat AS productCode,ce.no_estudio AS identNumber,ce.nombre_estudio AS description,FORMAT(oe.precio_neto_estudio,2) AS unitPrice, 
            FORMAT((oe.precio_neto_estudio)*0".$miva.",2) AS total, 
            FORMAT(oe.precio_neto_estudio,2) AS subtotal, 
            FORMAT(oe.precio_neto_estudio,2) AS base,
            '0".$miva."' AS rate, 
            FORMAT(oe.precio_neto_estudio*1".$miva.",2) as totalM 
            FROM orden o INNER JOIN orden_estudio oe ON oe.id_orden=o.id 
            INNER JOIN cat_estudio ce ON ce.id=oe.id_estudio 
            WHERE o.id_sucursal=".$_SESSION['id_sucursal']." AND o.id=".$_REQUEST['id_orden']; 
        }
        
        $data = $this->conexion->getQuery($sql);
         return $data;
    }

    public function obtenerDatosListaOrdenesM($miva,$ivaIncluido){ 
        $consecutivos=implode(",", $_REQUEST['lista_ordenes']);
        if($ivaIncluido==1){
            $sql="SELECT  ce.nombre_estudio, count(o.id) as numero_estudios,
            (SUM(oe.precio_neto_estudio) ) as precio_estudio,
            ((SUM(oe.precio_neto_estudio) /1".$miva.")*0".$miva.") AS total,
            ((SUM(oe.precio_neto_estudio)/count(o.id)) /1".$miva.") AS precioU, 
            (SUM(oe.precio_neto_estudio)/1".$miva.") AS subtotal,
            (SUM(oe.precio_neto_estudio)/1".$miva.") AS base,
            (SUM(oe.precio_neto_estudio)) AS totalM,
            '0".$miva."' AS rate, 
            o.consecutivo, o.id as orden_id,ce.sat AS productCode,ce.no_estudio AS identNumber from orden o 
            inner join orden_estudio oe on o.id=oe.id_orden
            inner join cat_estudio ce on ce.id=oe.id_estudio
            where o.id_sucursal=".$_SESSION['id_sucursal']." and o.consecutivo in (".$consecutivos.")
            GROUP by ce.no_estudio";
        }
        else{
            $sql="SELECT  ce.nombre_estudio, count(o.id) as numero_estudios,
            (SUM(oe.precio_neto_estudio)*1".$miva." ) as precio_estudio,
            FORMAT((SUM(oe.precio_neto_estudio)*0".$miva."),2) AS total,
            ((SUM(oe.precio_neto_estudio)/count(o.id)) ) AS precioU, 
            (SUM(oe.precio_neto_estudio)) AS subtotal,
            (SUM(oe.precio_neto_estudio)) AS base,
            FORMAT((SUM(oe.precio_neto_estudio)*1".$miva."),2) AS totalM,
            '0".$miva."' AS rate, 
            o.consecutivo, o.id as orden_id,ce.sat AS productCode,ce.no_estudio AS identNumber from orden o 
            inner join orden_estudio oe on o.id=oe.id_orden
            inner join cat_estudio ce on ce.id=oe.id_estudio
            where o.id_sucursal=".$_SESSION['id_sucursal']." and o.consecutivo in (".$consecutivos.")
            GROUP by ce.no_estudio";
        }
        

        $data = $this->conexion->getQuery($sql);
         return $data;
    }



    public function guardaCFDIM(){
        $id=uniqid();
        //---------------- Obtener los datos físcales que no se guardan en el CFDI (dirección, etc.)
        if($_REQUEST['tipo']=='individual'){
            $listaOrdenes=$_REQUEST['lista_ordenes'];
           $update="UPDATE orden SET id_factura='".$id."'  WHERE consecutivo=".$_REQUEST['lista_ordenes']; 
           $sqlDFP="SELECT dfp.* from datos_fiscales_paciente dfp inner join paciente p on p.id=dfp.id_paciente inner join orden o on o.id_paciente=p.id where o.consecutivo=".$_REQUEST['lista_ordenes']." order by o.fecha_registro DESC";
           $datosFiscalesP = $this->conexion->getQuery($sqlDFP);
           $direccion_fiscal=$datosFiscalesP[0]->direccion_fiscal.", CP:".$datosFiscalesP[0]->codigo_postal;
           $_REQUEST['id_empresa']='NULL';
           $this->conexion->setQuery($update);
        }
        else if($_REQUEST['tipo']=='masiva' || $_REQUEST['tipo']=='empresa'){
            $listaOrdenes=implode(",", $_REQUEST['lista_ordenes']);
            $arrayOrdenes=explode(",", $listaOrdenes);

            
            if($_REQUEST['tipo']=='masiva'){
                $sqlDFP="SELECT * FROM datos_fiscales_forma_masiva";
                $datosFiscalesP = $this->conexion->getQuery($sqlDFP);
                $direccion_fiscal=$datosFiscalesP[0]->direccion_fiscal.", CP:".$datosFiscalesP[0]->codigo_postal;
                $_REQUEST['id_empresa']='NULL';
                for($i=0;$i<count($arrayOrdenes);$i++){
                    $update="UPDATE orden SET id_factura='".$id."'  WHERE id_sucursal=".$_SESSION['id_sucursal']." and consecutivo=".$arrayOrdenes[$i];
                     $this->conexion->setQuery($update);
                }
            }
            elseif($_REQUEST['tipo']=='empresa'){
                $sqlDFP="SELECT * FROM empresa where id=".$_REQUEST['id_empresa'];
                $datosFiscalesP = $this->conexion->getQuery($sqlDFP);
                $direccion_fiscal=$datosFiscalesP[0]->direccion_fiscal.", CP:".$datosFiscalesP[0]->cp;
                for($i=0;$i<count($arrayOrdenes);$i++){
                    $update="UPDATE orden SET id_factura='".$id."'  WHERE id_sucursal=".$_SESSION['id_sucursal']." and consecutivo=".$arrayOrdenes[$i];
                     $this->conexion->setQuery($update);
                }
            }
            
        }

        $sql="insert into cfdi_pacientes (id,lista_ordenes,id_sucursal,folio_consecutivo,rfc,direccion_fiscal,fecha_certificacion,id_cfdi,blob_cfdi,empresa,observaciones)
             values ('".$id."','".$listaOrdenes."','".$_SESSION['id_sucursal']."','".$_REQUEST['folio']."','".strtoupper($_REQUEST['rfc'])."','".$direccion_fiscal."','".$_REQUEST['fecha_emision']."','".$_REQUEST['id_cfdi']."','".$_REQUEST['blob']."',".$_REQUEST['id_empresa'].",'".$_REQUEST['observaciones']."')";

        $this->conexion->setQuery($sql);
        
        
       
        return "success"; 
    }



    public function listaFacturasTimbradasM($folio,$rfc,$empresa,$fecha){
        $_fecha=$_folio=$_rfc=$_empresa="";
        if($fecha!=null)
            $_fecha=" AND cf.fecha_certificacion BETWEEN '".$fecha."-01' and '".$fecha."-31 23:59:59' ";
        if($empresa!=null)
            $_empresa=" AND cf.empresa=".$empresa." ";
        if($rfc!=null)
            $_rfc=" AND cf.rfc='".$rfc."' ";
        if($folio!=null)
            $_folio=" AND cf.folio_consecutivo=".$folio." ";
        $sql="SELECT cf.*, em.nombre as nombre_empresa FROM cfdi_pacientes cf left join empresa em on em.id=cf.empresa where cf.cancelado=0 and cf.lista_ordenes IS NOT NULL and cf.id_sucursal=".$_SESSION['id_sucursal'].$_fecha.$_rfc.$_folio.$_empresa;
  
        return $this->conexion->getQuery($sql);
    }

//-----------------Informacion para reporte de facturas
    public function informacionFacturasTimbradasM($fecha_inicial,$fecha_final,$descripcion,$busqueda){
        $fecha_final=$fecha_final." 23:59:59 ";
 
    if($descripcion=="Emitidas"){
        $sql="SELECT cf.id_cfdi,cf.blob_cfdi FROM cfdi_pacientes cf left join empresa em on em.id=cf.empresa where cf.cancelado=0 and cf.lista_ordenes IS NOT NULL and cf.id_sucursal=".$_SESSION['id_sucursal']." and cf.fecha_certificacion BETWEEN '".$fecha_inicial."' and '".$fecha_final."'";
    }
    elseif($descripcion=="Publico"){
        $sql="SELECT cf.id_cfdi,cf.blob_cfdi FROM cfdi_pacientes cf left join empresa em on em.id=cf.empresa where cf.cancelado=0 and cf.lista_ordenes IS NOT NULL and cf.id_sucursal=".$_SESSION['id_sucursal']." and cf.fecha_certificacion BETWEEN '".$fecha_inicial."' and '".$fecha_final."' and cf.rfc='XAXX010101000'";
    }
    elseif($descripcion=="Canceladas"){
        $sql="SELECT cf.id_cfdi,cf.blob_cfdi FROM cfdi_pacientes cf left join empresa em on em.id=cf.empresa where cf.cancelado=0 and cf.lista_ordenes IS NOT NULL and cf.id_sucursal=".$_SESSION['id_sucursal']." and cf.fecha_certificacion BETWEEN '".$fecha_inicial."' and '".$fecha_final."' and cf.cancelado=1";
    }
    elseif($descripcion=="Empresa"){
        $sql="SELECT cf.id_cfdi,cf.blob_cfdi FROM cfdi_pacientes cf left join empresa em on em.id=cf.empresa where cf.cancelado=0 and cf.lista_ordenes IS NOT NULL and cf.id_sucursal=".$_SESSION['id_sucursal']." and cf.fecha_certificacion BETWEEN '".$fecha_inicial."' and '".$fecha_final."' and cf.empresa IS NOT NULL and cf.empresa>0";
    }
        
  
        return $this->conexion->getQuery($sql);
    }

    public function listaFacturasCanceladasM($fecha){
        $sql="SELECT  cf.*,cc.*,cf.id_cfdi as id_cfdi, em.nombre as nombre_empresa FROM cfdi_pacientes cf left join empresa em on em.id=cf.empresa left join cfdi_cancelaciones cc on cc.id_cfdi=cf.id_cfdi where cf.cancelado=1 and cf.lista_ordenes IS NOT NULL and cf.id_sucursal=".$_SESSION['id_sucursal']." and fecha_certificacion BETWEEN '".$fecha."' and '".$fecha." 23:59:59' ";

         return $this->conexion->getQuery($sql);
    }


    public function datosOrdenM($id_orden){
        $sql="SELECT * FROM orden where id=".$id_orden; 
        return $this->conexion->getQuery($sql);
    }


    public function obtenerListaOrdenesEmpresaM(){
        $sql="SELECT e.id as id_empresaf,e.nombre as empresa, o.id as id_orden,o.consecutivo,o.fecha_registro, 
        CONCAT(p.paterno,' ',p.materno,' ',p.nombre) as paciente,o.importe 
        from orden o inner join paciente p on o.id_paciente=p.id 
        inner join empresa e on e.id=o.id_empresa 
        where o.id_empresa=".$_REQUEST['empresa']." and o.fecha_registro BETWEEN '".$_REQUEST['fecha_inicial']."' and '".$_REQUEST['fecha_final']."' and o.id_factura IS NULL order by o.consecutivo DESC";
        return $this->conexion->getQuery($sql);
    }

    public function obtenerCFDIEmpresaM(){
        $sql="SELECT rfc,nombre_fiscal,email,direccion,cp,condiciones_pago,metodo_pago,forma_pago,uso_cfdi,regimen_fiscal,observaciones,usar_descripcion,descripcion 
        FROM `empresa` WHERE id=".$_REQUEST['id_empresa']."";
        return $this->conexion->getQuery($sql);
    }

    public function actualizaDatosFacturaEmpresaM(){
        $usar_descripcion=0;
        if(isset($_REQUEST['empresa_udescripcion']) && $_REQUEST['empresa_udescripcion']=="on"){
            $usar_descripcion=1;
        }
        $sql = 'UPDATE empresa SET 
        rfc="'.strtoupper($_REQUEST['empresa_rfc']).'", 
        nombre_fiscal="'.$_REQUEST['empresa_nombre_fiscal'].'",
        regimen_fiscal="'.$_REQUEST['empresa_regimen'].'",
        direccion_fiscal="'.$_REQUEST['empresa_direccion'].'", 
        cp='.$_REQUEST['empresa_codigo_postal'].', 
        email="'.$_REQUEST['empresa_correo'].'", 
        condiciones_pago='.$_REQUEST['empresa_condiciones_pago'].', 
        metodo_pago="'.$_REQUEST['empresa_metodo_pago'].'", 
        forma_pago='.$_REQUEST['empresa_forma_pago'].', 
        uso_cfdi='.$_REQUEST['empresa_usocfdi'].', 
        cfdi_relacionado="'.$_REQUEST['empresa_cfdir'].'", 
        observaciones="'.$_REQUEST['empresa_observaciones'].'",
        usar_descripcion="'.$usar_descripcion.'",
        descripcion="'.$_REQUEST['empresa_ddescripcion'].'" WHERE id="'.$_REQUEST['empresa_id'].'"
        ';


        
        $this->conexion->setQuery($sql);
        return "success"; 
    }

    public function ObtenerBlobCFDIM($id_cfdi){
        $sql="SELECT blob_cfdi from cfdi_pacientes WHERE id='".$id_cfdi."'";
        return $this->conexion->getQuery($sql);
    }

    public function ObtenerUsoCFDIM($usoCFDI){
        $sql="SELECT uso from usocfdi WHERE clave='".$usoCFDI."'";
        return $this->conexion->getQuery($sql);
    }

    public function ObtenerFormaPagoM($formaPago){
        $sql="SELECT nombre from formas_pago WHERE clave='".$formaPago."'";
        return $this->conexion->getQuery($sql);
    }

    public function obtenerInformacionSucursal($id_sucursal){
        $sql="SELECT * from sucursal WHERE id='".$id_sucursal."'";
        return $this->conexion->getQuery($sql);
    } 

    public function obtenerDatosCFDIPacientesM($id_cfdi){
        $sql="SELECT * from cfdi_pacientes where id='".$id_cfdi."'";
        return $this->conexion->getQuery($sql);
    }
    

    public function obtenerDatosCFDIPacientesCFDIM($id_cfdi){
        $sql="SELECT * from cfdi_pacientes where id_cfdi='".$id_cfdi."'";
        return $this->conexion->getQuery($sql);
    }

    public function obtenerDatosCFDIPacientesCanceladasM($id_cfdi){
        $sql="SELECT * from cfdi_cancelaciones where id_cfdi='".$id_cfdi."'";
        return $this->conexion->getQuery($sql);
    }

    public function actualizaRegistroCancelacion(){

        $fecha=Date('Y-m-d H:i:s');
        $sql="UPDATE cfdi_cancelaciones SET motivo='".$_REQUEST['motivo']."', 
        uuid_relacionado='".$_REQUEST['uuid_relacionado']."', mensaje='".$_REQUEST['mensaje']."', 
        status='".$_REQUEST['status']."', uuid_relacionado='".$_REQUEST['uuid_relacionado']."', fecha_cancelacion='".$fecha."' WHERE id_cfdi='".$_REQUEST['id_cfdi']."' ";
        $this->conexion->setQuery($sql);
        return "success"; 
    }

    public function insertaRegistroCancelacion(){
        $sql="INSERT INTO cfdi_cancelaciones(id_cfdi,motivo,uuid_relacionado,mensaje,status) VALUES('".$_REQUEST['id_cfdi']."', 
              '".$_REQUEST['motivo']."', '".$_REQUEST['uuid_relacionado']."', '".$_REQUEST['mensaje']."', '".$_REQUEST['status']."')";
              echo $sql;
        $this->conexion->setQuery($sql);
        return "success"; 
    }

    public function precancelarFacturasM($id_cfdi){
        $sql="UPDATE cfdi_pacientes SET cancelado=1 where id='".$id_cfdi."'";
        $this->conexion->setQuery($sql);
        $cfdi=$this::obtenerDatosCFDIPacientesM($id_cfdi);
        $arrayOrdenes=explode(",",$cfdi[0]->lista_ordenes);
        for($i=0;$i<count($arrayOrdenes);$i++){
            $updt="UPDATE orden SET id_factura=null where consecutivo=".$arrayOrdenes[$i]." and id_factura='".$id_cfdi."'";
            $this->conexion->setQuery($updt);
        }
        return "ok"; 
    }

    function close() {
        $this->conexion->close();
    }
}