<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/Empresas/model/Usuarios.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Sucursales {

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


    function elimnaEstudioLPSucursalM($tipo,$id_sucursal,$id_estudio){
        if($tipo=="estudio"){
             $sql = "DELETE from lprecios_maquila_sucursales WHERE id_sucursal=".$id_sucursal." and id_cat_estudio=".$id_estudio; 
        }
        elseif($tipo=="paquete"){
            $sql = "DELETE from lprecios_maquila_sucursales WHERE id_sucursal=".$id_sucursal." and id_paquete_matriz=".$id_estudio;
        }
        $this->conexion->setQuery($sql);
    }


    function actualizaListaPreciosMaquilaM(){


        for($i=1;$i<=$_REQUEST['numero_estudiosm'];$i++){

            if(isset($_REQUEST['id_cat_estudio_'.$i]) &&  $_REQUEST['id_cat_estudio_'.$i]>0){
                $sql = "INSERT INTO lprecios_maquila_sucursales(id_sucursal,id_cat_estudio,precio_maquila) VALUES(".$_REQUEST['id_sucursal'].",".$_REQUEST['id_cat_estudio_'.$i].",".$_REQUEST['costo_maquila_'.$i].")";
                $this->conexion->setQuery($sql);
            }
            elseif(isset($_REQUEST['id_cat_estudio_'.$i]) &&  $_REQUEST['id_cat_estudio_'.$i]=='-1'){
                $sql = "INSERT INTO lprecios_maquila_sucursales(id_sucursal,id_cat_estudio,precio_maquila,id_paquete_sucursal,id_paquete_matriz) VALUES(".$_REQUEST['id_sucursal'].",'1000".$_REQUEST['idpaquetem_'.$i]."',".$_REQUEST['costo_maquila_'.$i].",".$_REQUEST['idpaquetes_'.$i].",".$_REQUEST['idpaquetem_'.$i].")";
                $this->conexion->setQuery($sql);
            }
        }
    }

    function listaEstudioSucursalM($id_sucursal){
        $id_matriz=$this->getIdMatrizCliente();
        $sql = "SELECT 'estudio' as tipo, ce.id, ce.no_estudio,ce.nombre_estudio,e.precio_publico,lpm.precio_maquila,lpm.id_paquete_sucursal,null as paquete_sucursal from lprecios_maquila_sucursales lpm 
            inner join cat_estudio ce on lpm.id_cat_estudio=ce.id 
            inner join estudio e on e.id_cat_estudio=ce.id and e.id_sucursal=".$id_sucursal." 
            where lpm.id_sucursal=".$id_sucursal."

            UNION
            select 'paquete' as tipo, p2.id, p2.alias as no_estudio,p2.nombre as nombre_estudio,p2.precio as precio_publico,lpm.precio_maquila,lpm.id_paquete_sucursal,p.nombre as paquete_sucursal 
            from lprecios_maquila_sucursales lpm inner join paquete p on p.id=lpm.id_paquete_sucursal and p.id_sucursal=".$id_sucursal." 
            inner join paquete p2 on p2.id=lpm.id_paquete_matriz and p2.id_sucursal=".$id_matriz[0]->id." ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function listaPaquetesSucursalM($id_sucursal){
        $sql = "SELECT id,nombre from paquete where activo=1 and id_sucursal=".$id_sucursal." order by nombre";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function listaEstudioBusqueda($busqueda,$id_sucursal){
        $id_matriz=$this->getIdMatrizCliente();
        $sql = "SELECT ce.id,ce.no_estudio,ce.nombre_estudio,e.precio_publico,-1 as paquete_matriz, '' as codigo_paquete from estudio e inner join cat_estudio ce on e.id_cat_estudio=ce.id where e.id_sucursal=".$id_matriz[0]->id." and e.activo=1 and ce.nombre_estudio like '%".$busqueda."%' and ce.id not in (SELECT id_cat_estudio from lprecios_maquila_sucursales where id_sucursal=".$id_sucursal." ) 
            UNION 
            SELECT  -1 as id, '' as no_estudio,nombre as nombre_estudio, precio as precio_publico, id as paquete_matriz, alias as codigo_paquete from paquete where id_sucursal=".$id_matriz[0]->id." and activo=1 and nombre like '%".$busqueda."%' and id not in (SELECT id_paquete_matriz from lprecios_maquila_sucursales where id_sucursal=".$id_sucursal." AND id_paquete_matriz IS NOT NULL ) limit 10";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getIdMatrizCliente(){
        $sql="SELECT id from sucursal WHERE tipo='MATRIZ' and id_cliente=".$_SESSION["id_cliente"];
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getSucursales($id_cliente) {

        $sql = "SELECT sucursal.*, cat_municipio.municipio, cat_estados.estado, cliente.prefijo, cliente.nombre AS cliente 
            FROM sucursal
            INNER JOIN cliente ON (cliente.id = sucursal.id_cliente)
            LEFT JOIN cat_municipio ON (cat_municipio.id = sucursal.ciudad)
            LEFT JOIN cat_estados ON (cat_estados.id = sucursal.estado)
            WHERE sucursal.id_cliente = '$id_cliente' AND sucursal.activo = 1";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getSucursal($id) {

        $sql = "SELECT *
        FROM sucursal
        WHERE id = '$id'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getConsecutivoSucursal($id_cliente) {

        $sql = "SELECT MAX(consecutivo) + 1 AS consecutivo, cliente.prefijo
        FROM sucursal
        INNER JOIN cliente ON (cliente.id = sucursal.id_cliente)
        WHERE sucursal.id_cliente = '$id_cliente' AND sucursal.activo = 1";

        $data = $this->conexion->getQuery($sql);

        if (empty(($data[0]->consecutivo))) {
            $data = $this->getCliente($id_cliente);
        }
        return $data;
    }

    function getCliente($id_cliente) {

        $sql = "SELECT *, 1 AS consecutivo
        FROM cliente 
        WHERE id = '$id_cliente'";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteSucursal($id) {

        $sql = "UPDATE sucursal
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE SUCURSAL: " . str_replace("'", "", $sql),
            "tabla" => "sucursal",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addSucursal($data) {

        $sql = "INSERT INTO sucursal (consecutivo, codigo, nombre, direccion, direccion2, ciudad, estado, activo,"
                . "tel1, tel2, email, id_cliente, prefijo, quimico, cedula,"
                . "quimico_aux, cedula_aux, impresion, inicio_urgencias, fin_urgencias, aumento_urgencias) "
                . "SELECT  CASE WHEN MAX(consecutivo) + 1 IS NULL THEN 1 ELSE 1 END, '" . $data["codigo"] . "', '" . $data["nombre"] . "', '" . $data["direccion"] . "', '" . $data["dirrecion2"] . "', " . $data["ciudad"] . " , " . $data["estado"] . ", 1, "
                . "'" . $data["tel1"] . "', '" . $data["tel2"] . "', '" . $data["email"] . "', '" . $data["id_cliente"] . "',  '" . $data["codigo"] . "', '" . $data["quimico"] . "', '" . $data["cedula"] . "', "
                . "'" . $data["quimico_aux"] . "', '" . $data["cedula_aux"] . "', '" . $data["impresion"] . "', '" . $data["inicio_urgencias"] . "',  '" . $data["fin_urgencias"] . "', '" . $data["aumento_urgencias"] . "' "
                . "FROM sucursal WHERE activo = 1 AND id_cliente = " . $data["id_cliente"];

        $this->conexion->setQuery($sql);

        $sql = "SELECT MAX(id) AS max
            FROM sucursal
            WHERE activo = '1'";

        $aux = $this->conexion->getQuery($sql);
        $id = $aux[0]->max;

        //Logo
        $extension = $this->extension_img($_FILES["logo"]["name"]);
        $archivo = "cliente_" . $_SESSION["ruta"] . "_" . $data["id_cliente"] . "_img_" . $id . $extension;
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], "../../images-sucursales/" . $archivo)) {
            $sql = "UPDATE `sucursal` "
                    . "SET img = '" . $archivo . "' "
                    . "WHERE id = " . $id;
            $this->conexion->setQuery($sql);
        };

        //Firma encargado
        $extension = $this->extension_img($_FILES["firma_quimico_encargado"]["name"]);
        $archivo = "cliente_" . $_SESSION["ruta"] . "_" . $data["id_cliente"] . "_firma_" . $id . $extension;
        if (move_uploaded_file($_FILES["firma_quimico_encargado"]["tmp_name"], "../../images-sucursales/firmas/" . $archivo)) {
            $sql = "UPDATE `sucursal` "
                    . "SET firma = '" . $archivo . "' "
                    . "WHERE id = " . $id;
            $this->conexion->setQuery($sql);
        };

        //Firma aux
        $extension = $this->extension_img($_FILES["firma_quimico_auxiliar"]["name"]);
        $archivo = "cliente_" . $_SESSION["ruta"] . "_" . $data["id_cliente"] . "_firma_aux_" . $id . $extension;
        if (move_uploaded_file($_FILES["firma_quimico_auxiliar"]["tmp_name"], "../../images-sucursales/firmas/" . $archivo)) {
            $sql = "UPDATE `sucursal` "
                    . "SET firma_aux = '" . $archivo . "' "
                    . "WHERE id = " . $id;
            $this->conexion->setQuery($sql);
        };

        //log_activity
        $data = array(
            "observaciones" => "NUEVA SUCURSAL: " . str_replace("'", "", $sql),
            "tabla" => "sucursal",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editSucursal($data) {

        $sql = "UPDATE sucursal SET "
                . "nombre = '" . $data["nombre"] . "', direccion = '" . $data["direccion"] . "', direccion2 = '" . $data["dirrecion2"] . "', ciudad = " . $data["ciudad"] . " , estado = " . $data["estado"] . ", "
                . "tel1 = '" . $data["tel1"] . "', tel2 = '" . $data["tel2"] . "', email = '" . $data["email"] . "', quimico = '" . $data["quimico"] . "', cedula = '" . $data["cedula"] . "', "
                . "quimico_aux = '" . $data["quimico_aux"] . "', cedula_aux = '" . $data["cedula_aux"] . "', impresion = '" . $data["impresion"] . "', inicio_urgencias = '" . $data["inicio_urgencias"] . "',  fin_urgencias = '" . $data["fin_urgencias"] . "', aumento_urgencias = '" . $data["aumento_urgencias"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //Logo
        $extension = $this->extension_img($_FILES["logo"]["name"]);
        $archivo = "cliente_" . $_SESSION["ruta"] . "_" . $data["id_cliente"] . "_img_" . $data["id"] . $extension;
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], "../../images-sucursales/" . $archivo)) {
            $sql = "UPDATE `sucursal` "
                    . "SET img = '" . $archivo . "' "
                    . "WHERE id = " . $data["id"];
            $this->conexion->setQuery($sql);
        };

        //Firma encargado
        $extension = $this->extension_img($_FILES["firma_quimico_encargado"]["name"]);
        $archivo = "cliente_" . $_SESSION["ruta"] . "_" . $data["id_cliente"] . "_firma_" . $data["id"] . $extension;
        if (move_uploaded_file($_FILES["firma_quimico_encargado"]["tmp_name"], "../../images-sucursales/firmas/" . $archivo)) {
            $sql = "UPDATE `sucursal` "
                    . "SET firma = '" . $archivo . "' "
                    . "WHERE id = " . $data["id"];
            $this->conexion->setQuery($sql);
        };

        //Firma aux
        $extension = $this->extension_img($_FILES["firma_quimico_auxiliar"]["name"]);
        $archivo = "cliente_" . $_SESSION["ruta"] . "_" . $data["id_cliente"] . "_firma_aux_" . $data["id"] . $extension;
        if (move_uploaded_file($_FILES["firma_quimico_auxiliar"]["tmp_name"], "../../images-sucursales/firmas/" . $archivo)) {
            $sql = "UPDATE `sucursal` "
                    . "SET firma_aux = '" . $archivo . "' "
                    . "WHERE id = " . $data["id"];
            $this->conexion->setQuery($sql);
        };

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE SUCURSAL: " . str_replace("'", "", $sql),
            "tabla" => "cliente",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getSucursalesCliente($id_cliente) {

        $sql = "SELECT sucursal.*, cat_municipio.municipio, cat_estados.estado, cliente.prefijo, cliente.nombre AS cliente 
            FROM sucursal
            INNER JOIN cliente ON (cliente.id = sucursal.id_cliente)
            LEFT JOIN cat_municipio ON (cat_municipio.id = sucursal.ciudad)
            LEFT JOIN cat_estados ON (cat_estados.id = sucursal.estado)
            WHERE sucursal.id_cliente = '$id_cliente' AND sucursal.activo = 1";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function clonarSucursal($id_sucursal_origen, $id_sucursal) {

        //BORRAR COMPONENTES
        $sql = "DELETE FROM componente_numerico
        WHERE `id_componente` in (SELECT id FROM componente WHERE id_sucursal = '$id_sucursal') ";
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM componente_formula
        WHERE `id_componente` in (SELECT id FROM componente WHERE id_sucursal = '$id_sucursal') ";
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM componente_lista
        WHERE `id_componente` in (SELECT id FROM componente WHERE id_sucursal = '$id_sucursal') ";
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM componente_tabla
        WHERE `id_componente` in (SELECT id FROM componente WHERE id_sucursal = '$id_sucursal') ";
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM componente
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);

        //BORRAR LISTA PRECIOS
        $sql = "DELETE FROM lista_precios_estudio
        WHERE `id_lista_precio` in (SELECT id FROM lista_precios WHERE id_sucursal = '$id_sucursal') ";
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM lista_precios
        WHERE `id_sucursal` = '$id_sucursal' ";
        $this->conexion->setQuery($sql);

        //BORRRA PAQUETES
        $sql = "DELETE FROM paquete_estudio
        WHERE `id_paquete`  in (SELECT id FROM paquete WHERE id_sucursal = '$id_sucursal') ";
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM paquete
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);

        //BORRAR ESTUDIOS
        $sql = "DELETE FROM estudio
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);

        //BORRRA VIEW
        $sql = "DROP VIEW IF EXISTS paqANDest_$id_sucursal";
        $this->conexion->setQuery($sql);

        //INSERTAR ESTUDIOS
        $sql = "SELECT * FROM estudio WHERE `id_sucursal` = '$id_sucursal' AND activo = 1";
        $data = $this->conexion->getQuery($sql);
        if (count($data) == 0) {
            //ESTUDIOS
            $sql = "INSERT INTO estudio (precio_publico, montaje, procesos, id_referencia, metodo_utilizado, volumen_requerido, porcentaje, id_indicaciones, id_sucursal, id_cat_estudio, id_tipo_reporte, activo, precio_maquila)
            SELECT precio_publico, montaje, procesos, id_referencia, metodo_utilizado, volumen_requerido, porcentaje, id_indicaciones, $id_sucursal, id_cat_estudio, id_tipo_reporte, activo, precio_maquila
            FROM estudio
            WHERE `id_sucursal` = '$id_sucursal_origen' AND activo = 1";
            $this->conexion->setQuery($sql);
        }

        //INSERTAR PAQUETE
        $sql = "SELECT * FROM paquete  WHERE `id_sucursal` = '$id_sucursal' AND activo = 1";
        $data = $this->conexion->getQuery($sql);
        if (count($data) == 0) {
            //PAQUETES
            $sql = "INSERT INTO paquete (no_paquete, nombre, alias, metodo, precio, id_sucursal, id_tipo_reporte, activo)
            SELECT no_paquete, nombre, alias, metodo, precio, $id_sucursal, id_tipo_reporte, activo
            FROM paquete
            WHERE `id_sucursal` = '$id_sucursal_origen' AND activo = 1";
            $this->conexion->setQuery($sql);

            //Paquete origen
            $sql = "SELECT * FROM paquete
            WHERE `id_sucursal` = '$id_sucursal_origen' AND activo = 1";
            $data = $this->conexion->getQuery($sql);
            foreach ($data AS $fila) {
                $id_paquete = $fila->id;
                $no_paquete = $fila->no_paquete;

                //Paquete destino
                $sql = "SELECT * FROM paquete
                WHERE `id_sucursal` = '$id_sucursal' AND no_paquete = $no_paquete AND activo = 1";
                $data_ = $this->conexion->getQuery($sql);
                foreach ($data_ AS $row) {
                    $id_paquete_destino = $row->id;

                    //estudios del paquete
                    $sql = "INSERT INTO paquete_estudio (id_paquete, id_estudio, precio_neto, posicion)
                    SELECT $id_paquete_destino, id_estudio, precio_neto, posicion
                    FROM paquete_estudio
                    WHERE `id_paquete` = '$id_paquete'";
                    $this->conexion->setQuery($sql);
                }
            }
        }

        //INSERTAR LISTA DE PRECIOS
        $sql = "SELECT * FROM lista_precios  WHERE `id_sucursal` = '$id_sucursal' ";
        $data = $this->conexion->getQuery($sql);
        if (count($data) == 0) {
            //LISA DE PRECIOS //revisar bien lista de precios
            $lista_precios = "INSERT INTO lista_precios (no, nombre, alias, id_sucursal)
            SELECT no, nombre, alias, $id_sucursal
            FROM lista_precios
            WHERE `id_sucursal` = '$id_sucursal_origen' ";
            $this->conexion->setQuery($lista_precios);

            //Lista de precio origen
            $sql = "SELECT * FROM lista_precios
            WHERE `id_sucursal` = '$id_sucursal_origen'";
            $data = $this->conexion->getQuery($sql);
            foreach ($data AS $fila) {
                $id_lista = $fila->id;
                $no_lista = $fila->no;

                //Lista de precios destino
                $sql = "SELECT * FROM lista_precios
                WHERE `id_sucursal` = '$id_sucursal' AND no = $no_lista";

                $data_ = $this->conexion->getQuery($sql);
                foreach ($data_ AS $row) {
                    $id_lista_destino = $row->id;

                    //estudios de la lista
                    $sql = "INSERT INTO lista_precios_estudio (precio_publico, precio_neto, id_estudio, id_lista_precio, activo, id_sucursal, id_paquete)
                    SELECT precio_publico, precio_neto, id_estudio, $id_lista_destino, activo, $id_sucursal, id_paquete
                    FROM lista_precios_estudio
                    WHERE `id_lista_precio` = '$id_lista'";
                    $this->conexion->setQuery($sql);
                }
            }

            //Ajustar los id de los paquetes de la nueva sucursal
            $sql = "SELECT * FROM lista_precios
            WHERE `id_sucursal` = '$id_sucursal'";
            $data = $this->conexion->getQuery($sql);
            foreach ($data AS $fila) {
                $id_lista = $fila->id;
                $no_lista = $fila->no;

                $sql = "SELECT * FROM lista_precios_estudio
                WHERE `id_lista_precio` = '$id_lista' AND id_paquete IS NOT NULL";
                $data_ = $this->conexion->getQuery($sql);
                foreach ($data_ AS $row) {
                    $id_estudio_lista = $row->id;
                    $id_paquete = $row->id_paquete;
                    //Actualizacion
                    $sql = "UPDATE lista_precios_estudio
                    SET id_paquete = (SELECT id FROM `paquete` WHERE no_paquete = (SELECT no_paquete FROM `paquete` WHERE `id` = '$id_paquete') AND id_sucursal = $id_sucursal)
                    WHERE id = $id_estudio_lista";
                    $this->conexion->setQuery($sql);
                }
            }
        }

        //INSERTAR COMPONENTES

        $sql = "SELECT * FROM componente  WHERE `id_sucursal` = '$id_sucursal'  AND activo = 1";
        $data = $this->conexion->getQuery($sql);
        if (count($data) == 0) {
            //COMPONENTES
            $componentes = "INSERT INTO componente (componente, alias, capturable, imprimible, linea, interfaz_letra, observaciones, id_sucursal, id_cat_componente, id_estudio, activo, unidad, referencia, orden, total_absoluto, absoluto)
            SELECT componente, alias, capturable, imprimible, linea, interfaz_letra, observaciones, $id_sucursal, id_cat_componente, id_estudio, activo, unidad, referencia, orden, total_absoluto, absoluto
            FROM componente
            WHERE `id_sucursal` = '$id_sucursal_origen' AND activo = 1";

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
                        
                        $sql = "INSERT INTO componente_numerico (referencia, edad_inicio, edad_fin, valores_decimales, valores_unidades, alta_aceptable, bajo_aceptable, alta, baja, tipo_edad, id_componente, activo)
                        SELECT referencia, edad_inicio, edad_fin, valores_decimales, valores_unidades, alta_aceptable, bajo_aceptable, alta, baja, tipo_edad, $id_componente_destino, activo
                        FROM componente_numerico
                        WHERE `id_componente` = '$id_componente' AND activo = 1";
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

                    usleep(10000); //nanosegundos 1,000,000 -> 1 seg
                    //echo "No." . $i . " = " . $id_componente_destino . " -> OK<BR>";
                    $i++;
                }
            }
        }

        // VIEW
        $view = "DROP VIEW IF EXISTS paqANDest_$id_sucursal";
        $this->conexion->setQuery($sql);
        $view = "CREATE VIEW paqANDest_$id_sucursal AS SELECT `paquete`.`id` AS `id`, `paquete`.`no_paquete` AS `no_paquete`, `paquete`.`nombre` AS `nombre`,
        `paquete`.`alias` AS `alias`, 'paquete' AS `paquete`
        FROM `paquete`
        WHERE `paquete`.`id_sucursal` = '$id_sucursal' AND `paquete`.`activo` = 1";
        $this->conexion->setQuery($sql);
    }

    function getUsuarios($id_cliente) {

        $sql = "SELECT usuario.*, tipo_empleado.tipo AS rol 
        FROM usuario
        INNER JOIN sucursal ON (sucursal.id = usuario.id_sucursal)
        INNER JOIN tipo_empleado ON (tipo_empleado.id = usuario.id_tipo_empleado)
        WHERE sucursal.id_cliente = $id_cliente AND usuario.activo = 1 AND usuario NOT LIKE '%connections%'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getUsuario($id) {

        $sql = "SELECT * 
        FROM usuario
        WHERE id = '$id'";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function list_regimen() {

        $sql = "SELECT * 
        FROM regimen ";
        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function addUsuario($data) {

        $sql = "INSERT INTO usuario (no, usuario, contraseña, nombre, id_tipo_empleado, id_sucursal) "
                . "SELECT MAX(no) + 1, '" . $data["codigo"] . "_" . $data["usuario"] . "', '" . $data["password"] . "', '" . $data["nombre"] . "', 1, '" . $data["id_sucursal"] . "' FROM usuario";

        $this->conexion->setQuery($sql);

        $sql = "SELECT MAX(id) AS max
            FROM usuario
            WHERE activo = '1'";

        $aux = $this->conexion->getQuery($sql);
        $id = $aux[0]->max;

        //PERMISOS
        $sql = "INSERT INTO permisos_usuario (id_cat_permiso, id_usuario)
                SELECT id, $id
                FROM cat_permisos;";
        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "NUEVO USUARIO: " . str_replace("'", "", $sql),
            "tabla" => "usuario",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editUsuario($data) {

        $sql = "UPDATE usuario "
                . "SET usuario = '" . $data["codigo"] . "_" . $data["usuario"] . "', contraseña = '" . $data["password"] . "', nombre = '" . $data["nombre"] . "' "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE USUARIO: " . str_replace("'", "", $sql),
            "tabla" => "usuario",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function close() {

        $this->conexion->close();
    }

    function extension_img($img) {
        if (strpos($img, '.png') !== false) {
            return ".png";
        } else if (strpos($img, '.jpg') !== false) {
            return ".jpg";
        } else if (strpos($img, '.jpeg') !== false) {
            return ".jpeg";
        }
    }

}

?>
