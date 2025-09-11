<?php

require_once('../../model/admision/Pacientes.php');

class Paciente { 

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'ordenes') {
            $this->ordenes();
        } else if ($opc == 'registro') {
            $this->registro();
        } else if ($opc == 'get-paciente') {
            $this->paciente();
        } else if ($opc == 'get-orden') {
            $this->orden();
        } else if ($opc == 'estudios-paquetes') {
            $this->estudiosPaquetes();
        } else if ($opc == 'get-instrucciones') {
            $this->instrucciones();
        } else if ($opc == 'modificacion') {
            $this->modificacion();
        } else if ($opc == 'modificacion_generales') {
            $this->modificacionGenerales();
        } else if ($opc == 'registro-tarjeta') {
            $this->registroTarjeta();
        } else if ($opc == 'search-tarjeta') {
            $this->searchTarjeta();
        } else if ($opc == 'cancelar-orden') {
            $this->cancelarOrden();
        } else if ($opc == 'activar-orden') {
            $this->activarOrden();
        }else if ($opc == 'buscar_paciente') {
            $this->buscarPacienteXNombre();
        }else if($opc=='modificacion_maestro'){
            $this->modificacionPacienteUsuarioMaestro();
        }
    }

    function modificacionPacienteUsuarioMaestro(){
        $pacientes = new Pacientes();
        $data = $pacientes->modificacionPacienteUsuarioMaestroM();  
        header("Location: /modificacion-paciente-admin?msg=ok");
    }

    function ordenes() {

        $fecha_ini = $_REQUEST["fecha_ini"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $palabra = $_REQUEST["palabra"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $pacientes = new Pacientes();
        $data = $pacientes->getOrdenes($palabra, $fecha_ini, $fecha_fin, $id_sucursal);  
        
        foreach($data as $row=>$item){ 
            $data[$row]->orden_maquila="-";
            if($item->sucursal_maquila!=null){
                 $orden = $pacientes->getOrdenMaquila("matriz",$item->sucursal_maquila,$item->consecutivo);
                 if($orden)
                    $data[$row]->orden_maquila=$orden[0]->om;
            }
            elseif($item->consecutivo_matriz!=null){
                $orden = $pacientes->getOrdenMaquila("sucursal",121,$item->consecutivo_matriz);
                 if($orden)
                    $data[$row]->orden_maquila=$orden[0]->om;
            }

            
        }


        $pacientes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function paciente() {

        $id_paciente = $_REQUEST["id_paciente"];

        $pacientes = new Pacientes();
        $data = $pacientes->getPaciente($id_paciente);

        $pacientes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function orden() {

        $id_orden = $_REQUEST["id_orden"];

        $pacientes = new Pacientes();
        $data = $pacientes->getOrden($id_orden); 

        $pacientes->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function estudiosPaquetes() {

        $estudio = $_REQUEST["estudio"];
        $id_sucursal = $_REQUEST["id_sucursal"];
        $id_empresa = $_REQUEST["id_empresa"];
        $modalidad= $_REQUEST["modalidad"];

        $pacientes = new Pacientes();
        $data = $pacientes->estudiosPaquetesDescripcion($estudio, $id_sucursal, $id_empresa);
        $datos = [];
        foreach ($data AS $row) {
            //--------------Si el paciente es de maquila
            if($modalidad==1){
                $precioMaquila="NO";
                $precioMaquilaE=$pacientes->getPrecioMaquilaEstudio($row->id,$id_sucursal);
                if($precioMaquilaE[0]->precio_maquila>0){
                    $id_estudioM=$row->id;
                    if($precioMaquilaE[0]->id_paquete_matriz!=null)// Si el estudio es un paquete
                        $id_estudioM=$precioMaquilaE[0]->id_paquete_matriz;
                    $precioMaquila= $precioMaquilaE[0]->precio_maquila.' &nbsp;&nbsp;&nbsp; <input type="checkbox" data-id="'.$id_estudioM.'" data-precio="'.$precioMaquilaE[0]->precio_maquila.'" class="cmaquila"  checked>';
                }
            }
            //---------------------------------------------
            $datos[] = array(
                "value" => $row->alias,
                "label" => $row->nombre,
                "id" => $row->id,
                "tipo" => $row->tipo,
                "precio" => number_format($row->precio, 2),
                "precio_neto" => number_format($row->precio_neto, 2),
                "fecha_entrega" => $row->fecha_entrega,
                "paquete" => $pacientes->getPaquete($row->id, $id_sucursal, $id_empresa),
                "porcentaje" => $row->porcentaje,
                "precio_maquila"=> $precioMaquila,
                "dato_incorrecto"=>$row->id,
            );
        }

        $pacientes->close();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
    }

    function instrucciones() {
        $alias = $_REQUEST["alias"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $pacientes = new Pacientes();
        $data = $pacientes->getInstrucciones($alias, $id_sucursal);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function registro() {
        
        $data = array(
            "id_paciente" => $_REQUEST["id_paciente"],
            "tipo_edad" => $_REQUEST["tipo_edad"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
            "id_orden" => $_REQUEST["id_orden"],
            "paterno" => $_REQUEST["paterno"],
            "materno" => $_REQUEST["materno"],
            "nombre" => $_REQUEST["nombre"],
            "fecha_nac" => $_REQUEST["fecha_nac"],
            "edad" => $_REQUEST["edad"],
            "sexo" => $_REQUEST["sexo"],
            "direccion" => $_REQUEST["direccion"],
            "cpEmail" => $_REQUEST["cpEmail"],
            "tel" => $_REQUEST["tel"],
            "medico" => strtoupper($_REQUEST["medico"]),
            "id_doctor" => $_REQUEST["id_doctor"] != "" ? $_REQUEST["id_doctor"] : "NULL",
            "id_empresa" => $_REQUEST["id_empresa"] != "" ? $_REQUEST["id_empresa"] : "NULL",
            "filiacion" => $_REQUEST["filiacion"],
            "fur" => $_REQUEST["fur"],
            "fud" => $_REQUEST["fud"],
            "nuevat" => $_REQUEST["nuevat"],
            "notarjeta" => $_REQUEST["notarjeta"],
            "saldo" => $_REQUEST["saldo"],
            "abono" => $_REQUEST["abono"], 
            "cliente" => $_REQUEST["cliente"],
            "rfc" => $_REQUEST["rfc"],
            "domicilio" => $_REQUEST["domicilio"],
            "cp" => $_REQUEST["cp"],
            "mail" => $_REQUEST["mail"],
            "cfdi" => $_REQUEST["id_cfdi"] != "" ? $_REQUEST["id_cfdi"] : "NULL", //$_REQUEST["cfdi"],
            "observaciones" => $_REQUEST["observaciones"],
            "ordenmatriz" => $_REQUEST["ordenmatriz"],
            "codigo" => $_REQUEST["codigo"],
            "maquila" => $_REQUEST["estudio_maquila"],
            "precio_maquila" => $_REQUEST["precio_maquila"], 
            "id_descuento" => $_REQUEST["id_descuento"] != "" ? $_REQUEST["id_descuento"] : "NULL",
            "aumento" => $_REQUEST["aumento"],
            "total" => $_REQUEST["total"],
            "descuento" => $_REQUEST['descuento'],
            "codigo" => $_REQUEST["codigo"],
            "precio_publico" => $_REQUEST["precio_publico"],
            "precio_neto" => $_REQUEST["precio_neto"],
            "fecha_entrega" => $_REQUEST["fecha_entrega"],
            "paquete" => $_REQUEST["paquete"],
            "tipo_orden" => $_REQUEST["tipo_orden"],
            "tipo_cliente" => $_REQUEST["tipo_cliente"],
            "pdescuento" => $_REQUEST["p_descuento"],
            "cotizacion" => $_REQUEST["cotizacion"] == "on" ? "1" : "0" 
        );
  

        $pacientes = new Pacientes();

        $data['ordenmatriz']='null';
        $data['orden_colon']='null';
        if($data['cotizacion']==0 && array_sum($data['maquila'])>0){
            $respuesta = $pacientes->addPacienteMaquila($data); 
            $data['ordenmatriz']=$respuesta['codigo'];
            $data['id_paciente']=$respuesta['id_pacientem'];
            $data['orden_colon']=$respuesta['codigo_colon'];
        }
        
        $data = $pacientes->addPaciente($data);  
        $pacientes->close();
        if ($data["credito"] == 1) {
            header("Location: /registro-paciente?msg=ok");
        } else if ($data["cotizacion"] == 1) {
            header("Location: /cotizaciones?msg=ok");
        } else if ($data["codigo"] == "") {
            header("Location: /registro-paciente");
        } else {
            header("Location: /caja?codigo=" . $data["codigo"]);
        }
    }

    function modificacion() {
        //  var_dump($_REQUEST);
        
        $data = array(
            "id_paciente" => $_REQUEST["id_paciente"],
            "tipo_edad" => $_REQUEST["tipo_edad"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
            "id_orden" => $_REQUEST["id_orden"],
            "consecutivo" => $_REQUEST["consecutivo"],
            "paterno" => $_REQUEST["paterno"],
            "materno" => $_REQUEST["materno"],
            "nombre" => $_REQUEST["nombre"],
            "fecha_nac" => $_REQUEST["fecha_nac"],
            "edad" => $_REQUEST["edad"],
            "sexo" => $_REQUEST["sexo"],
            "direccion" => $_REQUEST["direccion"],
            "cpEmail" => $_REQUEST["cpEmail"],
            "tel" => $_REQUEST["tel"],
            "medico" => $_REQUEST["medico"],
            "id_doctor" => $_REQUEST["id_doctor"] != "" ? $_REQUEST["id_doctor"] : "NULL",
            "id_empresa" => $_REQUEST["id_empresa"] != "" ? $_REQUEST["id_empresa"] : "NULL",
            "filiacion" => $_REQUEST["filiacion"],
            "fur" => $_REQUEST["fur"],
            "fud" => $_REQUEST["fud"],
            "nuevat" => $_REQUEST["nuevat"],
            "notarjeta" => $_REQUEST["notarjeta"],
            "saldo" => $_REQUEST["saldo"],
            "abono" => $_REQUEST["abono"],
            "cliente" => $_REQUEST["cliente"],
            "rfc" => $_REQUEST["rfc"],
            "domicilio" => $_REQUEST["domicilio"],
            "cp" => $_REQUEST["cp"],
            "mail" => $_REQUEST["mail"],
            "cfdi" => $_REQUEST["id_cfdi"] != "" ? $_REQUEST["id_cfdi"] : "NULL", //$_REQUEST["cfdi"],
            "observaciones" => $_REQUEST["observaciones"],
            "tipo_orden" => $_REQUEST["tipo_orden"],
            "ordenmatriz" => $_REQUEST["ordenmatriz"],
            "codigo" => $_REQUEST["codigo"],
            "id_descuento" => $_REQUEST["id_descuento"] != "" ? $_REQUEST["id_descuento"] : "NULL",
            "aumento" => $_REQUEST["aumento"],
            "total" => $_REQUEST["total"],
            "codigo" => $_REQUEST["codigo"],
            "maquila" => $_REQUEST["estudio_maquila"],
            "precio_maquila" => $_REQUEST["precio_maquila"], 
            "enviado_maquila" => $_REQUEST["enviado_maquila"], 
            "precio_publico" => $_REQUEST["precio_publico"],
            "id_detalle" => $_REQUEST["id_detalle"],
            "precio_neto" => $_REQUEST["precio_neto"],
            "fecha_entrega" => $_REQUEST["fecha_entrega"],
            "paquete" => $_REQUEST["paquete"],
            "cotizacion" => $_REQUEST["cotizacion"] == "on" ? "1" : "0"
        );

        $pacientes = new Pacientes();
        if($_REQUEST["ordenmatriz"]!="" && $_REQUEST["ordenmatriz"]!=null){
            $pacientes->modificacionMaquilaPaciente($data);
        }    

        
        $pacientes->modificacionPaciente($data);
        $pacientes->close();

        header("Location: /modificacion-paciente?msg=ok");
    }


    function modificacionGenerales() {
        //  var_dump($_REQUEST);

        $data = array(
            "id_paciente" => $_REQUEST["id_paciente"],
            "tipo_edad" => $_REQUEST["tipo_edad"],
            "id_sucursal" => $_REQUEST["id_sucursal"],
            "id_orden" => $_REQUEST["id_orden"],
            "consecutivo" => $_REQUEST["consecutivo"],
            "paterno" => $_REQUEST["paterno"],
            "materno" => $_REQUEST["materno"],
            "nombre" => $_REQUEST["nombre"],
            "fecha_nac" => $_REQUEST["fecha_nac"],
            "edad" => $_REQUEST["edad"],
            "sexo" => $_REQUEST["sexo"],
            "direccion" => $_REQUEST["direccion"],
            "cpEmail" => $_REQUEST["cpEmail"],
            "tel" => $_REQUEST["tel"],
            "medico" => $_REQUEST["medico"],
            "id_doctor" => $_REQUEST["id_doctor"] != "" ? $_REQUEST["id_doctor"] : "NULL"
        );

        $pacientes = new Pacientes();

        $pacientes->modificacionPacienteGenerales($data);
        $pacientes->close();

        header("Location: /modificacion-paciente-generales?msg=ok");
    }

    function registroTarjeta() {
        $tarjeta = $_REQUEST["tarjeta"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $pacientes = new Pacientes();
        $data = $pacientes->addTarjeta($tarjeta, $id_sucursal);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function searchTarjeta() {
        $tarjeta = $_REQUEST["tarjeta"];
        $id_sucursal = $_REQUEST["id_sucursal"];

        $pacientes = new Pacientes();
        $data = $pacientes->getTarjeta($tarjeta, $id_sucursal);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    function cancelarOrden() {
        $id_orden = $_REQUEST["id_orden"];

        $pacientes = new Pacientes();
        $pacientes->cancelarOrden($id_orden);

        $pacientes->close();
        header("Location: /modificacion-paciente?msg=ok");
    }

    function activarOrden() {
        $id_orden = $_REQUEST["id_orden"];

        $pacientes = new Pacientes();
        $pacientes->activarOrden($id_orden);

        $pacientes->close();
        header("Location: /modificacion-paciente?msg=ok");
    }

    function buscarPacienteXNombre(){ 
        $paterno=$_REQUEST['paterno'];
        $materno=$_REQUEST['materno'];
        $nombre=$_REQUEST['nombre'];
        $fecha_nacimiento=$_REQUEST['fecha_nacimiento'];
        $id_sucursal=$_REQUEST['id_sucursal'];
        $pacientes = new Pacientes();
        $pacientes=$pacientes->buscarPacienteXNombreM($paterno,$materno,$nombre,$fecha_nacimiento,$id_sucursal);
        
        foreach ($pacientes AS $row=>$item) {
            echo '<tr>
                <td>'.$item->expediente.'</td>
                <td>'.$item->numero_ordenes.'</td>
                <td>'.$item->nombre.'</td>
                <td>'.$item->fecha_nac.'</td>
                <td>'.$item->fecha_registro.'</td>';
            if($row==0)
                echo '<td><button data-id="'.$item->id.'" class="btn btn-success btn-sm cargar-paciente">Seleccionar</button></td>';
            else
                echo '<td>-</td>';
            echo '</tr>';
        }
    }

}

new Paciente();

