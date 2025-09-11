/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$(document).ready(function () {
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }
});

$(document).on('click', '.imprimir', function () {

    $("#loading").modal("show");

    var codigo = this.dataset.codigo;
    var expediente = this.dataset.expediente;
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        type: "POST",
        url: "../controller/laboratorio/Reporte?opc=imprimir-reporte-paciente",
        data: {codigo: codigo, expediente: expediente, id_sucursal: id_sucursal},
        dataType: "json",
        success: function (data) {
            var ruta = $("#ruta").val();

            setTimeout(function () {
                $("#loading").modal("hide");
            }, 600);
            
            if (data.bh) {
                window.open("../reportes/"+ruta+"/reporte-biometria-paciente?codigo=" + codigo + "&expediente=" + expediente);
            }

            if (data.ego) {
                window.open("../reportes/"+ruta+"/reporte-examen-orina-paciente?codigo=" + codigo + "&expediente=" + expediente);
            }

            if (data.estandar) {
                window.open("../reportes/"+ruta+"/reporte-estandar-paciente?codigo=" + codigo + "&expediente=" + expediente);
            }

            if (data.paquete) {
                window.open("../reportes/"+ruta+"/reporte-paquete-paciente?codigo=" + codigo + "&expediente=" + expediente);
            }

            if (data.texto) {
                window.open("../reportes/"+ruta+"/reporte-texto-paciente?codigo=" + codigo + "&expediente=" + expediente);
            }




        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});
