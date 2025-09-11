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
                //borra ultimo dato vacio
                var codigo = $(".codigo");
                for (var i = codigo.length - 1; i >= 0; i--) {
                    var id = codigo[i].dataset.id;
                    if ($(".existencia")[i].innerText == "") {
                        $('#est_' + id).remove();
                    }
                }

                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    //prueba
                    //event.preventDefault();
                    //event.stopPropagation();

                    var codigo = $(".codigo");

                    if (codigo.length == 0) {
                        event.preventDefault();
                        event.stopPropagation();
                        $(".btn-add-paq").click();
                        return;
                    }

                    //No mandar orden sin productos
                    var tam = 0;
                    for (var i = 0; i < codigo.length; i++) {
                        if ($(".existencia")[i].innerText != "") {
                            tam += 1;
                        }
                    }

                    if (tam == 0) {
                        event.preventDefault();
                        event.stopPropagation();
                        toastr.error("Debe agragar al menos un producto");
                        $('#toast-container').addClass('toast-top-center');
                        $('#toast-container').removeClass('toast-top-right');
                        return;
                    }

                    //iva
                    var iva = $(".iva");
                    var table = $("#table_registro");
                    for (var i = 0; i < iva.length; i++) {
                        table.append("<input type='hidden' name='iva[]' value='" + $(".iva")[i].checked + "'>");
                        table.append("<input type='hidden' name='subtotal[]' value='" + $(".subtotal")[i].innerText + "'>");
                    }


                    $("#loading").modal("show");

                    //Deshabilitar
                    $(".codigo").attr("disabled", false);
                    $("#total").attr("disabled", false);
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
        var forms = document.getElementsByClassName('needs-validation-inventario');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();
                    buscar_inventario();
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
        var forms = document.getElementsByClassName('needs-validation-proveedor');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {

                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();



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
                        url: "controller/administracion/InventarioC?opc=registro-tipo-proveedor&nombre=" + $("#nombre").val() + "&id_sucursal=" + $("#id_sucursal").val(),
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


$(document).on('change', "#movimiento", function (event) {
    var movimiento = this.value;

    if (movimiento == "Entrada") {
        window.location.href = "/inventario-entrada";
    } else {
        window.location.href = "/inventario-salida";
    }
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

        search_productos(codigo, id);

    }
});

function search_productos(codigo, id) {
    //funcion de busqueda
    var producto = $(codigo).val();
    var id_sucursal = $("#id_sucursal").val();

    $("#loading").modal("show");
    $.ajax({
        url: "controller/administracion/InventarioC?opc=productos",
        type: "POST",
        data: {producto: producto, id_sucursal: id_sucursal},
        dataType: "json",
        success: function (data) {
            setTimeout(function () {
                $("#loading").modal("hide");
            }, 500);
            if (data.length == 0) {
                $(codigo).val("");
                setTimeout(function () {
                    toastr.error("Producto no válido");
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');
                }, 800);
            } else if (data.length == 1) {
                setProducto(data[0], id, codigo);
            } else if (data.length >= 1) {
                $(codigo).autocomplete({
                    source: data,
                    select: function (event, ui) {
                        setProducto(ui.item, id, codigo);
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

function setProducto(data, id, codigo) {
    var repetido = validarProducto(data.value);
    if (repetido == 0) {
        $(codigo).attr("disabled", true);
        $(codigo).val(data.value);
        var currentRow = $("#est_" + id).closest("tr");
        currentRow.find("td:eq(1)").html(data.label);
        currentRow.find("td:eq(4)").html(data.presentacion);
        currentRow.find("td:eq(10)").html(data.existencia);
        currentRow.find("td:eq(11)").html(data.unidad);

        addFila();

    } else {
        $(codigo).val("");
        setTimeout(function () {
            toastr.error("El producto " + data.value + " ya se encuentra registrado");
            $('#toast-container').addClass('toast-top-center');
            $('#toast-container').removeClass('toast-top-right');
        }, 500);
    }


}

function validarProducto(actual) {

    var codigo = $(".codigo");
    var repetidos = 0;
    for (var i = 0; i < codigo.length; i++) {
        if (codigo[i].value == actual && $(".codigo")[i].disabled) {
            repetidos++;
        }
    }
    return repetidos;
}

$(document).on('blur', ".ingreso", function () {
    var id = this.dataset.id;
    calcular_subtotal(id);
});

$(document).on('keydown', ".ingreso", function (event) {
    var id = this.dataset.id;
    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        calcular_subtotal(id);
    }
});

$(document).on('blur', ".precio", function () {
    var id = this.dataset.id;
    calcular_subtotal(id);
});


$(document).on('keydown', ".precio", function (event) {
    var id = this.dataset.id;
    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        calcular_subtotal(id);
    }
});

$(document).on('change', ".iva", function () {
    var id = this.dataset.id;
    calcular_subtotal(id);
});


$(document).on('blur', ".descuento", function () {
    var id = this.dataset.id;
    calcular_subtotal(id);
});


$(document).on('keydown', ".descuento", function (event) {
    var id = this.dataset.id;
    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        calcular_subtotal(id);
    }
});

function calcular_subtotal(id) {

    var ingreso = $("#est_" + id).children()[3].children[0].value;
    var precio = $("#est_" + id).children()[5].children[0].value;
    var iva = $("#est_" + id).children()[7].children[0].checked;
    var descuento = $("#est_" + id).children()[8].children[0].value;

    var subtotal = 0;
    if (ingreso > 0 && precio > 0) {
        subtotal = ingreso * precio;
    }

    if (descuento > 0) {
        subtotal = subtotal * (1 - (descuento / 100));
    }

    if (iva) {
        subtotal = subtotal * 1.16;
    }

    var currentRow = $("#est_" + id).closest("tr");
    currentRow.find("td:eq(9)").html(formatter.format(subtotal));

    total();

}

function total() {

    var total = 0;
    var subtotal = $(".subtotal");
    for (var i = 0; i < subtotal.length; i++) {
        if (subtotal[i].innerText != "") {
            var importe = parseFloat((subtotal[i].innerText).replace(",", ""));
            total += importe;
        }
    }

    $("#total").val(formatter.format(total));

}


var index = $("tbody tr").length + 1;
$(document).on('click', '.btn-add-paq', function () {

    var table = $("#table_registro");
    table.append("<tr id='est_" + index + "' class='text-center'>\n\
        <td><input id='codigo_" + index + "' type='text' class='form-control form-control-border text-uppercase codigo' data-id='" + index + "'  name='codigo[]' value='' placeholder='Código'></td>\n\
        <td></td>\n\
        <td><input type='text' class='form-control form-control-border text-uppercase marca' data-id=" + index + "  name='marca[]' value='' placeholder='Marca' required=''></td>\n\
        <td><input type='text' class='form-control form-control-border ingreso' data-id=" + index + "  name='ingreso[]' value='' placeholder='Ingreso' required=''></td>\n\
        <td></td>\n\
        <td><input type='text' class='form-control form-control-border precio' data-id=" + index + "  name='precio[]' value='' placeholder='Precio' required=''></td>\n\
        <td><input type='date' class='form-control form-control-border caducidad' data-id=" + index + "  name='caducidad[]' value='' placeholder='Caducidad' required=''></td>\n\
        <td><input type='checkbox' class='iva' data-id=" + index + " ></td>\n\
        <td><input type='text' class='form-control form-control-border descuento' data-id=" + index + "  name='descuento[]' value='' placeholder='% Descuento'></td>\n\
        <td class='subtotal'></td>\n\
        <td class='existencia'></td>\n\
        <td></td>\n\
        <td align='center'>\n\
            <button type='button' class='btn btn-danger btn-sm delete-producto rounded-circle mt-1 mb-1' data-id='" + index + "'><i class='fas fa-trash'></i></button>\n\
        </td>\n\
    </tr>");
    index++;

    //Focus en ultimo input
    setTimeout(function () {
        $("#codigo_" + (index - 1)).focus();
    }, 800);

});

function addFila() {
    //Nueva fila
    var codigo = $(".codigo");
    var row = 0;
    for (var i = 0; i < codigo.length; i++) {
        if ($(".codigo")[i].disabled) {
            row++;
        }
    }
    var tr = $("#tbody tr").length;
    if (row == tr) {
        $(".btn-add-paq").click();
    }
}

$(document).on('click', '.delete-producto', function () {
    var id = this.dataset.id;

    if ($('.delete-producto').length == 1) {
        toastr.error("Debe agragar al menos un producto");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        return;
    }

    $('#est_' + id).remove();

    total();
});


$(document).on('click', '.load-inventario', function () {
    buscar_inventario();
});

function buscar_inventario() {
    var datos = new FormData($(".needs-validation-inventario")[0]);
    datos.append("id_sucursal", $("#id_sucursal").val());
    $("#loading").modal("show");

    //AJAX buscar reporte
    $.ajax({
        url: "controller/administracion/InventarioC?opc=reportes",
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {

            $("#table-reportes").html("");
            for (var i = 0; i < data.length; i++) {

                $("#table-reportes").append("<tr>\n\
                <td>" + data[i].consecutivo + "</td>\n\
                <td>" + (data[i].proveedor == null ? "" : data[i].proveedor) + "</td>\n\
                <td>" + data[i].factura + "</td>\n\
                <td>" + data[i].total + " </td>\n\
                <td>" + data[i].observacion + " </td>\n\
                <td>" + data[i].fecha + "</td>\n\
                <td align='center'>\n\
                    <a href='reporte-vale?codigo=" + data[i].consecutivo + "' target='_blank' class='btn btn-sm btn-warning rounded-circle m-1' title='Recibo'>\n\
                        <i class='fas fa-file-pdf'></i>\n\
                    </a>\n\
                </td>\n\
                </tr>");
            }

            setTimeout(function () {
                $("#loading").modal("hide");
            }, 500);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

//Proveedores
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





