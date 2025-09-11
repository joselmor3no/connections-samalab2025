<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/Empresas/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Formatos {

    private $conexion;

    function __construct() {
        //Validación de session
        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso) {
            header("Location: /Empresas");
        }

        $this->conexion = new Conexion();
    }

    function getFormatoLab($id_cliente) {

        $sql = "SELECT * 
            FROM formato_lab
            WHERE id_cliente = $id_cliente";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addLabortorio($data) {

        $sql = "INSERT INTO formato_lab (posicion_logo, fuente, punto, tipo, head, footer, "
                . "color_linea, separador, color, asterisco, horafecha, id_cliente) "
                . "VALUES( '" . $data["posicion_logo"] . "', '" . $data["fuente"] . "', '" . $data["punto"] . "', '" . $data["tipo"] . "', '" . $data["head"] . "', '" . $data["footer"] . "', "
                . " '" . $data["color_linea"] . "', '" . $data["separador"] . "', '" . $data["color"] . "', '" . $data["asterisco"] . "', '" . $data["horafecha"] . "', '" . $data["id_cliente"] . "')";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO DE FORMATO LABORATORIO: " . str_replace("'", "", $sql),
            "tabla" => "formato_lab",
            "id_tabla" => $data["id_cliente"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editLabortorio($data) {

        $sql = "UPDATE formato_lab "
                . "SET posicion_logo = '" . $data["posicion_logo"] . "', fuente = '" . $data["fuente"] . "', punto = '" . $data["punto"] . "', tipo = '" . $data["tipo"] . "', head = '" . $data["head"] . "', "
                . "footer = '" . $data["footer"] . "', color_linea = '" . $data["color_linea"] . "', separador = '" . $data["separador"] . "', color = '" . $data["color"] . "', asterisco = '" . $data["asterisco"] . "', "
                . "horafecha = '" . $data["horafecha"] . "' "
                . "WHERE id_cliente = " . $data["id_cliente"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE FORMATO LABORATORIO: " . str_replace("'", "", $sql),
            "tabla" => "formato_lab",
            "id_tabla" => $data["id_cliente"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getFormatoRecibo($id_cliente) {

        $sql = "SELECT * 
            FROM ticket_detalle
            WHERE id_cliente = $id_cliente";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addRecibo($data) {

        $sql = "INSERT INTO ticket_detalle (`logotipo_posicion`, `logotipo_tamano`, `campo_fuente`, `campo_tipo`, campo_tamano, `alineacion1`, `nombre_clinica1`, `sucursal1`, `orden1`, `fecha1`, `no_paciente1`, `nombre_paciente1`,  "
                . "`domicilio1`,`domicilio2`, `medico1`, `empresa1`, `edad1`, `tipo_edad1`, `sexo1`, `fecha_entrega1`, `dir_hospital1`, `telefono_g1`, `telefono2_g1`, `web1`, `descripcion_g1`, `informacion1`, `orden_interna`, id_cliente) "
                . "VALUES( '" . $data["logotipo_posicion"] . "', '" . $data["logotipo_tamano"] . "', '" . $data["campo_fuente"] . "', '" . $data["campo_tipo"] . "', '" . $data["campo_tamano"] . "', '" . $data["alineacion1"] . "', '" . $data["nombre_clinica1"] . "', '" . $data["sucursal1"] . "', '" . $data["orden1"] . "', '" . $data["fecha1"] . "', '" . $data["no_paciente1"] . "', '" . $data["nombre_paciente1"] . "', "
                . " '" . $data["domicilio1"] . "', '" . $data["domicilio2"] . "', '" . $data["medico1"] . "', '" . $data["empresa1"] . "', '" . $data["edad1"] . "', '" . $data["tipo_edad1"] . "', '" . $data["sexo1"] . "', '" . $data["fecha_entrega1"] . "', '" . $data["dir_hospital1"] . "', '" . $data["telefono_g1"] . "', '" . $data["telefono2_g1"] . "', '" . $data["web1"] . "', '" . $data["descripcion_g1"] . "', '" . $data["informacion1"] . "', '" . $data["orden_interna"] . "', '" . $data["id_cliente"] . "')";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO DE FORMATO LABORATORIO: " . str_replace("'", "", $sql),
            "tabla" => "ticket_detalle",
            "id_tabla" => $data["id_cliente"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editRecibo($data) {
        $sql = "UPDATE `ticket_detalle` SET 
                    `logotipo_posicion` =  '" . $data["logotipo_posicion"] . "',
                    `logotipo_tamano`  =  '" . $data["logotipo_tamano"] . "',
                    `campo_fuente` =  '" . $data["campo_fuente"] . "',
                    `campo_tipo` =  '" . $data["campo_tipo"] . "',
                    `campo_tamano` =  '" . $data["campo_tamano"] . "',
                    `alineacion1` =  '" . $data["alineacion1"] . "',
                    `nombre_clinica1` =  '" . $data["nombre_clinica1"] . "',
                    `sucursal1` =  '" . $data["sucursal1"] . "',
                    `orden1` =  '" . $data["orden1"] . "',
                    `fecha1` =  '" . $data["fecha1"] . "',
                    `no_paciente1` =  '" . $data["no_paciente1"] . "',
                    `nombre_paciente1` =  '" . $data["nombre_paciente1"] . "',
                    `domicilio1` =  '" . $data["domicilio1"] . "',
                    `domicilio2` =  '" . $data["domicilio2"] . "',
                    `medico1` =  '" . $data["medico1"] . "',
                    `empresa1` =  '" . $data["empresa1"] . "',
                    `edad1` =  '" . $data["edad1"] . "',
                    `tipo_edad1` =  '" . $data["logotipo_posicion"] . "', 
                    `sexo1` =  '" . $data["sexo1"] . "', 
                    `fecha_entrega1` =  '" . $data["fecha_entrega1"] . "',
                    `dir_hospital1` =  '" . $data["dir_hospital1"] . "',
                    `telefono_g1` =  '" . $data["telefono_g1"] . "',
                    `telefono2_g1` =  '" . $data["telefono2_g1"] . "',
                    `web1` =  '" . $data["web1"] . "',
                    `descripcion_g1` =  '" . $data["descripcion_g1"] . "', 
                    `informacion1` =  '" . $data["informacion1"] . "',
                    `orden_interna` =  '" . $data["orden_interna"] . "' 
                     WHERE `id_cliente` =  '" . $data["id_cliente"] . "' ";

        $this->conexion->setQuery($sql);
      
        //log_activity
        $data = array(
            "observaciones" => "NUEVO DE FORMATO LABORATORIO: " . str_replace("'", "", $sql),
            "tabla" => "ticket_detalle",
            "id_tabla" => $data["id_cliente"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function close() {

        $this->conexion->close();
    }

}

?>
