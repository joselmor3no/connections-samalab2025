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


$pdf = new mPDF('UTF-8', 'Letter', '9pt', '', 10, 10, 10, 10, 9, 9, 'P');

$head = '<table width="100%" style="font-size: 10pt;">
<tr>
    <td width="20%" align="center"><img src="../../images-sucursales/' . $sucursal->img . '" style="height: 100px" /> </td> 
    <td width="5%"></td>
    <td width="75%">
        <span style="font-weight: bold">CONSENTIMIENTO INFORMADO PARA LA TOMA DE MUESTRAS SANGUINEAS</span><br>
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
        La toma de muestras consiste en recoger una muestra biológica de su organismo. Las muestras biológicas más solicitadas en la práctica clínica son: sangre, orina, heces y
        esputo, aunque pueden recogerse otras. Estas muestras sirven para realizar el estudio (bioquímico, citológico, microbiológico, etc.) que puede aportar información muy útil
        sobre el diagnóstico o la evolución de su enfermedad lo que permitirá un tratamiento más adecuado. Tan importante como su obtención es el manejo de la muestra, por lo
        que existen normas estrictas para la correcta recolección, manipulación, transporte y conservación de la muestra, así como para su adecuado procesamiento en
        laboratorio   
        </p><br>    
        <p align="justify">
        La toma de la muestras presenta riesgos bajos. Para garantizar la seguridad del paciente, se efectuará por personal profesional capacitado y bajo condiciones de seguridad
        y de asepsia rigurosa. Los riesgos más frecuentes: En el caso de la toma de muestra sanguínea, puede producirse un mínimo hematoma en la zona de la punción, por lo
        que será conveniente que después se realice presión sobre la zona. El resto de las muestras habitualmente recogidas (orina, esputos, heces) no presentan riesgos. Riesgos
        infrecuentes: En algunos pacientes, por sus características individuales, resulta difícil extraer la muestra de sangre, por lo que tal vez sea preciso puncionarles repetidas
        veces hasta obtener la muestra de sangre. Entiendo que la toma de muestras es voluntaria y que puedo retirar mi consentimiento antes de que ésta sea realizada. Una
        vez entendido y resuelto mis dudas acepto me realicen el procedimiento.
        </p>  <br>
        <p align="justify">
    Conozco y acepto la realización de los estudios listados, así como los riesgos descritos en la carta de consentimiento informado, he tenido la oportunidad de resolver mis
    dudas y se ha resuelto de manera clara.
    </p>  <br>

    Nombre:_________________________________________________________________ Firma:_______________________ Fecha:____________________
    </td> 
</tr>
</table>';

$pdf->WriteHTML($hml);


$pdf->Output("Consentimiento.pdf", 'I');



?>