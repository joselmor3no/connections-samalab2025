<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/Empresas/model/Usuarios.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Estudios {

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

    function getEstudios() {

        $sql = "SELECT * 
            FROM cat_estudio";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEstudio($id) {

        $sql = "SELECT * 
            FROM cat_estudio
            WHERE id = '$id' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addEstudio($data) {

        $sql = "INSERT INTO `cat_estudio`(no_estudio, `nombre_estudio`, alias, tipo, id_departamento, id_secciones, id_materia_biologica, id_recipiente_biologico,"
                . "muestras, etiquetas, sat, resultado_componente, resultado_texto, orden_impresion, id_tipo_reporte, fur, fud) "
                . "VALUES ('" . $data["no_estudio"] . "', '" . $data["nombre_estudio"] . "', '" . $data["alias"] . "', '" . $data["tipo"] . "', '" . $data["id_departamento"] . "', '" . $data["id_secciones"] . "', '" . $data["id_materia_biologica"] . "', '" . $data["id_recipiente_biologico"] . "', "
                . "'" . $data["muestras"] . "', '" . $data["etiquetas"] . "', '" . $data["sat"] . "', '" . $data["resultado_componente"] . "', '" . $data["resultado_texto"] . "', '" . $data["orden_impresion"] . "', '" . $data["id_tipo_reporte"] . "', '" . $data["fur"] . "', '" . $data["fud"] . "')";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO USUARIO ADMIN" . str_replace("'", "", $sql),
            "tabla" => "cat_estudio",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editEstudio($data) {

        $sql = "UPDATE `cat_estudio` "
                . "SET no_estudio = '" . $data["no_estudio"] . "', nombre_estudio = '" . $data["nombre_estudio"] . "', alias = '" . $data["alias"] . "', tipo = '" . $data["tipo"] . "', "
                . "id_departamento = '" . $data["id_departamento"] . "', id_secciones = '" . $data["id_secciones"] . "', id_materia_biologica = '" . $data["id_materia_biologica"] . "', id_recipiente_biologico = '" . $data["id_recipiente_biologico"] . "', "
                . "muestras = '" . $data["muestras"] . "',  etiquetas = '" . $data["etiquetas"] . "',  sat = '" . $data["sat"] . "',  resultado_componente = '" . $data["resultado_componente"] . "', resultado_texto = '" . $data["resultado_texto"] . "', "
                . "orden_impresion = '" . $data["orden_impresion"] . "', id_tipo_reporte = '" . $data["id_tipo_reporte"] . "',  fur = '" . $data["fur"] . "',  fud = '" . $data["fud"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE ESTUDIO ADMIN: " . str_replace("'", "", $sql),
            "tabla" => "cat_estudio",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function aliasEstudio($alias) {
        $sql = "SELECT alias 
            FROM cat_estudio
            WHERE alias = '$alias'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
