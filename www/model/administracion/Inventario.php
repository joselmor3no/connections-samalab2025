<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Inventario {

    private $conexion;

    function __construct() {
        //Validación de session
        $this->conexion = new Conexion();
    }

    function getProveedores($id_sucursal) {

        $sql = "SELECT * FROM proveedor WHERE id_sucursal =  $id_sucursal";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getVale($tipo, $id_sucursal) {

        $sql = "SELECT COALESCE(MAX(consecutivo ), 0) + 1 AS consecutivo FROM vale_inventario WHERE movimiento = '$tipo' AND id_sucursal = " . $id_sucursal . " ORDER BY id";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function productoDescripcion($producto, $id_sucursal) {

        $sql = "SELECT productos.*, cat_presentacion_producto.nombre AS presentacion, CONCAT( productos.cantidad, ' ', cat_unidades_producto.nombre)  AS unidad,  COALESCE(inventario.existencia, 0) AS existencia,
                cat_unidades_producto.nombre AS unidad_egreso, (SELECT DATE_FORMAT(MIN(caducidad), '%d/%m/%Y') FROM vale_entrada WHERE actual > 0) AS caducidad
                FROM productos
                INNER JOIN cat_presentacion_producto ON (cat_presentacion_producto.id = productos.id_presentacion_producto)
                INNER JOIN cat_unidades_producto ON (cat_unidades_producto.id = productos.id_unidad_producto)
                INNER JOIN inventario ON (inventario.id_producto = productos.id)
                WHERE (productos.consecutivo LIKE '$producto' OR productos.nombre LIKE '%$producto%' ) AND inventario.id_sucursal =  $id_sucursal ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addValeEntrada($data) {
        if ($data["id_sucursal"] == $data["id_sucursal_salida"] || $data["id_sucursal_salida"] == "") {
            $id_sucursal = $_SESSION["id_sucursal"];
            $id_sucursal_trans = "NULL";
            $id_usuario = $_SESSION["id"];
        } else {
            $id_sucursal = $data["id_sucursal_salida"];
            $id_sucursal_trans = $data["id_sucursal"];
            $id_usuario = "NULL";
        }

        $catalogos = new Catalogos();
        $sql = "INSERT INTO vale_inventario (consecutivo, movimiento, id_proveedor, factura, observacion, id_sucursal,id_sucursal_transferencia, id_usuario, fecha)"
                . "SELECT COALESCE(MAX(consecutivo), 0)  + 1, 'Entrada', " . $data["id_proveedor"] . ", '" . $data["factura"] . "', '" . $data["observaciones"] . "', '" . $id_sucursal . "', " . $id_sucursal_trans . ", " . $id_usuario . ", NOW() FROM vale_inventario WHERE id_sucursal='" . $id_sucursal . "' AND movimiento = 'Entrada'";
        $this->conexion->setQuery($sql);

        $id_vale = $catalogos->maxTable("vale_inventario", $id_sucursal);

        //registrar el detalle del vale
        for ($i = 0; $i < count($data["codigo"]); $i++) {
            $ingreso = str_replace(",", "", $data["ingreso"][$i]);
            $precio = str_replace(",", "", $data["precio"][$i]);
            $iva = $data["iva"][$i] == "true" ? 1 : 0;
            $subtotal = str_replace(",", "", $data["subtotal"][$i]);
            $sql = "INSERT INTO vale_entrada (ingreso, actual, precio, marca, caducidad, iva, descuento, subtotal, id_producto, id_vale) 
                SELECT " . $ingreso . ", ($ingreso*cantidad),  '" . $precio . "', '" . $data["marca"][$i] . "', '" . $data["caducidad"][$i] . "', '" . $iva . "',  '" . $data["descuento"][$i] . "', '" . $subtotal . "', productos.id, $id_vale 
                FROM productos 
                INNER JOIN inventario ON (inventario.id_producto = productos.id)
                WHERE consecutivo = '" . $data["codigo"][$i] . "' AND inventario.id_sucursal =  " . $id_sucursal;

            $this->conexion->setQuery($sql);

            $sql = "UPDATE inventario
                INNER JOIN productos ON (inventario.id_producto = productos.id) 
                SET inventario.existencia = inventario.existencia +($ingreso*cantidad)
                WHERE productos.consecutivo = '" . $data["codigo"][$i] . "' AND inventario.id_sucursal = " . $id_sucursal;
            $this->conexion->setQuery($sql);

            //Calculo de costo promedio
            $sql = "UPDATE inventario
            INNER JOIN vale_entrada ON (inventario.id_producto = vale_entrada.id_producto) 
            INNER JOIN productos ON (vale_entrada.id_producto = productos.id) 
            SET inventario.precio_promedio = (
                SELECT AVG(vale_entrada.precio)
                FROM vale_entrada
                INNER JOIN productos ON (vale_entrada.id_producto = productos.id) 
                WHERE productos.consecutivo = '" . $data["codigo"][$i] . "' AND id_sucursal = " . $id_sucursal . "
            )
            WHERE productos.consecutivo = '" . $data["codigo"][$i] . "' AND inventario.id_sucursal =  " . $id_sucursal;
            $this->conexion->setQuery($sql);
        }
    }

    function addValeSalida($data) {

        $catalogos = new Catalogos();
        $sql = "INSERT INTO vale_inventario (consecutivo, movimiento, observacion, id_sucursal, id_usuario, fecha)"
                . "SELECT COALESCE(MAX(consecutivo), 0)  + 1, 'Salida', '" . $data["observaciones"] . "', '" . $_SESSION["id_sucursal"] . "', '" . $_SESSION["id"] . "', NOW() FROM vale_inventario WHERE id_sucursal='" . $_SESSION["id_sucursal"] . "' AND movimiento = 'Salida'";
        $this->conexion->setQuery($sql);

        $id_vale = $catalogos->maxTable("vale_inventario", $_SESSION["id_sucursal"]);

        //registrar el detalle del vale
        for ($i = 0; $i < count($data["codigo"]); $i++) {
            $egreso = str_replace(",", "", $data["egreso"][$i]);
            $resto = $egreso;

            $sql = "SELECT vale_entrada.* 
                    FROM vale_entrada 
                    INNER JOIN productos ON (vale_entrada.id_producto = productos.id)
                    INNER JOIN vale_inventario ON (vale_inventario.id = vale_entrada.id_vale)
                    WHERE vale_entrada.actual > 0 AND productos.consecutivo = '" . $data["codigo"][$i] . "' AND  vale_inventario.id_sucursal = " . $_SESSION["id_sucursal"] . " ORDER BY vale_entrada.caducidad ";
            $query = $this->conexion->getQuery($sql);
            foreach ($query AS $row) {
                $id_vale_entrada = $row->id;
                $resto -= $row->actual;
                if ($resto <= 0) {
                    $resto += $row->actual; //ajuste
                    $sql = "INSERT INTO vale_salida (egreso, id_vale_entrada, id_producto, id_vale)
                    SELECT $resto, $id_vale_entrada, productos.id, $id_vale
                    FROM productos 
                    INNER JOIN inventario ON (inventario.id_producto = productos.id)
                    WHERE consecutivo = '" . $data["codigo"][$i] . "' AND inventario.id_sucursal =  " . $_SESSION["id_sucursal"];
                    $this->conexion->setQuery($sql);

                    $sql = "UPDATE vale_entrada
                    SET actual = actual - $resto
                    WHERE id = $id_vale_entrada";
                    $this->conexion->setQuery($sql);

                    $sql = "UPDATE inventario
                    INNER JOIN productos ON (inventario.id_producto = productos.id)
                    SET inventario.existencia = inventario.existencia - $resto
                    WHERE productos.consecutivo = '" . $data["codigo"][$i] . "' AND inventario.id_sucursal = " . $_SESSION["id_sucursal"];
                    $this->conexion->setQuery($sql);
                    break;
                } else {
                    $sql = "INSERT INTO vale_salida (egreso, id_vale_entrada, id_producto, id_vale)
                    SELECT " . $row->actual . ", $id_vale_entrada, productos.id, $id_vale FROM productos WHERE consecutivo = '" . $data["codigo"][$i] . "' ";
                    $this->conexion->setQuery($sql);

                    $sql = "UPDATE inventario
                    INNER JOIN productos ON (inventario.id_producto = productos.id)
                    SET inventario.existencia = inventario.existencia - $row->actual
                    WHERE productos.consecutivo = '" . $data["codigo"][$i] . "' AND inventario.id_sucursal = " . $_SESSION["id_sucursal"];
                    $this->conexion->setQuery($sql);

                    $sql = "UPDATE vale_entrada
                    SET actual = actual - $row->actual
                    WHERE id = $id_vale_entrada";
                    $this->conexion->setQuery($sql);
                }
            }
        }

        if ($data["id_sucursal"] != $data["id_sucursal_salida"]) {
            //Ajuste de info para ingreso de sucursal
            $sql = "SELECT vale_entrada.*, productos.consecutivo, productos.cantidad
            FROM vale_salida 
            INNER JOIN vale_entrada ON (vale_salida.id_vale_entrada = vale_entrada.id)
            INNER JOIN productos ON (vale_entrada.id_producto = productos.id)
            INNER JOIN vale_inventario ON (vale_inventario.id = vale_salida.id_vale)
            WHERE vale_inventario.id = $id_vale";
            $query = $this->conexion->getQuery($sql);
            $i = 0;
            foreach ($query AS $row) {
                $egreso = str_replace(",", "", $data["egreso"][$i]);
                $cantidad = $egreso / $row->cantidad;

                $codigo[] = $row->consecutivo;
                $ingreso[] = $cantidad;
                $precio[] = $row->precio;
                $caducidad[] = $row->caducidad;
                $iva[] = "false";
                $descuento[] = "";
                $subtotal[] = $cantidad * $row->precio;

                $i++;
            }

            $dataEntrada = array(
                "observaciones" => "TRANSFERENCIA DE INVENTARIO",
                "factura" => "",
                "codigo" => $codigo,
                "ingreso" => $ingreso,
                "precio" => $precio,
                "caducidad" => $caducidad,
                "iva" => $iva,
                "descuento" => $descuento,
                "subtotal" => $subtotal,
                "id_proveedor" => "NULL",
                "id_sucursal" => $data["id_sucursal"],
                "id_sucursal_salida" => $data["id_sucursal_salida"],
            );
            $this->setExistenciaProductos($data["id_sucursal_salida"]);
            $this->addValeEntrada($dataEntrada);
        }
    }

    function setExistenciaProductos($id_sucursal) {
        $sql = "SELECT productos.* 
        FROM productos
        LEFT JOIN inventario ON (productos.id = inventario.id_producto AND inventario.id_sucursal = $id_sucursal)
        WHERE inventario.id IS NULL";
        $data = $this->conexion->getQuery($sql);

        foreach ($data AS $row) {
            $id_producto = $row->id;
            $sql = "INSERT INTO inventario (id_producto, id_sucursal) VALUES($id_producto, $id_sucursal)";
            $this->conexion->setQuery($sql);
        }
    }

    function getValeEntradas($fecha_ini, $fecha_fin, $id_sucursal) {

        $sql = "SELECT vale_inventario.id, vale_inventario.consecutivo, vale_inventario.factura, vale_inventario.observacion, FORMAT(SUM(vale_entrada.subtotal),2) AS total, 
                DATE_FORMAT(vale_inventario.fecha, '%d/%m/%Y') AS fecha, proveedor.nombre AS proveedor
                FROM inventario
                INNER JOIN vale_entrada ON (inventario.id_producto = vale_entrada.id_producto)
                INNER JOIN vale_inventario ON (vale_inventario.id = vale_entrada.id_vale AND vale_inventario.id_sucursal = inventario.id_sucursal) 
                LEFT JOIN proveedor ON (vale_inventario.id_proveedor = proveedor.id AND proveedor.id_sucursal = vale_inventario.id_sucursal ) 
                WHERE DATE(vale_inventario.fecha) >= '$fecha_ini' AND DATE(vale_inventario.fecha) <= '$fecha_fin' AND vale_inventario.movimiento = 'Entrada'  AND vale_inventario.id_sucursal = $id_sucursal
                GROUP BY vale_entrada.id_vale";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getValeSalidas($fecha_ini, $fecha_fin, $id_sucursal) {

        $sql = "SELECT vale_inventario.id, vale_inventario.consecutivo, vale_inventario.observacion, DATE_FORMAT(vale_inventario.fecha, '%d/%m/%Y') AS fecha, sucursal.nombre AS sucursal
                FROM vale_inventario
                LEFT JOIN sucursal ON (vale_inventario.id_sucursal = sucursal.id ) 
                WHERE DATE(vale_inventario.fecha) >= '$fecha_ini' AND DATE(vale_inventario.fecha) <= '$fecha_fin' AND vale_inventario.movimiento = 'Salida' AND vale_inventario.id_sucursal = $id_sucursal";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getTomasInventario($id_sucursal) {

        $sql = "SELECT id, codigo, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha
        FROM conciliacion
        WHERE id_sucursal = '$id_sucursal'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addTomaInventario($data) {
        $catalogos = new Catalogos();
        $sql = "INSERT INTO conciliacion (codigo, id_usuario, id_sucursal, fecha)"
                . "SELECT COALESCE(MAX(codigo), 0)  + 1, " . $_SESSION["id"] . "," . $data["id_sucursal"] . ", NOW() FROM conciliacion WHERE id_sucursal='" . $data["id_sucursal"] . "'";
        $this->conexion->setQuery($sql);

        $id_conciliacion = $catalogos->maxTable("conciliacion", $data["id_sucursal"]);

        for ($i = 0; $i < count($data["codigo"]); $i++) {
            $existencia = str_replace(",", "", $data["existencia"][$i]);
            $conteo = str_replace(",", "", $data["conteo"][$i]);
            $diferencia = str_replace(",", "", $data["diferencia"][$i]);
            $sql = "INSERT INTO conciliacion_detalle (existencia, conteo, diferencia, id_producto, id_conciliacion) 
                SELECT  $existencia, $conteo, $diferencia, productos.id, $id_conciliacion 
                FROM productos 
                INNER JOIN inventario ON (inventario.id_producto = productos.id)
                WHERE consecutivo = '" . $data["codigo"][$i] . "' AND inventario.id_sucursal =  " .  $data["id_sucursal"] ;

            $this->conexion->setQuery($sql);
        }
    }

    function close() {

        $this->conexion->close();
    }

}

?>