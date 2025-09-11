<?php 	
include("../ajax/conexion.php");

$sql="SELECT s.imagenes_radiologia,dr.datos_guardados_reporte,dr.numero_hojas,dr.resultado,ciu.nombre_municipio,	o.consecutivo,dcm.dato_clinico,o.nombre_doctor,p.fecha_nac,p.expediente,concat(p.paterno,' ',p.materno,' ',p.nombre) as nombre_paciente,p.edad,p.tipo_edad,p.sexo,ce.nombre_estudio FROM dcm inner join paciente p on dcm.id_paciente=p.id 
inner join cat_estudio ce on ce.id=dcm.id_categoria 
inner join orden o on o.id=dcm.id_orden  
inner join sucursal s on s.id=o.id_sucursal
 inner join cliente cli on cli.id=s.id_cliente 
 inner join cat_municipio ciu on ciu.id=s.ciudad
 inner join cat_estados est on est.id=s.estado
 left join dcm_resultado dr on dr.id_dcm=dcm.id 
where dcm.id=".$_REQUEST['dcm'];
//echo $sql;
$res=mysql_query($sql);
$ren=mysql_fetch_array($res);

if($ren['datos_guardados_reporte']==null){
	$tam_fuente=15;
	$tam_fuente_firma=15;
	$tam_fuente_cuerpo=15;
	$ajustar_firma=1;
}
else{
	$datos_guardados_reporte=split("-",$ren['datos_guardados_reporte']);
	$tam_fuente=$datos_guardados_reporte[1];
	$tam_fuente_firma=$datos_guardados_reporte[2];
	$tam_fuente_cuerpo=$datos_guardados_reporte[3];
	$nuevoNombre=$datos_guardados_reporte[4];
	$ajustar_firma=floatval($datos_guardados_reporte[5]);
	//$sqlU="UPDATE dcm_resultado SET cerrado=1 where id_dcm=".$_REQUEST['dcm'];
	//mysql_query($sqlU);
}

$imagenesFondo=split("/", $ren['imagenes_radiologia']);

$sqlD="select * from usuario where id=".$datos_guardados_reporte[0];
$resD=mysql_query($sqlD);
$renD=mysql_fetch_array($resD);

 ?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1">
	<style>	
			body { 
				margin: 0; padding: 0; background-color: #FAFAFA; font-size:<?php echo $tam_fuente_cuerpo."px"?>; 
			}
			 * { box-sizing: border-box; -moz-box-sizing: border-box; 

			 } 
			 .page { width: 21.6cm; height: 27.94cm; padding: 0.3cm 1cm 1cm 1cm; margin: 0px; border: 1px #D3D3D3 solid; border-radius: 5px; background: white; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); 

			 }

	</style>	
</head>
<body >
<!-- //========================================= PRIMERA HOJA -->
	<div class="page" style=" background-image:url('img/<?php echo $imagenesFondo[2];?>');background-position:center;">
		<?php 
		if($_REQUEST['p']==1){
			echo '<img src="img/'.$imagenesFondo[0].'" alt="" style="height:2.5cm;width:100%;">';
		}
		else{
			echo '<div style="height:2.5cm;"></div>';
		}

		if($ren['numero_hojas']==1 || $ren['numero_hojas']==""){
			$tamaano=15+$ajustar_firma;
				echo '<div style="height:'.$tamaano.'cm;">';
		}
		else{
			echo '<div style="height:23cm;">';
		}
			encabezado($ren,$tam_fuente,$nuevoNombre); 
			echo str_replace("font-size: 14px;","font-size: ".$tam_fuente_cuerpo."px;",$ren['resultado']);
		?>
		</div>
		<?php 

		if($ren['numero_hojas']==1 || $ren['numero_hojas']==""){
		 ?>
		<div style="width:100%;text-align: center;" class="text-center">
			<table style="margin: auto;text-align:center;font-size:<?php echo $tam_fuente_firma;?>">
				<tr>
					<td>
						<img src="img/<?php echo $datos_guardados_reporte[0]?>.png" alt="" style="height:2.5cm;">
						<?php
							echo "<br>".utf8_encode($renD['nombre'])."".$renD['cedula_profesional'];
						?>
					</td>
				</tr>
			</table>
		</div>
		<hr>
		<?php 
		}
		if($_REQUEST['p']==1){
			echo '<img src="img/'.$imagenesFondo[1].'" alt="" style="height:1.7cm;width:100%;position:relative;margin-top:-0.5cm">';
		}
		 ?>
		
	</div>
<!-- //========================================= SEGUNDA HOJA -->
	<?php if($ren['numero_hojas']>1): ?>
		<div class="page" style=" background-image:url('img/<?php echo $imagenesFondo[2];?>');background-position:center;">
		<?php  if($ren['numero_hojas']==2){ 
			$tamaano=15+$ajustar_firma;
				echo '<div style="height:'.$tamaano.'cm;">';
			?>
			</div>
			<div style="width:100%;text-align: center;" class="text-center">
				<table style="margin: auto;text-align:center;font-size:<?php echo $tam_fuente_firma;?>">
					<tr>
						<td>
							<img src="img/<?php echo $datos_guardados_reporte[0]?>.png" alt="" style="height:2.5cm;">
							<?php
								echo "<br>".utf8_encode($renD['nombre'])."__".$renD['cedula_profesional'];
							?>
						</td>
					</tr>
				</table>
			</div>
			<hr>
		<?php 
		}
		else{
			echo '<div style="height:23.5cm;">
			</div>';
		}
			if($_REQUEST['p']==1){
				echo '<img src="img/'.$imagenesFondo[1].'" alt="" style="height:1.7cm;width:100%;position:relative;margin-top:0.2cm">';
			}
			 ?>
			
		</div>
	<?php endif ?>
<!-- //========================================= TERCERA HOJA -->
	<?php if($ren['numero_hojas']>2): 
		$tamaano=15+$ajustar_firma;
				
		?>
		<div class="page" style=" background-image:url('img/<?php echo $imagenesFondo[2];?>');background-position:center;">

			<?php echo '<div style="height:'.$tamaano.'cm;">'; ?>
			</div>
			<div style="width:100%;text-align: center;" class="text-center">
				<?php  if($ren['numero_hojas']==3){ ?>
				<table style="margin: auto;text-align:center;font-size:<?php echo $tam_fuente_firma;?>">
					<tr>
						<td>
							<img src="img/<?php echo $datos_guardados_reporte[0]?>.png" alt="" style="height:2.5cm;">
							<?php
								echo "<br>".utf8_encode($renD['nombre'])."__".$renD['cedula_profesional'];
							?>
						</td>
					</tr>
				</table>
			</div>
			<hr>
			<?php 
			}
			if($_REQUEST['p']==1){
				echo '<img src="img/'.$imagenesFondo[1].'" alt="" style="height:1.7cm;width:100%;position:relative;margin-top:0.2cm">';
			}
			 ?>
			
		</div>
	<?php endif ?>

</body>

<?php 	
function encabezado($ren,$tam_fuente,$nombre_personalizado){
	if($nombre_personalizado!="" && $nombre_personalizado!=undefined)
		$nombrepe=($nombre_personalizado);
	else
		$nombrepe=utf8_encode($ren['nombre_estudio']);
	echo '<table style="width:100%;font-size:'.$tam_fuente.'px;">	
			<tr>
				<th align="right" colspan="4">'.utf8_encode($ren['nombre_municipio']).', '.fechaActual().'<br><br></th>
			</tr>
			<tr>
				<th align="left" width="15%">Nombre:</th>
				<td align="left" width="45%">'.utf8_encode($ren['nombre_paciente']).'</td>
				<th align="left" width="18%">F. Nacimiento:</th>
				<td align="left" width="22%">'.$ren['fecha_nac'].'</td>
			</tr>
			<tr>
				<th align="left">Expediente:</th>
				<td align="left">'.$ren['expediente'].'</td>
				<th align="left">Edad y sexo:</th>
				<td align="left">'.$ren['edad'].', '.$ren['sexo'].'</td>
			</tr>
			<tr>
				<th align="left">Medico:</th>
				<td align="left">'.utf8_encode($ren['nombre_doctor']).'</td>
				<th align="left">Código:</th>
				<td align="left">'.$ren['consecutivo'].'</td>
			</tr>
			<tr>
				<th align="left">D.Clinico:</th>
				<td align="left" colspan="3">'.$ren['dato_clinico'].'</td>
			</tr>
		</table>		
		<div style="text-align: center;border-top:solid 1px #999;font-size:'.$tam_fuente.'px;">	
				<h3>'.$nombrepe.'</h3>
		</div>';
}

function fechaActual(){
	$dia=date("d");
	$diaL=date("N");
	$mes=date("m");
	$anio=date("Y");

	switch($diaL){
		case 1:
			$diaTexto="Lunes,";
		break;
		case 2:
			$diaTexto="Martes,";
		break;
		case 3:
			$diaTexto="Miércoles,";
		break;
		case 4:
			$diaTexto="Jueves,";
		break;
		case 5:
			$diaTexto="Viernes,";
		break;
		case 6:
			$diaTexto="Sábado,";
		break;
		default:
			$diaTexto="Domingo,";
		break;

	}
	switch ($mes) {
		case 1:
				$mesTexto="Enero";
			break;
		case 2:
				$mesTexto="Febrero";
			break;
		case 3:
				$mesTexto="Marzo";
			break;
		case 4:
				$mesTexto="Abril";
			break;
		case 5:
				$mesTexto="Mayo";
			break;
		case 6:
				$mesTexto="Junio";
			break;
		case 7:
				$mesTexto="Julio";
			break;
		case 8:
				$mesTexto="Agosto";
			break;
		case 9:
				$mesTexto="Septiembre";
			break;
		case 10:
				$mesTexto="Octubre";
			break;
		case 11:
				$mesTexto="Noviembre";
			break;
		case 12:
				$mesTexto="Diciembre";
			break;
		
	}
	return $diaTexto." ".$dia." de ".$mesTexto." de ".$anio;

}
 ?>
<script>
	window.print();
</script>