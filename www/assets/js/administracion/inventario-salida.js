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
                for (var i =  codigo.length-1; i >= 0; i--) {
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

                    $("#loading").modal("show");

                    //Deshabilitar
                    $(".codigo").attr("disabled", false);
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
        var forms = document.getElementsByClassName('needs-validation-toma');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    var conteo = $(".conteo");

                    var table = $("#table_registro");
                    for (var i = 0; i < conteo.length; i++) {
                        table.append("<input type='hidden' name='codigo[]' value='" + $(".codigo")[i].innerText + "'>");
                        table.append("<input type='hidden' name='existencia[]' value='" + $(".existencia")[i].innerText + "'>");
                        table.append("<input type='hidden' name='diferencia[]' value='" + $(".diferencia")[i].innerText + "'>");
                    }


                    $("#loading").modal("show");
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
        if (data.existencia > 0) {
            $(codigo).attr("disabled", true);
            $(codigo).val(data.value);
            var currentRow = $("#est_" + id).closest("tr");
            currentRow.find("td:eq(1)").html(data.label);
            currentRow.find("td:eq(2)").html(data.caducidad);
            currentRow.find("td:eq(4)").html(data.unidad_egreso);
            currentRow.find("td:eq(5)").html(data.existencia);
            currentRow.find("td:eq(6)").html(data.presentacion + " (" + data.unidad + ")");

            addFila();

        } else {
            $(codigo).val("");
            setTimeout(function () {
                toastr.error("El producto " + data.value + " no cuenta con existencia");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
            }, 500);
        }
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


var index = $("tbody tr").length + 1;
$(document).on('click', '.btn-add-paq', function () {

    var table = $("#table_registro");
    table.append("<tr id='est_" + index + "' class='text-center'>\n\
        <td><input id='codigo_" + index + "' type='text' class='form-control form-control-border text-uppercase codigo' data-id='" + index + "'  name='codigo[]' value='' placeholder='Código'></td>\n\
        <td></td>\n\
        <td></td>\n\
        <td><input type='text' class='form-control form-control-border egreso' data-id=" + index + "  name='egreso[]' value='' placeholder='Egreso' required=''></td>\n\
        <td></td>\n\
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
            //console.log(data);
            $("#table-reportes").html("");
            for (var i = 0; i < data.length; i++) {

                $("#table-reportes").append("<tr>\n\
                <td>" + data[i].consecutivo + "</td>\n\
                <td>" + (data[i].sucursal == null ? "" : data[i].sucursal) + "</td>\n\
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

$(document).on('blur', ".egreso", function () {
    var id = this.dataset.id;
    existencia_venta(id);
});

$(document).on('keydown', ".egreso", function (event) {

    var id = this.dataset.id;
    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        existencia_venta(id);
        event.preventDefault();
        event.stopPropagation();
    }
});


function  existencia_venta(id) {
    var egreso = $("#est_" + id).children()[3].children[0].value;
    var existencia = $("#est_" + id).children('td')[5].innerText;


    if (existencia - egreso < 0) {
        $("#est_" + id).children()[3].children[0].value = "";
        setTimeout(function () {
            toastr.error("Sin existencia suficiente");
            $('#toast-container').addClass('toast-top-center');
            $('#toast-container').removeClass('toast-top-right');
        }, 500);
    }

}

$(document).on('blur', ".conteo", function () {
    var id = this.dataset.id;
    existencia_conteo(id);
});

$(document).on('keydown', ".conteo", function (event) {

    var id = this.dataset.id;
    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        existencia_conteo(id);
        event.preventDefault();
        event.stopPropagation();
    }
});


function  existencia_conteo(id) {
    var conteo = $("#est_" + id).children()[5].children[0].value;
    var existencia = $("#est_" + id).children('td')[3].innerText;

    $("#est_" + id).children('td')[6].innerText = existencia - conteo;

}

$(document).on('keydown', ".tab", function (event) {
    var id = this.dataset.id;
    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {

        var index = $('.tab').index(this);
        if (index < $('.tab').length - 1)
            $('.tab')[index + 1].focus();
    }
});

$(document).on('click', ".print-diferencias", function () {
    window.print();
});











