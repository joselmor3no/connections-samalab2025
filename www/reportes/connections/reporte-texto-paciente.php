<?php

//date_default_timezone_set('America/Mexico_City');

session_start();
$id_sucursal = $_SESSION["id_sucursal"];

require_once($_SERVER["DOCUMENT_ROOT"] . '/libs/mpdf-5.7-php7-master/mpdf.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/laboratorio/Reportes.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controller/laboratorio/Reporte.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

$codigo = $_REQUEST["codigo"];
$expediente = $_REQUEST["expediente"];
$laboratorio = $_REQUEST["lab"];

$reportes = new Reportes();
$id_orden = $reportes->getIdOrden($codigo, $id_sucursal);
$estudios = $reportes->estudiosPacientesImprimir($id_orden, $expediente, $id_sucursal);
$paciente = $reportes->getOrdenPaciente($id_orden)[0];
$formato = $reportes->getFormatoLab($id_sucursal)[0];

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];

//QR paciente
$contenidoQR = "https://" . $_SERVER['SERVER_NAME'] . "/Pacientes/controller/Acceso?opc=expediente&user=" . $expediente;
$qr = $catalogos->QR_Generator($contenidoQR);

//$laboratorio = 1;
//Filtrar por estudios estandar e impresos
$texto = [];
foreach ($estudios AS $row) {

    if ($row->id_tipo_reporte == 5 && $row->impresion > 0) {
        $texto[] = array(
            "estudio" => $row->nombre_estudio,
            "materia" => $row->materia,
            "metodo" => $row->metodo_utilizado,
            "pagina" => $row->pagina,
            "quimico" => $reportes->getQuimicoReporta($row->id_detalle_orden)[0],
            "componentes" => $controller->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo),
            "resultado" => $reportes->getOrdenEstudioTexto($row->id_detalle_orden, $id_sucursal)[0]->resultado
        );
    }
}

//sino es el paciente parar
if (count($estudios) == 0) {
    return;
}


$pdf = new mPDF('UTF-8', 'A4', $formato->punto, 'dejavuserif');
$pdf->SetTitle("REPORTE-TEXTO-" . $codigo);

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

$i = 0;
foreach ($texto AS $estudio) {

    $edad = $paciente->edad . " " . ($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "AÑOS") . " / " . $paciente->sexo;

    $html_paciente = "
<table width='100%' style='font-size:9pt'>
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
        <td width=30% ><span style='font-weight: bold;'>FECHA: </span>" . $paciente->fecha . "</td>
    </tr>
    <tr>
        <td colspan='3'><span style='font-weight: bold;'>EDAD/SEXO: </span>" . $edad . "</td>
        <td><span style='font-weight: bold;'>MUESTRA: </span>" . $estudio["materia"] . "</td>
        
    </tr>
    <tr>
        <td colspan='3'><span style='font-weight: bold;'>MÉDICO: </span>" . $paciente->doctor . "</td>
        <td><span style='font-weight: bold;'>No. ORDEN: </span>" . $paciente->codigo . "</td>
    </tr>";

    if ($paciente->empresa != "") {
        $html_paciente .= " <tr>
        <td colspan='3'><span style='font-weight: bold;'>EMPRESA: </span>" . $paciente->empresa . "</td>
    </tr>";
    }
    $html_paciente .= "</table>";




    if ($laboratorio == 1) {
        $pdf->SetHTMLHeader('
    <div style="height:' . ($formato->head * 37.7) . 'px">
    </div>' . $html_paciente);

        //<img width="120px" src="data:image/png;base64,' . $qr . '"/>


        if ($sucursal->quimico != "") {
            $quimico = ' 
            <img src="../../images-sucursales/firmas/' . ($estudio["quimico"]->firma_digital != "" ? $estudio["quimico"]->firma_digital : "transparente.png") . '" style="height:150px; margin-bottom:-60px; margin-top:-35px;" />                       
            <br>____________________________
            <br><b>QUIMICO RESPONSABLE</b>
            <br>' . $estudio["quimico"]->nombre . '          
            <br>';
        }


        if ($sucursal->quimico_aux != "") {
            $quimico_aux = ' 
            <img src="../../images-sucursales/firmas/' . $sucursal->firma_aux . '" style="height:150px; margin-bottom:-60px; margin-top:-35px;" />                       
            <br>____________________________
            <br><b>QUIMICO AUXILIAR</b>
            <br>' . $sucursal->quimico_aux . '          
            <br>' . $sucursal->cedula_aux . '          
        ';
        }

        $pdf->SetHTMLFooter(
                '<div style="height:' . ($formato->footer * 37.7) . 'px">
                <div style="text-align: left;">
                   <font size=2>
                   * Fuera de intervalo biológico de referencia
                   <br>
                    <span style="font-weight: bold;">Método empleado: ' . $estudio["metodo"] . '</span>
                   </font>
               </div>
               <table width="100%">
                   <tr>
                       <td width=33% align="center">' .
                $quimico . '
                       </td>
                       <td width=33% align="center">' .
                $quimico_aux
                . '</td> 
                      <td width="34%" align="center" style="font-weight: bold;"><br><br><br><br><br><br>"La interpretación de resultados solo puede hacerla su médico"</td>
                   </tr>
               </table>
               <br>'
                . ($formato->horafecha == 1 ? '<font size=2>Fecha de impresión: ' . date("d/m/Y G:i") . ' hrs' : '') . '</font>
           </div>'
        );
    } else {
        $pdf->SetHTMLHeader('
    <div style="height:' . ($formato->head * 37.7) . 'px">
    </div>' . $html_paciente);

        $pdf->SetHTMLFooter(
                '<div style="height:' . ($formato->footer * 37.7) .
                'px">
        <div style="text-align: left;">
            <font size=2>
            * Fuera de intervalo biológico de referencia
            <br>
             <span style="font-weight: bold;">Método empleado: ' . $estudio["metodo"] . '</span>
            </font>
        </div>
       <div style="height:' . (($formato->footer - 1) * 37.7) . 'px"></div>'
                . ($formato->horafecha == 1 ? '<font size=2>Fecha de impresión: ' . date("d/m/Y G:i") . ' hrs' : '') . '</font>
    </div>'
        );
    }

    //membrete
    if ($laboratorio == 0) {
        $cuerpo = "<style>
    body {
    background-image: url(membrete.png);
    background-size: cover;
    background-repeat: no-repeat;
    background-image-resize: 6;

    }
    </style>";
    }


    $cuerpo .= "
    <h3 style='border-bottom:1px solid " . $linea . ";border-top:1px solid " . $linea . "; text-align: center;'>" . $estudio["estudio"] . "</h3>
    <div>
    " . $estudio["resultado"] . "
    </div>";
    $pdf->WriteHTML($cuerpo);


    if (count($texto) < $i) {
        //$pdf->AddPage();
    }

    $i++;
}

//var_dump($texto);
$pdf->Output("REPORTE-TEXTO-" . $codigo . ".pdf", 'I');
