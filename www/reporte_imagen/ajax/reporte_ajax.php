<?php 	
include("../../ajax/conexion.php");
switch ($_REQUEST['opcion']) {
	case 1:
		$resultado=$_REQUEST['resultado'];
		$fecha=$_REQUEST['fecha'];
		$reporte=1;
		$id_dcm=$_REQUEST['dcm'];
		$datos_guardados=$_REQUEST['datos_guardados'];
		$nombrePEstudio=$_REQUEST['estudio'];
		//Verifico si es el primer o segundo reporte
		if($reporte==1){
			$resultado_tabla="resultado";
			$nombre_estudio_tabla="nombre_resultado_uno";

		}
		elseif($reporte==2){
		    $resultado_tabla="resultado_dos";
		    $nombre_estudio_tabla="nombre_resultado_dos";
		}
		elseif($reporte==3){
		    $resultado_tabla="resultado_tres";
		    $nombre_estudio_tabla="nombre_resultado_tres";
		}
		else{
			$resultado_tabla="resultado_cuatro";
			$nombre_estudio_tabla="nombre_resultado_cuatro";
		}

		$sql="SELECT id FROM dcm_resultado  WHERE id_dcm=".$id_dcm;
		$query=mysql_query($sql);
		$row_cnt = mysql_num_rows($query);
		$fila=mysql_fetch_array($query,MYSQL_ASSOC);
		if ($row_cnt>0){
			$sql="UPDATE dcm_resultado SET ".$resultado_tabla."='$resultado',".$nombre_estudio_tabla."='$nombrePEstudio',fecha='$fecha', numero_hojas=".$_REQUEST['nh'].", datos_guardados_reporte='".$datos_guardados."' WHERE id=".$fila['id']." and cerrado=0 and id_dcm=".$id_dcm;
			echo $sql;
			if(!$query=mysql_query($sql))
				echo mysql_error();
		}
		else{
			$sql="INSERT INTO dcm_resultado (".$resultado_tabla.",".$nombre_estudio_tabla.",fecha,id_dcm,datos_guardados_reporte) VALUES('$resultado','$nombrePEstudio','$fecha','$id_dcm','$datos_guardados')";
			echo $sql;
			if(!$query=mysql_query($sql))
				echo mysql_error(); 
		}
		
	break;
	case 2:
		$sql="select * from texto_radiologo where id=".$_REQUEST['id_formato'];
		$res=mysql_query($sql);
		$ren=mysql_fetch_array($res);
		echo utf8_encode($ren['texto']);
	break;
}

 ?>