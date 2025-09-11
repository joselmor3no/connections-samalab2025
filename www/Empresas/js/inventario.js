$(document).ready(function () {
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }
    if ($(".custom-file-input")[0]) {
        bsCustomFileInput.init();
    }

    $('.select-add-producto').select2({
        dropdownParent: $('#add-producto')
    });

    $('.add-estudio').select2({
        dropdownParent: $('#add-estudio')
    });


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

$(document).on('click', '.delete-proveedor', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_sucursal").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});

$(document).on('click', '.delete-producto', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_combo_producto").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});


$(document).on('click', '#btnNewTipo', function () {
    $("#newTipo").modal('show');
});

(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation-tipo');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {

                    event.preventDefault();
                    event.stopPropagation();

                    $("#loading").modal("show");
                    //console.log("save");

                    $.ajax({
                        url: "controller/Inventario?opc=registro-tipo-proveedor&nombre=" + $("#nombre").val() + "&id_cliente=" + $("#id_cliente").val(),
                        type: "POST",
                        async: true,
                        data: {},
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            $("#form_tipo").removeClass("was-validated");
                            $("#nombre").val("");

                            $('#tipo').select2('destroy');
                            $("#tipo").html("<option value=''>Selecciona un tipo</option>")
                            for (var i = 0; i < data.length; i++) {
                                $("#tipo").append("<option value='" + data[i].id + "'>" + data[i].nombre + "</option>");
                            }
                            $('#tipo').select2();

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $("#newTipo").modal("hide");
                                }, 500);
                            }, 500);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

$(document).on('click', '#btn-new-presentacion', function () {
    $("#new-presentacion").modal('show');
});

(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation-presentacion');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {

                    event.preventDefault();
                    event.stopPropagation();

                    $("#loading").modal("show");
                    //console.log("save");

                    $.ajax({
                        url: "controller/Inventario?opc=registro-presentacion&nombre=" + $("#presentacion").val() + "&id_cliente=" + $("#id_cliente").val(),
                        type: "POST",
                        async: true,
                        data: {},
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            $("#form_presentacion").removeClass("was-validated");
                            $("#presentacion").val("");

                            $('#id_presentacion_producto').select2('destroy');
                            $("#id_presentacion_producto").html("<option value=''>Selecciona un tipo</option>")
                            for (var i = 0; i < data.length; i++) {
                                $("#id_presentacion_producto").append("<option value='" + data[i].id + "'>" + data[i].nombre + "</option>");
                            }
                            $('#id_presentacion_producto').select2();

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $("#new-presentacion").modal("hide");
                                }, 500);
                            }, 500);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


$(document).on('click', '#btn-new-unidad', function () {
    $("#new-unidad").modal('show');
});

(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation-unidad');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {

                    event.preventDefault();
                    event.stopPropagation();

                    $("#loading").modal("show");
                    //console.log("save");

                    $.ajax({
                        url: "controller/Inventario?opc=registro-unidad&nombre=" + $("#unidad").val() + "&id_cliente=" + $("#id_cliente").val(),
                        type: "POST",
                        async: true,
                        data: {},
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            $("#form_unidad").removeClass("was-validated");
                            $("#unidad").val("");

                            $('#id_unidad_producto').select2('destroy');
                            $("#id_unidad_producto").html("<option value=''>Selecciona un tipo</option>")
                            for (var i = 0; i < data.length; i++) {
                                $("#id_unidad_producto").append("<option value='" + data[i].id + "'>" + data[i].nombre + "</option>");
                            }
                            $('#id_unidad_producto').select2();

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $("#new-unidad").modal("hide");
                                }, 500);
                            }, 500);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

$(document).on('change', '#id_estado', function () {
    var estado = $('#id_estado option:selected').text();
    var municipios = get_municipios_(estado);
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

$(document).on('click', '.new-producto', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_estudio").val(id);
    $("#estudio").html(nombre);
    $("#add-producto").modal('show');
});

$(document).on('click', '#new-estudio', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_estudio").val(id);
    $("#estudio").html(nombre);
    $("#add-estudio").modal('show');
});


$(document).on('click', '.delete-producto-td', function () {
    var id = this.dataset.id;

    if ($('.delete-producto-td').length == 1) {
        toastr.error("Debe agragar al menos un producto");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        return;
    }

    $('#est_' + id).remove();


});

var index = $("#tbody tr").length + 1;
$(document).on('click', '.btn-add-paq', function () {

    var tr = '<select class="form-control add-estudio" name="id_producto[]" required=""> \n\
            <option value="">Seleccionar un Producto</option>';
    for (var i = 0; i < productos.length; i++) {
        tr += '<option value="' + productos[i].id + '">' + productos[i].nombre + '( ' + productos[i].cantidad_utilizar + ' ' + productos[i].unidad + ')</option>';
    }
    tr += '</select>\n\
            <div class="invalid-feedback">\n\
                Favor de capturar el campo Producto\n\
            </div>';

    var table = $("#table_registro");
    table.append("<tr id='est_" + index + "' class='text-center'>\n\
        <td><input type='text' class='form-control form-control-border text-uppercase' data-id=" + index + "  name='cantidad[]' value='' placeholder='Cantidad' required=''></td>\n\
       <td>" + tr + "</td>\n\
        <td align='center'>\n\
            <button type='button' class='btn btn-danger btn-sm delete-producto-td rounded-circle mt-1 mb-1' data-id='" + index + "'><i class='fas fa-trash'></i></button>\n\
        </td>\n\
    </tr>");
    index++;

    //Focus en ultimo input
    setTimeout(function () {
        $("#codigo_" + (index - 1)).focus();
    }, 800);

    $('.add-estudio').select2({
        dropdownParent: $('#add-estudio')
    });

});

