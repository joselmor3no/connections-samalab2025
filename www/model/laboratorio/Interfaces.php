<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');

class Interfaces {

	public function getArchivoSucursal($codigo_sucursal){
		if($codigo_sucursal=="sama"){
			$codigo_sucursal="samalab";
		}
		if(file_exists($_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$codigo_sucursal."/")){
			$directorio=opendir($_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$codigo_sucursal."/");
			//recoger los  datos
			while ($archivo = readdir($directorio)) { 
			if(($archivo != '.')&&($archivo != '..')){
				if(strpos($archivo,$codigo_sucursal)>0){
					return 1;
				}
			} 
			}
			closedir($directorio);
		}
		
		return 0;
	}

	public function getResultadoInterfaz($ruta,$id_estudio,$interfaz_letra,$codigo_orden,$codigo_estudio){
		$xx="";
		if($ruta=="sama"){
			$ruta="samalab";
		}
		if($interfaz_letra=="")
					return "";
		if($id_estudio==1){
			
			$nombreArchivo=$ruta."/interfaz_bh_".$ruta.".txt";
			if(file_exists($_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$nombreArchivo)){
				$file = fopen($_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$nombreArchivo, "r");

				
				if($ruta=="samalab" ){
					
						if(file_exists ( $_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$nombreArchivo)){
							$file = fopen($_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$nombreArchivo, "r") or exit("Unable to open file!");
							$linea_encontrada=0;
							while($linea = fgets($file)) {
									$arregloMayor=explode("|".Date("Ymd"),$linea);	
									for($j=0;$j<count($arregloMayor);$j++){
											$orden=strpos($arregloMayor[$j],'|'.$codigo_orden.'|');
											if($orden!==false){
												$linea_encontrada=1;
											}
											if($linea_encontrada==1 && strpos($arregloMayor[$j],"OBX")!==false){
												$arreglo=explode("OBX|",$arregloMayor[$j]);
												for($i=0;$i<count($arreglo);$i++){
													if(strpos($arreglo[$i],$interfaz_letra)>-1){
														$arregloIn=explode("|",$arreglo[$i]);
														return str_replace(",",".",$arregloIn[4]);
													}
												}
											}
													
									}
									
								}
						}
				}
			}
		}else{

			//============== Interfaz de Quimica
			$nombreArchivo=$ruta."/interfaz_qca_".$ruta.".txt";
			if($ruta=="samalab" ){
							
				if(file_exists ( $_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$nombreArchivo)){
					$file = fopen($_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$nombreArchivo, "r") or exit("Unable to open file!");
					$linea_encontrada=0;
					while($linea = fgets($file)) {
						
						$arregloMayor=explode("|".Date("Ymd"),$linea);	
						for($j=0;$j<count($arregloMayor);$j++){
							$orden=strpos($arregloMayor[$j],'|'.$codigo_orden);
									
							if($orden!==false){
								$linea_encontrada=1;
										
							}
							if($linea_encontrada==1 && strpos($arregloMayor[$j],"OBX")!==false){
								$arreglo=explode("OBX|",$arregloMayor[$j]);
								for($i=0;$i<count($arreglo);$i++){
											
									if(strpos($arreglo[$i],$interfaz_letra)>-1){
										$arregloIn=explode("|",$arreglo[$i]);
										return $arregloIn[4];
									}
								}
							}				
						}		
					}
					return "";
				}
			}


			//============== Interfaz de Hormonas
			$nombreArchivo=$ruta."/interfaz_hormonas_".$ruta.".txt";
			if($ruta=="samalab" ){				
				if(file_exists ( $_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$nombreArchivo)){
					$file = fopen($_SERVER["DOCUMENT_ROOT"] . '/interfaces/'.$nombreArchivo, "r") or exit("Unable to open file!");
					$linea_encontrada=0;
					while($linea = fgets($file)) {
						
						$arregloMayor=explode("|".Date("Ymd"),$linea);	
							for($j=0;$j<count($arregloMayor);$j++){
									$orden=strpos($arregloMayor[$j],'|'.$codigo_orden);
									
									if($orden!==false){
										$linea_encontrada=1;
										
									}
									if($linea_encontrada==1 && strpos($arregloMayor[$j],"OBX")!==false){
										return $arregloMayor[$j];
										$arreglo=explode("OBX|",$arregloMayor[$j]);
										for($i=0;$i<count($arreglo);$i++){
											
											if(strpos($arreglo[$i],'|'.$interfaz_letra.'|')!==false){
												$arregloIn=explode("|",$arreglo[$i]);
												return $arregloIn[4];
											}
										}
									}
											
							}
							
						}
				}
			}
		}
		return "";

		
		return $xx;
	}
}