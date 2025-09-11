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
	$ruta=str_replace("wado/uploads","" , $rutaCarpetas);
	$cadena="url=".$ruta."&carpetas=".$carpetas[0];
	$carpetasNuevas=$carpetas[0];
	$total=$tImagenes[0];
	for($i=1;$i<count($carpetas);$i++){
		$cadena.="-l-".$carpetas[$i];
		$carpetasNuevas.="-".$carpetas[$i];
		$total.="-".$tImagenes[$i];
	}


    

    if($_REQUEST['cerrado']!=1){
    	$rutaNueva=str_replace("wado/uploads/discoDuro1/".$_REQUEST['prefijo_sucursal']."/","" , $rutaCarpetas);
	    $cadenaNueva="https://dcm-connections.connectionslab4.net/visor/".$_REQUEST['prefijo_sucursal']."/".str_replace("/","-",$rutaNueva)."/".$carpetasNuevas."/".$total."/".$_REQUEST['id_dcm']."/".$_REQUEST['usuario']."/".$_REQUEST['ruta'];
    }
    else{
    	$rutaNueva=str_replace("wado/uploads/discoDuro1/".$_REQUEST['prefijo_sucursal']."_pacientes/","" , $rutaCarpetas);
        $cadenaNueva="https://dcm-connections.connectionslab4.net/visor/".$_REQUEST['prefijo_sucursal']."_pacientes/".str_replace("/","-",$rutaNueva)."/".$carpetasNuevas."/".$total."/".$_REQUEST['id_dcm']."/".$_REQUEST['usuario']."/".$_REQUEST['ruta'];
    }
    $cadenaNueva=str_replace("-/","/",$cadenaNueva);

//------------------ Redirigiendo al visor
	if($carpetas[0]!=''){
		    header('Location:'.$cadenaNueva);
		    //echo $carpetasNuevas;
	}
	else{
		//header('Location:index?menu=lista');
        echo $cadenaNueva;
	}



	function rutaCarpetas($nombre,$cerrado,$prefijo_sucursal){
		if($cerrado==1){
		    $ruta="wado/uploads/discoDuro1/".$prefijo_sucursal."_pacientes/".$nombre."/";
		}
        else
            $ruta="wado/uploads/discoDuro1/".$prefijo_sucursal."/".$nombre."/";
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
		    $ruta="wado/uploads/discoDuro1/".$prefijo_sucursal."_pacientes/".$nombre."/";
		}
		else{
		    $ruta="wado/uploads/discoDuro1/".$prefijo_sucursal."/".$nombre."/";
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
