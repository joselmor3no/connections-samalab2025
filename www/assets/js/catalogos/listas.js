
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

                    //Enviar precio publico y precio neto
                    $(".codigo").attr("disabled", false);
                    $(".precio_neto").attr("disabled", false);
                    var estudios = $(".codigo");
                    for (var i = 0; i < estudios.length; i++) {
                        var table = $("#table_lista");
                        table.append("<input type='hidden' name='precio_publico[]' value='" + $(".precio_publico")[i].innerText + "'></td>");
                        table.append("<input type='hidden' name='paquete[]' value='" + $(".paquete")[i].innerText + "'></td>");
                    }

                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

$(document).on('blur', '#alias', function () {

    var alias = this.value;
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        url: "controller/catalogos/Lista?opc=alias&" + "alias=" + alias + "&" + "id_sucursal=" + id_sucursal,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
        data: {},
        success: function (data) {
            if (data.length > 0) {
                $("#alias").val("");
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


$(document).on('keydown', ".codigo", function (event) {
    var codigo = this;
    var id = this.dataset.id;
    var key = (event.keyCode ? event.keyCode : event.which);

    if (key == '13') {
        event.preventDefault();
        event.stopPropagation();

        //condicion de paro
        if (codigo.disabled) { //Doble enter en autocomplet
            return;
        }

        //funcion de busqueda
        var estudio = $(codigo).val();
        var id_sucursal = $("#id_sucursal").val();

        $("#loading").modal("show");
        $.ajax({
            url: "controller/catalogos/Lista?opc=estudios-paquetes",
            type: "POST",
            data: {estudio: estudio, id_sucursal: id_sucursal},
            dataType: "json",
            success: function (data) {
                setTimeout(function () {
                    $("#loading").modal("hide");
                }, 500);

                if (data.length == 0) {
                    $(codigo).val("");
                    setTimeout(function () {
                        toastr.error("Estudio no válido");
                        $('#toast-container').addClass('toast-top-center');
                        $('#toast-container').removeClass('toast-top-right');
                    }, 800);

                } else if (data.length == 1) {
                    setEstudio(data[0], id, codigo);
                } else if (data.length >= 1) {
                    $(codigo).autocomplete({
                        source: data,
                        select: function (event, ui) {
                            setEstudio(ui.item, id, codigo);
                        }
                    });
                    setTimeout(function () {
                        $(codigo).focus();
                        $(codigo).autocomplete('search');
                    }, 1200);
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }
});

$(document).on('keydown', ".precio_neto", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);

    if (key == '13') {
        event.preventDefault();
        event.stopPropagation();

    }
});

function setEstudio(data, id, codigo) {
    //Validar estudio repetido

    var repetido = validarEstudios(data.value);

    if (repetido == 0) {
        $(codigo).attr("disabled", true);
        $(codigo).val(data.value);

        var currentRow = $("#est_" + id).closest("tr");
        currentRow.find("td:eq(1)").html(data.label);
        currentRow.find("td:eq(2)").html(data.tipo == 'paquete' ? data.value : "");
        currentRow.find("td:eq(3)").html(data.precio);
        $(".precio_neto")[id - 1].value = data.precio;
        currentRow.find("td:eq(4)").val(data.precio);

        addFila();

    } else {
        $(codigo).val("");
        setTimeout(function () {
            toastr.error("El estudio " + data.value + " ya se encuentra registrado");
            $('#toast-container').addClass('toast-top-center');
            $('#toast-container').removeClass('toast-top-right');
        }, 500);
    }
}

function validarEstudios(actual) {
    //Falata revisar bien esta validacion
    var estudios = $(".codigo");

    var repetidos = 0;
    for (var i = 0; i < estudios.length; i++) {
        if (estudios[i].value == actual && $(".codigo")[i].disabled) {
            repetidos++;
        }
    }

    return repetidos;
}

function addFila() {
    //Nueva fila
    var estudios = $(".codigo");

    var row = 0;
    for (var i = 0; i < estudios.length; i++) {
        if ($(".codigo")[i].disabled) {
            row++;
        }
    }
    var tr = $("#tbody tr").length;

    if (row == tr) {
        $(".btn-add-paq").click();
    }
}

$(document).on('click', '.reset-estudio', function () {
    var id = this.dataset.id;

    $('.codigo')[0].disabled = false;
    $('.codigo')[0].value = '';
    var currentRow = $("#est_" + id).closest("tr");
    currentRow.find("td:eq(1)").html("");
    currentRow.find("td:eq(2)").html("");
    currentRow.find("td:eq(3)").html("");
    $(".precio_neto")[id - 1].value = "";
});


$(document).on('click', '.delete-lista', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_lista").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});

var index = $("tbody tr").length + 1;
$(document).on('click', '.btn-add-paq', function () {
    var table = $("#table_lista");
    var id_lista = $("#id_lista").val();

    table.append("<tr id='est_" + index + "' class='text-center'>\n\
        <td><input type='text' class='form-control form-control-border text-uppercase codigo' data-id='" + index + "'  name='codigo[]' value='' placeholder='Código' required=''></td>\n\
        <td></td>\n\
        <td class='paquete'></td>\n\
        <td class='precio_publico'></td>\n\
        <td><input type='text' class='form-control form-control-border precio_neto' data-id='" + index + "'  name='precio_neto[]' value='' placeholder='Precio Neto' required=''></td>\n\
        <td align='center' class='d-line'>\n\
            <button type='button' class='btn btn-danger btn-sm delete-estudio rounded-circle mt-1 mb-1' data-id='" + index + "'><i class='fas fa-trash'></i></button>"
            + (id_lista != "" ? "<button type='button' class='btn btn-primary btn-sm save-estudio rounded-circle mt-1 mb-1 ml-2' data-id='" + index + "'><i class='fas fa-save'></i></button>" : "") +
            "</td>\n\
    </tr>");
    index++;
});

$(document).on('click', '.delete-estudio', function () {
    $(this).attr("disabled", true);

    var id = this.dataset.id;
    var id_lista = $("#id_lista").val();
    var id_sucursal = $("#id_sucursal").val();

    var currentRow = $("#est_" + id).closest("tr");
    var alias = currentRow.find("td:eq(0)").children().val();

    if (alias != "" && id_lista != "") {
        $.ajax({
            url: "controller/catalogos/Lista?opc=delete-estudio&" + "codigo=" + alias + "&" + "id=" + id_lista + "&" + "id_sucursal=" + id_sucursal,
            type: "POST",
            processData: false,
            contentType: false,
            dataType: "json",
            data: {},
            success: function (data) {
                $('#est_' + id).remove();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    } else {
        $('#est_' + id).remove();
    }
});

$(document).on('click', '.save-estudio', function () {
    $(this).attr("disabled", true);

    var id = this.dataset.id;
    var id_lista = $("#id_lista").val();
    var id_sucursal = $("#id_sucursal").val();

    var currentRow = $("#est_" + id).closest("tr");
    var alias = currentRow.find("td:eq(0)").children().val();
    var paquete = currentRow.find("td:eq(2)").html();
    var precio_publico = currentRow.find("td:eq(3)").html();
    var precio_neto = currentRow.find("td:eq(4)").children().val();


    if (alias != "" && precio_publico != "" && precio_neto != "") {

        $.ajax({
            url: "controller/catalogos/Lista?opc=add-estudio&" + "codigo=" + alias + "&" + "paquete=" + paquete + "&" + "precio_publico=" + precio_publico + "&" + "precio_neto=" + precio_neto + "&" + "id=" + id_lista + "&" + "id_sucursal=" + id_sucursal,
            type: "POST",
            processData: false,
            contentType: false,
            dataType: "json",
            data: {},
            success: function (data) {
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    } else {
        toastr.error("favor de capturar código y/o precio neto");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }

});


$(document).on('click', ".pdf-lista", function (event) {

    event.preventDefault();
    event.stopPropagation();
});


$(document).on('click', ".excel-lista", function (event) {

    event.preventDefault();
    event.stopPropagation();
});

