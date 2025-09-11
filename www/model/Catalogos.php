<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/libs/phpqrcode/qrlib.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/libs/barcode.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');

class Catalogos {

    private $conexion;

    function __construct() {

        $this->conexion = new Conexion();
    }

    function getTipoEstudio($idEstudio){
        $sql = "SELECT tipo from cat_estudio where id=".$idEstudio;
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getClasesEstudio(){
        $sql = "SELECT * from clases_estudio_ec order by nombre ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getComplementarios(){
        $sql = "SELECT UPPER(nombre) as nombre,id  FROM lista_documentos_externos";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEstados() {

        $sql = "SELECT id, UPPER(estado) AS estado 
            FROM cat_estados";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getCatEstudios() {

        $sql = "SELECT *
            FROM cat_estudio";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getMunicipios($estado) {

        $sql = "SELECT cat_municipio.id,  UPPER(municipio) AS municipio 
            FROM cat_municipio
            INNER JOIN cat_estados ON (cat_estados.id = cat_municipio.id_cat_estado)
            WHERE cat_estados.estado = '" . $estado . "'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEspecialidades() {

        $sql = "SELECT * 
            FROM especialidad order by especialidad";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getCatComponentes() {

        $sql = "SELECT * 
            FROM cat_componente";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getSucursal($id_sucursal) {

        $sql = "SELECT  sucursal.*, cliente.nombre AS cliente, cliente.direccion AS direccion_cliente, ticket_detalle.orden_interna 
            FROM sucursal
            INNER JOIN cliente ON (cliente.id = sucursal.id_cliente)
            INNER JOIN ticket_detalle ON (cliente.id = ticket_detalle.id_cliente)
            WHERE sucursal.id = '" . $id_sucursal . "'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }



    function getSucursales($id_sucursal) {

        $sql = "SELECT sucursal.*, cliente.nombre AS cliente, cliente.direccion AS direccion_cliente
            FROM sucursal
            INNER JOIN cliente ON (cliente.id = sucursal.id_cliente  )
            WHERE cliente.id = (SELECT id_cliente FROM sucursal WHERE id = '" . $id_sucursal . "') and sucursal.activo=1";
        //echo $sql;

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getLitasPrecios($id_sucursal) {

        $sql = "SELECT * 
            FROM lista_precios
            WHERE id_sucursal = '" . $id_sucursal . "'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getTipoReporte() {

        $sql = "SELECT * 
            FROM tipo_reporte";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getIndicaciones($id_sucursal) {

        $sql = "SELECT * 
            FROM indicaciones
            WHERE id_sucursal = '" . $id_sucursal . "'  AND activo = '1'
            ORDER BY consecutivo";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getReferencia($id_sucursal) {

        $sql = "SELECT * 
            FROM referencia
            WHERE id_sucursal = '" . $id_sucursal . "'  AND activo = '1'
            ORDER BY consecutivo";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function logActivity($data) {

        $sql = "INSERT INTO `log_activity`(`observaciones`, `tabla`, `id_tabla`, `usuario`, `fecha`)  
                VALUES ('" . $data["observaciones"] . "', '" . $data["tabla"] . "', " . $data["id_tabla"] . ", '" . $data["usuario"] . "', NOW())";

        $this->conexion->setQuery($sql);
    }

    function maxTableActivo($tabla, $id_sucursal) {
        $sql = "SELECT MAX(id) AS max
            FROM $tabla
            WHERE id_sucursal = '" . $id_sucursal . "'  AND activo = '1'";

        $data = $this->conexion->getQuery($sql);

        if (count($data) == 0) {
            return 0;
        } else {
            return $data[0]->max;
        }
    }

    function maxTable($tabla, $id_sucursal) {
        $sql = "SELECT MAX(id) AS max
            FROM $tabla
            WHERE id_sucursal = '" . $id_sucursal . "'";

        $data = $this->conexion->getQuery($sql);

        if (count($data) == 0) {
            return 0;
        } else {
            return $data[0]->max;
        }
    }

    function getRol() {

        $sql = "SELECT id, UPPER(descripcion) AS rol 
            FROM rol";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getDepartamentos() {

        $sql = "SELECT *
            FROM departamento
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getSecciones() {

        $sql = "SELECT *
            FROM secciones
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getMateriaBiologica() {

        $sql = "SELECT *
            FROM materia_biologica
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getRecipienteBiologico() {

        $sql = "SELECT *
            FROM recipiente_biologico
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getTipoEmpleado() {

        $sql = "SELECT *
            FROM tipo_empleado order by tipo";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getSeccionesAgenda($id_sucursal) {

        $sql = "SELECT *
            FROM secciones_agenda
            WHERE id_sucursal =$id_sucursal AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function QR_Generator($content) {
        $filename = 'tmp.png';
        QRcode::png($content, $filename);

        $data = file_get_contents($filename);
        unlink($filename);

        return base64_encode($data);
    }

    function barsGenerator($content) {
        $filename = 'tmp.png';
        barcode($filename, $content, 20, 'horizontal', 'code128', false);

        $data = file_get_contents($filename);
        unlink($filename);

        return base64_encode($data);
    }

    function close() {

        $this->conexion->close();
    }

    # Obtener la lista de forma de pago (pago de empresas de cédito)

    function getFormaPago($id_sucursal) {
        $sql = "SELECT * FROM forma_pago  WHERE activo=1 and id_sucursal=" . $id_sucursal . "  ORDER BY consecutivo";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    # Obtener la lista de formas de pago (facturación)

    function getFormasPago() {
        $sql = "SELECT * FROM formas_pago";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    #Obtener el uso del CFDI para facturación

    function getUsoCFDI() {
        $sql = "SELECT * FROM usocfdi";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

}

?>
