<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/imagen/imagenm.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Imagen {

	function __construct() { 

        $opc = $_REQUEST["opc"];
        if ($opc == 'eliminar_dcm') {
            $this->eliminarDCM();
        } else if ($opc == 'descomprimir_dcm') {
            $this->descomprimirDCM();
        } else if ($opc == 'concluir_dcm') {
            $this->concluirDCM();
        } else if ($opc == 'quitar_conclusion') {
            $this->quitarConclusionDCM();
        }else if ($opc == 'estudios_seccion') {
            $this->listaEstudiosSeccion();
        } else if ($opc == 'formato_estudio') {
            $this->formatoEstudio();
        }else if ($opc == 'guarda_formato') {
            $this->guardaFormato();
        }else if ($opc == 'contenido_formato') {
            $this->contenidoFormato();
        }else if ($opc == 'eliminar_formato') {
            $this->eliminarFormato();
        }else if ($opc == 'resultado_dcm' && $_REQUEST['id_dcm']!="") {
            $this->resultadoDCM();
        }else if($opc=='concluir_dcm_local'){
            $this->concluirLocalDCM();
        }else if($opc=='asignar_dental'){
            $this->asignarEstudioDental();
        } 


    }

    public function asignarEstudioDental(){
        $catalogos = new Catalogos();
        $sucursal=$catalogos->getSucursal($_SESSION["id_sucursal"])[0];
        $origen=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$sucursal->prefijo_imagen.'_dental/'.$_REQUEST['ruta'];
        $destino=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$sucursal->prefijo_imagen.'/'.$_REQUEST['ruta'];
        if(file_exists($origen)){
            mkdir($destino);
            rename($origen, $destino."/".$_REQUEST['ruta']);
            $datos=array("id_paciente"=>$_REQUEST['id_paciente'],
                         "id_orden"=>$_REQUEST['id_orden'],
                         "id_estudio"=>$_REQUEST['id_estudio'],
                         "fecha_registro"=>$_REQUEST['fecha_registro'],
                         "ruta"=>$_REQUEST['ruta'],
                    );
            $imagenM = new Imagenm();
            $respuesta=$imagenM->insertaPacienteDental($datos);
            header("Location: /estudios-dentales?msg=ok");
        }
    }

    public function concluirLocalDCM(){
        $imagenM = new Imagenm();
        $respuesta=$imagenM->concluirLocalDCMM($_REQUEST['id_dcm']);
    }

    public function resultadoDCM(){
        $imagenM = new Imagenm();
        $respuesta=$imagenM->resultadoDCMM($_REQUEST['id_dcm']);
        echo $respuesta[0]->resultado;
    }

    public function listaFormatos(){
        $imagenM = new Imagenm();
        $respuesta=$imagenM->listaFormatosSelect();
        echo '<option value="-1">Seleccione...</option>';
        foreach ($respuesta AS $row=>$item) {
            echo '<option value="'.$item->id.'">'.$item->nombre.'</option>';
        }
    }

    public function eliminarFormato(){
        $imagenM = new Imagenm();
        $respuesta=$imagenM->eliminarFormatoM($_REQUEST['id_formato']);
    }
    
    public function contenidoFormato(){ 
        $imagenM = new Imagenm(); 
        $respuesta=$imagenM->contenidoFormatoM($_REQUEST['id_formato']);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($respuesta[0]);
    }

    public function obtenerFormatosMedico(){
        $imagenM = new Imagenm();
        $respuesta=$imagenM->todosFormatosM(); 
        foreach ($respuesta AS $row=>$item) {
            echo '<tr>
                <td>'.$item->nombre.'</td>
                <td>
                    <button class="btn btn-warning btn-sm btn-editar_formato" data-id="'.$item->id.'">Editar</button>
                    <button class="btn btn-danger btn-sm btn-eliminar_formato" data-id="'.$item->id.'">-</button>
                </td>
            </tr>';
        }
    }

    public function formatoEstudio(){
                $imagenM = new Imagenm();
        $respuesta=$imagenM->formatoEstudioM($_REQUEST['id_cat_estudio']); 
        foreach ($respuesta AS $row=>$item) {
            echo '<tr>
                <td>'.$item->nombre.'</td>
                <td>
                    <button class="btn btn-warning btn-sm btn-editar_formato" data-id="'.$item->id.'">Editar</button>
                    <button class="btn btn-danger btn-sm btn-eliminar_formato" data-id="'.$item->id.'">-</button>
                </td>
            </tr>';
        }
    }

    public function guardaFormato(){
        $imagenM = new Imagenm();

        if($_REQUEST['id_formato']!="-1" && $_REQUEST['id_formato']!=-1)
            $respuesta=$imagenM->editaFormatoM();
        else
            $respuesta=$imagenM->guardaFormatoM();
        echo $respuesta;
    }

    public function listaEstudiosSeccion(){
        $imagenM = new Imagenm();
        $respuesta=$imagenM->listaEstudiosSeccionM($_REQUEST['seccion']);
        $listaOpciones='<option value="">Seleccione...</option>';

        foreach ($respuesta AS $row=>$item) {
            $listaOpciones.='<option value="'.$item->id.'">'.$item->nombre_estudio.'</option>';
        }
        $res['lista']=$listaOpciones;
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($res);
    }

    public function quitarConclusionDCM(){
        $imagenM = new Imagenm();
        $destino=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$_REQUEST['ruta'].'/'.$_REQUEST['nombrep'].'/';
        $origen=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$_REQUEST['ruta'].'_pacientes/'.$_REQUEST['nombrep'].'/';
        if(rename($origen,$destino) || $_SESSION['ruta']=='savi'){
            $imagenM->quitarConclusionDCM($_REQUEST['id_dcm']);
        }
    }

    public function concluirDCM(){
        $imagenM = new Imagenm();
        $origen=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$_REQUEST['ruta'].'/'.$_REQUEST['nombrep'].'/';
        $destino=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$_REQUEST['ruta'].'_pacientes/'.$_REQUEST['nombrep'].'/';
        if(rename($origen,$destino)){
            $imagenM->concluirDCM($_REQUEST['id_dcm']);
        }
    }

    public function eliminarDCM(){
        $imagenM = new Imagenm();
        $this->rmDir_rf('../../'.$_REQUEST['ruta']);
        $imagenM->eliminarDCM($_REQUEST['id_dcm']);
    }

    function rmDir_rf($carpeta)
    {
      foreach(glob($carpeta . "/*") as $archivos_carpeta){             
        if (is_dir($archivos_carpeta)){
          $this->rmDir_rf($archivos_carpeta);
        } else {
        unlink($archivos_carpeta);
        }
      }
      rmdir($carpeta);
     }

    public function descomprimirDCM(){
        $imagenM = new Imagenm();
        $origen=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$_REQUEST['ruta'].'_comprimidos/'.$_REQUEST['nombrep'].'/';
        //$_REQUEST['zip']
        if(file_exists($origen."AppFiles.zip")){
            $nombreZIP=explode(".",$_REQUEST['zip']);
            $destino=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$_REQUEST['ruta'].'/'.$_REQUEST['nombrep'];
            mkdir($destino, 0777, true);
            rename($origen.'/'.$_REQUEST['zip'],$destino.'/_'.$_REQUEST['zip']);
            $zip = new ZipArchive;
            $comprimido= $zip->open($destino."/_".$_REQUEST['zip']);
            if ($comprimido=== TRUE) {

                $zip->extractTo($destino."/_".$nombreZIP[0]);
                $zip->close();
                $imagenM->actualizaEstatusDescomprimido($_REQUEST['id_dcm']);
                $this::eliminaArchivosNoDCM($destino."/_".$nombreZIP[0].'/');
                rename($destino.'/_'.$nombreZIP[0],$destino.'/'.$nombreZIP[0]);
            }
            rename($origen."AppFiles.zip", $destino.'/'.$_REQUEST['zip']);
        }
        else{
            $nombreZIP=explode(".",$_REQUEST['zip']);
            $destino=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$_REQUEST['ruta'].'/'.$_REQUEST['nombrep'];
            rename($origen,$destino);
            $zip = new ZipArchive;
            $comprimido= $zip->open($destino."/".$_REQUEST['zip']);
            if ($comprimido=== TRUE) {
                $zip->extractTo($destino."/".$nombreZIP[0]);
                $zip->close();
                $imagenM->actualizaEstatusDescomprimido($_REQUEST['id_dcm']);
                $this::eliminaArchivosNoDCM($destino."/".$nombreZIP[0].'/');
            }
        }
        $this::deleteDirectory($origen);
    }

    function agregar_zip($dir, $zip) {
        //verificamos si $dir es un directorio
        if (is_dir($dir)) {
          //abrimos el directorio y lo asignamos a $da
          if ($da = opendir($dir)) {
            //leemos del directorio hasta que termine
            while (($archivo = readdir($da)) !== false) {
              /*Si es un directorio imprimimos la ruta
               * y llamamos recursivamente esta función
               * para que verifique dentro del nuevo directorio
               * por mas directorios o archivos
               */
              if (is_dir($dir . $archivo) && $archivo != "." && $archivo != "..") {
                $this::agregar_zip($dir . $archivo . "/", $zip);
       
                /*si encuentra un archivo imprimimos la ruta donde se encuentra
                 * y agregamos el archivo al zip junto con su ruta 
                 */
              } elseif (is_file($dir . $archivo) && $archivo != "." && $archivo != "..") {
                $destinoDir=str_replace("wado/uploads/discoDuro1/sama_comprimidos","",$dir);
                $zip->addFile($dir . $archivo, $destinoDir . $archivo);
              }
            }
            //cerramos el directorio abierto en el momento
            closedir($da);
          }
        }
    }

    function deleteDirectory($dir) {
        if(!$dh = @opendir($dir)) return;
        while (false !== ($current = readdir($dh))) {
            if($current != '.' && $current != '..') {
                //echo 'Se ha borrado el archivo '.$dir.'/'.$current.'<br/>';
                if (!@unlink($dir.'/'.$current)) 
                    $this::deleteDirectory($dir.'/'.$current);
            }       
        }
        closedir($dh);
        echo 'Se ha borrado el directorio '.$dir.'<br/>';
        @rmdir($dir);
    }
    
    function eliminaArchivosNoDCM($carpeta) {
        if (is_file($carpeta)) {
            $extension = pathinfo($carpeta, PATHINFO_EXTENSION);
            if (strtolower($extension) !== 'dcm') {
                unlink($carpeta);
            }
        } elseif (is_dir($carpeta)) {
            $scan = glob(rtrim($carpeta, '/').'/*');
            foreach ($scan as $path) {
                if (basename($path) === '.' || basename($path) === '..') {
                    continue;
                }
                $this::eliminaArchivosNoDCM($path);
            }
        }
        return "ok";
    }

    public function listaPacientesDental($fechaInicial,$fechaFinal){
        $imagenM = new Imagenm();
        $catalogos = new Catalogos();
        $data = $imagenM->listaPacientesDentalR($fechaInicial,$fechaFinal);
        $sucursal=$catalogos->getSucursal($_SESSION["id_sucursal"])[0];
        $ruta=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$sucursal->prefijo_imagen.'_dental/';
         foreach ($data AS $row=>$item) {
            echo '<tr>
                    <td>'.$item->consecutivo.'</td>
                    <td>'.$item->paciente.'</td>
                    <td>'.$item->fecha_registro.'</td>
                    <td>'.$item->expediente.'</td>
                    <td>'.$item->nombre_estudio.'</td>
                    <td>

                        <form method="POST" action="controller/imagen/imagen?opc=asignar_dental">
                            <div class="row">
                                <div class="col-8">
                                    <select name="ruta" class="form-control"> ';
                                    $this::listar_directorios_ruta($ruta,$item->consecutivo);
                                    echo '  </select>
                                </div>
                                <div class="col-4">
                                    <input type="hidden" name="id_paciente" value="'.$item->id_paciente.'">
                                    <input type="hidden" name="id_orden" value="'.$item->id_orden.'">
                                    <input type="hidden" name="id_estudio" value="'.$item->id_estudio.'">
                                    <input type="hidden" name="fecha_registro" value="'.$item->fecha_registro.'">
                                    <button type="submit" class="btn btn-info btn-sm">Asignar</button>
                                </div>
                        </form>
                    </td>
            </tr>';
         }

    }

    public function listar_directorios_ruta($ruta,$consecutivo){
   // abrir un directorio y listarlo recursivo

   if (is_dir($ruta)) {
      if ($dh = opendir($ruta)) {
         while (($file = readdir($dh)) !== false) {
            //esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
            //mostraría tanto archivos como directorios
            //echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file);
            if (is_dir($ruta . $file) && $file!="." && $file!=".." && $file!="_gsdata_"){
               //solo si el archivo es un directorio, distinto que "." y ".."
                if(strpos($file,$consecutivo)){
                    echo '<option value="'.$file.'" selected>'.$file.'</option>';
                }
                else
                    echo '<option value="'.$file.'">'.$file.'</option>';
               $this::listar_directorios_ruta($ruta . $file . "/",$consecutivo);
            }
         }
      closedir($dh);
      }
   }else
      echo "<br>No es ruta valida";
}

    public function listaPacientesMedico($fechaInicial,$fechaFinal,$visor){
    	$imagenM = new Imagenm();
        $data = $imagenM->listaPacientesMedicoR($fechaInicial,$fechaFinal,"m"); 
        $seccion=-1;
        $colorC=0;
        foreach ($data AS $row=>$item) {
            if($seccion!=$item->seccion){
                if($colorC==0){
                   $color="#078CA4";
                   $colorC=1; 
                }
                else{
                    $color="green";
                    $colorC=0; 
                }
                
                $seccion=$item->seccion;
            }

            echo '<tr >
                <td>'.($row+1).'</td>
                <td>'.$item->consecutivo.'</td>
                <td>'.$item->paciente.'</td>
                <td>'.$item->fecha_registro.'</td>
                <td style="color:'.$color.';">('.$item->codigo.')-'.$item->nombre_estudio.'</td>
                <td>'.$item->sucursal.'</td>
                <td  style="text-align:center;">';
                    //$directorioZIP=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$_SESSION['ruta'];
                    if($item->local=="J"){
                        $rutaD=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'_comprimidos/'.$item->ruta;
                        
                    }   
                    elseif($item->local=="N" && ($item->cerrado==null || $item->cerrado==0)){
                        $rutaD=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'/'.$item->ruta;
                        $rutaDescargaZip='/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'/'.$item->ruta.'/'.$item->archivo_zip;
                    }
                    elseif($item->local=="N" && $item->cerrado==1){
                        $rutaD=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'_pacientes/'.$item->ruta;
                        $rutaDescargaZip='/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'_pacientes/'.$item->ruta.'/'.$item->archivo_zip;
                    }
                    $directorioZIP=$rutaD.'/'.$item->archivo_zip;
                    $directorioZIPG=$rutaD.'/'.$item->archivo_zip;

                    
                    $pesoZip= filesize($directorioZIP)/1024/1024;
                    if($pesoZip>$item->tamano_zip && $item->archivo_zip!=""){
                        echo '<a href="'.$rutaDescargaZip.'"><button type="button" class="btn btn-sm btn-primary rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                            <i class="fas fa-file-archive"></i>
                        </button></a>';
                    }
                    else{
                        if($item->cerrado==1){
                           
                        }
                        echo '<button type="button" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                            <i class="fas fa-sync"></i>
                        </button>';
                    }
                    /*<button type="button" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                        <i class="fas fa-file-archive"></i>
                    </button>*/
            echo '</td>
                <td  style="text-align:center;">';
                    if($item->reportado!=null && $item->cerrado==1){
                        echo '
                        <div class="row">
                            <input type="hidden" name="reporte" value="" class="d-none">
                            <button type="submit" class="btn btn-sm btn-warning rounded-circle m-1" data-toggle="tooltip" title="Reporte en Gabinete" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/'.$_SESSION['ruta'].'/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=0\')">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                           <input type="hidden" name="reportegabinete" value="" class="d-none">
                            <button type="submit" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="Reporte Cliente" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/'.$_SESSION['ruta'].'/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=1\')">
                                <i class="fas fa-file-pdf"></i>
                            </button>   
                            <div class="btn btn-sm btn-info rounded-circle m-1 mail" data-paciente="'.$item->paciente.'" data-correo="'.$item->email.'" data-id="'.$item->id_orden.'" data-expediente="'.$item->exp.'"  data-toggle="tooltip" title="Mail">
                                                <i class="far fa-envelope"></i>
                            </div>
                        </div> 
                       ';
                    }
                    else
                        echo '-';
          echo '</td>
                <td style="text-align:center;">';
                    if($pesoZip>$item->tamano_zip && $item->local=="J"){
                        echo '<button class="btn btn-secondary btn-sm btn-descomprimir" data-id_dcm="'.$item->dcm.'" data-nombrep="'.$item->ruta.'" data-ruta="'.$item->prefijo_imagen.'" data-zip="'.$item->archivo_zip.'">Descomprimir</button>';
                    }
                    elseif($pesoZip>$item->tamano_zip && $item->local=="N"){
                        //$_REQUEST['nombre'],$_REQUEST['cerrado'],$_REQUEST['prefijo_sucursal'],$_REQUEST['id_dcm'],
                        //$_REQUEST['usuario'],$_REQUEST['ruta']
                        if($visor==1)
                            $redirige="redirige_visor";
                        else
                            $redirige="redirige_visor_anterior";
                        echo '<a target="blank_" href="'.$redirige.'.php?nombre='.$item->ruta.'&cerrado='.$item->cerrado.'&prefijo_sucursal='.$item->prefijo_imagen.'&id_dcm='.$item->dcm.'&usuario='.$_SESSION["id"].'&ruta='.$_SESSION['ruta'].'&id_sucursal='.$item->id_sucursal.'">

                            <button class="btn btn-sm btn-info rounded-circle m-1 delete-empresa" data-id="" data-nombre="" data-toggle="tooltip" title="Visor DCM" >
                            <i class="far fa-eye"></i>
                            </button>
                        </a>';
                    }
                    else{
                        echo '-';
                    }
                    
            echo '</td>
                <td>';
                    if($item->reportado==null)
                        echo 'NO';
                    elseif($pesoZip>$item->tamano_zip && $item->reportado!=null && $item->cerrado==0 && $item->local=="N")
                        echo '<button class="btn btn-outline-success btn-concluir" data-id_dcm="'.$item->dcm.'" data-nombrep="'.$item->ruta.'" data-ruta="'.$item->prefijo_imagen.'">Concluir</button>';
                    elseif($pesoZip<$item->tamano_zip || $item->local=="J")
                        echo '-';
                    else
                        echo 'CONCLUIDO';
            echo '</td>';
            if($visor!=1)
                    echo '<td><a href="https://reporte.imagen.connectionslab5.net/arceo/'.$item->ruta_archivo.'" target="_blank">Descargar</td>';
            echo '</tr>';
        }
    }  

//------------------- Lista de pacientes para tecnicos
    public function listaPacientesTecnico($fechaInicial,$fechaFinal){
        $imagenM = new Imagenm();
        $data = $imagenM->listaPacientesMedicoR($fechaInicial,$fechaFinal,"t");
        $seccion=-1;
        $colorC=0;
        foreach ($data AS $row=>$item) {
            if($seccion!=$item->seccion){
                if($colorC==0){
                   $color="#078CA4";
                   $colorC=1; 
                }
                else{
                    $color="green";
                    $colorC=0; 
                }
                
                $seccion=$item->seccion;
            }

            echo '<tr >
                <td>'.($row+1).'</td>
                <td>'.$item->consecutivo.'</td>
                <td>'.$item->paciente.'</td>
                <td>'.$item->fecha_registro.'</td>
                <td style="color:'.$color.';">('.$item->codigo.')-'.$item->nombre_estudio.'</td>
                <td  style="text-align:center;">';
                    if($item->local=="J"){
                        $rutaD=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'_comprimidos/'.$item->ruta;
                    }
                    elseif($item->local=="N" && ($item->cerrado==null || $item->cerrado==0)){
                        $rutaD=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'/'.$item->ruta;
                        $rutaDescargaZip='/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'/'.$item->ruta.'/'.$item->archivo_zip;
                    }
                    elseif($item->local=="N" && $item->cerrado==1){
                        $rutaD=$_SERVER["DOCUMENT_ROOT"].'/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'_pacientes/'.$item->ruta;
                        $rutaDescargaZip='/wado/uploads/discoDuro1/'.$item->prefijo_imagen.'_pacientes/'.$item->ruta.'/'.$item->archivo_zip;
                    }
                    $directorioZIP=$rutaD.'/'.$item->archivo_zip;
                    $directorioZIPG=str_replace("..","https://connectionslab5.net",$rutaD).'/'.$item->archivo_zip;
                    $pesoZip= filesize($directorioZIP)/1024/1024;
                    if($pesoZip>$item->tamano_zip && $item->archivo_zip!=""){
                        echo '<a href="'.$rutaDescargaZip.'"><button type="button" class="btn btn-sm btn-primary rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                        <i class="fas fa-file-archive"></i>
                         </button></a>';
                    }
                    else{
                        echo '<button type="button" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                            <i class="fas fa-sync"></i>
                        </button>';
                    }
                    /*<button type="button" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                        <i class="fas fa-file-archive"></i>
                    </button>*/
            echo '</td>
                <td  style="text-align:center;">';
                    if($item->reportado!=null && $item->cerrado==1){
                        echo '
                        <div class="row">
                            <input type="hidden" name="reporte" value="" class="d-none">
                            <button type="submit" class="btn btn-sm btn-warning rounded-circle m-1" data-toggle="tooltip" title="Reporte en Gabinete" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/'.$_SESSION['ruta'].'/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=0\')">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                           <input type="hidden" name="reportegabinete" value="" class="d-none">
                            <button type="submit" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="Reporte Cliente" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/'.$_SESSION['ruta'].'/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=1\')">
                                <i class="fas fa-file-pdf"></i>
                            </button>   
                        </div> 
                       ';
                    }
                    else
                        echo '-';
          echo '</td>
                <td style="text-align:center;">';
                    if($pesoZip>$item->tamano_zip && $item->local=="J"){
                        echo '<button class="btn btn-secondary btn-sm btn-descomprimir" data-id_dcm="'.$item->dcm.'" data-nombrep="'.$item->ruta.'" data-ruta="'.$item->prefijo_imagen.'" data-zip="'.$item->archivo_zip.'">Descomprimir</button>';
                    }
                    elseif($pesoZip>$item->tamano_zip && $item->local=="N"){
                        //$_REQUEST['nombre'],$_REQUEST['cerrado'],$_REQUEST['prefijo_sucursal'],$_REQUEST['id_dcm'],
                        //$_REQUEST['usuario'],$_REQUEST['ruta']
                        echo '<a target="blank_" href="redirige_visor.php?nombre='.$item->ruta.'&cerrado='.$item->cerrado.'&prefijo_sucursal='.$item->prefijo_imagen.'&id_dcm='.$item->dcm.'&usuario='.$_SESSION["id"].'&ruta='.$_SESSION['ruta'].'">

                            <button class="btn btn-sm btn-info rounded-circle m-1 delete-empresa" data-id="" data-nombre="" data-toggle="tooltip" title="Visor DCM" >
                            <i class="far fa-eye"></i>
                            </button>
                        </a>';
                    }
                    else{
                        echo '-';
                    }
                    
            echo '</td>
                <td>';
                if($item->cerrado==0 ){
                    echo '
                    <button class="btn btn-sm btn-danger rounded-circle m-1 elimina-dcm" data-id_dcm="'.$item->dcm.'" data-ruta="'.$rutaD.'" data-toggle="tooltip" title="Eliminar DCM" >
                        <i class="fas fa-times"></i>
                    </button>';
                }
                else{
                    echo '-';
                }
            echo '</td>
            </tr>';
        }
    } 



//---------------------------------------- Lista de Pacientes DCM para reporte Local
    public function listaPacientesLocal($fechaInicial,$fechaFinal){
        $imagenM = new Imagenm();
        $data = $imagenM->listaPacientesLocalM($fechaInicial,$fechaFinal);
        foreach ($data AS $row=>$item) {
            echo '<tr>
                <td>'.($row+1).'</td>
                <td>'.$item->consecutivo.'</td>
                <td>'.$item->paciente.'</td>
                <td>'.$item->fecha_registro.'</td>
                <td>'.$item->nombre_estudio.'</td>
                <td>'.$item->sucursal.'</td>
                <td>';
                if($item->reportado==null){
                    echo '<button class="btn btn-outline-primary btn-local" data-dcm="'.$item->dcm.'" data-nombre="'.$item->nombre_estudio.'" data-seccion="'.$item->seccion.'" data-paciente="'.$item->id_paciente.'" data-orden="'.$item->id_orden.'" data-id_estudio="'.$item->id_estudio.'" data-dcm="'.$item->dcm.'" data-toggle="modal" data-target=".bd-example-modal-lg">Reportar</button>';
                }
                elseif($item->cerrado==0){
                    echo '<button class="btn btn-outline-success btn-local" data-dcm="'.$item->dcm.'" data-nombre="'.$item->nombre_estudio.'" data-paciente="'.$item->id_paciente.'" data-orden="'.$item->id_orden.'" data-id_estudio="'.$item->id_estudio.'" data-seccion="'.$item->seccion.'" data-dcm="'.$item->dcm.'" data-toggle="modal" data-target=".bd-example-modal-lg">Reportar</button>';
                }

            echo '</td>
                <td style="text-align:center;">';
                if($item->reportado==null){
                    echo '-';
                }
                elseif($item->reportado!=null && $item->cerrado==0){
                    echo '
                    <button class="btn btn-warning btn-sm" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/'.$_SESSION['ruta'].'/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=0\')">Imprimir</button><hr/>
                    <button class="btn btn-success btn-sm btn-concluir-local" data-dcm="'.$item->dcm.'">Concluir</button>';
                }
                elseif($item->reportado!=null && $item->cerrado==1){
                    echo '
                    <button class="btn btn-warning btn-sm" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/'.$_SESSION['ruta'].'/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=0\')">Imprimir</button><hr/>
                    <button class="btn btn-success btn-sm" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/'.$_SESSION['ruta'].'/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=1\')">Paciente</button>';
                }
            echo '</td>
            </tr>';
        }
    }
//---------------------------------------- Lista de dcm concluidos

    public function listaPacientesConcluidos($fechaInicial,$fechaFinal){
        $imagenM = new Imagenm();
        $data = $imagenM->listaPacientesConcluidosM($fechaInicial,$fechaFinal );
        foreach ($data AS $row=>$item) {
            echo '<tr>
                <td>'.$item->consecutivo.'</td> 
                <td>'.$item->paciente.'</td>
                <td>'.$item->fecha_registro.'</td>
                <td>'.$item->nombre_estudio.'</td>
                <td>'.$item->nombre_usuario.'</td>
                <td style="text-align:center;" ><button class="btn btn-sm btn-danger rounded-circle m-1 btn-quitar-conclusion" data-id="" data-nombre="" data-toggle="tooltip" title="Eliminar Conclusion" data-id_dcm="'.$item->dcm.'" data-nombrep="'.$item->ruta.'" data-ruta="'.$item->prefijo_imagen.'">
                 <i class="fas fa-times"></i>
                 </button></td>
            </tr>';
        }
    }

    public function ctrGuardaReporteLocal(){
        $imagenM = new Imagenm();
        if($_REQUEST['ft_id_dcm']=="")
            $data = $imagenM->mdlGuardaReporteLocal();
        else
            $data=$imagenM->mdlActualizaReporteLocal();
    }

}

new Imagen();