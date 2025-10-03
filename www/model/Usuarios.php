<?php

require_once('Conexion.php');

class Usuarios {

    private $conexion;

    function __construct() {
        $this->conexion = new Conexion();
    }

    function getUsuario($user, $pass) {

        /*$sql = "SELECT usuario.*,sucursal.id_cliente,usuario.acceso_sucursales,
            sucursal.nombre as nombre_sucursal   
            FROM usuario
            INNER JOIN sucursal ON (sucursal.id = usuario.id_sucursal)
            INNER JOIN cliente ON (cliente.id = sucursal.id_cliente)
            WHERE usuario.usuario = '$user' AND usuario.contraseña = '$pass' AND ((NOW() >= TIME(usuario.entrada_trabajo)   AND NOW() <= TIME(usuario.salida_trabajo)) OR usuario.id_tipo_empleado = 1) AND usuario.activo = 1 AND cliente.inactivo = 0";
        */
        $sql = "SELECT usuario.*,
               sucursal.id_cliente,
               usuario.acceso_sucursales,
               sucursal.nombre AS nombre_sucursal   
            FROM admin_samalab2025.usuario
            INNER JOIN admin_samalab2025.sucursal ON sucursal.id = usuario.id_sucursal
            INNER JOIN admin_samalab2025.cliente  ON cliente.id = sucursal.id_cliente
            WHERE usuario.usuario = :usuario  
            AND usuario.\"contraseña\" = :contrasena 
            AND (
                    (CURRENT_TIME >= usuario.entrada_trabajo 
                    AND CURRENT_TIME <= usuario.salida_trabajo) 
                    OR usuario.id_tipo_empleado = 1
                )
            AND usuario.activo = 1 
            AND cliente.inactivo = 0";
echo $sql;
        $params = [
            ":usuario"   => $user,
            ":contrasena"=> $pass
        ];

        $data = $this->conexion->getQuery($sql, $params);
        echo '----';
        print_r( $data);
        echo '----';
        return $data;
    }

    function listaSucursalesAcceso($listaSucursales){
        $lista=explode(",",$listaSucursales);
        
        $opciones="";
        for($i=0;$i<count($lista);$i++){
            $seleccionado="";
            if($lista[$i]==$_SESSION['id_sucursal'])
                $seleccionado="selected";
            $info=$this->informacionSucursal($lista[$i])[0];
            $opciones.='<option value="'.$lista[$i].'" '.$seleccionado.'>'.$info->nombre.'</option>';
        }
        return $opciones;
    }

    function informacionSucursal($idSucursal){
        $sql = "SELECT id,nombre from sucursal where id=".$idSucursal;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPermisos($id_usuario) {

        $sql = "SELECT permisos_usuario.*, cat_permisos.siglas  
            FROM permisos_usuario
            INNER JOIN cat_permisos ON (permisos_usuario.id_cat_permiso = cat_permisos.id)
            WHERE permisos_usuario.id_usuario = '$id_usuario'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function setSesionActiva($id_sesion_activa, $id_usuario) {

        $sql = "UPDATE usuario SET sesion_activa = '" . $id_sesion_activa . "' WHERE id = $id_usuario";
        $this->conexion->setQuery($sql);
    }

    function setSesionInactiva($id_sesion_activa) {

        $sql = "UPDATE usuario SET sesion_activa = 0 WHERE sesion_activa = '" . $id_sesion_activa . "'";
        $this->conexion->setQuery($sql);
    }

    function getUsuarioSesion($id_usuario) {

        $sql = "SELECT sesion_activa FROM usuario WHERE id = '" . $id_usuario . "'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function validarSession() {
        session_start();

        $session_activa = 0;

        //Proceso de validacion y permisos pendiente
        //se le permite al paciente para sus reportes
        $id_usuario = $_SESSION["id"] != "" ? $_SESSION["id"] : $_SESSION["id_paciente"];
        if ($id_usuario > 0) {
            $session_activa = 1;
        }

        return $session_activa;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
