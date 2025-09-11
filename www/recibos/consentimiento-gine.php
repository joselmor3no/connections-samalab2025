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
        <span style="font-weight: bold">CONSENTIMIENTO INFORMADO PARA TOMA DE MUESTRA
        GINECOLÓGICA</span><br>
        <span style="font-weight: bold">Fecha</span>' . $orden->fecha_orden . ' hrs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-weight: bold">Folio: </span><u>' . $codigo . '</u><br>
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
        Es muy importante para nosotros que reciba la presente información acerca del examen que fue solicitado por su médico tratante quien determinó que éste estudio es la
        mejor alternativa para obtener información, confirmar su diagnóstico o dar seguimiento al mismo, por favor lea con atención y no dude en consultar cualquier inquietud o
        duda al respecto.   
        </p><br>    
        <p align="justify">
        <b>Descripción</b><br>
        1. El examen que se le realizará requiere de una muestra ginecológica.
        2. Previa colocación de un espejo vaginal o no (según el caso); se tomará una muestra de secreción con ayuda de un aplicador especial, se colocará la muestra en
        diferentes medios (laminilla y tubos para examen directo y cultivo)

        </p>  <br>
        <p align="justify">
        <b>Riesgo</b><br>
        No existe ningún riesgo identificado al tomar las muestras. Cuando el cuello del útero se encuentra muy inflamado, en ocasiones se presenta un escaso sangrado vaginal
        que cede solo y no requiere tratamiento
        
    </p>  <br>
    <p align="justify">
    <b>Materiales</b><br>
    Para éste examen se utilizan elementos nuevos y desechables. Son procedimientos seguros y muy rara vez presentan complicaciones. Durante la toma de muestra se
    puede presentar alguna molestia como dolor leve que cederá rápidamente, su colaboración es muy importante para realizarlo en menor tiempo posible y con la menor
    incomodidad. No existen mecanismos para establecer el grado de riesgo, sin embargo el profesional asignado establecerá si se puede o no realizar la prueba solicitada y
    tomará las medidas necesarias para realizarla con la mayor seguridad.
    
    
</p>  <br>
<p align="justify">
<b>Declaraciones</b><br>
He comprendido con claridad todo lo descrito anteriormente y he tenido la oportunidad de preguntar y resolver todas mis dudas por lo mediante mi firma autorizo la
realización del examen, decisión que tomé libre y voluntariamente.


</p>  <br>

    Nombre:_________________________________________________________________ Firma:_______________________ Fecha:____________________
   
 </p>
 <p align="rigth">
 <br>
<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; FT06R00 Vigente 01092019 Página 1 de 1</b>
 </p>   
   
    </td> 
</tr>
</table>';

$pdf->WriteHTML($hml);


$pdf->Output("Consentimiento-Ginecologico.pdf", 'I');



?>