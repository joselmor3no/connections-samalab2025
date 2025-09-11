<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/catalogos/Paquetes.php');

class Listas {

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

    function getListas($id_sucursal) {

        $sql = "SELECT * 
            FROM lista_precios
            WHERE id_sucursal = '$id_sucursal'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function aliasLista($alias, $id_sucursal) {
        $sql = "SELECT alias 
            FROM lista_precios
            WHERE alias = '$alias' AND id_sucursal = $id_sucursal";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function noLista($no, $id_sucursal) {
        $sql = "SELECT * 
            FROM lista_precios
            WHERE no = '$no' AND id_sucursal = $id_sucursal";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function consecutivoLista($id_sucursal) {
        $sql = "SELECT MAX(CAST(no AS UNSIGNED)) + 1 AS no
        FROM lista_precios
        WHERE id_sucursal = $id_sucursal ";

        $data = $this->conexion->getQuery($sql);
        return $data[0]->no == "" ? "1" : $data[0]->no;
    }

    function estudiosPaquetesDescripcion($palabra, $id_sucursal) {


        $sql = "SELECT * FROM (
                SELECT id, no_paquete AS codigo, nombre, alias, 'paquete' AS tipo, precio FROM paquete WHERE id_sucursal = '121' AND activo = 1
                UNION
                SELECT cat_estudio.id, cat_estudio.no_estudio, cat_estudio.nombre_estudio, cat_estudio.alias ,'estudio', estudio.precio_publico FROM estudio
                INNER JOIN cat_estudio ON (estudio.id_cat_estudio = cat_estudio.id)
                WHERE estudio.id_sucursal = '121' AND estudio.activo = 1
            ) AS consulta
            WHERE alias = '$palabra' OR nombre LIKE '%$palabra%' OR codigo = '$palabra' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getLista($id) {
        $sql = "SELECT *
        FROM lista_precios
        WHERE id = '$id' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEstudiosPaquetesLista($id, $id_sucursal) {

        $sql = "SELECT lista_precios_estudio.id,
                    CASE WHEN lista_precios_estudio.id_estudio IS NOT NULL THEN 'Estudio'
                        ELSE 'Paquete' END AS tipo,
                    CASE WHEN lista_precios_estudio.id_estudio IS NOT NULL THEN cat_estudio.alias
                        ELSE paquete.alias END AS codigo, 
                    CASE WHEN lista_precios_estudio.id_estudio IS NOT NULL THEN cat_estudio.nombre_estudio
                        ELSE paquete.nombre END AS descripcion,
                lista_precios_estudio.precio_publico, lista_precios_estudio.precio_neto
                FROM `lista_precios` 
                INNER JOIN lista_precios_estudio ON (lista_precios.id = lista_precios_estudio.id_lista_precio) 
                LEFT JOIN cat_estudio ON (lista_precios_estudio.id_estudio = cat_estudio.id) 
                LEFT JOIN paquete ON (lista_precios_estudio.id_paquete = paquete.id) 
                WHERE lista_precios.id = '$id' AND lista_precios.id_sucursal = '$id_sucursal' AND lista_precios_estudio.activo = 1";


        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteLista($id) {

        //Falta: No borra xq esta ligada a empresas preguntar a eder

        $sql = "DELETE FROM lista_precios_estudio
          WHERE id_lista_precio = " . $id;
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM lista_precios
                WHERE id = " . $id;
        $this->conexion->setQuery($sql);

        $data = array(
            "observaciones" => "ELIMINACION DE LISTA DE PRECIOS: " . str_replace("'", "", $sql),
            "tabla" => "lista_precios",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editLista($data) {
        $catalogos = new Catalogos();
        $paquetes = new Paquetes();

        $sql = "UPDATE `lista_precios` "
                . "SET alias = '" . $data["alias"] . "', nombre = '" . $data["nombre"] . "' "
                . "WHERE id = " . $data["id"];
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM lista_precios_estudio
          WHERE id_lista_precio = " . $data["id"];
        $this->conexion->setQuery($sql);

        for ($i = 0; $i < count($data["codigo"]); $i++) {
            $paquete = $data["paquete"][$i];
            if ($paquete != "") {
                $id_paquete = $paquetes->getPaqueteAlias($data["paquete"][$i], $data["id_sucursal"]);
            } else {
                $id_paquete = "NULL";
            }

            $precio_publico = str_replace(",", "", $data["precio_publico"][$i]);
            $precio_neto = str_replace(",", "", $data["precio_neto"][$i]);

            $sql = "INSERT INTO `lista_precios_estudio`(`id_lista_precio`, `id_estudio`, `precio_publico`, `precio_neto`, id_paquete, id_sucursal)
                    SELECT " . $data["id"] . ", " . ($paquete == '' ? "id" : "NULL") . " , " . $precio_publico . ", " . $precio_neto . ", "
                    . "" . $id_paquete . "," . $data["id_sucursal"] . " FROM cat_estudio WHERE alias ='" . $data["codigo"][$i] . "'" . ($paquete != '' ? " OR 1 LIMIT 1" : "");

            $this->conexion->setQuery($sql);
        }

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE LISTA: " . str_replace("'", "", $sql),
            "tabla" => "lista_precios",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos->logActivity($data);
    }

    function addLista($data) {
        $catalogos = new Catalogos();
        $paquetes = new Paquetes();

        $sql = "INSERT INTO `lista_precios`(`alias`, `nombre`, no,  `id_sucursal` )"
                . "VALUES ('" . $data["alias"] . "', '" . $data["nombre"] . "', " . $this->consecutivoLista($data["id_sucursal"]) . ", " . $data["id_sucursal"] . ")";
        $this->conexion->setQuery($sql);

        $id = $catalogos->maxTable("lista_precios", $data["id_sucursal"]);
        for ($i = 0; $i < count($data["codigo"]); $i++) {
            $paquete = $data["paquete"][$i];
            if ($paquete != "") {
                $id_paquete = $paquetes->getPaqueteAlias($data["paquete"][$i], $data["id_sucursal"]);
            } else {
                $id_paquete = "NULL";
            }

            $precio_publico = str_replace(",", "", $data["precio_publico"][$i]);
            $precio_neto = str_replace(",", "", $data["precio_neto"][$i]);

            $sql = "INSERT INTO `lista_precios_estudio`(`id_lista_precio`, `id_estudio`, `precio_publico`, `precio_neto`, id_paquete, id_sucursal)
                    SELECT " . $id . ", " . ($paquete == '' ? "id" : "NULL") . " , " . $precio_publico . ", " . $precio_neto . ", "
                    . "" . $id_paquete . "," . $data["id_sucursal"] . " FROM cat_estudio WHERE alias ='" . $data["codigo"][$i] . "'" . ($paquete != '' ? " OR 1 LIMIT 1" : "");

            $this->conexion->setQuery($sql);
        }
        //log_activity
        $data = array(
            "observaciones" => "NUEVA LISTA: " . str_replace("'", "", $sql),
            "tabla" => "lista_precios",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);

        $catalogos->logActivity($data);
    }

    function addEstudio($data) {

        $catalogos = new Catalogos();
        $paquetes = new Paquetes();

        $paquete = $data["paquete"];
        if ($paquete != "") {
            $id_paquete = $paquetes->getPaqueteAlias($data["paquete"], $data["id_sucursal"]);
        } else {
            $id_paquete = "NULL";
        }

        $precio_publico = str_replace(",", "", $data["precio_publico"]);
        $precio_neto = str_replace(",", "", $data["precio_neto"]);

        $sql = "INSERT INTO `lista_precios_estudio`(`id_lista_precio`, `id_estudio`, `precio_publico`, `precio_neto`, id_paquete, id_sucursal)
                    SELECT " . $data["id"] . ", " . ($paquete == '' ? "id" : "NULL") . " , " . $precio_publico . ", " . $precio_neto . ", "
                . "" . $id_paquete . "," . $data["id_sucursal"] . " FROM cat_estudio WHERE alias ='" . $data["codigo"] . "'" . ($paquete != '' ? " OR 1 LIMIT 1" : "");

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE LISTA: " . str_replace("'", "", $sql),
            "tabla" => "lista_precios_estudio",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos->logActivity($data);

        return $data;
    }

    function deleteEstudio($data) {

        if ($data["codigo"] != "" && $data["id"] != "") {
            $catalogos = new Catalogos();

            $sql = " DELETE l FROM lista_precios_estudio l
            LEFT JOIN cat_estudio  ON (cat_estudio.id = l.id_estudio )
            WHERE cat_estudio.alias =  '" . $data["codigo"] . "' AND l.id_lista_precio = " . $data["id"];

            $this->conexion->setQuery($sql);

            //log_activity
            $data = array(
                "observaciones" => "ELIMINACION DE LISTA: " . str_replace("'", "", $sql),
                "tabla" => "lista_precios_estudio",
                "id_tabla" => $data["id"],
                "usuario" => $_SESSION["usuario"]);
            $catalogos->logActivity($data);
        }


        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
