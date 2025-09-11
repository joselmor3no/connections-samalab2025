<?php
	//$_REQUEST['nombre'],$_REQUEST['cerrado'],$_REQUEST['prefijo_sucursal'],$_REQUEST['id_dcm'],
	//$_REQUEST['usuario'],$_REQUEST['ruta']
 
	$carpetas=array();
	$tImagenes=array();
	$rutaCarpetas= rutaCarpetas($_REQUEST['nombre'],$_REQUEST['cerrado'],$_REQUEST['prefijo_sucursal']);
	$carpetas= listaCarpetas($_REQUEST['nombre'],$_REQUEST['cerrado'],$_REQUEST['prefijo_sucursal']); 
    
	asort($carpetas);

//------------------Obtener el total de imagenes por carpeta
	if($carpetas){
		for($i=0;$i<count($carpetas);$i++){
			$ficherosT  = scandir($rutaCarpetas.$carpetas[$i]);
			$tImagenes[$i]=0;
			for($j=1;$j<count($ficherosT);$j++){
				if(strripos($ficherosT[$j],".dcm")!==false){
					$tImagenes[$i]=$tImagenes[$i]+1;
				}
			}
		}

	}

//------------ Concatenando la URL
	$ruta=str_replace("../wado/uploads","" , $rutaCarpetas);
	$cadena="url=".$ruta."&carpetas=".$carpetas[0];
	$total=$tImagenes[0];
	for($i=1;$i<count($carpetas);$i++){
		$cadena.="-l-".$carpetas[$i];
		$total.="-".$tImagenes[$i];
	}
	$cadena=$cadena."&totales=".$total."&id_dcm=".$_REQUEST['id_dcm']."&suc=".$_REQUEST['id_sucursal']."&medico=".$_REQUEST['usuario']."&estudio_=".$estudio_;


//------------------ Redirigiendo al visor
	if($carpetas[0]!=''){
		    header('Location: http://107.161.176.4:3000/responsive-dcm/index.html?'.$cadena);
		    //echo $carpetasNuevas;
	}
	else{
		//header('Location:index?menu=lista');
        echo $cadena;
	}


	function rutaCarpetas($nombre,$cerrado,$prefijo_sucursal){
		if($cerrado==1){
		    $ruta="../wado/uploads/discoDuro1/".$prefijo_sucursal."_pacientes/".$nombre."/";
		}
        else
            $ruta="../wado/uploads/discoDuro1/".$prefijo_sucursal."/".$nombre."/";
        //echo $ruta;
		if(file_exists($ruta)){
			while(count(glob($ruta,GLOB_BRACE))<10){
				$ficheros1  = scandir($ruta);
				$encontrado=0;
				
				for($i=0; $i<count($ficheros1);$i++){	
					if(strripos($ficheros1[$i],".")===false){
						$anterior=$ruta;
						$ruta=$ruta.$ficheros1[$i]."/";
						break;
					}
					else{
						if(strripos($ficheros1[$i],".dcm")!==false){
							$encontrado=1;
							break;
						}
					}	
				}
				if($encontrado==1)
					break;
			}
			return $anterior;
		}
		else{
			return false;
		}
	}



	function listaCarpetas($nombre,$cerrado,$prefijo_sucursal){
		if($cerrado==1){
		    $ruta="../wado/uploads/discoDuro1/".$prefijo_sucursal."_pacientes/".$nombre."/";
		}
		else{
		    $ruta="../wado/uploads/discoDuro1/".$prefijo_sucursal."/".$nombre."/";
		}
        //echo $ruta;
		if(file_exists($ruta)){
			while(count(glob($ruta,GLOB_BRACE))<10){
			$ficheros1  = scandir($ruta);
			$encontrado=0;
			
			for($i=0; $i<count($ficheros1);$i++){	
				if(strripos($ficheros1[$i],".")===false){
					$anterior=$ruta;
					$ruta=$ruta.$ficheros1[$i]."/";
					break;
				}
				else{
					if(strripos($ficheros1[$i],".dcm")!==false){
						$encontrado=1;
						break;
					}
				}	
			}
			if($encontrado==1)
				break;
		}
		$carpetas=scandir($anterior);
		$lista=array();
		$cont=0;
		for($j=0;$j<count($carpetas);$j++){
			if(strripos($carpetas[$j],".")===false){
				$lista[$cont]=$carpetas[$j];
				$cont++;
			}
		}
		return $lista;

		}
		else{
			return false;
		}
	}
?>
