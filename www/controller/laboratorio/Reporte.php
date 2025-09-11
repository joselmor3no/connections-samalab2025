<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/laboratorio/Reportes.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/laboratorio/Interfaces.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Reporte {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'save-resultados-lab') {
            $this->addResultadosLab();
        } else if ($opc == 'get-componentes') {
            $this->getComponentes();
        } else if ($opc == 'get-texto') {
            $this->getTexto();
        } else if ($opc == 'imprimir-reporte') {
            $this->imprimirReporte();
        } else if ($opc == 'restablecer-reporte') {
            $this->restablecerReporte();
        } else if ($opc == 'borrar-resultado') {
            $this->borarResultado();
        } else if ($opc == 'borrar-resultado-texto') {
            $this->borarResultadoTexto();
        } else if ($opc == 'generar-reporte') {
            $this->generarReporte();
        } else if ($opc == 'bitacora-estudio') {
            $this->getBitacoraEstudio();
        } else if ($opc == 'get-orden-paciente') {
            $this->getOrdenPaciente();
        } else if ($opc == 'upload-resultado') {
            $this->uploadResultado();
        } else if ($opc == 'delete-pdf-estudio') {
            $this->deleteResultado();
        } else if ($opc == 'get-orden') {
            $this->getOrden();
        } else if ($opc == 'imprimir-reporte-paciente') {
            $this->imprimirReportePaciente();
        } else if ($opc == 'preview-reporte-paciente') {
            $this->PreviewReportePaciente();
        } else if ($opc == 'info-envio-resultados') {
            $this->infoEnvioResultados();
        } else if ($opc == 'resultado-maquila') {
            $this->resultadoMaquila();
        } else if ($opc == 'buscar-paciente') {
            $this->buscarPaciente();
        }else if ($opc == 'orden-complementarios') {
            $this->buscarDComplementariosOrden(); 
        }else if ($opc == 'guarda_complementario') {
            $this->guardaDComplementariosOrden();
        }else if ($opc == 'nuevo-complementario') {
            $this->guardaNuevoComplementario($_REQUEST['id_documento'],$_REQUEST['nombre_documento']);
        }else if ($opc == 'elimina-complementario') {
            $this->eliminaComplementario($_REQUEST['id_documento']);
        }
        else if ($opc == 'elimina_complementario_orden') {
            $this->eliminaComplementarioOrden($_REQUEST['ordenComplementario'],$_REQUEST['estudioComplementario']);
        }

    }

    function eliminaComplementarioOrden($id_orden,$id_documento){
        $reportes = new Reportes();
        $respuesta = $reportes->eliminaComplementarioOrdenM($id_orden,$id_documento);
        header("Location: /documentos-complementarios?msg=ok");
    }

    function eliminaComplementario($id_documento){
        $reportes = new Reportes();
        $respuesta = $reportes->eliminaComplementarioM($id_documento);
        return;
    }

    function guardaNuevoComplementario($id_documento,$nombre_documento){
        $reportes = new Reportes();
        if($id_documento==-1){
            $respuesta = $reportes->guardaNuevoComplementario($nombre_documento);
        }
        else{
            $respuesta = $reportes->editaComplementario($id_documento,$nombre_documento);
        }
        echo $respuesta;
        return;
        
    }

    function guardaDComplementariosOrden(){
        if($_FILES['documento']["tmp_name"]){
            session_start();
            $ruta="../../reportes/".$_SESSION['ruta']."/resultados/".$_REQUEST['ordenComplementario'].'_'.$_REQUEST['nombreComplementario'].".pdf";
           if(move_uploaded_file($_FILES['documento']["tmp_name"], $ruta)){
               $reportes = new Reportes();
               $respuesta = $reportes->guardaOrdenComplementario($_REQUEST['ordenComplementario'],$_REQUEST['estudioComplementario'],$_REQUEST['idComplementario']);
               if($respuesta=="ok"){
                    header("Location: /documentos-complementarios?msg=ok");
               }
               else{
                    header("Location: /documentos-complementarios?msg=error");
               }
            } 
        }
        else{
            header("Location: /documentos-complementarios?msg=error");
        }
    }

    function buscarDComplementariosOrden($id_orden,$id_documento){
        $reportes = new Reportes();
        
        $respuesta = $reportes->buscarDComplementariosOrdenM($id_orden,$id_documento); 
        echo strtoupper($respuesta[0]->nombre.".pdf");
    }

    function buscarPaciente(){

        $reportes = new Reportes();
        $respuesta = $reportes->buscarPacienteM($_REQUEST['busqueda']);
        $arregloDatos=[];
        foreach ($respuesta as $row => $item) {
            $opciones.='<option>'.$item->paciente.'</option>';
            $arregloDatos[$row]['id_orden']=$item->id_orden;
        }
        $res['opciones']=$opciones;
        $res['datos']=$arregloDatos;
        echo json_encode( $res);
    }

    function addResultadosLab() {
        $data = [];

        $reportes = new Reportes();
        $data = $reportes->addResultadosLab($_REQUEST);

        $reportes->close();

        $rango = $_REQUEST["rango"];
        $no_estudio = $_REQUEST["estudio"];
        $fecha_inicial = $_REQUEST["ini"];
        $fecha_final = $_REQUEST["fin"];

        if ($rango != "") {
            header("Location: /reporte-global?codigo=" . $_REQUEST["codigo"] . "&ini=" . $fecha_inicial . "&fin=" . $fecha_final . "&msg=ok");
        } else if ($no_estudio != "") {
            header("Location: /reporte-estudio?estudio=" . $no_estudio . "&ini=" . $fecha_inicial . "&fin=" . $fecha_final . "&msg=ok");
        } else {
            header("Location: /reporte-paciente?codigo=" . $_REQUEST["codigo"] . "&id_sucursal=" . $_REQUEST["id_sucursal"] . "&msg=ok");
        }
    }

    function getComponentes() {

        $id_orden_estudio = $_REQUEST["id"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        //
        $edad = $_REQUEST["edad"];
        $tipo_edad = $_REQUEST["tipo_edad"];
        $sexo = $_REQUEST["sexo"];

        $datos = $this->getComponentesData($id_orden_estudio, $id_sucursal, $edad, $tipo_edad, $sexo);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

    function getComponentesData($id_orden_estudio, $id_sucursal, $edad, $tipo_edad, $sexo) {

        $data = [];
        $reportes = new Reportes();
        $interfaces = new Interfaces();
        $existeInterfaz = $interfaces->getArchivoSucursal($_SESSION['ruta']);

        $data = $reportes->getOrdenEstudioComponentes($id_orden_estudio, $id_sucursal);
        foreach ($data AS $row) {

            if ($row->id_cat_componente == null) {

                $datos[] = array(
                    "componente" => $row,
                );
            } else if ($row->id_cat_componente == 1 || $row->id_cat_componente == 2) {

                if (($row->resultado == '' || $row->resultado == null) && $existeInterfaz == 1) {
                    $resultadoInterfaz = $interfaces->getResultadoInterfaz($_SESSION['ruta'], $row->id_estudio, $row->interfaz_letra, $row->consecutivo_orden, $row->numero_estudio);
                    $row->resultado = $resultadoInterfaz;
                }

                $paciente = array(
                    "id_componente" => $row->id,
                    "edad" => $edad,
                    "tipo_edad" => $tipo_edad,
                    "sexo" => $sexo
                );

                $referencia = $reportes->getReferenciaNumerico($paciente);
                $formula = $reportes->getFormula($row->id);
                $tabla = $reportes->getTabla($row->id, $sexo);

                $datos[] = array(
                    "componente" => $row,
                    "referencia" => $referencia[0],
                    "formula" => $formula[0],
                    "tabla" => $tabla[0]->valor
                );
            } else if ($row->id_cat_componente == 3) {
                $referencia = $reportes->getReferenciaLista($row->id);
                $tabla = $reportes->getTabla($row->id, $sexo);
                $datos[] = array(
                    "componente" => $row,
                    "referencia" => $referencia,
                    "tabla" => $tabla[0]->valor
                );
            } else if ($row->id_cat_componente == 4) {
                $tabla = $reportes->getTabla($row->id, $sexo);
                $datos[] = array(
                    "componente" => $row,
                    "tabla" => $tabla[0]->valor
                );
            }
        }

        $reportes->close();
        return $datos;
    }

    function getTexto() {

        $id_orden_estudio = $_REQUEST["id"];
        $id_orden = $_REQUEST["id_orden"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $data = [];
        $reportes = new Reportes();
        $data = $reportes->getOrdenEstudioTexto($id_orden_estudio, $id_sucursal);

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data[0]);
    }

    function imprimirReporte() {

        $id_sucursal = $_REQUEST["id_sucursal"];
        $id_orden = $_REQUEST["id_orden"];
        $edad = $_REQUEST["hide_edad"];
        $tipo_edad = $_REQUEST["hide_tipo_edad"];
        $sexo = $_REQUEST["hide_sexo"];

        $bh = [];
        $ego = [];
        $estandar = [];
        $paquete = [];
        $texto = [];

        $reportes = new Reportes();
        $estudios = $reportes->imprimirReporte($_REQUEST);

        
        foreach ($estudios AS $row) {
            if ($row["id_tipo_reporte"] == 1) {
                $bh[] = "1";
            } else if ($row["id_tipo_reporte"] == 2) {
                $ego[] = "1";
            } else if ($row["id_tipo_reporte"] == 3) {
                $estandar[] = "1";
            } else if ($row["id_tipo_reporte"] == 4) {
                $paquete[] = "1";
            } else if ($row["id_tipo_reporte"] == 5) {
                $texto[] = "1";
            }
        }

     
        $formatos[] = is_array($bh) && count($bh) > 0 ? "BH" : "";
        $formatos[] = is_array($ego) && count($ego) > 0 ? "EGO" : "";
        $formatos[] = is_array($estandar) && count($estandar) > 0 ? "ESTANDAR" : "";
        $formatos[] = is_array($paquete) && count($paquete) > 0 ? "PAQUETE" : "";
        $formatos[] = is_array($texto) && count($texto) > 0 ? "TEXTO" : "";

        
        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($formatos);
    }

    function imprimirReporteBiometria($estudio, $paciente, $formato, $sucursal, $laboratorio) {
        $ruta = $_SESSION["ruta"];
        $formato_biometria = "REPORTE-BIOMETRIA-" . strtotime("NOW") . ".pdf";
        require_once('../../reportes/' . $ruta . '/reporte-biometria.php');

        return $formato_biometria;
    }

    function imprimirReporteExamenOrina($estudio, $paciente, $formato, $sucursal, $laboratorio) {
        $ruta = $_SESSION["ruta"];
        $formato_examen_orina = "REPORTE-EXAMEN-ORINA-" . strtotime("NOW") . ".pdf";
        require_once('../../reportes/' . $ruta . '/reporte-examen-orina.php');

        return $formato_examen_orina;
    }

    function imprimirReporteEstandar($estandar, $paciente, $formato, $sucursal, $laboratorio) {
        $ruta = $_SESSION["ruta"];
        $formato_estandar = "REPORTE-ESTANDAR-" . strtotime("NOW") . ".pdf";
        require_once('../../reportes/' . $ruta . '/reporte-estandar.php');

        return $formato_estandar;
    }

    function imprimirReportePaquete($paquete, $paciente, $formato, $sucursal, $laboratorio) {
        $ruta = $_SESSION["ruta"];
        $formato_paquete = "REPORTE-PAQUETE-" . strtotime("NOW") . ".pdf";
        require_once('../../reportes/' . $ruta . '/reporte-paquete.php');

        return $formato_paquete;
    }

    function imprimirReporteTexto($texto, $paciente, $formato, $sucursal, $laboratorio) {
        $ruta = $_SESSION["ruta"];
        $formato_texto = "REPORTE-TEXTO-" . strtotime("NOW") . ".pdf";
        require_once('../../reportes/' . $ruta . '/reporte-texto.php');

        return $formato_texto;
    }

    function imprimirReportePaciente() {

        $id_sucursal = $_REQUEST["id_sucursal"];
        $codigo = $_REQUEST["codigo"];
        $expediente = $_REQUEST["expediente"];

        $reportes = new Reportes();
        $id_orden = $reportes->getIdOrden($codigo, $id_sucursal);
        $estudios = $reportes->estudiosPacientesImprimir($id_orden, $expediente, $id_sucursal);
        $paciente = $reportes->getOrdenPaciente($id_orden)[0];

        $bh = [];
        $ego = [];
        $estandar = [];
        $paquete = [];
        $texto = [];

        foreach ($estudios AS $row) {
            if ($row->id_tipo_reporte == 1 && $row->impresion > 0) {
                $bh = array(
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "componentes" => $this->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo)
                );
            } else if ($row->id_tipo_reporte == 2 && $row->impresion > 0) {
                $ego = array(
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "componentes" => $this->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo)
                );
            } else if ($row->id_tipo_reporte == 3 && $row->impresion > 0) {
                $estandar[] = array(
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "componentes" => $this->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo)
                );
            } else if ($row->id_tipo_reporte == 4 && $row->impresion > 0) {
                $paquete[] = array(
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "paquete" => $row->paquete,
                    "componentes" => $this->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo)
                );
            } else if ($row->id_tipo_reporte == 5 && $row->impresion > 0) {
                $texto[] = array(
                    "no_estudio" => $row->no_estudio,
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "resultado" => $reportes->getOrdenEstudioTexto($row->id_detalle_orden, $id_sucursal)[0]->resultado
                );
            }
        }

        $data = [];
        if (count($bh) > 0)
            $data += ["bh" => $bh];

        if (count($ego) > 0)
            $data += ["ego" => $ego];

        if (count($estandar) > 0)
            $data += ["estandar" => $estandar];

        if (count($paquete) > 0)
            $data += ["paquete" => $paquete];

        if (count($texto) > 0)
            $data += ["texto" => $texto];

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function PreviewReportePaciente() {

        $id_sucursal = $_REQUEST["id_sucursal"];
        $codigo = $_REQUEST["codigo"];
        $expediente = $_REQUEST["expediente"];

        $reportes = new Reportes();
        $id_orden = $reportes->getIdOrden($codigo, $id_sucursal);
        $estudios = $reportes->estudiosPacientesImprimir($id_orden, $expediente, $id_sucursal);
        $paciente = $reportes->getOrdenPaciente($id_orden)[0];

        $bh = [];
        $ego = [];
        $estandar = [];
        $paquete = [];
        $texto = [];

        foreach ($estudios AS $row) {
            if ($row->id_tipo_reporte == 1 && $row->reportado > 0) {
                $bh = array(
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "componentes" => $this->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo)
                );
            } else if ($row->id_tipo_reporte == 2 && $row->reportado > 0) {
                $ego = array(
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "componentes" => $this->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo)
                );
            } else if ($row->id_tipo_reporte == 3 && $row->reportado > 0) {
                $estandar[] = array(
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "componentes" => $this->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo)
                );
            } else if ($row->id_tipo_reporte == 4 && $row->reportado > 0) {
                $paquete[] = array(
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "paquete" => $row->paquete,
                    "componentes" => $this->getComponentesData($row->id_detalle_orden, $id_sucursal, $paciente->edad, $paciente->tipo_edad, $paciente->sexo)
                );
            } else if ($row->id_tipo_reporte == 5 && $row->reportado > 0) {
                $texto[] = array(
                    "no_estudio" => $row->no_estudio,
                    "estudio" => $row->nombre_estudio,
                    "pagina" => $row->pagina,
                    "resultado" => $reportes->getOrdenEstudioTexto($row->id_detalle_orden, $id_sucursal)[0]->resultado
                );
            }
        }

        $data = [];
        if (count($bh) > 0)
            $data += ["bh" => $bh];

        if (count($ego) > 0)
            $data += ["ego" => $ego];

        if (count($estandar) > 0)
            $data += ["estandar" => $estandar];

        if (count($paquete) > 0)
            $data += ["paquete" => $paquete];

        if (count($texto) > 0)
            $data += ["texto" => $texto];

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function restablecerReporte() {

        $data = [];
        $reportes = new Reportes();

        $id_estudio = $_REQUEST["id_estudio"];
        $id_orden = $_REQUEST["id_orden"];

        $reportes->restablecerReporte($id_estudio, $id_orden);

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function borarResultado() {

        $data = [];
        $reportes = new Reportes();

        $id_estudio = $_REQUEST["id_estudio"];
        $id_orden = $_REQUEST["id_orden"];

        $reportes->borarResultado($id_estudio, $id_orden);

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function borarResultadoTexto() {

        $data = [];
        $reportes = new Reportes();

        $id_estudio = $_REQUEST["id_estudio"];
        $id_orden = $_REQUEST["id_orden"];

        $reportes->borarResultadoTexto($id_estudio, $id_orden);

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

    function getBitacoraEstudio() {

        $id_detalle_orden = $_REQUEST["id_estudio"];

        $reportes = new Reportes();

        $datos = $reportes->getBitacoraEstudio($id_detalle_orden);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

    function getOrdenPaciente() {

        $id_orden = $_REQUEST["id_orden"];

        $reportes = new Reportes();

        $datos = $reportes->getOrdenPaciente($id_orden);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos[0]);
    }

    function uploadResultado() {

        $data = "";
        $reportes = new Reportes();

        $expediente = $_REQUEST["expediente"];
        $id_estudio = $_REQUEST["id_estudio"];
        $file = $_FILES["file"];

        $data = $reportes->uplodResultado($id_estudio, $expediente, $file);

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function deleteResultado() {
        $reportes = new Reportes();

        $expediente = $_REQUEST["expediente"];
        $id_estudio = $_REQUEST["id_estudio"];
        $file = $_FILES["file"];

        $data = $reportes->deleteResultado($id_estudio);

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function getOrden() {

        $id_orden = $_REQUEST["id_orden"];

        $reportes = new Reportes();
        $estudios = $reportes->getOrdenEstudio($id_orden);

        foreach ($estudios AS $estudio) {
            ?>
            <tr id="index_<?= $estudio->id ?>" class="bg-estudios">

                <td align="center">
                    <div class="custom-control custom-checkbox">
                        <input id="imprimir_<?= $estudio->id ?>" <?= $estudio->reportado > 0 ? "checked" : "disabled" ?> type="checkbox" name="imprimir[]" value="<?= $estudio->id ?>" class="custom-control-input imprimir">
                        <label class="custom-control-label" for="imprimir_<?= $estudio->id ?>"></label>
                    </div>
                </td>
                <td align="center">
                    <div class="custom-control custom-checkbox">
                        <input id="pagina_<?= $estudio->id ?>" <?= $estudio->reportado > 0 ? "" : "disabled" ?> <?= $estudio->pagina == 1 ? "checked" : "" ?> type="checkbox" name="pagina[]" value="<?= $estudio->id ?>"  class="custom-control-input pagina">
                        <label class="custom-control-label" for="pagina_<?= $estudio->id ?>"></label>
                    </div>
                </td>
                <td>
                    <button id="btn-estudio-<?= $estudio->id ?>" data-id="<?= $estudio->id ?>" data-estudio="<?= $estudio->estudio ?>" data-impresion="<?= $estudio->impresion ?>" data-reportado="<?= $estudio->reportado ?>" 
                            class="<?= $estudio->impresion > 0 ? "text-success" : ( $estudio->reportado > 0 ? "text-primary" : "text-black" ) ?> btn btn-link pt-0 pb-0 estudio"  data-tipo="<?= $estudio->resultado_componente == 1 ? "componente" : "texto" ?>"> 
                                <?= $estudio->estudio ?>
                    </button>
                </td>
                <td class="text-center p-1">
                    <button type="button" data-id="<?= $estudio->id ?>"  data-estudio="<?= $estudio->estudio ?>" class="btn btn-sm btn-primary rounded-circle load-activity "  title="Bitácora" data-toggle="tooltip" >
                        <i class="fas fa-clipboard-list"></i>
                    </button>
                </td>
            </tr> 

            <?php
        }
    }

    function resultadoMaquila() {
        $tipo_componente = "componente";
        // Sucursal Matriz
        $id_sucursal_maquila = $_REQUEST['id_matriz'];
        //Sucursal destino
        $id_sucursal = $_REQUEST['id_sucursal'];;
        //Maquila
        $id_orden_maquila = $_REQUEST['id_orden'];

        $reportes = new Reportes();
        $reportes->resultadoMaquila($id_orden_maquila, $id_sucursal_maquila, $id_sucursal);

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok"); 
    }

    function infoEnvioResultados() {

        $expediente = $_REQUEST["expediente"];
        $codigo = $_REQUEST["codigo"];

        $reportes = new Reportes();
        $data = $reportes->infoEnvioResultados($expediente, $codigo)[0];

        $reportes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function obtener_estructura($ruta) {
        global $msg;
        $now = strtotime(date("Y-m-d H:i:s"));

        if (is_dir($ruta)) {
            $gestor = opendir($ruta);

            while (($archivo = readdir($gestor)) !== false) {

                $ruta_completa = $ruta . "/" . $archivo;
                if ($archivo != "." && $archivo != "..") {
                    if (is_dir($ruta_completa)) {
                        $this->obtener_estructura($ruta_completa);
                    } else {

                        $pos = strpos($ruta_completa, "recibo");
                        if ($pos === false) {
                            
                        } else {

                            $aux = explode("_", $ruta_completa);
                            if ($aux[1] != "") {
                                $aux2 = explode(".", $aux[1]);
                                $tiempo = $aux2[0];

                                $diff = $now - $tiempo;

                                //TICKET IMPRESO HACE MAS DE UNA HORA BORRAR
                                if ($diff > 3600) {
                                    unlink($ruta_completa);
                                    $msg .= $ruta_completa . "<br>";
                                }
                            }
                        }
                    }
                }
            }

            // Cierra el gestor de directorios
            closedir($gestor);
        } else {
            $msg .= "No es una ruta de directorio valida<br/>";
        }
    }

}

$controller = new Reporte();

