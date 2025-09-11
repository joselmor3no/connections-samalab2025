
$(document).ready(function () {

    $("#id_promotor").change(function(){
        alert();
    })
    
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }

    $("#prefijo").change(function(){
        $.ajax({
            url: "controller/catalogos/Doctor?opc=consecutivo_prefijo&prefijo=" + $(this).val(),
            type: "POST",
            processData: false,
            contentType: false,
            data: {},
            success: function (data) {
                console.log('data',data);
                $("#consecutivo_prefijo").val(data);
                $("#consecutivo_prefijo").attr('value',data);
                            
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    $("#busqueda_nombre").keyup(function(){
        var paterno=$("#busqueda_paterno").val();
        var materno=$("#busqueda_materno").val();
        var id_sucursal = $("#id_sucursal").val();
        var nombre=$(this).val();
        $.ajax({
            url: "controller/catalogos/Doctor?opc=busqueda-doctor&" + "paterno=" + paterno + "&" + "materno=" + materno+ "&" + "nombre=" + nombre +"&id_sucursal="+id_sucursal,
            type: "POST",
            processData: false,
            contentType: false,
            data: {},
            success: function (data) {
                //console.log('datos doc',data)
                if(data==""){
                    console.log("data", data);
                    $("#lista_doctores_catalogo").html(data)
                    $("#nuevo-doctor").attr("disabled",false)
                }
                else{
                    $("#lista_doctores_catalogo").html(data)
                    $("#nuevo-doctor").attr("disabled",true)
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

$(document).on('click', '.delete-zonas', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_zona").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});


$(document).on('click', '.delete-promotores', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_promotor").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});

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

$(document).on('click', '.delete-doctor', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_doctor").val(id);
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
