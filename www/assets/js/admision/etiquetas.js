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

    if ($('.dataTableEtiquetas').html()) {
        $('.dataTableEtiquetas').DataTable({
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
            order: [[0, "asc"]],
            "lengthMenu": [[-1, 50], ["Todo", 50]]
        });
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
                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


$(document).on('click', '.btn-imprimir-etiquetas', function () {
    var codigo = $("#consecutivo").val();
    var etiquetas = $(".imprimir-etiqueta");

    var imprimir = "";
    for (var i = 0; i < etiquetas.length; i++) {
        imprimir += etiquetas[i].dataset.alias + ",";
    }
    window.open("etiquetas/etiquetas?codigo=" + codigo + "&estudios=" + imprimir);
});

$(document).on('click', '.imprimir-etiqueta', function () {
    var codigo = $("#consecutivo").val();
    var imprimir = this.dataset.alias;

    window.open("etiquetas/etiquetas?codigo=" + codigo + "&estudios=" + imprimir);
});


$(document).on('click', '.estudio', function () {

    var alias = this.dataset.alias;
    this.disabled = true;

    var actual = $("#estudios").val();

    $("#estudios").val(actual + alias + ",");

});

$(document).on('click', '.imprimir-varias-etiquetas', function () {
    var codigo = $("#consecutivo").val();
    var imprimir = $("#estudios").val();

    window.open("etiquetas/etiquetas?codigo=" + codigo + "&tipo=1&estudios=" + imprimir);
});




