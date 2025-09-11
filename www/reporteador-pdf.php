<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];

setlocale(LC_TIME, 'es_ES.UTF-8');

require_once('libs/mpdf-5.7-php7-master/mpdf.php');
require_once('model/administracion/Reportes.php');
require_once('model/Catalogos.php');

$siglas = $_REQUEST["siglas"];
$fecha_inicial = $_REQUEST["fecha_inicial"];
$fecha_final = $_REQUEST["fecha_final"];
$palabra = $_REQUEST["palabra"];

$fecha = date("Y-m-d");
$fecha_text = strftime('%A, %d de %B del %Y ', strtotime($fecha));

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];

if ($siglas == "rx33") {
    $pdf = new mPDF('', '', 0, '', 15, 15, 15, 15, 8, 8);
}
else{
    $pdf = new mPDF('UTF-8', 'A4', '9pt', 'arial');
}

if ($siglas == "rx34") {
    $pdf = new mPDF('', '', 0, '', 15, 15, 15, 15, 8, 8);
}
else{
    $pdf = new mPDF('UTF-8', 'A4', '9pt', 'arial');
}
$pdf->SetTitle("REPORTE");

$pdf->setAutoTopMargin = 'stretch';
$pdf->setAutoBottomMargin = 'stretch';


if ($siglas == "rx1") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Totales empresas de credito</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r1($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr> 
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivareal=$row->total-$row->iva;
        if($tipo!=$row->tipo){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th></th>
            <th align="center">Totales por tipo de empresa: </th>
            <th align="left">$'. number_format($suma,2).'</th>
            <th align="left">$'. number_format($suma2,2).'</th>
            <th align="left">$'. number_format($suma3,2).'</th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }

            $table .= "
            <tr>
            <td  colspan='6'>Tipo de empresa: $row->tipo</td>
            </tr>
            ";

            $table.='
            <tr>
                <th align="left"># Orden</th> 
                <th align="left">Empresa</th> 
                <th align="left">Subtotal</th> 
                <th align="left">IVA</th> 
                <th align="left">Total</th> 
            </tr>';
            $tipo=$row->tipo;
        }

        $suma=$suma+$row->iva;
        $suma2=$suma2+$ivareal;
        $suma3=$suma3+$row->total;

      

       
        $table .= '
        <tr>
            <td>'.$row->id.'</td> 
            <td>'.$row->nombre.'</td> 
            <td>$' . number_format($row->iva, 2) .'</td> 
             <td>$' . number_format($ivareal, 2) .'</td> 
             <td>$'.number_format($row->total, 2) .'</td> 
        </tr>';

        $subtotal += $row->total;
        $iva += $ivareal;
        $total += ($row->subtotal + $row->iva);
    }
    $table .='
    <tr>
    <th></th> 
    <th align="center">Totales por tipo de empresa: </th>
    <th align="left">$'. number_format($suma,2).'</th>
    <th  align="left">$'. number_format($suma2,2).'</th>
    <th align="left" > $'. number_format($suma3,2).'</th>
    </tr>'; 

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th align="left">$' . number_format($total, 2) . '</th> 
            <th align="left">$' . number_format($iva, 2) . '</th> 
            <th align="left">$' . number_format($subtotal, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("totales_empresas_credito.pdf", 'I');
}

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx2") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Pacientes de empresas de credito</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


     $reportes = new Reportes();
    $data = $reportes->r2($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
         
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivas=$row->importe /1.16;
        $ivareal=$row->importe- $ivas;
        if($tipo!=$row->id_empresa){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th></th>
            <th align="center">Totales por empresa: </th>
            <th align="left">$'. number_format($suma,2).'</th>
            <th align="left">$'. number_format($suma2,2).'</th>
            <th align="left">$'. number_format($suma3,2).'</th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }


         

            $table .= "
            <tr>
            <td  colspan='5'>Empresa: $row->id_empresa $row->nombre </td>
            
            </tr>
            ";
            $table .='
            <tr>
            <th align="left">Orden #</th> 
            <th align="left">Nombre Paciente</th> 
            <th align="left">Subtotal</th> 
            <th align="left">IVA</th> 
            <th align="left">Total</th> 
            
            </tr>'; 
            



            $tipo=$row->id_empresa;
        }

        $suma=$suma+$ivas;
        $suma2=$suma2+$ivareal;
        $suma3=$suma3+$row->importe;
      
        $table .= '
        <tr>
            <td>'.$row->id_orden.'</td> 
            <td>'.$row->nombre_paciente.'</td> 
             
            <td>$' . number_format($ivas, 2) .'</td> 
             <td>$' . number_format($ivareal, 2) .'</td> 
             <td>$'.number_format($row->importe, 2) .'</td> 
           
            
        </tr>';
     



        $subtotal += $row->importe;
        $iva += $ivareal;
        $total += ($row->importe - $ivareal);
    }
    $table .='
    <tr>
    <th></th> 
    <th align="center">Totales por empresa: </th>
    <th align="left">$'. number_format($suma,2).'</th>
    <th  align="left">$'. number_format($suma2,2).'</th>
    <th align="left" > $'. number_format($suma3,2).'</th>
    </tr>'; 

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th align="left">$' . number_format($total, 2) . '</th> 
            <th align="left">$' . number_format($iva, 2) . '</th> 
            <th align="left">$' . number_format($subtotal, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("pacientes_empresas_credito.pdf", 'I');
}


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx3") {
    $pdf->SetHTMLHeader('
     <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Estudios de pacientes credito</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r3($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    $paciente="-1";
    $subtotal2=0;
    foreach ($data AS $row) {
        

        if($tipo!=$row->id_empresa){

         

          if($tipo!="-1"){
            $table .='
            <tr>
            <th></th>
            <th></th>
            <th align="center">Totales por empresa: </th>
            <th align="left">$'. number_format($subtotal,2).'</th>
            <th align="left"></th>
            <th></th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
            
            $subtotal=0;
          }

            $table .= "
            <tr>
            <td  colspan='5'>Empresa: $row->id_empresa $row->nombre_empresa</td>
            </tr>
            ";
            $tipo=$row->id_empresa;
           
            
        }


         if($paciente!=$row->id_orden){
            $table .= '
            <tr>
                 
                <th># Orden</td>
                <th>Fecha</th>  
                <td>Nombre del Paciente</td> 
                 <th>Importe Paciente</th>
                 <th></th>  
            </tr>';
           
            $table .= '
            <tr>
            
                <td>'.$row->id_orden.'</td> 
                <td>'.$row->fecha.'</td>
                <td>'.$row->nombre_paciente.'</td> 
                 <td>$'.number_format($row->importe, 2) .'</td> 
                 <td></td>
            </tr>';
            $subtotal=$subtotal+$row->importe;
            $subtotal2 = $subtotal2+$row->importe;

            $paciente=$row->id_orden;

         }
       
       
       
       
        $table .= '
        <tr>
        <td></td> 
            <td>'.$row->id.'</td> 
            <td>'.$row->nombre.'</td> 
             <td></td> 
             <td></td> 
        </tr>';

       

    }

    
    $table .='
    <tr>
    <th></th> 
    <th></th>
    <th align="center">Totales por empresa: </th>
    <th  align="left">$'. number_format($subtotal,2).'</th>
    <th align="left"></th>
    </tr>'; 

    $table .= '
        <tr border=1>
            <th></th> 
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
             
            <th align="left">$' . number_format($subtotal2, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("estudios_empresas_credito.pdf", 'I');
}

//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
if ($siglas == "rx4") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Totales empresas de contado</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r4($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">Código</th> 
            <th align="left">Empresa</th> 
            <th align="left">Subtotal</th> 
            <th align="left">IVA</th> 
            <th align="left">Total</th> 
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivareal=$row->total-$row->iva;
        if($tipo!=$row->tipo){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th></th>
            <th align="center">Totales por tipo de empresa: </th>
            <th align="left">$'. number_format($suma,2).'</th>
            <th align="left">$'. number_format($suma2,2).'</th>
            <th align="left">$'. number_format($suma3,2).'</th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }

            $table .= "
            <tr>
            <td  colspan='6'>Tipo de empresa: $row->tipo</td>
            </tr>
            ";
            $tipo=$row->tipo;
        }

        $suma=$suma+$row->iva;
        $suma2=$suma2+$ivareal;
        $suma3=$suma3+$row->total;
       
        $table .= '
        <tr>
            <td>'.$row->id.'</td> 
            <td>'.$row->nombre.'</td> 
            <td>$' . number_format($row->iva, 2) .'</td> 
             <td>$' . number_format($ivareal, 2) .'</td> 
             <td>$'.number_format($row->total, 2) .'</td> 
        </tr>';

        $subtotal += $row->total;
        $iva += $ivareal;
        $total += ($row->subtotal + $row->iva);
    }
    $table .='
    <tr>
    <th></th> 
    <th align="center">Totales por tipo de empresa: </th>
    <th align="left">$'. number_format($suma,2).'</th>
    <th  align="left">$'. number_format($suma2,2).'</th>
    <th align="left" > $'. number_format($suma3,2).'</th>
    </tr>'; 

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th align="left">$' . number_format($total, 2) . '</th> 
            <th align="left">$' . number_format($iva, 2) . '</th> 
            <th align="left">$' . number_format($subtotal, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("totales_empresas_contado.pdf", 'I');
}


//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
if ($siglas == "rx5") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Saldos Deudores</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r5($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">Código</th> 
            <th align="left">Empresa</th> 
            <th align="left">Subtotal</th> 
            <th align="left">IVA</th> 
            <th align="left">Total</th> 
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivareal=$row->total-$row->iva;
        if($tipo!=$row->tipo){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th></th>
            <th align="center">Totales por tipo de empresa: </th>
            <th align="left">$'. number_format($suma,2).'</th>
            <th align="left">$'. number_format($suma2,2).'</th>
            <th align="left">$'. number_format($suma3,2).'</th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }

            $table .= "
            <tr>
            <td  colspan='6'>Tipo de empresa: $row->tipo</td>
            </tr>
            ";
            $tipo=$row->tipo;
        }

        $suma=$suma+$row->iva;
        $suma2=$suma2+$ivareal;
        $suma3=$suma3+$row->total;
       
        $table .= '
        <tr>
            <td>'.$row->id.'</td> 
            <td>'.$row->nombre.'</td> 
            <td>$' . number_format($row->iva, 2) .'</td> 
             <td>$' . number_format($ivareal, 2) .'</td> 
             <td>$'.number_format($row->total, 2) .'</td> 
        </tr>';

        $subtotal += $row->total;
        $iva += $ivareal;
        $total += ($row->subtotal + $row->iva);
    }
    $table .='
    <tr>
    <th></th> 
    <th align="center">Totales por tipo de empresa: </th>
    <th align="left">$'. number_format($suma,2).'</th>
    <th  align="left">$'. number_format($suma2,2).'</th>
    <th align="left" > $'. number_format($suma3,2).'</th>
    </tr>'; 

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th align="left">$' . number_format($total, 2) . '</th> 
            <th align="left">$' . number_format($iva, 2) . '</th> 
            <th align="left">$' . number_format($subtotal, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Saldos_deudores_Empresas.pdf", 'I');
}


//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
if ($siglas == "rx6") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Venta por departamento</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r6($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr> 
        </tr>';

    $id=-1;
    $sumaTotalEmpresa=0;
    $departamento=-1;
    foreach ($data AS $row) {
        if($sucursal->iva_incluido==1){
           $subtotal=floatval($row->totala)/1.16;
           $iva=floatval($subtotal)*0.16;
           $sumaTotalEmpresa=$sumaTotalEmpresa+$subtotal+$iva;
        }
        else{
            $subtotal=floatval($row->totala)/1.16;
            $iva=floatval($row->precio_neto_estudio)*0.16;
            $sumaTotalEmpresa=$sumaTotalEmpresa+$subtotal+$iva;
        }

        if($row->id!=$id){
            $table .= "
            <tr>
            <td  colspan='5' style='text-align:center;border-bottom:solid 2px black;font-size:16px;'>Empresa: $row->id $row->nombre</td>
            </tr>
            ";
            $table .= '
            <tr>
                <td></td>
                <td>Departamento</td>
                <td>Subtotal</td> 
                <td>IVA</td>  
                <td>Total</td> 
            </tr>';
            $id=$row->id;
        }


        $table .= '
        <tr>
            <td></td>
            <td>'.$row->departamento.'</td>
            <td>$'.number_format($subtotal,2).'</td> 
            <td>$'.number_format($iva,2).'</td>  
            <td>$'.number_format($subtotal+$iva,2).'</td> 
        </tr>';

    }

    $table.='</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Venta_Departamento_Empresas.pdf", 'I');
}


//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
if ($siglas == "rx7") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Médico: Desgloce Pacientes/Estudios</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');

$table = '';


    $reportes = new Reportes();
    $medicos=$reportes->r7m($id_sucursal, $fecha_inicial, $fecha_final, $palabra);
 

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        font-size:10px;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr> 
        </tr>';

    $id=-1;
    $paciente=-1;
    $sumaSubtotal=0;
    $sumaIVA=0;
    $sumaTotalMedico=0;
    foreach ($medicos AS $row) {
        if($sucursal->iva_incluido==1){
           $subtotal=floatval($row->precio_neto_estudio)/1.16;
           $iva=floatval($subtotal)*0.16;
           $sumaTotalMedico=$sumaTotalMedico+$subtotal+$iva;
        }
        else{
            $subtotal=floatval($row->precio_neto_estudio)/1.16;
            $iva=floatval($row->precio_neto_estudio)*0.16;
            $sumaTotalMedico=$sumaTotalMedico+$subtotal+$iva;
        }

        //-----------------Imprime un nuevo médico y el total del anterior
        if($row->id!=$id){
            if($paciente!=-1){
                $table .= '
                <tr>
                    <td style="background:#EEE"></td>
                    <td style="background:#EEE" colspan="2" style="text-align:right;background:#EEE;">Total:</td>
                    <td style="background:#EEE;text-align:right">$'.number_format($sumaSubtotal,2).'</td> 
                    <td style="background:#EEE;text-align:right">$'.number_format($sumaIVA,2).'</td>  
                    <td style="background:#EEE;text-align:right">$'.number_format($sumaSubtotal+$sumaIVA,2).'</td> 
                </tr>';
                $sumaSubtotal=0;
                $sumaIVA=0;
            }

            if($id!=-1){
                $table .= '
                    <tr>
                        <td colspan="5" style="text-align:right;background:#F4F5DD"><b>Total del Medico:</b></td>
                        <td style="background:#F4F5DD;text-align:right">$'.number_format($sumaTotalMedico,2).'</td>
                    </tr>
                ';
                $sumaTotalMedico=0;
            }
            $table .= "
                <tr>
                    <td colspan='6' style='text-align:center;border-bottom:solid 2px black;font-size:16px;'><b>Medico:  $row->nombre ($row->id) </b></td>
                </tr>
            ";
            $id=$row->id;
        }
        //------------------------- Imprime cada nuevo paciente del médico
        if($row->consecutivo!=$paciente){

            if($paciente!=-1 && $sumaSubtotal>0){
                $table .= '
                <tr>
                    <td style="background:#EEE"></td>
                    <td style="background:#EEE" colspan="2" style="text-align:right;background:#EEE;">Total:</td>
                    <td style="background:#EEE;text-align:right">$'.number_format($sumaSubtotal,2).'</td> 
                    <td style="background:#EEE;text-align:right">$'.number_format($sumaIVA,2).'</td>  
                    <td style="background:#EEE;text-align:right">$'.number_format($sumaSubtotal+$sumaIVA,2).'</td> 
                </tr>';
                $sumaSubtotal=0;
                $sumaIVA=0;
            }

            $table .= '
            <tr>
                <td>' .$row->consecutivo.'</td>
                <td colspan="3">PACIENTE: '.$row->paciente.'- (<span style="font-size:10px;">DR.'.$row->nombre.'</span>)</td> 
                <td colspan="2">'.$row->fecha_registro.'</td>  
            </tr>';

             $table .= '
            <tr >
                <td style="background:#EEE"></td>
                <td style="background:#EEE">Código</td>
                <td style="background:#EEE">Nombre</td>
                <td style="background:#EEE">Subtotal</td>
                <td style="background:#EEE">IVA</td>
                <td style="background:#EEE">Total</td>
            </tr>';
            $paciente=$row->consecutivo;
        } 
        //------------------- Imprime cada estudio del paciente
        $table .= '
            <tr>
                <td></td>
                <td>'.$row->no_estudio.'</td> 
                <td>'.$row->nombre_estudio.'</td>  
                <td style="text-align:right;">$'.number_format($subtotal,2).'</td> 
                <td style="text-align:right;">$'.number_format($iva,2).'</td> 
                <td style="text-align:right;">$'.number_format($subtotal+$iva,2).'</td> 
            </tr>';

        $sumaSubtotal=$sumaSubtotal+$subtotal;
        $sumaIVA=$sumaIVA+$iva;
    }//termina for


    $table .= '
    <tr>
        <td style="background:#EEE"></td>
        <td style="background:#EEE" colspan="2" style="text-align:right;background:#EEE;">Total:</td>
        <td style="background:#EEE;text-align:right;">'.number_format($sumaSubtotal,2).'</td> 
        <td style="background:#EEE;text-align:right;">'.number_format($sumaIVA,2).'</td>  
        <td style="background:#EEE;text-align:right;">'.number_format($sumaSubtotal+$sumaIVA,2).'</td>  
    </tr>';

    $table .= '
        <tr>
            <td colspan="4" style="text-align:right;background:#F4F5DD"><b>Total del Medico:  '.$row->nombre.' </b></td>
            <td style="background:#F4F5DD"  colspan="2" style="text-align:right;" ><b>$'.number_format($sumaTotalMedico,2).'</b></td>
        </tr>
    ';

    $table .= '</table>';
    $pdf->WriteHTML($table);
    $pdf->Output("medico_desgloce_pacientes.pdf", 'I');
}

//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
if ($siglas == "rx22") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Cortes</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r22($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:8px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">#</th> 
             
            <th align="left">Pagos</th> 
            <th align="left">Gasto</th> 
            <th align="left">Total Corte</th> 
            <th align="left">Usuario</th>
            <th align="left">Fecha y hora</th>
            
           
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
     
        $table .= '
        <tr>
            <td>'.$row->corte_numero.'</td> 
             <td>'.number_format($row->ingresos,2).'</td> 
            <td>$' . number_format($row->gastos, 2) .'</td> 
            <td>$' . number_format($row->total, 2) .'</td> 
            <td>'.$row->nombre.'</td> 
          
            <td>'.$row->fecha.'</td> 
            
        </tr>';

        $subtotal += $row->ingresos;
        $gasto += $row->gastos;
        $total += $row->total;
    }


    $table .= '
        <tr border=1>
           
            <th align="center">TOTALES GENERALES:</th> 
            <th align="left">$' . number_format($subtotal, 2) . '</th> 
            <th align="left">$' . number_format($gasto, 2) . '</th> 
            <th align="left">$' . number_format($total, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Cortes_de_Sucursal.pdf", 'I');
}

//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
if ($siglas == "rx24") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Ordenes Canceladas</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r24($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">Orden #</th> 
             
            <th align="left">Paciente</th> 
            <th align="left">Importe</th> 
            <th align="left">Fecha</th> 
            <th align="left">Observaciones</th>
            
           
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
     
        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
             <td>'.$row->nombreson.'</td> 
            <td>' .$row->importe.'</td> 
            <td>'.$row->fecha_registro.'</td> 
            <td>'.$row->observaciones.'</td> 
          
          ¡
            
        </tr>';

        $total += $row->importe;
    }


    $table .= '
        <tr border=1>
        <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th align="left">$' . number_format($total, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("ordenes_canceladas.pdf", 'I');
}


//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
if ($siglas == "rx10") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Lista de Mèdicos</h3>
                </td> 
                <td width=20% align="center"></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r10($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:8px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">id</th> 
             
            <th align="left">Paciente</th> 
            <th align="left">Nombre</th> 
            
            <th align="left">Ciudad</th>
            <th align="left">Estado</th>
            <th align="left">Telefono</th>
            <th align="left">Email</th>
            <th align="left">Comision</th>
            
           
        </tr>';

    foreach ($data AS $row) {
     
        $table .= '
        <tr>
            <td>'.$row->id.'</td> 
             <td style="width=100%!important;">'.$row->nombre.'</td> 
            <td>' .$row->alias.'</td> 
            <td>'.$row->ciudad.'</td> 
            <td>'.$row->estado.'</td> 
            <td>'.$row->tel.'</td> 
            <td>'.$row->email.'</td> 
            <td>'.$row->porcentaje.'</td> 
          
          ¡
            
        </tr>';

     
    }


    $table .= '
       ';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("lista_de_medicos.pdf", 'I');
}

//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
if ($siglas == "rx44") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Lista de Paquetes</h3>
                </td> 
                <td width=20% align="center"></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r44($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:8px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">#</th> 
            <th align="left">Nombre</th> 
            <th align="left">Alias</th> 
            <th align="left">Precio</th>  
        </tr>';

    foreach ($data AS $row) {
     
        $table .= '
        <tr>
            <td>'.$row->no_paquete.'</td> 
             <td style="width=100%!important;">'.$row->nombre.'</td> 
            <td>' .$row->alias.'</td> 
            <td>'.$row->precio.'</td> 
          
          ¡
            
        </tr>';

     
    }


    $table .= '
       ';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("lista_de_paquetes.pdf", 'I');
}


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx45") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Paquetes detalles</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r45($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
       
        if($tipo!=$row->id){

          if($tipo!="-1"){
      
          }

            $table .= "
            <tr>
            <td></td>
            <td>Paquete: $row->nombre</td>
            <td  colspan='2'>Alias: $row->alias  Precio: $row->precio</td>
            
            </tr>
            ";
            $tipo=$row->id;
        }

      
       
        $table .= '
        <tr>
        <td>'.$row->no_estudio.'</td> 
            <td>'.$row->nombre_estudio.'</td> 
            <td>'.$row->precio_neto.'</td> 
            
             <td colspan="3"></td> 
             
        </tr>';


    }
    $table .='
    <tr>
   
    </tr>'; 

    $table .= '
        <tr border=1>
 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Detalle_Paquetes.pdf", 'I');
}


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx45") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Paquetes detalles</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r3($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">Orden #</th> 
            <th align="left">Nombre Paciente</th> 
            <th align="left">Subtotal</th> 
            <th align="left">IVA</th> 
            <th align="left">Total</th> 
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivas=$row->importe /1.16;
        $ivareal=$row->importe- $ivas;
        if($tipo!=$row->id_empresa){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th></th>
            <th align="center">Totales por empresa: </th>
            <th align="left">$'. number_format($suma,2).'</th>
            <th align="left">$'. number_format($suma2,2).'</th>
            <th align="left">$'. number_format($suma3,2).'</th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }

            $table .= "
            <tr>
            <td  colspan='6'>Empresa: $row->id_empresa </td>
            </tr>
            ";
            $tipo=$row->id_empresa;
        }

        $suma=$suma+$ivas;
        $suma2=$suma2+$ivareal;
        $suma3=$suma3+$row->importe;
       
        $table .= '
        <tr>
            <td>'.$row->id_orden.'</td> 
            <td>'.$row->nombre_paciente.'</td> 
            <td>$' . number_format($ivas, 2) .'</td> 
             <td>$' . number_format($ivareal, 2) .'</td> 
             <td>$'.number_format($row->importe, 2) .'</td> 
        </tr>';

        $subtotal += $row->importe;
        $iva += $ivareal;
        $total += ($row->importe - $ivareal);
    }
    $table .='
    <tr>
    <th></th> 
    <th align="center">Totales por empresa: </th>
    <th align="left">$'. number_format($suma,2).'</th>
    <th  align="left">$'. number_format($suma2,2).'</th>
    <th align="left" > $'. number_format($suma3,2).'</th>
    </tr>'; 

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th align="left">$' . number_format($total, 2) . '</th> 
            <th align="left">$' . number_format($iva, 2) . '</th> 
            <th align="left">$' . number_format($subtotal, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Paquetes_Detalle.pdf", 'I');
}


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx48") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Venta Global de Medicos</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r48($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">#</th> 
            <th align="left">Nombre</th> 
            <th align="left">Alias</th> 
            <th align="left">Subtotal</th> 
            <th align="left">IVA</th> 
            <th align="left">Total</th> 
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivas=$row->total /1.16;
        $ivareal=$row->total- $ivas;
        if($tipo!=$row->id_empresa){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th></th>
            <th align="center">Totales por empresa: </th>
            <th align="left">$'. number_format($suma,2).'</th>
            <th align="left">$'. number_format($suma2,2).'</th>
            <th align="left">$'. number_format($suma3,2).'</th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }

        
        }

        $suma=$suma+$ivas;
        $suma2=$suma2+$ivareal;
        $suma3=$suma3+$row->total;
       
        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
            <td>'.$row->nombre.'</td> 
            <td>'.$row->alias.'</td> 
            <td>$' . number_format($ivas, 2) .'</td> 
             <td>$' . number_format($ivareal, 2) .'</td> 
             <td>$'.number_format($row->total, 2) .'</td> 
        </tr>';

        $subtotal += $row->total;
        $iva += $ivareal;
        $total += ($row->total - $ivareal);
    }
  

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th></th>
            <th align="left">$' . number_format($total, 2) . '</th> 
            <th align="left">$' . number_format($iva, 2) . '</th> 
            <th align="left">$' . number_format($subtotal, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Venta_Global_Medicos", 'I');
}


///////////////////////////////////////////////////////
#--------- REPORTE GLOBAL ADMINISTRATIVO
///////////////////////////////////////////////////////
if ($siglas == "rx800") {
    echo "<script>alert()</script>
    ";
}




///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx9") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Medicos sin Registro</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r9($id_sucursal, $fecha_inicial, $fecha_final);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:8px;
        }
        th {
        height: 40px;
        text-align: left;
        
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left"># Orden</th> 
            <th align="left">Nombre Paciente </th> 
            <th align="left">Fecha</th> 
            <th align="left">Medico</th> 
            <th align="left">Usuario</th> 
            <th align="left">Total</th> 
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {

        if($tipo!=$row->id_empresa){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th></th>
            <th align="center">Totales: </th>
            <th align="left">$'. number_format($suma,2).'</th>
            <th align="left">$'. number_format($suma2,2).'</th>
            <th align="left">$'. number_format($suma3,2).'</th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }

        
        }

       
        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
            <td>'.$row->nombrepaciente.'</td> 
            <td>'.$row->fecha.'</td>
            <td>'.$row->nombre_doctor.'</td> 
            
            <td>'.$row->nombre_usuario.'</td>
             <td>$'.number_format($row->total, 2) .'</td> 
        </tr>';

        $subtotal += $row->total;
    }
  

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th></th>
            <th></th>
            <th></th>
          
            <th align="left">$' . number_format($subtotal, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Medicos_no_Registrados", 'I');
}

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx29") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Saldos Deudores</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r29($id_sucursal, $fecha_inicial, $fecha_final);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:8px;
        }
        th {
        height: 40px;
        text-align: left;
        
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left"># Orden</th> 
            <th align="left">Nombre Paciente </th> 
            <th align="left">Fecha</th> 
            <th align="left">Total</th> 
            <th align="left">Cubierto</th> 
            <th align="left">Saldo</th> 
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {

        
        
       $cubierto=$row->importe-$row->saldo_deudor;
       
        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
            <td>'.$row->nombre.'</td> 
            <td>'.$row->fecha_registro.'</td>
            <td>'.$row->importe.'</td>
            <td>$'.number_format($cubierto, 2) .'</td> 
             <td>'.$row->saldo_deudor.'</td>
            
        </tr>';

        $subtotal += $row->saldo_deudor;
    }
  

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th></th>
            <th></th>
            <th></th>
          
            <th align="left">$' . number_format($subtotal, 2) . '</th> 
        </tr>';
     $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Saldos", 'I');
}




///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx14") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Venta Global de Estudios</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r14($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">Codigo</th> 
            <th align="left">Nombre</th> 
            <th align="left">Cantidad</th> 
            <th align="left">Total</th>
            <th align="left">Promedio</th> 
            <th align="left">Cantidad</th> 
            <th align="left">Contado</th> 
            <th align="left">Cantidad</th> 
            <th align="left">Credito</th>
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivas=$row->total /1.16;
        $ivareal=$row->total- $ivas;
        if($tipo!=$row->id_empresa){

         

        
        }

        $suma=$suma+$ivas;
        $suma2=$suma2+$ivareal;
        $suma3=$suma3+$row->total;
       
        $table .= '
        <tr>
            <td>'.$row->codigo.'</td> 
            <td>'.$row->nombre.'</td> 
            <td align="center">'.$row->cantidad.'</td> 
            <td>$' . number_format($row->venta, 2) .'</td> 
            <td>$' . number_format($row->costo_promedio, 2) .'</td> 
            <td align="center">'.$row->cuenta_contado.'</td> 
            <td>$' . number_format($row->venta_contado, 2) .'</td> 
            <td align="center">'.$row->cuenta_credito.'</td> 
            <td>$' . number_format($row->venta_credito, 2) .'</td>
        </tr>';

        $total_estudios += $row->cantidad;
        $total_venta += $row->venta;
        $cuenta_con += $row->cuenta_contado;
        $suma_con += $row->venta_contado;
        $cuenta_cre += $row->cuenta_credito;
        $suma_cre += $row->venta_credito;
    }
  

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th align="left">' .number_format($total_estudios,0). '</th> 
            <th align="left">$' . number_format($total_venta, 2) . '</th> 
            <th></th>
            <th align="left">$' . number_format($cuenta_con, 0) . '</th> 
            <th align="left">$' . number_format($suma_con, 2) . '</th> 
            <th align="left">$' . number_format($cuenta_cre, 0) . '</th> 
            <th align="left">$' . number_format($suma_cre, 2) . '</th>
           
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Venta_Global_Estudios", 'I');
}


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx8") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Comisiones Médicas</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


     $reportes = new Reportes();
    $data = $reportes->r8($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
         
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivas=$row->importe /1.16;
        $ivareal=$row->importe- $ivas;
        if($tipo!=$row->id_doctor){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th>Un Saldo Negatvo , informa que el adeudo de los pacientes es mayor a la comision</th> 
    <th align="center">Comision a Pagar: '. number_format($suma3-$suma2,2).' </th>
    <th align="left">$'. number_format($suma,2).'</th>
    <th  align="left">$'. number_format($suma2,2).'</th>
    <th></th>
    <th align="left" > $'. number_format($suma3,2).'</th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }


         

            $table .= "
            <tr>
            <td  colspan='5'>Médico: $row->id_doctor $row->nombre_medico </td>
            
            </tr>
            ";
            $table .='
            <tr>
            <th align="left">Orden #</th> 
            <th align="left">Nombre Paciente</th> 
            <th align="left">Importe</th> 
            <th align="left">Saldo</th> 
            <th align="left">%</th> 
            <th align="left">Comision</th> 
            
            </tr>'; 
            



            $tipo=$row->id_doctor;
        }

        $suma=$suma+$row->total;
        $suma2=$suma2+$row->deuda;
        $suma3=$suma3+$row->porcentaje;
      
        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
            <td>'.$row->nombre_paciente.'</td> 
             
            <td>$' . number_format($row->total, 2) .'</td> 
            <td>$' . number_format($row->deuda, 2) .'</td> 
             <td>'.number_format($row->pago, 2) .' %</td> 
             <td>'.number_format($row->porcentaje, 2) .'</td> 
            
        </tr>';
     



        $subtotal += $suma;
        $iva += $suma2;
        $total += $suma3;
    }
    $table .='
    <tr>
    <th>Un Saldo Negatvo , informa que el adeudo de los pacientes es mayor a la comision</th> 
    <th align="center">Comision a Pagar: '. number_format($suma3-$suma2,2).' </th>
    <th align="left">$'. number_format($suma,2).'</th>
    <th  align="left">$'. number_format($suma2,2).'</th>
    <th></th>
    <th align="left" > $'. number_format($suma3,2).'</th>
    </tr>'; 

    $table .= '
        <tr border=1>
      
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("comisiones_medicas.pdf", 'I');
}



///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx27") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Lista de Gastos</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


     $reportes = new Reportes();
    $data = $reportes->r27($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:10px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
         
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivas=$row->importe /1.16;
        $ivareal=$row->importe- $ivas;
        if($tipo!=$row->id_doctor){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th>Un Saldo Negatvo , informa que el adeudo de los pacientes es mayor a la comision</th> 
            <th align="center">Comision a Pagar: '. number_format($suma3-$suma2,2).' </th>
              <th align="left">$'. number_format($suma,2).'</th>
            <th  align="left">$'. number_format($suma2,2).'</th>
             <th></th>
             <th align="left" > $'. number_format($suma3,2).'</th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }


            $table .='
            <tr>
            <th align="left">Fecha</th> 
            <th align="left">Importe</th> 
            <th align="left">Usuario</th> 
            <th align="left">Corte</th> 
            <th align="left">Gasto</th> 
            <th align="left">Obervacion</th> 
            
            </tr>'; 
            



            $tipo=$row->id_doctor;
        }

     
        $suma3=$suma3+$row->importe;
      
        $table .= '
        <tr>
            <td>'.$row->fecha.'</td> 
            <td>'.number_format($row->importe,2).'</td> 
            <td>'.$row->nombre_usuario.'</td> 
            <td>'.$row->corte_numero.'</td> 
            <td>'.$row->concepto.'</td> 
            <td>'.$row->aclaracion.'</td> 
             
            
        </tr>';
     



    }
    $table .='
    <tr>
    <th>Total:</th> 
    <th align="center"> '. number_format($suma3,2).' </th>
    <th align="left"></th>
    <th  align="left"></th>
    <th></th>
    <th align="left" ></th>
    </tr>'; 

    $table .= '
        <tr border=1>
      
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Gastos_de_Sucursal.pdf", 'I');
}


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx33") {
    $pdf->SetHTMLHeader('

    <div style="height: 80px;">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Lista de Precios</h3>
                </td> 
                <td width=20% align="center">Publico General</td> 
            </tr>
        </table>
        <div style="float:left; width:50%">
        <table width="97%;" border=0 CELLSPACING=0 >
        <tr>
        <th align="left" width="17%">Codigo</th> 
        <th align="left">Nombre</th> 
        <th align="left" width="17%">Importe</th> 
        </tr></table>
        </div>
        
        <div style="float:right;width:49%">
        <table width="100%" border=0 CELLSPACING=0 >
        <tr>
        <th align="left" width="17%">Codigo</th> 
        <th align="left">Nombre</th> 
        <th align="left" width="17%">Importe</th> 
        </tr></table>
        </div>
        
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');

    $pdf->SetColumns(2,'J',5);

     $reportes = new Reportes();
    $data = $reportes->r33($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
       
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:10px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>
   <div>
    <table width="100%" border=0 CELLSPACING=0 >
        ';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
  
        if($tipo!=$row->id_doctor){
            $table .='
            <tr>
           
            </tr>'; 
        }

     
        
      
        $table .= '
        <tr>
            <td>'.$row->no_estudio.'</td> 
            <td>'.$row->nombre_estudio.'</td>
            <td>'.number_format($row->precio_publico,2).'</td>   
        </tr>';
     



    }
    $table .='
    <tr>
    
    </tr>'; 

    $table .= '
        <tr border=1>
      
        </tr>';
    $table .= '</table> 
    
    </div>';
   
    $pdf->WriteHTML($table);

    $pdf->Output("Lista_Publico_General.pdf", 'I');
}


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx26") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Pagos Desaplicados</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


     $reportes = new Reportes();
    $data = $reportes->r26($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:10px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
        <th align="left">Fecha</th> 
        <th align="left"># Orden</th> 
        <th align="left">Paciente</th> 
        <th align="left">Importe</th>  
        <th align="left">Desaplico</th> 
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
  
        if($tipo!=$row->id_doctor){
            $table .='
            <tr>
           
            </tr>'; 
        }

     
        
      
        $table .= '
        <tr>
            <td>'.$row->fecha.'</td> 
            <td>'.$row->consecutivo.'</td>
            <td>'.$row->paciente.'</td>
            <td>'.number_format($row->monto,2).'</td>
            <td>'.$row->usuario.'</td>
            
        </tr>';
     



    }
    $table .='
    <tr>
    
    </tr>'; 

    $table .= '
        <tr border=1>
      
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Pagos_Desaplicados.pdf", 'I');
}

/////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
if ($siglas == "rx46") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Lista de Precios</h3>
                </td> 
                <td width=20% align="center"></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r46($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:8px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">#</th> 
            <th align="left">Nombre</th> 
            <th align="left">Alias</th> 
            
        </tr>';

    foreach ($data AS $row) {
     
        $table .= '
        <tr>
            <td>'.$row->no.'</td> 
             <td style="width=100%!important;">'.$row->nombre.'</td> 
            <td>' .$row->alias.'</td> 
           
          
          ¡
            
        </tr>';

     
    }


    $table .= '
       ';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("lista_de_precios.pdf", 'I');
}

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx47") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Lias de precios detallada</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r47($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
       
        if($tipo!=$row->id){

          if($tipo!="-1"){
      
          }

            $table .= "
            <tr>
            <td  colspan='1'>Lista de Pecios: $row->nombre</td>
            <td  colspan='1'>Alias $row->alias</td>
            <td colspan='1'></td>
            </tr>
            ";
            $tipo=$row->id;
        }

       
        $table .= '
        <tr>
           <td>'.$row->no_estudio.'</td> 
            <td>'.$row->nombre_estudio.'</td> 
            <td>'.$row->precio_neto.'</td> 
            
          
             
        </tr>';


    }
    $table .='
    <tr>
   
    </tr>'; 

    $table .= '
        <tr border=1>
 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Detalle_Listas_de_Precios.pdf", 'I');
}

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
if ($siglas == "rx13") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Venta Global de Estudios Por Sección</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r13($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
            <th align="left">Codigo</th> 
            <th align="left">Nombre</th> 
            <th align="left">Cantidad</th> 
            <th align="left">Total</th>
            <th align="left">Promedio</th> 
            <th align="left">Cantidad</th> 
            <th align="left">Contado</th> 
            <th align="left">Cantidad</th> 
            <th align="left">Credito</th>
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivas=$row->total /1.16;
        $ivareal=$row->total- $ivas;

        if($tipo!=$row->seccion){

            $table .= "
            <tr>
            <td  colspan='6'>Sección: $row->nombre_seccion </td>
            </tr>
            ";
            $tipo=$row->seccion;

            if($tipo!="-1"){
         
    
              
            }
         

        
        }

        $suma=$suma+$ivas;
        $suma2=$suma2+$ivareal;
        $suma3=$suma3+$row->total;
       
        $table .= '
        <tr>
            <td>'.$row->codigo.'</td> 
            <td>'.$row->nombre.'</td> 
            <td align="center">'.$row->cantidad.'</td> 
            <td>$' . number_format($row->venta, 2) .'</td> 
            <td>$' . number_format($row->costo_promedio, 2) .'</td> 
            <td align="center">'.$row->cuenta_contado.'</td> 
            <td>$' . number_format($row->venta_contado, 2) .'</td> 
            <td align="center">'.$row->cuenta_credito.'</td> 
            <td>$' . number_format($row->venta_credito, 2) .'</td>
        </tr>';

        $total_estudios += $row->cantidad;
        $total_venta += $row->venta;
        $cuenta_con += $row->cuenta_contado;
        $suma_con += $row->venta_contado;
        $cuenta_cre += $row->cuenta_credito;
        $suma_cre += $row->venta_credito;
    }
  

    $table .= '
        <tr border=1>
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
            <th align="left">' .number_format($total_estudios,0). '</th> 
            <th align="left">$' . number_format($total_venta, 2) . '</th> 
            <th></th>
            <th align="left">$' . number_format($cuenta_con, 0) . '</th> 
            <th align="left">$' . number_format($suma_con, 2) . '</th> 
            <th align="left">$' . number_format($cuenta_cre, 0) . '</th> 
            <th align="left">$' . number_format($suma_cre, 2) . '</th>
           
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Venta_Global_Estudios_Secciones", 'I');
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
if ($siglas == "rx30") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Venta global por dia</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


     $reportes = new Reportes();
    $data = $reportes->r30($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:10px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
         
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
  

        $total_pacientes=$row->cuenta_contado+$row->cuenta_credito;
        $total_global=$row->venta_contado+$row->venta_credito;
        if($tipo!=$row->id_doctor){

          if($tipo!="-1"){
            $table .='
            <tr>
            </tr>';  
   
          }


            $table .='
            <tr>
            <th align="left">Fecha</th> 
            <th align="left">Contado</th> 
            <th align="left">Importe</th> 
            <th align="left">Credito</th> 
            <th align="left">Importe</th> 
            <th align="left">Pacientes</th> 
            <th align="left">Total</th> 
            
            </tr>'; 
            



            $tipo=$row->id_doctor;
        }

     
        $suma3=$suma3+$total_global;
        
        $table .= '
        <tr>
            <td align="center">'.$row->fecha.'</td> 
            <td align="center">'.$row->cuenta_contado.'</td> 
            <td align="center">$'.number_format($row->venta_contado,2).'</td> 
            <td align="center">'.$row->cuenta_credito.'</td> 
            <td align="center">$'.number_format($row->venta_credito,2).'</td> 
            <td align="center">'.$total_pacientes.'</td> 
            <td align="center">$'.number_format($total_global,2).'</td> 
             
            
        </tr>';
     



    }
    $table .='
    <tr>
   
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th>Total:</th> 
    <th align="center"> $'. number_format($suma3,2).' </th>
    </tr>'; 

    $table .= '
        <tr border=1>
      
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("venta_global_por_dia.pdf", 'I');
}

////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
if ($siglas == "rx28") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Pacientes agrupados por dia</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r28($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
      </tr>';

    foreach ($data AS $row) {
       
       
        if ($item->credito == 0) {
            $tipo_orden = "Contado";
        } 
        if ($item->credito == 1) {
            $tipo_orden = "Credito";
        } 


        if($tipo!=$row->fecha){

   

            $table .= "
            <tr>
            <td  colspan='5'>Fecha: $row->fecha </td>
            </tr>
            ";
            $tipo=$row->fecha;

            if($tipo!="-1"){

         

               $table .='
                <tr>
                    <th align="left">Codigo</th> 
                    <th align="left">Nombre</th> 
                    <th align="left">Importe</th> 
                    <th align="left">Empresa</th>
                    <th align="left">Tipo</th> 
        
                </tr>';
         
              
              
            }
         
             
        
        }

       
        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
            <td>'.$row->nombre_paciente.'</td> 
            <td>$' . number_format($row->importe, 2) .'</td> 
            <td align="center">'.$row->nombre.'</td> 
            <td>'.$tipo_orden.'</td> 
         
        </tr>';

   
    }
  

    $table .= '
        <tr border=1>
          </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("pacientes_por_dia", 'I');
}

////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
if ($siglas == "rx23") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Corte detallado de ingresos</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r23($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:9px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
      </tr>';

    foreach ($data AS $row) {



        if($tipo!=$row->id_corte){

   

            $table .= '
            <tr>
            <td>Corte: '.$row->corte_numero.'</td>
            <td>Fecha:'. $row->fecha.'</td>
            <td>Ingreso: $'.number_format($row->ingresos,2).'</td>
            <td>Gasto:$'.number_format($row->gastos,2).'</td>
            <td>Total:$'.number_format($row->total,2).'</td>
            <td>Usuario:'.$row->usuario.'</td>
            </tr>
            ';
            $tipo=$row->id_corte;

            if($tipo!="-1"){

         

               $table .='
                <tr>
                    <th align="left">Codigo</th> 
                    <th align="left">Nombre</th> 
                    <th align="left">Importe</th> 
                    <th align="left">Fecha</th>
                    <th align="left">Forma</th> 
                    <th></th>
        
                </tr>';
         
              
              
            }
         
             
        
        }

       
        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
            <td>'.$row->nombre_paciente.'</td> 
            <td>$' . number_format($row->pago, 2) .'</td> 
            <td align="center">'.$row->fecha_pago.'</td> 
            <td>'.$row->descripcion.'</td> 
            <td></td>
         
        </tr>';

   
    }
  

    $table .= '
        <tr border=1>
          </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("corte_detallado", 'I');
}

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx701") {
    $pdf->SetHTMLHeader('
     <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Desglose Empresas de Contado</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r701($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    $paciente="-1";
    $subtotal2=0;
    foreach ($data AS $row) {
        

        if($tipo!=$row->id_empresa){

         

          if($tipo!="-1"){
            $table .='
            <tr>
            <th></th>
            <th></th>
            <th align="center">Totales por empresa: </th>
            <th align="left">$'. number_format($subtotal,2).'</th>
            <th align="left"></th>
            <th></th>
            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
            
            $subtotal=0;
          }

            $table .= "
            <tr>
            <td  colspan='5'>Empresa: $row->id_empresa $row->nombre_empresa</td>
            </tr>
            ";
            $tipo=$row->id_empresa;
           
            
        }


         if($paciente!=$row->id_orden){
            $table .= '
            <tr>
                 
                <th># Orden</td>
                <th>Fecha</th>  
                <td>Nombre del Paciente</td> 
                 <th>Importe Paciente</th>
                 <th></th>  
            </tr>';
           
            $table .= '
            <tr>
            
                <td>'.$row->id_orden.'</td> 
                <td>'.$row->fecha.'</td>
                <td>'.$row->nombre_paciente.'</td> 
                 <td>$'.number_format($row->importe, 2) .'</td> 
                 <td></td>
            </tr>';
            $subtotal=$subtotal+$row->importe;
            $subtotal2 = $subtotal2+$row->importe;

            $paciente=$row->id_orden;

         }
       
       
       
       
        $table .= '
        <tr>
        <td></td> 
            <td>'.$row->id.'</td> 
            <td>'.$row->nombre.'</td> 
             <td></td> 
             <td></td> 
        </tr>';

       

    }

    
    $table .='
    <tr>
    <th></th> 
    <th></th>
    <th align="center">Totales por empresa: </th>
    <th  align="left">$'. number_format($subtotal,2).'</th>
    <th align="left"></th>
    </tr>'; 

    $table .= '
        <tr border=1>
            <th></th> 
            <th></th> 
            <th align="center">TOTALES GENERALES:</th> 
             
            <th align="left">$' . number_format($subtotal2, 2) . '</th> 
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("estudios_empresas_credito.pdf", 'I');
}

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx15") {
    $pdf->SetHTMLHeader('
     <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Estudios no reportados</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r15($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
        </tr>';
  
        $table .= '
        <tr>
             
            <th># Orden</td>
            <th>Fecha</th>  
            <th>Nombre del Paciente</th> 
             <th>Importe Paciente</th>
             <th>Cubierto</th>  
             <th>Saldo deudor</th>
        </tr>';
         $paciente=-1;
    foreach ($data AS $row) {
        $cubierto=$row->importe-$row->saldo_deudor;

        if($paciente!=$row->id_orden){
            $table .= '
            <tr>
            
                <td>'.$row->id_orden.'</td> 
                <td>'.$row->fecha.'</td>
                <td>'.$row->nombre_paciente.'</td> 
                 <td>$ '.number_format($row->importe, 2) .'</td> 
                 <td>$ '.number_format($cubierto, 2) .'</td> 
                 <td>$ '.number_format($row->saldo_deudor, 2) .'</td> 
                 
            </tr>';

            $paciente=$row->id_orden;
        }
        
     
        $table .= '
        <tr>
        <td></td> 
            <td>'.$row->no_estudio.'</td> 
            <td>'.$row->nombre_estudio.'</td> 
             <td></td> 
             <td></td> 
             <td></td> 
        </tr>';
       

       

    }

 
  

    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("no_reportados.pdf", 'I');
}


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx16") {
    $pdf->SetHTMLHeader('
     <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Estudios no impresos</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r16($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
        </tr>';
  
        $table .= '
        <tr>
             
            <th># Orden</td>
            <th>Fecha</th>  
            <th>Nombre del Paciente</th> 
             <th>Importe Paciente</th>
             <th>Cubierto</th>  
             <th>Saldo deudor</th>
        </tr>';
         $paciente=-1;
    foreach ($data AS $row) {
        $cubierto=$row->importe-$row->saldo_deudor;

        if($paciente!=$row->id_orden){
            $table .= '
            <tr>
            
                <td>'.$row->id_orden.'</td> 
                <td>'.$row->fecha.'</td>
                <td>'.$row->nombre_paciente.'</td> 
                 <td>$ '.number_format($row->importe, 2) .'</td> 
                 <td>$ '.number_format($cubierto, 2) .'</td> 
                 <td>$ '.number_format($row->saldo_deudor, 2) .'</td> 
                 
            </tr>';

            $paciente=$row->id_orden;
        }
        
     
        $table .= '
        <tr>
        <td></td> 
            <td>'.$row->no_estudio.'</td> 
            <td>'.$row->nombre_estudio.'</td> 
             <td></td> 
             <td></td> 
             <td></td> 
        </tr>';
       

       

    }

 
  

    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("no_Impresos.pdf", 'I');
}

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx32") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Descuentos</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


     $reportes = new Reportes();
    $data = $reportes->r32($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:10px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
        
        <th align="left"># Orden</th> 
        <th align="left">Paciente</th> 
        <th align="left">Importe de orden</th>  
        <th align="left">Descuento</th> 
        <th align="left">Porcentaje</th> 
        <th align="left">Monto</th>
        <th align="left">observaciones</th> 
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
  
        if($tipo!=$row->id_doctor){
            $table .='
            <tr>
           
            </tr>'; 
        }

     
        
      
        $table .= '
        <tr>
            
            <td>'.$row->consecutivo.'</td>
            <td>'.$row->paciente.'</td>
            <td>$ '.number_format($row->importe,2).'</td>
            <td>'.$row->nombre_descuento.'</td>
            <td>'.$row->descuento.'</td>
            <td>$ '.number_format($row->porcentaje,2).'</td>
            <td>'.$row->observaciones.'</td>
            
        </tr>';
     
        $suma_cre += $row->porcentaje;


    }

    
    $table .='
    <tr>
    <th></th>
    <th></th>
    th></th>
    <th></th>
    <th></th>
    <th>TOTAL</th>
    <td>$ '.number_format($suma_cre,2).'</td>
    <th></th>
    </tr>'; 

    $table .= '
        <tr border=1>
      
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("descuentos_en_ordenes.pdf", 'I');
}
////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx11") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Venta por departamento</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r11($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr> 
        </tr>';

    $id=-1;
    $sumaTotalEmpresa=0;
    $sumaTotalEmpresaContado=0;
    $departamento=-1;

    foreach ($data AS $row) {
        $sumaTotalEmpresa=$sumaTotalEmpresa+floatval($row->venta);
        $sumaTotalEmpresaContado=$sumaTotalEmpresaContado+floatval($row->venta_contado);
        if($row->id!=$id){
        
            $table .= '
            <tr>
       
                <td>Departamento</td>
                <td># Estudios</td> 
                <td>Venta total</td>  
                <td>Estudios Contado</td>
                <td>Venta Contado</td> 
                <td>Estudios Credito</td> 
                <td>Estudios Credito</td>  
            </tr>';
            $id=$row->id;
        }


        $table .= '
        <tr>
            <td>'.$row->departamento.'</td>
            <td align="center">'.number_format($row->estudios,2).'</td> 
            <td align="rigth">$'.number_format($row->venta,2).'</td>  
            <td align="center">'.number_format($row->estudios_contado,2).'</td> 
            <td align="rigth">$'.number_format($row->venta_contado,2).'</td> 
            <td align="center">'.number_format($row->estudios_credito,2).'</td> 
            <td align="center">$'.number_format($row->venta_credito,2).'</td> 
        </tr>';

    }

    $table.='<tr>
        <td colspan="2" style="text-align:rigth;">Total</td>
        <td align="rigth">$'.number_format($sumaTotalEmpresa,2).'</td>
        <td></td>
        <td align="rigth">$'.number_format($sumaTotalEmpresaContado,2).'</td>
        <td colspan="2"></td>
    </tr></table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Venta_Departamento_Empresas.pdf", 'I');
}

if ($siglas == "rx34") {
    $pdf->SetHTMLHeader('

    <div style="height: 80px;">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Lista de Precios Secciones</h3>
                </td> 
                <td width=20% align="center">Publico General</td> 
            </tr>
        </table> 
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');

    $pdf->SetColumns(2,'J',5);

     $reportes = new Reportes();
    $data = $reportes->r34($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
       
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        font-size:10px;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>
   <div>
    <table width="100%" border=0 CELLSPACING=0 >
        ';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {

        if($tipo!=$row->id_seccion){

            if($tipo!="-1"){
       
            }
  
  
           
  
            $table .='
            <tr>
            <td colspan=3 style="background:gray;">SECCION: '.$row->nombre_seccion.' </td>
            
            </tr>';

            $table .='
            <tr>
            <th align="left" width="17%">Codigo</th> 
            <th align="left">Nombre</th> 
            <th align="left" width="17%">Importe</th>
            </tr>';
              
  
  
  
              $tipo=$row->id_seccion;
          }
     
  
      

          $table .= '
        <tr>
            <td>'.$row->no_estudio.'</td> 
            <td>'.$row->nombre_estudio.'</td>
            <td>$ '.number_format($row->precio_publico,2).'</td>   
        </tr>';
    } 

    $table .='
    <tr>
    
    </tr>'; 

    $table .= '
        <tr border=1>
      
        </tr>';
    $table .= '</table> 
    
    </div>';
   
    $pdf->WriteHTML($table);

    $pdf->Output("Lista_Publico_Departamentos.pdf", 'I');
}


if ($siglas == "rx31") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Pacientes con Aumento </h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r31($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
      </tr>';

    foreach ($data AS $row) {
               
               $porcentaje_aumento = 1+($row->aumento/100);
               $procen = round($row->importe/$porcentaje_aumento,2);
        if($tipo!=$row->fecha){

   

            $table .= "
            <tr>
            <td  colspan='5'>Fecha: $row->fecha </td>
            </tr>
            ";
            $tipo=$row->fecha;

            if($tipo!="-1"){

         

               $table .='
                <tr>
                    <th align="left">Codigo</th> 
                    <th align="left">Nombre</th> 
                    <th align="left">Importe</th> 
                    <th align="left">Aumento</th>
                    <th align="left">Total</th> 
        
                </tr>';
         
            
              
            }
         
              
        
        }

       
        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
            <td>'.$row->paciente.'</td> 
            <td>$' . number_format($procen, 2) .'</td> 
            <td align="center">'.$row->aumento.'</td> 
            <td>$' . number_format($row->importe, 2) .'</td> 
         
        </tr>';

   
    }
  
    $table .='
    <tr>
        <th align="left"></th> 
        <th align="left"></th> 
        <th align="left"></th> 
        <th align="left">Total</th>
        <th align="left"></th> 

    </tr>';
    $table .= '
        <tr border=1>
          </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("pacientes_por_dia", 'I');
}


if ($siglas == "rx704") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Lista de Trabajo</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r704($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
      </tr>';

    foreach ($data AS $row) {
               
               $porcentaje_aumento = 1+($row->aumento/100);
               $procen = round($row->importe/$porcentaje_aumento,2);
        if($tipo!=$row->consecutivo){

   

            $table .= "
            <tr>
            <th>Consecutivo: $row->consecutivo</th>
            <th>Nombre: $row->nombre_paciente</th>
            <th>Fecha: $row->fecha_registro</th>
            
            </tr>
            ";
            $tipo=$row->consecutivo;

            if($tipo!="-1"){

         

               $table .='
                <tr>
                    <th align="left">No. Estudio</th> 
                    <th align="left">Nombre del Estudio</th> 
                </tr>';
         
            
              
            }
         
              
        
        }

       
        $table .= '
        <tr>
            <td>'.$row->no_estudio.'</td> 
            <td>'.$row->nombre_estudio.'</td>  
           
         
        </tr>';

   
    }
  
  
    
    $table .= '
        <tr border=1>
          </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("pacientes_por_dia", 'I');
}


if ($siglas == "rx709") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Pacientes por Zonas</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r709($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
      </tr>';
    $tipo=-1;
    foreach ($data AS $row) {
  
        if(trim($tipo)!=trim(strtoupper($row->direccion))){

   

            $table .= "
            <tr>
            <th>Zona: ".strtoupper($row->direccion)."</th>

            
            </tr>
            ";
            $tipo=trim(strtoupper($row->direccion));

            if($tipo!="-1"){

         

               $table .='
                <tr>
                    <th align="left">Consecutivo</th> 
                    <th align="left">Nombre</th> 
                    <th align="left">Medico</th> 
                    <th align="left">Monto</th>
                </tr>';
            
              
            }
         
         
        
        }

        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
            <td>'.$row->nombre_paciente.'</td> 
            <td>'.$row->nombre_medico.'</td> 
            <td>$ '.$row->importe.'</td>   
           
         
        </tr>'; 
     

   
    }
  
  
    
    $table .= '
        <tr border=1>
          </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("pacientes_por_Zona", 'I');
}

if ($siglas == "rx12") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Corte por Departamento</h3>
                </td> 
                <td width=20% align="center"></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r12($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
        <th colspan="2">Corte:'.$data[0]->corteson.'</th>
      </tr>';
    $tipo=-1;
    $suma_depa=0;
    foreach ($data AS $row) {
  
             
            if($tipo!="-1"){

         
          
               $table .='
                <tr>
                    <th align="left">Departamento</th> 
                    <th align="left">Cantidad</th> 
                </tr>';
            
              
            }
         
         
        
         

        $table .= '
        <tr>
            <td>'.$row->departamento.'</td> 
            <td>$ '.$row->pp_pago.'</td> 
             
           
         
        </tr>'; 
     
        $suma_depa = $suma_depa+ floatval(str_replace(',','',$row->pp_pago));   
   
    }
  
  
    $table .= '
    <tr>
        <th align="rigth">Total</th> 
        <th>$ '.$suma_depa.'</th>   

    </tr>';
    $table .= '
        <tr border=1>
          </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("corte_por_departamento", 'I');
}

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if ($siglas == "rx710") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Comisiones Médicas</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


     $reportes = new Reportes();
    $data = $reportes->r8($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
         
        </tr>';

    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $tipo="-1";
    $suma=0;
    foreach ($data AS $row) {
        $ivas=$row->importe /1.16;
        $ivareal=$row->importe- $ivas;
        if($tipo!=$row->id_doctor){

          if($tipo!="-1"){
            $table .='
            <tr>
            <th colspan="2" align="right">Comision a Pagar:</th> 
    <th align="center">$ '. number_format($suma3-$suma2,2).' </th>

            </tr>';  
            $suma=0;
            $suma2=0;
            $suma3=0;
          }


         

            $table .= "
            <tr>
            <td  colspan='3'>Médico: $row->nombre_medico Alias: $row->aliasd</td>
            
            </tr>
            ";
            $table .='
            <tr>
            <th align="left">Orden #</th> 
            <th align="left">Nombre Paciente</th> 
            <th align="left">Comision</th> 
            
            </tr>'; 
            



            $tipo=$row->id_doctor;
        }

        $suma=$suma+$row->total;
        $suma2=$suma2+$row->deuda;
        $suma3=$suma3+$row->porcentaje;
      
        $table .= '
        <tr>
            <td>'.$row->consecutivo.'</td> 
            <td>'.$row->nombre_paciente.'</td> 
  
             <td align="center">$ '.number_format($row->porcentaje, 2) .'</td> 
            
        </tr>';
     



        $subtotal += $suma;
        $iva += $suma2;
        $total += $suma3;
    }
    $table .='
    <tr>
    <th colspan="2" align="rigth">Comision a Pagar:</th> 
    <th align="center">$ '. number_format($suma3-$suma2,2).' </th>
    </tr>'; 

    $table .= '
        <tr border=1>
      
        </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("comisiones_medicas.pdf", 'I');
}


if ($siglas == "rx711") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Ordenes enviadas a Maquila</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r711($id_sucursal, $fecha_inicial, $fecha_final, $palabra);
  
    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
      </tr>';

    $paquete=1;
    $paciente=1;
    $total=0;
    
    foreach ($data AS $row) {
               
               $porcentaje_aumento = 1+($row->aumento/100);
               $procen = round($row->importe/$porcentaje_aumento,2);
        if($tipo!=$row->consecutivo){

            $estilo='';
            $consecutivo_=$row->consecutivo;
            if($row->cancelado==1){
                $estilo='style="color:red !important;border:solid 1px red"'; 
                $consecutivo_=$row->consecutivo.'-CANCELADO';
            }

            $table .= "
            <tr >
            <th ".$estilo.">Consecutivo: $consecutivo_</th>
            <th ".$estilo.">Nombre: $row->nombre_paciente</th>
            <th ".$estilo.">Fecha: $row->fecha_registro</th>
            <th ".$estilo."> Consecutivo Matriz: $row->consecutivo_matriz | $row->consecutivo_matriz_imagen</th>
            
            
            </tr>
            ";
            $tipo=$row->consecutivo;

            if($tipo!="-1"){

         

               $table .='
                <tr>
                    <th align="left">No. Estudio</th> 
                    <th align="left">Nombre del Estudio</th> 
                    <th align="left">Costo maquila</th> 
                </tr>';
         
            
              
            }
         
              
        
        }
       
       if($row->id_paquete==null){
            if($row->cancelado==0){
                $total=$total+$row->precio_maquila;
                $precioMaquila=$precioT+$row->precio_maquila;
            }
            else{
                $precioMaquila=0;
            }
            
        $table .= '
        <tr>
            <td>'.$row->no_estudio.'</td> 
            <td>'.$row->nombre_estudio.'</td>  
            <td>$'.number_format($precioMaquila,2).'</td> 
         
        </tr>';
        $paquete=1;
       }
       else{
           if($row->id_paquete!=$paquete || $paciente!=$row->consecutivo){
               $total=$total+$row->precio_maquila_paquete;
               $table .= '
                <tr>
                <td>'.$row->alias.'</td> 
                <td>'.$row->nombre_paquete.'</td>  
                <td>$'.number_format($row->precio_maquila_paquete,2).'</td> 
         
                 </tr>';
                $paquete=$row->id_paquete;
                $paciente=$row->consecutivo;
           }
       }

   
    }
  
  
    
    $table .= '
        <tr border=1>
            <td colspan="2" align="rigth"><b>Total de maquila:</b></td>
            <td><b>$'.number_format($total,2).'</b></td>
            <td></td>
         </tr>
        <tr border=1>
          </tr>';
          
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Ordenes_enviados_maquilas", 'I');
}


if ($siglas == "rx712") {
    $pdf->SetHTMLHeader('
    <div style="height: 50px">
        <table  width="100%">
            <tr border: px solid #dee2e6;>
                <td width=20%><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 90px" /> </td> 
                <td align="center" width=60% style="font-weight: bold">
                    ' . $sucursal->cliente . '<br>
                    ' . $sucursal->nombre . '<br><br>
                    <h3>Lista de Pacientes Maquila</h3>
                </td> 
                <td width=20% align="center">Periodo <br>' . date("d/m/Y", strtotime($fecha_inicial)) . '<br>
                ' . date("d/m/Y", strtotime($fecha_final)) . '<br></td> 
            </tr>
        </table>
      
    </div>');

    $pdf->SetHTMLFooter('<font size=2>Connections-Labs © Copyright </font>  Página {PAGENO} de {nbpg}<br>' .
    $fecha_text .
    '');


    $reportes = new Reportes();
    $data = $reportes->r712($id_sucursal, $fecha_inicial, $fecha_final, $palabra);

    $table = '
    <style>
    
    table {
        width: 100%; 
        border-collapse: collapse;
        }
        th, td {
        padding: 7px;
        border: 1px solid #dee2e6;
        }
        th {
        height: 40px;
        text-align: left;
        }
    </style>

    <table width="100%" border=0 CELLSPACING=0 >
        <tr>
      </tr>';

    foreach ($data AS $row) {
               
               $porcentaje_aumento = 1+($row->aumento/100);
               $procen = round($row->importe/$porcentaje_aumento,2);
        if($tipo!=$row->consecutivo){

   

            $table .= "
            <tr>
            <th>Consecutivo: $row->consecutivo</th>
            <th>Nombre: $row->nombre_paciente</th>
            <th>Fecha: $row->fecha_registro</th>
            <th>$row->nombre $row->consecutivo_matriz</th>
            
            </tr>
            ";
            $tipo=$row->consecutivo;

            if($tipo!="-1"){

         

               $table .='
                <tr>
                    <th align="left">No. Estudio</th> 
                    <th align="left">Nombre del Estudio</th> 
                </tr>';
         
            
              
            }
         
              
        
        }

       
        $table .= '
        <tr>
            <td>'.$row->no_estudio.'</td> 
            <td>'.$row->nombre_estudio.'</td>  
           
         
        </tr>';

   
    }
  
  
    
    $table .= '
        <tr border=1>
          </tr>';
    $table .= '</table>';

    $pdf->WriteHTML($table);

    $pdf->Output("Ordenes_recibidas_maquilas", 'I');
}