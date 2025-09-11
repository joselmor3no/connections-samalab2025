<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/administracion/facturacion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/libs/mpdf-5.7-php7-master/mpdf.php');

 include("numeroLetra.php");
 
class Facturacion { 
	 function __construct() { 
    	 if(isset($_REQUEST['opc'])){
            $usuarios = new Usuarios();
            $acceso = $usuarios->validarSession(); 
            if (!$acceso) {
                header("Location: /");
            }

	 		if($_REQUEST['opc'] == 'datos_factura_paciente') {
	            $this->datosFacturaPaciente();
	        }
            else if($_REQUEST['opc'] == 'datos_factura_masiva'){
                $this->datosFacturaMasiva();
            }
            else if($_REQUEST['opc'] == 'guarda_datos_factura_paciente'){
                $this->guardarDatosFacturaPaciente();
            }
            else if($_REQUEST['opc'] == 'guarda_datos_factura_masiva'){
                $this->guardarDatosFacturaMasiva();
            }
            else if($_REQUEST['opc'] == 'datos_cfdi_orden'){
                $this->datos_cfdi_orden();
            }
            else if($_REQUEST['opc'] == 'datos_cfdi_orden_masiva'){
                $this->datos_cfdi_orden_masiva();
            }
            else if($_REQUEST['opc'] == 'datos_cfdi_orden_masiva_empresa'){
                $this->datos_cfdi_orden_masiva_empresa();
            }
            else if($_REQUEST['opc'] == 'guarda_cfdi'){
                    $this->guardaCFDI();
            }
            else if($_REQUEST['opc']=='datos_ordenes_fm'){
                $this->obtenerDatosListaOrdenes();
            }
            else if($_REQUEST['opc']=='lista_ordenes_empresas'){
                $this->obtenerListaOrdenesEmpresa();
            }
            else if($_REQUEST['opc']=='datos_cfdi_empresa'){
                $this->obtenerCFDIEmpresa();
            }
            else if($_REQUEST['opc'] == 'guarda_datos_factura_empresa'){
                $this->guardarDatosFacturaEmpresa();
            }
            else if($_REQUEST['opc']=='obtener_blob_cfdi'){
                $this->ObtenerBlobCFDI();
            }
            else if($_REQUEST['opc']=='crear_pdf_cfdi'){
                $this->crearPdfCFDI();
            }
            else if($_REQUEST['opc']=='envio_correo'){
                $this->enviarCFDICorreo();
            }
            else if($_REQUEST['opc']=='filtrar_timbradas'){
                $this->filtrarCFDITimbrados();
            }
            else if($_REQUEST['opc']=='precancelar_cfdi'){
                $this->precancelarFacturas();
            }
            else if($_REQUEST['opc']=='registra_cancelacion'){
                $this->registraCancelacion();
            }
            else if($_REQUEST['opc']=='obtener_reporte_factura'){
                $this->reporteFacturacion();
            }

	 	}
    }
 

    private function registraCancelacion(){
        $facturacionM=new FacturacionModel();
        $cfdi=$facturacionM->obtenerDatosCFDIPacientesCanceladasM($_REQUEST['id_cfdi_tabla']);
        if(count($cfdi)>0){
            $facturacionM->actualizaRegistroCancelacion();
        }
        else{
            $facturacionM->insertaRegistroCancelacion();
        }
    }

    private function precancelarFacturas(){
        $facturacionM=new FacturacionModel();
        return $facturacionM->precancelarFacturasM($_REQUEST['id_cfdi']);
    }

    private function filtrarCFDITimbrados(){
        $fecha=$_REQUEST['ft_anio'].'-'.$_REQUEST['ft_mes'];
        $empresa=null;
        $rfc=null;
        $folio=null;
        if($_REQUEST['ft_empresa']!=""){
            $empresa=$_REQUEST['ft_empresa']; 
        }
        if($_REQUEST['ft_rfc']!=""){
            $rfc=$_REQUEST['ft_rfc']; 
        }
        if($_REQUEST['ft_folio']!=""){
            $folio=$_REQUEST['ft_folio']; 
        }
        $this::listaFacturasTimbradas($folio,$rfc,$empresa,$fecha); 
    }

    private function enviarCFDICorreo(){
        //-----------------------GENERA LOS DOCUMENTOS
        error_reporting(0);
        $this::crearPdfCFDI();
        $facturacionM=new FacturacionModel();
        $xml=$facturacionM->ObtenerBlobCFDIM($_REQUEST['id_cfdi']);
        $datosSucursal=$facturacionM->obtenerInformacionSucursal($_SESSION["id_sucursal"]);
        $blob_cfdi=base64_decode($xml[0]->blob_cfdi);
        $archivo_xml = new SimpleXMLElement($blob_cfdi);
        $archivoxml='Factura-'.$_REQUEST['id_cfdi'].'.xml';
        $archivopdf='Factura-'.$_REQUEST['id_cfdi'].'.pdf';
        $ruta=$_SERVER["DOCUMENT_ROOT"]."/reportes/facturacion/";
        $archivo_xml->asXml($ruta."/".$archivoxml);

        //--------------------------ENVIÓ DEL CORREO
            $to = $_REQUEST['correo'];
            //remitente del correo
            $from = 'resultados@connectionslab.net';
            $fromName = 'Facturación | '.$datosSucursal[0]->nombre;
            //Asunto del email
            $subject = 'connectionslab | Facturación'; 
            //Ruta del archivo adjunto
            $files[] = $ruta."/".$archivopdf;
            $files[] = $ruta."/".$archivoxml;
            //Contenido del Email
            $message = 'FACTURACIÓN '.$datosSucursal[0]->nombre;
            //Encabezado para información del remitente
            $headers = "De: $fromName"." <".$from.">";
            //Limite Email
            $semi_rand = md5(time()); 
            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
            //Encabezados para archivo adjunto 
            $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
            $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
            $message .= "--{$mime_boundary}\n";

            // preparing attachments
            for($x=0;$x<count($files);$x++){
                $file = fopen($files[$x],"rb");
                $data = fread($file,filesize($files[$x]));
                fclose($file);
                $data = chunk_split(base64_encode($data));
                $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" . 
                "Content-Disposition: attachment;\n" . " filename=\"$files[$x]\"\n" . 
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                $message .= "--{$mime_boundary}\n";
            }
            //Enviar EMail
            $mail = @mail($to, $subject, $message, $headers); 
            //Estado de envío de correo electrónico
            echo $mail?"Correo enviado.":"El envío de correo falló.";
    }

    private function guardarDatosFacturaPaciente(){
        $facturacionM=new FacturacionModel();
        if($_REQUEST['m_actualizar']==0)
            $data=$facturacionM->guardarDatosFacturaPacienteM();
        else
            $data=$facturacionM->actualizaDatosFacturaPacienteM();
        $facturacionM->close();
        header('Location:/facturacion?m='.$_REQUEST['m']);
    }


    private function guardarDatosFacturaMasiva(){ 
        $facturacionM=new FacturacionModel();
        if($_REQUEST['fm_actualizar']==0)
            $data=$facturacionM->guardarDatosFacturaMasivaM();
        else
            $data=$facturacionM->actualizaDatosFacturaMasivaM();
        $facturacionM->close();
        echo 'ok';
    }
 

    private function guardarDatosFacturaEmpresa(){
        $facturacionM=new FacturacionModel();
        $data=$facturacionM->actualizaDatosFacturaEmpresaM();
        $facturacionM->close();
        echo 'ok';
    }

    private function datosFacturaPaciente(){
        $facturacionM=new FacturacionModel();
        $data=$facturacionM->datosFiscalesPacienteM($_REQUEST['id_paciente']);
        $facturacionM->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    private function datosFacturaMasiva(){
        $facturacionM=new FacturacionModel();
        $data=$facturacionM->datosFacturaMasivaM();
        $facturacionM->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

#============= Función para obtener los datos del cfdi de una orden (timbrado individual)========

    private function datos_cfdi_orden(){
        $facturacionM=new FacturacionModel();
        $datos=[];
    //------------receiver
        $receiver=[];
        $relations=[];
        $paciente=$facturacionM->datosFiscalesPacienteM($_REQUEST['id_paciente']);

        if(count($paciente)>0){
            $receiver['Rfc']=$paciente[0]->rfc;
            $receiver['Name']=$paciente[0]->nombre_fiscal;
            $receiver['CfdiUse']=$paciente[0]->uso_cfdi3;
            $datos['Receiver']=$receiver;
            $datos['PaymentConditions']=$paciente[0]->condiciones_pago;
            $datos['PaymentForm']=$paciente[0]->forma_pagocfdi;
            $datos['PaymentMethod']=$paciente[0]->metodo_pago;

            if(strlen($uuids[]['Uuid']=$paciente[0]->cfdi_relacionado)>20){
                $relations['Type']="04";
                $uuids=[];
                $uuids[]['Uuid']=$paciente[0]->cfdi_relacionado;;
                $relations['Cfdis']=$uuids;
                $datos['Relations']=$relations;
            }
            

        }
        else{
            $datos['error']="Error en los datos del receptor";
        }
        
    //------------issuer
        $issuer=[];
        $sucursal=$facturacionM->datosFiscalesSucursalM();
        $ivaFrontera=0;
        $ivaIncluido=0;
        if(count($sucursal)>0){
            $issuer['FiscalRegime']=$sucursal[0]->regimen;
            $issuer['Rfc']=$sucursal[0]->rfc;
            $issuer['Name']=$sucursal[0]->nombre_fiscal;
            $datos['Issuer']=$issuer;
            $datos['ExpeditionPlace']=$sucursal[0]->cp;
            $datos['Serie']=$sucursal[0]->serie;
            $ivaFrontera=$sucursal[0]->iva_frontera;
            $ivaIncluido=$sucursal[0]->iva_incluido;
        }
        else{
            $datos['error']="Error en los datos del emisor";
        }
    //------------generales
        $folioInterno=$facturacionM->datosFolioInternoFacturaM();
        if(count($folioInterno)==0)
            $folio=1;
        else
            $folio=intval($folioInterno[0]->folio_consecutivo)+1;
        $datos['Currency']="MXN";;
        $datos['Folio']=$folio;
        $datos['CfdiType']="I";
        
    //-------------items
        $miva=".16";
        if($ivaFrontera==1){
           $miva=".08";  
        }
        if($ivaIncluido==0)
            $miva=".0";
        $ritems=$facturacionM->itemsOrdenCFDIM($miva);
        $items=[];
        foreach ($ritems AS $row=>$item) {
            $impuestos=[];
            $impuestos['Total']=($item->total);
            $impuestos['Name']="IVA";
            $impuestos['Base']=($item->base);
            $impuestos['Rate']=($item->rate);
            $impuestos['IsRetention']=false;
            $items[$row]["productCode"]=$item->productCode;
            $items[$row]["identNumber"]=$item->identNumber;
            $items[$row]["description"]=$item->description;
            $items[$row]["Unit"]="NO APLICA";
            $items[$row]["UnitCode"]="E48";
            $items[$row]["UnitPrice"]=($item->unitPrice);
            $items[$row]["Quantity"]=1;
            $items[$row]["Subtotal"]=($item->subtotal);
            $items[$row]["Discount"]=0.0;
            $items[$row]["Taxes"][]=$impuestos;
            $items[$row]["Total"]=$item->totalM;
        }
        $datos["Items"]=$items;
        $facturacionM->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }
#========== Termina Función para obtener los datos del cfdi de una orden (timbrado individual)===



#============= Función para obtener los datos del cfdi de una orden (timbrado masivo)========
    private function datos_cfdi_orden_masiva(){
        $facturacionM=new FacturacionModel();
        $datos=[];
    //------------receiver
        $receiver=[];
        $receptor=$facturacionM->datosFacturaMasivaM();
        if(count($receptor)>0){
            $receiver['Rfc']=$receptor[0]->rfc;
            $receiver['Name']=$receptor[0]->nombre_fiscal;
            $receiver['CfdiUse']=$receptor[0]->uso_cfdi3;
            $datos['Receiver']=$receiver;
            $datos['PaymentConditions']=$receptor[0]->condiciones_pago;
            $datos['PaymentForm']=$receptor[0]->forma_pagocfdi;
            $datos['PaymentMethod']=$receptor[0]->metodo_pago;
        }
        else{
            $datos['error']="Error en los datos del receptor";
        }
    //------------issuer
        $issuer=[];
        $sucursal=$facturacionM->datosFiscalesSucursalM();
        $ivaFrontera=0;
        $ivaIncluido=0;
        if(count($sucursal)>0){
            $issuer['FiscalRegime']=$sucursal[0]->regimen;
            $issuer['Rfc']=$sucursal[0]->rfc;
            $issuer['Name']=$sucursal[0]->nombre_fiscal;
            $datos['Issuer']=$issuer;
            $datos['ExpeditionPlace']=$sucursal[0]->cp;
            $datos['Serie']=$sucursal[0]->serie;
            $ivaFrontera=$sucursal[0]->iva_frontera;
            $ivaIncluido=$sucursal[0]->iva_incluido;
        }
        else{
            $datos['error']="Error en los datos del emisor";
        }
    //------------generales
        $folioInterno=$facturacionM->datosFolioInternoFacturaM();
        if(count($folioInterno)==0)
            $folio=1;
        else
            $folio=intval($folioInterno[0]->folio_consecutivo)+1;
        $datos['Currency']="MXN";;
        $datos['Folio']=$folio;
        $datos['CfdiType']="I";

    //-------------items
        $miva=".16";
        if($ivaFrontera==1){
           $miva=".08";  
        }
        $items=[];

        $ritems=$facturacionM->obtenerDatosListaOrdenesM($miva,$ivaIncluido);
        $sumaTotalImpuestos=0;
        $sumaBase=0;
        $sumaSubtotal=0;
        $sumaTotal=0;
        $sumaPrecioU=0;
        foreach ($ritems AS $row=>$item) {
            $impuestos=[];
            $impuestos['Total']=number_format($item->total,2);
            $sumaTotalImpuestos=$sumaTotalImpuestos+$item->total;
            $impuestos['Name']="IVA";
            $impuestos['Base']=number_format($item->base,2);
            $sumaBase=$sumaBase+$item->base;
            $impuestos['Rate']=($item->rate);
            $impuestos['IsRetention']=false;
            $items[$row]["productCode"]=$item->productCode;
            $items[$row]["identNumber"]=$item->identNumber;
            $items[$row]["description"]=$item->nombre_estudio;
            $items[$row]["Unit"]="NO APLICA";
            $items[$row]["UnitCode"]="E48";
            $items[$row]["UnitPrice"]=number_format($item->precioU,2);
            $sumaPrecioU=$sumaPrecioU+$item->precioU;
            $items[$row]["Quantity"]=$item->numero_estudios;
            $items[$row]["Subtotal"]=number_format($item->subtotal,2);
            $sumaSubtotal=$sumaSubtotal+$item->subtotal;
            $items[$row]["Discount"]=0.0;
            $items[$row]["Taxes"][]=$impuestos;
            $items[$row]["Total"]=number_format($item->totalM,2);
            $sumaTotal=$sumaTotal+floatval(str_replace(",","",$item->totalM));
        }

        if($receptor[0]->usar_descripcion==1){
            $items=[];
            $impuestos=[];
            $impuestos['Total']=round($sumaBase*$item->rate,2);
            $impuestos['Name']="IVA";
            $impuestos['Base']=round($sumaBase,2);
            $impuestos['Rate']=($item->rate);
            $impuestos['IsRetention']=false;
            $items[0]["productCode"]=$item->productCode;
            $items[0]["identNumber"]=$item->identNumber;
            $items[0]["description"]=$receptor[0]->descripcion;
            $items[0]["Unit"]="NO APLICA";
            $items[0]["UnitCode"]="E48";
            $items[0]["UnitPrice"]=round($sumaSubtotal,2);
            $items[0]["Quantity"]=1;
            $items[0]["Subtotal"]=round($sumaSubtotal,2);
            $items[0]["Discount"]=0.0;
            $items[0]["Taxes"][]=$impuestos;
            $items[0]["Total"]=round($sumaSubtotal+$impuestos['Total'],2);
        }
        $datos["Items"]=$items;
        $facturacionM->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }
#============= Termina Función para obtener los datos del cfdi de una orden (timbrado masivo)========


#============= Función para obtener los datos del cfdi de una orden de empresa (timbrado empresa)========

    private function datos_cfdi_orden_masiva_empresa(){
        $facturacionM=new FacturacionModel();
        $datos=[];
    //------------receiver
        $receiver=[];
        $empresa=$facturacionM->datosFiscalesEmpresaM($_REQUEST['id_empresa']);
        if(count($empresa)>0){
            $receiver['Rfc']=$empresa[0]->rfc;
            $receiver['Name']=$empresa[0]->nombre_fiscal;
            $receiver['CfdiUse']=$empresa[0]->uso_cfdi3;
            $datos['Receiver']=$receiver;
            $datos['PaymentConditions']=$empresa[0]->condiciones_pago;
            $datos['PaymentForm']=$empresa[0]->forma_pagocfdi;
            $datos['PaymentMethod']=$empresa[0]->metodo_pago;
        }
        else{
            $datos['error']="Error en los datos del receptor";
        }
        
    //------------issuer
        $issuer=[];
        $sucursal=$facturacionM->datosFiscalesSucursalM();
        $ivaFrontera=0;
        $ivaIncluido=0;
        if(count($sucursal)>0){
            $issuer['FiscalRegime']=$sucursal[0]->regimen;
            $issuer['Rfc']=$sucursal[0]->rfc;
            $issuer['Name']=$sucursal[0]->nombre_fiscal;
            $datos['Issuer']=$issuer;
            $datos['ExpeditionPlace']=$sucursal[0]->cp;
            $datos['Serie']=$sucursal[0]->serie;
            $ivaFrontera=$sucursal[0]->iva_frontera;
            $ivaIncluido=$sucursal[0]->iva_incluido;
        }
        else{
            $datos['error']="Error en los datos del emisor";
        }
    //------------generales
        $folioInterno=$facturacionM->datosFolioInternoFacturaM();
        if(count($folioInterno)==0)
            $folio=1;
        else
            $folio=intval($folioInterno[0]->folio_consecutivo)+1;
        $datos['Currency']="MXN";;
        $datos['Folio']=$folio;
        $datos['CfdiType']="I";
        
    //-------------items
        $miva=".16";
        if($ivaFrontera==1){
           $miva=".08";  
        }
        if($ivaIncluido==0)
            $miva=".0";
        $items=[];

        $ritems=$facturacionM->obtenerDatosListaOrdenesM($miva,$ivaIncluido);
         $sumaTotalImpuestos=0;
        $sumaBase=0;
        $sumaSubtotal=0;
        $sumaTotal=0;
        $sumaPrecioU=0;
        foreach ($ritems AS $row=>$item) {
                $impuestos=[];
                $impuestos['Total']=number_format($item->total,2);
                $sumaTotalImpuestos=$sumaTotalImpuestos+$item->total;
                $impuestos['Name']="IVA";
                $impuestos['Base']=number_format($item->base,2);
                $sumaBase=$sumaBase+$item->base;
                $impuestos['Rate']=($item->rate);
                $impuestos['IsRetention']=false;
                $items[$row]["productCode"]=$item->productCode;
                $items[$row]["identNumber"]=$item->identNumber;
                $items[$row]["description"]=$item->nombre_estudio;
                $items[$row]["Unit"]="NO APLICA";
                $items[$row]["UnitCode"]="E48";
                $items[$row]["UnitPrice"]=number_format($item->precioU,2);
                $sumaPrecioU=$sumaPrecioU+$item->precioU;
                $items[$row]["Quantity"]=$item->numero_estudios;
                $items[$row]["Subtotal"]=number_format($item->subtotal,2);
                $sumaSubtotal=$sumaSubtotal+$item->subtotal;
                $items[$row]["Discount"]=0.0;
                $items[$row]["Taxes"][]=$impuestos;
                $items[$row]["Total"]=$item->totalM;
                $sumaTotal=$sumaTotal+$item->totalM;
        }

        if($empresa[0]->usar_descripcion==1){
            $items=[];
            $impuestos=[];
            $impuestos['Total']=number_format($sumaTotalImpuestos,2);
            $impuestos['Name']="IVA";
            $impuestos['Base']=number_format($sumaBase,2);
            $impuestos['Rate']=($item->rate);
            $impuestos['IsRetention']=false;
            $items[0]["productCode"]=$item->productCode;
            $items[0]["identNumber"]=$item->identNumber;
            $items[0]["description"]=$empresa[0]->descripcion;
            $items[0]["Unit"]="NO APLICA";
            $items[0]["UnitCode"]="E48";
            $items[0]["UnitPrice"]=number_format($sumaSubtotal,2);
            $items[0]["Quantity"]=1;
            $items[0]["Subtotal"]=number_format($sumaSubtotal,2);
            $items[0]["Discount"]=0.0;
            $items[0]["Taxes"][]=$impuestos;
            $items[0]["Total"]=number_format($sumaTotal,2);
        }
        $datos["Items"]=$items;
        $facturacionM->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }
#========== Termina Función para obtener los datos del cfdi de una orden (timbrado individual)===


    
//--------------------------- datos de ordenes para timbrado masivo
    private function obtenerDatosListaOrdenes(){
        $facturacionM=new FacturacionModel(); 
        $sucursal=$facturacionM->datosFiscalesSucursalM();
        $ivaIncluido=$sucursal[0]->iva_incluido;
        $miva=null;//-------este dato se ocupar para el timbrado, esta función es sólo informativa
        if($ivaIncluido==0){
            $miva=".16";
        }
        $res=$facturacionM->obtenerDatosListaOrdenesM($miva,$ivaIncluido); 
        $respuesta=[];
        $respuesta['tabla']="";
        $respuesta['total']=0;
        $consecutivos=implode(",", $_REQUEST['lista_ordenes']);
        $respuesta['iva']=$ivaIncluido;
        foreach ($res AS $row=>$item) {
            if(floatval($item->precio_estudio)==0){
                $respuesta['error']=1;
                break;
            }
            $respuesta['total']=$respuesta['total']+floatval($item->precio_estudio);
            $respuesta['tabla'].='<tr>
                <td>'.$item->nombre_estudio.'</td>
                <td>'.$item->numero_estudios.'</td>
                <td style="text-align:right">$'.number_format(floatval($item->precio_estudio)/(intval($item->numero_estudios)),2).'</td>
                <td style="text-align:right">$'.number_format($item->subtotal,2).'</td>
            </tr>';
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($respuesta);
    }



#-Función para guardar los datos de un CFDI timbrado individual
    private function guardaCFDI(){
        $facturacionM=new FacturacionModel();
        $datos=$facturacionM->guardaCFDIM();
        //echo $datos; 
    }



#Obtener las formas de pago para Facturación del modelo catalogo general
    public function obtenerListaPago(){
        $catalogosM=new Catalogos();
        $res=$catalogosM->getFormasPago();
        foreach ($res AS $row=>$item) {
            echo '<option value="'.$item->id.'">'.$item->clave.'-'.$item->nombre.'</option>';
        }
        $catalogosM->close();
    }


#Obtener el uso del CFDI del catalogo general
    public function obtenerUsoCFDI(){
        $catalogosM=new Catalogos();
        $res=$catalogosM->getUsoCFDI();
        foreach ($res AS $row=>$item) {
            echo '<option value="'.$item->id.'">'.$item->clave.'-'.$item->uso.'</option>';
        }
        $catalogosM->close();
    }

    public function listaFacturasPreCanceladas($fecha){
        $facturacionM=new FacturacionModel();
        $res=$facturacionM->listaFacturasCanceladasM($fecha);
   

        foreach ($res AS $row=>$item) {

            $res=$facturacionM->ObtenerBlobCFDIM($item->id); 
            $datosFactura=$this::datosXML(base64_decode($res[0]->blob_cfdi));
            if(strpos($item->lista_ordenes,",")===false){
                $orden=$facturacionM->datosOrdenM($item->lista_ordenes);
                $item->lista_ordenes=$orden[0]->consecutivo;
            }
            $listaOrdenesArray=explode(",", $item->lista_ordenes);
            $lista_ordenesC;
            if(count($listaOrdenesArray)>0){
                for ($i=0; $i < count($listaOrdenesArray); $i++) { 
                    if($i%8==0 && $i>0)
                        $lista_ordenesC=$lista_ordenesC."<br>";
                    $lista_ordenesC=$lista_ordenesC.$listaOrdenesArray[$i].",";
                }
            }

            
            echo '<tr>
                <td>'.$item->folio_consecutivo.'</td>
                <td style="font-size:12px;">'.$datosFactura['uuid'].'</td>
                <td>'.$lista_ordenesC.'</td>
                <td>'.$item->fecha_certificacion.'</td>
                <td>'.$item->rfc.'</td>
                <td>
                     <b>'.$item->status.' - Motivo:'.$item->motivo.'</b><br>
                     <span style="font-size:11px;">'.$item->mensaje.'</span><br>
                     Fecha de énvio:'.$item->fecha_cancelacion.'
                </td>
                <td><button data-id="'.$item->id_cfdi.'" data-id-tabla="'.$item->id.'" class="btn btn-danger btn-cancelar_cfdi">CANCELAR</button></td>
            </tr>';
        }
    }

    public function listaFacturasTimbradas($folio,$rfc,$empresa,$fecha){ 
        $facturacionM=new FacturacionModel();
        $res=$facturacionM->listaFacturasTimbradasM($folio,$rfc,$empresa,$fecha);
        $facturasTimbradas="";
        foreach ($res AS $row=>$item) {
            $facturasTimbradas.= '<tr stye="font-size:11px;">
                <td>'.$item->folio_consecutivo.'</td> 
                ';

            $res=$facturacionM->ObtenerBlobCFDIM($item->id); 
            $datosFactura=$this::datosXML(base64_decode($res[0]->blob_cfdi));
            $facturasTimbradas.= '<td>'.$datosFactura['uuid'].'</td>';

            $ordenes=explode(",", $item->lista_ordenes);
            $tdOrdenes="<td>";
                if(count($ordenes)==1 && $item->nombre_empresa==null){
                    $informacionOrden=$facturacionM->datosOrdenM($ordenes[0]);
                    $tdOrdenes.=$informacionOrden[0]->consecutivo;
                    
                }
                else{
                    for($i=0;$i<count($ordenes);$i++){
                        $tdOrdenes.=$ordenes[$i].',';
                        if($i%8==0 && $i>1){$tdOrdenes.= "<br>";}
                    }
                    $tdOrdenes=substr($tdOrdenes,0,-1);
                }
                
                $tdOrdenes.="</td>";
                
            $facturasTimbradas.= $tdOrdenes;
            $facturasTimbradas.= '<td>'.$item->fecha_certificacion.'</td> 
                <td>'.$item->rfc.'</td> 
                <td>'.$item->nombre_empresa.'</td> 
                <td style="text-align:center">
                    <button data-id="'.$item->id.'" class="btn btn-secondary btn-sm btn_pdf">PDF</button>
                    <button data-id="'.$item->id.'" class="btn btn-info btn-sm btn_xml">XML</button>
                    <button data-id="'.$item->id.'" class="btn btn-secondary btn-sm btn_correo">EMAIL</button>
                    <button data-id="'.$item->id.'" class="btn btn-danger btn-sm btn_pcancelar">PRE-CANCELAR</button>
                </td> 
            </tr>';
        }
        echo $facturasTimbradas;
    }



    public function listaOrdenesIndividuales($mes_factura){
        $facturacionM=new FacturacionModel();
        $res=$facturacionM->listaOrdenesIndividualesM($mes_factura);
        //print_r($res);
        $respuesta['html']="";
        foreach ($res AS $row=>$item) {
            $respuesta['html'].='<tr>
                <td>'.$item->fecha_registro.'</td>
                <td>'.$item->orden_c.'</td>
                <td>'.$item->nombre.'</td>
                <td>'.$item->empresa_nombre.'</td>
                <td>'.$item->importe.'</td>';
                $paciente=$facturacionM->datosFiscalesPacienteM($item->id_paciente);
                $completo="";
                if(count($paciente)==0){
                    $estilo='style="background:gray;border-color:gray;"';
                    $completo='class="btn btn-sm btn-success rounded-circle m-1"';
                }
                else{
                    $estilo='';
                    if($paciente[0]->rfc!=null && $paciente[0]->forma_pago!=null && $paciente[0]->uso_cfdi!=null)
                        $completo='class="btn btn-sm btn-success rounded-circle m-1 timbrar_individual" data-orden="'.$item->orden_id.'" data-paciente="'.$item->id_paciente.'" ';
                }
                $respuesta['html'].='
                <td style="text-align:center;">  
                    <button id="datosFactura'.$row.'" data-id="'.$item->id_paciente.'" data-nombre="'.$item->nombre.'" type="button" class="btn btn-sm btn-info rounded-circle m-1 boton_dfp" title="Ver Estudios" '.$estilo.'>
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
                <td style="text-align:center;">  
                    <button id="btnTf_'.$row.'" '.$completo.' type="button"  '.$estilo.' title="Timbrar Factura">
                        <i class="fas fa-check"></i>
                    </button>
                </td>
            </tr>';
        }
        echo $respuesta['html'];

    }


    public function listaOrdenesMasiva($mes_factura){
        $facturacionM=new FacturacionModel();
        $res=$facturacionM->listaOrdenesIndividualesM($mes_factura);
        //print_r($res);
        $respuesta['html']="";
        foreach ($res AS $row=>$item) {
            $respuesta['html'].='<tr>
                <td>'.$item->fecha_registro.'</td>
                <td>'.$item->orden_c.'</td>
                <td>'.$item->nombre.'</td>
                <td>'.$item->empresa_nombre.'</td>
                <td>'.$item->importe.'</td>
                <td><input type="checkbox" id="fcheck'.$row.'" data-orden="'.$item->orden_c.'" class="fm_check" ></td>
            </tr>';
        }
        echo $respuesta['html']; 

    }

    public function obtenerListaOrdenesEmpresa(){
        $facturacionM=new FacturacionModel();
        $res=$facturacionM->obtenerListaOrdenesEmpresaM();

        $tabla="";
        foreach ($res AS $row=>$item) {
           $tabla.= '<tr>
                <td>'.($row+1).'</td>
                <td>'.$item->consecutivo.'</td>
                <td>'.utf8_encode($item->empresa).'</td>
                <td>'.($item->paciente).'</td>
                <td>'.$item->fecha_registro.'</td>
                <td>'.$item->importe.'</td>
                <td><input type="checkbox" id="fecheck'.$row.'" data-orden="'.$item->consecutivo.'" data-empresa="'.$_REQUEST['empresa'].'" class="fm_check" ></td>
            </tr>';
        }
        $respuesta['nombre_empresa']=utf8_encode($item->empresa);
        $respuesta['id_empresa']=utf8_decode($item->id_empresaf);
        $respuesta['tabla']=$tabla;
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($respuesta);
    }


    public function obtenerCFDIEmpresa(){
        $facturacionM=new FacturacionModel();
        $res=$facturacionM->obtenerCFDIEmpresaM();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($res[0]);
    }

    private function ObtenerBlobCFDI(){
        $facturacionM=new FacturacionModel();
        $res=$facturacionM->ObtenerBlobCFDIM($_REQUEST['id_cfdi']);
        echo $res[0]->blob_cfdi;

    }

    private function reporteFacturacion(){
        $facturacionM=new FacturacionModel();
        $res=$facturacionM->informacionFacturasTimbradasM($_REQUEST['fecha_inicial'],$_REQUEST['fecha_final'],$_REQUEST['descripcion'],$_REQUEST['busqueda']);
        foreach ($res AS $row=>$item) {
            $datosFiscalesG=$facturacionM->obtenerDatosCFDIPacientesCFDIM($item->id_cfdi);

            $datosFactura=$this::datosXML(base64_decode($item->blob_cfdi));
            $lista_ordenes=explode(",",$datosFiscalesG[0]->lista_ordenes);
            $uso_fp_letra=$facturacionM->ObtenerFormaPagoM($datosFactura['forma_pago']);
            echo '<tr>
                <td>'.($row+1).'</td>
                <td>'.$datosFiscalesG[0]->folio_consecutivo.'</td><td>';

                for($i=0;$i<count($lista_ordenes);$i++){
                    $orden=$facturacionM->datosOrdenM($lista_ordenes[$i]);
                    echo $orden[0]->consecutivo.',';
                }
                
            
            echo '</td>
                <td>'.$datosFactura['fecha_timbrado'].'</td>
                <td>'.utf8_encode($datosFactura['receptor_nombre']).'</td>
                <td>'.$datosFactura['receptor_rfc'].'</td>
                <td>'.$datosFactura['uuid'].'</td>
                <td>'.number_format(floatval($datosFactura['subtotal']),2).'</td>
                <td>'.number_format(floatval($datosFactura['total'])-floatval($datosFactura['subtotal']),2).'</td>
                <td>'.number_format(floatval($datosFactura['total']),2).'</td>
                <td>'.$uso_fp_letra[0]->nombre.'</td>
            </tr>';
        }
        
    }

    private function crearPdfCFDI(){
        error_reporting(0);
        $facturacionM=new FacturacionModel();
        $res=$facturacionM->ObtenerBlobCFDIM($_REQUEST['id_cfdi']); 

        $datosSucursal=$facturacionM->obtenerInformacionSucursal($_SESSION["id_sucursal"]);
        $datosFiscalesG=$facturacionM->obtenerDatosCFDIPacientesM($_REQUEST['id_cfdi']);
        $datosFactura=$this::datosXML(base64_decode($res[0]->blob_cfdi));
        $uso_cfdi_letra=$facturacionM->ObtenerUsoCFDIM($datosFactura['uso_cfdi']);
        $uso_fp_letra=$facturacionM->ObtenerFormaPagoM($datosFactura['forma_pago']);

        $direccion_fiscal="";
        if(substr($datosFiscalesG[0]->direccion_fiscal, 0, 1)!=','){
            $direccion_fiscal=$datosFiscalesG[0]->direccion_fiscal;
        }
        
        $pdf = new mPDF('UTF-8', 'A4', "10pt", 'dejavuserif',10,10,50,50);
        $pdf->SetTitle("Factura-" . $_REQUEST['id_cfdi']);
        $img='../../images-sucursales/'.$datosSucursal[0]->img;
        $pdf->SetHTMLHeader('<div>
            <table style="width:100%;">
                <tr>
                    <td rowspan="2" width="20%"><img src="'.$img.'" width="100" /></td>
                    <td width="60%" style="text-align:center;"><b>EMISOR</b><BR><span style="font-size:18px;color:#234462;">'.utf8_encode($datosFactura['emisor_nombre']).'</span><br><b>RFC:'.$datosFactura['emisor_rfc'].'</b></td>
                    <td width="20%"></td>
                </tr>
                <tr>
                    <td style="text-align:center;">Tipo de comprobante:'.$datosFactura['tipo_comprobante'].'<br>Lugar de expedición:'.$datosFactura['lugar_expedicion'].'<BR>Regimen Físcal:'.utf8_encode($datosFactura['emisor_rf']).'</td>
                    <td><span style="color:red;">Factura:'.$datosFactura['folio_factura'].'</span><BR>Fecha:'.str_replace("T"," ",$datosFactura['fecha_timbrado']).'</td>
                </tr>
            </table>
        </div>'); 
        $pdf->SetHTMLFooter('<div>
                <table style="width:20cm !important;" cellPadding="0" cellspacing="0">
                    <tr>
                        <td colspan="2" style="background:#678BA3;color:white;text-align:center;">ESTE DOCUMENTO ES UNA REPRESENTACIÓN IMPRESA DE UN CFDI</td>
                    </tr>
                    <tr>
                        <td style="border:solid 0.1px #333;">UUID Relacionado</td>
                        <td style="border:solid 0.1px #333;">'.$datosFactura['uuid_relacionado'].'</td>
                    </tr>
                    <tr>
                        <td style="border:solid 0.1px #333;">Serie del Certificado del emisor</td>
                        <td style="border:solid 0.1px #333;">'.$datosFactura['serie_certificado'].'</td>
                    </tr>
                    <tr>
                        <td style="border:solid 0.1px #333;">Folio Fiscal</td>
                        <td style="border:solid 0.1px #333;">'.$datosFactura['uuid'].'</td>
                    </tr>
                    <tr>
                        <td style="border:solid 0.1px #333;">No. de Serie del Certificado del SAT</td>
                        <td style="border:solid 0.1px #333;">'.$datosFactura['numero_certificado_sat'].'</td>
                    </tr>
                </table>
                <div style="font-size:10px;border:solid 1px #333;"><span style="color:#678BA3">Sello Digital del CFDI: </span>'.$datosFactura['sello_sat'].'</div>
                <div style="font-size:10px;border:solid 1px #333;"><span style="color:#678BA3">Sello del SAT: </span>'.$datosFactura['sello_cfdi'].'</div>
                <div style="font-size:10px;border:solid 1px #333;"><span style="color:#678BA3">Cadena original del complemento de certificación digital del SAT:</span>'.$datosFactura['cadena_original'].'</div>
            </div>');

         $cuerpo='<html><body style="padding-top:3cm">
            <div style="border:solid 1px black;">
                <table style="width:100%;">
                    <tr>
                        <td width="60%">Cliente:<b>'.utf8_encode($datosFactura['receptor_nombre']).'</b></td>
                        <td>RFC:<b>'.utf8_encode($datosFactura['receptor_rfc']).'</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Dirección:<b>'.($direccion_fiscal).'</b></td>
                    </tr>
                    <tr>
                        <td>Uso del CFDI:<b>'.($uso_cfdi_letra[0]->uso).'</b></td>
                        <td>Forma de pago:<b>'.($uso_fp_letra[0]->nombre).'</b></td>
                    </tr>
                </table> 
            </div>
            <div>
            <br>
            <table style="width:100%;font-size:11px">
                <tr >
                    <th style="color:#678BA3">Cantidad</th>
                    <th style="color:#678BA3">Clave P.</th>
                    <th style="color:#678BA3">Clave U.</th>
                    <th style="color:#678BA3;width:30%;">Concepto</th>
                    <th style="color:#678BA3">Valor Unitario</th>
                    <th style="color:#678BA3">Impuestos</th>
                    <th style="color:#678BA3">Subtotal</th>
                    <th style="color:#678BA3">Total</th>
                </tr>
            ';


        for($i=0;$i<count($datosFactura['conceptos']['cantidad']);$i++){
            $subtotal=floatval($datosFactura['conceptos']['importe'][$i])+floatval($datosFactura['conceptos']['impuesto_importe'][$i]);
            $cuerpo.='<tr>
                <td style="text-align:center">'.$datosFactura['conceptos']['cantidad'][$i].'</td>
                <td style="text-align:center">'.$datosFactura['conceptos']['clave_producto'][$i].'</td>
                <td style="text-align:center">'.$datosFactura['conceptos']['clave_unidad'][$i].'</td>
                <td style="text-align:left">'.($datosFactura['conceptos']['conceptoD'][$i]).'</td>
                <td style="text-align:center">'.$datosFactura['conceptos']['importe'][$i].'</td>
                <td style="text-align:center">IVA-'.$datosFactura['conceptos']['impuesto_importe'][$i].'</td>
                <td style="text-align:center">'.$datosFactura['conceptos']['importe'][$i].'</td>
                <td style="text-align:center">'.$subtotal.'</td>
            </tr>';
        }
        $cuerpo.='</table>
        <br><table cellSpacing="0" cellspacing="0" style="width:100%;">
            <tr>
                <td style="border:solid 0.1px #333;width:50%;color:#678BA3;">Importe con Letra</td>
                <td style="border:solid 0.1px #333;color:#678BA3;">Subtotal</td>
                <td style="border:solid 0.1px #333;text-align:right;">$'.number_format(floatval($datosFactura['subtotal']),2).'</td>
            </tr>
            <tr>
                <td rowspan="2" style="border:solid 0.1px #333;">'.(convertir_a_letras($datosFactura['total'])).' pesos</td>
                <td style="border:solid 0.1px #333;color:#678BA3;">Impuestos</td>
                <td style="border:solid 0.1px #333;text-align:right;">$'.number_format(floatval($datosFactura['total'])-floatval($datosFactura['subtotal']),2).'</td>
            </tr>
            <tr>
                <td style="border:solid 0.1px #333;color:#678BA3;">TOTAL</td>
                <td style="border:solid 0.1px #333;text-align:right;"><b>$'.number_format(floatval($datosFactura['total']),2).'</b></td>
            </tr>
        </table>';

        $cuerpo.='</div></body></html>';
        echo '<pre>'; print_r($cuerpo); echo '</pre>';

        $pdf->WriteHTML($cuerpo);

            
        $pdf->Output($_SERVER["DOCUMENT_ROOT"]."/reportes/facturacion/Factura-" . $_REQUEST['id_cfdi'] . ".pdf");
        
        return;
    }

    public static function datosXML($datosXML){
      $xml = new SimpleXMLElement($datosXML);
      $ns = $xml->getNamespaces(true);
      $xml->registerXPathNamespace('c', $ns['cfdi']);
      $xml->registerXPathNamespace('t', $ns['tfd']);
      
      foreach ($xml->xpath('//cfdi:Comprobante//cfdi:CfdiRelacionados//cfdi:CfdiRelacionado') as $Relacion){ 
        $df['uuid_relacionado']=$Relacion['UUID']; 
      }
      
      foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
        $df['emisor_rfc']=$Emisor['Rfc']; 
        $df['emisor_nombre']=utf8_decode($Emisor['Nombre']); 
        $df['emisor_rf']=$Emisor['RegimenFiscal']; 
      }
      foreach ($xml->xpath('//cfdi:Comprobante') as $Emisor){ 
        $df['forma_pago']=$Emisor['FormaPago']; 
        $df['metodo_pago']=$Emisor['MetodoPago']; 
        $df['moneda']=$Emisor['Moneda'];    
        $df['lugar_expedicion']=$Emisor['LugarExpedicion']; 
        $df['fecha']=$Emisor['Fecha']; 
        $df['serie']=$Emisor['Serie']; 
        $df['serie_certificado']=$Emisor['NoCertificado']; 
        $df['folio']=$Emisor['Folio']; 
        $df['subtotal']=$Emisor['SubTotal']; 
        $df['total']=$Emisor['Total']; 
        $df['tipo_comprobante']=$Emisor['TipoDeComprobante']; 
        $df['folio_factura']=$Emisor['Serie'].$Emisor['Folio'];  

      }
      $df['cadena_original']="|";
      foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//tfd:TimbreFiscalDigital') as $Emisor){ 
        $df['cadena_original'].="|".$Emisor['Version'];
        $df['cadena_original'].="|".$Emisor['UUID'];
        $df['cadena_original'].="|".$Emisor['FechaTimbrado'];
        $df['cadena_original'].="|".$Emisor['RfcProvCertif'];
        $df['cadena_original'].="|".$Emisor['SelloCFD'];
        $df['cadena_original'].="|".$Emisor['NoCertificadoSAT'];
        $df['uuid']=$Emisor['UUID'];
        $df['numero_certificado_sat']=$Emisor['NoCertificadoSAT']; 
        $df['sello_cfdi']=$Emisor['SelloCFD']; 
        $df['sello_sat']=$Emisor['SelloSAT']; 
        $df['fecha_timbrado']=$Emisor['FechaTimbrado'];


      }
     $df['cadena_original'].="||";
       
      foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
        $df['receptor_rfc']=$Receptor['Rfc']; 
        $df['receptor_nombre']=utf8_decode($Receptor['Nombre']); 
        $df['uso_cfdi']=$Receptor['UsoCFDI']; 
      }
      $cont=0;
      
      foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){ 
        
        //$df['cadena_original']+="|".$Concepto['UUID'];
        $conc['cantidad'][$cont]=$Concepto['Cantidad'];
        $conc['clave_producto'][$cont]=$Concepto['ClaveProdServ'];
        $conc['conceptoD'][$cont]=$Concepto['Descripcion'];
        $conc['importe'][$cont]=$Concepto['Importe'];
        $conc['clave_unidad']=$Concepto['ClaveUnidad'];
        $cont++;
      }
      $cont=0;
      foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Impuesto){ 
        $conc['impuesto_base'][$cont]=$Impuesto['Base'];
        $conc['impuesto_impuesto'][$cont]=$Impuesto['Impuesto'];
        $conc['impuesto_iva'][$cont]=$Impuesto['TasaOCuota'];
        $conc['impuesto_importe'][$cont]=$Impuesto['Importe'];
        $cont++;
      }
      $df['conceptos']=$conc;
      return $df;
    } 


}
new Facturacion();