
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

$(document).on('click', '.delete-estudio', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_estudio").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});



$(document).on('blur', '#alias', function () {

    var alias = this.value;

    $.ajax({
        url: "controller/Estudio?opc=alias&" + "alias=" + alias,
        type: "POST",
        async: false,
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


