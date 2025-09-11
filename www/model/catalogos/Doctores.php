<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Doctores {

    private $conexion; 

    function __construct() {
        //Validación de session
        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso) {
            header("Location: /");
        }

        $this->conexion = new Conexion();
    }

    function getPrefijos(){
        $sql = "SELECT DISTINCT d.prefijo from doctor d inner join doctor_sucursal dr on dr.id_doctor=d.id where  activo=1 order by prefijo";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function actualizaPromotorM($origen,$destino,$sucursal){
        $sql="UPDATE doctor SET promotor=".$destino." where id_sucursal=".$sucursal." and promotor=".$origen." and activo=1";
        $this->conexion->setQuery($sql);
        return;

    }

    function consecutivoPrefijoM($prefijo){
        $sql = "SELECT consecutivo_prefijo from doctor where prefijo='".$prefijo."' and activo=1 ORDER BY consecutivo_prefijo DESC limit 1 ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function buscarSugerenciasDoctores($busqueda,$id_sucursal){
        $arrayBusqueda=explode("-",$busqueda);
        $sql = "SELECT DISTINCT CONCAT(d.alias,'-',d.apaterno,' ',d.amaterno,' ',d.nombre) as nombre,d.alias,d.id from doctor d inner join doctor_sucursal ds on ds.id_doctor=d.id AND d.activo=1 where (CONCAT(d.alias,'-',d.apaterno,' ',d.amaterno,' ',d.nombre) like '%".$busqueda."%' or d.alias = '".$arrayBusqueda[0]."') and d.activo=1  limit 10";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function buscarDoctoresCatalogo($datos){
        $sql = "SELECT id,apaterno,amaterno,nombre from doctor where apaterno like '%".$datos['paterno']."%' and amaterno like '%".$datos['materno']."%' and nombre like '%".$datos['nombre']."%' and activo=1";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getDoctores($id_sucursal) {

        $sql = "SELECT d.*, p.nombre as nombre_promotor, e.especialidad as nombre_especialidad, z.nombre as nombre_zona   
            FROM doctor d inner join doctor_sucursal ds on ds.id_doctor=d.id 
            left join promotores p on d.promotor=p.id 
            left join especialidad e on d.id_especialidad=e.id 
            left join zonas z on z.id=d.zona 
            WHERE d.id_sucursal = '$id_sucursal' AND d.activo = 1 group by d.id order by d.nombre";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }
    
    function getDoctoresFiltros($id_sucursal,$promotor,$zona,$alias,$tipo,$prefijo) {
       
        if($alias==''){
            if($promotor!="")
                $promotor=" AND p.id=".$promotor." ";
            if($prefijo!="")
                $prefijo=" AND d.prefijo='".$prefijo."' ";
            else
                $prefijo=" AND d.prefijo IS NULL ";
            if($zona!="")
                $zona=" AND z.id=".$zona." ";
            if($especialidad!="")
                $especialidad=" AND e.id=".$especialidad." ";
            if($tipo!="")
                $tipo=" AND d.tipo='".$tipo."' ";
                $sql = "SELECT d.*, p.nombre as nombre_promotor, e.especialidad as nombre_especialidad, z.nombre as nombre_zona   
                FROM doctor d inner join doctor_sucursal ds on ds.id_doctor=d.id 
                left join promotores p on d.promotor=p.id 
                left join especialidad e on d.id_especialidad=e.id 
                left join zonas z on z.id=d.zona 
                WHERE d.activo = 1  $promotor $zona $especialidad $tipo $prefijo";
            //echo $sql;
        }
        else{
            $sql = "SELECT d.*, p.nombre as nombre_promotor, e.especialidad as nombre_especialidad, z.nombre as nombre_zona   
                FROM doctor d inner join doctor_sucursal ds on ds.id_doctor=d.id 
                left join promotores p on d.promotor=p.id 
                left join especialidad e on d.id_especialidad=e.id 
                left join zonas z on z.id=d.zona 
                WHERE d.activo = 1 AND (alias like '".$alias."' || CONCAT(d.apaterno,' ',d.amaterno) like '%".$alias."%' ) ";
        }
    
        $data = $this->conexion->getQuery($sql);
        return $data;
    }


    function getDoctor($id) {

        $sql = "SELECT d.*, p.nombre as nombre_promotor, e.especialidad as nombre_especialidad   
            FROM doctor d left join promotores p on d.promotor=p.id 
            left join especialidad e on d.id_especialidad=e.id 
            WHERE d.id = '$id' order by d.nombre";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addDoctor($data) {

        $sql = "INSERT INTO `doctor`(`prefijo`,`consecutivo_prefijo`,`alias`, `nombre`, `apaterno`, `amaterno`, `direccion`, `ciudad`, `estado`, "
                . "`cp`, `tel`, `celular`, `porcentaje`,`porcentaje_imagen`, `email`,  "
                . "`id_especialidad`, `id_sucursal`,zona,promotor, contrasena, expediente,tipo) "
                . "VALUES ('" . $data["prefijo"] . "','" . $data["consecutivo_prefijo"] . "','" . $data["alias"] . "', '" . strtoupper($data["nombre"]) . "','" . strtoupper($data["apaterno"]) . "','" . strtoupper($data["amaterno"]) . "', '" . $data["direccion"] . "', '" . $data["ciudad"] . "', '" . $data["estado"] . "', "
                . "'" . $data["cp"] . "', '" . $data["tel"] . "', '" . $data["cel"] . "', '" . $data["porcentaje"] . "', '" . $data["porcentaje_imagen"] . "', '" . $data["email"] . "', "
                . $data["id_especialidad"] . ", '" . $data["id_sucursal"] . "', '" . $data["id_zona"] . "', '" . $data["id_promotor"] . "',  '" . $data["pass"] . "', '" . $data["codigo"] . "_" . $data["descarga"] . "',  '" . $data["tipo"] . "')";

        $this->conexion->setQuery($sql);

        $sqlU="SELECT max(id) as id from doctor where id_sucursal=".$data["id_sucursal"];
        $data2 = $this->conexion->getQuery($sqlU);
        $sql='INSERT INTO doctor_sucursal VALUES("'.$data2[0]->id.'","'.$data["id_sucursal"].'")';
        $this->conexion->setQuery($sql);
        if($data["id_sucursal"]==124){
            $sql='INSERT INTO doctor_sucursal VALUES("'.$data2[0]->id.'","123")';
            $this->conexion->setQuery($sql);
            $sql='INSERT INTO doctor_sucursal VALUES("'.$data2[0]->id.'","140")';
            $this->conexion->setQuery($sql); 
        }

    //----------------------- Insertando el medico en la matriz
        $sqlS="SELECT id from sucursal where id_cliente=".$_REQUEST['id_cliente']." and tipo='MATRIZ' ";
        $matrizCliente = $this->conexion->getQuery($sqlS);
        if($_REQUEST['id_sucursal']!=$matrizCliente[0]->id){
            $sql='INSERT INTO doctor_sucursal VALUES("'.$data2[0]->id.'","'.$matrizCliente[0]->id.'")';
            $this->conexion->setQuery($sql);
        }
        //log_activity
        $data = array(
            "observaciones" => "NUEVO DOCTOR: " . str_replace("'", "", $sql),
            "tabla" => "doctor",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function deleteDoctor($id) {

        $sql = "UPDATE doctor
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE DOCTOR: " . str_replace("'", "", $sql),
            "tabla" => "doctor",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editDoctor($data) {

        $sql = "UPDATE doctor "
                . "SET  nombre = '" . $data["nombre"] . "',apaterno = '" . $data["apaterno"] . "',amaterno = '" . $data["amaterno"] . "', direccion = '" . $data["direccion"] . "', ciudad = '" . $data["ciudad"] . "', estado = '" . $data["estado"] . "', "
                . "cp = '" . $data["cp"] . "', tel = '" . $data["tel"] . "', celular = '" . $data["cel"] . "', porcentaje = '" . $data["porcentaje"] . "', porcentaje_imagen = '" . $data["porcentaje_imagen"] . "', email = '" . $data["email"] . "', "
                . "id_especialidad = " . $data["id_especialidad"] . ", contrasena = '" . $data["pass"] . "',promotor='".$data["id_promotor"]."', expediente = 'sama1_" . $data["descarga"] . "'"
                . " WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE DOCTOR: " . str_replace("'", "", $sql),
            "tabla" => "doctor",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function aliasDoctor($alias, $id_sucursal) {
        $sql = "SELECT alias 
            FROM doctor
            WHERE alias = '$alias' AND id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getUltimoConsecutivoSucursal($id_sucursal){
        $sql = "SELECT consecutivo_prefijo+1 as consecutivo from doctor where id_sucursal=".$id_sucursal." AND activo=1 Order by consecutivo_prefijo DESC limit 1";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }
    //--------------------- ZONAS -----------------------------
    function getZonas($id_sucursal){

        if($id_sucursal==144)
            $id_sucursal=121;
        $sql = "SELECT * from zonas where id_sucursal=".$id_sucursal." order by nombre";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getInfoZona($id_zona){
        $sql = "SELECT * from zonas where id=".$id_zona;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getUltimoConsecutivoZona($id_sucursal){
        $sql = "SELECT numero+1 as numero from zonas where id_sucursal=$id_sucursal order by numero DESC limit 1";
        $data = $this->conexion->GetQuery($sql);
        if($data[0]->numero>0)
            return $data[0]->numero;
        else    
            return 1;
    }

    function addZona($datos){
        $consecutivo=$this->getUltimoConsecutivoZona($datos['id_sucursal']);
         $sql = "INSERT INTO zonas(numero,nombre,id_sucursal) values(".$consecutivo.",'".$datos['nombre']."',".$datos['id_sucursal'].")";
        $data = $this->conexion->SetQuery($sql);

    }


    function editZona($datos){
        $sql = "UPDATE zonas SET nombre='".$datos['nombre']."' where id=".$datos['id']."";
        $data = $this->conexion->SetQuery($sql);
    }

    function deleteZona($id_zona){
        $sql = "DELETE FROM zonas where id=".$id_zona;
        $data = $this->conexion->SetQuery($sql);
    }


    //--------------------- PROMOTORES -----------------------------
    function getPromotores($id_sucursal){
        if($id_sucursal==121 || $id_sucursal==144){
            $sql = "SELECT p.*, s.nombre as nombre_sucursal from promotores p left join sucursal s on p.sucursal_destino=s.id where p.id_sucursal=121 order by p.nombre";
        }
        else{
            $sql = "SELECT p.*,s.nombre as nombre_sucursal from promotores p left join sucursal s on p.sucursal_destino=s.id where p.id_sucursal=$id_sucursal and p.sucursal_destino=".$id_sucursal." order by nombre";
        }

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getUltimoConsecutivoPromotor($id_sucursal){
        $sql = "SELECT numero+1 as numero from promotores where id_sucursal=$id_sucursal order by numero DESC limit 1";
        $data = $this->conexion->GetQuery($sql);
        return $data;
    }

    function getZonasPromotor($id_promotor){
        $sql = "SELECT z.id from zona_promotor zp inner join zonas z on z.id=zp.id_zona where zp.id_promotor=".$id_promotor;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getInfoPromotor($id_promotor){
        $sql = "SELECT * from promotores where id=".$id_promotor;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addPromotor($datos){
        $consecutivo=$this->getUltimoConsecutivoPromotor($datos['id_sucursal'])[0]->numero;
        if(!$consecutivo>0)
            $consecutivo=1;
         $sql = "INSERT INTO promotores(numero,nombre,id_sucursal,sucursal_destino) values(".$consecutivo.",'".$datos['nombre']."',".$datos['id_sucursal'].",".$datos['sucursal_destino'].")";
        $data = $this->conexion->SetQuery($sql);

        $sql="SELECT MAX(id) as id FROM promotores";
        $ultimoId = $this->conexion->GetQuery($sql)[0];

        for ($i=0;$i<count($datos['lista_zonas']);$i++){     
            $sql = "INSERT INTO zona_promotor(id_zona,id_promotor) VALUES(".$datos['lista_zonas'][$i].",".$ultimoId->id.")";
            $data = $this->conexion->SetQuery($sql);
        } 

    }


    function editPromotor($datos){
        $sql = "UPDATE promotores SET nombre='".$datos['nombre']."', sucursal_destino='".$datos['sucursal_destino']."' where id=".$datos['id']."";
        $data = $this->conexion->SetQuery($sql);
        $this->deleteZonasPromotor($datos['id']);

        for ($i=0;$i<count($datos['lista_zonas']);$i++){     
            $sql = "INSERT INTO zona_promotor(id_zona,id_promotor) VALUES(".$datos['lista_zonas'][$i].",".$datos['id'].")";
            $data = $this->conexion->SetQuery($sql);
        } 
    }

    function deletePromotor($id_promotor){
        $sql = "DELETE FROM promotores where id=".$id_promotor;
        $data = $this->conexion->SetQuery($sql);
    }

    function deleteZonasPromotor($id_promotor){
        $sql = "DELETE FROM zona_promotor where id_promotor=".$id_promotor;
        $data = $this->conexion->SetQuery($sql);
    }

    function close() {

        $this->conexion->close();
    }

}

?>
