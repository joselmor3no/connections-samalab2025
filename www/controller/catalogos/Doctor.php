<?php

require_once('../../model/catalogos/Doctores.php');

class Doctor {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'alias') {
            $this->alias();
        } else if ($opc == 'delete') {
            $this->delete();
        } else if ($opc == 'doctores') {
            $this->doctores();
        }
        else if($opc=='registro-zonas'){
            $this->registroZonas();
        }
        else if($opc=='delete-zona'){
            $this->deleteZonas();
        }
        else if($opc=='registro-promotores'){
            $this->registroPromotores();
        }
        else if($opc=='delete-promotor'){
            $this->deletePromotores();
        }
        else if($opc=='busqueda-doctor'){
            $this->buscarDoctoresCatalogo();
        }
        else if($opc=='sugerencias_doctores'){
            $this->buscarSugerenciasDoctores();
        }
        else if($opc=='consecutivo_prefijo'){
            $this->consecutivoPrefijo();
        }
        else if($opc=='intercambio_promotor'){
            $this->intercambioPromotor();
        }
    }

    function intercambioPromotor(){
        $doctores = new Doctores();        
        if($_REQUEST['swaccion']=="intercambio"){
            $doctores->actualizaPromotorM($_REQUEST['sworigen'],"-1",$_REQUEST['swsucursal']); 
            $doctores->actualizaPromotorM($_REQUEST['swdestino'],$_REQUEST['sworigen'],$_REQUEST['swsucursal']); 
            $doctores->actualizaPromotorM("-1",$_REQUEST['swdestino'],$_REQUEST['swsucursal']);  
        }
        else if($_REQUEST['swaccion']=="unilateral"){
            $doctores->actualizaPromotorM($_REQUEST['sworigen'],$_REQUEST['swdestino'],$_REQUEST['swsucursal']);
        }
        header("Location: /zonas-promotores-p?msg=ok");
        $doctores->close();
    }

    function consecutivoPrefijo(){
        $doctores = new Doctores();
        $respuesta=$doctores->consecutivoPrefijoM($_REQUEST['prefijo']);        
        echo floatval($respuesta[0]->consecutivo_prefijo)+1;
    }

    function buscarSugerenciasDoctores(){
        $doctores = new Doctores();
        $respuesta=$doctores->buscarSugerenciasDoctores($_REQUEST['busqueda'],$_REQUEST['id_sucursal']);
        $opciones="";
        $arregloDatos=[];
        foreach ($respuesta AS $row=>$item) {
            $opciones.='<option>'.$item->nombre.'</option>';
            $arregloDatos[$row]['id']=$item->id;
            $arregloDatos[$row]['alias']=$item->alias;
        }
        $datos=[];
        $datos["datos"]=$arregloDatos;
        $datos["opciones"]=$opciones;
        echo json_encode($datos);
    }

    function buscarDoctoresCatalogo(){
        $doctores = new Doctores();
        $datos=array("paterno"=>$_REQUEST['paterno'],
                     "materno"=>$_REQUEST['materno'],
                     "nombre"=>$_REQUEST['nombre'],
                     "sucursal"=>$_REQUEST['id_sucursal']);
        $respuesta=$doctores->buscarDoctoresCatalogo($datos);
        foreach ($respuesta AS $row=>$item) {
            echo '<tr>
                <td>'.$item->apaterno.'</td>
                <td>'.$item->amaterno.'</td>
                <td>'.$item->nombre.'</td>
            </tr>';
        }
    }

    function deleteZonas(){
        $doctores = new Doctores();
        $doctores->deleteZona($_REQUEST['id_zona']);
        header("Location: /zonas-promotores?msg=ok");
        $doctores->close();
    }

    function registroZonas(){
        $data = array(
            "id" => $_REQUEST["id_zona"],
            "numero" => $_REQUEST["znumero"],
            "nombre" => $_REQUEST["znombre"],
            "id_sucursal" => $_REQUEST["id_sucursal"]
        );
        $doctores = new Doctores();
        if ($_REQUEST["id_zona"] == "-1") {
            $doctores->addZona($data);
        } else {
            $doctores->editZona($data);
        }
        header("Location: /zonas-promotores?msg=ok"); 
        $doctores->close();
    }

    function deletePromotores(){
        $doctores = new Doctores();
        $doctores->deletePromotor($_REQUEST['id_promotor']);
        header("Location: /zonas-promotores-p?msg=ok");
        $doctores->close();
    }

    function registroPromotores(){
        $data = array(
            "id" => $_REQUEST["id_promotor"],
            "numero" => $_REQUEST["pnumero"],
            "nombre" => $_REQUEST["pnombre"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
            "lista_zonas" => $_REQUEST["lista_zonas"],
            "sucursal_destino" => $_REQUEST["sucursalo"]
        );
        $doctores = new Doctores();
        if ($_REQUEST["id_promotor"] == "-1") {
            $doctores->addPromotor($data);
        } else {
            $doctores->editPromotor($data);
        }
        header("Location: /zonas-promotores-p?msg=ok");
        $doctores->close();
    }

    function registro() { 
        $doctores = new Doctores();
        if($_REQUEST["id_sucursal"]==156)
            $_REQUEST["id_sucursal"]=121;
        $consecutivo=$doctores->getUltimoConsecutivoSucursal($_REQUEST["id_sucursal"])[0];
 
        if(!$consecutivo->consecutivo || $consecutivo->consecutivo=="") 
            $consecutivo->consecutivo=1;
        $data = array(
            "id" => $_REQUEST["id_doctor"],
            "prefijo" => $_REQUEST["prefijo"],
            "consecutivo_prefijo" => $_REQUEST["consecutivo_prefijo"],
            "alias" => $_REQUEST["prefijo"].$_REQUEST["consecutivo_prefijo"],
            "nombre" => $_REQUEST["nombre"],
            "apaterno" => $_REQUEST["apaterno"],
            "amaterno" => $_REQUEST["amaterno"],
            "direccion" => $_REQUEST["direccion"],
            "estado" => $_REQUEST["estado"],
            "ciudad" => $_REQUEST["ciudad"],
            "cp" => $_REQUEST["cp"],
            "tel" => $_REQUEST["tel"],
            "cel" => $_REQUEST["cel"],
            "id_especialidad" => $_REQUEST["id_especialidad"] != "" ? $_REQUEST["id_especialidad"] : "NULL",
            "codigo" => $_REQUEST["codigo"],
            "descarga" => $_REQUEST["descarga"],
            "pass" => $_REQUEST["pass"],
            "porcentaje" => $_REQUEST["porcentaje"],
            "porcentaje_imagen" => $_REQUEST["porcentajeI"],
            "sitio" => $_REQUEST["sitio"],
            "email" => $_REQUEST["email"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
            "id_cliente" => $_REQUEST["id_cliente"],
            "id_promotor" =>$_REQUEST["id_promotor"],
            "id_zona" =>$_REQUEST["id_zona"],
            "tipo" => $_REQUEST["tipo"]

        );
        echo "<pre>";
        print_r($_REQUEST);
        echo "</pre>";
        return;
        
        if ($_REQUEST["id_doctor"] == "") {
            $doctores->addDoctor($data);
        } else {
            $doctores->editDoctor($data);
        }

        $doctores->close();
        header("Location: /doctores?msg=ok");
    }

    function alias() {

        $alias = $_REQUEST["alias"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $doctores = new Doctores();
        $data = $doctores->aliasDoctor($alias, $id_sucursal);

        $doctores->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function delete() {
        $id_doctor = $_REQUEST["id_doctor"];

        $doctores = new Doctores();
        $doctores->deleteDoctor($id_doctor);

        $doctores->close();
        header("Location: /doctores?msg=ok");
    }

    function doctores() {

        $id_sucursal = $_REQUEST["id_sucursal"];

        $doctores = new Doctores();
        $data = $doctores->getDoctores($id_sucursal);
        $datos = [];
        foreach ($data AS $row) {
            $datos[] = array(
                "id" => $row->id,
                "value" => $row->alias . " - ".$row->apaterno. ' '. $row->amaterno. ' ' . $row->nombre
            );
        }

        $doctores->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

}

new Doctor();

