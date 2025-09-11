<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$codigo = $_REQUEST["codigo"];

require_once $_SERVER["DOCUMENT_ROOT"]  . '/vendor/autoload.php'; 
use Mpdf\Mpdf;
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/laboratorio/Reportes.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/admision/Pacientes.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/admision/Pagos.php');

$reporte = new Reportes();
$id_orden = $reporte->getIdOrden($codigo, $id_sucursal);
$paciente = $reporte->getOrdenPaciente($id_orden)[0];
$edad = $paciente->edad . " " . ($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "años");

$pacientes = new Pacientes();
$datos = $pacientes->getOrden($id_orden);
$orden = $datos["orden"][0];
$orden_detalle = $datos["detalle"];

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];

//QR paciente
$contenidoQR = "https://" . $_SERVER['SERVER_NAME'] . "/Pacientes/controller/Acceso?opc=expediente&user=" . $paciente->expediente;
$qr = $catalogos->QR_Generator($contenidoQR);

$pagos = new Pagos();
$pago = $pagos->getPagos($codigo, $id_sucursal)[0];

$mpdfConfig = [
    'mode' => 'utf-8',
    'format' => 'Letter', // Tamaño de página, puedes ajustarlo según tus necesidades
    'default_font_size' => 8,
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 10,
    'margin_bottom' => 10,
    'margin_header' => 9,
    'margin_footer' => 9,
    'orientation' => 'P' // 'P' para retrato, 'L' para paisaje
];

// Crear instancia de mPDF
$pdf = new Mpdf($mpdfConfig);
$pdf->SetTitle("RECIBO-" . $codigo);

if($orden->id_doctor==null)
    $doctorc=$paciente->doctor;
else
    $doctorc=$orden->nombre_cdoctor;
if($orden->descripcion==null)
    $pagod="";
else
    $pagod=$orden->descripcion;
$head = '<table width="100%" style="font-size: 10pt;">
        <tr>
            <td width="20%" align="center"><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 100px" /> </td> 
            <td width="5%"></td>
            <td width="75%">
                <span style="font-weight: bold">Fecha Registro: </span>' . $orden->fecha_orden . ' hrs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-weight: bold">Folio: </span><u>' . $codigo . '</u><br>
                <span style="font-weight: bold">Paciente: </span>' . $paciente->paciente . ' <span style="font-weight: bold">Edad:</span> ' . $edad . ' <span style="font-weight: bold">Sexo:</span> ' . ($paciente->sexo == "Nino" ? "Niño (a)" : $paciente->sexo ) . '<br>
                <span style="font-weight: bold">Tel. Pac:</span> ' . $paciente->telefono . ' <span style="font-weight: bold">Fecha de Nacimiento: </span>' . $paciente->fecha_nac . '<br>';
            if($paciente->empresa!=null && $paciente->empresa!="")
                $head.=' <span style="font-weight: bold">Empresa:</span>'.$paciente->empresa.'<br>';
            $head .='<span style="font-weight: bold">Médico:</span> ' . $doctorc . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">Atendio:</span>'.$orden->nombre_usuario.'<br>
                <span style="font-weight: bold">Sucursal:</span> ' . $sucursal->nombre . '<br>
                <span style="font-weight: bold">Forma de pago:</span> ' . $pagod . '<br>
            </td> 
        </tr>
    </table><br>';

$pdf->WriteHTML($head);


$html_estudios ='<div>FECHA DE ENTREGA: <span style="font-weight: bold"> ' . $orden_detalle[0]->fecha_entrega . '</span> </div>';

$html_estudios .= '<div style="height:180px;"><table width="100%" border=0 CELLSPACING=0 >
       
        <tr style="background:#366BA0; color:#fff">
            <th align="center" width="10%" style="color:#fff">CLAVE</th> 
            <th align="center" style="color:#fff">ESTUDIO</th> 
            <th align="center" width="10%" style="color:#fff">PRECIO</th> 
            <th align="center" width="15%" style="color:#fff">% DESCUENTO</th> 
            <th align="center" width="15%" style="color:#fff">$ DESCUENTO</th> 
            <th align="center" width="10%" style="color:#fff">IMPORTE</th> 
        </tr>';

$ant = "";

$precio = 0;
$importe = 0;
$mdescuento=0;
$i = 0;
$orden_detalle=array_reverse($orden_detalle);
foreach ($orden_detalle AS $row) {
    $sig = $row->paquete;

    if ($row->paquete != "" && $sig != $ant) {
        $neto = costo_paquete($orden_detalle, $row->paquete);
        $publico = $row->precio_paquete;
        $descuento = $publico != 0 || $neto != 0 ? (1 - ($neto / $publico)) * 100 : 0;
        if($descuento<0)
            $descuento=0;
        $monto_descuento=$publico-$neto;

        $precio += $publico;
        $importe += $neto;
        $mdescuento+=$monto_descuento;
        $html_estudios .= '<tr ' . ($i % 2 == 1 ? 'style="background:#eee;"' : "") . '>
                    <td>' . $row->paquete . '</td> 
                    <td>' . $row->nombre_paquete . '</td> 
                    <td align="right">$ ' . number_format($publico, 2) . '</td> 
                    <td align="right">' . number_format($descuento, 2) . '%</td> 
                    <td align="right">$' . number_format($monto_descuento, 2) . '</td> 
                    <td align="right">$ ' . number_format($neto, 2) . '</td> 
                </tr>';
        $i++;
    } else if ($row->paquete == "") {

        $descuento = $row->precio_publico != 0 || $row->precio_neto_estudio != 0 ? (1 - ($row->precio_neto_estudio / $row->precio_publico)) * 100 : 0;
        if($descuento<0)
            $descuento=0;

        $monto_descuento=$row->precio_publico-$row->precio_neto_estudio;

        $mdescuento+=$monto_descuento;
        $precio += $row->precio_publico;
        $importe += $row->precio_neto_estudio;

        $html_estudios .= '<tr ' . ($i % 2 == 1 ? 'style="background:#eee;"' : "") . '>
                    <td>' . $row->no_estudio . '</td> 
                    <td>' . $row->nombre_estudio . '</td> 
                    <td align="right">$ ' . number_format($row->precio_publico, 2) . '</td> 
                    <td align="right">' . number_format($descuento, 2) . '%</td> 
                    <td align="right">$' . number_format($monto_descuento, 2) . '</td> 
                    <td align="right" >$ ' . number_format($row->precio_neto_estudio, 2) . '</td> 
                </tr>';
        $i++;
    }

    $ant = $sig;
}

$html_estudios .= '<tr>
                    <td></td> 
                    <td align="right" style="font-weight: bold">TOTALES&nbsp;&nbsp;</td> 
                    <td align="right" style="background:#366BA0; color:#fff; font-weight: bold; font-size: 9pt;">$ ' . number_format($precio, 2) . '</td> 
                    <td align="right" style="background:#366BA0; color:#fff; font-weight: bold"></td> 
                    <td align="right" style="background:#366BA0; color:#fff; font-weight: bold">$ ' . number_format($mdescuento, 2) . '</td> 
                    <td align="right" style="background:#366BA0; color:#fff; font-weight: bold; font-size: 9pt;">$ ' . number_format($importe, 2) . '</td> 
                </tr>';

$html_estudios .= '<tr>
                    <td></td> 
                    <td align="right" colspan="4" style="font-weight: bold;">A CUENTA&nbsp;&nbsp;</td> 
                    <td align="right" style="background:#366BA0; color:#fff; font-weight: bold; font-size: 9pt;">$ ' . number_format($orden->importe - $orden->saldo_deudor, 2) . '</td> 
                </tr>';

$html_estudios .= '<tr>
                    <td></td> 
                    <td align="right" colspan="4" style="font-weight: bold">RESTA&nbsp;&nbsp;</td> 
                    <td align="right" style="background:#366BA0; color:#fff; font-weight: bold; font-size: 9pt;">$ ' . number_format($orden->saldo_deudor, 2) . '</td> 
                </tr>';
$html_estudios .= '</table></div>';

//$pdf->WriteHTML($html_estudios);

$html_estudios .='<table width="100%" >
        <tr>
            <td width="20%" align="center">
                <p align="center">
                    <span>Folio de descarga:</span><br>
                    <span style="font-weight: bold">' . $paciente->expediente . '</span>
                    <img width="120px" src="data:image/png;base64,' . $qr . '"/>     
                </p>            
            </td> 
            <td width="80%" align="center" style="">
                Descarga de resultados: <span style="font-weight: bold">' . $_SERVER["SERVER_NAME"] . '/Pacientes</span><br>
                Telefono de la sucursal: <span style="font-weight: bold">' . $sucursal->tel1 . '</span>
            </td> 
        </tr>
    </table> ';

$pdf->WriteHTML($html_estudios);
$head = '<table width="100%" style="font-size: 10pt;">
        <tr>
            <td width="20%" align="center"><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 100px" /> </td> 
            <td width="5%"></td>
            <td width="75%">
                <span style="font-weight: bold">Fecha Registro: </span>' . $orden->fecha_orden . ' hrs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-weight: bold">Folio: </span><u>' . $codigo . '</u><br>
                <span style="font-weight: bold">Paciente: </span>' . $paciente->paciente . ' <span style="font-weight: bold">Edad:</span> ' . $edad . ' <span style="font-weight: bold">Sexo:</span> ' . ($paciente->sexo == "Nino" ? "Niño (a)" : $paciente->sexo ) . '<br>
                <span style="font-weight: bold">Tel. Pac:</span> ' . $paciente->telefono . ' <span style="font-weight: bold">Fecha de Nacimiento: </span>' . $paciente->fecha_nac . '<br>';
            if($paciente->empresa!=null && $paciente->empresa!="")
                $head.=' <span style="font-weight: bold">Empresa:</span>'.$paciente->empresa.'<br>';
            $head .='<span style="font-weight: bold">Médico:</span> ' . $doctorc . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">Atendio:</span>'.$orden->nombre_usuario.'<br>
                <span style="font-weight: bold">Sucursal:</span> ' . $sucursal->nombre . '<br>
                <span style="font-weight: bold">Forma de pago:</span> ' . $pagod . '<br>
            </td> 
        </tr>
    </table><br>';

$pdf->WriteHTML($head);
$pdf->WriteHTML($html_estudios);

$pdf->Output("RECIBO-" . $codigo . ".pdf", 'I');

function costo_paquete($estudios, $paquete) {
    $total = 0;
    foreach ($estudios AS $row) {
        if ($row->paquete == $paquete) {
            $total += $row->precio_neto_estudio;
        }
    }
    return $total;
}

?>