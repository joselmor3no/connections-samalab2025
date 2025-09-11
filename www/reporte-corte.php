<?php

session_start();
$id_usuario = $_SESSION["id"];
$id_sucursal = $_SESSION["id_sucursal"];

setlocale(LC_TIME, 'es_ES.UTF-8');

//require_once('libs/mpdf-5.7-php7-master/mpdf.php');
require_once $_SERVER["DOCUMENT_ROOT"]  . '/vendor/autoload.php'; 
use Mpdf\Mpdf;

require_once('model/admision/Cortes.php');
require_once('model/Catalogos.php');

$tipo = $_REQUEST["tipo"];

$fecha = date("Y-m-d");
$fecha_text = strftime('%A, %d de %B del %Y ', strtotime($fecha));

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];


$pdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'default_font_size' => 9,
    'default_font' => 'arial'
]);


$pdf->setAutoTopMargin = 'stretch';
$pdf->setAutoBottomMargin = 'stretch';

if ($tipo == "detalle") {


    $cortes = new Cortes();
    $datos_ingresos = $cortes->getIngresosUltimoCorte();
    $datos_egresos = $cortes->getGastosUltimoCorte();
    $metodo = $cortes->getMetodoIngresosUltimoCorte();
    $no_corte = $cortes->getCorteUsuario($id_usuario)[0]->no_corte;

    $pdf->SetTitle("CORTE DETALLE " . $no_corte);

    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%" border=0 CELLSPACING=0 >
            <tr>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Corte / Detalle</h3>
                </td> 
                <td width=20% align="center"><h3>Corte No. <br>' . $no_corte . '</h3></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
            $fecha_text .
            '');

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 5px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <br>
    <h3 style="border-bottom:1px solid #000000 ; border-top:1px solid #000000; text-align: center;">REPORTE DE INGRESO</h3>
    <table width="100%" border=0 CELLSPACING=0 >
        <tr> 
        </tr>';

    $table .= '
            <tr>
                <th>Código</th>
                <th>Paciente</th>
                <th>Pago</th>
                <th>Forma de Pago</th>
                <th>Usuario</th>
                <th>Fecha</th>
            </tr>';

    $ingresos = 0;
    foreach ($datos_ingresos AS $row) {

        $table .= "
            <tr>
                <td>" . $row->codigo . "</td>
                <td>" . $row->paciente . "</td>
                <td style='text-align: right'>$ " . number_format($row->pago, 2) . "</td>
                <td>" . $row->forma_pago . "</td>
                <td>" . $row->usuario . "</td>  
                <td>" . $row->fecha . "</td>
            </tr>";
        $ingresos += $row->pago;
    }
    $table .= "
            <tr>
                <td colspan=2 style='text-align: right'>TOTAL</td>
                <td style='text-align: right'><b>$ " . number_format($ingresos, 2) . "</b></td>
            </tr>";
    $table .= '</table>';
    $pdf->WriteHTML($table);


    $table = '
    <br>
    <h3 style="border-bottom:1px solid #000000 ;border-top:1px solid #000000; text-align: center;">INGRESOS POR METODO DE PAGO</h3>
    <table width="50%" border=0 CELLSPACING=0 >
        <tr> 
        </tr>';

    $table .= '
            <tr>
                <th>Metodo de pago</th>
                <th>Total</th>
            </tr>';

    foreach ($metodo AS $row) {

        $table .= "
            <tr>
                <td>" . $row->metodo . "</td>
                <td>$ " . number_format($row->total, 2) . "</td>
            </tr>";
    }
    $table .= '</table>';
    $pdf->WriteHTML($table);

    $table = '
    <br>
    <h3 style="border-bottom:1px solid #000000 ;border-top:1px solid #000000; text-align: center;">REPORTE DE GASTOS</h3>
    <table width="100%" border=0 CELLSPACING=0 >
        <tr> 
        </tr>';

    $table .= '
            <tr>
                <th>Concepto</th>
                <th>Cantidad</th>
                <th>Observación</th>
                <th>Usuario</th>
                <th>Fecha</th>
            </tr>';

    $gastos = 0;
    foreach ($datos_egresos AS $row) {

        $table .= "
            <tr>
                <td>" . $row->concepto . "</td>
                <td style='text-align: right'>$ " . number_format($row->importe, 2) . "</td>
                <td>" . $row->aclaracion . "</td>
                <td>" . $row->usuario . "</td>  
                <td>" . $row->fecha . "</td>
            </tr>";
        $gastos += $row->importe;
    }

    $table .= "
            <tr>
                <td style='text-align: right'>TOTAL</td>
                <td style='text-align: right'><b>$ " . number_format($gastos, 2) . "</b></td>
            </tr>";
    $table .= '</table>';
    $pdf->WriteHTML($table);

    $table = '

    <table width="100%" border=0 CELLSPACING=0  >
        <tr> 
        </tr>';

    $table .= '
            <tr>
                <th width="50%"></th>
                <th width="15%">Ingresos</th>
                <th width="15%">Gastos</th>
                <th width="15%">Total</th>
                <th></th>
                
            </tr>
            <tr>
                <th style ="border-top:3px solid #000000; text-align: right">Totales</th>
                <td style ="border-top:3px solid #000000;">' . number_format($ingresos, 2) . '</td>
                <td style ="border-top:3px solid #000000;">' . number_format($gastos, 2) . '</td>
                <td style ="border-top:3px solid #000000;">' . number_format($ingresos - $gastos, 2) . '</td>
                <td style ="border-top:3px solid #000000;"></td>
                
            </tr>';

    $table .= '</table>';
    $pdf->WriteHTML($table);


    $pdf->Output("detalle-corte-" . $no_corte . ".pdf", 'I');
}

if ($tipo == "departamento") {


    $cortes = new Cortes();
    $no_corte = $cortes->getCorteUsuario($id_usuario)[0]->no_corte;
    $departamentos = $cortes->getDepartamentosIngresosUltimoCorte();


    $pdf->SetTitle("CORTE DEPARTAMENTO " . $no_corte);


    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%" border=0 CELLSPACING=0 >
            <tr>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Corte por Departamentos</h3>
                </td> 
                <td width=20% align="center"><h3>Corte No. <br>' . $no_corte . '</h3></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
            $fecha_text .
            '');

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 5px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <br>
    <table width="100%" border=0 CELLSPACING=0 >
        <tr> 
        </tr>';

    $table .= '
            <tr>
                <th>Código</th>
                <th>Departamento</th>
                <th>Venta</th>
            </tr>';

    $ingresos = 0;
    foreach ($departamentos AS $row) {

        $table .= "
            <tr>
                <td>" . $row->codigo . "</td>
                <td>" . $row->departamento . "</td>
                <td>$ " . number_format($row->total, 2) . "</td>
            </tr>";
        $ingresos += $row->total;
    }
    $table .= '</table>';
    $pdf->WriteHTML($table);

    $pdf->Output("corte-departamento-" . $no_corte . ".pdf", 'I');
}