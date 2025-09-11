<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/Pacientes/model/Resultados.php');

class Imagen {

	public function listaEstudiosMedico($medico){
        
		$resultados = new Resultados(); 
		$res=$resultados->obtenerListaOrdenesImagenMedico($medico);
    

        
		foreach ($res AS $row=>$item) {

            $arrayDocumentos=explode(',',$item->documentos);
            $documentos=""; 
            for($j=0;$j<=count($arrayDocumentos);$j++){
                if($j>0)
                    $documentos.='<br>';
                $documentos.='<a href="reportes/'.$_SESSION['ruta'].'/resultados/'.$item->id.'_'.strtoupper($arrayDocumentos[$j]).'.pdf" target="_blank"><u>'.$arrayDocumentos[$j].'</u></a>';
            }

			if($item->local=="N" && ($item->cerrado==null || $item->cerrado==0)){
                $rutaD='../wado/uploads/discoDuro1/'.$item->prefijo_imagen.'/'.$item->ruta;
            }
            elseif($item->local=="N" && $item->cerrado==1){
                $rutaD='../wado/uploads/discoDuro1/'.$item->prefijo_imagen.'_pacientes/'.$item->ruta;
            }

            $directorioZIP=$rutaD.'/'.$item->archivo_zip;
            $directorioZIPG=str_replace("../","https://samalab.connectionslab5.net/",$rutaD).'/'.$item->archivo_zip;
            $pesoZip= filesize($directorioZIP)/1024/1024;
			//$item->archivo_zip
			echo '<tr>
				<td>'.$item->consecutivo.'</td>
				<td>'.$item->paciente.'</td>
				<td>'.$item->nombre_estudio.'</td>
				<td>'.$item->fecha_registro.'</td>
				<td></td>
				<td>';
				if($pesoZip>$item->tamano_zip){
					echo '<a href="'.$directorioZIPG.'"><button type="button" class="btn btn-sm btn-primary rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                            <i class="fas fa-file-archive"></i>
                        </button></a>';
				}
				else{
                        echo '<button type="button" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                            <i class="fas fa-sync"></i>
                        </button>';
                    }
				if($pesoZip>$item->tamano_zip && $item->local=="N"  &&  ($item->saldo_deudor==0 || $item->credito==1 || $item->prefijo_imagen=="arceo")){
					echo '<a target="blank_" href="redirige_visor.php?nombre='.$item->ruta.'&cerrado='.$item->cerrado.'&prefijo_sucursal='.$item->prefijo_imagen.'&id_dcm='.$item->dcm.'&usuario=1&ruta='.$item->prefijo_imagen.'">

                            <button class="btn btn-sm btn-info rounded-circle m-1 delete-empresa" data-id="" data-nombre="" data-toggle="tooltip" title="Visor DCM" >
                            <i class="far fa-eye"></i>
                            </button>
                        </a>';
				}

				if($item->cerrado==1 &&  ($item->saldo_deudor==0 || $item->credito==1 || $item->prefijo_imagen=="arceo")){

				echo '
                           <input type="hidden" name="reportegabinete" value="" class="d-none">
                            <button type="submit" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="Reporte Cliente" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/sama/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=1\')">
                                <i class="fas fa-file-pdf"></i>
                            </button>   
                       ';
                }
                else
                    echo '-';
			echo '</td><td>';
            $thefolder = "../wado/uploads/discoDuro1/".$item->prefijo_imagen."/".$item->ruta."/".$item->ruta;
            if ($handler = opendir($thefolder)) {
                while (false !== ($file = readdir($handler))) {
                    if(strpos($file,"jpg")>0){
                        $thefolder2=str_replace("","https://samalab.connectionslab5.net/",$thefolder);
                        echo '<a target="_blank" href="'.$thefolder2.'/'.$file.'">'.$file.'</a><br>';
                    }
                }
                closedir($handler);
            }
			echo '</td>
            <td>'.$documentos.'</td>
			</tr>';
		}
	}


    public function listaEstudiosEmpresa($empresa){
		$resultados = new Resultados(); 
		$res=$resultados->obtenerListaOrdenesImagenEmpresa($empresa);
        echo "<pre>---";
        print_r($empresa);
        echo "</pre>";
        
		foreach ($res AS $row=>$item) {

			if($item->local=="N" && ($item->cerrado==null || $item->cerrado==0)){
                $rutaD='../../../wado/uploads/discoDuro1/'.$item->prefijo_imagen.'/'.$item->ruta;
            }
            elseif($item->local=="N" && $item->cerrado==1){
                $rutaD='../../../wado/uploads/discoDuro1/'.$item->prefijo_imagen.'_pacientes/'.$item->ruta;
            }

            $directorioZIP=$rutaD.'/'.$item->archivo_zip;
            $directorioZIPG=str_replace("..","https://samalab.connectionslab5.net/",$rutaD).'/'.$item->archivo_zip;
            $pesoZip= filesize($directorioZIP)/1024/1024;
			//$item->archivo_zip
			echo '<tr>
				<td>'.$item->consecutivo.'</td>
				<td>'.$item->paciente.'</td>
				<td>'.$item->nombre_estudio.'</td>
				<td>'.$item->fecha_registro.'</td>
				<td></td>
				<td>';
				if($pesoZip>$item->tamano_zip){
					echo '<a href="'.$directorioZIPG.'"><button type="button" class="btn btn-sm btn-primary rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                            <i class="fas fa-file-archive"></i>
                        </button></a>';
				}
				else{
                        echo '<button type="button" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                            <i class="fas fa-sync"></i>
                        </button>';
                    }
				if($pesoZip>$item->tamano_zip && $item->local=="N"  &&  ($item->saldo_deudor==0 || $item->credito==1 || $item->prefijo_imagen=="arceo")){
					echo '<a target="blank_" href="redirige_visor.php?nombre='.$item->ruta.'&cerrado='.$item->cerrado.'&prefijo_sucursal='.$item->prefijo_imagen.'&id_dcm='.$item->dcm.'&usuario=1&ruta='.$item->prefijo_imagen.'">

                            <button class="btn btn-sm btn-info rounded-circle m-1 delete-empresa" data-id="" data-nombre="" data-toggle="tooltip" title="Visor DCM" >
                            <i class="far fa-eye"></i>
                            </button>
                        </a>';
				}

				if($item->cerrado==1 &&  ($item->saldo_deudor==0 || $item->credito==1 || $item->prefijo_imagen=="arceo")){

				echo '
                           <input type="hidden" name="reportegabinete" value="" class="d-none">
                            <button type="submit" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="Reporte Cliente" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/sama/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=1\')">
                                <i class="fas fa-file-pdf"></i>
                            </button>   
                       ';
                }
                else
                    echo '-';
			echo '</td>
			<td>';
            $thefolder = "../../../wado/uploads/discoDuro1/".$item->prefijo_imagen."/".$item->ruta."/".$item->ruta;
            if ($handler = opendir($thefolder)) {
                while (false !== ($file = readdir($handler))) {
                    if(strpos($file,"jpg")>0){
                        $thefolder2=str_replace("../../","https://samalab.connectionslab5.net/",$thefolder);
                        echo '<a target="_blank" href="'.$thefolder2.'/'.$file.'">'.$file.'</a><br>';
                    }
                }
                closedir($handler);
            }
			echo '</td>
			</tr>';
		}
	}

	public function listaEstudiosPaciente($id_paciente){
		$resultados = new Resultados(); 
		$res=$resultados->obtenerListaOrdenesImagen($id_paciente);
		foreach ($res AS $row=>$item) {

			if($item->local=="N" && ($item->cerrado==null || $item->cerrado==0)){
                $rutaD='../wado/uploads/discoDuro1/'.$item->prefijo_imagen.'/'.$item->ruta;
            }
            elseif($item->local=="N" && $item->cerrado==1){
                $rutaD='../wado/uploads/discoDuro1/'.$item->prefijo_imagen.'_pacientes/'.$item->ruta;
            }

            $directorioZIP=$rutaD.'/'.$item->archivo_zip;
            $directorioZIPG=str_replace("..","https://connectionslab5.net",$rutaD).'/'.$item->archivo_zip;
            $pesoZip= filesize($directorioZIP)/1024/1024;
			//$item->archivo_zip
			echo '<tr>
				<td>'.$item->consecutivo.'</td>
				<td>'.$item->nombre_estudio.'</td>
				<td>'.$item->fecha_registro.'</td>
				<td></td>
				<td>';
				if($pesoZip>$item->tamano_zip){
					echo '<a href="'.$directorioZIPG.'"><button type="button" class="btn btn-sm btn-primary rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                            <i class="fas fa-file-archive"></i>
                        </button></a>';
				}
				else{
                        echo '<button type="button" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="Descargar zip">
                            <i class="fas fa-sync"></i>
                        </button>';
                    }
				if($pesoZip>$item->tamano_zip && $item->local=="N"  &&  ($item->saldo_deudor==0 || $item->credito==1 || $item->prefijo_imagen=="arceo")){
					echo '<a target="blank_" href="redirige_visor.php?nombre='.$item->ruta.'&cerrado='.$item->cerrado.'&prefijo_sucursal='.$item->prefijo_imagen.'&id_dcm='.$item->dcm.'&usuario=1&ruta='.$item->prefijo_imagen.'">

                            <button class="btn btn-sm btn-info rounded-circle m-1 delete-empresa" data-id="" data-nombre="" data-toggle="tooltip" title="Visor DCM" >
                            <i class="far fa-eye"></i>
                            </button>
                        </a>';
				}
				if($item->cerrado==1 &&  ($item->saldo_deudor==0 || $item->credito==1 || $item->prefijo_imagen=="arceo")){

				echo '
                           <input type="hidden" name="reportegabinete" value="" class="d-none">
                            <button type="submit" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="Reporte Cliente" onclick="window.open(\'https://reporte.imagen.connectionslab5.net/sama/reporte/imprimir_rdcm.php?dcm='.$item->dcm.'&p=1\')">
                                <i class="fas fa-file-pdf"></i>
                            </button>   
                       ';
                }
                else
                    echo '-';
			echo '</td>
			<td>';
            $thefolder = "../wado/uploads/discoDuro1/".$item->prefijo_imagen."/".$item->ruta."/".$item->ruta;
            if ($handler = opendir($thefolder)) {
                while (false !== ($file = readdir($handler))) {
                    if(strpos($file,"jpg")>0){
                        $thefolder2=str_replace("../","https://samalab.connectionslab5.net/",$thefolder);
                        echo '<a target="_blank" href="'.$thefolder2.'/'.$file.'">'.$file.'</a><br>';
                    }
                }
                closedir($handler);
            }
			echo '</td>
			</tr>';
		}
	}
} 