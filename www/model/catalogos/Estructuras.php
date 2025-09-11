<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Estructuras {

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

    //DESCUENTO

    function getDescuentos($id_sucursal) {

        $sql = "SELECT * 
            FROM descuento
            WHERE id_sucursal = '$id_sucursal' AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getDescuento($id) {

        $sql = "SELECT * 
            FROM descuento
            WHERE id = '$id'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function consecutivoDescuento($id_sucursal) {
        $sql = "SELECT MAX(consecutivo) + 1 AS consecutivo
            FROM descuento
            WHERE id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data[0]->consecutivo == "" ? "1" : $data[0]->consecutivo;
    }

    function aliasDescuento($alias, $id_sucursal) {
        $sql = "SELECT codigo
        FROM descuento
        WHERE codigo = '$alias' AND id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteDescuento($id) {

        $sql = "UPDATE descuento
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE DESCUENTO: " . str_replace("'", "", $sql),
            "tabla" => "descuento",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addDescuento($data) {

        $sql = "INSERT INTO descuento (consecutivo, codigo, nombre, descuento, autorizacion, id_sucursal)"
                . "SELECT COALESCE(MAX(consecutivo), 0)  + 1, '" . $data["codigo"] . "', '" . $data["nombre"] . "', '" . $data["descuento"] . "', '" . $data["autorizacion"] . "', '" . $data["id_sucursal"] . "' FROM descuento WHERE id_sucursal='" . $data["id_sucursal"] . "' AND activo = 1";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO DESCUENTO: " . str_replace("'", "", $sql),
            "tabla" => "descuento",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editDescuento($data) {

        $sql = "UPDATE descuento "
                . "SET codigo = '" . $data["codigo"] . "', nombre = '" . $data["nombre"] . "', descuento = '" . $data["descuento"] . "', autorizacion =  '" . $data["autorizacion"] . "'"
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE DESCUENTO: " . str_replace("'", "", $sql),
            "tabla" => "descuento",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    //PAGO

    function aliasFormaPago($alias, $id_sucursal) {
        $sql = "SELECT codigo
        FROM forma_pago
        WHERE codigo = '$alias' AND id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getFormasPagos($id_sucursal) {

        $sql = "SELECT * 
            FROM forma_pago
            WHERE id_sucursal = '$id_sucursal' AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getFormaPago($id) {

        $sql = "SELECT * 
            FROM forma_pago
            WHERE id = '$id'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function consecutivoFormasPago($id_sucursal) {
        $sql = "SELECT MAX(consecutivo) + 1 AS consecutivo
            FROM forma_pago
            WHERE id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data[0]->consecutivo == "" ? "1" : $data[0]->consecutivo;
    }

    function deleteFormaPago($id) {

        $sql = "UPDATE forma_pago
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE FORMA DE PAGO: " . str_replace("'", "", $sql),
            "tabla" => "forma_pago",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addFormaPago($data) {

        $sql = "INSERT INTO forma_pago (consecutivo, codigo, descripcion, id_sucursal)"
                . "SELECT COALESCE(MAX(consecutivo), 0)  + 1, '" . $data["codigo"] . "', '" . $data["nombre"] . "', '" . $data["id_sucursal"] . "' FROM forma_pago WHERE id_sucursal='" . $data["id_sucursal"] . "'";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA FORMA DE PAGO: " . str_replace("'", "", $sql),
            "tabla" => "forma_pago",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editFormaPago($data) {

        $sql = "UPDATE forma_pago "
                . "SET codigo = '" . $data["codigo"] . "', descripcion = '" . $data["nombre"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE FORMA DE PAGO: " . str_replace("'", "", $sql),
            "tabla" => "forma_pago",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    //INDICACIONES

    function getIndicaciones($id_sucursal) {

        $sql = "SELECT * 
            FROM indicaciones
            WHERE id_sucursal = '$id_sucursal' AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function consecutivoIndicaciones($id_sucursal) {
        $sql = "SELECT MAX(consecutivo) + 1 AS consecutivo
            FROM indicaciones
            WHERE id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data[0]->consecutivo == "" ? "1" : $data[0]->consecutivo;
    }

    function getIndicacion($id) {

        $sql = "SELECT * 
            FROM indicaciones
            WHERE id = '$id'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteIndicacion($id) {

        $sql = "UPDATE indicaciones
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE INDICACION: " . str_replace("'", "", $sql),
            "tabla" => "indicaciones",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addIndicacion($data) {

        $sql = "INSERT INTO indicaciones (consecutivo, descripcion, indicacion, id_sucursal)"
                . "SELECT COALESCE(MAX(consecutivo), 0)  + 1, '" . $data["nombre"] . "', '" . $data["indicacion"] . "', '" . $data["id_sucursal"] . "' FROM indicaciones WHERE id_sucursal='" . $data["id_sucursal"] . "'";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA INDICACION: " . str_replace("'", "", $sql),
            "tabla" => "indicaciones",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editIndicacion($data) {

        $sql = "UPDATE indicaciones "
                . "SET descripcion = '" . $data["nombre"] . "', indicacion = '" . $data["indicacion"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE INDICACION: " . str_replace("'", "", $sql),
            "tabla" => "indicaciones",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    //REFERENCIAS

    function getReferencias($id_sucursal) {

        $sql = "SELECT * 
            FROM referencia
            WHERE id_sucursal = '$id_sucursal' AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getReferencia($id) {

        $sql = "SELECT * 
            FROM referencia
            WHERE id = '$id'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function consecutivoReferencia($id_sucursal) {
        $sql = "SELECT MAX(consecutivo) + 1 AS consecutivo
            FROM referencia
            WHERE id_sucursal = $id_sucursal  AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data[0]->consecutivo == "" ? "1" : $data[0]->consecutivo;
    }

    function deleteReferencia($id) {

        $sql = "UPDATE referencia
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE REFERENCIA: " . str_replace("'", "", $sql),
            "tabla" => "referencia",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addReferencia($data) {

        $sql = "INSERT INTO referencia (consecutivo, codigo, nombre, direccion, ciudad, id_cat_estados, cp, telefono, email, id_sucursal)"
                . "SELECT COALESCE(MAX(consecutivo), 0)  + 1,  '" . $data["codigo"] . "', '" . $data["nombre"] . "',  '" . $data["direccion"] . "',  '" . $data["ciudad"] . "',  " . $data["id_cat_estado"] . ",  '" . $data["cp"] . "',  '" . $data["telefono"] . "', '" . $data["email"] . "', '" . $data["id_sucursal"] . "' FROM referencia WHERE id_sucursal='" . $data["id_sucursal"] . "'";

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVA REFERENCIA: " . str_replace("'", "", $sql),
            "tabla" => "referencia",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editReferencia($data) {

        $sql = "UPDATE referencia "
                . "SET codigo = '" . $data["codigo"] . "', nombre = '" . $data["nombre"] . "', direccion = '" . $data["direccion"] . "', ciudad = '" . $data["ciudad"] . "', id_cat_estados = '" . $data["id_cat_estado"] . "', "
                . "cp = '" . $data["cp"] . "', telefono = '" . $data["telefono"] . "', email = '" . $data["email"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE REFERENCIA: " . str_replace("'", "", $sql),
            "tabla" => "referencia",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addBonificacion($data) {
        if ($data["monedero"] > "0") {

            $sql = "INSERT INTO bitacora_estudio_porcentaje ( id_estudio, porcentaje_actual, nuevo_porcentaje, fecha_mov, id_sucursal, id_usuario )
                SELECT estudio.id, estudio.porcentaje, " . $data["monedero"] . ", NOW(), " . $data["id_sucursal"] . ", '" . $_SESSION["id"] . "' FROM  cat_estudio
                INNER JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio AND estudio.id_sucursal = '" . $data["id_sucursal"] . "') 
                WHERE cat_estudio.id_departamento = " . $data["id_departamento"];
            $this->conexion->setQuery($sql);

            $sql = "UPDATE cat_estudio
                INNER JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio AND estudio.id_sucursal = '" . $data["id_sucursal"] . "') 
                SET porcentaje = '" . $data["monedero"] . "'
                WHERE cat_estudio.id_departamento = " . $data["id_departamento"];
            $this->conexion->setQuery($sql);
        }


        if ($data["aumento"] > "0") {
            $aumento = 1 + ($data["aumento"] / 100);

            $sql = "INSERT INTO bitacora_estudio_precio ( id_estudio, precio_publico_actual, nuevo_precio, fecha_mov, id_sucursal, id_usuario )
                SELECT estudio.id, estudio.precio_publico, $aumento*estudio.precio_publico, NOW(), " . $data["id_sucursal"] . ", '" . $_SESSION["id"] . "' FROM  cat_estudio
                INNER JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio AND estudio.id_sucursal = '" . $data["id_sucursal"] . "') 
                WHERE cat_estudio.id_departamento = " . $data["id_departamento"];
            $this->conexion->setQuery($sql);

            $sql = "UPDATE cat_estudio
                INNER JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio AND estudio.id_sucursal = '" . $data["id_sucursal"] . "') 
                SET precio_publico =  $aumento*estudio.precio_publico
                WHERE cat_estudio.id_departamento = " . $data["id_departamento"];
            $this->conexion->setQuery($sql);
        }

        if ($data["descuento"] > "0") {
            $descuento = 1 - ($data["descuento"] / 100);

            $sql = "INSERT INTO bitacora_estudio_precio ( id_estudio, precio_publico_actual, nuevo_precio, fecha_mov, id_sucursal, id_usuario )
                SELECT estudio.id, estudio.precio_publico, $descuento*estudio.precio_publico, NOW(), " . $data["id_sucursal"] . ", '" . $_SESSION["id"] . "' FROM  cat_estudio
                INNER JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio AND estudio.id_sucursal = '" . $data["id_sucursal"] . "') 
                WHERE cat_estudio.id_departamento = " . $data["id_departamento"];
            $this->conexion->setQuery($sql);

            $sql = "UPDATE cat_estudio
                INNER JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio AND estudio.id_sucursal = '" . $data["id_sucursal"] . "') 
                SET precio_publico =  $descuento*estudio.precio_publico
                WHERE cat_estudio.id_departamento = " . $data["id_departamento"];
            $this->conexion->setQuery($sql);
        }
    }

    function close() {

        $this->conexion->close();
    }

}

?>
