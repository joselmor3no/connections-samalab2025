
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
                    var estudios = $(".codigo");
                    for (var i = 0; i < estudios.length; i++) {
                        var table = $("#table_paquete");
                        table.append("<input type='hidden' name='precio_publico[]' value='" + $(".precio_publico")[i].innerText + "'></td>");
                        table.append("<input type='hidden' name='precio_neto[]' value='" + $(".precio_neto")[i].innerText + "'></td>");
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
        url: "controller/catalogos/Paquete?opc=alias&" + "alias=" + alias + "&" + "id_sucursal=" + id_sucursal,
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

var index = $("tbody tr").length + 1;
$(document).on('click', '.btn-add-paq', function () {
    var table = $("#table_paquete");

    table.append("<tr id='est_" + index + "' class='text-center'>\n\
        <td><input type='text' class='form-control form-control-border text-uppercase codigo' data-id='" + index + "'  name='codigo[]' value='' placeholder='Código' required=''></td>\n\
        <td></td>\n\
        <td></td>\n\
        <td class='precio_publico'></td>\n\
        <td class='precio_neto'></td>\n\
        <td align='center'>\n\
            <button type='button' class='btn btn-danger btn-sm delete-estudio rounded-circle mt-1 mb-1' data-id='" + index + "'><i class='fas fa-trash'></i></button>\n\
        </td>\n\
    </tr>");
    index++;
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
            url: "controller/catalogos/Estudio?opc=get-estudio",
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

function setEstudio(data, id, codigo) {
    //Validar estudio repetido

    var repetido = validarEstudios(data.value);

    if (repetido == 0) {
        $(codigo).attr("disabled", true);
        $(codigo).val(data.value);

        var currentRow = $("#est_" + id).closest("tr");
        currentRow.find("td:eq(1)").html(data.label);
        currentRow.find("td:eq(2)").html($("#alias").val());
        currentRow.find("td:eq(3)").html(data.precio);
        currentRow.find("td:eq(4)").html();

        $("#total").blur();
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
    currentRow.find("td:eq(4)").html("");
});


$(document).on('click', '.delete-estudio', function () {
    var id = this.dataset.id;
    $('#est_' + id).remove();
});

$(document).on('click', '.delete-paquete', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_paquete").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});

$(document).on('blur', '#total', function () {
    //Falta terminar esto y poner el precio publico cuando carga 
    var total_publico = total_precio_publico();
    var total_paquete = (this.value).replace(",", "");

    if (total_paquete != "") {
        var total_publico = total_precio_publico();
        var porc = total_paquete / total_publico;

        var precio = $(".precio_publico");
        for (var i = 0; i < precio.length; i++) {
            if (precio[i].innerText != "") {
                $(".precio_neto")[i].innerText = formatter.format(parseFloat((precio[i].innerText).replace(",", "")) * porc);

            }
        }
    }

    var precio_neto = total_precio_neto();
    var diff = (parseFloat(total_paquete) - precio_neto);
    var temp = parseFloat($(".precio_neto")[0].innerText.replace(",", ""));
    if (diff.toFixed(2) > 0 || diff.toFixed(2) < 0) {
        $(".precio_neto")[0].innerText = (temp + diff).toFixed(2);
    }
    $("#total_publico").html(formatter.format(total_publico));
});


function total_precio_publico() {
    var total = 0;
    var precio = $(".precio_publico");
    for (var i = 0; i < precio.length; i++) {
        if (precio[i].innerText != "") {
            var importe = parseFloat((precio[i].innerText).replace(",", ""));

            total += importe;
        }
    }
    return total.toFixed(2);

}

function total_precio_neto() {
    var total = 0;
    var precio = $(".precio_neto");
    for (var i = 0; i < precio.length; i++) {
        if (precio[i].innerText != "") {
            var importe = parseFloat((precio[i].innerText).replace(",", ""));

            total += importe;
        }
    }
    return total.toFixed(2);

}


