/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$(document).ready(function () {
// Activamos los tooltips
    $('[data-toggle="tooltip"]').tooltip('enable');

    if ($('.select2').html()) {
        $('.select2').select2();
    }

    if ($('.dataTable').html()) {
        $('.dataTable').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay informaci&oacute;n",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ resultados",
                "infoEmpty": "Mostrando 0 a 0 de 0 resultados",
                "infoFiltered": "(Filtrado de _MAX_ total resultados)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Resultados",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "&Uacute;ltimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            order: [[0, "asc"]]
        });
    }
});

session(1);
function session(x) {

    setTimeout(function () {
        $.ajax({
            url: 'controller/Acceso?opc=session',
            type: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (datos) {

                if (!datos.stop) {
                    session(x + 1);

                } else {
                    Swal.fire({
                        title: '<h2>Sesión terminada</h2>',
                        text: "Su sesión de 45 minutos ha sido finalizada por inactividad, vuelva a iniciar sesión.",
                        icon: 'info',
                        confirmButtonText: 'Inicir sesión',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = '/Empresas';
                        }
                    })
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });

    }, 1000 * 60);//1 min


}

function get_municipios(estado) {
    var municipios = [];

    $.ajax({
        url: "controller/Generales?opc=municipios&estado=" + estado,
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

var formatter = new Intl.NumberFormat('en-US', {
    
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,


});

$(document).on('click', '.btn-cargando', function () {
    $("#modConfirmarDelete").modal('hide');
    $("#loading").modal("show");
});

function loading() {
    $('#loading').modal('show');
}

$(".text-uppercase").blur(function () {
    var value = $(this).val();
    $(this).val(value.toUpperCase());
});

$(".text-uppercase").focus(function () {
    var value = $(this).val();
    $(this).val(value.toUpperCase());
});


