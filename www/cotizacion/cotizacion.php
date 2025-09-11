<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$codigo = $_REQUEST["codigo"];

require_once( $_SERVER["DOCUMENT_ROOT"] . '/libs/mpdf-5.7-php7-master/mpdf.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/admision/Cotizaciones.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

$pdf = new mPDF('UTF-8', array(80, 300), '8pt', '', 5, 5, 5, 5, 9, 9, 'P');
$pdf->SetTitle("COTIZACION-" . $codigo);

$cotizaciones = new Cotizaciones();
$id_orden = $cotizaciones->getIdOrden($codigo, $id_sucursal);
$paciente = $cotizaciones->getOrdenPaciente($id_orden)[0];
$edad = $paciente->edad . " " . ($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "años");
$orden_detalle = $cotizaciones->getOrdenDetalle($id_orden);
$indicaciones = $cotizaciones->getOrdenIndicaciones($id_orden);

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];

$html = '<table width="100%" >
        <tr>
            <td align="center"><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 100px" /> </td> 
        </tr>
        <tr>
            <td align="center" style="font-weight: bold">
                ' . $sucursal->cliente . '<br>
                ' . $sucursal->nombre . '<br>
                ' . $sucursal->direccion . '<br>
                ' . $sucursal->tel1 . ' / ' . $sucursal->tel2 . '<br>
            </td> 
        </tr>
    </table>';

$pdf->WriteHTML($html);

$html_paciente = '<br><table width="100%">

        <tr>
            <td align="center" style="font-weight: bold" colspan="2">
                <h2>Cotización <u>' . $codigo . '</u></h2>
            </td> 
        </tr>
        
        <tr>
             <td align="center" colspan="2">
                Fecha de registro: ' . $paciente->fecha . ' hrs
            </td> 
        </tr>
        
        <tr>
             <td align="center" colspan="2">
                Fecha de impresión:  ' . date("d/m/Y G:i") . ' hrs
            </td> 
        </tr>
        
    </table>
    <br><table width="100%">
        <tr>
            <td align="center" style="font-weight: bold" colspan="2">
               <h2>Datos del paciente</h2>
            </td> 
        </tr>
        
        <tr>
            <td align="center" colspan="2">
               ' . $paciente->paciente . '
            </td> 
        </tr>
        
        <tr>
             <td>
                <span style="font-weight: bold">Edad:</span> ' . $edad . '
            </td> 
            <td>
                <span style="font-weight: bold">Sexo:</span> ' . ($paciente->sexo == "Nino" ? "Niño (a)" : $paciente->sexo ) . '
            </td> 
        </tr>
        
        <tr>
            <td colspan="2">
               <span style="font-weight: bold">Médico: </span> ' . $paciente->doctor . '
            </td> 
        </tr>

    </table>';

$pdf->WriteHTML($html_paciente);

$html_estudios = '<br><table width="100%" border=0>
       
        <tr>
            <th align="left">Código</th> 
            <th align="left">Nombre del Estudio</th> 
            <th align="left">Precio</th> 
        </tr>
        <tr style=" margin-top:-60px;"><td colspan=3 style="border-top:1px solid"></td></tr>';

$ant = "";
foreach ($orden_detalle AS $row) {
    $sig = $row->paquete;
    if ($row->paquete != "" && $sig != $ant) {

        $html_estudios .= '<tr>
                    <td>' . $row->paquete . '</td> 
                    <td>' . $row->nombre_paquete . '</td> 
                    <td>$ ' . number_format(costo_paquete($orden_detalle, $row->paquete), 2) . '</td> 
                </tr>
                <tr style=" margin-top:-60px;"><td colspan=3 style="border-top:1px solid"></td></tr>';
    } else if ($row->paquete == "") {
        $html_estudios .= '<tr>
                    <td>' . $row->no_estudio . '</td> 
                    <td>' . $row->nombre_estudio . '</td> 
                    <td>$ ' . number_format($row->precio_neto_estudio, 2) . '</td> 
                </tr>
                <tr style=" margin-top:-60px;"><td colspan=3 style="border-top:1px solid"></td></tr>';
    }
    $ant = $sig;
}
$html_estudios .= '</table>';

$pdf->WriteHTML($html_estudios);

$html = '<br>
        <table width="100%" CELLSPACING=0>
            <tr>
                <td width="15%"></td> 
                <td align="center" style="font-weight: bold; border:1px solid"> Monto Total</td> 
                <td align="center"  style="border:1px solid">$ ' . number_format($paciente->importe, 2) . '</td> 
                <td width="15%"></td> 
            </tr>
        </table>';
$pdf->WriteHTML($html);

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
                <td style="font-weight: bold;">'.$row->nombre_estudio.'</td> 
            </tr>
             <tr>
                <td>'.$row->indicacion.'</td> 
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