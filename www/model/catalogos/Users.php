<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Users {

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

    function getUsuarios($id_sucursal) {

        $sql = "SELECT usuario.*, tipo_empleado.tipo
            FROM usuario
            INNER JOIN tipo_empleado ON (usuario.id_tipo_empleado = tipo_empleado.id)
            WHERE id_sucursal = '$id_sucursal' AND activo = 1 AND usuario NOT LIKE '%connections%'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getUsuario($id) {

        $sql = "SELECT * 
            FROM usuario
            WHERE id = '$id'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addUsuario($data) {
        $accesoSucursales="";
        if($data["lista_sucursales"]!="NULL")
            $accesoSucursales=implode(',',$data["lista_sucursales"]);
        $sql = "INSERT INTO `usuario`(no, `usuario`, `contraseña`, `nombre`, `id_tipo_empleado`, "
                . "`entrada_trabajo`, `salida_trabajo`, id_sucursal,acceso_sucursales) "
                . "SELECT MAX(no)+1, '" . $data["prefijo"] . "_" . $data["user"] . "', '" . $data["pass"] . "', '" . $data["nombre"] . "', " . $data["id_tipo_empleado"] . ", "
                . "" . $data["salida"] . ", " . $data["entrada"] . ", '" . $data["id_sucursal"] . "','".$accesoSucursales."' FROM usuario WHERE id_sucursal = " . $data["id_sucursal"] . "";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO USUARIO: " . str_replace("'", "", $sql),
            "tabla" => "usuario",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editUsuario($data) {

        $accesoSucursales=implode(',',$data["lista_sucursales"]);
        $sql = "UPDATE `usuario` "
                . "SET `usuario` = '" . $data["prefijo"] . "_" . $data["user"] . "', `contraseña` = '" . $data["pass"] . "', `nombre` = '" . $data["nombre"] . "', "
                . " `id_tipo_empleado` = " . $data["id_tipo_empleado"] . ", `entrada_trabajo` = " . $data["entrada"] . ", `salida_trabajo` = " . $data["salida"] . ", acceso_sucursales='".$accesoSucursales."' "
                . "WHERE id = " . $data["id"] . "";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE USUARIO: " . str_replace("'", "", $sql),
            "tabla" => "usuario",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function deleteUsuario($id) {

        $sql = "UPDATE usuario
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE USUARIO: " . str_replace("'", "", $sql),
            "tabla" => "usuario",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function aliasUsuario($alias, $id_sucursal) {
        $sql = "SELECT usuario 
            FROM usuario
            WHERE usuario = '$alias' AND id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPermisosUsuario($id_usuario) {

        $sql = "SELECT permisos_usuario.*, cat_permisos.siglas,cat_permisos.global, cat_permisos.seccion, cat_permisos.descripcion, cat_permisos.tooltip
        
            FROM permisos_usuario
            INNER JOIN cat_permisos ON (permisos_usuario.id_cat_permiso = cat_permisos.id)
            WHERE permisos_usuario.id_usuario = '$id_usuario'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addPermisosUsuario($data) {

        $sql = "DELETE permisos_usuario
        FROM permisos_usuario 
        INNER JOIN cat_permisos ON (cat_permisos.id = permisos_usuario.id_cat_permiso)
        WHERE cat_permisos.tipo = 'permiso' AND  permisos_usuario.id_usuario =" . $data["id_usuario"];
        $this->conexion->setQuery($sql);

        foreach ($data["permisos"] AS $row) {
            $sql = "INSERT INTO `permisos_usuario`( `id_cat_permiso`, `id_usuario`) "
                    . "VALUES ('" . $row . "', '" . $data["id_usuario"] . "' )";

            $this->conexion->setQuery($sql);
        }
    }

    function addPermisosInformesUsuario($data) {

        $sql = "DELETE permisos_usuario
        FROM permisos_usuario 
        INNER JOIN cat_permisos ON (cat_permisos.id = permisos_usuario.id_cat_permiso)
        WHERE cat_permisos.tipo = 'informe' AND  permisos_usuario.id_usuario =" . $data["id_usuario"];
        $this->conexion->setQuery($sql);

        foreach ($data["informes"] AS $row) {
            $sql = "INSERT INTO `permisos_usuario`( `id_cat_permiso`, `id_usuario`) "
                    . "VALUES ('" . $row . "', '" . $data["id_usuario"] . "' )";

            $this->conexion->setQuery($sql);
        }
    }

    function getCatPermisosUsuario() {

        $sql = "SELECT *
            FROM cat_permisos
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    } 

    function getUsuariosReporteGlobal($id_sucursal){
        $sql = "SELECT *
            FROM usuario
            WHERE id_sucursal =".$id_sucursal." and (id_tipo_empleado=1 or id_tipo_empleado=5 or id_tipo_empleado=11) and activo=1 and nombre!='CONNECTIONS' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEmpresasReporteGlobal($id_sucursal){
        $sql = "SELECT id,nombre FROM empresa where id_sucursal=".$id_sucursal." and activo=1 order by nombre";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getDoctoresReporteGlobal($id_sucursal){
        $sql = "SELECT id,nombre FROM doctor where id_sucursal=".$id_sucursal." and activo=1 order by nombre";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getFormaPagoReporteGlobal($id_sucursal){
        $sql = "SELECT id,descripcion FROM forma_pago where id_sucursal=".$id_sucursal." and activo=1 order by descripcion";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function obtenerReporteGlobal($fechaInicial,$fechaFinal,$id_sucursal,$empresa,$usuario,$doctor,$estatus,$tipo_pago){
        $_usuario=$_empresa=$_doctor=$_cancelado=$_tipop=null;
        if($empresa!=-1 && $empresa!="-1"){
            $_empresa="and  e.id=".$empresa;
        }
        if($usuario!=-1 && $usuario!="-1"){
            $_usuario="and  u.id=".$usuario;
        }
        if($doctor!=-1 && $doctor!="-1"){
            $_doctor="and  d.id=".$doctor;
        }
        if($estatus==2 || $estatus=='2'){
            $_cancelado="and  o.cancelado=1";
        }
        if($tipo_pago!=-1 && $tipo_pago!="-1"){
            $_tipop="and  fp.descripcion='".$tipo_pago."' ";
        }
        $sql = "SELECT  descu.nombre as nombre_descuento,o.id as id_orden,pa.cpEmail as correo,o.telefono,GROUP_CONCAT(est.clase) as clases, GROUP_CONCAT(ece.tipo_descuento) as tipos_descuento_clase,GROUP_CONCAT(ece.porcentaje_descuento) as montos_descuento_clase,e.porcentaje as porcentaje_descuentoc,o.tipo_cliente,dep.departamento as nombre_departamento,clasee.nombre as nombre_clasee, o.tipo_orden, GROUP_CONCAT(paq.nombre) as nombre_paquete,GROUP_CONCAT(ref.codigo) as referencias, o.sucursal_maquila, GROUP_CONCAT(oe.id_estudio) as id_estudios,o.cancelado, o.consecutivo,e.nombre as empresa, o.credito,CONCAT(pa.paterno,' ',pa.materno,' ',pa.nombre) as paciente,
        if(o.id_doctor is null,CONCAT(o.nombre_doctor,'(nr)'),CONCAT(d.apaterno,' ',d.amaterno,' ',d.nombre)) as doctor,d.alias as alias_doctor, o.fecha_registro, u.nombre as usuario, GROUP_CONCAT(ce.no_estudio) as codigos_estudio,
GROUP_CONCAT(ce.nombre_estudio) as nombres_estudio,GROUP_CONCAT(oe.precio_neto_estudio) as precios_netos,GROUP_CONCAT(oe.precio_publicoh) as preciosp_estudio, 
o.importe,o.importe-o.saldo_deudor as acuenta,o.saldo_deudor,GROUP_CONCAT(sec.seccion) as secciones,o.tipo_orden,if(o.sucursal_maquila>0,'maquila','-') as maquila,suc.nombre as nombre_sucursal from orden o 
        left join descuento descu on descu.id=o.id_descuento 
        left join empresa e on e.id=o.id_empresa
        inner join paciente pa on pa.id=o.id_paciente 
        left join doctor d on d.id=o.id_doctor 
        inner join usuario u on u.id=o.id_usuario 
        inner join orden_estudio oe on oe.id_orden=o.id 
        inner join cat_estudio ce on ce.id=oe.id_estudio 
        inner join departamento dep on dep.id=ce.id_departamento  
        inner join estudio est on est.id_cat_estudio=ce.id and est.id_sucursal=$id_sucursal 
        left join clases_estudio_ec clasee on clasee.id=est.clase 
        left join empresa_clase_estudio ece on ece.id_empresa=e.id and ece.id_clase=clasee.id
        left join referencia ref on ref.id=est.id_referencia 
        inner join secciones sec on sec.id=ce.id_secciones 
        inner join sucursal suc on suc.id=o.id_sucursal 
        left join paquete paq on paq.id=oe.id_paquete 
        where o.id_sucursal=$id_sucursal $_usuario $_empresa $_doctor $_cancelado $_tipop and o.fecha_registro >= '".$fechaInicial." 00:00:00' and o.fecha_registro<= '".$fechaFinal." 23:59:59' group by o.id   
            ORDER BY  o.consecutivo ASC";
        //echo $sql;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPrecioEstudio($id_estudio,$id_sucursal){
        $sql="SELECT precio_publico from estudio where id_cat_estudio=".$id_estudio." and id_sucursal=".$id_sucursal;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function obtenerReporteGlobalCaja($fechaInicial,$fechaFinal,$id_sucursal,$usuario){

        if($usuario!=-1 && $usuario!="-1"){
            $_usuario="and  u.id=".$usuario;
        }
        $sql="SELECT o.id,e.nombre as nombre_empresa,s.nombre,o.fecha_registro,o.consecutivo,CONCAT(p.nombre,' ',p.paterno,' ',p.materno) as nombre_paciente,pa.pago,pa.fecha_pago,o.importe,(o.importe-o.saldo_deudor) as cubierto,o.saldo_deudor,o.tipo_pago,o.credito,o.cancelado,u.nombre as nombre_usuario from orden o 
            left join pago pa on pa.id_orden=o.id
            inner join usuario u on o.id_usuario=u.id 
            inner join paciente p on p.id=o.id_paciente
            inner join sucursal s on o.id_sucursal=s.id
            left join empresa e on o.id_empresa=e.id 
            where s.id=$id_sucursal $_usuario and o.fecha_registro >= '".$fechaInicial." 00:00:00' and o.fecha_registro<= '".$fechaFinal." 21:59:59' ORDER BY o.fecha_registro";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function obtenerReporteGlobalPaciente($fechaInicial,$fechaFinal,$id_sucursal,$id_estudio){
        $sql="SELECT o.id,o.fecha_registro,o.consecutivo,p.fecha_nac,o.telefono,
        CONCAT(p.nombre,' ',p.paterno,' ',p.materno) as nombre_paciente,p.expediente, 
        u.nombre as nombre_usuario from orden o 
            inner join usuario u on o.id_usuario=u.id 
            inner join paciente p on p.id=o.id_paciente
            inner join sucursal s on o.id_sucursal=s.id 
            inner join orden_estudio oe on oe.id_orden=o.id and oe.id_estudio=$id_estudio 
            where s.id=$id_sucursal  and o.fecha_registro >= '".$fechaInicial." 00:00:00' and o.fecha_registro<= '".$fechaFinal." 21:59:59' ORDER BY o.fecha_registro";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function obtenerReporteGlobalCajaPago($fechaInicial,$fechaFinal,$id_sucursal,$usuario){

        if($usuario!=-1 && $usuario!="-1"){
            $_usuario="and  u.id=".$usuario;
        }
        $sql="SELECT o.tipo_pago,SUM(o.importe) as importe,SUM((o.importe-o.saldo_deudor)) as cubierto,SUM(o.saldo_deudor) as deudor from orden o 
            inner join usuario u on o.id_usuario=u.id 
            inner join paciente p on p.id=o.id_paciente
            inner join sucursal s on o.id_sucursal=s.id
            where s.id=$id_sucursal $_usuario and o.fecha_registro >= '".$fechaInicial." 00:00:00' and o.fecha_registro<= '".$fechaFinal." 21:59:59' GROUP BY o.tipo_pago ORDER BY o.fecha_registro";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

     function obtenerReporteGlobalDescripcionPago($id_orden){
        $sql="select fp.descripcion from pago p inner join forma_pago fp on p.id_forma_pago=fp.id where p.id_orden=".$id_orden;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
