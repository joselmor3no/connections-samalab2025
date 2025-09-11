
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

(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation-bonificacion');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#modalConfirm").modal("show");

                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


$(document).on('click', "#btn-send-bonificacion", function () {
    $("#modalConfirm").modal("hide");

    $("#loading").modal("show");
    $(".needs-validation-bonificacion")[0].submit();

});


$(document).on('blur', '.validate', function () {

    var monedero = $("#monedero").val();
    var aumento = $("#aumento").val();
    var descuento = $("#descuento").val();

    if (monedero > 0) {
        $("#aumento").attr("required", false);
        $("#descuento").attr("required", false);
    }

    if (aumento > 0) {
        $("#monedero").attr("required", false);
        $("#descuento").attr("required", false);
    }

    if (descuento > 0) {
        $("#monedero").attr("required", false);
        $("#aumento").attr("required", false);
    }


});

$(document).on('blur', '#alias-descuento', function () {

    var alias = this.value;
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        url: "controller/catalogos/Estructura?opc=alias-descuento&" + "alias=" + alias + "&" + "id_sucursal=" + id_sucursal,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        data: {},
        success: function (data) {
            if (data.length > 0) {
                $("#alias-descuento").val("");
                toastr.error("Alias no válido");
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

$(document).on('click', '.delete-estructura', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_estructura").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});


$(document).on('blur', '#alias-forma-pago', function () {

    var alias = this.value;
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        url: "controller/catalogos/Estructura?opc=alias-forma-pago&" + "alias=" + alias + "&" + "id_sucursal=" + id_sucursal,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        data: {},
        success: function (data) {
            if (data.length > 0) {
                $("#alias-descuento").val("");
                toastr.error("Alias no válido");
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

