<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Paquetes {

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

    function getPaquetes($id_sucursal) {

        $sql = "SELECT * 
            FROM paquete
            WHERE id_sucursal = '$id_sucursal' AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPaquete($id) {

        $sql = "SELECT * 
            FROM paquete
            WHERE  id = '$id' AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPaqueteAlias($alias, $id_sucursal) {

        $sql = "SELECT * 
            FROM paquete
            WHERE alias = '$alias' AND id_sucursal = '$id_sucursal' AND activo = 1";

        $data = $this->conexion->getQuery($sql);

        if (count($data) == 0) {
            return "NULL";
        } else {
            return $data[0]->id;
        }
    }

    function getEstudiosPaquete($id, $id_sucursal) {

        $sql = "SELECT paquete_estudio.*, cat_estudio.alias,cat_estudio.no_estudio, cat_estudio.nombre_estudio, estudio.precio_publico 
            FROM paquete_estudio
            INNER JOIN cat_estudio ON (cat_estudio.id = paquete_estudio.id_estudio)
            INNER JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio AND estudio.id_sucursal = $id_sucursal )
            WHERE  paquete_estudio.id_paquete = '$id' 
            ORDER BY paquete_estudio.posicion";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function aliasPaquete($alias, $id_sucursal) {
        $sql = "SELECT alias 
            FROM paquete
            WHERE alias = '$alias' AND id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function consecutivoPaquete($id_sucursal) {
        $sql = "SELECT MAX(no_paquete) + 1 AS no_paquete
            FROM paquete
            WHERE id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data[0]->no_paquete == "" ? "1" : $data[0]->no_paquete ;
    }

    function deletePaquete($id) {

        $sql = "UPDATE paquete
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        $data = array(
            "observaciones" => "ELIMINACION DE PAQUETE: " . str_replace("'", "", $sql),
            "tabla" => "paquete",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);

        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addPaquete($data) {
        $catalogos = new Catalogos();

        $sql = "INSERT INTO `paquete`(`alias`, `nombre`, no_paquete, `id_tipo_reporte`, "
                . "`metodo`, `id_sucursal`, precio )"
                . "VALUES ('" . $data["alias"] . "', '" . $data["nombre"] . "', " . $this->consecutivoPaquete($data["id_sucursal"]) . "," . $data["id_tipo_reporte"] . ", "
                . "'" . $data["metodo"] . "', " . $data["id_sucursal"] . ", " . $data["precio"] . ")";

        $this->conexion->setQuery($sql);

        $id = $catalogos->maxTableActivo("paquete", $data["id_sucursal"]);

        for ($i = 0; $i < count($data["codigo"]); $i++) {
            $precio_neto = str_replace(",", "", $data["precio_neto"][$i]);
            $sql = "INSERT INTO `paquete_estudio`(`id_paquete`, `id_estudio`, `precio_neto`, `posicion`) 
                    SELECT " . $id . ", id , " . $precio_neto . ", " . ($i + 1) . " FROM cat_estudio WHERE alias ='" . $data["codigo"][$i] . "'";
            $this->conexion->setQuery($sql);
        }

        //log_activity
        $data = array(
            "observaciones" => "NUEVO PAQUETE: " . str_replace("'", "", $sql),
            "tabla" => "paquete",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos->logActivity($data);
    }

    function editPaquete($data) {

        $sql = "UPDATE `paquete` "
                . "SET alias = '" . $data["alias"] . "', nombre = '" . $data["nombre"] . "', id_tipo_reporte = " . $data["id_tipo_reporte"] . ", "
                . "metodo = '" . $data["metodo"] . "', precio = " . $data["precio"] . " "
                . "WHERE id = " . $data["id"];
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM paquete_estudio 
        WHERE id_paquete = " . $data["id"];
        $this->conexion->setQuery($sql);

        for ($i = 0; $i < count($data["codigo"]); $i++) {
            $precio_neto = str_replace(",", "", $data["precio_neto"][$i]);
            $sql = "INSERT INTO `paquete_estudio`(`id_paquete`, `id_estudio`, `precio_neto`, `posicion`) 
                    SELECT " . $data["id"] . ", id , " . $precio_neto . ", " . ($i + 1) . " FROM cat_estudio WHERE alias ='" . $data["codigo"][$i] . "'";
            $this->conexion->setQuery($sql);
        }

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE PAQUETE: " . str_replace("'", "", $sql),
            "tabla" => "paquete",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function close() {

        $this->conexion->close();
    }

}

?>
