<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Etiquetas {

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

    function getEtiquetasEstudio($id_orden) {

        $sql = "SELECT orden_estudio.*,cat_estudio.alias, cat_estudio.nombre_estudio, paquete.alias AS paquete_alias, paquete.nombre AS paquete, recipiente_biologico.recipiente 
                FROM orden_estudio "
                . "INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) "
                . "LEFT JOIN recipiente_biologico ON (recipiente_biologico.id = cat_estudio.id_recipiente_biologico) "
                . "LEFT JOIN paquete ON (paquete.id = orden_estudio.id_paquete) "
                . "LEFT JOIN paquete_estudio ON (paquete_estudio.id_paquete = orden_estudio.id_paquete AND paquete_estudio.id_estudio = orden_estudio.id_estudio )  "
                . "WHERE orden_estudio.id_orden = '$id_orden' "
                . "ORDER BY orden_estudio.posicion";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEtiquetasRecipientes($alias, $id_orden) {
        $alias = str_replace(",", "','", $alias);

        $sql = "SELECT recipiente_biologico.recipiente,  GROUP_CONCAT(cat_estudio.alias SEPARATOR ', ') AS estudios 
                FROM orden_estudio "
                . "INNER JOIN cat_estudio ON (cat_estudio.id = orden_estudio.id_estudio) "
                . "LEFT JOIN recipiente_biologico ON (recipiente_biologico.id = cat_estudio.id_recipiente_biologico) "
                . "LEFT JOIN paquete ON (paquete.id = orden_estudio.id_paquete) "
                . "LEFT JOIN paquete_estudio ON (paquete_estudio.id_paquete = orden_estudio.id_paquete AND paquete_estudio.id_estudio = orden_estudio.id_estudio )  "
                . "WHERE orden_estudio.id_orden = '$id_orden' AND cat_estudio.alias IN ('$alias')"
                . "GROUP BY recipiente_biologico.recipiente";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEtiquetasRecipientesVarias($alias) {
        $alias = substr($cadena, -1) == "," ? substr($alias, 0, -1) : $alias;
        $alias = str_replace(",", ", ", $alias);

        $sql = "SELECT '' AS recipiente,  '$alias' AS estudios";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function close() {

        $this->conexion->close();
    }

}

?>
