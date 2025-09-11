
$(document).ready(function () {
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }


    $(".mbtn_editar").click(function(){
        var fila=this.dataset.fila;
        var id_clase=this.dataset.id_clase;
        var porcentaje=$("#porcentaje_"+fila).val();
        var tipo=$("#tipo_descuento_"+fila).val();
        var id_empresa=$("#id_empresa_credito").val();

        $("#span_guardado").remove();

        $.ajax({
        url: "controller/catalogos/Empresa?opc=actualiza_clase_empresa&" + "id_clase=" + id_clase + "&id_empresa=" + id_empresa+"&porcentaje="+porcentaje+"&tipo_descuento="+tipo,
        type: "POST",
        processData: false,
        contentType: false,
        data: {},
        success: function (data) {
            if(data=="ok"){
                $("#exampleModalLabel").append('<span id="span_guardado" style="color:green"><br>Guardado</span>')
                $("#pguardado_"+fila).html(tipo+'='+porcentaje);
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
        url: "controller/catalogos/Empresa?opc=alias&" + "alias=" + alias + "&" + "id_sucursal=" + id_sucursal,
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

$(document).on('click', '.delete-empresa', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_empresa").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});


$(document).on('change', '#id_estado', function () {
    var estado = this.value;
    var municipios = get_municipios(estado);
    $("#id_municipio").html("");
    for (var i = 0; i < municipios.length; i++) {
        $("#id_municipio").append("<option value='" + municipios[i].municipio + "'>" + municipios[i].municipio + "</option>")
    }

});

$(document).on('change', '#id_lista_precios', function () {
    var lista = this.value;
    if (lista != "") {
        $(".lista").val("")
        $(".lista").attr("disabled", true);
    } else {
        $(".lista").attr("disabled", false);
    }

});

