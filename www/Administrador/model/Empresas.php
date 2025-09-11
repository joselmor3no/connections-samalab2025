<?php

require_once( $_SERVER["DOCUMENT_ROOT"] . '/Administrador/model/Usuarios.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/Conexion.php');
require_once( $_SERVER["DOCUMENT_ROOT"] . '/model/Catalogos.php');

class Empresas {

    private $conexion;

    function __construct() {

        $usuarios = new Usuarios();
        $acceso = $usuarios->validarSession();
        if (!$acceso) {
            header("Location: /Administrador");
        }

        $this->conexion = new Conexion();
    }

    function getEmpresas() {

        $sql = "SELECT * 
            FROM cliente
            WHERE activo = 1";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function getEmpresa($id) {

        $sql = "SELECT * 
            FROM cliente
            WHERE id = '$id' ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function deleteEmpresa($id) {

        $sql = "UPDATE cliente
        SET activo = 0
        WHERE id = " . $id;

        $this->conexion->setQuery($sql);

        //log_activity
        $data = array(
            "observaciones" => "ELIMINACION DE EMPRESA: " . str_replace("'", "", $sql),
            "tabla" => "cliente",
            "id_tabla" => $id,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function addEmpresa($data) {

        $sql = "INSERT INTO `cliente`(prefijo, `nombre`, direccion, id_cat_estados, id_cat_municipio, telefono, correo, max_sucursales,"
                . "fecha_alta, fecha_vence, laboratorio, teleradiologia, inactivo, usuario, password, maquila) "
                . "VALUES ('" . $data["prefijo"] . "', '" . $data["nombre"] . "', '" . $data["direccion"] . "', '" . $data["id_cat_estados"] . "', '" . $data["id_cat_municipio"] . "', '" . $data["telefono"] . "', '" . $data["correo"] . "', '" . $data["max_sucursales"] . "', "
                . "'" . $data["fecha_alta"] . "', '" . $data["fecha_vence"] . "', '" . $data["laboratorio"] . "', '" . $data["teleradiologia"] . "', '" . $data["inactivo"] . "', '" . $data["usuario"] . "', '" . $data["password"] . "', '" . $data["maquila"] . "')";

        $this->conexion->setQuery($sql);

        $sql = "SELECT MAX(id) AS max
            FROM cliente
            WHERE activo = '1'";

        $data = $this->conexion->getQuery($sql);
        $id = $data[0]->max;

        //Archivo
        $extension = $this->extension_img($_FILES["file"]["name"]);
        $archivo = "cliente_" . $_SESSION["ruta"] . "_" . $id . $extension;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "../../images-clientes/" . $archivo)) {
            $sql = "UPDATE `cliente` "
                    . "SET img = '" . $archivo . "'"
                    . "WHERE id = " . $id;
            $this->conexion->setQuery($sql);
        };

        //log_activity
        $data = array(
            "observaciones" => "NUEVO USUARIO ADMIN" . str_replace("'", "", $sql),
            "tabla" => "usuario_admin",
            "id_tabla" => 0,
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function editEmpresa($data) {

        $sql = "UPDATE `cliente` "
                . "SET prefijo = '" . $data["prefijo"] . "', nombre = '" . $data["nombre"] . "', direccion = '" . $data["direccion"] . "', id_cat_estados = '" . $data["id_cat_estados"] . "',  id_cat_municipio = '" . $data["id_cat_municipio"] . "', "
                . "telefono = '" . $data["telefono"] . "', correo = '" . $data["correo"] . "', max_sucursales = '" . $data["max_sucursales"] . "',  fecha_alta = '" . $data["fecha_alta"] . "',  fecha_vence = '" . $data["fecha_vence"] . "', "
                . "laboratorio = '" . $data["laboratorio"] . "', teleradiologia = '" . $data["teleradiologia"] . "', inactivo = '" . $data["inactivo"] . "', usuario = '" . $data["usuario"] . "',  password = '" . $data["password"] . "',  maquila = '" . $data["maquila"] . "'  "
                . "WHERE id = " . $data["id"];

        $this->conexion->setQuery($sql);

        //Archivo
        $extension = $this->extension_img($_FILES["file"]["name"]);
        $archivo = "cliente_" . $_SESSION["ruta"] . "_" . $data["id"] . $extension;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "../../images-clientes/" . $archivo)) {
            $sql = "UPDATE `cliente` "
                    . "SET img = '" . $archivo . "'"
                    . "WHERE id = " . $data["id"];
            $this->conexion->setQuery($sql);
        };

        //log_activity
        $data = array(
            "observaciones" => "EDICION DE EMPRESA ADMIN: " . str_replace("'", "", $sql),
            "tabla" => "cliente",
            "id_tabla" => $data["id"],
            "usuario" => $_SESSION["usuario"]);
        $catalogos = new Catalogos();
        $catalogos->logActivity($data);
    }

    function getSucurales() {

        $sql = "SELECT sucursal.*, cliente.nombre AS cliente "
                . "FROM cliente "
                . "LEFT JOIN sucursal ON (cliente.id = sucursal.id_cliente) "
                . " ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function eliminarEmpresa($id_cliente) {

        $sql = "SELECT sucursal.id, sucursal.nombre AS sucursal, cliente.nombre AS cliente FROM cliente "
                . "LEFT JOIN sucursal ON (cliente.id = sucursal.id_cliente) "
                . "WHERE cliente.id = $id_cliente ";

        $data = $this->conexion->getQuery($sql);
        foreach ($data AS $row) {
            $id_sucursal = $row->id;
            $sucursal = $row->sucursal;
            $cliente = $row->cliente;
            echo "<br><h6 class='font-weight-bold'>" . $cliente . "<br> SUCURSAL: " . $id_sucursal . " - " . $sucursal . "</h6>";
            $this->deleteSucursal($id_sucursal, $id_cliente);
        }


        $sql = "DELETE FROM combos_estudio
          WHERE `id_cliente` = '$id_cliente'";
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM productos
          WHERE `id_cliente` = '$id_cliente'";
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM tipo_proveedor
          WHERE `id_cliente` = '$id_cliente'";
        $this->conexion->setQuery($sql);

        //54. tarjeta
        $sql = "DELETE FROM tarjeta
          WHERE `id_cliente` = '$id_cliente'";
        $this->conexion->setQuery($sql);

        //55. cliente
        $sql = "DELETE FROM cliente
          WHERE `id` = '$id_cliente'";
        $this->conexion->setQuery($sql);
    }

    function eliminarSucursal($id_sucursal) {

        $sql = "SELECT sucursal.id, sucursal.nombre AS sucursal, cliente.nombre AS cliente FROM cliente "
                . "LEFT JOIN sucursal ON (cliente.id = sucursal.id_cliente) "
                . "WHERE sucursal.id = $id_sucursal ";

        $data = $this->conexion->getQuery($sql);
        foreach ($data AS $row) {
            $id_sucursal = $row->id;
            $sucursal = $row->sucursal;
            $cliente = $row->cliente;
            echo "<br><h6 class='font-weight-bold'>" . $cliente . "<br> SUCURSAL: " . $id_sucursal . " - " . $sucursal . "</h6>";
            $this->deleteSucursal($id_sucursal, '');
        }
    }

    function deleteSucursal($id_sucursal, $id_cliente) {
        echo $cliente;

        //1. bitacora_estudio_porcentaje
        $sql = "DELETE FROM bitacora_estudio_porcentaje
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "bitacora_estudio_porcentaje: OK<br>";
        //2.
        $sql = "DELETE FROM bitacora_estudio_precio
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "bitacora_estudio_precio: OK<br>";
        //3. bitacora_paciente
        $sql = "DELETE FROM bitacora_paciente
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "bitacora_paciente: OK<br>";
        //4. bitacora_tarjeta
        $sql = "DELETE FROM bitacora_tarjeta
        WHERE id_usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "bitacora_tarjeta: OK<br>";
        //5. cfdi_datos_factura_masiva
        $sql = "DELETE FROM cfdi_datos_factura_masiva
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "cfdi_datos_factura_masiva: OK<br>";
        //6. cfdi_datos_factura_paciente
        $sql = "DELETE FROM cfdi_datos_factura_paciente
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "cfdi_datos_factura_paciente: OK<br>";
        //7. cfdi_pacientes
        $sql = "DELETE FROM cfdi_pacientes
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "cfdi_pacientes: OK<br>";
        //8. conciliacion_detalle
        $sql = "DELETE FROM conciliacion_detalle
        WHERE id_conciliacion in (SELECT id FROM conciliacion WHERE `id_sucursal` = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "conciliacion_detalle: OK<br>";
        //9. conciliacion
        $sql = "DELETE FROM conciliacion
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "conciliacion: OK<br>";
        //10. correos_enviados
        $sql = "DELETE FROM correos_enviados
        WHERE usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "correos_enviados: OK<br>";
        //11. datos_fiscales_cliente
        $sql = "DELETE FROM datos_fiscales_cliente
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "datos_fiscales_cliente: OK<br>";
        //12. dcm_anotaciones
        $sql = "DELETE FROM dcm_anotaciones
        WHERE id_dcm in (SELECT id FROM dcm WHERE `id_usuario` = (id_usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')))";
        $this->conexion->setQuery($sql);
        //echo "dcm_anotaciones: OK<br>";
        //13. dcm_resultado
        $sql = "DELETE FROM dcm_resultado
        WHERE id_dcm in (SELECT id FROM dcm WHERE `id_usuario` = (id_usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')))";
        $this->conexion->setQuery($sql);
        //echo "dcm_resultado: OK<br>";
        //14. dcm_resultado
        $sql = "DELETE FROM dcm
        WHERE id_usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "dcm_resultado: OK<br>";
        //15. orden_cotizacion
        $sql = "DELETE FROM orden_cotizacion
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "orden_cotizacion: OK<br>";
        //16. expediente_paciente
        $sql = "DELETE FROM expediente_paciente
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "expediente_paciente: OK<br>";
        //17. gasto
        $sql = "DELETE FROM gasto
        WHERE id_usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "gasto: OK<br>";
        //18. vale_detalle
        $sql = "DELETE FROM vale_detalle
        WHERE `id_vale` in (SELECT id FROM vale WHERE id_sucursal ='$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "vale_detalle: OK<br>";
        //19. vale
        $sql = "DELETE FROM vale
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "vale: OK<br>";
        //20. inventario
        $sql = "DELETE FROM inventario
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "inventario: OK<br>";
        //21. log
        $sql = "DELETE FROM log
        WHERE id_usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "log: OK<br>";
        //22. log_resultados
        $sql = "DELETE FROM log_resultados
        WHERE id_usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "log_resultados: OK<br>";
        //23. log_resultados_impresion
        $sql = "DELETE FROM log_resultados_impresion
        WHERE id_usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "log_resultados_impresion: OK<br>";
        //24. pagos_empresas_credito
        $sql = "DELETE FROM pagos_empresas_credito
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "pagos_empresas_credito: OK<br>";
        //25. resultado_texto
        $sql = "DELETE FROM resultado_texto
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "resultado_texto: OK<br>";
        //26. texto_radiologo
        $sql = "DELETE FROM texto_radiologo
        WHERE `id_usuario` in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "texto_radiologo: OK<br>";
        //27. resultado_estudio
        $sql = "DELETE FROM resultado_estudio
        WHERE id_componente in (SELECT id FROM componente WHERE `id_sucursal` = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "resultado_estudio: OK<br>";
        //28. orden_estudio
        $sql = "DELETE FROM orden_estudio
        WHERE id_orden in (SELECT id FROM orden WHERE `id_sucursal` = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "orden_estudio: OK<br>";
        //29. componente_numerico
        $sql = "DELETE FROM componente_numerico
        WHERE `id_componente` in (SELECT id FROM componente WHERE `id_sucursal` = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "componente_numerico: OK<br>";
        //30. componente_formula
        $sql = "DELETE FROM componente_formula
        WHERE `id_componente` in (SELECT id FROM componente WHERE `id_sucursal` = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "componente_formula: OK<br>";
        //31. componente_lista
        $sql = "DELETE FROM componente_lista
        WHERE `id_componente` in (SELECT id FROM componente WHERE `id_sucursal` = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "componente_lista: OK<br>";
        //32. componente_tabla
        $sql = "DELETE FROM componente_tabla
        WHERE `id_componente` in (SELECT id FROM componente WHERE `id_sucursal` = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "componente_tabla: OK<br>";
        //33. componente
        $sql = "DELETE FROM componente
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "componente: OK<br>";
        //34. descuento
        $sql = "DELETE FROM descuento
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "descuento: OK<br>";
        //35. orden
        $sql = "DELETE FROM orden
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "orden: OK<br>";
        //36. doctor
        $sql = "DELETE FROM doctor
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "doctor: OK<br>";
        //37. paciente
        $sql = "DELETE FROM paciente
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "paciente: OK<br>";
        //38. empresa
        $sql = "DELETE FROM empresa
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "empresa: OK<br>";
        //39. lista_precios_estudio
        $sql = "DELETE FROM lista_precios_estudio 
        WHERE `id_lista_precio` in (SELECT id FROM lista_precios WHERE `id_sucursal` = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "lista_precios_estudio: OK<br>";
        //40. lista_precios
        $sql = "DELETE FROM lista_precios
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "lista_precios: OK<br>";
        //41. paquete_estudio
        $sql = "DELETE FROM paquete_estudio 
        WHERE `id_paquete` in (SELECT id FROM paquete WHERE `id_sucursal` = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "paquete_estudio: OK<br>";
        //42. paquete
        $sql = "DELETE FROM paquete
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "paquete: OK<br>";
        //43. estudio
        $sql = "DELETE FROM estudio
        WHERE `id_sucursal` in (SELECT id FROM sucursal WHERE id_cliente = '$id_cliente')";
        $this->conexion->setQuery($sql);
        //echo "estudio: OK<br>";
        //44. forma_pago
        $sql = "DELETE FROM forma_pago
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "forma_pago: OK<br>";
        //45. indicaciones
        $sql = "DELETE FROM indicaciones
        WHERE `id_sucursal` in (SELECT id FROM sucursal WHERE id_cliente = '$id_cliente')";
        $this->conexion->setQuery($sql);
        //echo "indicaciones: OK<br>";
        //46. corte
        $sql = "DELETE FROM corte
        WHERE `id_usuario` in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "corte: OK<br>";
        //48. tema
        $sql = "DELETE FROM tema
        WHERE id_usuario in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);
        //echo "tema: OK<br>";
        //49. usuario

        $sql = "DELETE FROM permisos_usuario
        WHERE `id_usuario`in (SELECT id FROM usuario WHERE id_sucursal = '$id_sucursal')";
        $this->conexion->setQuery($sql);

        $sql = "DELETE FROM usuario
        WHERE `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "usuario: OK<br>";
        //50. formato_lab
        $sql = "DELETE FROM formato_lab
        WHERE `id_cliente` = '$id_cliente'";
        $this->conexion->setQuery($sql);
        //echo "formato_lab: OK<br>";
        //51. referencia
        $sql = "DELETE FROM referencia
        WHERE  `id_sucursal` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
        //echo "referencia: OK<br>";
        //52. ticket_detalle
        $sql = "DELETE FROM ticket_detalle
        WHERE `id_cliente` = '$id_cliente'";
        $this->conexion->setQuery($sql);
        //echo "ticket_detalle: OK<br>";
        //43. estudio
        $sql = "DELETE FROM estudio
        WHERE `id_sucursal` = $id_sucursal";
        $this->conexion->setQuery($sql);

        //45. indicaciones
        $sql = "DELETE FROM indicaciones
        WHERE `id_sucursal` = $id_sucursal";
        $this->conexion->setQuery($sql);
        //53. sucursal
        $sql = "DELETE FROM sucursal
        WHERE `id` = '$id_sucursal'";
        $this->conexion->setQuery($sql);
    }

    function getUsuarios($id_sucursal) {

        $sql = "SELECT * 
            FROM usuario
            WHERE id_sucursal = $id_sucursal ";

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
            //LISA DE PRECIOS
            $lista_precios = "INSERT INTO lista_precios (no, nombre, alias, id_sucursal)
            SELECT no, nombre, alias, $id_sucursal
            FROM lista_precios
            WHERE `id_sucursal` = '$id_sucursal_origen' ";
            $this->conexion->setQuery($sql);

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
                    $id_estudio_lista = $fila->id;
                    $id_paquete = $fila->id_paquete;
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

    function getOrdenes($id_sucursal, $ini, $fin) {

        $sql = "SELECT * 
            FROM orden
            WHERE consecutivo >= $ini AND consecutivo <= $fin AND id_sucursal = $id_sucursal ";

        $data = $this->conexion->getQuery($sql);
        return $data;
    }

    function eliminarOrden($id_sucursal, $ini, $fin) {

        $data = $this->getOrdenes($id_sucursal, $ini, $fin);

        $sql = "DELETE FROM orden
        WHERE consecutivo >= $ini AND consecutivo <= $fin AND id_sucursal = $id_sucursal ";
        $this->conexion->setQuery($sql);

        foreach ($data AS $fila) {
            $sql = "DELETE FROM paciente
            WHERE id = '$fila->id_paciente'";
            $this->conexion->setQuery($sql);
        }
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
