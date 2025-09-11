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

    init_calendar();
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
                    event.preventDefault();
                    event.stopPropagation();
                    $("#cita").modal("hide");

                    var formData = new FormData($(".needs-validation")[0]);
                    formData.append("id_seccion", $("#id_seccion").val());
                    $.ajax({
                        url: "controller/admision/Agenda?opc=registro",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        data: formData,
                        success: function (data) {
                            if (data == 0) {
                                init_calendar();
                                toastr.success("Operación Exitosa");
                                $('#toast-container').addClass('toast-top-center');
                                $('#toast-container').removeClass('toast-top-right');
                            } else {
                                toastr.error("No disponible");
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
        var forms = document.getElementsByClassName('needs-validation-edit');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();
                    $("#edit_cita").modal("hide");

                    var formData = new FormData($(".needs-validation-edit")[0]);
                    formData.append("id_seccion", $("#id_seccion").val());
                    $.ajax({
                        url: "controller/admision/Agenda?opc=edit",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        data: formData,
                        success: function (data) {
                            if (data == 0) {
                                init_calendar();
                                toastr.success("Operación Exitosa");
                                $('#toast-container').addClass('toast-top-center');
                                $('#toast-container').removeClass('toast-top-right');
                            } else {
                                toastr.error("No disponible");
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

                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();



function init_calendar() {
    var date = new Date()
    var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();


    var Calendar = FullCalendar.Calendar;
    var calendarEl = document.getElementById('calendar');
    var calendar = new Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'bootstrap',
        timeZone: 'UTC',
        locale: 'es',

        editable: true,
        droppable: true,

        dateClick: function (info) {

            var now = (new Date().toISOString()).substring(0, 10);
            if (now > info.dateStr) {
                toastr.error("No disponible");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');

            } else {

                $("#cita").modal("show");

                $("#paciente").val("");
                $("#telefono").val("");
                $("#observaciones").val("");
                $("#inicio").val(info.dateStr + "T12:00:00");
                $("#final").val(info.dateStr + "T12:30:00");

                $('#submitButton').on('click', function () {
                    calendar.addEvent({
                        title: $("#paciente").val(),
                        start: $("#inicio").val(),
                        end: $("#final").val()
                    });
                    init_calendar();

                });
            }
        },

        eventClick: function (info) {

            $("#edit_cita").modal("show");

            var id = info.event.id;
            $.ajax({
                url: "controller/admision/Agenda?opc=event",
                type: "POST",
                data: {id: id},
                dataType: "json",
                success: function (data) {
                    $("#id").val(id);
                    $("#paciente_").val(info.event.title);
                    $("#telefono_").val(data.telefono);
                    $("#observaciones_").val(data.observaciones);
                    $("#inicio_").val((info.event.start.toISOString()).substring(0, 16));
                    $("#final_").val((info.event.end.toISOString()).substring(0, 16));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });

            var now = (new Date().toISOString()).substring(0, 10);
            if (now > (info.event.start.toISOString()).substring(0, 16)) {
                $("#submitButtonEdit").attr("disabled", true);
                $("#btn-delete-event").attr("disabled", true);
                $("#btn-cancelar-event").attr("disabled", true);
            } else {
                $("#submitButtonEdit").attr("disabled", false);
                $("#btn-delete-event").attr("disabled", false);
                $("#btn-cancelar-event").attr("disabled", false);
            }

        },

        eventDrop: function (info) {
            var id = info.event.id;
            var paciente = info.event.title;
            var inicio = (info.event.start.toISOString()).substring(0, 16);
            var final = (info.event.end.toISOString()).substring(0, 16);

            $.ajax({
                url: "controller/admision/Agenda?opc=horario",
                type: "POST",
                data: {id: id, id_seccion: $("#id_seccion").val(), inicio: inicio, final: final},
                dataType: "json",
                success: function (data) {
                    if (data == 0) {
                        /*toastr.success("Operación Exitosa");
                         $('#toast-container').addClass('toast-top-center');
                         $('#toast-container').removeClass('toast-top-right');*/
                    } else {
                        toastr.error("No disponible");
                        $('#toast-container').addClass('toast-top-center');
                        $('#toast-container').removeClass('toast-top-right');
                    }
                    init_calendar();

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });

        },
        eventResize: function (info) {
            var id = info.event.id;
            var paciente = info.event.title;
            var inicio = (info.event.start.toISOString()).substring(0, 16);
            var final = (info.event.end.toISOString()).substring(0, 16);

            $.ajax({
                url: "controller/admision/Agenda?opc=horario",
                type: "POST",
                data: {id: id, id_seccion: $("#id_seccion").val(), inicio: inicio, final: final},
                dataType: "json",
                success: function (data) {
                    init_calendar();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        },

        events: 'controller/admision/Agenda?opc=citas&id_seccion=' + $("#id_seccion").val(),

    });

    calendar.render();
}




$('#id_seccion').on('change', function () {

    init_calendar();

});


$(document).on('click', '.delete-event', function () {

    var nombre = $("#paciente_").val();
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});


$(document).on('click', '#btn-delete-event', function () {

    $("#modConfirmarDelete").modal('hide');
    var id = $("#id").val();
    $.ajax({
        url: "controller/admision/Agenda?opc=delete",
        type: "POST",
        data: {id: id},
        dataType: "json",
        success: function (data) {
            $("#edit_cita").modal("hide");
            setTimeout(function () {

                toastr.success("Operación Exitosa");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');

                init_calendar();
            }, 500);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

$(document).on('click', '.cancelar-event', function () {

    var nombre = $("#paciente_").val();
    $("#nombre2").html(nombre);
    $("#modConfirmarCancelacion").modal('show');
});


$(document).on('click', '#btn-cancelar-event', function () {

    $("#modConfirmarCancelacion").modal('hide');
    var id = $("#id").val();
    $.ajax({
        url: "controller/admision/Agenda?opc=cancelar",
        type: "POST",
        data: {id: id},
        dataType: "json",
        success: function (data) {
            $("#edit_cita").modal("hide");
            setTimeout(function () {

                toastr.success("Operación Exitosa");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');

                init_calendar();
            }, 500);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});






  