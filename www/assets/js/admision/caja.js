
$(document).ready(function () {
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');

        console.log(window.location.href.search("corte"))
        if (window.location.href.search("corte") > -1) {
            window.open("reporte-corte?tipo=detalle");
            window.open("reporte-corte?tipo=departamento");
        }
    }

    if ($("#codigo").val() != "") {
        $("#codigo").blur();
    }

    if ($('.dataTableCorte').html()) {
        $('.dataTableCorte').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay informaci&oacute;n",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ resultados",
                "infoEmpty": "Mostrando 0 a 0 de 0 resultados",
                "infoFiltered": "(Filtrado de _MAX_ total resultados)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Resultados",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "&Uacute;ltimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            order: []
        });
    }
});

$(document).on('keydown', ".form-control", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        event.preventDefault();
        event.stopPropagation();
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
                    var id_orden = $("#id_orden").val();

                    $("#codigo").attr("disabled", false);
                    if (id_orden == "") {
                        event.preventDefault();
                        event.stopPropagation();
                        toastr.error("Favor de capturar un código");
                        $('#toast-container').addClass('toast-top-center');
                        $('#toast-container').removeClass('toast-top-right');

                    } else {
                        event.preventDefault();
                        event.stopPropagation();

                        $("#modalPassword").modal("show");

                    }
                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

$(document).on('click', "#acceso", function () {
    $("#modalPassword").modal("hide");
    var password = $("#password").val();
    var password_actual = $("#password_actual").val();


    if (password.toUpperCase() == password_actual.toUpperCase() ) {

        $("#loading").modal("show");
        $(".needs-validation")[0].submit()
    } else {
        $("#password_actual").val("");
        toastr.error("Contraseña incorrecta");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }

});


$(document).on('keydown', "#password_actual", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {

        $("#acceso").click();
    }

});

$(document).on('keydown', "#codigo", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        event.preventDefault();
        event.stopPropagation();

        var codigo = this.value;
        load_pago(codigo);

    }

});


$(document).on('click', "#btn-recibo", function (event) {
    if ($("#id_ticket").val() != 3) {
         window.open("recibos/recibo?codigo=" + $("#codigo").val());
    } else {
        window.open("recibos/recibo-media?codigo=" + $("#codigo").val());
    }

});

$(document).on('click', "#btn-etiquetas", function (event) {
    window.open("etiquetas-estudios?codigo=" + $("#codigo").val());
});


$(document).on('blur', '#codigo', function () {
    var codigo = this.value;
    load_pago(codigo);

});

function load_pago(codigo) {

    var id_sucursal = $("#id_sucursal").val();

    $.ajax({
        url: "controller/admision/Caja?opc=pagos",
        type: "POST",
        data: {codigo: codigo, id_sucursal: id_sucursal},
        dataType: "json",
        success: function (data) {

            if (data.length == 0) {
                toastr.error("Código de paciente no encontrado"); 
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
                return;
            } else {
                $("#id_orden").val(data.id);
                $("#paciente").html(data.paciente);
                $("#tipo_orden").val(data.tipo_orden)
                $("#fecha_orden").html(data.fecha_orden);
                $("#codigo_matriz").html(data.consecutivo_matriz);
                $("#total").html(formatter.format(data.importe));
                $("#monto").html(formatter.format(data.importe - data.saldo_deudor));
                $("#saldo_deudor").html(formatter.format(data.saldo_deudor));

                $("#table_caja").html("");
                var table = $("#table_caja");
                table.append("<input type='hidden' id='hide_total' value='" + data.importe + "'>");
                table.append("<input type='hidden' id='hide_monto' value='" + (data.importe - data.saldo_deudor) + "'>");

                $("#codigo").attr("disabled", true);
                $("#reset-codigo").removeClass("d-none")

                if (data.credito == 1) {
                    $("#save-pago").attr("disabled", true);
                    $("#credito").removeClass("d-none");
                    $("#pago").attr("disabled", true);
                } else if (data.saldo_deudor == 0) {
                    $("#save-pago").attr("disabled", true);
                    $("#pagado").removeClass("d-none");
                    $("#pago").attr("disabled", true);
                } else if (data.cancelado == 1) {
                    $("#save-pago").attr("disabled", true);
                    $("#cancelado").removeClass("d-none");
                    $("#pago").attr("disabled", true);
                }

            }

            //console.log(data);
            $("#print-recibo").removeClass("d-none");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

}

$(document).on('blur', '#pago', function () {

    var pago = parseFloat(this.value);
    abono_pago(pago);

});

$(document).on('keydown', "#pago", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        event.preventDefault();
        event.stopPropagation();

        var pago = parseFloat(this.value);
        abono_pago(pago);
    }

});

function abono_pago(pago) {
    var monto = parseFloat($("#hide_monto").val().replace(",", ""));
    var total = parseFloat($("#hide_total").val().replace(",", ""));
    var saldo_deudor = (total - monto).toFixed(2);

    if (pago <= 0) {
        toastr.error("Favor de capturar un pago váldo");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        $("#pago").val("");
        $("#saldo_deudor").html(formatter.format(total));
        $("#monto").html(formatter.format(monto));
    } else if (pago > saldo_deudor) {
        toastr.error("El pago no puede ser mayor a la deuda del paciente");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        $("#pago").val("");
        $("#saldo_deudor").html(formatter.format(total));
        $("#monto").html(formatter.format(monto));
    } else if (saldo_deudor >= 0 && total > 0 && pago > 0) {
        $("#monto").html(formatter.format(monto + pago));
        $("#saldo_deudor").html(formatter.format(total - pago - monto));
    }

}


$(document).on('click', "#btn-informado", function (event) {
    window.open("recibos/consentimiento-informado?codigo=" + $("#codigo").val());
});


$(document).on('click', "#btn-ginecologico", function (event) {
    window.open("recibos/consentimiento-gine?codigo=" + $("#codigo").val());
});


$(document).on('click', "#btn-vih", function (event) {
    window.open("recibos/consentimiento-vih?codigo=" + $("#codigo").val());
});
