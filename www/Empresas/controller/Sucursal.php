<?php

require_once('../model/Sucursales.php');

class Sucursal {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'delete') {
            $this->delete();
        } else if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'delete-usuario') {
            $this->deleteUsuario();
        } else if ($opc == 'registro-usuario') {
            $this->registroUsuario();
        }
        else if($opc == 'lista_estudio'){
            $this->listaEstudioBusqueda();
        }
        else if($opc == 'lista_estudio_sucursal'){
            $this->listaEstudioSucursal();
        }
        else if($opc=='actualiza_lpmaquila'){
            $this->actualizaListaPreciosMaquila();
        }
        else if($opc=='lista_paquetes_sucursal'){
            $this->listaPaquetesSucursal();
        }
        else if($opc=='elimina_estudio_lp'){
            $this->elimnaEstudioLPSucursal();
        }
    }

    function elimnaEstudioLPSucursal(){
        $sucursales = new Sucursales();
        $respuesta=$sucursales->elimnaEstudioLPSucursalM($_REQUEST['tipo'],$_REQUEST['id_sucursal'],$_REQUEST['id']);
        return $respuesta;
    }

    function listaPaquetesSucursal(){
        $sucursales = new Sucursales();
        $respuestap=$sucursales->listaPaquetesSucursalM($_REQUEST['id_sucursal']);
        $listaPaquete='<option value="'.$item->id.'">'.$item->nombre.'</option>';
        foreach ($respuestap as $row => $item) {
            $listaPaquete.='<option value="'.$item->id.'">'.$item->nombre.'</option>';
        }
        $datos['lista_paquete']=$listaPaquete;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo json_encode($datos);
    }

    function actualizaListaPreciosMaquila(){
        $sucursales = new Sucursales();
        $respuesta=$sucursales->actualizaListaPreciosMaquilaM();
    }

    function listaEstudioSucursal(){
        $sucursales = new Sucursales();
        $respuesta=$sucursales->listaEstudioSucursalM($_REQUEST['id_sucursal']);
        $datos=[];
        $tr="";
        foreach ($respuesta as $row => $item) {
            $tr.= '<tr>
                <td>'.($item->nombre_estudio).'</td>
                <td>'.$item->no_estudio.'</td>
                <td>'.($item->paquete_sucursal).'</td>
                <td>'.$item->precio_publico.'</td>
                <td>'.$item->precio_maquila.'</td>
                <td><button data-tipo="'.$item->tipo.'" data-sucursal="'.$_REQUEST['id_sucursal'].'" data-id="'.$item->id.'" class="btn btn-danger btn-sm elimina-ep-lp">Eliminar</button></td>
            </tr>';
        }

        $respuestap=$sucursales->listaPaquetesSucursalM($_REQUEST['id_sucursal']);
        $listaPaquete='<option value="">Seleccione...</option>';

        foreach ($respuestap as $row => $item) {
            $listaPaquete.='<option value="'.$item->id.'">'.$item->nombre.'</option>';
        }
        $datos['tabla']=$tr;
        $datos['lista_paquete']=$listaPaquete;
        $datos['id_sucursal']=$_REQUEST['id_sucursal'];
        header( 'Content-Type: application/json; charset=utf-8' );
        echo json_encode($datos);

    }

    function listaEstudioBusqueda(){
        $sucursales = new Sucursales();
        $respuesta=$sucursales->listaEstudioBusqueda($_REQUEST['busqueda'],$_REQUEST['id_sucursal']);
        $opciones="";
        $datos=[];
        foreach ($respuesta as $row => $item) {
            $opciones.='<option>'.($item->nombre_estudio).'</option>';
            $datos[$row]['id']=$item->id;
            $datos[$row]['codigo']=$item->no_estudio;
            $datos[$row]['nombre']=($item->nombre_estudio);
            $datos[$row]['id_paquete']=($item->paquete_matriz);
            $datos[$row]['codigo_paquete']=($item->codigo_paquete);
            $datos[$row]['nombre_paquete']=($item->paquete_matriz_nombre);
            $datos[$row]['precio_publico']=$item->precio_publico;
            $datos[$row]['id_sucursal']=$_REQUEST['id_sucursal'];
        }
        $res['datos']=$datos;
        $res['opciones']=$opciones;
        
        echo json_encode($res);
    }

    function delete() {
        $id_sucursal = $_REQUEST["id_sucursal"];

        $sucursales = new Sucursales();
        $sucursales->deleteSucursal($id_sucursal);

        $sucursales->close();
        header("Location: /Empresas/sucursales?msg=ok");
    }

    function registro() {

        $data = array(
            "id" => $_REQUEST["id_sucursal"],
            "codigo" => $_REQUEST["codigo"],
            "nombre" => $_REQUEST["nombre"],
            "direccion" => $_REQUEST["direccion"],
            "direccion2" => $_REQUEST["direccion2"],
            "estado" => $_REQUEST["estado"] != "" ? $_REQUEST["estado"] : "NULL",
            "ciudad" => $_REQUEST["ciudad"] != "" ? $_REQUEST["ciudad"] : "NULL",
            "tel1" => $_REQUEST["tel1"],
            "tel2" => $_REQUEST["tel2"],
            "email" => $_REQUEST["email"],
            "quimico" => $_REQUEST["quimico"],
            "cedula" => $_REQUEST["cedula"],
            "quimico_aux" => $_REQUEST["quimico_aux"],
            "cedula_aux" => $_REQUEST["cedula_aux"],
            "inicio_urgencias" => $_REQUEST["inicio_urgencias"],
            "fin_urgencias" => $_REQUEST["fin_urgencias"],
            "impresion" => $_REQUEST["impresion"],
            "aumento_urgencias" => $_REQUEST["aumento_urgencias"],
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        // var_dump($data);

        $sucursales = new Sucursales();
        if ($_REQUEST["id_sucursal"] == "") {
            $sucursales->addSucursal($data);
        } else {
            $sucursales->editSucursal($data);
        }

        $sucursales->close();
        header("Location: /Empresas/sucursales?msg=ok");
    }

    function registroUsuario() {

        $data = array(
            "id" => $_REQUEST["id_usuario"],
            "nombre" => $_REQUEST["nombre"],
            "codigo" => $_REQUEST["codigo"],
            "usuario" => $_REQUEST["usuario"],
            "password" => $_REQUEST["password"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
            "id_cliente" => $_REQUEST["id_cliente"],
        );

        // var_dump($data);

        $sucursales = new Sucursales();
        if ($_REQUEST["id_usuario"] == "") {
            $sucursales->addUsuario($data);
        } else {
            $sucursales->editUsuario($data);
        }

        $sucursales->close();
        header("Location: /Empresas/usuarios?msg=ok");
    }

}

new Sucursal();

