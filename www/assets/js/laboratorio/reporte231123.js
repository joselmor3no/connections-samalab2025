
$(document).ready(function () {
    setTimeout(function(){
        $("#observaciones").val('');
    }, 1000);

    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }

    if ($('#table-estudios').html()) {
        $('#table-estudios').tableDnD();
    }

    if ($('#reporte-texto').html()) {
        $('#reporte-texto').summernote({
            placeholder: 'Escribe aquí ...',
            height: 350
        });

    }

    if ($(".custom-file-input")[0]) {
        bsCustomFileInput.init();
    }

    $("#btn-envio-maquila").click(function(){
        let id_orden=this.dataset.orden;
        let id_sucursal=this.dataset.sucursal;
        let id_matriz = $("#id_sucursal").val();
        $.ajax({
            type: "POST",
            url: "controller/laboratorio/Reporte?opc=resultado-maquila",
            data: {'id_orden': id_orden,'id_sucursal': id_sucursal ,'id_matriz': id_matriz},
            dataType: "json",
            success: function (data) {
                //console.log("data", data);
                if(data.trim()=="ok"){
                    toastr.success("Operación Exitosa");
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
    })

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

                    var formula = $(".formula");
                    for (var i = 0; i < formula.length; i++) {
                        var table = $("#table-componentes");
                        table.append("<input type='hidden' name='" + $(".formula")[i].id + "' value='" + ($(".formula")[i].innerText == "Sin valores de referencia" ? "" : $(".formula")[i].innerText) + "'>");
                    }

                    $("#hide_observaciones").val($("#observaciones").val());
                    $("#hide_ingles").val($("#ingles")[0].checked);
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
        var forms = document.getElementsByClassName('needs-validation-pdf');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {

                    event.preventDefault();
                    event.stopPropagation();

                    var id_estudio = $("#id_estudio").val();
                    if (id_estudio == "") {

                        toastr.error("Debe elegir un estudio / paciente");
                        $('#toast-container').addClass('toast-top-center');
                        $('#toast-container').removeClass('toast-top-right');
                        return;
                    }

                    //var id_paciente = $("#id_paciente").val();
                    var ruta = $("#ruta").val();
                    var expediente = $("#paciente_expediente").html();
                    var formData = new FormData($("#load-pdf")[0]);
                    formData.append("id_estudio", id_estudio);
                    formData.append("expediente", expediente);

                    $.ajax({
                        url: "controller/laboratorio/Reporte?opc=upload-resultado",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            toastr.success("Operación Exitosa");
                            $('#toast-container').addClass('toast-top-center');
                            $('#toast-container').removeClass('toast-top-right');

                            $("#pdf_resultado").removeClass("d-none");
                            $("#pdf_resultado").html("<a class='pr-2' href='reportes/" + ruta + "/resultados/" + data + "' target='_blank'>Ver PDF</a>\n\
                        <button type='button' data-id='" + id_estudio + "' class='btn btn-sm btn-danger rounded-circle delete-resultado-pdf' title='' data-toggle='tooltip' data-original-title='Borrar'>\n\
                        <i class='fas fa-trash'></i>\n\
                        </button>");

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


$(document).on('click', '.estudio', function () {
    var id = this.dataset.id;
    var estudio = this.dataset.estudio;
    var impresion = this.dataset.impresion;
    var reportado = this.dataset.reportado;
    var tipo = this.dataset.tipo;
    var id_orden = this.dataset.id_orden;
    var id_sucursal = $("#id_sucursal").val();

    $("#loading").modal("show");

    //reporte por estudio
    if (id_orden > 0) {

        //reset check
        var imprimir = $(".imprimir");
        for (var i = 0; i < imprimir.length; i++) {
            $(".imprimir")[i].checked = false;
        }

        if (impresion > 0 || reportado > 0) {
            for (var i = 0; i < imprimir.length; i++) {
                if ($(".imprimir")[i].value == id) {
                    $(".imprimir")[i].checked = true;
                }
            }
        }

        $.ajax({
            type: "POST",
            url: "controller/laboratorio/Reporte?opc=get-orden-paciente",
            data: {id_orden: id_orden},
            dataType: "json",
            async: false,
            success: function (data) {
                //console.log(data)
                $("#codigo").val(data.codigo);
                $("#id_orden").val(data.id_orden);
                $("#id_paciente").val(data.id);
                $("#hide_edad").val(data.edad);
                $("#hide_tipo_edad").val(data.tipo_edad);
                $("#hide_sexo").val(data.sexo);

                //Cargar datos del paciente
                $("#paciente_codigo").html(data.codigo);
                $("#paciente_expediente").html(data.expediente);
                $("#paciente_nombre").html(data.paciente);
                $("#paciente_fecha").html(data.fecha);
                $("#paciente_sexo").html(data.sexo);
                $("#paciente_edad").html(data.edad);
                $("#paciente_medico").html(data.doctor);
                $("#paciente_observaciones").html(data.observaciones);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    } else {
        var id_orden = $("#id_orden").val();
    }

    var edad = $("#hide_edad").val();
    var tipo_edad = $("#hide_tipo_edad").val();
    var sexo = $("#hide_sexo").val();

    $("#id_estudio").val(id);
    $(".bg-estudios").removeClass("bg-light");
    $("#index_" + id).addClass("bg-light");
    $("#hide_tipo").val(tipo);

    if (tipo == "componente") {

        $("#estudio").html(estudio);

        $("#tipo-componente").removeClass("d-none");
        $("#tipo-texto").addClass("d-none");

        $.ajax({
            url: "controller/laboratorio/Reporte?opc=get-componentes",
            type: "POST",
            data: {id: id, edad: edad, tipo_edad: tipo_edad, sexo: sexo, id_orden: id_orden, id_sucursal: id_sucursal},
            dataType: "json",
            success: function (data) {
                console.log("data", data);
                var ruta = $("#ruta").val();

                $("#table-componentes").html("");
                if (data == null) {
                    toastr.error("Sin componnetes registradas");
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');

                    setTimeout(function () {
                        $("#loading").modal("hide");
                    }, 600);

                }

                for (var i = 0; i < data.length; i++) {

                    if (data[i]["componente"].id_cat_componente == null) {
                        $("#table-componentes").append("<tr>\n\
                            <td colspan='5' class='bg-light'>" + data[i]["componente"].componente + "</td>\n\
                            </tr>");
                    } else if (data[i]["componente"].id_cat_componente == 1) {
                        var puntoDecimal = data[i].referencia == null ? 0 : data[i].referencia.decimales;
                        $("#table-componentes").append("<tr id='tr_component_" + data[i]["componente"].id + "'>\n\
                        <td align='center'>" + data[i]["componente"].componente + "</td>\n\
                        <td align='center'>\n\
                        <input id='componente_" + data[i]["componente"].id + "' data-id='" + data[i]["componente"].id + "' data-alias='" + data[i]["componente"].alias + "' \n\
                        type='text' class='form-control form-control-border text-center componente tab' name='componente_" + data[i]["componente"].id + "' \n\
                        value='" + (data[i]["componente"].resultado == '' ? '' : parseFloat(data[i]["componente"].resultado).toFixed(puntoDecimal)) + "' placeholder='" + (data[i].referencia != null ? (0.0).toFixed(puntoDecimal) : "Sin valores de referencia") + "' " + (data[i].referencia != null ? "" : "readonly") + ">\n\
                        </td>\n\
                        <td align='center'>" + (data[i].referencia != null ? parseFloat(data[i].referencia.baja).toFixed(puntoDecimal) : "-") + "</td>\n\
                        <td align='center'>" + (data[i].referencia != null ? parseFloat(data[i].referencia.alta).toFixed(puntoDecimal) : "-") + "</td>\n\
                        <td align='center'>" + (data[i].referencia != null ? data[i].referencia.unidad : "-") + "</td>\n\
                        </tr>");
                    } else if (data[i]["componente"].id_cat_componente == 2) {
                        var puntoDecimal = data[i].referencia == null ? 0 : data[i].referencia.decimales;
                        $("#table-componentes").append("<tr id='tr_component_" + data[i]["componente"].id + "'>\n\
                        <td align='center'>" + data[i]["componente"].componente + "</td>\n\
                        <td align='center'><span id='componente_" + data[i]["componente"].id + "' data-id='" + data[i]["componente"].id + "' data-alias='" + data[i]["componente"].alias + "' data-formula='" + data[i].formula.formula + "' class='form-control form-control-border text-center bg-light formula' name='componente_" + data[i]["componente"].id + "'></span></td>\n\
                        <td align='center'>" + (data[i].referencia != null ? parseFloat(data[i].referencia.baja).toFixed(puntoDecimal) : "-") + "</td>\n\
                        <td align='center'>" + (data[i].referencia != null ? parseFloat(data[i].referencia.alta).toFixed(puntoDecimal) : "-") + "</td>\n\
                        <td align='center'>" + (data[i].referencia != null ? data[i].referencia.unidad : "-") + "</td>\n\
                        </tr>");
                    } else if (data[i]["componente"].id_cat_componente == 3) {

                        var lista = "";
                        var predeterminado = "";
                        for (var x = 0; x < data[i].referencia.length; x++) {
                            var elemento = data[i].referencia[x];
                            if (elemento.predeterminado == 1)
                                predeterminado = elemento.elemento;
                            lista += "<option value='" + elemento.elemento + "' " + (data[i]["componente"].resultado == elemento.elemento ? "selected" : "") + ">" + elemento.elemento + "</option>";
                        }

                        $("#table-componentes").append("<tr>\n\
                        <td align='center'>" + data[i]["componente"].componente + "</td>\n\
                        <td align='center'><select id = 'componente_" + data[i]["componente"].id + "' class='form-control text-center tab' name = 'componente_" + data[i]["componente"].id + "'>" + lista + "</select></td>\n\
                        <td align='center'></td>\n\
                        <td align='center'></td>\n\
                        <td align='center'>" + predeterminado + "</td>\n\
                        </tr>");
                    } else if (data[i]["componente"].id_cat_componente == 4) {

                        $("#table-componentes").append("<tr>\n\
                        <td align='center'>" + data[i]["componente"].componente + "</td>\n\
                        <td align='center'><input id='componente_" + data[i]["componente"].id + "' type='text' \n\
                        class='form-control form-control-border text-center tab' name='componente_" + data[i]["componente"].id + "' \n\
                        value='" + data[i]["componente"].resultado + "' placeholder='texto'></td>\n\
                        <td align='center' colspan='2' class='bg-light'></td>\n\
                        <td align='center' >" + (data[i]["componente"].unidad != null ? data[i]["componente"].unidad : "") + "</td>\n\
                        </tr>");
                    }

                    $("#observaciones").val(data[i]["componente"].observaciones);
                    if (data[i]["componente"].pdf != null && data[i]["componente"].pdf != "") {
                        $("#pdf_resultado").removeClass("d-none");
                        $("#pdf_resultado").html("<a class='pr-2' href='reportes/" + ruta + "/resultados/" + data[i]["componente"].pdf + "' target='_blank'>Ver PDF</a>\n\
                        <button type='button' data-id='" + id + "' class='btn btn-sm btn-danger rounded-circle delete-resultado-pdf' title='' data-toggle='tooltip' data-original-title='Borrar'>\n\
                        <i class='fas fa-trash'></i>\n\
                        </button>");

                    } else {
                        $("#pdf_resultado").addClass("d-none");
                        $("#pdf_resultado").html("");
                    }

                    $("#restablecio").html(data[i]["componente"].restablecio);
                    $(".componente").blur();
                    setTimeout(function () {
                        $("#loading").modal("hide");
                    }, 600);

                    //Restriccion de impresion
                    if (impresion > 0) {
                        $("#save").attr("disabled", true);
                        $(".tab").attr("disabled", true);
                    } else {
                        $("#save").attr("disabled", false);
                        $(".tab").attr("disabled", false);
                    }
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    } else {
        $("#tipo-texto").removeClass("d-none");
        $("#tipo-componente").addClass("d-none");
        $("#estudio").html(estudio);

        $.ajax({
            type: "POST",
            url: "controller/laboratorio/Reporte?opc=get-texto",
            data: {id: id, edad: edad, tipo_edad: tipo_edad, sexo: sexo, id_orden: id_orden, id_sucursal: id_sucursal},
            dataType: "json",
            success: function (data) {
                //console.log(data)
                setTimeout(function () {
                    $("#loading").modal("hide");
                }, 600);

                if (data != null) {
                    if (data.resultado) {
                        $('#reporte-texto').summernote('code', data.resultado);
                    } else {
                        $('#reporte-texto').summernote('code', "");
                    }

                    $("#observaciones").val(data.observaciones);
                    //Restriccion de impresion
                    if (impresion > 0) {
                        $("#save").attr("disabled", true);
                        $('#reporte-texto').summernote('disable');
                    } else {
                        $("#save").attr("disabled", false);
                        $('#reporte-texto').summernote('enable');
                    }
                } else {
                    $("#save").attr("disabled", false);
                    $('#reporte-texto').summernote('enable');
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

$(document).on('blur', ".componente", function (event) {
    calculo_formula()
});

$(document).on('keydown', ".componente", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        calculo_formula();

    }
});

function calculo_formula() {

    var componenes_formula = $(".formula");
    for (var i = 0; i < componenes_formula.length; i++) {
        var id = componenes_formula[i].dataset.id;
        var formula = componenes_formula[i].dataset.formula;
        var val_formula = componenes_formula[i].innerText;
        var alias_formula = componenes_formula[i].innerText;

        //Recorrer todas las componentes numericos para la formula
        var componenes_numerico = $(".componente");
        for (var x = 0; x < componenes_numerico.length; x++) {

            var alias = componenes_numerico[x].dataset.alias;
            var value = componenes_numerico[x].value.replace(",", "");

            var reg = new RegExp(alias, "g");
            formula = formula.replace(reg, value);

        }

        //Recorrer los componenes de formula exepcion de vilar
        var componenes_form = $(".formula");
        for (var x = 0; x < componenes_form.length; x++) {

            var alias = componenes_form[x].dataset.alias;
            var value = componenes_form[x].innerText.replace(",", "");
            if (value != "" && value != "-") {
                var reg = new RegExp(alias, "g");
                formula = formula.replace(reg, value);
                console.log(formula);
            }
        }

        //Fuera de rango de formula
        var tr = $("#tr_component_" + id + ">td");
        var value = parseFloat((tr[1].innerText).replace(",", ""));
        var baja = parseFloat((tr[2].innerText).replace(",", ""));
        var alta = parseFloat((tr[3].innerText).replace(",", ""));

        var decimales = 0;
        var aux = (tr[2].innerText).split(".");
        if (aux.length == 2) {
            decimales = aux[1].length;
        }

        //Verifcar sea una formula vália
        try {
            var formatterFormula = new Intl.NumberFormat('en-US', {
                currency: 'USD',
                minimumFractionDigits: decimales,
                maximumFractionDigits: decimales,

            });

            if (isNaN(baja) || isNaN(alta)) {
                componenes_formula[i].innerText = "Sin valores de referencia";
            } else {


                var val = eval(formula.replace("^", "**"));
                componenes_formula[i].innerText = formatterFormula.format(val);

                if (val > baja && val < alta) {//rango
                    $("#componente_" + id).removeClass("text-primary");
                    $("#componente_" + id).removeClass("text-danger");
                } else if (val == baja || val == alta) {//limite
                    $("#componente_" + id).addClass("text-primary");
                    $("#componente_" + id).removeClass("text-danger");
                } else {//fuera
                    $("#componente_" + id).addClass("text-danger");
                    $("#componente_" + id).removeClass("text-primary");
                }
            }

        } catch (e) {
            if (e instanceof TypeError) {
                // sentencias para manejar excepciones TypeError
                componenes_formula[i].innerText = "-";
            } else if (e instanceof RangeError) {
                // sentencias para manejar excepciones RangeError
                componenes_formula[i].innerText = "-";
            } else if (e instanceof EvalError) {
                componenes_formula[i].innerText = "-";
                // sentencias para manejar excepciones EvalError
                componenes_formula[i].innerText = "-";
            } else {
                // sentencias para manejar cualquier excepción no especificada
                componenes_formula[i].innerText = "-";
            }
        }
    }
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

$(document).on('blur', ".componente", function (event) {
    var id = this.dataset.id;
    fuera_rango(id);
});

$(document).on('keydown', ".componente", function (event) {
    var id = this.dataset.id;
    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        fuera_rango(id);
    }
});

function fuera_rango(id) {

    //Fuera de rango
    var tr = $("#tr_component_" + id + ">td");
    var value = parseFloat(($("#componente_" + id).val().replace(",", "")));
    var baja = parseFloat((tr[2].innerText).replace(",", ""));
    var alta = parseFloat((tr[3].innerText).replace(",", ""));

    var decimales = 0;
    var aux = (tr[2].innerText).split(".");
    if (aux.length == 2) {
        decimales = aux[1].length;
    }

    var formatterNumero = new Intl.NumberFormat('en-US', {
        currency: 'USD',
        minimumFractionDigits: decimales,
        maximumFractionDigits: decimales,

    });

    if (value > 0) {
        var valorN = formatterNumero.format(value);
        valorN = valorN.replace(",", "");
        $("#componente_" + id).val(valorN);
    }

    if (value > baja && value < alta) {//rango
        $("#componente_" + id).removeClass("text-primary");
        $("#componente_" + id).removeClass("text-danger");
    } else if (value == baja || value == alta) {//limite
        $("#componente_" + id).addClass("text-primary");
        $("#componente_" + id).removeClass("text-danger");
    } else {//fuera
        $("#componente_" + id).addClass("text-danger");
        $("#componente_" + id).removeClass("text-primary");
    }
}


$(document).on('click', "#imprimir-reporte", function (event) {

    //validar resultados estudios
    var id_orden = $("#id_orden").val();
    var imprimir = validar_impresion();
    if (id_orden == "") {
        toastr.error("Debe elegir un paciente");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        return;
    } else if (id_orden == "" || !imprimir) {
        toastr.error("Error al imprimir");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        return;
    }

    $("#table-imprimir").html("");
    var estudios = $(".estudio");
    for (var i = 0; i < estudios.length; i++) {
        var table = $("#table-imprimir");
        //console.log(estudios);
        table.append("<input type='hidden' name='id_orden_detalle[]' value='" + estudios[i].dataset.id + "'>");
        table.append("<input type='hidden' name='imprimir[]' value='" + $(".imprimir")[i].checked + "'>");
        table.append("<input type='hidden' name='pagina[]' value='" + $(".pagina")[i].checked + "'>");
    }

    var data = new FormData($("#reporte-estudios")[0]);
    data.append("hide_ingles", $("#ingles")[0].checked);

    $("#loading").modal("show");

    $.ajax({
        type: "POST",
        url: "controller/laboratorio/Reporte?opc=imprimir-reporte",
        data: data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (data) {
            location.reload();

            var ruta = $("#ruta").val();
            var consecutivo = $("#paciente_codigo").html();
            var expediente = $("#paciente_expediente").html();

            for (var i = 0; i < data.length; i++) {
                if (data[i] == "BH")
                    window.open("reportes/" + ruta + "/reporte-biometria-paciente?codigo=" + consecutivo + "&expediente=" + expediente + "&lab=1");
                if (data[i] == "EGO")
                    window.open("reportes/" + ruta + "/reporte-examen-orina-paciente?codigo=" + consecutivo + "&expediente=" + expediente + "&lab=1");
                if (data[i] == "ESTANDAR")
                    window.open("reportes/" + ruta + "/reporte-estandar-paciente?codigo=" + consecutivo + "&expediente=" + expediente + "&lab=1");
                if (data[i] == "PAQUETE")
                    window.open("reportes/" + ruta + "/reporte-paquete-paciente?codigo=" + consecutivo + "&expediente=" + expediente + "&lab=1");
                if (data[i] == "TEXTO")
                    window.open("reportes/" + ruta + "/reporte-texto-paciente?codigo=" + consecutivo + "&expediente=" + expediente + "&lab=1");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

$(document).on('click', "#preview-reporte", function (event) {

    var tipo = this.dataset.tipo;

    $("#loading").modal("show");
    var codigo = $("#paciente_codigo").html();
    var expediente = $("#paciente_expediente").html();
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        type: "POST",
        url: "controller/laboratorio/Reporte?opc=preview-reporte-paciente",
        data: {codigo: codigo, expediente: expediente, id_sucursal: id_sucursal},
        dataType: "json",
        success: function (data) {
            var ruta = $("#ruta").val();

            setTimeout(function () {
                $("#loading").modal("hide");
            }, 600);

            if (data.length) {
                toastr.error("Sin reportes disponibles");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
            }

            if (data.bh && tipo == "bh") {
                window.open("/reportes/" + ruta + "/reporte-biometria-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1&preview=1");
            }

            if (data.ego && tipo == "ego") {
                window.open("/reportes/" + ruta + "/reporte-examen-orina-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1&preview=1");
            }

            if (data.estandar && tipo == "estandar") {
                window.open("/reportes/" + ruta + "/reporte-estandar-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1&preview=1");
            }

            if (data.paquete && tipo == "paquete") {
                window.open("/reportes/" + ruta + "/reporte-paquete-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1&preview=1");
            }

            if (data.texto && tipo == "texto") {
                window.open("/reportes/" + ruta + "/reporte-texto-paciente?codigo=" + codigo + "&expediente=" + expediente + "&lab=1&preview=1");
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

function validar_impresion() {
    var isImprimir = false;
    var imprimir = $(".imprimir");
    for (var i = 0; i < imprimir.length; i++) {
        if (imprimir[i].checked) {
            isImprimir = true;
        }
    }
    return isImprimir;
}


$(document).on('click', "#restablecer", function (event) {
    var id_estudio = $("#id_estudio").val();
    var id_orden = $("#id_orden").val();
    var tipo = $("#hide_tipo").val();

    if (id_estudio == '') {
        toastr.error("Debe elegir un estudio para restablecer");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    } else {

        if (tipo == "componente") {

            if ($('.tab')[0].disabled) {

                $(".tab").attr("disabled", false);
                $("#save").attr("disabled", false);

                $("#btn-estudio-" + id_estudio).removeClass("text-success");
                $("#btn-estudio-" + id_estudio).addClass("text-primary");

                $.ajax({
                    url: "controller/laboratorio/Reporte?opc=restablecer-reporte",
                    type: "POST",
                    data: {id_estudio: id_estudio, id_orden: id_orden},
                    dataType: "json",
                    success: function (data) {

                        $("#btn-estudio-" + id_estudio).attr("data-impresion", 0);

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });

            } else {
                $("#btn-estudio-" + id_estudio).removeClass("text-primary");
                $("#btn-estudio-" + id_estudio).addClass("text-black ");

                $.ajax({
                    url: "controller/laboratorio/Reporte?opc=borrar-resultado",
                    type: "POST",
                    data: {id_estudio: id_estudio, id_orden: id_orden},
                    dataType: "json",
                    success: function (data) {

                        $("#btn-estudio-" + id_estudio).click();

                        $("#imprimir_" + id_estudio).attr("checked", false);
                        $("#imprimir_" + id_estudio).attr("disabled", true);

                        $("#pagina_" + id_estudio).attr("checked", false);
                        $("#pagina_" + id_estudio).attr("disabled", true);

                        $("#btn-estudio-" + id_estudio).attr("data-reportado", 0);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });

            }
        } else {
            if ($('#save')[0].disabled) {
                $('#reporte-texto').summernote('enable');
                $("#save").attr("disabled", false);

                $("#btn-estudio-" + id_estudio).removeClass("text-success");
                $("#btn-estudio-" + id_estudio).addClass("text-primary");

                $.ajax({
                    url: "controller/laboratorio/Reporte?opc=restablecer-reporte",
                    type: "POST",
                    data: {id_estudio: id_estudio, id_orden: id_orden},
                    dataType: "json",
                    success: function (data) {

                        $("#btn-estudio-" + id_estudio).attr("data-impresion", 0);

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            } else {
                $("#btn-estudio-" + id_estudio).removeClass("text-primary");
                $("#btn-estudio-" + id_estudio).addClass("text-black ");

                $.ajax({
                    url: "controller/laboratorio/Reporte?opc=borrar-resultado-texto",
                    type: "POST",
                    data: {id_estudio: id_estudio, id_orden: id_orden},
                    dataType: "json",
                    success: function (data) {

                        $("#btn-estudio-" + id_estudio).click();

                        $("#imprimir_" + id_estudio).attr("checked", false);
                        $("#imprimir_" + id_estudio).attr("disabled", true);

                        $("#pagina_" + id_estudio).attr("checked", false);
                        $("#pagina_" + id_estudio).attr("disabled", true);

                        $("#btn-estudio-" + id_estudio).attr("data-reportado", 0);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            }
        }
    }
});

$(document).on('click', '.load-activity', function () {
    var id_estudio = this.dataset.id;
    var estudio = this.dataset.estudio;
    $("#bitacora").html(estudio);

    //$(".bg-estudios").removeClass("bg-light");
    //$("#index_" + id_estudio).addClass("bg-light");

    $("#modalBitocara").modal("show");

    $.ajax({
        url: "controller/laboratorio/Reporte?opc=bitacora-estudio",
        type: "POST",
        data: {id_estudio: id_estudio},
        dataType: "json",
        success: function (data) {
            $("#table-bitacora").html("");
            for (var i = 0; i < data.length; i++) {
                $("#table-bitacora").append("<tr>\n\
                <td>" + data[i].observaciones + "</td>\n\
                <td>" + data[i].usuario + "</td>\n\
                <td>" + data[i].fecha + "</td>\n\
                </tr>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

$(document).on('click', ".delete-resultado-pdf", function (event) {
    var id_estudio = this.dataset.id;
    $.ajax({
        url: "controller/laboratorio/Reporte?opc=delete-pdf-estudio",
        type: "POST",
        data: {id_estudio: id_estudio},
        dataType: "json",
        success: function (data) {

            toastr.success("Operación Exitosa");
            $('#toast-container').addClass('toast-top-center');
            $('#toast-container').removeClass('toast-top-right');

            $("#pdf_resultado").addClass("d-none");
            $("#pdf_resultado").html("");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});


$(document).on('click', '.pacientes', function () {

    $("#loading").modal("show");

    var id = this.dataset.id;

    $("#id_estudio").val(id);
    $(".bg-pacientes").removeClass("bg-light");
    $("#index_paciente_" + id).addClass("bg-light");

    $("#id_orden").val(id);

    $.ajax({
        type: "POST",
        url: "controller/laboratorio/Reporte?opc=get-orden-paciente",
        data: {id_orden: id},
        dataType: "json",
        async: false,
        success: function (data) {
            //console.log(data)
            $("#codigo").val(data.codigo);
            $("#id_orden").val(data.id_orden);
            $("#id_paciente").val(data.id);
            $("#hide_edad").val(data.edad);
            $("#hide_tipo_edad").val(data.tipo_edad);
            $("#hide_sexo").val(data.sexo);

            //Cargar datos del paciente
            $("#paciente_codigo").html(data.codigo);
            $("#paciente_expediente").html(data.expediente);
            $("#paciente_nombre").html(data.paciente);
            $("#paciente_fecha").html(data.fecha);
            $("#paciente_sexo").html(data.sexo);
            $("#paciente_edad").html(data.edad);
            $("#paciente_medico").html(data.doctor);
            $("#paciente_observaciones").html(data.observaciones);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

    //Cargar estudios
    $.ajax({
        type: "POST",
        url: "controller/laboratorio/Reporte?opc=get-orden",
        data: {id_orden: id},
        dataType: "html",
        async: false,
        success: function (data) {
            $("#tbody_estudios").html("");
            $("#tbody_estudios").append(data);

            setTimeout(function () {
                $("#loading").modal("hide");
            }, 600);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

$(document).on('click', '.whatsapp', function () {

    var datos_paciente = get_datos_paciente();

    var expediente = datos_paciente.expediente;
    var paciente = datos_paciente.paciente;
    var telPac = datos_paciente.tel;

    var sucursal = datos_paciente.sucursal;
    var telSuc = datos_paciente.tel_sucursal;

    var mensaje = "%C2%A1Hola%20%2A" + paciente + "%2A%21%20%0A%0AHas%20recibido%20una%20notificaci%C3%B3n%20de%20%2A" + sucursal + "%2A%0A%0AIngresa%20a%20https%3A%2F%2F" + document.domain + '%2FPacientes%2Fcontroller%2FAcceso%3Fopc%3Dexpediente%26user%3D' + expediente + " para%20ver%20los%20resultados%20de%20tus%20estudios%20de%20laboratorio%20en%20l%C3%ADnea.%0A%0A_No%20responda%20a%20este%20mensaje%2C%20ha%20sido%20enviado%20de%20manera%20autom%C3%A1tica. Si%20tiene%20dudas%20favor%20de%20comunicarse%20con%20" + sucursal + "%20al%20tel%C3%A9fono%20" + telSuc + "_%0A%0AMensaje%20generado%20por%20Connections.";
    Swal.fire({
        title: 'N&uacute;mero de WhatsApp',
        input: 'text',
        inputValue: telPac,
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        console.log(result);
        if (result.isConfirmed && result.value != "") {
            var win = window.open('https://api.whatsapp.com/send?phone=52' + result.value + '&text=' + mensaje + '', '_blank');

        } else if (result.value == "") {
            Swal.fire('Debes ingresar un n&uacute;mero telef&oacute;nico');
        }
    });

});


$(document).on('click', '.mail', function () {
    var datos_paciente = get_datos_paciente();
    var id = datos_paciente.id;
    var expediente = datos_paciente.expediente;
    var paciente = datos_paciente.paciente;
    var correo = datos_paciente.correo;

    var sucursal = datos_paciente.sucursal;
    var telSuc = datos_paciente.tel_sucursal;
    var img = datos_paciente.img;


    Swal.fire({
        title: 'Correo electr&oacute;nico',
        input: 'text',
        inputValue: correo,
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {

        if (result.isConfirmed && result.value != "") {
            //ajax
            $.ajax({
                type: "POST",
                url: "controller/admision/Resultado?opc=envio-correo",
                data: {id: id, correo: result.value, expediente: expediente, paciente: paciente, sucursal: sucursal, telSuc: telSuc, img: img},
                success: function (data) {
                    if (data == 'ok') {
                        Swal.fire("Correo enviado!", "El correo electrónico con los resultados fueron enviados al paciente correctamente", "success")
                    } else {
                        Swal.fire("Error!", "No pudo enviarse el correo electronico, intente nuevamente", "error")
                    }
                }
            });

        } else if (result.value == "") {
            Swal.fire('Debes ingresar el correo electr&oacute;nico');
        }
    });


});

function get_datos_paciente() {
    var result = [];
    var expediente = $("#paciente_expediente").html();
    var codigo = $("#paciente_codigo").html();

    $.ajax({
        type: "POST",
        url: "controller/laboratorio/Reporte?opc=info-envio-resultados",
        data: {expediente: expediente, codigo: codigo},
        dataType: "json",
        async: false,
        success: function (data) {
            result = data;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

    return result;
}