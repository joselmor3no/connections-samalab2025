
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

$(document).on('blur', '#user', function () {

    var alias = this.value;
    var prefijo = $("#prefijo").val();
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        url: "controller/catalogos/Usuario?opc=alias&" + "alias=" + (prefijo + "_" + alias) + "&" + "id_sucursal=" + id_sucursal,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        data: {},
        success: function (data) {
            if (data.length > 0) {
                $("#user").val("");
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

$(document).on('click', '.delete-usuario', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_doctor").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});

$(document).on('click', '#btn-modal-permisos', function () {
    $("#modal-permisos").modal("show");

    var id = this.dataset.id;
    $.ajax({
        url: "controller/catalogos/Usuario?opc=permisos&" + "id_usuario=" + id,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        data: {},
        success: function (data) {

            //reset check
            var permisos = $(".permisos");
            for (var i = 0; i < permisos.length; i++) {
                $(".permisos")[i].checked = false;
            }

            for (var i = 0; i < data.length; i++) {
                if ($("#permiso_" + data[i].id_cat_permiso).length > 0) {
                    $("#permiso_" + data[i].id_cat_permiso)[0].checked = true;
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});

$(document).on('click', '#btn-modal-informes', function () {
    $("#modal-informes").modal("show");

    var id = this.dataset.id;
    $.ajax({
        url: "controller/catalogos/Usuario?opc=permisos&" + "id_usuario=" + id,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        data: {},
        success: function (data) {

            //reset check
            var permisos = $(".informes");
            for (var i = 0; i < permisos.length; i++) {
                $(".informes")[i].checked = false;
            }

            for (var i = 0; i < data.length; i++) {
                if ($("#informe_" + data[i].id_cat_permiso).length > 0) {
                    $("#informe_" + data[i].id_cat_permiso)[0].checked = true;
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});


$(document).on('click', '#btn-save-permisos', function () {

    var formData = new FormData();
    formData.append("id_usuario", $("#id_usuario").val());

    var permisos = $(".permisos");
    for (var i = 0; i < permisos.length; i++) {
        var actual = $(".permisos")[i];

        if (actual.checked == true) {
            formData.append("permisos[]", actual.dataset.id);
        }
    }

    $.ajax({
        url: "controller/catalogos/Usuario?opc=save-permisos",
        type: "POST",
        dataType: "json",
        processData: false,
        contentType: false,
        data: formData,
        success: function (data) {
            $("#modal-permisos").modal("hide");
            toastr.success("Operación Exitosa");
            $('#toast-container').addClass('toast-top-center');
            $('#toast-container').removeClass('toast-top-right');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});

$(document).on('click', '#btn-save-informes', function () {

    var formData = new FormData();
    formData.append("id_usuario", $("#id_usuario").val());

    var permisos = $(".informes");
    for (var i = 0; i < permisos.length; i++) {
        var actual = $(".informes")[i];

        if (actual.checked == true) {
            formData.append("informes[]", actual.dataset.id);
        }
    }

    $.ajax({
        url: "controller/catalogos/Usuario?opc=save-informes",
        type: "POST",
        dataType: "json",
        processData: false,
        contentType: false,
        data: formData,
        success: function (data) {
            $("#modal-informes").modal("hide");
            toastr.success("Operación Exitosa");
            $('#toast-container').addClass('toast-top-center');
            $('#toast-container').removeClass('toast-top-right');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});

$(document).on('click', '.permisos-all', function () {

    var permisos = $(".permisos");
    for (var i = 0; i < permisos.length; i++) {
        if (this.checked)
            $(".permisos")[i].checked = true;
        else
            $(".permisos")[i].checked = false;
    }
});

$(document).on('click', '.informes-all', function () {

    var permisos = $(".informes");
    for (var i = 0; i < permisos.length; i++) {
        if (this.checked)
            $(".informes")[i].checked = true;
        else
            $(".informes")[i].checked = false;
    }
});





