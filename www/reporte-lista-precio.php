<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$tipo = $_REQUEST["tipo"];
$no = $_REQUEST["no"];

require_once('libs/mpdf-5.7-php7-master/mpdf.php');
require_once('model/Catalogos.php');
require_once('model/catalogos/Listas.php');

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];

$listas = new Listas();
$lista = $listas->noLista($no, $id_sucursal)[0];
$id_lista = $lista->id;
$consecutivo_lista = $listas->consecutivoLista($id_sucursal);
$estudios_paquetes = $listas->getEstudiosPaquetesLista($id_lista, $id_sucursal);



$pdf = new mPDF('UTF-8', 'A4', '9pt', 'arial');

$pdf->setAutoTopMargin = 'stretch';
$pdf->setAutoBottomMargin = 'stretch';



$pdf->SetTitle("LISTA DE PRECIO " . $lista->no);


$pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%" border=0 CELLSPACING=0 >
            <tr>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>' . $lista->nombre . '</h3>
                </td> 
                <td width=20% align="center"><h3>Lista de Precio No. ' . $lista->no . '</h3></td> 
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
                <th>Descripción</th>
                <th>Paquete</th>
                <th>Precio Público</th>
                <th>Precio Neto</th>
            </tr>';

$ingresos = 0;
foreach ($estudios_paquetes AS $row) {

    $table .= "
            <tr>
                <td>" . $row->codigo . "</td>
                <td>" . $row->descripcion . "</td>
                <td> " . ($row->tipo == "Paquete" ? $row->codigo : "" ) . "</td>
                <td> $" . number_format($row->precio_publico, 2) . "</td>  
                <td> $" . number_format($row->precio_neto, 2) . "</td>  
            </tr>";
    $ingresos += $row->total;
}
$table .= '</table>';
$pdf->WriteHTML($table);

if ($tipo == "pdf") {
    $pdf->Output("reporte-lista-" . $lista->no . ".pdf", 'I');
} else {

    header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=reporte-lista-$lista->nombre.xls"); //Indica el nombre del archivo resultante
    header("Pragma: no-cache");
    header("Expires: 0");

    echo '<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
  
        <table  width="100%" border=0 CELLSPACING=0 >
            <tr>
                <td width=20%></td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>' . $lista->nombre . '</h3>
                </td> 
                <td width=20% align="center"><h3>Lista de Precio No. ' . $lista->no . '</h3></td> 
            </tr>
        </table>' .
    $table .
    '</html>';
    //echo ;
}

