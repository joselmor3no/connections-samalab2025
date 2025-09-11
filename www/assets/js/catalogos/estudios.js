
$(document).ready(function () {
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }

    if ($('#table-components').html()) {
        $('#table-components').tableDnD();
    }

    if ($('#component_tabla').html()) {
        $('#component_tabla').summernote({
            placeholder: 'Escribe aquí ...',
            height: 350
        });

    }

    if ($('#leyenda').html()) {
        $('#leyenda').summernote({
            placeholder: 'Escribe aquí ...',
            height: 100
        });

    }

    if ($('#resultado_texto').html()) {
        $('#resultado_texto').summernote({
            placeholder: 'Escribe aquí ...',
            height: 400
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
                    $("#id_formato").attr("disabled", false);
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
        var forms = document.getElementsByClassName('needs-validation-components');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#loading").modal("show");
                    var datos = new FormData($(".needs-validation-components")[0]);
                    datos.append("id_cat_estudio", $("#id_cat_estudio").val());
                    datos.append("id_sucursal", $("#id_sucursal").val());

                    //AJAX para guardar
                    $.ajax({
                        url: "controller/catalogos/Estudio?opc=save-component",
                        type: "POST",
                        data: datos,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            var id_estudio = $("#id_cat_estudio").val();
                            var id_sucursal = $("#id_sucursal").val();
                            reload_componets(id_estudio, id_sucursal);

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $("#modal-component").modal("hide");
                                    setTimeout(function () {
                                        $(".valores-referencia").click();
                                        $(".needs-validation-components")[0].reset();
                                        $(".needs-validation-components").removeClass('was-validated');
                                    }, 500);
                                }, 500);
                            }, 500);


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
        var forms = document.getElementsByClassName('needs-validation-component-numerica');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#loading").modal("show");
                    var datos = new FormData($(".needs-validation-component-numerica")[0]);
                    datos.append("id_componente", $("#id_componente").val());

                    //AJAX para guardar
                    $.ajax({
                        url: "controller/catalogos/Estudio?opc=save-component-numerica",
                        type: "POST",
                        data: datos,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            var id_componente = $("#id_componente").val();
                            reload_componets_numerica(id_componente);

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $(".needs-validation-component-numerica")[0].reset();
                                    $(".needs-validation-component-numerica").removeClass('was-validated');
                                }, 500);
                            }, 500);

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
        var forms = document.getElementsByClassName('needs-validation-component-formula');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#loading").modal("show");
                    var datos = new FormData($(".needs-validation-component-formula")[0]);
                    datos.append("id_componente", $("#id_componente").val());

                    //AJAX para guardar
                    $.ajax({
                        url: "controller/catalogos/Estudio?opc=save-component-formula",
                        type: "POST",
                        data: datos,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            setTimeout(function () {
                                $("#loading").modal("hide");
                                toastr.success("Operación Exitosa");
                                $('#toast-container').addClass('toast-top-center');
                                $('#toast-container').removeClass('toast-top-right');
                            }, 500);

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
        var forms = document.getElementsByClassName('needs-validation-component-lista');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#loading").modal("show");
                    var datos = new FormData($(".needs-validation-component-lista")[0]);
                    datos.append("id_componente", $("#id_componente").val());

                    //AJAX para guardar
                    $.ajax({
                        url: "controller/catalogos/Estudio?opc=save-component-lista",
                        type: "POST",
                        data: datos,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            var id_componente = $("#id_componente").val();
                            reload_componets_lista(id_componente);

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $(".needs-validation-component-lista")[0].reset();
                                    $(".needs-validation-component-lista").removeClass('was-validated');
                                }, 500);
                            }, 500);

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
        var forms = document.getElementsByClassName('needs-validation-component-tabla');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#loading").modal("show");
                    var datos = new FormData($(".needs-validation-component-tabla")[0]);
                    datos.append("id_componente", $("#id_componente").val());

                    //AJAX para guardar
                    $.ajax({
                        url: "controller/catalogos/Estudio?opc=save-component-tabla",
                        type: "POST",
                        data: datos,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            var id_componente = $("#id_componente").val();
                            reload_componets_tabla(id_componente);

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $(".needs-validation-component-tabla")[0].reset();
                                    $(".needs-validation-component-tabla").removeClass('was-validated');
                                }, 500);
                            }, 500);

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
        var forms = document.getElementsByClassName('needs-delete-components');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#modConfirmarDelete").modal('hide');
                    $("#loading").modal("show");

                    var datos = new FormData($(".needs-delete-components")[0]);

                    //ajax para eliminar
                    $.ajax({
                        url: "controller/catalogos/Estudio?opc=delete-component",
                        type: "POST",
                        data: datos,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            var id_estudio = $("#id_cat_estudio").val();
                            var id_sucursal = $("#id_sucursal").val();

                            setTimeout(function () {
                                $("#loading").modal('hide');
                                reload_componets(id_estudio, id_sucursal);
                            }, 1000);

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
        var forms = document.getElementsByClassName('needs-delete-component-num');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#modConfirmarDeleteNum").modal('hide');
                    $("#loading").modal("show");

                    var datos = new FormData($(".needs-delete-component-num")[0]);

                    //ajax para eliminar
                    $.ajax({
                        url: "controller/catalogos/Estudio?opc=delete-component-numerica",
                        type: "POST",
                        data: datos,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            var id_componente = $("#id_componente").val();
                            reload_componets_numerica(id_componente);

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $(".needs-validation-component-numerica")[0].reset();
                                    $(".needs-validation-component-numerica").removeClass('was-validated');
                                }, 500);
                            }, 500);

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
        var forms = document.getElementsByClassName('needs-delete-component-lista');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#modConfirmarDeleteLista").modal('hide');
                    $("#loading").modal("show");

                    var datos = new FormData($(".needs-delete-component-lista")[0]);
                    datos.append("id_componente", $("#id_componente_lista").val());

                    //ajax para eliminar
                    $.ajax({
                        url: "controller/catalogos/Estudio?opc=delete-component-lista",
                        type: "POST",
                        data: datos,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            var id_componente = $("#id_componente").val();
                            reload_componets_lista(id_componente);

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $(".needs-validation-component-lista")[0].reset();
                                    $(".needs-validation-component-lista").removeClass('was-validated');
                                }, 500);
                            }, 500);

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
        var forms = document.getElementsByClassName('needs-delete-component-tabla');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    event.stopPropagation();

                    $("#modConfirmarDeleteTabla").modal('hide');
                    $("#loading").modal("show");

                    var datos = new FormData($(".needs-delete-component-tabla")[0]);

                    //ajax para eliminar
                    $.ajax({
                        url: "controller/catalogos/Estudio?opc=delete-component-tabla",
                        type: "POST",
                        data: datos,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (data) {
                            var id_componente = $("#id_componente").val();
                            reload_componets_tabla(id_componente);

                            setTimeout(function () {
                                $("#loading").modal("hide");
                                setTimeout(function () {
                                    $(".needs-validation-component-tabla")[0].reset();
                                    $(".needs-validation-component-tabla").removeClass('was-validated');
                                }, 500);
                            }, 500);

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

$(document).on('click', '.delete-estudio', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_estudio").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});


$(document).on('click', '.btn-estudio', function () {
    $("#modal-estudio").modal("show");

    var tipo = $("#tipo-resultado").val();

    if (tipo == "componente") {

        $("#tipo-componente").removeClass("d-none");
        $("#tipo-texto").addClass("d-none");
    } else {
        $("#tipo-resultado_").html("Formato ");
        $("#tipo-componente").addClass("d-none");
        $("#tipo-texto").removeClass("d-none");
    }


    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#nombre_estudio").html(nombre);
});


$(document).on('click', '.new-component', function () {
    $("#modal-component").modal("show");

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_componente").val(id);
    $("#nombre_componente").html(nombre);

    $.ajax({
        url: "controller/catalogos/Estudio?opc=get-component",
        type: "POST",
        data: {id_componente: id},
        dataType: "json",
        success: function (data) {
            $("#tipo_componente").val(data.id_cat_componente);

            $(".needs-validation-components")[0].reset();

            $("#nombre_componet").val(data.componente);
            $("#alias").val(data.alias);
            $("#unidad").val(data.unidad);
            $("#referencia").val(data.referencia);


            if (data.total_absoluto == 1) {
                $("#total_componet").attr('checked', true);
            } else {
                $("#total_componet").attr('checked', false);
            }

            if (data.absoluto == 1) {
                $("#absoluto_componet").attr('checked', true);
            } else {
                $("#absoluto_componet").attr('checked', false);
            }

            if (data.capturable == 1) {
                $("#capturable_componet").attr('checked', true);
            } else {
                $("#capturable_componet").attr('checked', false);
            }

            if (data.imprimible == 1) {
                $("#imprimible_componet").attr('checked', true);
            } else {
                $("#imprimible_componet").attr('checked', false);
            }

            if (data.linea == 1) {
                $("#linea_componet").attr('checked', true);
            } else {
                $("#linea_componet").attr('checked', false);
            }

            if (data.observaciones == 1) {
                $("#observaciones_componet").attr('checked', true);
            } else {
                $("#observaciones_componet").attr('checked', false);
            }

            $('#id_cat_componente').val(data.id_cat_componente);
            $('#id_cat_componente').select2().trigger('change');

            if (data.leyenda) {
                $('#leyenda').summernote('code', data.leyenda);
            } else {
                $('#leyenda').summernote('code', "");
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});

$(document).on('click', '.delete-component', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#_id_componente").val(id);
    $("#nombre").html(nombre);
    $("#modConfirmarDelete").modal('show');
});


function reload_componets(id_estudio, id_sucursal) {

    $.ajax({
        url: "controller/catalogos/Estudio?opc=components",
        type: "POST",
        data: {id_estudio: id_estudio, id_sucursal: id_sucursal},
        dataType: "json",
        success: function (data) {
            var table = $("#table_components");
            table.html("");
            for (var i = 0; i < data.length; i++) {
                table.append("<tr>\n\
                                <td>" + data[i].componente + "</td>\n\
                                <td>" + data[i].alias + "</td>\n\
                                <td align='center'>" + (data[i].capturable == 1 ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>') + "</td>\n\
                                <td align='center'>" + (data[i].imprimible == 1 ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>') + "</td>\n\
                                <td>" + (data[i].tipo_componente == null ? "" : data[i].tipo_componente) + "</td>\n\
                                <td>\n\
                                    <button type='button' class='btn btn-warning btn-sm mr-2 new-component rounded-circle' data-id='" + data[i].id + "' data-nombre='" + data[i].componente + "'><i class='fas fa-edit'></i></button>\n\
                                    <button type='button' class='btn btn-danger btn-sm delete-component rounded-circle' data-id='" + data[i].id + "' data-nombre='" + data[i].componente + "'><i class='fas fa-trash'></i></button>\n\
                                </td>\n\
                            </tr>");
            }
            $('#table-components').tableDnD();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

$(document).on('blur', '#alias', function () {

    var alias = this.value;
    var id_sucursal = $("#id_sucursal").val();
    var id_cat_estudio = $("#id_cat_estudio").val();
    $.ajax({
        url: "controller/catalogos/Estudio?opc=alias-component&" + "alias=" + alias + "&" + "id_sucursal=" + id_sucursal + "&" + "id_cat_estudio=" + id_cat_estudio,
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


$(document).on('click', '.valores-referencia', function () {

    var id_componene = $("#id_componente").val();
    var tipo_componene = $("#tipo_componente").val();
    var nombre_componente = $("#nombre_componente").html();

    if (tipo_componene == 1) {
        $(".nuevo-valor-numerico").click();
        $("#componente_numerica").html(nombre_componente);
        $("#modal-component-numerica").modal('show');
        reload_componets_numerica(id_componene);

    } else if (tipo_componene == 2) {
        $("#componente_formula").html(nombre_componente);
        $("#modal-component-formula").modal('show');
        reload_componets_formula(id_componene);
        get_formula(id_componene);

    } else if (tipo_componene == 3) {
        $("#componente_lista").html(nombre_componente);
        $("#modal-component-lista").modal('show');
        reload_componets_lista(id_componene);
    }

});

function reload_componets_numerica(id) {

    $.ajax({
        url: "controller/catalogos/Estudio?opc=components-numericas",
        type: "POST",
        data: {id_componente: id},
        dataType: "json",
        success: function (data) {
            var table = $("#table_components_numerica");
            table.html("");
            for (var i = 0; i < data.length; i++) {
                table.append("<tr>\n\
                                <td>" + data[i].referencia + "</td>\n\
                                <td>" + data[i].edad_inicio + " - " + data[i].edad_fin + " " + data[i].tipo_edad + "</td>\n\
                                <td class='text-center'>" + data[i].alta_aceptable + "</td>\n\
                                <td class='text-center'>" + data[i].alta + "</td>\n\
                                <td class='text-center'>" + data[i].baja + "</td>\n\
                                <td class='text-center'>" + data[i].bajo_aceptable + "</td>\n\
                                <td>\n\
                                    <button type='button' class='btn btn-warning btn-sm mr-2 edit-component-numerico rounded-circle' data-id='" + data[i].id + "'><i class='fas fa-edit'></i></button>\n\
                                    <button type='button' class='btn btn-danger btn-sm delete-component-numerico rounded-circle' data-id='" + data[i].id + "' data-nombre='" + data[i].referencia + " " + data[i].edad_inicio + " - " + data[i].edad_fin + " " + data[i].tipo_edad + "'><i class='fas fa-trash'></i></button>\n\
                                </td>\n\
                            </tr>");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

$(document).on('click', '.delete-component-numerico', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#_id_componente_num").val(id);
    $("#referencia_num").html(nombre);
    $("#modConfirmarDeleteNum").modal('show');
});



$(document).on('click', '.edit-component-numerico', function () {

    var id = this.dataset.id;

    $.ajax({
        url: "controller/catalogos/Estudio?opc=get-component-numerica",
        type: "POST",
        data: {id_componente: id},
        dataType: "json",
        success: function (data) {
            $("#id_componente_num").val(id);
            $('#referencia_numerica').val(data.referencia);
            //$('#referencia_numerica').select2().trigger('change');
            $("#edad_inicio").val(data.edad_inicio);
            $("#edad_fin").val(data.edad_fin);
            $('#tipo_edad').val(data.tipo_edad);
            $('#tipo_edad').select2().trigger('change');
            $("#valores_unidades").val(data.valores_unidades);
            $("#valores_decimales").val(data.valores_decimales);
            $("#alta_aceptable").val(data.alta_aceptable);
            $("#alta").val(data.alta);
            $("#baja").val(data.baja);
            $("#bajo_aceptable").val(data.bajo_aceptable);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});


$(document).on('click', '.nuevo-valor-numerico', function () {

    $(".needs-validation-component-numerica")[0].reset();
    $("#id_componente_num").val("");
    $('#referencia_numerica').val("General");
    //$('#referencia_numerica').select2().trigger('change');
    $('#tipo_edad').val("");
    //$('#tipo_edad').select2().trigger('change');
});


function reload_componets_lista(id) {

    $.ajax({
        url: "controller/catalogos/Estudio?opc=components-lista",
        type: "POST",
        data: {id_componente: id},
        dataType: "json",
        success: function (data) {
            var table = $("#table_components_lista");
            table.html("");
            for (var i = 0; i < data.length; i++) {
                table.append("<tr>\n\
                               <td align='center'>" + (data[i].predeterminado == 1 ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>') + "</td>\n\
                                <td>" + data[i].elemento + "</td>\n\
                                <td>\n\
                                    <button type='button' class='btn btn-danger btn-sm delete-component-lista rounded-circle' data-id='" + data[i].id + "' data-nombre='" + data[i].elemento + "'><i class='fas fa-trash'></i></button>\n\
                                </td>\n\
                            </tr>");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

$(document).on('click', '.delete-component-lista', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#id_componente_lista").val(id);
    $("#referencia_lista").html(nombre);
    $("#modConfirmarDeleteLista").modal('show');
});

function reload_componets_formula(id) {
    var id_estudio = $("#id_cat_estudio").val();
    var id_sucursal = $("#id_sucursal").val();

    $.ajax({
        url: "controller/catalogos/Estudio?opc=components",
        type: "POST",
        data: {id_estudio: id_estudio, id_sucursal: id_sucursal},
        dataType: "json",
        success: function (data) {
            var table = $("#table_components_formula");
            table.html("");
            for (var i = 0; i < data.length; i++) {
                if (data[i].tipo_componente == 'Número') {
                    table.append("<tr>\n\
                                <td class='elemento-formula text-primary textdecoration' data-alias='" + data[i].alias + "'>" + data[i].alias + "</td>\n\
                            </tr>");
                }
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

}

function get_formula(id) {
    $.ajax({
        url: "controller/catalogos/Estudio?opc=get-formula",
        type: "POST",
        data: {id_componente: id},
        dataType: "json",
        success: function (data) {
            $("#component_formula").val(data)

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

$(document).on('click', '.elemento-formula', function () {

    var alias = this.dataset.alias;

    var actual = $("#component_formula").val();
    $("#component_formula").val(actual + alias);
});


$(document).on('click', '.valores-referencia-numerico', function () {
    var id_componene = $("#id_componente").val();
    var nombre_componente = $("#nombre_componente").html();

    $(".nuevo-valor-numerico").click();
    $("#componente_numerica").html(nombre_componente);
    $("#modal-component-numerica").modal('show');
    reload_componets_numerica(id_componene);
});

$(document).on('click', '.position-component', function () {
    var id_estudio = $("#id_cat_estudio").val();
    var id_sucursal = $("#id_sucursal").val();


    var table = $("#table_components tr");
    var components = [];
    for (var i = 0; i < table.length; i++) {
        var data = $(table[i]).find("td:eq(1)").html();
        components.push(data);
    }
    $("#loading").modal("show");

    $.ajax({
        url: "controller/catalogos/Estudio?opc=position-componets",
        type: "POST",
        data: {id_estudio: id_estudio, id_sucursal: id_sucursal, components: components},
        dataType: "json",
        success: function (data) {

            setTimeout(function () {
                toastr.success("Operación Exitosa");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
                $("#loading").modal("hide");
            }, 500);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });


});

$(document).on('click', '.tabla-referencia', function () {

    var id_componene = $("#id_componente").val();
    var tipo_componene = $("#tipo_componente").val();
    var nombre_componente = $("#nombre_componente").html();

    if (tipo_componene == 1) {
        $("#componente_tabla").html(nombre_componente);
        $("#modal-tabla").modal("show");
        reload_componets_tabla(id_componene);
    } else if (tipo_componene == 2) {
        $("#componente_tabla").html(nombre_componente);
        $("#modal-tabla").modal("show");
        reload_componets_tabla(id_componene);
    } else if (tipo_componene == 3) {
        $("#componente_tabla").html(nombre_componente);
        $("#modal-tabla").modal("show");
        reload_componets_tabla(id_componene);
    } else if (tipo_componene == 4) {
        $("#componente_tabla").html(nombre_componente);
        $("#modal-tabla").modal("show");
        reload_componets_tabla(id_componene);
    }

    $('#component_tabla').summernote('code', '');

});

function reload_componets_tabla(id) {

    $.ajax({
        url: "controller/catalogos/Estudio?opc=components-tabla",
        type: "POST",
        data: {id_componente: id},
        dataType: "json",
        success: function (data) {
            var table = $("#table_components_tabla");
            table.html("");
            for (var i = 0; i < data.length; i++) {
                table.append("<tr>\n\
                                <td>" + data[i].sexo + "</td>\n\
                                <td>\n\
                                    <button type='button' class='btn btn-warning btn-sm edit-component-tabla rounded-circle mr-2' data-id='" + data[i].id + "' data-nombre='" + data[i].sexo + "'><i class='fas fa-edit'></i></button>\n\
                                    <button type='button' class='btn btn-danger btn-sm delete-component-tabla rounded-circle' data-id='" + data[i].id + "' data-nombre='" + data[i].sexo + "'><i class='fas fa-trash'></i></button>\n\
                                </td>\n\
                            </tr>");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

$(document).on('click', '.delete-component-tabla', function () {

    var id = this.dataset.id;
    var nombre = this.dataset.nombre;
    $("#_id_componente_tabla").val(id);
    $("#componet_tabla").html(nombre);
    $("#modConfirmarDeleteTabla").modal('show');
});

$(document).on('click', '.edit-component-tabla', function () {

    var id = this.dataset.id;

    $.ajax({
        url: "controller/catalogos/Estudio?opc=get-component-tabla",
        type: "POST",
        data: {id_componente: id},
        dataType: "json",
        success: function (data) {

            $("#id_componente_tabla").val(id);
            $('#sexo_tabla').val(data.sexo);
            $('#sexo_tabla').select2().trigger('change');
            $('#component_tabla').summernote('code', data.tabla);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

$(document).on('click', '.nueva-tabla', function () {

    $(".needs-validation-component-tabla")[0].reset();
    $("#id_componente_tabla").val("");
    $('#sexo_tabla').val("General");
    $('#sexo_tabla').select2().trigger('change');
    $('#component_tabla').summernote('code', '');

});

$(document).on('blur', '#alta_aceptable', function () {
    chande_decimales();
});

$(document).on('blur', '#alta', function () {
    chande_decimales();
});

$(document).on('blur', '#baja', function () {
    chande_decimales();
});

$(document).on('blur', '#bajo_aceptable', function () {
    chande_decimales();
});

$(document).on('blur', '#valores_decimales', function () {
    chande_decimales();
});

function chande_decimales() {
    var decimales = parseFloat($("#valores_decimales").val()) > 0 ? parseFloat($("#valores_decimales").val()) : 0;

    var alta_aceptable = parseFloat($("#alta_aceptable").val()) > 0 ? parseFloat($("#alta_aceptable").val()) : 0;
    $("#alta_aceptable").val(alta_aceptable.toFixed(decimales));

    var alta = parseFloat($("#alta").val()) > 0 ? parseFloat($("#alta").val()) : 0;
    $("#alta").val(alta.toFixed(decimales));

    var baja = parseFloat($("#baja").val()) > 0 ? parseFloat($("#baja").val()) : 0;
    $("#baja").val(baja.toFixed(decimales));

    var bajo_aceptable = parseFloat($("#bajo_aceptable").val()) > 0 ? parseFloat($("#bajo_aceptable").val()) : 0;
    $("#bajo_aceptable").val(bajo_aceptable.toFixed(decimales));
}

$(document).on('keydown', ".tab-num", function (event) {

    var key = (event.keyCode ? event.keyCode : event.which);
    if (key == '13') {
        event.preventDefault();
        event.stopPropagation();

        var index = $('.tab-num').index(this);
        if (index < $('.tab-num').length - 1)
            $('.tab-num')[index + 1].focus();
    }

});


$(document).on('click', '.clonar-componentes', function () {

    var id_estudio = $("#id_cat_estudio").val();
    var id_sucursal = $("#id_sucursal").val();

    var mensaje1 = confirm('Se Eliminarán los componentes actuales,¿Deséa continuar?')
    if (mensaje1 === true) {
        var mensaje2 = confirm('Estos cambios no son reversibles ¿Deséa continuar?')
        if (mensaje2 === true) {
            $("#loading").modal("show");
            $.ajax({
                url: "controller/catalogos/Estudio?opc=clonar-componentes",
                data: {id_estudio: id_estudio, id_sucursal: id_sucursal},
                contentType: "application/x-www-form-urlencoded",
                dataType: "json",
                error: function (objeto, quepaso, otroobject) {
                    console.log('Paso lo siguiente en delete_componente: ' + quepaso + " " + otroobject);
                },
                success: function (datos) {
                    reload_componets(id_estudio, id_sucursal);
                    setTimeout(function () {
                        $("#loading").modal("hide");
                        toastr.success("Operación Exitosa");
                        $('#toast-container').addClass('toast-top-center');
                        $('#toast-container').removeClass('toast-top-right');
                    }, 500);
                },
                type: "POST"
            });
        }
    }

});



















