
$(document).ready(function () {

    $("#tipo_orden").change(function(){
        var valor=$(this).val();
        if(valor=="ORDEN_SAMALAB"){
            $("#tipo_cliente").html(`
                <option value="">Tipo|Cliente</option>
                <option value="REFERIDO">REFERIDO</option>
                <option value="CLINICA">CLINICA</option>
            `)
        }
        else if(valor=="INSTITUCIONP" || valor=="RECETA" || valor=="OTRO" || valor=="WEB" || valor=="FORMATO_RECEPCION" || valor=="FB"){
            $("#tipo_cliente").html(`
                <option value="AQC">AQC</option>
            `)
        }
        else if(valor=="RECETA_REFERIDO" || valor=="ORDEN_CREFERIDA" ){
            $("#tipo_cliente").html(`
                <option value="REFERIDO">REFERIDO</option>
            `)
        }
        else if(valor=="WHATS"){
            $("#tipo_cliente").html(`
                <option value="">Tipo|Cliente</option>
                <option value="REFERIDO">REFERIDO</option>
                <option value="CLINICA">CLINICA</option>
            `)
        }
    })

    $("#busca-doctor").keyup(function(){
        $.ajax({
            url: "controller/catalogos/Doctor?opc=sugerencias_doctores",  
            type: "POST",
            data: {'busqueda':$(this).val(),'id_sucursal' : $("#id_sucursal").val()},
            dataType: 'json',
            success: function (data) {
                console.log("data", data);
                    $("#opcionesDoctores").html(data.opciones) 
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
     })

     $("#busca-doctor").change(function(){
        $.ajax({
            url: "controller/catalogos/Doctor?opc=sugerencias_doctores", 
            type: "POST",
            data: {'busqueda':$(this).val(),'id_sucursal' : $("#id_sucursal").val()},
            dataType: 'json',
            success: function (data) {
                console.log("data", data);
                $("#id_doctor").val(data.datos[0].id)
                $("#dalias").val(data.datos[0].alias)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
     })


//================== Descuentos individuales
    $("#tbody").on('keyup','.pdescuento', function(event){
        var codigo = event.which || event.keyCode;     
        if(codigo === 13){        
            var id=$(this).attr("id").split('_');
            var fila=id[1];
            var precio_neto=$("#precio_publico_"+fila).text().replace(",","");
            var descuentoi=$(this).val();
            var nuevoPrecioPublico=parseFloat(precio_neto)-(parseFloat(precio_neto)*parseFloat(descuentoi)/100)
            $("#precio_neto_"+fila).text(nuevoPrecioPublico.toFixed(2))
            total();
        }
    })

    $("#descuento_estudio").change(function(){
        if($(this).prop("checked")==true)
            $(".display-descuento").removeClass("d-none")
        else{
            $(".display-descuento").addClass("d-none")
            $(".pdescuento").each(function(){
                let id=$(this).attr("id").split('_');
                var fila=id[1];
                let precio_neto=parseFloat($("#precio_neto_"+fila).html().replace(",",''));
                if(precio_neto>0){
                    let porcentaje=100-parseFloat($(this).val());
                    let precio_nuevo=100*precio_neto/porcentaje
                    $(this).val(0);
                    $("#precio_neto_"+fila).html(precio_nuevo.toFixed(2));
                }
            })
            total();
        }
             
    })
//===============================================
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }

    if ($("#cubierto").val() > 0) {
        toastr.error("Orden con pago registrado");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }

    if ($('.select2').html()) {
        $('.select2').select2();
    }

    //load_medicos();


    $("#paterno").click(function(){
       $("#modalBusquedaPacientes").modal('show');
       $("#bpaterno").select()
    })

    $("#bmaterno").keyup(function(){

        if($("#bpaterno").val()!=""){
            $("#bnombre").attr("disabled",false);
        }
        else{
            $("#bnombre").attr("disabled",true);
        }
    })

    $("#bnombre").keyup(function(){
        var fn=$("#bfn").val();
        console.log("fn", fn);
        var paterno=$("#bpaterno").val();
        var materno=$("#bmaterno").val();
        var nombre=$("#bnombre").val();
        var id_sucursal = $("#id_sucursal").val();
        $.ajax({
            url: "controller/admision/Paciente?opc=buscar_paciente", 
            type: "POST",
            data: {'paterno':paterno,'materno':materno,'nombre':nombre,'id_sucursal':id_sucursal,'fecha_nacimiento':fn},

            success: function (data) {
                console.log("data", data);
                $("#bt_pacientes_buscados").html(data)
                if(data.length>10){
                    $("#bregistrar").attr("disabled",true);
                }
                else{
                    $("#bregistrar").attr("disabled",false);
                }
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    $("#bregistrar").click(function(){
        $("#paterno").val($("#bpaterno").val())
        $("#materno").val($("#bmaterno").val())
        $("#nombre").val($("#bnombre").val())
        $("#modalBusquedaPacientes").modal('hide');
    })


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

                    //prueba
                    //event.preventDefault();
                    //event.stopPropagation();

                    var estudios = $(".codigo");

                    if (estudios.length == 0) {
                        event.preventDefault();
                        event.stopPropagation();
                        $(".btn-add-paq").click();
                        return;
                    }

                    //No mandar orden sin estudios
                    var tam = 0;
                    for (var i = 0; i < estudios.length; i++) {
                        if ($(".precio_publico")[i].innerText != "") {
                            tam += 1;
                        }
                    }

                    if (tam == 0) {
                        event.preventDefault();
                        event.stopPropagation();
                        toastr.error("Debe agragar al menos un estudio");
                        $('#toast-container').addClass('toast-top-center');
                        $('#toast-container').removeClass('toast-top-right');
                        return;
                    }

                    $("#loading").modal("show");

                    //Enviar precio publico y precio neto
                    $(".codigo").attr("disabled", false);
                    $(".precio_neto").attr("disabled", false);
                    $("#total").attr("disabled", false);
                    $("#id_descuento").attr("disabled", false);
                    $("#aumento").attr("disabled", false);
                    $("#id_empresa").attr("disabled", false);


                    for (var i = 0; i < estudios.length; i++) {
                        var table = $("#table_registro");
                        if ($(".precio_publico")[i].innerText != "") {
                    //-------------------------- Tomando en cuenta la maquila--------------------
                            if ($(".maquila")[i].innerText == "NO" || $(".maquila")[i].innerText == "") {
                                table.append("<input type='hidden' name='estudio_maquila[]' value='0'>");
                                table.append("<input type='hidden' name='precio_maquila[]' value='0'>");
                            } else {
                                let id = $(".maquila")[i].id;
                                if ($("#" + id + " .cmaquila").prop("checked") == true) {
                                    let id_estudio = $("#" + id + " .cmaquila").attr('data-id')
                                    let precio_estudio = $("#" + id + " .cmaquila").attr('data-precio')
                                    table.append("<input type='hidden' name='estudio_maquila[]' value='" + id_estudio + "'>");
                                    table.append("<input type='hidden' name='precio_maquila[]' value='" + precio_estudio + "'>");
                                } else {
                                    table.append("<input type='hidden' name='estudio_maquila[]' value='0'>");
                                    table.append("<input type='hidden' name='precio_maquila[]' value='0'>");
                                }
                            }
                    //-------------------------Tomando en cuenta la maquila/-----------------------------

                            table.append("<input type='hidden' name='paquete[]' value='" + $(".paquete")[i].innerText + "'>");
                            table.append("<input type='hidden' name='precio_publico[]' value='" + $(".precio_publico")[i].innerText + "'>");
                            table.append("<input type='hidden' name='precio_neto[]' value='" + $(".precio_neto")[i].innerText + "'>");
                            var aux = ($(".fecha_entrega")[i].innerText).split("/");
                            table.append("<input type='hidden' name='fecha_entrega[]' value='" + aux.reverse().join("-") + "'>");
                            table.append("<input type='hidden' name='id_detalle[]' value='" + ($(".precio_publico")[i].dataset.id_detalle == undefined ? "" : $(".precio_publico")[i].dataset.id_detalle) + "'>");

                        } else {
                            $(".codigo")[i].value = "";
                        }
                    }

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
        var forms = document.getElementsByClassName('needs-validation-codigo');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


$(document).on('keydown', ".form-control, .custom-select", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        event.preventDefault();
        event.stopPropagation();
    }

});

$(document).on('keydown', ".tab", function (event) {
    var id = this.dataset.id;
    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {

        var index = $('.tab').index(this);
        if (index < $('.tab').length - 1)
            $('.tab')[index + 1].focus();
    }
});


function load_medicos() {
    //Carga de medicos en el auto
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        url: "controller/catalogos/Doctor?opc=doctores",
        type: "POST",
        data: {id_sucursal: id_sucursal},
        dataType: "json",
        success: function (data) {
            console.log("data", data);

            $("#medico").autocomplete({
                source: data,
                select: function (event, ui) {
                    $("#id_doctor").val(ui.item.id);
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

var index = $("tbody tr").length + 1;
$(document).on('click', '.btn-add-paq', function () {

    var modalidad = $("#modalidad").val();
    if (modalidad == 2) {
        $(".display-descuento").removeClass("d-none");
    }

    var table = $("#table_registro");
    var visualizar_descuento="d-none";
    if($("#descuento_estudio").prop("checked")==true){
        visualizar_descuento="";
    }
    table.append("<tr id='est_" + index + "' class='text-center'>\n\
        <td><input id='codigo_" + index + "' type='text' class='form-control form-control-border text-uppercase codigo' data-id='" + index + "'  name='codigo[]' value='' placeholder='Código'></td>\n\
        <td></td>\n\
        <td class='paquete'></td>\n\
        <td class='precio_publico' id='precio_publico_"+index+"'></td>\n\
        <td class='precio_neto' id='precio_neto_"+index+"'></td>\n\
        <td class='fecha_entrega'></td>\n\
        <td class='maquila' id='maquila_" + index + "'></td>\n\
        <td align='center'>\n\
            <button type='button' class='btn btn-danger btn-sm delete-estudio rounded-circle mt-1 mb-1' data-id='" + index + "'><i class='fas fa-trash'></i></button>\n\
            <button type='button' class='btn btn-sm info-estudio btn-info rounded-circle m-1' data-id='" + index + "'><i class='fas fa-eye'></i></button>\n\
        </td>\n\
        <td> \n\
            <div class='custom-control custom-checkbox "+visualizar_descuento+" display-descuento'>\n\
                <div class='row m-0 p-0'>\n\
                    <div class='col-1 m-0 p-0'>\n\
                        <input id='descuento_" + index + "' checked='' type='checkbox' name='descuento[]' value='" + index + "' class='custom-control-input descuento'>\n\
                        <label class='custom-control-label' for='descuento_" + index + "'></label>\n\
                    </div>\n\
                <div class='col-11 m-0 p-0'>\n\
                    <input type='number' class='form-control pdescuento' id='pdescuento_"+index+"' name='p_descuento[]' value='0' style='display: inline-block;width:100%;'>\n\
                </div>\n\
            </div>\n\
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

$(document).on('click', '.delete-estudio', function () {
    var id = this.dataset.id;
    var paq = this.dataset.paq;

    if ($('.delete-estudio').length == 1) {
        toastr.error("Debe agragar al menos un estudio");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        return;
    }

    if (paq == undefined) {
        var tr = $('.delete-estudio');

        for (var i = 0; i < tr.length; i++) {
            var id_actual = tr[i].dataset.id;
            var borrar = tr[i].dataset.borrar;

            if (id_actual == id && (borrar == "1" || borrar == undefined)) {
                $('#est_' + id).remove();
                total();
                return;
            }
        }

        toastr.error("El estudio ya se encuentra reportado y/o impreso");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');

    } else {
        var tr = $('.delete-estudio');
        //Validar sino el paquete no esta reportado o impreso
        for (var i = 0; i < tr.length; i++) {
            var aux = tr[i].dataset.borrar;
            if (aux == 0) {
                toastr.error("El estudio ya se encuentra reportado y/o impreso");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
                return;
            }
        }

        for (var i = 0; i < tr.length; i++) {
            var id = tr[i].dataset.id;
            var alias = tr[i].dataset.paq;
            if (alias != undefined && paq == alias) {
                $('#est_' + id).remove();
            }
        }

    }

    total();

});

$(document).on('click', '.info-estudio', function () {
    var actual = this.dataset.id;

    var codigo = $('.codigo');
    for (var i = 0; i < codigo.length; i++) {
        var id = codigo[i].dataset.id;
        if (actual == id) {
            var alias = codigo[i].value;
            var id_sucursal = $('#id_sucursal').val();

            if (alias != "") {

                $.ajax({
                    url: "controller/admision/Paciente?opc=get-instrucciones",
                    type: "POST",
                    data: {alias: alias, id_sucursal: id_sucursal},
                    dataType: "json",
                    success: function (data) {

                        if (data.length == 0) {

                            Swal.fire({
                                title: '<h2>Indicaciones</h2>',
                                text: "Sin indicaciones",
                                icon: 'info',
                                confirmButtonText: 'CERRAR',
                                //allowOutsideClick: false
                            });
                        } else {
                            Swal.fire({
                                title: '<h2>Indicaciones</h2>',
                                text: data[0].indicacion,
                                icon: 'info',
                                confirmButtonText: 'TERMINAR',
                                // allowOutsideClick: CERRAR
                            });
                        }
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

//Parche para moviles

$(document).on('blur', ".codigo", function (event) {
    var codigo = this;
    var id = this.dataset.id;

    var width = $(window).width();
    if (width < 768) {

        //condicion de paro
        if (codigo.disabled) { //Doble enter en autocomplet
            return;
        }
        search_estudio_paquete(codigo, id);
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

        search_estudio_paquete(codigo, id);
    }
});

function search_estudio_paquete(codigo, id) {
    //funcion de busqueda
    var estudio = $(codigo).val();
    var id_sucursal = $("#id_sucursal").val();
    var id_empresa = $("#id_empresa").val();
    var modalidad=0;
    if($("#maquila").prop("checked")==true)
        modalidad=1;
    $("#loading").modal("show");
    $.ajax({
        url: "controller/admision/Paciente?opc=estudios-paquetes",
        type: "POST",
        data: {estudio: estudio, id_sucursal: id_sucursal, id_empresa: id_empresa, modalidad: modalidad},
        dataType: "json",
        success: function (data) {
            //console.log("data", data);
            setTimeout(function () {
                $("#loading").modal("hide");
            }, 500);
            if (data.length == 0) {
                $(codigo).val("");
                setTimeout(function () {
                    toastr.error("Estudio o paquete no válido");
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

function setEstudio(data, id, codigo) {
    //Validar estudio repetido

    var repetido = validarEstudios(data.value);
    if (repetido == 0) {
        if (data.tipo == 'paquete') {

            for (var i = 0; i < data.paquete.length; i++) {

                var repetidoPaquete = validarEstudios(data.paquete[i].alias);
                if (repetidoPaquete != 0) {
                    $(codigo).val("");
                    setTimeout(function () {
                        toastr.error("El estudio " + data.paquete[i].alias + " ya se encuentra registrado");
                        $('#toast-container').addClass('toast-top-center');
                        $('#toast-container').removeClass('toast-top-right');
                    }, 500);
                    return;
                }
            }

            $('#est_' + id).remove();

            var table = $("#table_registro");
            var visualizar_descuento="d-none";
            if($("#descuento_estudio").prop("checked")==true){
                visualizar_descuento="";
            }
            for (var i = 0; i < data.paquete.length; i++) {
                var maquila = "";
                if (i == 0) {
                    maquila = data.precio_maquila;
                }

                table.append("<tr id='est_" + index + "' class='text-center'>\n\
                    <td><input type='text' class='form-control form-control-border text-uppercase codigo' data-id='" + index + "' data-porcentaje='" + data.paquete[i].porcentaje + "'  name='codigo[]' value='" + data.paquete[i].alias + "' placeholder='Código' disabled></td>\n\
                    <td>" + data.paquete[i].nombre_estudio + "</td>\n\
                    <td class='paquete'>" + data.value + "</td>\n\
                    <td id='precio_publico_"+index+"' class='precio_publico' data-precio='" + data.paquete[i].precio_neto + "'> " + formatter.format(data.paquete[i].precio_publico) + "</td>\n\
                    <td id='precio_neto_"+index+"' class='precio_neto'>" + formatter.format(data.paquete[i].precio_neto) + "</td>\n\
                    <td class='fecha_entrega'>" + data.paquete[i].fecha_entrega + "</td>\n\
                    <td class='maquila' id='maquila_" + index + "'>" + maquila + "</td>\n\
                    <td align='center'>\n\
                        <button type='button' class='btn btn-danger btn-sm delete-estudio rounded-circle mt-1 mb-1' data-id='" + index + "' data-paq='" + data.value + "'><i class='fas fa-trash'></i></button>\n\
                        <button type='button' class='btn btn-sm info-estudio btn-info rounded-circle m-1' data-id='" + index + "' data-paq='" + data.value + "'><i class='fas fa-eye'></i></button>\n\
                    </td>\n\
                    <td> \n\
                        <div class='custom-control custom-checkbox "+visualizar_descuento+" display-descuento'>\n\
                            <div class='row m-0 p-0'>\n\
                                <div class='col-1 m-0 p-0'>\n\
                                    <input id='descuento_" + index + "' " + (i != 0 ? "disabled" : "") + " data-paq='" + data.value + "' checked='' type='checkbox' name='descuento[]' value='" + index + "' class='custom-control-input descuento'>\n\
                                    <label class='custom-control-label' for='descuento_" + index + "'></label>\n\
                                </div>\n\
                            <div class='col-11 m-0 p-0'>\n\
                                <input type='number' class='form-control pdescuento' id='pdescuento_"+index+"' name='p_descuento[]' value='0' style='display: inline-block;width:100%;'>\n\
                            </div>\n\
                        </div>\n\
                    </td>\n\
                </tr>");
                index++;
            }
        } else {

            $(codigo).attr("disabled", true);
            $(codigo).val(data.value);
            $(codigo).attr("data-porcentaje", data.porcentaje);
            var currentRow = $("#est_" + id).closest("tr");
            currentRow.find("td:eq(1)").html(data.label);
            currentRow.find("td:eq(2)").html("");
            currentRow.find("td:eq(3)").html(data.precio);
            currentRow.find("td:eq(4)").html(data.precio_neto);
            currentRow.find("td:eq(5)").html(data.fecha_entrega);
            currentRow.find("td:eq(6)").html(data.precio_maquila);
        }

        //Recalcular precio neto por aumento o descuento
        var descuento = $("#id_descuento").val();
        var aumento = $("#aumento").val();
        if (descuento != "") {
            $("#id_descuento").change();
        } else if (aumento != "" && aumento != "0") {
            $("#aumento").blur();
        }
        total();
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
    currentRow.find("td:eq(5)").html("");
    total();
});
function calcular_dias(fechaIni, fechaFin)
{
    var fecha1 = new Date(fechaIni.substring(0, 4), fechaIni.substring(5, 7), fechaIni.substring(8, 10));
    var fecha2 = new Date(fechaFin.substring(0, 4), fechaFin.substring(5, 7), fechaFin.substring(8, 10));
    var diasDif = fecha2.getTime() - fecha1.getTime();
    var dias = Math.round(diasDif / (1000 * 60 * 60 * 24));
    return dias;
}

function calcular_dias(fechaIni, fechaFin)
{
    var fecha1 = new Date(fechaIni.substring(0, 4), fechaIni.substring(5, 7), fechaIni.substring(8, 10));
    var fecha2 = new Date(fechaFin.substring(0, 4), fechaFin.substring(5, 7), fechaFin.substring(8, 10));
    var diasDif = fecha2.getTime() - fecha1.getTime();
    var dias = Math.round(diasDif / (1000 * 60 * 60 * 24));
    return dias;
}

function calcular_edad_fecha() {
    var fecha_nac = $("#fecha_nac").val();
    var hoy = new Date().toISOString();
    var dias = calcular_dias(fecha_nac, hoy);
    var edad = [];
    if (dias == 0) {
        var hora = hoy.substring(11, 13);
        //Se resta 6 hrs por la zona horaria
        edad = [parseInt(hora) - 6, "Horas"];
    } else if (dias <= 30) {
        edad = [dias, "Dias"];
    } else if (dias < 365) {
        edad = [(dias / 30.4) | 0, "Meses"];
    } else {
        edad = [((dias / 30.4) / 12) | 0, "Anios"];
    }

    $("#edad").val(edad[0]);
    $("#tipo_edad").val(edad[1]);
}

$(document).on('change', '#fecha_nac', function () {
    calcular_edad_fecha();
    isNino();
});

function calcular_edad() {
    var tipo_edad = $("#tipo_edad").val();
    var edad = $("#edad").val();
    var dias = 0;
    if (tipo_edad == "Anios") {
        dias = edad * 365.4;
    } else if (tipo_edad == "Meses") {
        dias = edad * 30.4;
    } else if (tipo_edad == "Meses") {
        dias = edad;
    }

    var fecha_nac = $("#fecha_nac").val();
    if (fecha_nac == "") {
        var hoy = new Date();
        hoy.setDate(hoy.getDate() - dias);
        var fecha_nac = hoy.toISOString();
        $("#fecha_nac").val(fecha_nac.substring(0, 10));
    }

}

function isNino() {
    //comentado por vilar
    /*var tipo_edad = $("#tipo_edad").val();
     var edad = $("#edad").val();
     if (tipo_edad != "Anios") {
     $("#sexo").val("Nino");
     } else if (edad <= 12) {
     $("#sexo").val("Nino");
     } else {
     $("#sexo").val("Masculino");
     }*/
}

$(document).on('keydown', "#edad", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == 13) {
        calcular_edad();
        isNino();
    }

});

$(document).on('blur', "#edad", function (event) {

    calcular_edad("");
    isNino();
});

$(document).on('change', '#tipo_edad', function () {
    calcular_edad();
    isNino();
});

$(document).on('click', '.clear-medico', function () {
    $("#busca-doctor").val("");
    $("#id_doctor").val("");
    $("#dalias").val("");
});

$(document).on('click', '.load-ordenes', function () {
    buscar_pacienes();
});

function buscar_pacienes() {
    var datos = new FormData($(".needs-validation-pacientes")[0]);
    datos.append("id_sucursal", $("#id_sucursal").val());
    $("#loading").modal("show");
    //AJAX buscar orden
    $.ajax({
        url: "controller/admision/Paciente?opc=ordenes", 
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {
            //console.log(data);
            $("#table-pacientes").html("");
            for (var i = 0; i < data.length; i++) {
                var consecutivoColon='';
                if(data[i].consecutivo_maquila_imagen!=null && data[i].consecutivo_maquila_imagen!='null')
                    consecutivoColon='<BR> colon -'+data[i].consecutivo_maquila_imagen
                $("#table-pacientes").append("<tr class=" + (data[i].cancelado == 1 ? "text-danger" : "") + ">\n\
                <td>" + data[i].consecutivo + "</td>\n\
                <td>" + data[i].paciente + "</td>\n\
                <td>" + data[i].fecha_nacimiento + "</td>\n\
                <td>" + data[i].expediente + "</td>\n\
                <td>" + data[i].fecha_orden + "</td>\n\
                <td>" + (data[i].cancelado == 1 ? "<span class='text-danger'>CANCELADO</span>" : data[i].credito == 1 ? "<span class='text-primary'>CRÉDITO</span>" : data[i].saldo_deudor == 0 ? "<span class='text-success'>PAGADO</span>" : "<span class='text-danger'>" + formatter.format(data[i].saldo_deudor) + "</span>") + "</td>\n\
                <td>" + data[i].orden_maquila + consecutivoColon +  "</td>\n\
                <td align='center'>   \n\
                    <button class='btn btn-sm btn-info rounded-circle m-1 cargar-orden' data-id='" + data[i].id + "' title='Orden'>\n\
                        <i class='fas fa-address-card'></i>\n\
                    </button>\n\
                    <button class='btn btn-sm btn-primary rounded-circle m-1 cargar-paciente' data-id='" + data[i].id_paciente + "' title='Pacientes'>\n\
                        <i class='fas fa-user-plus'></i>\n\
                    </button>\n\
                    <a href='caja?codigo=" + data[i].consecutivo + "' class='btn btn-sm btn-success rounded-circle m-1' title='Caja'>\n\
                        <i class='fas fa-credit-card'></i>\n\
                    </a>\n\
                    <a href='recibos/recibo" + ($("#id_ticket").val() == 3 ? "-media" : "") + "?codigo=" + data[i].consecutivo + "' target='_blank' class='btn btn-sm btn-warning rounded-circle m-1' title='Recibo'>\n\
                        <i class='far fa-file-pdf'></i>\n\
                    </a></td>\n\
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

(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation-pacientes');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();
                    buscar_pacienes();
                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

$(document).on('click', '.cargar-orden', function () {
    var id = this.dataset.id;
    $("#id_orden").val(id);
    $("#modalPacientes").modal("hide");
    $.ajax({
        url: "controller/admision/Paciente?opc=get-orden",
        type: "POST",
        data: {id_orden: id},
        dataType: "json",
        success: function (data) {
 
            //console.log(data);

            $("#folio").html("Orden: <span class='font-weight-bold border-bottom '>" + data.orden[0].consecutivo + "</span>");
            $("#paterno").val(data.orden[0].paterno);
            $("#materno").val(data.orden[0].materno);
            $("#nombre").val(data.orden[0].nombre);
            $("#fecha_nac").val(data.orden[0].fecha_nac);
            $("#edad").val(data.orden[0].edad);
            $("#tipo_edad").val(data.orden[0].tipo_edad);
            $("#sexo").val(data.orden[0].sexo);
            $("#direccion").val(data.orden[0].direccion);
            $("#email").val(data.orden[0].cpEmail);
            $("#tel").val(data.orden[0].tel);
            $("#medico").val(data.orden[0].nombre_doctor);
            $('.select-registro').select2("destroy");
            $(".select-registro").val(data.orden[0].empresa_id);
            $('.select-registro').select2();

            //Adicionales
            $("#filiacion").val(data.orden[0].filacion);
            $("#fur").val(data.orden[0].fur);
            $("#fud").val(data.orden[0].fud);

            if (data.fiscal[0] != null) {
                //Facturación
                $("#cliente").val(data.fiscal[0].nombre_fiscal);
                $("#rfc").val(data.fiscal[0].rfc);
                $("#domicilio").val(data.fiscal[0].direccion_fiscal);
                $("#cp").val(data.fiscal[0].codigo_postal);
                $("#mail").val(data.fiscal[0].correo);
                $("#cfdi").val(data.fiscal[0].direccion_fiscal);
                $('#id_cfdi').select2("destroy");
                $("#id_cfdi").val(data.fiscal[0].uso_cfdi);
                $('#id_cfdi').select2();
            } else {
                $("#cliente").val("");
                $("#rfc").val("");
                $("#domicilio").val("");
                $("#cp").val("");
                $("#mail").val("");
                $("#cfdi").val("");
                $('#id_cfdi').select2("destroy");
                $("#id_cfdi").val("");
                $('#id_cfdi').select2();
            }

            $("#fecha_entrega").val(data.orden[0].fecha_entrega);
            $("#id_descuento").val(data.orden[0].id_descuento);
            $("#aumento").val(data.orden[0].aumento);


            $("#save").attr("disabled", true);
            $(".menssage").html(data.orden[0].cancelado == 1 ? "<span class='text-danger'>CANCELADO</span>" : data.orden[0].credito == 1 ? "<span class='text-primary'>CRÉDITO</span>" : data.orden[0].saldo_deudor == 0 ? "<span class='text-success'>PAGADO</span>" : "<span class='text-danger'>Saldo deudor: " + formatter.format(data.orden[0].saldo_deudor) + "</span>");

            //Detalle orden
            $("#tbody").html("");
            var table = $("#table_registro");
            for (var i = 0; i < data.detalle.length; i++) {
                table.append("<tr id='est_" + index + "' class='text-center " + (data.detalle[i].impresion > 0 ? "text-success" : (data.detalle[i].reportado > 0 ? "text-primary" : "text-black")) + "'>\n\
                <td><input type='text' class='form-control form-control-border text-uppercase codigo' data-id='" + index + "'  name='codigo[]' value='" + data.detalle[i].alias + "' placeholder='Código' required='' disabled></td>\n\
                <td>" + data.detalle[i].nombre_estudio + "</td>\n\
                <td class='paquete'>" + (data.detalle[i].paquete != null ? data.detalle[i].paquete : "") + "</td>\n\
                <td class='precio_publico'>" + formatter.format(data.detalle[i].precio_publico) + "</td>\n\
                <td class='precio_neto'>" + formatter.format(data.detalle[i].precio_neto_estudio) + "</td>\n\
                <td class='fecha_entrega'>" + data.detalle[i].fecha_entrega + "</td>\n\
                <td class='maquila'>" + data.detalle[i].envio_maquila + "</td>\n\
                <td align='center'>\n\
                    <button type='button' class='btn btn-danger btn-sm rounded-circle mt-1 mb-1' data-id='" + index + "'><i class='fas fa-trash'></i></button>\n\
                    <button type='button' class='btn btn-sm btn-info rounded-circle m-1' data-id='" + index + "'><i class='fas fa-eye'></i></button>\n\
                </td>\n\
            </tr>");
                index++;
            }

            total();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});

$(document).on('click', '.cargar-paciente', function () {
    var id = this.dataset.id;
    $("#id_paciente").val(id);
    $("#modalPacientes").modal("hide");
    $.ajax({
        url: "controller/admision/Paciente?opc=get-paciente",
        type: "POST",
        data: {id_paciente: id},
        dataType: "json",
        success: function (data) {

            //console.log(data);
            $("#paterno").val(data[0].paterno);
            $("#materno").val(data[0].materno);
            $("#nombre").val(data[0].nombre);
            $("#fecha_nac").val(data[0].fecha_nac);
            $("#edad").val(data[0].edad);
            $("#tipo_edad").val(data[0].tipo_edad);
            $("#sexo").val(data[0].sexo);
            $("#direccion").val(data[0].direccion);
            $("#email").val(data[0].cpEmail);
            $("#tel").val(data[0].tel);
            $("#modalBusquedaPacientes").modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});

function fecha_entrega() {
    var max = "";
    var fecha = $(".fecha_entrega");
    var entrega = "";
    for (var i = 0; i < fecha.length; i++) {
        if (fecha[i].innerText != "") {
            entrega = fecha[i].innerText;
            var aux = entrega.split("/");
            var tmp = aux.reverse().join("-");

            if (tmp > max) {
                max = tmp;
            }
        }
    }

    var aux = entrega.split("-");
    var tmp = aux.reverse().join("/");
    $("#fecha_entrega").html(tmp);

}

function total() {
    var total_neto = total_precio_neto();
    var total_cerrado = Math.round(total_neto);

    var diff = (total_cerrado - total_neto);
    if (diff.toFixed(2) > 0 || diff.toFixed(2) < 0) {
        var temp = parseFloat($(".precio_neto")[0].innerText.replace(",", ""));
        $(".precio_neto")[0].innerText = (temp + diff).toFixed(2);
        $("#total").val(formatter.format(total_cerrado));
        //console.log("diff");
    } else {
        $("#total").val(formatter.format(total_neto));
        //console.log("no");
    }


    setTimeout(function () {
        abono_monedero();
    }, 500);

    fecha_entrega();
}

function abono_monedero() {
    var abono = 0;
    var precio = $(".precio_neto");
    var codigo = $(".codigo");
    for (var i = 0; i < precio.length; i++) {
        if (precio[i].innerText != "") {
            var importe = parseFloat((precio[i].innerText).replace(",", ""));
            var porcentaje = codigo[i].dataset.porcentaje;
            //console.log(codigo[i]);
            if (porcentaje > 0) {
                abono += importe * (porcentaje / 100);
            }
        }
    }

    $("#abono").html(formatter.format(abono));

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

$(document).on('change', '#id_descuento', function () {

    var descuento = $('#id_descuento option:selected').data("descuento");
    var porc = 0;
    var precio = $(".precio_publico");
    var neto = $(".precio_neto");
    var pdescuento=$(".pdescuento");
    for (var i = 0; i < precio.length; i++) {
        if (precio[i].innerText != "" &&  pdescuento[i].value=='0' ) {
            var actual = $(".descuento")[i].checked;
            if (!actual) {
                porc = 1;
            } else {
                porc = (100 - parseFloat(descuento)) / 100;
            }
            //si es paquete debe sacar descuento del costo del paquete
            var precio_paq = parseFloat(precio[i].dataset.precio);
            var importe = precio_paq > 0 ? precio_paq * porc : parseFloat((precio[i].innerText).replace(",", "")) * porc;
            neto[i].innerText = formatter.format(importe);
        }
    }

    if (descuento == 0) {

        $("#aumento").attr("disabled", false);
        $("#total").attr("disabled", false);
    } else {
        $("#aumento").attr("disabled", true);
        $("#total").attr("disabled", true);
    }

    total();
});

$(document).on('blur', '#total', function () {

    var total = this.value.replace(",", "");
    calculo_importe(total);
});


$(document).on('keydown', "#total", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == 13) {
        var total = this.value.replace(",", "");
        calculo_importe(total);
    }

});

function calculo_importe(total_) {

    var actual = total_publico();
    var porc = total_ / actual;

    var precio = $(".precio_publico");
    var neto = $(".precio_neto");

    for (var i = 0; i < precio.length; i++) {
        if (precio[i].innerText != "") {

            //si es paquete debe sacar importe del costo del paquete
            var precio_paq = parseFloat(precio[i].dataset.precio);
            var importe = precio_paq > 0 ? precio_paq * porc : parseFloat((precio[i].innerText).replace(",", "")) * porc;
            neto[i].innerText = formatter.format(importe);
        }
    }

    $("#aumento").attr("disabled", true);
    $("#id_descuento").attr("disabled", true);

    total();//ajuste de centavos

}

function total_publico() {
    var precio = $(".precio_publico");
    var importe = 0;
    for (var i = 0; i < precio.length; i++) {
        if (precio[i].innerText != "") {

            //si es paquete debe sacar importe del costo del paquete
            var precio_paq = parseFloat(precio[i].dataset.precio);
            importe += precio_paq > 0 ? precio_paq : parseFloat((precio[i].innerText).replace(",", ""));

        }
    }
    return importe;

}

$(document).on('keydown', '#aumento', function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == 13) {
        calcular_aumento();

    }

});


$(document).on('blur', '#aumento', function (event) {

    calcular_aumento();

});


function calcular_aumento() {
	

    var aumento = $('#aumento').val() ? $('#aumento').val() : 0;
	if (aumento < 0) {
                toastr.error("El aumento debe ser mayor de 0");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
                return;
            }	
    var porc = (100 + parseFloat(aumento)) / 100;
    var precio = $(".precio_publico");
    var neto = $(".precio_neto");
    for (var i = 0; i < precio.length; i++) {
        if (precio[i].innerText != "") {
            var precio_paq = parseFloat(precio[i].dataset.precio);
            var importe = precio_paq > 0 ? precio_paq * porc : parseFloat((precio[i].innerText).replace(",", "")) * porc;
            neto[i].innerText = formatter.format(importe);
        }
    }

    if (aumento == "" || aumento == 0) {
        $("#id_descuento").attr("disabled", false);
        $("#total").attr("disabled", false);
    } else {
        $("#id_descuento").attr("disabled", true);
        $("#total").attr("disabled", true);
    }

    total();
}



$(document).on('change', '#id_empresa', function () {

    var id_empresa = $(this).val();
    if (id_empresa == "") {
        $("#id_descuento").attr("disabled", false);
        $("#aumento").attr("disabled", false);
    } else {
        var cliente = $("#ruta").val();
        if (cliente == "vilar") {//Solo descuento
            $("#id_descuento").attr("disabled", false);
            $("#aumento").attr("disabled", true);
        } else if (cliente == "alquimia") {//Solo Aumento
            $("#id_descuento").attr("disabled", true);
            $("#aumento").attr("disabled", false);
        } else if (cliente == "biomedical") {//Descuento y aumento
            $("#id_descuento").attr("disabled", false);
            $("#aumento").attr("disabled", false);
        } else {
            $("#id_descuento").attr("disabled", true);
            $("#aumento").attr("disabled", true);
        }


    }

    var precio = $(".precio_neto");

    for (var i = 0; i < precio.length; i++) {
        if (precio[i].innerText != "") {
            $('#id_empresa').select2("destroy");
            $('#id_empresa').val("");
            $('#id_empresa').select2();
            Swal.fire({
                title: '<h2>Alerta</h2>',
                text: "Debe borrar los estudios registrados, para seleccionar una empresa ",
                icon: 'warning',
                confirmButtonText: 'CERRAR',
                //allowOutsideClick: false
            });
            return;
        }
    }
});

$(document).on('change', '#modalidad', function () {
    var modalidad = $(this).val();
    if (modalidad != 1) {
        $("#orden_matriz").addClass("d-none");

        if (modalidad == 2) {

            var descuento = $("#id_descuento").val();

            if (descuento == "") {
                $("#modalidad").val("");

                toastr.error("Debes seleccionar un Descuento");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
                return;

            } else {
                $(".display-descuento").removeClass("d-none");
            }
        }
    } else {
        $("#orden_matriz").removeClass("d-none");
    }

});

$(document).on('change', '.descuento', function () {

    var paq = this.dataset.paq;
    var actual = this.checked;


    if (paq != undefined) {

        var tr = $('.descuento');
        for (var i = 0; i < tr.length; i++) {
            var alias = tr[i].dataset.paq;
            if (alias != undefined && paq == alias) {
                $(".descuento")[i].checked = actual;
            }
        }
    }

    $("#id_descuento").change();

});


$(document).on('click', '.reset-orden', function () {

    $("#id_descuento").val("");
    $("#aumento").val("");
    $("#aumento").attr("disabled", false);
    $("#id_descuento").attr("disabled", false);
    $("#total").attr("disabled", false);

    var precio = $(".precio_publico");
    var neto = $(".precio_neto");
    for (var i = 0; i < precio.length; i++) {

        if (precio[i].innerText != "") {

            var precio_publico = parseFloat(precio[i].dataset.publico);
            neto[i].innerText = formatter.format(precio_publico);
        }
    }



    total();
});

$(document).on('click', '.btn-save-tarjeta', function () {


    var tarjeta = $("#nueva_tarjeta").val();

    if (tarjeta == "") {
        toastr.error("Favor de capturar Nueva Tarjeta");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        return;
    }

    $("#loading").modal("show");
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        url: "controller/admision/Paciente?opc=registro-tarjeta",
        type: "POST",
        data: {id_sucursal: id_sucursal, tarjeta: tarjeta},
        dataType: "json",
        success: function (data) {
            setTimeout(function () {
                $("#loading").modal("hide");
            }, 800);


            $("#nueva_tarjeta").val("");
            $("#no_tarjeta").val(data[0].numero);
            $("#saldo").html(data[0].saldo);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});

$(document).on('click', '.btn-search-tarjeta', function () {

    var tarjeta = $("#no_tarjeta").val();

    if (tarjeta == "") {
        toastr.error("Favor de capturar No. Tarjeta");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        return;
    }

    $("#loading").modal("show");
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        url: "controller/admision/Paciente?opc=search-tarjeta",
        type: "POST",
        data: {id_sucursal: id_sucursal, tarjeta: tarjeta},
        dataType: "json",
        success: function (data) {
            setTimeout(function () {
                $("#loading").modal("hide");
            }, 800);

            $("#saldo").html(data[0].saldo);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});

$(document).on('click', '.btn-cancear-orden', function () {

    var id = $("#id_orden").val();
    var nombre = $("#codigo").val();
    $("#id_orden_").val(id);
    $("#nombre_").html(nombre);
    $("#modConfirmarDelete").modal('show');
});


$(document).on('click', '.btn-activar-orden', function () {

    var id = $("#id_orden").val();
    var nombre = $("#codigo").val();
    $("#id_orden_2").val(id);
    $("#nombre_2").html(nombre);
    $("#modConfirmar").modal('show');
});

