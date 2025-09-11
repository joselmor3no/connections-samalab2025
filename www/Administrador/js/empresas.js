
$(document).ready(function () {

    if ($(".custom-file-input")[0]) {
        bsCustomFileInput.init();
    }

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

$(document).on('click', '.delete-empresa', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_empresa").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});

$(document).on('change', '#id_estado', function () {
    var municipios = get_municipios_($('#id_estado option:selected').text());
    $("#id_municipio").html("");
    for (var i = 0; i < municipios.length; i++) {
        $("#id_municipio").append("<option value='" + municipios[i].id + "'>" + municipios[i].municipio + "</option>")
    }

});

function get_municipios_(estado) {
    var municipios = [];

    $.ajax({
        url: "../controller/Generales?opc=municipios&estado=" + estado,
        type: "POST",
        async: false,
        data: {},
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {
            municipios = data;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

    return municipios;
}

$("#exampleInputFile").change(function () {
    var file = this.files[0];

    if (file) {
        var reader = new FileReader();

        reader.onload = function () {
            $("#previewImg").attr("src", reader.result);
        }

        reader.readAsDataURL(file);
    }
});


$(document).on('change', '#id_sucursal', function () {

    $.ajax({
        url: "controller/Empresa?opc=get-usuarios&id_sucursal=" + $('#id_sucursal').val(),
        type: "POST",
        async: true,
        data: {},
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {
            $("#id_usuario").html("");
            for (var i = 0; i < data.length; i++) {
                $("#id_usuario").append("<option value='" + data[i].id + "'>" + data[i].nombre + "</option>")
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});





