<?php 

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/catalogos/Empresas.php');

class AdministracionModel {
	private $conexion;

    function __construct() {
        //Validación de session
        $this->conexion = new Conexion();
    }

#Informacion de pago de una orden para el reporte global
    public function informacionPagosOrden($id_orden){
        $sql="SELECT sum(IF(fp.descripcion = 'EFECTIVO', p.pago, 0)) As efectivo, 
        GROUP_CONCAT(IF(fp.descripcion = 'EFECTIVO', p.fecha_pago, NULL)) As fecha_efectivo, 
        sum(IF(fp.descripcion like 'TARJETA%', p.pago, 0)) As tarjeta, 
        GROUP_CONCAT(IF(fp.descripcion like 'TARJETA%', p.fecha_pago, NULL)) As fecha_tarjeta, 
        sum(IF((fp.descripcion not like 'TARJETA%' and fp.descripcion != 'EFECTIVO'), p.pago, 0)) As otro, 
        GROUP_CONCAT(IF((fp.descripcion not like 'TARJETA%' and fp.descripcion != 'EFECTIVO'), p.fecha_pago, NULL)) As fecha_otro 
        from pago p 
        inner join forma_pago fp on p.id_forma_pago=fp.id 
        where p.id_orden=".$id_orden;
        $data = $this->conexion->getQuery($sql);
        return $data[0];
    }

#Guarda el pago de empresa
    public function guardaPagoECM(){

        if($_REQUEST['uso_saldo']=="on"){
            $_REQUEST['uso_saldo']=1;
            $saldo_final=$_REQUEST['saldo_final'];
        }
        else{
            $_REQUEST['uso_saldo']=0;
            $diferencia=floatval($_REQUEST['monto_pago'])-floatval($_REQUEST['total_pagado']);
            $saldo_final=floatval($_REQUEST['saldo_anterior'])+$diferencia;
        }

        $sql = 'INSERT INTO pagos_empresas_credito(id_empresa,id_usuario,monto_pago,tipo_pago,total_pagado,uso_saldo,saldo_final,numero_referencia,observaciones,id_sucursal,lista_ordenes,lista_importes,lista_descuentos,fecha_pago) 
        VALUES(
            '.$_REQUEST['empresa'].', 
            '.$_SESSION["id"].', 
            "'.$_REQUEST['monto_pago'].'", 
            "'.$_REQUEST['tipo_pago'].'", 
            '.$_REQUEST['total_pagado'].', 
            '.$_REQUEST['uso_saldo'].', 
            '.$saldo_final.', 
            "'.$_REQUEST['numero_referencia'].'",
            "'.$_REQUEST['observaciones'].'",
            '.$_SESSION['id_sucursal'].',
            "'.$_REQUEST['lista_ordenes'].'",
            "'.$_REQUEST['lista_importes'].'",
            "'.$_REQUEST['lista_descuentos'].'",
            "'.$_REQUEST['fecha_pago'].'"
        )';
        $this->conexion->setQuery($sql);
        $sql = "SELECT MAX(id_pago_ec) as ultimo_id from pagos_empresas_credito";
        $data = $this->conexion->getQuery($sql);
        $id_pago=$data[0];
        $arrayOrdenes=explode(",",$_REQUEST['lista_ordenes'] );
        echo '<pre>'; print_r($arrayOrdenes); echo '</pre>';
        for($i=0;$i<count($arrayOrdenes);$i++){
            $sql="UPDATE orden SET pago_credito=".$id_pago->ultimo_id.", saldo_deudor=0 where id=".$arrayOrdenes[$i];
            echo '<pre>'; print_r($sql); echo '</pre>';
            $this->conexion->setQuery($sql);
        }
        $sql="UPDATE empresa SET saldo_credito_pagos=".$saldo_final." where id=".$_REQUEST['empresa'];
        $this->conexion->setQuery($sql);
        return "success";
        
    }

#Obtiene la lista de empresas de la sucursal
    public function listaEmpresasM($id_sucursal){
    	$sql = "SELECT * FROM empresa WHERE activo=1 and credito=1 and id_sucursal=".$_SESSION['id_sucursal']."  ORDER BY nombre";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

#Obtener el crédito de una empresa
    public function obtenerCreditoEmpresaM(){
        $sql = "SELECT saldo_credito_pagos from empresa where id=".$_REQUEST['id_empresa'];
        $data = $this->conexion->getQuery($sql);
        return $data[0];
    }


#Obtiene la lista de ordenes que no han sido pagadas en Aplicación de Pagos Empresa
    public function listaOrdenesEmpresaM(){
    	$sql = "SELECT o.id as id_orden,o.consecutivo,CONCAT(p.nombre,' ',p.paterno,' ',p.materno) as nombre_paciente,o.importe,
            o.fecha_registro,ece.tipo_descuento,
            sum(if(ece.tipo_descuento='porcentaje',round(oe.precio_neto_estudio*ece.porcentaje_descuento/100),if(ece.porcentaje_descuento IS NULL,0,ece.porcentaje_descuento))) as total_descuento, 
            sum(if(ece.tipo_descuento='porcentaje',round(oe.precio_neto_estudio*(100-ece.porcentaje_descuento)/100),oe.precio_neto_estudio-if(ece.porcentaje_descuento IS NULL,0,ece.porcentaje_descuento))) as total   
            from orden o inner join orden_estudio oe on o.id=oe.id_orden
            inner join paciente p on o.id_paciente=p.id 
            inner join estudio e on e.id_cat_estudio=oe.id_estudio and e.activo=1 and e.id_sucursal=".$_SESSION['id_sucursal']." 
            left join empresa_clase_estudio ece on ece.id_empresa=o.id_empresa and ece.id_clase=e.clase
             where  o.id_sucursal=".$_SESSION['id_sucursal']." and o.id_Empresa=".$_REQUEST['id_empresa']." and o.credito=1 and o.cancelado=0 and (o.pago_credito=0 or o.pago_credito IS NULL) and o.fecha_registro BETWEEN  '".$_REQUEST['fecha_inicial']."' and '".$_REQUEST['fecha_final']." 23:59:59' 
             group by o.id";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    #Obtiene la lista estudios de una orden seleccinada en Aplicación de Pagos Empresa
    public function listaEstudiosIOrdenM($orden){
        $sql = "SELECT o.id as id_orden,oe.precio_neto_estudio,ece.porcentaje_descuento,ce.nombre_estudio,o.importe, o.fecha_registro,ece.tipo_descuento, 
            if(ece.tipo_descuento='porcentaje',round(oe.precio_neto_estudio*ece.porcentaje_descuento/100),if(ece.porcentaje_descuento IS NULL,0,ece.porcentaje_descuento)) as total_descuento, 
            if(ece.tipo_descuento='porcentaje',round(oe.precio_neto_estudio*(100-ece.porcentaje_descuento)/100),oe.precio_neto_estudio-if(ece.porcentaje_descuento IS NULL,0,ece.porcentaje_descuento)) as total from orden o 
            inner join orden_estudio oe on o.id=oe.id_orden 
            inner join paciente p on o.id_paciente=p.id 
            inner join estudio e on e.id_cat_estudio=oe.id_estudio and e.activo=1 and e.id_sucursal=".$_SESSION['id_sucursal']." 
            inner join cat_estudio ce on ce.id=e.id_cat_estudio 
            left join empresa_clase_estudio ece on ece.id_empresa=o.id_empresa and ece.id_clase=e.clase 
            where o.id_sucursal=".$_SESSION['id_sucursal']." and o.id=".$orden;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

#Obtiene la lista estudios de una orden seleccinada en Aplicación de Pagos Empresa
    public function listaEstudiosOrdenM(){
        $sql = "SELECT o.id as id_orden,oe.precio_neto_estudio,ece.porcentaje_descuento,ce.nombre_estudio,o.importe, o.fecha_registro,ece.tipo_descuento, 
            if(ece.tipo_descuento='porcentaje',round(oe.precio_neto_estudio*ece.porcentaje_descuento/100),(ece.porcentaje_descuento)) as total_descuento, 
            if(ece.tipo_descuento='porcentaje',round(oe.precio_neto_estudio*(100-ece.porcentaje_descuento)/100),oe.precio_neto_estudio-ece.porcentaje_descuento) as total from orden o 
            inner join orden_estudio oe on o.id=oe.id_orden 
            inner join paciente p on o.id_paciente=p.id 
            inner join estudio e on e.id_cat_estudio=oe.id_estudio and e.activo=1 and e.id_sucursal=".$_SESSION['id_sucursal']." 
            inner join cat_estudio ce on ce.id=e.id_cat_estudio 
            left join empresa_clase_estudio ece on ece.id_empresa=o.id_empresa and ece.id_clase=e.clase 
            where o.id_sucursal=".$_SESSION['id_sucursal']." and o.id=".$_REQUEST['id_orden'];
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

     function close() {
        $this->conexion->close();
    }

# Lista de ordenes de todas las empresas:
    public function listaOrdenesEmpresasM($fecha_inicio,$fecha_final){
        $fecha_fin=$fecha_final." 23:59:59";
        $sql="SELECT em.id,em.nombre,count(Distinct o.id) as numero_ordenes,round(sum(oe.precio_neto_estudio)) as importe_ordenes, 
                sum(if(ece.tipo_descuento='porcentaje',round(oe.precio_neto_estudio*ece.porcentaje_descuento/100),(ece.porcentaje_descuento))) as total_descuento, 
                sum(if(ece.tipo_descuento='porcentaje',round(oe.precio_neto_estudio*(100-ece.porcentaje_descuento)/100),oe.precio_neto_estudio-ece.porcentaje_descuento)) as total 
                from orden o inner join orden_estudio oe on o.id=oe.id_orden inner join paciente p on o.id_paciente=p.id inner join empresa em on em.id=o.id_empresa and em.credito=1 inner join estudio e on e.id_cat_estudio=oe.id_estudio and e.activo=1 and e.id_sucursal=".$_SESSION['id_sucursal']." left join empresa_clase_estudio ece on ece.id_empresa=o.id_empresa and ece.id_clase=e.clase where o.id_sucursal=".$_SESSION['id_sucursal']." and o.credito=1 and o.pago_credito=0 and o.fecha_registro BETWEEN '".$fecha_inicio."' and '".$fecha_fin."' group by em.id ORDER BY `o`.`id` ASC";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

# Lista de pagos de empresa
    public function ListaPagosCompletaM(){
        $fecha_inicio=$_REQUEST['fecha_inicio']." 00:00:00";
        $fecha_fin=$_REQUEST['fecha_fin']." 23:59:59";

        $sql="SELECT e.nombre as nombre_empresa, fc.descripcion as forma_pago, pc.* FROM pagos_empresas_credito pc
            INNER JOIN empresa e  on pc.id_empresa= e.id
            INNER JOIN forma_pago fc  on pc.tipo_pago = fc.id 
            WHERE pc.fecha_pago BETWEEN '".$fecha_inicio."' and '".$fecha_fin."' and e.id=".$_REQUEST['empresa']." ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    public function consecutivosPago($lista){
        $arreglo=explode(",", $lista); 
        $respuesta=[];
        for($i=0;$i<count($arreglo)-1;$i++){
            $sql="SELECT consecutivo from orden where id=".$arreglo[$i];
            $data=$this->conexion->getQuery($sql);
            array_push($respuesta, $data[0]->consecutivo);
        }
        return $respuesta;
    }
}
 ?>