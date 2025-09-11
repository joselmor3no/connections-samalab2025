<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/administracion/Administracion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/catalogos/Users.php');
require_once $_SERVER["DOCUMENT_ROOT"]  . '/vendor/autoload.php'; 
use Mpdf\Mpdf;

class Administracion { 
	 function __construct() {
    	 if(isset($_REQUEST['opc'])){
            $usuarios = new Usuarios();
            $acceso = $usuarios->validarSession();
            if (!$acceso) {
                header("Location: /");
            }

	 		if ($_REQUEST['opc'] == 'lista_ordenes') {
	            $this->listaOrdenes();
	        }
            else if($_REQUEST['opc'] == 'lista_estudios_orden'){
                $this->listaEstudiosOrden();
            }
            else if($_REQUEST['opc'] == 'pagos_empresa'){
                $this->listaPagosEmpresa();
            }
            else if($_REQUEST['opc'] == 'genera_reporte_global'){
                $this->listaGeneraReporteG();
            }
            else if($_REQUEST['opc'] == 'genera_reporte_global_caja'){
                $this->listaGeneraReporteGC();
            }
            else if($_REQUEST['opc'] == 'genera_reporte_global_paciente'){
                $this->listaGeneraReportePaciente();
            }
            else if($_REQUEST['opc'] == 'ordenes_adeudo_empresas'){
                $this->listaOrdenesEmpresa($_REQUEST['fecha_inicio'],$_REQUEST['fecha_fin']); 
            }
            else if($_REQUEST['opc'] == 'imprime_pago_empresas'){
                $this->listaImprimeOrdenesEmpresa(); 
            }

            
            
	 	}
    }

    
# pdf de pago de empresas
    public function listaImprimeOrdenesEmpresa(){
        $administracionM=new AdministracionModel();
    	$res=$administracionM->listaOrdenesEmpresaM(); 

        $html='
        <style>
            body {
            background-image: url(../../reportes/sama/membrete.png);
            background-size: cover;
            background-repeat: no-repeat;
            background-image-resize: 6;
            }

            @page *{
                margin-top: 15.54cm;
                margin-bottom: 3 cm;
                margin-left: 1.5 cm;
                margin-right: 1.5cm;
            }

            table{
                font-size:11px;
            }
            table .th1{
                border: solid 0.2px black;
                background:#59A2FA;
                color:black;
                font-wieght:bold;
                padding:3px;
            }
            table td{
                border: solid 0.2px black;
                padding:3px;
            }
        </style>
        <body>
            <div style="text-align:center;width:100%;">
                <h4>
                    LABORATORIO MÉDICO SAMALAB<BR>
                    ESTADO DE CUENTA DEL '.$_REQUEST['fecha_inicial'].' a '.$_REQUEST['fecha_final'].'<br>
                    EMPRESA:'.$_REQUEST['nombre_empresa'].'
                </h4>
            </div>
            
            
            </table>
        ';
        $html.='<table cellspacing="0" cellpadding="1" class="table table-streped" style="width:100%;">
            <thead>
                <tr>
                    <th class="th1"># Orden</th>
                    <th class="th1">Paciente</th>
                    <th class="th1">Fecha</th>
                    <th class="th1" >Estudio</th>
                    <th class="th1" >Precio</th>
                    <th class="th1" >Descuento</th>
                    <th class="th1">Total</th>
                </tr>
            </thead>
            <tbody> '; 
            $totalImporte=0;
            $totalDescuento=0;
            $totalApagar=0;
  		foreach ($res AS $row=>$item) { 
            $res2=$administracionM->listaEstudiosIOrdenM($item->id_orden);
            foreach ($res2 AS $row2=>$item2) {
                $html.='<tr>';
                if($row2==0){
                    $html.='
    			        <td>'.$item->consecutivo.'</td>
    			        <td>'.$item->nombre_paciente.'</td>
    			        <td>'.$item->fecha_registro.'</td>';
                }
                else{
                    $html.='
    			        <td></td>
    			        <td></td>
    			        <td></td>';
                }
                $formato_descuento="$".number_format($item2->porcentaje_descuento,2);
                if($item2->tipo_descuento=="porcentaje")
                    $formato_descuento=$item2->porcentaje_descuento." %";
                elseif($item2->tipo_descuento=="monto")
                    $formato_descuento="$ ".$item2->porcentaje_descuento;
                else
                    $formato_descuento=" - ";
                $totalImporte=$totalImporte+floatval($item2->precio_neto_estudio);
                $totalDescuento=$totalDescuento+floatval($item2->total_descuento);
                $totalApagar=$totalApagar+floatval($item2->total);
                $html.='
                    <td>'.$item2->nombre_estudio.'</td>
                    <td align="right">$'.number_format($item2->precio_neto_estudio,2).'</td>
                    <td align="left">'.$formato_descuento.'</td>
                    <td align="right">$'.number_format($item2->total,2).'</td>
                    </tr>';
            }
    	}
        $html.='<tr>
                    <td colspan="3" align="right" ></td>
                    <td  align="right" style="background:#EEAF05;">TOTAL</td>
                    <td align="right">$'.number_format($totalImporte,2).'</td>
                    <td align="left">$'.number_format($totalDescuento,2).'</td>
                    <td align="right">$'.number_format($totalApagar,2).'</td>
                    </tr>';

        $html.='</tbody></table>';
        $pdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'default_font_size' => 12,
            'default_font' => 'arial'
        ]);
        $pdf->SetTitle("REPORTE-DEUDA-EMPRESA" . $_REQUEST['id_empresa']);
        $pdf->WriteHTML($html);
        $pdf->Output($_SERVER["DOCUMENT_ROOT"] ."/reportes/REPORTE-DEUDA-EMPRESA" . $_REQUEST['id_empresa'] . ".pdf", 'F');
        echo "REPORTE-DEUDA-EMPRESA" . $_REQUEST['id_empresa'] . ".pdf";
    }

#   Genera la tabla de reporte global
    public function listaGeneraReporteG(){ 
        $usuarios = new Users();
        $respuestaRG=$usuarios->obtenerReporteGlobal($_POST['fecha_inicial'],$_POST['fecha_final'],$_SESSION["id_sucursal"],$_POST['institucion'],$_POST['usuario'],$_POST['medico'],$_POST['estatus'],$_POST['tipo_pago']); 
        $administracionM=new AdministracionModel();
        
        $contador=1; 
                        $paciente=-1;
                        $orden_id=-1;
                        $totalPrecioNeto=0;
                        $totalImporte=0;
                        $totalACuenta=0;
                        $totalOrdeneV=0;
                        $totalSaldoDudor=0;
                        $contadorN=1;
                        foreach ($respuestaRG AS $row) {
                            $informacionPagos=$administracionM->informacionPagosOrden($row->id_orden);
                            $codigos=explode(",",$row->codigos_estudio);
                            $estudios=explode(",",$row->nombres_estudio);
                            $netos=explode(",",$row->precios_netos);
                            $ids_estudios=explode(",",$row->id_estudios);
                            $ppublicos=explode(",",$row->preciosp_estudio);
                            $secciones=explode(",",$row->secciones);
                            $referencias=explode(",",$row->referencias);
                            $nombre_paquete=explode(",",$row->nombre_paquete);
                            $tipos_descuento_clase=explode(",",$row->tipos_descuento_clase);
                            $montos_descuento_clase=explode(",",$row->montos_descuento_clase);
                            if($ppublicos[0]==0){
                                $precio=$usuarios->getPrecioEstudio($ids_estudios[0],$_SESSION["id_sucursal"])[0];
                                $ppublicos[0]=$precio->precio_publico;
                            }

                            if($row->cancelado==1){
                                $status="Cancelado";
                                $codigos[0]="Cancelado";
                                $estudios[0]="Cancelado";
                                $netos[0]=0;
                                $ppublicos[0]=0;
                            }
                            else{
                                $status="Vigente";
                            }
                            if($row->credito==1){
                                $condiciones="Credito";
                            }
                            else{
                                $condiciones="Contado";
                            }
                            $usuarioA=explode(" ", ($row->usuario));
                            $usuario_s=$usuarioA[0].' '.$usuarioA[1];
                            if($row->maquila!="-"){
                                $usuario_s="maquila";
                            }
                            if($row->empresa==NULL){
                                if($row->sucursal_maquila==124)
                                    $row->empresa="SAMALAB TEOTIHUACAN";
                                else if($row->sucursal_maquila==123)
                                    $row->empresa="SAMALAB OTUMBA";
                                else if($row->sucursal_maquila==140)
                                    $row->empresa="SAMALAB ACOLMAN";
                                else if($row->sucursal_maquila==141)
                                    $row->empresa="SAMALAB CALPULALPAN";
                                else if($row->sucursal_maquila==142)
                                    $row->empresa="SAMALAB SANTA MARIA";
                                else if($row->sucursal_maquila==123)
                                    $row->empresa="SAMALAB SAN MARTÍN TEXMELUCAN";
                                else if($row->sucursal_maquila==148)
                                    $row->empresa="ERMITA";
                                else if($_SESSION["id_sucursal"]==151)
                                    $row->empresa="SAMALAB ECATEPEC";
                                else
                                    $row->empresa="SAMALAB TEXCOCO";
                            }
                            
                            if($row->descuento==NULL)
                                $row->descuento=0;
                            if($row->cancelado==1){
                                $row->saldo_deudor=0;
                                $row->importe=0;
                            }

                            if($contador%2==0){
                                echo '<tr >';
                            }
                            else{
                                echo '<tr>';
                            }
                            $tipoDescuentoClase="";
                            $montoDescuentoClase="";
                            $precioPublicoDescuentoClase=$ppublicos[0];
                            if($montos_descuento_clase[0]>0){
                                $tipoDescuentoClase=$tipos_descuento_clase[0];
                                
                                if($tipoDescuentoClase=="porcentaje"){
                                    $montoDescuentoClase=$montos_descuento_clase[0]."%";
                                    $precioPublicoDescuentoClase=floatval($ppublicos[0])-($montoDescuentoClase*floatval($ppublicos[0])/100);
                                }
                                    
                                elseif($tipoDescuentoClase=="monto"){
                                    $montoDescuentoClase="$".number_format($montos_descuento_clase[0],2);
                                    $precioPublicoDescuentoClase=floatval($ppublicos[0])-floatval($montoDescuentoClase);
                                }
                                    
                            }

                            $descuento=$ppublicos[0]-$netos[0];
                            $totalPrecioNeto=$totalPrecioNeto+$netos[0];
                            $totalImporte=$totalImporte+$row->importe;
                            $totalACuenta=$totalACuenta+$row->acuenta;
                            $totalSaldoDudor=$totalSaldoDudor+$row->saldo_deudor;
                            if(floatval($ppublicos[0])>0)
                                $porcentajeDescuento=floatval($descuento)*100/floatval($ppublicos[0]);
                            else
                            $porcentajeDescuento==0;
                            //total pagado desde tabla pago
                            $totalTPago=floatval($informacionPagos->efectivo)+floatval($informacionPagos->tarjeta)+floatval($informacionPagos->otro);
                            echo '
                                <td>'.$contadorN.'</td>
                                <td>'.$status.'</td>
                                <td>'.$row->consecutivo.'</td>
                                <td width="20%">'.($row->empresa).'</td>
                                <td>'.$condiciones.'</td>
                                <td>'.$row->porcentaje_descuentoc.'</td>
                                <td>'.($row->paciente).'</td>
                                <td>'.($row->telefono).'</td>
                                <td>'.($row->correo).'</td>
                                <td>'.($row->alias_doctor).'</td>
                                <td>'.($row->doctor).'</td>
                                <td>'.substr($row->fecha_registro,0,10).'</td>
                                <td>'.substr($row->fecha_registro,10,18).'</td>
                                <td>'.$usuario_s.'</td>
                                <td>'.$codigos[0].'</td>
                                <td style="font-size:10px;">'.$estudios[0].'</td>
                                <td style="text-align:right">'.$ppublicos[0].'</td>

                                <td style="text-align:center" >'.$tipoDescuentoClase.'</td>
                                <td style="text-align:center" >'.$montoDescuentoClase.'</td>
                                <td style="text-align:right">'.$precioPublicoDescuentoClase.'</td>
                                <td style="text-align:center" >'.$row->nombre_descuento.'</td>
                                <td style="text-align:center" >'.(floatval($ppublicos[0])-floatval($netos[0])).'</td>
                                

                                <td style="text-align:right">'.number_format($netos[0],2).'</td>
                                <td style="text-align:right">'.$row->importe.'</td>
                                <td style="text-align:right">'.$row->acuenta.'</td>
                                <td style="text-align:right">'.$row->saldo_deudor.'</td>
                                <td style="text-align:right">'.$informacionPagos->efectivo.'</td>
                                <td style="text-align:right">'.$informacionPagos->fecha_efectivo.'</td>
                                <td style="text-align:right">'.$informacionPagos->tarjeta.'</td>
                                <td style="text-align:right">'.$informacionPagos->fecha_tarjeta.'</td>
                                <td style="text-align:right">'.$informacionPagos->otro.'</td>
                                <td style="text-align:right">'.$informacionPagos->fecha_otro.'</td>
                                <td style="text-align:left">'.number_format($totalTPago,2).'</td>
                                <td style="text-align:left">'.$secciones[0].'</td>
                                <td style="text-align:left">'.$referencias[0].'</td>
                                <td style="text-align:left">'.$nombre_paquete[0].'</td>
                                <td style="text-align:left">'.$row->tipo_orden.'</td>
                                <td style="text-align:left">'.$row->tipo_cliente.'</td>
                                <td style="text-align:left">'.$row->nombre_departamento.'</td>
                            </tr>';

                            if($row->cancelado==0){
                                for($i=1;$i<count($codigos);$i++){
                                    if($ppublicos[$i]==0){
                                        $precio=$usuarios->getPrecioEstudio($ids_estudios[$i],$_SESSION["id_sucursal"])[0];
                                        $ppublicos[$i]=$precio->precio_publico;
                                    }
                                    $descuento=$ppublicos[$i]-$netos[$i];
                                    $totalPrecioNeto=$totalPrecioNeto+$netos[$i];
                                    $porcentajeDescuento=floatval($descuento)*100/floatval($ppublicos[$i]);
                                    $tipoDescuentoClase="";
                                    $montoDescuentoClase="";
                                    $precioPublicoDescuentoClase=$ppublicos[$i];
                                    if($montos_descuento_clase[$i]>0){
                                        $tipoDescuentoClase=$row->tipos_descuento_clase[$i];
                                        
                                        if($tipoDescuentoClase=="porcentaje"){
                                            $montoDescuentoClase=$montos_descuento_clase[$i]."%";
                                            $precioPublicoDescuentoClase=floatval($ppublicos[$i])*$montoDescuentoClase/100;
                                        }
                                            
                                        elseif($tipoDescuentoClase=="monto"){
                                            $montoDescuentoClase="$".number_format($montos_descuento_clase[$i],2);
                                            $precioPublicoDescuentoClase=floatval($ppublicos[$i])-$montoDescuentoClase;
                                        }
                                            
                                    }
                                    echo '<tr>
                                        <td>'.$contadorN.'</td>
                                        <td>'.$status.'</td>
                                        <td>'.$row->consecutivo.'</td>
                                        <td width="20%">'.($row->empresa).'</td>
                                        <td>'.$condiciones.'</td>
                                        <td>'.($row->porcentaje_descuentoc).'</td>
                                        <td>'.($row->paciente).'</td>
                                        <td>'.($row->telefono).'</td>
                                        <td>'.($row->correo).'</td>
                                        <td>'.($row->alias_doctor).'</td>
                                        <td>'.($row->doctor).'</td>
                                        <td>'.substr($row->fecha_registro,0,10).'</td>
                                        <td>'.substr($row->fecha_registro,10,18).'</td>
                                        <td>'.$usuario_s.'</td>
                                        <td>'.$codigos[$i].'</td>
                                        <td style="font-size:10px;">'.$estudios[$i].'</td>
                                        <td style="text-align:right">'.number_format($ppublicos[$i],2).'</td>

                                        <td style="text-align:center" >'.$tipoDescuentoClase.'</td>
                                        <td style="text-align:center" >'.$montoDescuentoClase.'</td>
                                        <td style="text-align:right">'.$precioPublicoDescuentoClase.'</td>
                                        <td style="text-align:center" ></td>
                                        <td style="text-align:center" >'.(floatval($ppublicos[$i])-floatval($netos[$i])).'</td>
                                        

                                        <td style="text-align:right">'.number_format($netos[$i],2).'</td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:left"></td>
                                        <td style="text-align:left">'.$secciones[$i].'</td>
                                        <td style="text-align:left">'.$referencias[$i].'</td>
                                        <td style="text-align:left">'.$nombre_paquete[$i].'</td>
                                        <td style="text-align:left"></td>
                                        <td style="text-align:left"></td>
                                        <td style="text-align:left">'.$row->nombre_departamento.'</td>
                                    </tr>';
                                }
                            }
                            $contadorN++;        
                        }
                    echo '<tr>
                            <td>0</td>
                            <td>TOTALES:</td>
                            <td>c</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-size:10px;"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:center" ></td>
                            <td style="text-align:center" ></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:center" ></td>
                            <td style="text-align:center" ></td>
                            <td style="text-align:center" ></td>
                            <td style="text-align:center" ></td>
                            <td style="text-align:right">'.number_format($totalPrecioNeto,2).'</td>
                            <td style="text-align:right">'.number_format($totalImporte,2).'</td>
                            <td style="text-align:right">'.number_format($totalACuenta,2).'</td>
                            <td style="text-align:right">'.number_format($totalSaldoDudor,2).'</td>
                            <td style="text-align:right"></td>
                            <td style="text-align:left"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
    }

    #   Genera la tabla de reporte global caja
    public function listaGeneraReporteGC(){ 
        $usuarios = new Users();
        $respuestaRGC=$usuarios->obtenerReporteGlobalCaja($_POST['fecha_inicial'],$_POST['fecha_final'],$_SESSION["id_sucursal"],$_POST['usuario']);
        $respuestaRGCP=$usuarios->obtenerReporteGlobalCajaPago($_POST['fecha_inicial'],$_POST['fecha_final'],$_SESSION["id_sucursal"],$_POST['usuario']);

        $timporte=0;
        $tcubierto=0;
        $tdeudor=0;
        $contador=1;
        $consecutivoActual=-1;
        $pagoAT=0;
        $pagosAnteriores=0;
        $cajaR="";
        foreach ($respuestaRGC AS $row) {
            $orden_id=$row->id;
            $resTpo=$usuarios->obtenerReporteGlobalDescripcionPago($orden_id);
            $tdeudor=$tdeudor+$row->saldo_deudor;
            if($row->credito==1){
                $status="E.Credito";
            }
            elseif($row->saldo_deudor==0){
                $status="Pagado";
            }
            else{
                $status="C/adeudo";
            }
            $nombreU=explode(" ", $row->nombre_usuario);
            $fecha_r=substr($row->fecha_registro,0,10);
            $fecha_p=substr($row->fecha_pago,0,10);
            $cajaR.='<tr>
                <td>'.$contador.'</td>
                <td>'.utf8_encode($row->nombre).'</td>
                <td>'.$row->fecha_registro.'</td>
                <td>'.$row->consecutivo.'</td>
                <td>'.utf8_encode($row->nombre_empresa).'</td>
                <td>'.utf8_encode($row->nombre_paciente).'</td>';
                if($consecutivoActual!=$row->consecutivo){
                    $cajaR.='<td style="text-align:right">'.number_format($row->importe,2).'</td>';
                    $consecutivoActual=$row->consecutivo;
                    $timporte=$timporte+$row->importe;
                }
                else{
                    $cajaR.='<td style="text-align:right"></td>';
                }
                
                if($fecha_r==$fecha_p  && $row->credito!=1){
                    $cajaR.='
                    <td style="text-align:right">'.number_format($row->pago,2).'</td>
                    <td style="text-align:right;color:purple;">'.$row->fecha_pago.'</td>
                    <td style="text-align:right"></td>
                    ';
                    $tcubierto=$tcubierto+$row->pago;
                    $pagoAT=$pagoAT+$row->pago;
                } 
                else{
                    $cajaR.='
                    <td style="text-align:right"></td>
                    <td style="text-align:right;color:purple;">'.$row->fecha_pago.'</td>
                    <td style="text-align:right;color:purple;">'.number_format($row->pago,2).'</td>
                    ';
                    $tcubierto=$tcubierto+$row->pago;
                    $pagosAnteriores=$pagosAnteriores+$row->pago;
                }

            $cajaR.='<td style="text-align:right">'.number_format($row->pago,2).'</td>
                <td style="text-align:right">'.number_format($row->saldo_deudor,2).'</td>
                <td>';
                    foreach ($resTpo AS $row2) {
                        $cajaR.= $row2->descripcion.'<br>';
                    }
                
                $cajaR.='</td>
                <td>'.$status.'</td>
                <td>'.utf8_encode($nombreU[0]).'</td>
            </tr>';
            $contador++;
        }
        echo $cajaR;
    }


        #   Genera la tabla de reporte global paciente
        public function listaGeneraReportePaciente(){ 
            $usuarios = new Users();
            $respuestaRGC=$usuarios->obtenerReporteGlobalPaciente($_POST['fecha_inicial'],$_POST['fecha_final'],$_SESSION["id_sucursal"],$_POST['id_estudio']);
            $contador=1;
            foreach ($respuestaRGC AS $row) {

                $cajaR.='<tr>
                    <td>'.$contador.'</td>
                    <td>'.$row->consecutivo.'</td>
                    <td>'.$row->fecha_registro.'</td>
                    <td>'.utf8_encode($row->nombre_paciente).'</td>
                    <td>'.$row->expediente.'</td>
                    <td>'.$row->fecha_nac.'</td>
                    <td>'.$row->telefono.'</td>
                </tr>';
                $contador++;
            }
            echo $cajaR;
        }

#  Pago de empresas
    public function guardaPagoEC(){  
        $administracionM=new AdministracionModel();
        if(isset($_POST['empresa']) && $_POST['lista_ordenes']!=""){
             $res=$administracionM->guardaPagoECM();
             $administracionM->close();
             if($res=="success"){
                echo '
                <script>
                    window.location = "pago-empresa?msg=ok";
                </script>
                ';
             }
        }
        
    }



# Lista de oredenes en pago de empresas
    public function listaOrdenes(){
    	$administracionM=new AdministracionModel();
    	$res=$administracionM->listaOrdenesEmpresaM(); 
    	$respuesta['html']=""; 
        $totalDeuda=0;
  		foreach ($res AS $row=>$item) { 
            $totalDeuda=$totalDeuda+$item->total;
    		$respuesta['html'].='<tr>
    			<td>'.$item->consecutivo.'</td>
    			<td>'.$item->nombre_paciente.'</td>
    			<td>'.$item->fecha_registro.'</td>
    			<td>'.number_format($item->importe,2).'</td>
                <td>'.number_format($item->total_descuento,2).'</td>
                <td>'.number_format($item->total,2).'</td>
    			<td style="text-align:center;">  
	    			<button type="button" class="btn btn-sm btn-info rounded-circle m-1" title="Ver Estudios" onclick="verestudios('.$item->id_orden.',\''.$item->nombre_paciente.'\')">
	    			<i class="fas fa-eye"></i>
	    			</button>
    			</td>
    			<td style="text-align:center;"> 
    				<input type="checkbox" data-orden="'.$item->id_orden.'" data-descuento="'.$item->total_descuento.'" data-importe="'.$item->importe.'" data-monto="'.$item->total.'"  class="checkdinero">
    			</td>
    		</tr>';
    	}
        $respuesta['saldo']=$administracionM->obtenerCreditoEmpresaM()->saldo_credito_pagos;
        $respuesta['totalDeuda']=number_format($totalDeuda,2);
        $administracionM->close();
    	header('Content-Type: application/json; charset=utf-8');
        echo json_encode($respuesta);
    }

#-------------------------------------------
    public function listaEstudiosOrden(){
        $administracionM=new AdministracionModel();
        $res=$administracionM->listaEstudiosOrdenM();
        $respuesta['html']='<table class="table table-striped">
        <tr>
            <th>Estudio</th>
            <th>Costo</th>
            <th>Tipo Descuento</th>
            <th>Descuento</th>
            <th>Total</th>
        </tr>
        ';
        $totalImporte=0;
        $totalDescuento=0;
        $totalApagar=0;
        foreach ($res AS $row=>$item) {
            $formato_descuento="$".number_format($item->porcentaje_descuento,2);
            if($item->tipo_descuento=="porcentaje")
                $formato_descuento=$item->porcentaje_descuento."%";
            $totalImporte=$totalImporte+floatval($item->precio_neto_estudio);
            $totalDescuento=$totalDescuento+floatval($item->total_descuento);
            $totalApagar=$totalApagar+floatval($item->total);
            $respuesta['html'].='<tr>
                <td>'.$item->nombre_estudio.'</td>
                <td align="right">$'.number_format($item->precio_neto_estudio,2).'</td>
                <td align="left">'.$item->tipo_descuento.' = '.$formato_descuento.'</td>
                <td align="right">$'.number_format($item->total_descuento,2).'</td>
                <td align="right">$'.number_format($item->total,2).'</td>
                </tr>';
        }
        $respuesta['html'].='
        <tr>
            <th style="text-align:right;">TOTALES:</th>
            <th style="text-align:right;">$'.number_format($totalImporte,2).'</th>
            <th style="text-align:right;"></th>
            <th style="text-align:right;">$'.number_format($totalDescuento,2).'</th>
            <th style="text-align:right;">$'.number_format($totalApagar,2).'</th>
        </tr>
        </table>';
        $administracionM->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($respuesta);
    }

#----------------------------------------------------------
    public  function listaEmpresas(){
    	$administracionM=new AdministracionModel();
    	$res=$administracionM->listaEmpresasM($_SESSION["id_sucursal"]);
        echo '<option value="">Seleccione...</option>';
    	foreach ($res AS $row=>$item) {
    		echo '<option value="'.$item->id.'">'.$item->nombre.'</option>';
    	}
    	$administracionM->close();
    }

#Obtener las formas de pago del modelo catalogo general
    public function obtenerListaPagoM(){
        $catalogosM=new Catalogos();
        $res=$catalogosM->getFormaPago($_SESSION['id_sucursal']);
        foreach ($res AS $row=>$item) {
            echo '<option value="'.$item->id.'">'.$item->descripcion.'</option>';
        }
        $catalogosM->close();
    }

# Lista de ordenes con deuda empresa
    public function listaOrdenesEmpresa($fecha_inicio,$fecha_final){
        $administracionM=new AdministracionModel();
        $lista=$administracionM->listaOrdenesEmpresasM($fecha_inicio,$fecha_final);
        foreach ($lista AS $row=>$item) {
            if($item->total_descuento==null){
                $item->total_descuento=0;
                $item->total=$item->importe_ordenes;
            }
            echo '<tr> 
                <td>'.$item->nombre.'</td>
                <td>$'.number_format($item->importe_ordenes,2).'</td>
                <td>$'.number_format($item->total_descuento,2).'</td>
                <td>$'.number_format($item->total,2).'</td>
                <td>'.$item->numero_ordenes.' ordenes</td>
                <td>
                    <button id="ver_detalles_oe" data-empresa="'.$item->id.'" data-fechai="'.$fecha_inicio.'" data-fechaf="'.$fecha_final.'" class="btn btn-sm btn-info rounded-circle m-1 enviar_pago_datose" title="Ver Detalles" >
                    <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>';
        }
    }

# Lista de pagos de empresa
    public function listaPagosEmpresa(){ 
        $administracionM=new AdministracionModel();
        $res=$administracionM->ListaPagosCompletaM();  
        foreach($res as $row => $item){
            $consecutivos=$administracionM->consecutivosPago($item->lista_ordenes);
            $cadena="";
            $aimporte=explode(",", substr($item->lista_importes,0,-1)); 
            $adescuento=explode(",",substr($item->lista_descuentos,0,-1));
            $simporte=0;
            $sdescuento=0;
            for($j=0;$j<count($consecutivos);$j++){
                $cadena.=$consecutivos[$j].",";
                $simporte=$simporte+$aimporte[$j];
                $sdescuento=$sdescuento+$adescuento[$j];
            }
            
            echo'
            <tr>
                <td class="text-center">'.$item->nombre_empresa.'</td>
                <td class="text-center">'.$item->forma_pago.'</td>
                <td class="text-center">'.$item->numero_referencia.'</td>
                <td class="text-center">'.$item->fecha_pago.'</td>
                <td class="text-center">$'.number_format($simporte,2).'</td>
                <td class="text-center">$'.number_format($sdescuento,2).'</td>
                <td class="text-center">$'.number_format($item->total_pagado,2).'</td>
                <td class="text-center">$'.number_format($item->saldo_final,2).'</td>
                  <td class="text-center">
                     '.substr($cadena,0,-1).'
                 </td>
            
            </tr>
            ';
        }
        $administracionM->close();
    }

#============ Termina pago de empresas




}

new Administracion();