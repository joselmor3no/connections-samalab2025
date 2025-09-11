<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/model/catalogos/Empresas.php');

class Empresa {

    function __construct() {

        if (isset($_REQUEST["opc"])) {
            $opc = $_REQUEST["opc"];
            if ($opc == 'registro') {
                $this->registro();
            } else if ($opc == 'alias') {
                $this->alias();
            } else if ($opc == 'delete') {
                $this->delete();
            } else if ($opc == 'lista_empresas') {
                $this->listaEmpresasSelect($_REQUEST['fecha_inicial'], $_REQUEST['fecha_final']); 
            } else if ($opc == 'actualiza_clase_empresa'){
                $this->actualizaClaseEmpresa();
            }
        }
    }

    function actualizaClaseEmpresa(){
        $empresa = new Empresas();
        $data= array(
            "id_empresa" => $_REQUEST['id_empresa'],
            "id_clase" => $_REQUEST['id_clase'],
            "tipo_descuento" => $_REQUEST['tipo_descuento'],
            "porcentaje" => $_REQUEST['porcentaje']
        );
        echo $empresa->actualizaClaseEmpresaM($data);

    }

    function registro() {
        $data = array(
            "id" => $_REQUEST["id_empresa"],
            "alias" => $_REQUEST["alias"],
            "nombre" => $_REQUEST["nombre"],
            "direccion" => $_REQUEST["direccion"],
            "estado" => $_REQUEST["estado"],
            "ciudad" => $_REQUEST["ciudad"],
            "cp" => $_REQUEST["cp"],
            "tel" => $_REQUEST["tel"],
            "cel" => $_REQUEST["cel"],
            "contacto" => $_REQUEST["contacto"],
            "promotor" => $_REQUEST["promotor"],
            "tipo_sucursal" => $_REQUEST["tipo_sucursal"] != "" ? $_REQUEST["tipo_sucursal"] : "''",
            "id_lista_precios" => $_REQUEST["id_lista_precios"] != "" ? $_REQUEST["id_lista_precios"] : "NULL",
            "codigo" => $_REQUEST["codigo"],
            "expediente" => $_REQUEST["expediente"],
            "pass" => $_REQUEST["pass"],
            "porcentaje" => $_REQUEST["porcentaje"] != "" ? $_REQUEST["porcentaje"] : "0",
            "porcentaje_pago" => $_REQUEST["porcentaje_pago"] != "" ? $_REQUEST["porcentaje_pago"] : "0",
            "aumento" => $_REQUEST["aumento"] != "" ? $_REQUEST["aumento"] : "0",
            "email" => $_REQUEST["email"],
            "credito" => $_REQUEST["credito"] == "on" ? "1" : "0",
            "laboratorio" => $_REQUEST["laboratorio"] == "on" ? "1" : "0",
            "logo" => $_REQUEST["logo"] == "on" ? "1" : "0",
            "inactivo" => $_REQUEST["inactivo"] == "on" ? "1" : "0",
            "id_sucursal" => $_REQUEST["id_sucursal"],
        );

        $empresa = new Empresas();
        if ($_REQUEST["id_empresa"] == "") {
            $empresa->addEmpresa($data);
        } else {
            $empresa->editEmpresa($data);
        }

        $empresa->close();
        header("Location: /empresas?msg=ok");
    }

    function alias() {

        $alias = $_REQUEST["alias"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $empresas = new Empresas();
        $data = $empresas->aliasEmpresa($alias, $id_sucursal);

        $empresa->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function delete() {
        $id_empresa = $_REQUEST["id_empresa"];

        $empresas = new Empresas();
        $empresas->deleteEmpresa($id_empresa);

        $empresa->close();
        header("Location: /empresas?msg=ok");
    }

    public function listaEmpresasSelect($fechaInicial, $fechaFinal) {
        $empresas = new Empresas();
        $res = $empresas->listaEmpresasSelectM($fechaInicial, $fechaFinal);
        foreach ($res AS $row => $item) {
            echo '<option value="' . $item->id . '">' . $item->nombre . '</option>';
        }
        $empresas->close();
    }

}

new Empresa();

