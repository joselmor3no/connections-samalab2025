<?php 
include("../ajax/conexion.php");

$sql="SELECT ce.id as id_cat_estudio,dcmr.datos_guardados_reporte, dcmr.numero_hojas,dcmr.resultado,ciu.nombre_municipio,	o.consecutivo,dcm.dato_clinico,o.nombre_doctor,p.fecha_nac,p.expediente,concat(p.paterno,' ',p.materno,' ',p.nombre) as nombre_paciente,p.edad,p.tipo_edad,p.sexo,ce.nombre_estudio FROM dcm inner join paciente p on dcm.id_paciente=p.id 
inner join cat_estudio ce on ce.id=dcm.id_categoria 
inner join orden o on o.id=dcm.id_orden  
inner join sucursal s on s.id=o.id_sucursal
 inner join cliente cli on cli.id=s.id_cliente 
 inner join cat_municipio ciu on ciu.id=s.ciudad
 inner join cat_estados est on est.id=s.estado 
 left join dcm_resultado dcmr on dcmr.id_dcm=dcm.id 
where dcm.id=".$_REQUEST['dcm'];

$res=mysql_query($sql);
$ren=mysql_fetch_array($res);
if($ren['datos_guardados_reporte']==null){
	$tam_fuente=15;
	$tam_fuente_firma=15;
	$tam_fuente_cuerpo=15;
	$nuevoNombre=utf8_encode($ren['nombre_estudio']);
	$ajustar_firma=1;
}
else{
	$datos_guardados_reporte=split("-",$ren['datos_guardados_reporte']);
	$tam_fuente=$datos_guardados_reporte[1];
	$tam_fuente_firma=$datos_guardados_reporte[2];
	$tam_fuente_cuerpo=$datos_guardados_reporte[3];
	$nuevoNombre=$datos_guardados_reporte[4];
	$ajustar_firma=floatval($datos_guardados_reporte[5]);
}
if($ren['numero_hojas']==null || $ren['numero_hojas']==""){
	$numeroHojas=1;
}
else{
	$numeroHojas=$ren['numero_hojas'];
}

$sql_textoEstudio="SELECT * FROM texto_radiologo where id_usuario=".$_REQUEST['d']." and 
(
	lista_estudios like '%,".$ren['id_cat_estudio']."%' or 
	lista_estudios like '%".$ren['id_cat_estudio']."%,' or 
	lista_estudios like '%,".$ren['id_cat_estudio']."%,' or 
	lista_estudios=".$ren['id_cat_estudio']."  
)";
$resTE=mysql_query($sql_textoEstudio);
if($resTE)
	$renTE=mysql_fetch_array($resTE);

$sqlSec="SELECT s.id FROM cat_estudio ce left join secciones s on ce.id_secciones=s.id where ce.id=".$ren['id_cat_estudio'];
$resSecF=mysql_query($sqlSec);
$renSEcE=mysql_fetch_array($resSecF);


$sqlF="SELECT te.* from texto_radiologo te left join cat_estudio ce on ce.id=te.id_cat_estudio inner join secciones s on s.id=ce.id_secciones where s.id=".$renSEcE['id'];
$resEstudioF=mysql_query($sqlF);


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1">
	<title>ReporteDCM</title>
	<link rel="icon" type="image/png" href="css/favicon.png" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	<link rel="stylesheet" href="css/reporte_dcm.css">
	<link rel="stylesheet" href="css/summernote.css">
</head>
<body>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-7">
				<form action=""> 
					<div class="row ta_datos">
						<div class="col-12">
							<div class="row">
								<div class="col-12 col-md-5">
									<div class="form-group">
										<label for="estudio">Estudio:</label>
										<input type="text" id="estudio" name="estudio" value="<?php echo $nuevoNombre ?>" class="form-control">
									</div>
								</div>
								<div class="col-12 col-md-3">
									<div class="form-group">
										<label for="fecha_estudio">Fecha:</label>
										<input type="date" id="fecha_estudio" value="<?php echo date('Y-m-d')?>" name="fecha_estudio" class="form-control">
									</div>
								</div>
								<div class="col-12 col-md-4">
									<div class="form-group">
										<label for="formato">Seleccionar otro Formato Precargado:</label>
										<select name="formato" id="formato" class="form-control">
											<option value="">seleccione</option>
											<?php 
											while($renF=mysql_fetch_array($resEstudioF)){
												echo '<option value="'.$renF['id'].'">'.$renF['nombre'].'</option>';
											}
											 ?>
											
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								
								<div class="col-12 col-md-2">
									<div class="form-group">
										<label for="">Número de Hojas</label>
										<select name="nhojas" id="nhojas" class="form-control">
											<option value="1">1 hoja</option>
											<option value="2">2 hojas</option>
											<option value="3">3 hojas</option>
										</select>
										<?php 
											echo '<script>
											document.getElementById("nhojas").value='.$numeroHojas.';
											</script>';
										 ?>
									</div>
								</div>
								<div class="col-12 col-md-2">
									<div class="form-group">
										<label for="ft_datos">Fuente Datos</label>
										<select class="form-control" name="ft_datos" id="ft_datos">
											<option value="12">12px</option>
											<option value="13">13px</option>
											<option value="14">14px</option>
											<option value="15" selected>15px</option>
											<option value="16">16px</option>
											<option value="18">18px</option>
											<option value="20">20px</option>
										</select>
										<?php 
											echo '<script>
											document.getElementById("ft_datos").value='.$tam_fuente.';
											</script>';
										 ?>
									</div>
								</div>
								<div class="col-12 col-md-2">
									<div class="form-group">
										<label for="tf_firma">Fuente Firma</label>
										<select class="form-control" name="ft_firma" id="ft_firma">
											<option value="10">10px</option>
											<option value="11">11px</option>
											<option value="12">12px</option>
											<option value="13">13px</option>
											<option value="14">14px</option>
											<option value="15" selected>15px</option>
											<option value="16">16px</option>
											<option value="18">18px</option>
											
										</select>
										<?php 
											echo '<script>
											document.getElementById("ft_firma").value='.$tam_fuente_firma.';
											</script>';
										 ?>
									</div>
								</div>
								<div class="col-12 col-md-2">
									<div class="form-group">
										<label for="tf_cuerpo">Fuente Cuerpo</label>
										<select class="form-control" name="ft_cuerpo" id="ft_cuerpo">
											<option value="10">10px</option>
											<option value="11">11px</option>
											<option value="12">12px</option>
											<option value="13">13px</option>
											<option value="14">14px</option>
											<option value="15" selected>15px</option>
											<option value="16">16px</option>
											<option value="18">18px</option>
											<option value="20">20px</option>
											<option value="22">22px</option>
											
										</select>
										<?php 
											echo '<script>
											document.getElementById("ft_cuerpo").value='.$tam_fuente_cuerpo.';
											</script>';
										 ?>
									</div>
								</div>
								<div class="col-12 col-md-2">
									<div class="form-group">
										<label for="tf_cuerpo">Ajustar Firma</label>
										<input id="ajustar_firma" class="form-control" type="number" step="0.5" value="<?php echo $ajustar_firma?>">
									</div>
								</div>
								<div class="col-12 col-md-2">
									<div class="form-group text-center">
										</BR>
										<div class="btn btn-success" id="actualizar_resultado">Guardar y visualizar</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row ta_reporte">
						<div class="col-12 ">
							<textarea  class="summernote" name="resultado" id="resultado" >
								<?php 
								if($ren['resultado']!=""){
									echo utf8_encode($ren['resultado']); 
								}
								else{
									echo utf8_encode($renTE['texto']);
								}
									
								
								


								?>
							</textarea>
							
						</div>
						
					</div>
				</form>
			</div>
			<div class="col-5 ta_salida">
				<div class="row vistap">
					<div class="col-12 vista_documento">	
						<div class="embed-responsive embed-responsive-16by9" style="width:100%;height:100%">
						  <iframe class="embed-responsive-item iframe_" src="hoja_carta.php?dcm=<?php echo $_REQUEST['dcm']?>&d=<?php echo $_REQUEST['d']?>&p=1" style="width:100%;height:100%"></iframe>
						</div>
					</div>
				</div>
				<div class="row botonera text-center">
					<div class="col-6" >
						<button class="btn btn-primary" onclick="window.open('imprimir_rdcm.php?dcm=<?php echo $_REQUEST["dcm"]?>&p=0&d=<?php echo $_REQUEST['d']?>')">Imprimir Laboratorio</button>
					</div>
					<div class="col-6">
						<button class="btn btn-primary" onclick="window.open('imprimir_rdcm.php?dcm=<?php echo $_REQUEST["dcm"]?>&p=1&d=<?php echo $_REQUEST['d']?>')">Imprimir Paciente</button>
					</div>
				</div>
			</div>
		</div>
	</div>

<script src="js/jquery-3.3.1.min.js" ></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
	<script src="js/summernote.js"></script>
<script src="js/reporte_dcm.js"></script>
</body>
</html>