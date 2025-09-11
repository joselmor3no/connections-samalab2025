var listaSugerenciasEstudiosDatos;
$(document).ready(function () {
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }
    if ($(".custom-file-input")[0]) {
        bsCustomFileInput.init();
    }


// =========================  MAQUILA =====================================
$("#tbt_lpreciosm").on('keyup', '.opciones-estudios', function () {
    var arrId=$(this).attr("id").split("_");
    var fila=parseFloat(arrId[1]);
    var id_sucursal=$("#id_sucursalm").val();
    var busqueda=$(this).val();
    $.ajax({
        url: "controller/Sucursal?opc=lista_estudio&busqueda="+busqueda+"&id_sucursal="+id_sucursal,
        type: "POST",
        async: false,
        data: {},
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {
            //console.log("data", data);
            $("#opcionesEstudios_"+fila).html(data.opciones)
            listaSugerenciasEstudiosDatos=data.datos;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});


$("#tbt_lpreciosm").on('blur', '.opciones-estudios', function () {
    var arrId=$(this).attr("id").split("_");
    var fila=parseFloat(arrId[1]);
    if(listaSugerenciasEstudiosDatos[0].codigo!=undefined && $("#codigo_"+(fila+1)).val()!="" ){
        $("#id_cat_estudio_"+fila).val(listaSugerenciasEstudiosDatos[0].id)
        $("#codigo_"+fila).val(listaSugerenciasEstudiosDatos[0].codigo.trim())
        $("#precio_publico_"+fila).val(listaSugerenciasEstudiosDatos[0].precio_publico.trim())
        
        $("#numero_estudiosm").val(fila);
        var trtabla=`<tr id="fila_`+(fila+1)+`">
                        <td>
                            <input type="text" class="form-control opciones-estudios" list="opcionesEstudios_`+(fila+1)+`" id="estudio_`+(fila+1)+`" name="estudio_`+(fila+1)+`" autocomplete="off">
                            <datalist id="opcionesEstudios_`+(fila+1)+`">

                            </datalist>
                            <input type="hidden" id="id_cat_estudio_`+(fila+1)+`" name="id_cat_estudio_`+(fila+1)+`">
                            <input type="hidden" id="idpaquetem_`+(fila+1)+`" name="idpaquetem_`+(fila+1)+`">
                        </td>
                        <td>
                            <input type="text" class="form-control" id="codigo_`+(fila+1)+`" disabled >
                        </td>
                        <td>
                            <input type="hidden" class="form-control w-100 p-0" id="idpaquete_`+(fila+1)+`" name="idpaquete_`+(fila+1)+`"  >
                            <input type="text" class="form-control w-100 p-0" id="nombrepaquete_`+(fila+1)+`" name="nombrepaquete_`+(fila+1)+`" disabled >
                        </td>
                        <td>
                        <select name="idpaquetes_`+(fila+1)+`" id="idpaquetes_`+(fila+1)+`" class="form-control" disabled>
                        </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="precio_publico_`+(fila+1)+`" disabled>
                        </td>
                        <td>
                            <input type="number" class="form-control" id="costo_maquila_`+(fila+1)+`" name="costo_maquila_`+(fila+1)+`">
                        </td>
                        <td><button class="btn btn-danger btn-sm elimina-filam" data-fila="fila_`+(fila+1)+`">-</button></td>
                    </tr>`;
        $("#tbt_lpreciosm").append(trtabla);

        if(listaSugerenciasEstudiosDatos[0].id_paquete==-1){
            $("#nombrepaquete_"+fila).val('NO')
            $("#idpaquetes_"+fila).attr("disabled",true);
            $("#idpaquetes_"+fila).attr("required",false);

        }
        else{
            $("#nombrepaquete_"+fila).val(listaSugerenciasEstudiosDatos[0].codigo_paquete)
            $("#idpaquetes_"+fila).attr("disabled",false);
            $("#idpaquetes_"+fila).attr("required",true);
            $("#idpaquetem_"+fila).val(listaSugerenciasEstudiosDatos[0].id_paquete)
            obtenerListaPaquetesSucursal(fila,listaSugerenciasEstudiosDatos[0].id_sucursal)
        }
        
    }
})

    $("#actualiza_lpmaquila").click(function(e){
        e.preventDefault();
        var id_sucursal=$("#id_sucursalm").val();
        var datos=$("#form_lpmaquila").serialize();
        $.ajax({
            url: "controller/Sucursal?opc=actualiza_lpmaquila&"+datos,
            type: "POST",
            async: false,
            data: {},
            processData: false,
            contentType: false,
            success: function (data) {
                console.log("data", data);
                var trtabla=`<tr id="fila_1">
                        <td>
                            <input type="text" class="form-control opciones-estudios" list="opcionesEstudios_1" id="estudio_1" name="estudio_1" autocomplete="off">
                            <datalist id="opcionesEstudios_1">

                            </datalist>
                            <input type="hidden" id="id_cat_estudio_1" name="id_cat_estudio_1">
                            <input type="hidden" id="idpaquetem_1" name="idpaquetem_1">
                        </td>
                        <td>
                            <input type="text" class="form-control" id="codigo_1" disabled >
                        </td>
                        <td>
                            <input type="hidden" class="form-control w-100 p-0" id="idpaquete_1" name="idpaquete_1"  >
                            <input type="text" class="form-control w-100 p-0" id="nombrepaquete_1" name="nombrepaquete_1" disabled >
                        </td>
                        <td>
                        <select name="idpaquetes_1" id="idpaquetes_1" class="form-control" disabled>
                        </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="precio_publico_1" disabled>
                        </td>
                        <td>
                            <input type="number" class="form-control" id="costo_maquila_1" name="costo_maquila_1" value="0">
                        </td>
                        <td><button class="btn btn-danger btn-sm elimina-filam" data-fila="fila_1">-</button></td>
                    </tr>`;
                $("#tbt_lpreciosm").html(trtabla);
                obtenerEstudiosLPM(id_sucursal)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    $("#tbt_lpreciosm").on('click', '.elimina-filam', function (e) {
        e.preventDefault();
        var arrId=$(this).attr("data-fila").split("_");;
        var fila=parseFloat(arrId[1]);
        $("#fila_"+fila).remove();
    })

    $("#btb_lista_estudios").on('click', '.elimina-ep-lp', function (e) {
        e.preventDefault();
        var tipo=$(this).attr("data-tipo")
        var id=$(this).attr("data-id")
        var id_sucursal=$(this).attr("data-sucursal")
        $.ajax({
            url: "controller/Sucursal?opc=elimina_estudio_lp&tipo="+tipo+"&id_sucursal="+id_sucursal+"&id="+id,
            type: "POST",
            async: false,
            data: {},
            processData: false,
            contentType: false,
            success: function (data) {
                obtenerEstudiosLPM(id_sucursal)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    

// ================================== TERMINA MAQUILA ===================
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

$(document).on('blur', '#alias', function () {

    var alias = this.value;
    var id_sucursal = $("#id_sucursal").val();
    $.ajax({
        url: "controller/catalogos/Doctor?opc=alias&" + "alias=" + alias + "&" + "id_sucursal=" + id_sucursal,
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

$(document).on('click', '.delete-sucursal', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_sucursal").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});

$(document).on('click', '.datos-sat', function () {
 //alert('si llega canas');
    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_sucursal2").val(id);
    $("#nombre2").html(nombre);
    //alert(nombre);
   $("#modalsat").modal('show');
});


$(document).on('click', '.datos-lprecios', function () {
 //alert('si llega canas');
    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_sucursal3").val(id);
    $("#nombre3").html(nombre);
    $("#id_sucursalm").val(id);
    //alert(nombre);
   $("#modallprecios").modal('show');
   obtenerEstudiosLPM(id);
});

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

$("#exampleInputFile1").change(function () {
    var file = this.files[0];

    if (file) {
        var reader = new FileReader();

        reader.onload = function () {
            $("#previewImg1").attr("src", reader.result);
        }

        reader.readAsDataURL(file);
    }
});

$("#exampleInputFile2").change(function () {
    var file = this.files[0];

    if (file) {
        var reader = new FileReader();

        reader.onload = function () {
            $("#previewImg2").attr("src", reader.result);
        }

        reader.readAsDataURL(file);
    }
});

$("#exampleInputFile3").change(function () {
    var file = this.files[0];

    if (file) {
        var reader = new FileReader();

        reader.onload = function () {
            $("#previewImg3").attr("src", reader.result);
        }

        reader.readAsDataURL(file);
    }
});

$(document).on('change', '#id_sucursal', function () {

    var text = $('#id_sucursal option:selected').data("codigo");
    $("#codigo").val(text);
    $("#codigo_").html(text+"_");
});





$(".dataTable").DataTable();

function obtenerEstudiosLPM(id_sucursal){

    $.ajax({
        url: "controller/Sucursal?opc=lista_estudio_sucursal&id_sucursal="+id_sucursal,
        type: "POST",
        async: false,
        data: {},
        processData: false,
        contentType: false,

        success: function (data) {
            $("#tb_listaEstudiosSucursal").DataTable().destroy();
            $("#btb_lista_estudios").html(data.tabla);
            //$("#idpaquetes_1").html(data.lista_paquete);
            $("#tb_listaEstudiosSucursal").DataTable();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function obtenerListaPaquetesSucursal(fila,id_sucursal){
    $.ajax({
        url: "controller/Sucursal?opc=lista_paquetes_sucursal&id_sucursal="+id_sucursal,
        type: "POST",
        async: false,
        data: {},
        processData: false,
        contentType: false,
        dataType:'json',
        success: function (data) {
            $("#idpaquetes_"+fila).html(data.lista_paquete);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}