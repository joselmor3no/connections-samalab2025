<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');

class Empresas {

    private $conexion;

    function __construct() {
         //Validación de session 
        if(isset($_REQUEST["opc"])){
            $usuarios = new Usuarios();
            $acceso = $usuarios->validarSession();
            if (!$acceso) {
                header("Location: /");
            }
        }
        

        $this->conexion = new Conexion();
    }

    function actualizaClaseEmpresaM($data){
        $sql = "DELETE FROM empresa_clase_estudio WHERE id_empresa=".$data['id_empresa']." and id_clase=".$data['id_clase'];
        $this->conexion->setQuery($sql);
        $sql = "INSERT INTO empresa_clase_estudio VALUES(".$data['id_empresa'].",".$data['id_clase'].",'".$data['tipo_descuento']."',".$data['porcentaje'].")";
        $data = $this->conexion->setQuery($sql);
        return "ok";
    }

    function getClasesEstudioEmpresa($id_empresa){
        $sql = "SELECT e.id,ece.id_clase,ece.porcentaje_descuento,ece.tipo_descuento from empresa e left join empresa_clase_estudio ece on e.id=ece.id_empresa left join clasificacion_estudio ce on ce.id=ece.id_clase where e.id=".$id_empresa;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getClasesEstudio(){ 
        $sql = "SELECT * FROM clases_estudio_ec  order by nombre";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEmpresas($id_sucursal) {

        $sql = "SELECT * 
            FROM empresa
            WHERE id_sucursal = '$id_sucursal' AND activo = 1
            ORDER BY nombre";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteEmpresa($id) {

        $sql = "UPDATE empresa
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        $data = array(
            "observaciones" => "ELIMINACION DE PAQUETE: " . str_replace("'", "", $sql),
            "tabla" => "empresa",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);

        $catalogos->logActivity($data);
    }

    function getEmpresa($id) {

        $sql = "SELECT *
      FROM empresa
      WHERE id = '$id'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addEmpresa($data) {


        $sql = "INSERT INTO `empresa` (`alias`, `nombre`, `direccion`, `ciudad`, `estado`, `cp`, `rfc`, `telefono`, "
                . "`celular`, `contacto`, `promotor`, `porcentaje`, `credito`, `laboratorio`, `email`, `porcentaje_pago`, `activo`, "
                . "`aumento`, `id_lista_precios`, `id_sucursal`, inactivo, tipo, contrasena, expediente, `mostrarlogo`) "
                . "VALUES ('" . $data["alias"] . "', '" . $data["nombre"] . "', '" . $data["direccion"] . "', '" . $data["ciudad"] . "', '" . $data["estado"] . "', '" . $data["cp"] . "', '" . $data["rfc"] . "', '" . $data["tel"] . "', "
                . "'" . $data["cel"] . "', '" . $data["contacto"] . "', '" . $data["promotor"] . "', " . $data["porcentaje"] . ", " . $data["credito"] . ", " . $data["laboratorio"] . ", '" . $data["email"] . "', " . $data["porcentaje_pago"] . ", 1, "
                . "" . $data["aumento"] . ", " . $data["id_lista_precios"] . ", " . $data["id_sucursal"] . ", " . $data["inactivo"] . ", '" . $data["tipo_sucursal"] . "', '" . $data["pass"] . "','" . $data["codigo"] . "_" . $data["expediente"] . "'," . $data["logo"] . ");";


        $this->conexion->setQuery($sql);
    }

    function editEmpresa($data) {

        $sql = "UPDATE empresa "
                . "SET alias = '" . $data["alias"] . "', nombre = '" . $data["nombre"] . "', direccion = '" . $data["direccion"] . "', ciudad = '" . $data["ciudad"] . "', estado = '" . $data["estado"] . "', cp = '" . $data["cp"] . "', rfc = '" . $data["rfc"] . "', telefono = '" . $data["tel"] . "', "
                . "celular = '" . $data["cel"] . "', contacto = '" . $data["contacto"] . "', promotor = '" . $data["promotor"] . "', porcentaje = " . $data["porcentaje"] . ", credito = " . $data["credito"] . ", laboratorio = " . $data["laboratorio"] . ", email = '" . $data["email"] . "', porcentaje_pago = " . $data["porcentaje_pago"] . ", "
                . "aumento = " . $data["aumento"] . ", id_lista_precios = " . $data["id_lista_precios"] . ", inactivo = " . $data["inactivo"] . ", tipo = '" . $data["tipo_sucursal"] . "', contrasena = '" . $data["pass"] . "', expediente = '" . $data["codigo"] . "_" . $data["expediente"] . "', mostrarlogo = " . $data["logo"] . " "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);
    }

    function aliasEmpresa($alias, $id_sucursal) {
        $sql = "SELECT alias
      FROM empresa
      WHERE alias = '$alias' AND id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function listaEmpresasSelectM($fechaInicial,$fechaFinal){
        $sql="SELECT DISTINCT e.id, e.nombre FROM orden o inner join empresa e on o.id_empresa=e.id where o.id_empresa IS NOT NULL and o.fecha_registro BETWEEN '".$fechaInicial."' and '".$fechaFinal."' ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
