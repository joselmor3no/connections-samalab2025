<?php 	
include("../ajax/conexion.php");

$sql="SELECT dato_clinico from dcm where dcm.id=".$_REQUEST['dcm'];

$res=mysql_query($sql);
$ren=mysql_fetch_array($res);
if($ren['dato_clinico']!="")
	echo $ren['dato_clinico'];
else
	echo "SIN DATO CLINICO AGREGADO";
 ?>
