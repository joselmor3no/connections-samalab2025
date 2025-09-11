<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/model/Conexion.php');

class Doctores {

    private $conexion;

    function __construct() {
        $this->conexion = new Conexion();
    }

    function getDoctores($id_sucursal){
         $sql = "SELECT d.*,p.nombre as promotor, e.especialidad,GROUP_CONCAT(s.id) as id_sucursales ,GROUP_CONCAT(s.nombre) as sucursales, z.nombre as nombre_zona, d.id as id_doctor from doctor d 
                left join especialidad e on d.id_especialidad=e.id 
                left join promotores p on d.promotor=p.id 
                left join doctor_sucursal ds on ds.id_doctor=d.id
                left join sucursal s on s.id=ds.id_sucursal 
                left join zonas z on z.id=d.zona 
                where d.id_sucursal=".$id_sucursal." and d.activo=1 GROUP BY d.id ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function eliminaDoctorSucursal($id_doctor){
        $sql="DELETE FROM doctor_sucursal where id_doctor=".$id_doctor;
        $this->conexion->setQuery($sql);
        return;
    }

    function actualizaDoctorSucursal($id_doctor,$lista_sucursales){
        $sucursales=explode(",", $lista_sucursales);
        for ($i=0; $i <count($sucursales) ; $i++) { 
            $sql="INSERT INTO doctor_sucursal VALUES(".$id_doctor.",".$sucursales[$i].")";
            $this->conexion->setQuery($sql);
        }

    }

}