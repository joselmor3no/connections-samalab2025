<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');

class Imagenm {
	
	private $conexion;
 
    function __construct() {
        //Validación de session 
        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso) {
            //header("Location: /");
        }

        $this->conexion = new Conexion(); 
    }

    function insertaPacienteDental($datos){
        $sql="INSERT INTO dcm(ruta,id_paciente,fecha,id_orden,id_categoria,id_usuario,prioridad,local) 
                VALUES('".$datos['ruta']."',".$datos['id_paciente'].",'".$datos['fecha_registro']."',".$datos['id_orden'].",".$datos['id_estudio'].",1099,'media','N')";
         $data = $this->conexion->setQuery($sql);
         return "ok";
    }

    
    function eliminarFormatoM($id_formato){
        $sql="DELETE FROM texto_radiologo  where id=".$id_formato;
        $data = $this->conexion->setQuery($sql);
        return $data;
    }

    function contenidoFormatoM($id_formato){
        $sql="SELECT * FROM texto_radiologo tr where tr.id=".$id_formato;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function formatoEstudioM($id_cat_estudio){
        $sql="SELECT * FROM texto_radiologo tr where tr.id_cat_estudio=".$id_cat_estudio." and tr.id_usuario=".$_SESSION['id'];
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function todosFormatosM(){
        $sql="SELECT * FROM texto_radiologo tr where tr.id_usuario=".$_SESSION['id']." order by tr.nombre";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function guardaFormatoM(){
        $sql="INSERT INTO texto_radiologo (texto,nombre,id_usuario,id_cat_estudio,id_sucursal) VALUES('".$_REQUEST['contenido']."','".$_REQUEST['nombre_estudio']."','".$_SESSION['id']."',".$_REQUEST['id_cat_estudio'].",'".$_SESSION['id_sucursal']."')";

        $data = $this->conexion->setQuery($sql);
        return $data;
    }

    function editaFormatoM(){
        $sql="UPDATE texto_radiologo SET nombre='".$_REQUEST['nombre_estudio']."', texto='".$_REQUEST['contenido']."' WHERE id=".$_REQUEST['id_formato'];
        $data = $this->conexion->setQuery($sql);
        return $data;
    }

    function listaEstudiosSeccionM($seccion){
        $sql="SELECT ce.id,ce.nombre_estudio from  estudio e 
            inner join cat_estudio ce on ce.id=e.id_cat_estudio 
            inner join secciones s on s.id=ce.id_secciones 
            where  e.id_sucursal=".$_SESSION['id_sucursal']." and s.codigo='".$seccion."' ORDER BY ce.nombre_estudio";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function listaFormatosGuardadosSeccionM($seccion){
        $sql="SELECT ce.id,ce.nombre_estudio from  estudio e 
            inner join cat_estudio ce on ce.id=e.id_cat_estudio 
            inner join secciones s on s.id=ce.id_secciones 
            where  e.id_sucursal=".$_SESSION['id_sucursal']." and s.codigo='".$seccion."' ORDER BY ce.nombre_estudio";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function listaFormatosSelect(){
         $sql="SELECT * FROM texto_radiologo tr where tr.id_usuario=".$_SESSION['id'];
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function listaPacientesDentalR($fechaInicial,$fechaFinal){
        $sql="SELECT ce.id as id_estudio, o.id as id_orden,p.id as id_paciente,o.consecutivo,p.expediente, CONCAT(p.paterno,' ',p.materno,' ',p.nombre)as paciente,o.fecha_registro, ce.no_estudio,ce.nombre_estudio 
            FROM orden o inner join orden_estudio oe on o.id=oe.id_orden 
            inner join cat_estudio ce on ce.id=oe.id_estudio 
            inner join secciones s on s.id=ce.id_secciones 
            inner join paciente p on p.id=o.id_paciente 
            left join dcm d on d.id_orden=o.id 
            where s.seccion like '%DENT%' and o.fecha_registro BETWEEN '".$fechaInicial."' and '".$fechaFinal." 23:59:59' and d.id IS NULL and o.id_sucursal=".$_SESSION['id_sucursal'];;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function listaPacientesMedicoR($fechaInicial,$fechaFinal,$tipo){
       $sqlS="SELECT id FROM sucursal where visualiza_imagen_global=1 and id_cliente=".$_SESSION['id_cliente']." and id!=".$_SESSION['id_sucursal'];
       $data = $this->conexion->getQuery($sqlS);
       $ids="(".$_SESSION['id_sucursal'];
       foreach ($data AS $row=>$item) {
            $ids.=",".$item->id;
       }
       $ids.=")"; 

       if($tipo=="t")
          $ids="(".$_SESSION['id_sucursal'].")";

        $sql = "SELECT o.id as id_orden,p.email,p.tel,p.expediente as exp,p.id as id_paciente, dr.ruta_archivo,su.prefijo_imagen,d.tamano_zip,d.ruta,d.archivo_zip,dr.cerrado,dr.id as reportado, d.local, su.nombre as sucursal,s.codigo,s.seccion,su.id as id_sucursal, o.consecutivo,d.id as dcm,CONCAT(p.paterno,' ',p.materno,' ',p.nombre) as paciente,o.fecha_registro,ce.nombre_estudio FROM orden o left join dcm d on d.id_orden=o.id 
            left join dcm_resultado dr on dr.id_dcm=d.id 
            inner join paciente p on p.id=o.id_paciente 
            inner join cat_estudio ce on ce.id=d.id_categoria 
            inner join secciones s on s.id=ce.id_secciones 
            inner join sucursal su on o.id_sucursal=su.id 
            where su.id_cliente=".$_SESSION['id_cliente']." and su.id in ".$ids." and d.fecha BETWEEN '".$fechaInicial."' and '".$fechaFinal." 23:59:59' order by s.seccion, o.consecutivo DESC";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function listaPacientesLocalM($fechaInicial,$fechaFinal){
       $sqlS="SELECT id FROM sucursal where visualiza_imagen_global=1 and id_cliente=".$_SESSION['id_cliente']." and id!=".$_SESSION['id_sucursal'];
       $data = $this->conexion->getQuery($sqlS);
       $ids="(".$_SESSION['id_sucursal'];
       foreach ($data AS $row=>$item) {
            $ids.=",".$item->id;
       }
       $ids.=")";

        $sql = "SELECT p.id as id_paciente,o.id as id_orden,oe.id_estudio,su.prefijo_imagen,d.tamano_zip,d.ruta,d.archivo_zip,dr.cerrado,dr.id as reportado, d.local, su.nombre as sucursal,s.codigo,s.seccion, o.consecutivo,d.id as dcm,CONCAT(p.paterno,' ',p.materno,' ',p.nombre) as paciente,o.fecha_registro,ce.nombre_estudio FROM orden o 
            inner join orden_estudio oe on oe.id_orden=o.id
            left join dcm d on d.id_orden=o.id 
            left join dcm_resultado dr on dr.id_dcm=d.id 
            inner join paciente p on p.id=o.id_paciente 
            inner join cat_estudio ce  on ce.id=oe.id_estudio  
            inner join secciones s on s.id=ce.id_secciones 
            inner join sucursal su on o.id_sucursal=su.id 
            where su.id_cliente=".$_SESSION['id_cliente']." and su.id in ".$ids." and s.consecutivo in (19,20,21,24,25) and o.fecha_registro BETWEEN '".$fechaInicial."'   and '".$fechaFinal." 23:59:59' order by s.seccion, o.consecutivo DESC";
 
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function listaPacientesConcluidosM($fechaInicial,$fechaFinal){
        $sqlS="SELECT id FROM sucursal where visualiza_imagen_global=1 and id_cliente=".$_SESSION['id_cliente']." and id!=".$_SESSION['id_sucursal'];
       $data = $this->conexion->getQuery($sqlS);
       $ids="(".$_SESSION['id_sucursal'];
       foreach ($data AS $row=>$item) {
            $ids.=",".$item->id;
       }
       $ids.=")";

        $sql = "SELECT su.prefijo_imagen, o.consecutivo,u.nombre as nombre_usuario,su.prefijo_imagen,d.ruta,su.nombre as sucursal, o.consecutivo,d.id as dcm,CONCAT(p.paterno,' ',p.materno,' ',p.nombre) as paciente,o.fecha_registro,ce.nombre_estudio FROM orden o left join dcm d on d.id_orden=o.id 
            inner join dcm_resultado dr on dr.id_dcm=d.id 
            left join usuario u on u.id=d.id_usuario 
            inner join paciente p on p.id=o.id_paciente 
            inner join cat_estudio ce on ce.id=d.id_categoria 
            inner join sucursal su on o.id_sucursal=su.id 
            where su.id_cliente=".$_SESSION['id_cliente']." and su.id in ".$ids." and o.fecha_registro BETWEEN '".$fechaInicial."' and '".$fechaFinal." 23:59:59' and dr.cerrado=1 order by  o.consecutivo DESC";

            $data = $this->conexion->getQuery($sql);
            return $data;
    }

    function actualizaEstatusDescomprimido($id_dcm){
        $sql = "UPDATE dcm SET local='N' WHERE id=".$id_dcm;
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function eliminarDCM($id_dcm){
        $sql = "DELETE FROM dcm WHERE id=".$id_dcm;
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function concluirDCM($id_dcm){
        $sql = "UPDATE dcm_resultado SET cerrado=1 WHERE id_dcm=".$id_dcm;
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function quitarConclusionDCM($id_dcm){
        $sql = "UPDATE dcm_resultado SET cerrado=0 WHERE id_dcm=".$id_dcm;
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function resultadoDCMM($id_dcm){
        $sql="SELECT resultado from dcm_resultado where id_dcm=".$id_dcm;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function mdlGuardaReporteLocal(){
        $sql = "INSERT into dcm (id_paciente,id_orden,id_categoria,id_usuario,prioridad,local) VALUES(".$_REQUEST['ft_id_paciente'].",".$_REQUEST['ft_id_orden'].",".$_REQUEST['ft_id_cat_estudio'].",".$_SESSION['id'].",'media','S')";
            $this->conexion->setQuery($sql);
            $sqlS="SELECT id from dcm where id_orden=".$_REQUEST['ft_id_orden']." ORDER BY id DESC";
            $data = $this->conexion->getQuery($sqlS);
            $datos_gr=$_SESSION['id']."-15-15-15-".$_REQUEST['ft_nombre_estudio']."-".$_REQUEST['ft_ajustaFirma'];
            $sql = "INSERT into dcm_resultado (resultado,id_dcm,cerrado,numero_hojas,datos_guardados_reporte) VALUES('".$_REQUEST['resultado']."',".$data[0]->id.",0,".$_REQUEST['nhojas'].",'".$datos_gr."')";
            $this->conexion->setQuery($sql);
            return "ok";
    }

    function mdlActualizaReporteLocal(){
        $datos_gr=$_SESSION['id']."-15-15-15-".$_REQUEST['ft_nombre_estudio']."-".$_REQUEST['ft_ajustaFirma'];
        $sql="UPDATE dcm_resultado SET resultado='".$_REQUEST['resultado']."', numero_hojas=".$_REQUEST['nhojas'].", datos_guardados_reporte='".$datos_gr."' WHERE id_dcm=".$_REQUEST['ft_id_dcm'];
        $this->conexion->setQuery($sql);
        return "ok";
    }

    function concluirLocalDCMM($id_dcm){
        $sql="UPDATE dcm_resultado SET cerrado=1 WHERE id_dcm=".$id_dcm;
        $this->conexion->setQuery($sql);
        return "ok";
    }
}