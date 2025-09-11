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
            <font size=2>
             * Fuera de intervalo biológico de referencia
                <br>
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
        '<div style="height:' . ($formato->footer * 37.7) .
            'px">
        <div style="text-align: right;">
            <font size=2>
             * Fuera de intervalo biológico de referencia
                <br>
            Página {PAGENO} de {nbpg}
            </font>
        </div>
       <div style="height:' . (($formato->footer - 1) * 37.7) . 'px"></div>'
            . ($formato->horafecha == 1 ? '<font size=2>Fecha de impresión: ' . date("d/m/Y G:i") . ' hrs' : '') . '</font>
    </div>'
    );
}




$nombre_estudio = "
<h3 style='border-bottom:1px solid " . $linea . ";border-top:1px solid " . $linea . "; text-align: center;'>" . $estudio["estudio"] . "</h3>
<div>
" . $estudio["resultado"] . "
</div>
";
$pdf->WriteHTML($nombre_estudio);

$cabecera_resultado = "
    <div>
        <table width='100%' style='border-bottom:1px solid " . $linea . "'>
            <tr>
                <td align='center' width='33%'>NOMBRE DE EXAMEN</td> 
                <td align='center' width='33%' style='font-weight: bold;'>RESULTADO</td> 
                <td align='center' width='33%'>INTERVALO DE REFERENCIA</td> 
            </tr>
        </table>
    </div><br>
";
$cabecera_resultado = "
    <div>
        <table width='100%'>
            <tr>
                <td align='center' width='33%'></td> 
                <td align='center' width='33%' style='font-weight: bold;'>RESULTADO</td> 
                <td align='center' width='33%'>INTERVALO DE REFERENCIA</td> 
            </tr>
        </table>
    </div>
";

$pdf->WriteHTML($cabecera_resultado);

$cuerpo = "
<div>
<table width='100%' CELLPADDING=0 CELLSPACING=1>";

foreach ($estudio["componentes"] as $componente) {
    if ($componente["componente"]->imprimible == 1) {


        if ($componente["componente"]->id_cat_componente == NULL) {
            $cuerpo .= "<tr>
                        <td colspan='6'>
                           <font style='color:#466BF1'> " . $componente["componente"]->componente . "</font>
                        </td>
                    </tr>";
        } else if ($componente["componente"]->id_cat_componente == 1) {
            if ($componente["componente"]->resultado < $componente["referencia"]->baja || $componente["componente"]->resultado > $componente["referencia"]->alta) {
                if ($formato->color == 1) {
                    $color = "red";
                } else {
                    $color = "black";
                }

                if ($formato->asterisco == 1) {
                    $marca = "<font color='red'> * </font>";
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
            //componente numerico
            $cuerpo .= "<tr>
                        <td width='33%'>
                            " . $componente["componente"]->componente . "
                        </td>
                        <td align='center' width='33%'>
                          <font color='" . $color . "'>  " . $componente["componente"]->resultado . "</font>" . $marca . "
                        </td>";
            if ($componente["tabla"] != NULL) {
                $cuerpo .= "
                        <td  width='34%' colspan='4'>
                            " . $componente["tabla"] . "
                        </td>
                    </tr>";
            } else {
                $cuerpo .= "
                        <td align='right' width='10%'>" . $componente["referencia"]->baja . "</td>
                                <td align='center' width='3%'> " . $formato->separador . " </td>
                                <td align='left' width='10%'>" . $componente["referencia"]->alta . "</td>
                                <td align='left' width='11%'> " . $componente["referencia"]->unidad . "</td>
                    </tr>";
            }
        } else if ($componente["componente"]->id_cat_componente == 2) {
            //componente formula
            if ($componente["componente"]->resultado < $componente["referencia"]->baja || $componente["componente"]->resultado > $componente["referencia"]->alta) {
                if ($formato->color == 1) {
                    $color = "red";
                } else {
                    $color = "black";
                }
                if ($formato->asterisco == 1) {
                    $marca = "<font color='red'> * </font>";
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
            $cuerpo .= "<tr>
                        <td width='33%'>
                            " . $componente["componente"]->componente . "
                        </td>
                        <td align='center' width='33%'>
                           <font color='" . $color . "'>  " . $componente["componente"]->resultado . "</font>" . $marca . "
                        </td>";
            if ($componente["tabla"] != NULL) {
                $cuerpo .= "
                        <td width='34%' colspan='4'>
                            " . $componente["tabla"] . "
                        </td>
                    </tr>";
            } else {
                $cuerpo .= "
                        <td align='right' width='10%'>" . $componente["referencia"]->baja . "</td>
                                <td align='center' width='3%'> " . $formato->separador . " </td>
                                <td align='left' width='10%'>" . $componente["referencia"]->alta . "</td>
                                <td align='left' width='11%'> " . $componente["referencia"]->unidad . "</td>
                    </tr>";
            }
        } else if ($componente["componente"]->id_cat_componente == 3) {
            //componente lista
            $cuerpo .= "<tr>
                        <td width='33%'>
                            " . $componente["componente"]->componente . "
                        </td>
                        <td align='center' width='33%'>
                            " . $componente["componente"]->resultado . "
                        </td>";
            if ($componente["tabla"] != NULL) {
                $cuerpo .= "
                        <td width='34%' colspan='4'>
                            " . $componente["tabla"] . "
                        </td>
                    </tr>";
            } else {
                foreach ($componente["referencia"] as $lista) {
                    if ($lista->predeterminado == "1") {
                        $referencia_imprimir = $lista->elemento;
                    }
                }

                $cuerpo .= "
                        <td width='34%' colspan='4'>
                            " . $referencia_imprimir . "
                        </td>
                    </tr>";
            }
        } else if ($componente["componente"]->id_cat_componente == 4) {
            //componente texto  <td  align='justify' width='33%'>
            $cuerpo .= "<tr>
                        <td width='33%'>
                            " . $componente["componente"]->componente . "
                        </td>
                        <td  align='center' width='33%'>
                            " . $componente["componente"]->resultado . "
                        </td>
                        <td width='34%' colspan='4'>
                        " . $componente["componente"]->unidad . "
                        </td>
                </tr>";
        }
        //si tiene leyenda
        if ($componente["componente"]->leyenda != "") {
            $cuerpo .= "<tr><td colspan=6>" . $componente["componente"]->leyenda . "</td></tr>";
        }

        //linea
        if ($componente["componente"]->linea == 1) {
            $cuerpo .= "<tr style=' margin-top:-60px;'><td colspan=6 style='border-top:1px solid " . $linea . "; '></td></tr>";
        }
    }
}
$cuerpo .= "
</table>
</div>
";
$pdf->WriteHTML($cuerpo);

if ($estudio["pagina"] == "true") {
    $pdf->AddPage();
    $pdf->WriteHTML($cabecera_resultado);
}


$pdf->Output("../../reportes/formatos/" . $formato_examen_orina);