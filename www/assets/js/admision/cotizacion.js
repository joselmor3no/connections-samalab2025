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


$(document).on('click', '.whatsapp', function () {
    var codigo = this.dataset.codigo;
    var expediente = this.dataset.expediente;
    var paciente = this.dataset.paciente;
    var telPac = this.dataset.telefono;

    var sucursal = $("#sucursal").val();
    var telSuc = $("#telefono").val();

    var mensaje = "%c2%a1Hola+*" + paciente + "*!+%0d%0a%0d%0aHas+recibido+una+notificaci%c3%b3n+de+*" + sucursal + "*%0d%0a%0d%0aIngresa+a+https%3a%2f%2f" + document.domain + "%2fcotizacion%2fcotizacion-carta%3fcodigo%3d" + codigo + "%26expediente%3d" + expediente + "+para+visualizar+t%c3%ba+cotizaci%c3%b3n+en+l%c3%adnea.%0d%0a%0d%0a_No+responda+a+este+mensaje%2c+ha+sido+enviado+de+manera+autom%c3%a1tica.+Si+tiene+dudas+favor+de+comunicarse+con+" + sucursal + "+al+tel%c3%a9fono+" + telSuc + "_%0d%0a%0d%0aMensaje+generado+por+Connections.";
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
                url: "controller/admision/Cotizacion?opc=envio-correo",
                data: {id: id, correo: result.value, expediente: expediente, paciente: paciente, sucursal: sucursal, telSuc: telSuc, img: img},
                success: function (data) {
                    if (data == 'ok') {
                        Swal.fire("Correo enviado!", "El correo electrónico con la cotización fue enviada al paciente correctamente", "success")
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