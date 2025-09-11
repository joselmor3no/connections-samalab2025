<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Estudios {

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

    function addEstudio($data) {
        $sql = "INSERT INTO `estudio`( `precio_publico`, `montaje`, `procesos`, `id_referencia`, `metodo_utilizado`, `volumen_requerido`, "
                . "`id_indicaciones`, `id_sucursal`, `id_cat_estudio`, porcentaje, id_tipo_reporte, precio_maquila, clase) "
                . "VALUES ('" . $data["publico"] . "', '" . $data["montaje"] . "', '" . $data["procesos"] . "', " . $data["id_referencia"] . ", '" . $data["metodo"] . "', '" . $data["volumen"] . "', "
                . "" . $data["id_indicaciones"] . ", '" . $data["id_sucursal"] . "', '" . $data["id"] . "', " . $data["porcentaje"] . ", " . $data["id_formato"] . ", '" . $data["maquila"] . "','" . $data["clase"] . "')";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO DE ESTUDIO: " . str_replace("'", "", $sql),
            "tabla" => "estudio",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editEstudio($data) {
        $sql = "UPDATE estudio "
                . "SET precio_publico = '" . $data["publico"] . "', montaje = '" . $data["montaje"] . "', procesos = '" . $data["procesos"] . "', id_referencia = " . $data["id_referencia"] . ", metodo_utilizado = '" . $data["metodo"] . "', volumen_requerido = '" . $data["volumen"] . "', "
                . "id_indicaciones = " . $data["id_indicaciones"] . ", porcentaje = " . $data["porcentaje"] . ", id_tipo_reporte = " . $data["id_formato"] . ",clase='".$data['clase']."', precio_maquila = '" . $data["maquila"] . "', activo = 1"
                . " WHERE id = '" . $data["id_estudio"] . "'";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE ESTUDIO: " . str_replace("'", "", $sql),
            "tabla" => "estudio",
            "id_tabla" => $data["id_estudio"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getEstudiosActuales($id_sucursal) {

        $sql = "SELECT cat_estudio.*, estudio.id AS id_estudio,ces.nombre as clase, estudio.precio_publico AS precio
            FROM estudio
            INNER JOIN cat_estudio ON (cat_estudio.id = estudio.id_cat_estudio) 
            LEFT JOIN clases_estudio_ec ces on ces.id=estudio.clase 
            WHERE  cat_estudio.id_secciones != 67  AND estudio.activo = 1 
            ORDER BY cat_estudio.nombre_estudio ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEstudios($id_sucursal) {

        //Extrar si el cliente es laboratorio o imagen
        $sql = "SELECT c.teleradiologia, c.laboratorio 
                FROM sucursal s 
                INNER JOIN cliente c ON (s.id_cliente = c.id) 
                WHERE s.id = " . $id_sucursal;

        $data = $this->conexion->getQuery($sql);
        foreach ($data AS $row) {
            
        }
        $tipo = "";
        if ($row->teleradiologia == '1' && $row->laboratorio == '1') {
            $tipo = "(cat_estudio.tipo = 'Estudios' OR cat_estudio.tipo = 'Gabinete') AND cat_estudio.id_secciones != 67 ";
        } else if ($row->teleradiologia == '1' && $row->laboratorio == '0') {
            $tipo = "cat_estudio.tipo = 'Gabinete' AND cat_estudio.id_secciones != 67 ";
        }if ($row->teleradiologia == '0' && $row->laboratorio == '1') {
            $tipo = "cat_estudio.tipo = 'Estudios' AND cat_estudio.id_secciones != 67 ";
        }

        //Estudios no registrados
        $sql = "SELECT cat_estudio.* 
            FROM cat_estudio
            LEFT JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio  AND estudio.activo = 1 )
            WHERE $tipo AND estudio.id IS NULL ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEstudio($id_estudio, $id_sucursal) {

        $sql = "SELECT cat_estudio.*, departamento.departamento, secciones.seccion, materia_biologica.materia, recipiente_biologico.recipiente, 
            CASE
            WHEN estudio.id_tipo_reporte IS NOT NULL THEN estudio.id_tipo_reporte 
            ELSE cat_estudio.id_tipo_reporte 
            END id_tipo_reporte,  
            estudio.id AS id_estudio, estudio.precio_publico, estudio.precio_maquila, estudio.montaje, estudio.procesos, estudio.metodo_utilizado, estudio.volumen_requerido, estudio.porcentaje, estudio.id_indicaciones,
            (SELECT texto FROM resultado_texto WHERE id_estudio = $id_estudio  LIMIT 1  ) AS formato, cec.nombre as nombre_clase,cec.id as id_clase 
            FROM cat_estudio
            LEFT JOIN departamento ON (departamento.id = cat_estudio.id_departamento)
            LEFT JOIN secciones ON (secciones.id = cat_estudio.id_secciones)
            LEFT JOIN materia_biologica ON (materia_biologica.id = cat_estudio.id_materia_biologica)
            LEFT JOIN recipiente_biologico ON (recipiente_biologico.id = cat_estudio.id_recipiente_biologico)
            LEFT JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio  ) 
            LEFT JOIN clases_estudio_ec cec on cec.id=estudio.clase 
            WHERE cat_estudio.id = '$id_estudio' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }
    
        function getComponentesConnections($id_estudio) {

        $sql = "SELECT *
            FROM componente
            WHERE id_estudio = '$id_estudio' AND id_sucursal = 9 AND activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEstudioDescripcion($estudio, $id_sucursal) {
        //Falta valdar

        $sql = "SELECT cat_estudio.*, estudio.precio_publico
            FROM cat_estudio
            INNER JOIN estudio ON (cat_estudio.id = estudio.id_cat_estudio )
            WHERE (cat_estudio.no_estudio LIKE '%$estudio%' OR cat_estudio.nombre_estudio LIKE '%$estudio%' OR cat_estudio.alias LIKE '%$estudio%')  
            AND estudio.id_sucursal = '$id_sucursal' AND (cat_estudio.tipo = 'Estudios' OR cat_estudio.tipo = 'Gabinete')  AND cat_estudio.id_secciones != 67 ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteEstudio($id) {
        $catalogos = new Catalogos();

        $sql = "DELETE FROM bitacora_estudio_precio
        WHERE id_estudio = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE ESTUDIO: " . str_replace("'", "", $sql),
            "tabla" => "estudio",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);

        $catalogos->logActivity($data);

        $sql = "DELETE FROM estudio
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE BITACORA DE PRECIO: " . str_replace("'", "", $sql),
            "tabla" => "estudio",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos->logActivity($data);
    }

    function addFormato($data) {

        $sql = "SELECT * 
            FROM resultado_texto
            WHERE id_estudio = " . $data["id_estudio"] . " AND id_sucursal = " . $data["id_sucursal"];

        $datos = $this->conexion->getQuery($sql);

        if (count($datos) == 0) {

            $sql = "INSERT INTO `resultado_texto`( `texto`, `id_estudio`, `id_sucursal`) 
                    VALUES ('" . $data["formato"] . "', " . $data["id_estudio"] . ", " . $data["id_sucursal"] . ")";
            $this->conexion->setQuery($sql);

            //log_activity
            $data = array(
                "observaciones" => "ACTUALIZAR DE FORMATO: " . str_replace("'", "", $sql),
                "tabla" => "resultado_texto",
                "id_tabla" => 0,
                "usuario" => $_SESSION["usuario"]);
            $catalogos = new Catalogos();
            $catalogos->logActivity($data);
        } else {

            $sql = "UPDATE resultado_texto SET  
                    texto = '" . $data["formato"] . "'
                    WHERE id_estudio = " . $data["id_estudio"] . " AND id_sucursal = " . $data["id_sucursal"];
            $this->conexion->setQuery($sql);

            //log_activity
            $data = array(
                "observaciones" => "NUEVO DE FORMATO: " . str_replace("'", "", $sql),
                "tabla" => "resultado_texto",
                "id_tabla" => 0,
                "usuario" => $_SESSION["usuario"]);
            $catalogos = new Catalogos();
            $catalogos->logActivity($data);
        }
    }

    function clonarEstudio() {

        $id_sucursal_origen = 9;
        $id_sucursal = $_REQUEST["id_sucursal"];
        $id_estudio = $_REQUEST["id_estudio"];

        //INSERTAR COMPONENTES

        $sql = "UPDATE componente_numerico SET activo = 0
        WHERE `id_componente` in (SELECT id FROM componente WHERE id_sucursal = '$id_sucursal' AND id_estudio = $id_estudio) ";
        $this->conexion->setQuery($sql);

        /*$sql = "UPDATE componente_formula  SET activo = 0
        WHERE `id_componente` in (SELECT id FROM componente WHERE id_sucursal = '$id_sucursal' AND id_estudio = $id_estudio) ";
        $this->conexion->setQuery($sql);*/

        $sql = "UPDATE componente_lista  SET activo = 0
        WHERE `id_componente` in (SELECT id FROM componente WHERE id_sucursal = '$id_sucursal' AND id_estudio = $id_estudio) ";
        $this->conexion->setQuery($sql);

        $sql = "UPDATE componente_tabla  SET activo = 0
        WHERE `id_componente` in (SELECT id FROM componente WHERE id_sucursal = '$id_sucursal' AND id_estudio = $id_estudio) ";
        $this->conexion->setQuery($sql);

        $sql = "UPDATE componente  SET activo = 0
        WHERE `id_sucursal` = '$id_sucursal' AND id_estudio = $id_estudio";
        $this->conexion->setQuery($sql);

        $sql = "SELECT * FROM componente  WHERE `id_sucursal` = '$id_sucursal' AND id_estudio = $id_estudio AND activo = 1";
        $data = $this->conexion->getQuery($sql);
        if (count($data) == 0) {
            //COMPONENTES
            $componentes = "INSERT INTO componente (componente, alias, capturable, imprimible, linea, interfaz_letra, observaciones, id_sucursal, id_cat_componente, id_estudio, activo, unidad, referencia, orden, total_absoluto, absoluto)
            SELECT componente, alias, capturable, imprimible, linea, interfaz_letra, observaciones, $id_sucursal, id_cat_componente, id_estudio, activo, unidad, referencia, orden, total_absoluto, absoluto
            FROM componente
            WHERE `id_sucursal` = '$id_sucursal_origen' AND id_estudio = $id_estudio AND activo = 1";

            $this->conexion->setQuery($componentes);

            $i = 1;
            //Componente origen                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
            $sql = "SELECT * FROM componente
            WHERE `id_sucursal` = '$id_sucursal_origen' AND activo = 1";

            $data = $this->conexion->getQuery($sql);
            foreach ($data AS $fila) {
                $id_componente = $fila->id;
                $alias = $fila->alias;
                $id_cat_componente = $fila->id_cat_componente;

                //Componente destino
                $sql = "SELECT * FROM componente
                WHERE `id_sucursal` = '$id_sucursal' AND activo = 1 AND alias = '$alias' ";
                $data_ = $this->conexion->getQuery($sql);
                foreach ($data_ AS $row) {
                    $id_componente_destino = $row->id;

                    if ($id_cat_componente == 1) {//Númerico
                        $sql = "INSERT INTO componente_numerico (referencia, edad_inicio, edad_fin, valores_decimales, valores_unidades, alta_aceptable, bajo_aceptable, alta, baja, tipo_edad, id_componente, activo)
                        SELECT referencia, edad_inicio, edad_fin, valores_decimales, valores_unidades, alta_aceptable, bajo_aceptable, alta, baja, tipo_edad, $id_componente_destino, activo
                        FROM componente_numerico
                        WHERE `id_componente` = '$id_componente' AND activo = 1";
                        $this->conexion->setQuery($sql);
                    } else if ($id_cat_componente == 2) {//Formula
                        $sql = "INSERT INTO componente_formula (formula, id_componente)
                        SELECT formula, $id_componente_destino
                        FROM componente_formula
                        WHERE `id_componente` = '$id_componente' ";
                        $this->conexion->setQuery($sql);
                    } else if ($id_cat_componente == 3) {//Lista
                        $sql = "INSERT INTO componente_lista (elemento, predeterminado, id_componente, activo)
                        SELECT elemento, predeterminado, $id_componente_destino, activo
                        FROM componente_lista
                        WHERE `id_componente` = '$id_componente' AND activo = 1";
                        $this->conexion->setQuery($sql);
                    }

                    //TABLA
                    $sql = "INSERT INTO componente_tabla (sexo, valor, id_componente, activo)
                    SELECT sexo, valor, $id_componente_destino, activo
                    FROM componente_tabla
                    WHERE `id_componente` = '$id_componente' AND activo = 1";
                    $this->conexion->setQuery($sql);

                    usleep(5000); //nanosegundos 1,000,000 -> 1 seg
                    $i++;
                }
            }
        }
    }

    function close() {

        $this->conexion->close();
    }

}

?>
