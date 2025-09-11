<?php

session_start();
$id_sucursal = $_SESSION["id_sucursal"];
$codigo = $_REQUEST["codigo"];
$alias = $_REQUEST["estudios"];
$tipo = $_REQUEST["tipo"]; 

require_once( $_SERVER["DOCUMENT_ROOT"] . '/libs/mpdf-5.7-php7-master/mpdf.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/laboratorio/Reportes.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/admision/Pacientes.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/admision/Etiquetas.php');

$reporte = new Reportes();
$id_orden = $reporte->getIdOrden($codigo, $id_sucursal);
$paciente = $reporte->getOrdenPaciente($id_orden)[0];


$catalogos = new Catalogos();
$sucursal = $catalogos->getSucursal($id_sucursal)[0];
$codebar = $catalogos->barsGenerator($codigo);

$etiquetas = new Etiquetas();
if ($tipo == 1) {
    $datos = $etiquetas->getEtiquetasRecipientesVarias($alias);
} else {
    $datos = $etiquetas->getEtiquetasRecipientes($alias, $id_orden);
}

$pdf = new mPDF('UTF-8', array(25, 50), '5pt', '', 1, 1, 1, 1, 9, 9, 'L');
$pdf->SetTitle("ETIQUETAS-" . $codigo);

$i = 1;
foreach ($datos AS $row) {



    $html = '<table width="100%" CELLPADDING=1 CELLSPACING=0 style="font-weight: bold">
        <tr>
          <td colspan="4" style="border:1px solid">_' . ($sucursal->nombre . " - " . $sucursal->id) . '</td>
        </tr>
        <tr>
          <td colspan="4" style="border:1px solid; border-width: 0 1px 1px 1px;">' . $paciente->paciente . '</td>
        </tr>
        <tr>
           <td colspan="4" style="border:1px solid; border-width: 0 1px 1px 1px;">' . $row->estudios . '</td>
        </tr>
        <tr>
          <td colspan="3" style="border:1px solid; border-width: 0 0 1px 1px;">' . ($paciente->sexo == "Nino" ? "Niño (a)" : $paciente->sexo) . '</td>
          <td style="border:1px solid; border-width: 0 1px 1px 0; text-align: right; font-size:7pt;">' . $codigo . '</td>
        </tr>
         <tr>
          <td style="border:1px solid; border-width: 0 1px 1px 1px;">' . $paciente->edad . '</td>
          <td style="border:1px solid; border-width: 0 0 1px 0;">' . ($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "Años") . '</td>
          <td width=40% style="border:1px solid; border-width: 0 0 1px 1px;">  <img width="100px" src="data:image/png;base64,' . $codebar . '"/>  </td>
          <td width=35% style="border:1px solid; border-width: 0 1px 1px 1px;">' . $row->recipiente . '</td>
        </tr>

    </table>';

    $pdf->WriteHTML($html);
    if ($i <= count($datos)) {
        $pdf->AddPage();
    }

    $i++;
}

$html = '<table width="100%" CELLPADDING=1 CELLSPACING=0 style="font-weight: bold">
        <tr>
          <td colspan="4" style="border:1px solid">' . ($sucursal->nombre . " - " . $sucursal->id) . '</td>
        </tr>
        <tr>
          <td colspan="4" style="border:1px solid; border-width: 0 1px 1px 1px;">' . $paciente->paciente . '</td>
        </tr>
        <tr>
           <td colspan="4" style="border:1px solid; border-width: 0 1px 1px 1px;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" style="border:1px solid; border-width: 0 0 1px 1px;">' . ($paciente->sexo == "Nino" ? "Niño (a)" : $paciente->sexo) . '</td>
          <td style="border:1px solid; border-width: 0 1px 1px 0; text-align: right; font-size:7pt;">' . $codigo . '</td>
        </tr>
         <tr>
          <td style="border:1px solid; border-width: 0 1px 1px 1px;">' . $paciente->edad . '</td>
          <td style="border:1px solid; border-width: 0 0 1px 0;">' . ($paciente->tipo_edad != "Anios" ? $paciente->tipo_edad : "Años") . '</td>
          <td width=40% style="border:1px solid; border-width: 0 0 1px 1px;">  <img width="100px" src="data:image/png;base64,' . $codebar . '"/>  </td>
          <td width=35% style="border:1px solid; border-width: 0 1px 1px 1px;"></td>
        </tr>

    </table>';

    $pdf->WriteHTML($html);

$pdf->Output("ETIQUETAS-" . $codigo . ".pdf", 'I');
?>