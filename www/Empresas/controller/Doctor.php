<?php

require_once('../model/Doctores.php');

class Doctor {

    function __construct() {

        $opc = $_REQUEST["opc"];
        if ($opc == 'doctor-sucursales') {
            $this->agregaDoctorSucursales();
        } 
    }

    function agregaDoctorSucursales() {
        $modelo=new Doctores();
        $modelo->eliminaDoctorSucursal($_REQUEST['id_doctor']);
        $modelo->actualizaDoctorSucursal($_REQUEST['id_doctor'],$_REQUEST['lista_sucursales']);
    }
}

new Doctor();