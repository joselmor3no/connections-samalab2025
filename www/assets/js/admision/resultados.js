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
        url: "controller/laboratorio/Reporte?opc=imprimir-reporte-paciente",
        data: {codigo: codigo, expediente: expediente, id_sucursal: id_sucursal},
        dataType: "json",
        success: function (data) {
            var ruta = $("#ruta").val();

            setTimeout(function () {
                $("#loading").modal("hide");
            }, 600);

            if (data.length) {
                toastr.error("Sin reportes disponibles");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
            }

            if (data.bh) {
                window.open("/reportes/" + ruta + "/reporte-biometria-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1");
            }

            if (data.ego) {
                window.open("/reportes/" + ruta + "/reporte-examen-orina-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1");
            }

            if (data.estandar) {
                window.open("/reportes/" + ruta + "/reporte-estandar-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1");
            }

            if (data.paquete) {
                window.open("/reportes/" + ruta + "/reporte-paquete-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1");
            }

            if (data.texto) {
                window.open("/reportes/" + ruta + "/reporte-texto-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1");
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

$(document).on('click', '.whatsapp', function () {
    var expediente = this.dataset.expediente;
    var paciente = this.dataset.paciente;
    var telPac = this.dataset.telefono; 

    var sucursal = $("#sucursal").val();
    var telSuc = $("#telefono").val();

    var mensaje = "%C2%A1Hola%20%2A" + paciente + "%2A%21%20%0A%0AHas%20recibido%20una%20notificaci%C3%B3n%20de%20%2A" + sucursal + "%2A%0A%0AIngresa%20a%20https%3A%2F%2F" + document.domain + '%2FPacientes%2Fcontroller%2FAcceso%3Fopc%3Dexpediente%26user%3D' + expediente + " para%20ver%20los%20resultados%20de%20tus%20estudios%20de%20laboratorio%20en%20l%C3%ADnea.%0A%0A_No%20responda%20a%20este%20mensaje%2C%20ha%20sido%20enviado%20de%20manera%20autom%C3%A1tica. Si%20tiene%20dudas%20favor%20de%20comunicarse%20con%20" + sucursal + "%20al%20tel%C3%A9fono%20" + telSuc + "_%0A%0AMensaje%20generado%20por%20Connections.";
    Swal.fire({
        title: 'N&uacute;mero de WhatsApp',
        input: 'text',
        inputValue: telPac,
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        console.log(result);
        if (result.isConfirmed && result.value != "") {
            var win = window.open('https://api.whatsapp.com/send?phone=52' + result.value + '&text=' + mensaje + '', '_blank');

        } else if (result.value == "") {
            Swal.fire('Debes ingresar un n&uacute;mero telef&oacute;nico');
        }
    });
});

$(document).on('click', '.mail', function () {
    var id = this.dataset.id;
    var expediente = this.dataset.expediente;
    var paciente = this.dataset.paciente;
    var correo = this.dataset.correo;

    var sucursal = $("#sucursal").val();
    var telSuc = $("#telefono").val();
    var img = $("#img").val();

    Swal.fire({
        title: 'Correo electr&oacute;nico',
        input: 'text',
        inputValue: correo,
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {

        if (result.isConfirmed && result.value != "") {
            //ajax
            $.ajax({
                type: "POST",
                url: "controller/admision/Resultado?opc=envio-correo",
                data: {id: id, correo: result.value, expediente: expediente, paciente: paciente, sucursal: sucursal, telSuc: telSuc, img: img},
                success: function (data) {
                    if (data == 'ok') {
                        Swal.fire("Correo enviado!", "El correo electrónico con los resultados fueron enviados al paciente correctamente", "success")
                    } else {
                        Swal.fire("Error!", "No pudo enviarse el correo electronico, intente nuevamente", "error")
                    }
                }
            });

        } else if (result.value == "") {
            Swal.fire('Debes ingresar el correo electr&oacute;nico');
        }
    });
});