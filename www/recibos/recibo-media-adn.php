<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/libs/mpdf-5.7-php7-master/mpdf.php');
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

$pdf = new mPDF('UTF-8', 'Letter', '8pt', '', 10, 10, 10, 10, 9, 9, 'P');
$pdf->SetTitle("RECIBO-" . $codigo);


for ($ite = 0; $ite < 2; $ite++) {

    $head = '<table width="100%" style="font-size: 10pt;">
        <tr>
            <td width="20%" align="center"><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 100px" /> </td> 
            <td width="5%"></td>
            <td width="75%">
                <span style="font-weight: bold">Fecha Registro: </span>' . $orden->fecha_orden . ' hrs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-weight: bold">Folio: </span><u>' . $codigo . '</u><br>
                <span style="font-weight: bold">Paciente: </span>' . $paciente->paciente . ' <span style="font-weight: bold">Edad:</span> ' . $edad . ' <span style="font-weight: bold">Sexo:</span> ' . ($paciente->sexo == "Nino" ? "Niño (a)" : $paciente->sexo ) . '<br>
                <span style="font-weight: bold">Tel. Pac:</span> ' . $paciente->telefono . ' <span style="font-weight: bold">Fecha de Nacimiento: </span>' . $paciente->fecha_nac . '<br>
                <span style="font-weight: bold">Médico:</span> ' . $paciente->doctor . '<br>
                <span style="font-weight: bold">Sucursal:</span> ' . $sucursal->nombre . '<br>
            </td> 
        </tr>
    </table><br>';

    $pdf->WriteHTML($head);


    $pdf->WriteHTML('<div>FECHA DE ENTREGA: <span style="font-weight: bold"> ' . $orden_detalle[0]->fecha_entrega . '</span> </div>');

    $html_estudios = '<table width="100%" border=0 CELLSPACING=0 >
       
        <tr style="background:#366BA0; color:#fff">
            <th align="center" width="10%" style="color:#fff">CLAVE</th> 
            <th align="center" style="color:#fff">ESTUDIO</th> 
            <th align="center" width="10%" style="color:#fff"></th> 
            <th align="center" width="15%" style="color:#fff"></th> 
            <th align="center" width="10%" style="color:#fff">TOTAL</th> 
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
                    <td colspan = "4">' . $row->nombre_paquete . '</td> 
                </tr>';
            $i++;
        } else if ($row->paquete == "") {
            $descuento = $row->precio_publico != 0 || $row->precio_neto_estudio != 0 ? (1 - ($row->precio_neto_estudio / $row->precio_publico)) * 100 : 0;

            $precio += $row->precio_publico;
            $importe += $row->precio_neto_estudio;

            $html_estudios .= '<tr ' . ($i % 2 == 1 ? 'style="background:#eee;"' : "") . '>
                    <td>' . $row->no_estudio . '</td> 
                    <td colspan = "4">' . $row->nombre_estudio . '</td>  
                </tr>';
            $i++;
        }

        $ant = $sig;
    }

    $html_estudios .= '<tr>
                    <td></td> 
                    <td align="right" colspan="3" style="font-weight: bold;">A CUENTA&nbsp;&nbsp;</td> 
                    <td align="right" style="background:#366BA0; color:#fff; font-weight: bold; font-size: 9pt;">$ ' . number_format($orden->importe - $orden->saldo_deudor, 2) . '</td> 
                </tr>';

    $html_estudios .= '<tr>
                    <td></td> 
                    <td align="right" colspan="3" style="font-weight: bold">RESTA&nbsp;&nbsp;</td> 
                    <td align="right" style="background:#366BA0; color:#fff; font-weight: bold; font-size: 9pt;">$ ' . number_format($orden->saldo_deudor, 2) . '</td> 
                </tr>';
    $html_estudios .= '</table>';

    $pdf->WriteHTML($html_estudios);

    $hml = '<table width="100%" >
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
    </table> <hr>';

    $pdf->WriteHTML($hml);

}

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