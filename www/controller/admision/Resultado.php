<?php

require_once('../../model/admision/Resultados.php');

class Resultado {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'envio-correo') {
            $this->correo();
        }
    }

    function correo() {

        $id = $_REQUEST["id"];
        $paciente = $_REQUEST["paciente"];
        $expediente = $_REQUEST["expediente"];
        $correo = $_REQUEST["correo"];
        $sucursal = $_REQUEST["sucursal"];
        $telSuc = $_REQUEST["telSuc"];
        $img = $_REQUEST["img"];

        $message = '<center><img src="https://' . $_SERVER['SERVER_NAME'] . '/images-sucursales/' . $img . '" style="height: 100px" /></center><br>';

        $message .= "¡Hola! <b>$paciente</b><br><br>
            Has recibido una notificación de <b>$sucursal</b><br><br>
            Ingresa a <a href ='https://" . $_SERVER['SERVER_NAME'] . "/Pacientes/controller/Acceso?opc=expediente&user=$expediente '>https://" . $_SERVER['SERVER_NAME'] . "/Pacientes/controller/Acceso?opc=expediente&user=$expediente</a> para ver los resultados de tus estudios de laboratorio en línea.<br><br>
            <i>No responda a este mensaje, ha sido enviado de manera automática. Si tiene dudas favor de comunicarse con $sucursal al teléfono $telSuc<br><br>
            Mensaje generado por Connections.</i>";


        $message .= "<br><br><br><br><center>	<img src='http://" . $_SERVER['SERVER_NAME'] . "/assets/images/logo.svg' width='100' /></center>
	<center><h1>Connectionslab</h1></center>
	<center>Recuerda que puedes acceder a tu historial en cualquier momento en:</center>
	<center>https://" . $_SERVER['SERVER_NAME'] . "/Pacientes</center>";

        $from = "resultados@connectionslab.net";
        $subject = "Resultado de laboratorio";
        $headers = "From:" . $from . "\r\n";
        $headers .= "Content-type:text/html;chraset=utf-8";


        mail($correo, $subject, $message, $headers);

        $resultados = new Resultados();
        $resultados->addBitacoraCorreo($id, $correo, $message);
        $resultados->close();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode("ok");
    }

}

new Resultado();

