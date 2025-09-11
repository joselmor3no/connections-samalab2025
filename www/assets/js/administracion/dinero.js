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
(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    $("#loading").modal("show");
                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

$(document).on('blur', '#no_tarjeta', function () {

    var tarjeta = $("#no_tarjeta").val();

    /*if (tarjeta == "") {
     toastr.error("Favor de capturar Número de Tarjeta");
     $('#toast-container').addClass('toast-top-center');
     $('#toast-container').removeClass('toast-top-right');
     return;
     }*/

    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        url: "controller/admision/Paciente?opc=search-tarjeta",
        type: "POST",
        data: {id_sucursal: id_sucursal, tarjeta: tarjeta},
        dataType: "json",
        success: function (data) {

            if (data.length > 0) {
                $("#no_tarjeta").val("");
                toastr.error("Tarjeta registrada");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});





