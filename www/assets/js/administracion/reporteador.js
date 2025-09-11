
$(document).ready(function () {
    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }



      $("#example2").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel",  "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $(document).on('click','.btn-global',function(){
        let fechaInicial=$("#fecha_inicial").val();
        let fechaFinal=$("#fecha_final").val();
        $("#fecha_inicialg").val(fechaInicial)
        $("#fecha_finalg").val(fechaFinal)
        $("#fecha_inicialgc").val(fechaInicial)
        $("#fecha_finalgc").val(fechaFinal)
    })

    //---------------------Reporte de facturacion ------------------------

    

    $(document).on('click','.btn_facturacion',function(){
        let fechaInicial=$("#fecha_inicial").val();
        let fechaFinal=$("#fecha_final").val();
        let descipcion=$(this).attr("data-desc")
        let busqueda=$("#palabra").val()
        $.ajax({
            url: "controller/administracion/facturacion?opc=obtener_reporte_factura",
            type: "POST",
            data: "fecha_inicial="+fechaInicial+"&fecha_final="+fechaFinal+"&descripcion="+descipcion+"&busqueda=",
            success: function (data) {
                console.log("data", data);
                $("#tb_facturacion").dataTable().fnDestroy();
                $("#btb_Facturacion").html(data);
                $("#tb_facturacion").DataTable({
                  "responsive": true, "lengthChange": false, "autoWidth": false,
                  "buttons": ["excel", "print", "colvis",
                        {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'LETTER'
                        }
                        ]
                }).buttons().container().appendTo('#tb_facturacion_wrapper .col-md-6:eq(0)');
            }, 
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });

    })

    //---------------------------------------------------------------
        
   

    $("#btn-generarRG").click(function(event){
        event.preventDefault()
        $("#tabla_reporteG").html('Generando...');
        $.ajax({
            url: "controller/administracion/administracion?opc=genera_reporte_global",
            type: "POST",
            data: $("#form-global").serialize(),
            success: function (data) {
                $("#example1").dataTable().fnDestroy();
                $("#tabla_reporteG").html(data);
                $("#example1").DataTable({
                  "responsive": true, "lengthChange": false, "autoWidth": false,
                  "buttons": ["copy", "csv", "excel", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }, 
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    $("#btn-generarRGC").click(function(event){
        event.preventDefault()
        $("#tabla_reporteGC").html('Generando...');
        $.ajax({
            url: "controller/administracion/administracion?opc=genera_reporte_global_caja",
            type: "POST",
            data: $("#form-global-c").serialize(),

            success: function (data) {
                console.log("data", data);
                $("#example2").dataTable().fnDestroy();
                $("#tabla_reporteGC").html(data.tabla);
                $("#example2").DataTable({
                  "responsive": true, "lengthChange": false, "autoWidth": false,
                  "buttons": ["copy", "csv", "excel", "print", "colvis"]
                }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
            }, 
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    $("#btn-generarPaciente").click(function(event){
        event.preventDefault()
        $("#tabla_reportePaciente").html('Generando...');
        $.ajax({
            url: "controller/administracion/administracion?opc=genera_reporte_global_paciente",
            type: "POST",
            data: $("#form-global-p").serialize(),

            success: function (data) {
                //console.log("data", data);
                $("#example3").dataTable().fnDestroy();
                $("#tabla_reportePaciente").html(data);
                $("#example3").DataTable({
                  "responsive": true, "lengthChange": false, "autoWidth": false,
                  "buttons": ["copy", "csv", "excel", "print", "colvis"]
                }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
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

                    toastr.error("Debe elegir un tipo de Reporte");
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');
                } else {
                   // $("#loading").modal("show");
                }

                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


