<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/Empresas/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Productos {

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

    function getProductos($id_cliente) {

        $sql = "SELECT productos.*, FORMAT(productos.cantidad/productos.cant_pruebas,0) AS cantidad_utilizar, cat_presentacion_producto.nombre AS presenacion, cat_unidades_producto.nombre AS unidad, cat_departamentos_producto.nombre AS departamento   
        FROM productos
        LEFT JOIN cat_presentacion_producto ON (cat_presentacion_producto.id = productos.id_presentacion_producto)
        LEFT JOIN cat_unidades_producto ON (cat_unidades_producto.id = productos.id_unidad_producto)
        LEFT JOIN cat_departamentos_producto ON (cat_departamentos_producto.id = productos.id_departamento_producto)
        WHERE productos.id_cliente = '$id_cliente'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getProducto($id) {

        $sql = "SELECT *
        FROM productos
        WHERE id = '$id'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getPresentacion() {

        $sql = "SELECT *
        FROM cat_presentacion_producto";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getUnidades() {

        $sql = "SELECT *
        FROM cat_unidades_producto";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getDepartamentos() {

        $sql = "SELECT *
        FROM cat_departamentos_producto";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addProducto($data) {

        $sql = "INSERT INTO productos (consecutivo, nombre, id_presentacion_producto, cantidad, id_unidad_producto, stock_min, cant_pruebas, id_departamento_producto, id_cliente) "
                . "SELECT COALESCE(MAX(consecutivo), 0)  + 1, '" . $data["nombre"] . "', '" . $data["id_presentacion_producto"] . "', '" . $data["cantidad"] . "', '" . $data["id_unidad_producto"] . "', '" . $data["stock_min"] . "', '" . $data["cant_pruebas"] . "',  '" . $data["id_departamento_producto"] . "', '" . $data["id_cliente"] . "' "
                . "FROM productos WHERE id_cliente = '" . $data["id_cliente"] . "'";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO PRODUCTO: " . str_replace("'", "", $sql),
            "tabla" => "productos",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editProducto($data) {

        $sql = "UPDATE productos SET "
                . "nombre = '" . $data["nombre"] . "', id_presentacion_producto = '" . $data["id_presentacion_producto"] . "', cantidad = '" . $data["cantidad"] . "', id_unidad_producto = '" . $data["id_unidad_producto"] . "' , "
                . "stock_min = '" . $data["stock_min"] . "', cant_pruebas = '" . $data["cant_pruebas"] . "', id_departamento_producto = '" . $data["id_departamento_producto"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE PRODUCTO: " . str_replace("'", "", $sql),
            "tabla" => "productos",
            "id_tabla" => $data["id_cliente"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getCombosProductos($id_cliente) {

        $sql = "SELECT cat_estudio.id, cat_estudio.nombre_estudio AS estudio
        FROM combos_estudio
        LEFT JOIN cat_estudio ON (cat_estudio.id = combos_estudio.id_estudio)
        WHERE id_cliente = '$id_cliente'
        GROUP BY cat_estudio.id";
        $datos = $this->conexion->getQuery($sql);

        foreach ($datos as $row) {

            $id_estudio = $row->id;
            $estudio = $row->estudio;

            $sql = "SELECT combos_estudio.*, productos.nombre AS producto,  FORMAT(productos.cantidad/productos.cant_pruebas,0) AS cantidad_utilizar, cat_presentacion_producto.nombre AS presenacion, cat_unidades_producto.nombre AS unidad
            FROM combos_estudio
            LEFT JOIN productos ON (productos.id = combos_estudio.id_producto)
             LEFT JOIN cat_presentacion_producto ON (cat_presentacion_producto.id = productos.id_presentacion_producto)
            LEFT JOIN cat_unidades_producto ON (cat_unidades_producto.id = productos.id_unidad_producto)
            WHERE id_estudio = '$id_estudio'
            ";
            $productos = $this->conexion->getQuery($sql);

            $data[] = array(
                "id" => $id_estudio,
                "estudio" => $estudio,
                "productos" => $productos
            );
        }
        return $data;
    }

    function deleteCombo($id) {

        $sql = "DELETE  
            FROM combos_estudio WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        $data = array(
            "observaciones" => "ELIMINACION DE PRODUCTO COMBO: " . str_replace("'", "", $sql),
            "tabla" => "combos_estudio",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);

        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addCombo($data) {

        $sql = "SELECT *
        FROM combos_estudio
        WHERE id_estudio = '" . $data["id_estudio"] . "' AND id_cliente = '" . $data["id_cliente"] . "'";

        $existe = $this->conexion->getQuery($sql);

        if (count($existe) == 0) {

            for ($i = 0; $i < count($data["id_producto"]); $i++) {
                $sql = "INSERT INTO combos_estudio (cantidad, id_estudio, id_producto, id_cliente) "
                        . "VALUES('" . $data["cantidad"][$i] . "', '" . $data["id_estudio"] . "', '" . $data["id_producto"][$i] . "', '" . $data["id_cliente"] . "')";
                $this->conexion->setQuery($sql);
            }
            //log_activity
            $data = array(
                "observaciones" => "NUEVO COMBO: " . str_replace("'", "", $sql),
                "tabla" => "combos_estudio",
                "id_tabla" => 0,
                "usuario" => $_SESSION["usuario"]);
            $catalogos = new Catalogos();
            $catalogos->logActivity($data);
        } else {
            /* for ($i = 0; $i < count($data["id_producto"]); $i++) {
              $sql = "UPDATE combos_estudio SET "
              . "cantidad = '" . $data["cantidad"][$i] . "' "
              . "WHERE id = " . $existe[0]->id;

              $this->conexion->setQuery($sql);
              }
              //log_activity
              $data = array(
              "observaciones" => "EDICION DE PRODUCTO EN COMBO: " . str_replace("'", "", $sql),
              "tabla" => "combos_estudio",
              "id_tabla" => $existe[0]->id,
              "usuario" => $_SESSION["usuario"]);
              $catalogos = new Catalogos();
              $catalogos->logActivity($data); */
        }
    }

    function addComboProducto($data) {

        $sql = "SELECT *
        FROM combos_estudio
        WHERE id_producto = '" . $data["id_producto"] . "' AND id_estudio = '" . $data["id_estudio"] . "' AND id_cliente = '" . $data["id_cliente"] . "'";

        $existe = $this->conexion->getQuery($sql);

        if (count($existe) == 0) {

            $sql = "INSERT INTO combos_estudio (cantidad, id_estudio, id_producto, id_cliente) "
                    . "VALUES('" . $data["cantidad"] . "', '" . $data["id_estudio"] . "', '" . $data["id_producto"] . "', '" . $data["id_cliente"] . "')";

            $this->conexion->setQuery($sql);

            //log_activity
            $data = array(
                "observaciones" => "NUEVO PRODUCTO EN COMBO: " . str_replace("'", "", $sql),
                "tabla" => "combos_estudio",
                "id_tabla" => 0,
                "usuario" => $_SESSION["usuario"]);
            $catalogos = new Catalogos();
            $catalogos->logActivity($data);
        } else {

            $sql = "UPDATE combos_estudio SET "
                    . "cantidad = '" . $data["cantidad"] . "' "
                    . "WHERE id = " . $existe[0]->id;

            $this->conexion->setQuery($sql);

            //log_activity
            $data = array(
                "observaciones" => "EDICION DE PRODUCTO EN COMBO: " . str_replace("'", "", $sql),
                "tabla" => "combos_estudio",
                "id_tabla" => $existe[0]->id,
                "usuario" => $_SESSION["usuario"]);
            $catalogos = new Catalogos();
            $catalogos->logActivity($data);
        }
    }

    function addPresentacionProducto($data) {



        $sql = "INSERT INTO cat_presentacion_producto (nombre) "
                . "VALUES('" . $data["nombre"] . "')";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA PRESENTACION DE PRODUCTO: " . str_replace("'", "", $sql),
            "tabla" => "cat_presentacion_producto",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addUnidadProducto($data) {

        $sql = "INSERT INTO cat_unidades_producto (nombre) "
                . "VALUES('" . $data["nombre"] . "')";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA UNIDAD DE PRODUCTO: " . str_replace("'", "", $sql),
            "tabla" => "cat_unidades_producto",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getEstudiosCombo() {

        $sql = "SELECT *
        FROM cat_estudio
        LEFT JOIN combos_estudio ON (cat_estudio.id = combos_estudio.id_estudio)
        WHERE combos_estudio.id IS NULL 
        GROUP BY cat_estudio.id";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
