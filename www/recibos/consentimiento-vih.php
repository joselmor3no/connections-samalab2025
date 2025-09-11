<?php
session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$codigo = $_REQUEST["codigo"];

require_once( $_SERVER["DOCUMENT_ROOT"] . '/libs/mpdf-5.7-php7-master/mpdf.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/laboratorio/Reportes.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/admision/Pacientes.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

$reporte = new Reportes();
$id_orden = $reporte->getIdOrden($codigo, $id_sucursal);
$paciente = $reporte->getOrdenPaciente($id_orden)[0];
$edad = $paciente->edad . " " . ($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "años");

$pacientes = new Pacientes();
$datos = $pacientes->getOrden($id_orden);
$orden = $datos["orden"][0];
/*$orden_detalle = $datos["detalle"]*/

$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];


$pdf = new mPDF('UTF-8', 'Letter', '8.5pt', '', 10, 10, 10, 10, 9, 9, 'P');

$head = '<table width="100%" style="font-size: 10pt;">
<tr>
    <td width="20%" align="center"><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 100px" /> </td> 
    <td width="5%"></td>
    <td width="75%">
        <span style="font-weight: bold">CONSENTIMIENTO INFORMADO PARA REALIZAR PRUEBA DE VIH</span><br>
        <span style="font-weight: bold">Fecha: </span>' . $orden->fecha_orden . ' hrs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-weight: bold">Folio: </span><u>' . $codigo . '</u><br>
        <span style="font-weight: bold">Paciente: </span>' . $paciente->paciente . ' <span style="font-weight: bold">Edad:</span> ' . $edad . ' <span style="font-weight: bold">Sexo:</span> ' . ($paciente->sexo == "Nino" ? "Niño (a)" : $paciente->sexo ) . '<br>
        <span style="font-weight: bold">Tel. Pac:</span> ' . $paciente->telefono . ' <span style="font-weight: bold">Fecha de Nacimiento: </span>' . $paciente->fecha_nac . '<br>
        <span style="font-weight: bold">Sucursal:</span> ' . $sucursal->nombre . '<br>
    </td> 
</tr>
</table><br>';

$pdf->WriteHTML($head);

$hml = '<table width="100%" >
<tr>
    <td align="justify">
        <p align="justify">
        El presente documento tiene como objetivo que usted, luego de haber recibido información, manifieste de manera libre y voluntaria, a través de su firma, la autorización o
        rechazo a la realización del examen de detección del VIH.   
        </p><br>    
        <p align="justify">
        El examen para detectar el virus del SIDA (Virus de Inmunodeficiencia Humana, VIH) se realiza a partir de una muestra de sangre que al ser procesada, puede entregar un
        resultado negativo o reactivo. El resultado negativo significa que no se encuentran anticuerpos al VIH; el resultado reactivo significa que se detecta la presencia de
       anticuerpos al VIH y que se requieren realizar pruebas confirmatorias de acuerdo a la NORMA Oficial Mexicana NOM-010-SSA2-2010, Para la prevención y el control de la
      infección por Virus de la Inmunodeficiencia Humana las cuales tienen costo adicional de acuerdo a la lista de precios vigente y se realizan únicamente si las solicita..
        </p>  <br>
    <p align="justify">
   <b> Declaraciones</b><br>
    He comprendido con claridad todo lo descrito anteriormente y he tenido la oportunidad de preguntar y resolver todas mis dudas por lo mediante mi firma autorizo la
    realización del examen, decisión que tomé libre y voluntariamente
</p>  <br>
<p align="justify">
<b>Nota:</b>
 La interpretación de resultados de las pruebas mencionadas requiere de la interpretación de personal médico.
</p>  <br>

    Nombre:_________________________________________________________________ Firma:_______________________ Fecha:____________________
    </p>
    <p align="rigth">
    <br>
   <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; FT07R00 Vigente 01092019 Página 1 de 1</b>
    </p> 
   
   
    </td> 
    
</tr>
</table>';

$pdf->WriteHTML($hml);


$pdf->Output("Consentimiento-VIH.pdf", 'I');



?>