<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/libs/mpdf-5.7-php7-master/mpdf.php');

$pdf = new mPDF('UTF-8', 'A4', $formato->punto, 'dejavuserif');

$pdf->setAutoTopMargin = 'stretch';
$pdf->setAutoBottomMargin = 'stretch';

if ($formato->color_linea == "Negro") {
    $linea = "#000000";
} else if ($formato->color_linea == "Azul") {
    $linea = "#001BFF";
} else if ($formato->color_linea == "Rojo") {
    $linea = "#FF0000";
} else if ($formato->color_linea == "Rosa") {
    $linea = "FF00C1";
} else if ($formato->color_linea == "Gris") {
    $linea = "#616d71";
} else if ($formato->color_linea == "Verde") {
    $linea = "09A11B";
} else if ($formato->color_linea == "Amarillo") {
    $linea = "FFFF11";
}

foreach ($texto AS $estudio) {

    $edad = $paciente->edad . " " . ($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "AÑOS");

    $paciente = "
<table width='100%'>
    <tr>
        <td colspan='3'></td> 
        <td style='text-align: right;'>
        <font SIZE=2>
            Página {PAGENO} de {nbpg}
            </font>
        </td>
    </tr>
    <tr>
        <td colspan='3'><span style='font-weight: bold;'>PACIENTE: </span> " . $paciente->paciente . "</td>
        <td><span style='font-weight: bold;'>FECHA: </span>" . $paciente->fecha . "</td>
    </tr>
    <tr>
        <td colspan='3'><span style='font-weight: bold;'>EDAD/SEXO: </span>" . $edad . "</td>
        <td><span style='font-weight: bold;'>No. ORDEN: </span>" . $paciente->codigo . "</td>
        
    </tr>
    <tr>
        <td colspan='3'><span style='font-weight: bold;'>MÉDICO: </span>" . $paciente->doctor . "</td>
        <td><span style='font-weight: bold;'></span></td>
    </tr>
</table>
";

    if ($laboratorio == 1) {
        $pdf->SetHTMLHeader('
        <div style="height:' . ($formato->head * 37.7) . 'px">
            <table width="100%">
                <tr>
                    <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: ' . ($formato->head * 37.7) . 'px" /> </td> 
                    <td align="center" width=60% style="font-weight: bold">
                        ' . $sucursal->cliente . '<br>
                        ' . $sucursal->nombre . '<br>
                        ' . $sucursal->direccion . '<br>
                        ' . $sucursal->tel1 . ' / ' . $sucursal->tel2 . '<br>
                    </td> 
                    <td width=20%></td> 
                </tr>
            </table>
        </div>'
                . $paciente);

        $pdf->SetHTMLFooter(
                '<div style="height:' . ($formato->footer * 37.7) . 'px">
                <div style="text-align: right;">
                    <font size=2><br>
                    Página {PAGENO} de {nbpg}
                    </font>
                </div>
                <table width="100%">
                    <tr>
                        <td width=33% align="center">
                            <img src="../../images-sucursales/firmas/' . $sucursal->firma . '" style="height:150px; margin-bottom:-60px; margin-top:-35px;" />                       
                            <br>____________________________
                            <br><b>QUIMICO RESPONSABLE</b>
                            <br>' . $sucursal->quimico . '          
                            <br>' . $sucursal->cedula . '          
                        </td> 
                        <td width=33% align="center">
                            <img src="../../images-sucursales/firmas/' . $sucursal->firma_aux . '" style="height:150px; margin-bottom:-60px; margin-top:-35px;" />                       
                            <br>____________________________
                            <br><b>QUIMICO AUXILIAR</b>
                            <br>' . $sucursal->quimico_aux . '          
                            <br>' . $sucursal->cedula_aux . '          
                       </td> llll
                       <td width="35%" align="center"></td>
                    </tr>
                </table>
                <br>'
                . ($formato->horafecha == 1 ? '<font size=2>Fecha de impresión: ' . date("d/m/Y G:i") . ' hrs' : '') . '</font>
            </div>'
        );
    } else {
        $pdf->SetHTMLHeader('
        <div style="height:' . ($formato->head * 37.7) . 'px">

        </div>'
                . $paciente);
        $pdf->SetHTMLFooter(
                '<div style="height:' . ($formato->footer * 37.7) . 'px">
                <div style="text-align: right;">
                    <font size=2><br>
                    Página {PAGENO} de {nbpg}
                    </font>
                </div>
               <div style="height:' . (($formato->footer - 1) * 37.7) . 'px"></div>'
                . ($formato->horafecha == 1 ? '<font size=2>Fecha de impresión: ' . date("d/m/Y G:i") . ' hrs' : '') . '</font>
    </div>'
        );
    }


    $cuerpo = "
<h3 style='border-bottom:1px solid " . $linea . ";border-top:1px solid " . $linea . "; text-align: center;'>" . $estudio["estudio"] . "</h3>
<div>
" . $estudio["resultado"] . "
</div>
";
    $pdf->WriteHTML($cuerpo);

    if ($estudio["pagina"] == "true") {
        $pdf->AddPage();
        $pdf->WriteHTML($cabecera_resultado);
    }
}

$pdf->Output("../../reportes/formatos/" . $formato_texto);
