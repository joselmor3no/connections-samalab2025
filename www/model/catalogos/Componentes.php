<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Componentes {

    private $conexion;

    function __construct() {

        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso) {
            header("Location: /");
        }

        $this->conexion = new Conexion();
    }

    function addComponente($data) {

        $sql = "INSERT INTO `componente`( `componente`, `alias`, `capturable`, `imprimible`, `linea`, `observaciones`, id_estudio, unidad, referencia, "
                . "leyenda, `total_absoluto`, `absoluto`, `id_cat_componente`, id_sucursal, orden) "
                . "SELECT '" . $data["componente"] . "', '" . $data["alias"] . "', " . $data["capturable"] . ", " . $data["imprimible"] . ", " . $data["linea"] . ", " . $data["observaciones"] . ", " . $data["id_cat_estudio"] . ", '" . $data["unidad"] . "', '" . $data["referencia"] . "', "
                . " '" . $data["leyenda"] . "', " . $data["total_absoluto"] . ", " . $data["absoluto"] . ", " . $data["id_cat_componente"] . ", " . $data["id_sucursal"] . ", MAX(orden) + 1 FROM componente WHERE id_sucursal = " . $data["id_sucursal"] . " AND  id_estudio = " . $data["id_cat_estudio"] . "";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA COMPONENTE: " . str_replace("'", "", $sql),
            "tabla" => "componente",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getComponentes($id_estudio, $id_sucursal) {

        $sql = "SELECT componente.*, cat_componente.tipo_componente 
            FROM componente
            LEFT JOIN cat_componente ON (cat_componente.id = componente.id_cat_componente)
            WHERE id_sucursal = '" . $id_sucursal . "' AND id_estudio = '" . $id_estudio . "'  AND activo = '1'
            ORDER BY componente.orden";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function editComponente($data) {

        $sql = "UPDATE `componente`"
                . "SET componente =  '" . $data["componente"] . "', alias = '" . $data["alias"] . "', leyenda =  '" . $data["leyenda"] . "' , capturable = " . $data["capturable"] . ", imprimible = " . $data["imprimible"] . ", linea = " . $data["linea"] . ", observaciones = " . $data["observaciones"] . ", "
                . "total_absoluto = " . $data["total_absoluto"] . ", absoluto = " . $data["absoluto"] . ", id_cat_componente = " . $data["id_cat_componente"] . ", unidad = '" . $data["unidad"] . "', referencia = '" . $data["referencia"] . "' "
                . "WHERE id = " . $data["id"];
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE COMPONENTE: " . str_replace("'", "", $sql),
            "tabla" => "componente",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function deleteComponente($id) {

        $sql = "UPDATE componente
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE COMPONENTE: " . str_replace("'", "", $sql),
            "tabla" => "componente",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function positionComponets($data) {
        $componets = $data["components"];

        for ($i = 1; $i <= count($componets); $i++) {
            $sql = "UPDATE componente
            SET orden = $i
            WHERE alias = '" . $componets[$i - 1] . "' AND  id_sucursal = '" . $data["id_sucursal"] . "' AND id_estudio = '" . $data["id_estudio"] . "'  AND activo = '1'";
            $this->conexion->setQuery($sql);
        }

        //log_activity
        $data = array(
            "observaciones" => "ORDENAMIENTO DE COMPONENTES: " . str_replace("'", "", $sql),
            "tabla" => "componente",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function aliasComponente($alias, $id_estudio, $id_sucursal) {
        $sql = "SELECT alias
        FROM componente
        WHERE alias = '$alias' AND id_sucursal = $id_sucursal AND id_estudio = $id_estudio  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getComponente($id) {

        $sql = "SELECT *
            FROM componente
            WHERE componente.id = " . $id;

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getComponentesNumericas($id_componente) {

        $sql = "SELECT *
            FROM componente_numerico
            WHERE id_componente = '" . $id_componente . "' AND activo = '1'"
                . "ORDER BY referencia, tipo_edad, edad_inicio";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getComponentNumerico($id) {

        $sql = "SELECT *
            FROM componente_numerico
            WHERE id = '" . $id . "'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addComponenteNumerico($data) {

        $sql = "INSERT INTO `componente_numerico`( `referencia`, `edad_inicio`, `edad_fin`, `valores_decimales`, `valores_unidades`, "
                . "`alta_aceptable`, `bajo_aceptable`, `alta`, `baja`, `id_componente`, tipo_edad) "
                . "VALUES ('" . $data["referencia"] . "', '" . $data["edad_inicio"] . "', " . $data["edad_fin"] . ", '" . $data["valores_decimales"] . "', '" . $data["valores_unidades"] . "', "
                . "" . $data["alta_aceptable"] . ", " . $data["bajo_aceptable"] . ", " . $data["alta"] . ", " . $data["baja"] . ", " . $data["id_componente"] . ", '" . $data["tipo_edad"] . "')";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA COMPONENTE NUMERICA: " . str_replace("'", "", $sql),
            "tabla" => "componente_numerico",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editComponenteNumerico($data) {

        $sql = "UPDATE `componente_numerico` "
                . "SET referencia = '" . $data["referencia"] . "', edad_inicio = '" . $data["edad_inicio"] . "', edad_fin = " . $data["edad_fin"] . ", valores_decimales = '" . $data["valores_decimales"] . "', valores_unidades = '" . $data["valores_unidades"] . "', "
                . "alta_aceptable = " . $data["alta_aceptable"] . ", bajo_aceptable = " . $data["bajo_aceptable"] . ", alta = " . $data["alta"] . ", baja = " . $data["baja"] . ", tipo_edad = '" . $data["tipo_edad"] . "'"
                . "WHERE id = " . $data["id"] . "";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE COMPONENTE NUMERICA: " . str_replace("'", "", $sql),
            "tabla" => "componente_numerico",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function deleteComponenteNumerico($id) {

        $sql = "UPDATE componente_numerico
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE COMPONENTE NUMERICA: " . str_replace("'", "", $sql),
            "tabla" => "componente_numerico",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getComponentLista($id_componente) {

        $sql = "SELECT *
            FROM componente_lista
            WHERE id_componente = '" . $id_componente . "' AND activo = '1'"
                . "ORDER BY predeterminado DESC, elemento";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addComponenteLista($data) {

        $sql = "INSERT INTO `componente_lista`(elemento, predeterminado,  `id_componente`) "
                . "VALUES ('" . $data["elemento"] . "', '" . $data["predeterminado"] . "', '" . $data["id_componente"] . "')";
        $this->conexion->setQuery($sql);

        $data = array(
            "observaciones" => "NUEVA COMPONENTE DE LISTA: " . str_replace("'", "", $sql),
            "tabla" => "componente_lista",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function deleteComponenteLista($id) {

        $sql = "UPDATE componente_lista
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        $data = array(
            "observaciones" => "ELIMINACION COMPONENTE DE LISTA: " . str_replace("'", "", $sql),
            "tabla" => "componente_lista",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getComponenteFormula($id_componente) {

        $sql = "SELECT *
            FROM componente_formula
            WHERE id_componente = '" . $id_componente . "'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addComponenteFormula($formula, $id_componente) {

        $sql = "SELECT *
            FROM componente_formula
            WHERE id_componente = '" . $id_componente . "'";

        $data = $this->conexion->getQuery($sql);

        if (count($data) == 0) {
            $sql = "INSERT INTO `componente_formula`(formula,  `id_componente`) "
                    . "VALUES ('$formula', '" . $id_componente . "')";
            $this->conexion->setQuery($sql);
        } else {
            $sql = "UPDATE componente_formula
            SET formula = '$formula'
            WHERE id_componente = " . $id_componente;
            $this->conexion->setQuery($sql);
        }
    }

    function componentsTabla($id_componente) {

        $sql = "SELECT *
            FROM componente_tabla
            WHERE id_componente = '" . $id_componente . "' AND activo = '1'"
                . "ORDER BY sexo";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteComponenteTabla($id) {

        $sql = "UPDATE componente_tabla
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE TABLA: " . str_replace("'", "", $sql),
            "tabla" => "componente_tabla",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getComponentTabla($id_componente) {

        $sql = "SELECT *
            FROM componente_tabla
            WHERE id = '" . $id_componente . "'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addComponenteTabla($data) {

        $sql = "INSERT INTO `componente_tabla`(sexo, valor,  `id_componente`) "
                . "VALUES ('" . $data["sexo"] . "', '" . $data["valor"] . "', '" . $data["id_componente"] . "')";
        $this->conexion->setQuery($sql);

        $data = array(
            "observaciones" => "NUEVA DE TABLA: " . str_replace("'", "", $sql),
            "tabla" => "componente_tabla",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editComponenteTabla($data) {

        $sql = "UPDATE `componente_tabla` "
                . "SET sexo = '" . $data["sexo"] . "', valor = '" . $data["valor"] . "' "
                . "WHERE id = " . $data["id"] . "";
        $this->conexion->setQuery($sql);

        $data = array(
            "observaciones" => "EDICION DE TABLA: " . str_replace("'", "", $sql),
            "tabla" => "componente_tabla",
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
