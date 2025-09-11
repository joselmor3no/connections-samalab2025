<?php

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

//$laboratorio = 0;
//Filtrar por bh e impresos
$estudio = [];
foreach ($estudios AS $row) {

    if ($row->id_tipo_reporte == 1 && ( ($row->impresion > 0 && $laboratorio == 0) || ( $row->impresion > 0 && $laboratorio == 1 && $row->imprimir == 1 ))) {
        $estudio = array(
            "estudio" => $row->nombre_estudio,
            "materia" => $row->materia,
            "metodo" => $row->metodo_utilizado,
            "pagina" => $row->pagina,
            "observaciones" => $row->observaciones_generales,
            "quimico" => $reportes->getQuimicoReporta($row->id_detalle_orden)[0],
            "componentes" => $controller->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo)
        );
    }
}

//sino es el paciente parar
if (count($estudios) == 0) {
    return;
}


$pdf = new mPDF('UTF-8', 'Letter', $formato->punto, strtolower($formato->fuente));
$pdf->SetTitle("REPORTE-BIOMETRIA-" . $codigo);

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


$edad = $paciente->edad . " " . strtoupper($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "AÑOS") . " / " . strtoupper($paciente->sexo != "Nino" ? $paciente->sexo : "NIÑO(A)");
$age = $paciente->edad . " " . ($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "Year") . " / " . ($paciente->sexo == "Nino" ? "Child" : $paciente->sexo == "Masculino" ? "Male" : "Female" );


if($id_sucursal == 151){
    $html_paciente = "
    <table width='100%' style='font-size:9pt' border=0>
        <tr>
            <td colspan='3'></td> 
            <td style='text-align: right;'>
            <font SIZE=2>
                " . ($paciente->ingles == 0 ? "Página" : "Page" ) . " {PAGENO} " . ($paciente->ingles == 0 ? "de" : "of" ) . " {nbpg}
                </font>
            </td>
        </tr>
        <tr>
            <td colspan='3'><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "ATENCIÓN" : "DOCTOR" ) . ": </span>" . $paciente->doctor . "</td>
            <td width=30% ><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "FECHA" : "DATE" ) . ": </span>" . $paciente->fecha . "</td>
        </tr>
        <tr>
            <td colspan='3'><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "PACIENTE" : "PATIENT" ) . ": </span> " . $paciente->paciente . "</td>
            <td ><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "MUESTRA" : "SAMPLE" ) . ": </span>" . $estudio["materia"] . "</td>
    
        </tr>
        <tr>
            <td colspan='3'><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "EDAD/SEXO" : "AGE/GENDER" ) . ": </span>" . ($paciente->ingles == 0 ? $edad : $age ) . "</td>
            <td><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "No. ORDEN" : "FOLIO" ) . ": </span>" . $paciente->codigo . "</td>
        </tr>
        <tr>
            <td colspan='3'><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "FECHA NACIMIENTO" : "BIRTH DATE" ) . ": </span>" . $paciente->fecha_nac . "</td>
            <td><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "FECHA REGISTRO" : "REGISTRATION DATE" ) . ": </span>" . $paciente->fecha_hora . "</td>
        </tr>

        
        ";

}else{
    $html_paciente = "
    <table width='100%' style='font-size:9pt' border=0>
        <tr>
            <td colspan='3'></td> 
            <td style='text-align: right;'>
            <font SIZE=2>
                " . ($paciente->ingles == 0 ? "Página" : "Page" ) . " {PAGENO} " . ($paciente->ingles == 0 ? "de" : "of" ) . " {nbpg}
                </font>
            </td>
        </tr>
        <tr>
            <td colspan='3'><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "ATENCIÓN" : "DOCTOR" ) . ": </span>" . $paciente->doctor . "</td>
            <td width=30% ><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "FECHA" : "DATE" ) . ": </span>" . $paciente->fecha . "</td>
        </tr>
        <tr>
            <td colspan='3'><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "PACIENTE" : "PATIENT" ) . ": </span> " . $paciente->paciente . "</td>
            <td ><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "MUESTRA" : "SAMPLE" ) . ": </span>" . $estudio["materia"] . "</td>
       
        </tr>
        <tr>
            <td colspan='3'><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "EDAD/SEXO" : "AGE/GENDER" ) . ": </span>" . ($paciente->ingles == 0 ? $edad : $age ) . "</td>
            <td><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "No. ORDEN" : "FOLIO" ) . ": </span>" . $paciente->codigo . "</td>
        </tr>";
}


if ($paciente->empresa != "") {
    $html_paciente .= " <tr>
        <td colspan='3'><span style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "EMPRESA" : "COMPANY" ) . ": </span>" . $paciente->empresa . "</td>
    </tr>";
}
$html_paciente .= "</table>";

if ($sucursal->quimico != "") {
    $quimico = ' 
            <img src="../../images-sucursales/firmas/' . $sucursal->firma . '" style="height:150px; margin-bottom:-60px; margin-top:-35px;" />                       
            <br>____________________________
            <br><b>QUIMICO RESPONSABLE</b>
            <br>' . $sucursal->quimico . '          
            <br>' . $sucursal->cedula . '        
            <br>';
}


if ($sucursal->quimico_aux != "") {
    $quimico_aux = ' 
            <img src="../../images-sucursales/firmas/' . $sucursal->firma_aux . '" style="height:150px; margin-bottom:-60px; margin-top:-35px;" />  
            <br>____________________________
            <br><b>' . ($paciente->ingles == 0 ? "QUIMICO AUXILIAR" : "AUXILIAR CHEMIST" ) . '</b>
            <br>' . $sucursal->quimico_aux . '          
            <br>' . $sucursal->cedula_aux . '           
            <br>';
}

$pdf->SetHTMLHeader('
    <div style="height:' . ($formato->head * 37.7) . 'px">
    </div>' . $html_paciente);

$pdf->SetHTMLFooter(
        '<div style="height:' . ($formato->footer * 37.7) . 'px">
                <div style="text-align: left;">
                   <font size=2>
                    *' . ($paciente->ingles == 0 ? "Fuera de intervalo biológico de referencia" : "Out of biological reference range" ) . ' 
                   <br>
                   <span style="font-weight: bold;">' . ($paciente->ingles == 0 ? "Método empleado" : "Method used" ) . ': ' . $estudio["metodo"] . '</span>
                   </font>
                   <br><br>'
                   . ($formato->horafecha == 1 ? '<font size=2>Fecha de impresión: ' . date("d/m/Y G:i") . ' hrs' : '') . '</font>
               </div>
               <table width="100%" border=0>
                   <tr>
                       <td width=33% align="center">' .
        $quimico_aux . '
                       </td>
                       <td width=33% align="center">' .
        $quimico
        . '</td> 
                      <td width="34%" align="right" style="font-weight: bold; "> <img width="100px"  style="margin-top:-95px; margin-right:-35px" src="data:image/png;base64,' . $qr . '"/></td>
                   </tr>
               </table>
               <br>
           </div>'
);


//membrete
if($id_sucursal == 143){
    $membrete = "texmelucan.png";
}else if($id_sucursal == 124 || $id_sucursal == 139){
    $membrete = "tehotihuacan.png";
}else if($id_sucursal == 149){
    $membrete = "zacualtipan.png";
}else if($id_sucursal == 123){
    $membrete = "otumba.png";
}else if($id_sucursal == 144){
    $membrete = "izcali.png";
}else if($id_sucursal == 142){
    $membrete = "santamaria.png";
}else if($id_sucursal == 141){
    $membrete = "calculalpan.png";
}else if($id_sucursal == 140){
    $membrete = "acolman.png";
}else if($id_sucursal == 151){
    $membrete = "ecatepec.png";
}else{
    $membrete = "membrete.png";
}

if ($paciente->mostrarlogo == 1) {

    $nombre_estudio = "<style>
    body {
    background-image: url($membrete);
    background-size: cover;
    background-repeat: no-repeat;
    background-image-resize: 6;

    }
    </style>";
}

$nombre_estudio .= "<h3 style='border-bottom:1px solid " . $linea . ";border-top:1px solid " . $linea . "; text-align: center;'>" . $estudio["estudio"] . "</h3>
<div>
" . $estudio["resultado"] . "
</div>
";
$pdf->WriteHTML($nombre_estudio);

$cabecera_resultado = "
    <div>
        <table width='100%'>
            <tr>
                <td align='center' width='33%'></td> 
                <td align='center' width='33%' style='font-weight: bold;'>" . ($paciente->ingles == 0 ? "RESULTADO" : "RESULT" ) . "</td> 
                <td align='center' width='33%'>" . ($paciente->ingles == 0 ? "INTERVALO DE REFERENCIA" : "REFERENCE INTERVAL" ) . "</td> 
            </tr>
        </table>
    </div>
";

$pdf->WriteHTML($cabecera_resultado);

$cuerpo = "
<div>
<table width='100%' CELLPADDING=1 CELLSPACING=1 BORDER=0>";

foreach ($estudio["componentes"] as $componente) {
    if ($componente["componente"]->imprimible == 1 && ( $componente["componente"]->resultado != "" || $componente["componente"]->id_cat_componente == NULL )) {
        $decimales = $componente["referencia"]->decimales;

        if ($componente["componente"]->id_cat_componente == NULL) {
            if ($formato->color == 1) {
                $color = "color:#466BF1";
            } else {
                $color = "color:#000000; font-weight: bold;";
            }
            $cuerpo .= "<tr>
                        <td colspan='8'>
                           <font style='$color'> " . $componente["componente"]->componente . "&nbsp;</font>
                        </td>
                    </tr>";
        } else if ($componente["componente"]->id_cat_componente == 1 || $componente["componente"]->id_cat_componente == 2) {
            if ($componente["componente"]->resultado < $componente["referencia"]->baja || $componente["componente"]->resultado > $componente["referencia"]->alta) {
                if ($formato->color == 1) {
                    $color = "red";
                } else {
                    $color = "black";
                }

                if ($formato->asterisco == 1) {
                    $marca = "<font color='$color'> * </font>";
                } else {
                    $marca = "";
                }
            } else if ($componente["componente"]->resultado == $componente["referencia"]->alta || $componente["componente"]->resultado == $componente["referencia"]->baja) {
                if ($formato->color == 1) {
                    $color = "blue";
                } else {
                    $color = "black";
                }
                $marca = "";
            } else {
                $color = "black";
                $marca = "";
            }

            //componente numerico y formula con absolutos

            if ($componente["componente"]->total_absoluto == 1 || $componente["componente"]->absoluto == 1) {

                if ($componente["componente"]->total_absoluto == 1) {
                    $absoluto = $componente["componente"]->resultado;
                    $cuerpo .= "<tr>
                        <td>
                            " . $componente["componente"]->componente . "
                        </td>
                        <td >
                        
                        </td>
                        <td align='center'>  <font style='font-weight: bold;' color='" . $color . "'>  " . number_format($componente["componente"]->resultado, $decimales) . "</font></td>
                        <td></td>";

                    $cuerpo .= "
                            <td align='right'>" . number_format($componente["referencia"]->baja, $decimales) . "</td>
                            <td align='center'> " . $formato->separador . " </td>
                            <td align='left'>" . number_format($componente["referencia"]->alta, $decimales) . "</td>
                            <td align='left'> " . $componente["referencia"]->unidad . "</td>";
                    $cuerpo .= "</tr>";

                    $cuerpo .= "
                        <tr>
                        <td></td>
                        <td></td>
                        <th align='center'> %</th>
                        <th>Absoluto</th>
                        <th colspan=4 align='center'>" . ($paciente->ingles == 0 ? "Rango Normal" : "Normal Range" ) . "</th>";
                } else {
                    $cuerpo .= "<tr>
                        <td>
                            " . $componente["componente"]->componente . "
                        </td>
                        <td>
                          
                        </td>
                        <td align='center'><font style='font-weight: bold;' color='" . $color . "'>  " . number_format($componente["componente"]->resultado,$decimales) . "</font></td>
                        <td align='center'> " . number_format(($absoluto * $componente["componente"]->resultado) / 100, 2) . "  </td>
                        <td align='right'>" . number_format($componente["referencia"]->baja, $decimales) . "</td>
                        <td align='center'> " . $formato->separador . " </td>
                        <td align='left'>" . number_format($componente["referencia"]->alta, $decimales) . "</td>
                        <td align='left'> " . $componente["referencia"]->unidad . "</td>
                        ";
                }

                $cuerpo .= "</tr>";
            } else { //componente numerico y formula
                $cuerpo .= "<tr>
                        <td width='35%'>
                            " . $componente["componente"]->componente . "
                        </td>
                        <td  width='8%'></td>
                        <td width='15%' align='center'> <font style='font-weight: bold;' color='" . $color . "'>  " .  number_format($componente["componente"]->resultado,$decimales) . "</font></td>
                        <td width='8%'> " . $marca . "</td>";

                if ($componente["tabla"] != NULL) {
                    $cuerpo .= "
                        <td colspan=4 width='34%'>
                            " . $componente["tabla"] . "
                        </td>";
                } else {
                    $cuerpo .= "
                            <td align='right'>" . number_format($componente["referencia"]->baja, $decimales) . "</td>
                            <td align='center' width='3%'> " . $formato->separador . " </td>
                            <td align='left'>" . number_format($componente["referencia"]->alta, $decimales) . "</td>
                            <td align='left' width='11%'> " . $componente["referencia"]->unidad . "</td>";
                }
                $cuerpo .= "</tr>";
            }
        } else if ($componente["componente"]->id_cat_componente == 3) {
            //componente lista
            $cuerpo .= "<tr>
                        <td>
                            " . $componente["componente"]->componente . "
                        </td>
                        <td style='font-weight: bold;' align='center'>
                            " . $componente["componente"]->resultado . "
                        </td>";
            if ($componente["tabla"] != NULL) {
                $cuerpo .= "
                        <td>
                            " . $componente["tabla"] . "
                        </td>
                    </tr>";
            } else {
                $referencia_imprimir = "";
                foreach ($componente["referencia"] as $lista) {
                    if ($lista->predeterminado == "1") {
                        $referencia_imprimir = $lista->elemento;
                    }
                }

                $cuerpo .= "
                        <td align='center'>
                            " . $referencia_imprimir . "
                        </td>
                    </tr>";
            }
        } else if ($componente["componente"]->id_cat_componente == 4) {

                $cuerpo .= "<tr>
                <td width='33%'>
                    " . $componente["componente"]->componente . "
                </td>
                <td align='" . ($componente["tabla"] != "" && $componente["componente"]->unidad != "" ? "center" : "left") . "' colspan='" . ($componente["tabla"] != "" && $componente["componente"]->unidad != "" ? "3" : "7") . "'>
                  <font style='font-weight: bold' color='" . $color . ";  '>  " . $componente["componente"]->resultado . "</font>
                </td>";

            if ($componente["tabla"] == "" || $componente["componente"]->unidad == "") {
                $cuerpo .= "</tr>";
            } else {
                if ($componente["tabla"] != NULL) {
                    $cuerpo .= "
                    <td  width='34%' colspan='4' align='center'>
                        " . $componente["tabla"] . "
                    </td>
                </tr>";
                } else {
                    $cuerpo .= "
                    <td align='center' width='34%' colspan='4'>
                    " . $componente["componente"]->unidad . "
                    </td>
                </tr>";
                }
            }
        }
        //si tiene leyenda
        if ($componente["componente"]->leyenda != "") {
            $cuerpo .= "<tr><td colspan=9>" . $componente["componente"]->leyenda . "</td></tr>";
        }

        //linea
        if ($componente["componente"]->linea == 1) {
            $cuerpo .= "<tr style=' margin-top:-60px;'><td colspan=9 style='border-top:1px solid " . $linea . "; '></td></tr>";
        }
    }
}

//observaciones
if ($estudio["observaciones"] != "") {
    $cuerpo .= "<tr><td colspan=8 style='padding-bottom: 15px; padding-top: 7px;font-size:" . ($formato->punto - 2) . "pt'>"
            . "<span style='font-weight: bold;'>Observación: </span>" . $estudio["observaciones"] 
            . "</td></tr>";
}

$cuerpo .= "
</table>
</div>
";
$pdf->WriteHTML($cuerpo);

if ($estudio["pagina"] == 1) {
    $pdf->AddPage();
    $pdf->WriteHTML($cabecera_resultado);
}
$reportes->close();
$pdf->Output("REPORTE-BIOMETRIA-" . $codigo . ".pdf", 'I');
