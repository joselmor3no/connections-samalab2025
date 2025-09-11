<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$codigo = $_REQUEST["codigo"];

require_once( $_SERVER["DOCUMENT_ROOT"] . '/libs/mpdf-5.7-php7-master/mpdf.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/admision/Cotizaciones.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

$cotizaciones = new Cotizaciones();
$id_orden = $cotizaciones->getIdOrden($codigo, $id_sucursal);
$paciente = $cotizaciones->getOrdenPaciente($id_orden)[0];
$edad = $paciente->edad . " " . ($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "años");
$orden_detalle = $cotizaciones->getOrdenDetalle($id_orden);
$indicaciones = $cotizaciones->getOrdenIndicaciones($id_orden);

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];

$pdf = new mPDF('UTF-8', array(215.9, 139.7), '8pt', '', 10, 10, 10, 10, 9, 9, 'P');
$pdf->SetTitle("COTIZACION-" . $codigo);


$head = '<table width="100%" style="font-size: 10pt;">
        <tr>
            <td width="20%" align="center"><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 100px" /> </td> 
            <td width="5%"></td>
            <td width="75%">
                <span style="font-weight: bold">Fecha Registro: </span>' . $paciente->fecha . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-weight: bold">No. Cotización: </span><u>' . $codigo . '</u><br>
                <span style="font-weight: bold">Paciente: </span>' . $paciente->paciente . ' <span style="font-weight: bold">Edad:</span> ' . $edad . ' <span style="font-weight: bold">Sexo:</span> ' . ($paciente->sexo == "Nino" ? "Niño (a)" : $paciente->sexo ) . '<br>
                <span style="font-weight: bold">Tel. Pac:</span> ' . $paciente->telefono . ' <span style="font-weight: bold">Fecha de Nacimiento: </span>' . $paciente->fecha_nac . '<br>
                <span style="font-weight: bold">Médico:</span> ' . $paciente->doctor . '<br>
                <span style="font-weight: bold">Sucursal:</span> ' . $sucursal->nombre . '<br>
            </td> 
        </tr>
    </table><br>';

$pdf->WriteHTML($head);

$html_estudios = '<table width="100%" border=0 CELLSPACING=0 >
       
        <tr style="background:#8CBF93; color:#fff">
            <th align="center" width="10%" style="color:#fff">Código</th> 
            <th align="center" style="color:#fff">Nombre del Estudio</th> 
            <th align="center" width="10%" style="color:#fff">Precio</th> 
            <th align="center" width="15%" style="color:#fff">%Descuento</th> 
            <th align="center" width="10%" style="color:#fff">Importe</th> 
        </tr>';

$ant = "";

$precio = 0;
$importe = 0;
$i = 0;
foreach ($orden_detalle AS $row) {
    $sig = $row->paquete;

    if ($row->paquete != "" && $sig != $ant) {
        $neto = costo_paquete($orden_detalle, $row->paquete);
        $publico = $row->precio_paquete;
        $descuento = $publico != 0 || $neto != 0 ? (1 - ($neto / $publico)) * 100 : 0;

        $precio += $publico;
        $importe += $neto;

        $html_estudios .= '<tr ' . ($i % 2 == 1 ? 'style="background:#eee;"' : "") . '>
                    <td>' . $row->paquete . '</td> 
                    <td>' . $row->nombre_paquete . '</td> 
                    <td align="center">$ ' . number_format($publico, 2) . '</td> 
                    <td align="center">' . number_format($descuento, 2) . '%</td> 
                    <td align="right">$ ' . number_format($neto, 2) . '</td> 
                </tr>';
        $i++;
    } else if ($row->paquete == "") {
        $descuento = $row->precio_publico != 0 || $row->precio_neto_estudio != 0 ? (1 - ($row->precio_neto_estudio / $row->precio_publico)) * 100 : 0;

        $precio += $row->precio_publico;
        $importe += $row->precio_neto_estudio;

        $html_estudios .= '<tr ' . ($i % 2 == 1 ? 'style="background:#eee;"' : "") . '>
                    <td>' . $row->no_estudio . '</td> 
                    <td>' . $row->nombre_estudio . '</td> 
                    <td align="center">$ ' . number_format($row->precio_publico, 2) . '</td> 
                    <td align="center">' . number_format($descuento, 2) . '%</td> 
                    <td align="right" >$ ' . number_format($row->precio_neto_estudio, 2) . '</td> 
                </tr>';
        $i++;
    }

    $ant = $sig;
}

$html_estudios .= '<tr>
                    <td></td> 
                    <td align="right" style="font-weight: bold">TOTALES&nbsp;&nbsp;</td> 
                    <td align="center" style="background:#8CBF93; color:#fff; font-weight: bold; font-size: 9pt;">$ ' . number_format($precio, 2) . '</td> 
                    <td align="center" style="background:#8CBF93; color:#fff; font-weight: bold"></td> 
                    <td align="right" style="background:#8CBF93; color:#fff; font-weight: bold; font-size: 9pt;">$ ' . number_format($importe, 2) . '</td> 
                </tr>';
$html_estudios .= '</table>';

$pdf->WriteHTML($html_estudios);


$html_indicaciones = '<br>
    <table width="100%" border=0>
        <tr>
            <td align="center" style="font-weight: bold" colspan="2">
               <h2>Indicaciones</h2>
            </td> 
        </tr>';
foreach ($indicaciones AS $row) {

    $html_indicaciones .= '
            <tr>
                <td style="font-weight: bold;">' . $row->nombre_estudio . '</td> 
            </tr>
             <tr>
                <td>' . $row->indicacion . '</td> 
            </tr>';
}

$html_indicaciones .= '
        </table>';
$pdf->WriteHTML($html_indicaciones);

$pdf->Output("COTIZACION-" . $codigo . ".pdf", 'I');

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