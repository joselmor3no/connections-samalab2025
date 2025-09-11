$(document).ready(function () {
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }
    if ($(".custom-file-input")[0]) {
        bsCustomFileInput.init();
    }

    // $("#dataTableReporte").append('<caption style="caption-side: bottom">A fictional company\'s staff table.</caption>');

    $("#dataTableReporte").DataTable({
        responsive: true, "lengthChange": false, "autoWidth": false,
        buttons: ["copy",
             {
                extend: 'csv',
                title: $("#title").val(),
                messageTop: $("#text").val(),
            },
            {
                extend: 'excel',
                title: $("#title").val(),
                messageTop: $("#text").val(),
            },

            {
                extend: 'pdf',
                title: $("#title").val(),
                messageTop: $("#text").val(),
                /*customize: function (doc) {
                 doc.content.splice(1, 0, {
                 margin: [0, 0, 0, 12],
                 alignment: 'center',
                 image: 'data:image/png;base64,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'
                 });
                 }*/
            },
            {
                extend: 'print',
                title: $("#title").val(),
                messageTop: $("#text").val(),
            },

            "colvis"],
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
    }).buttons().container().appendTo('#dataTableReporte_wrapper .col-md-6:eq(0)');


    /*$("#dataTableReporte").DataTable({
     tittle: 'Corte',
     dom: 'Bfrtip',
     buttons: [
     'copy', 'csv', 'excel', 'pdf', 'print'
     ]
     });*/

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
