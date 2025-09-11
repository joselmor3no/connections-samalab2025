var montoglobal=0
var saldoOriginal=0;
var listaOrdenes=[];
var listaDescuentos=[];
var listaImportes=[];
var numeroChecks=0;

$(document).ready(function () {

    if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }

//===================== Aplicación de pagos empresas
    $("#tabla_lista_pagos_g").on('click','.enviar_pago_datose',function(){
        $("#custom-tabs-one-home-tab").click();
        $("#fecha_inicial").val(this.dataset.fechai)
        $("#fecha_final").val(this.dataset.fechaf)
        $("#empresa").val(this.dataset.empresa)
        $("#empresa").change();
    });
    //------al seleccionar una empresa en la ventana aplicación
    $("#empresa").change(function(){
      var id_empresa=$(this).val();
      var fecha_inicial=$("#fecha_inicial").val(); 
      var fecha_final=$("#fecha_final").val();
        $.ajax({
            url: "controller/administracion/administracion?opc=lista_ordenes",
            type: "POST",
            data: {'id_empresa': id_empresa,'fecha_inicial':fecha_inicial,'fecha_final':fecha_final},
            dataType: 'json',
            success: function (data) {
                console.log(data)
                saldoOriginal=data.saldo
                $("#saldo").val(data.saldo)
                $("#ordenes_pago").dataTable().fnDestroy();
                $("#tabla_ordenes").html(data.html)
                $("#ordenes_pago").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "print", "colvis"]
                  }).buttons().container().appendTo('#ordenes_pago_wrapper .col-md-6:eq(0)');
                $("#monto").val("0")
                $("#totalordenes").val("0")
                $("#saldoFinal").val("0")
                $("#usarsaldo").prop("checked",false);
                $("#adeudoTotal").val(data.totalDeuda)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    //------Usar saldo en la ventana aplicación
    $("#usarsaldo").click(function(){
      if($(this).prop("checked")==true){
           let totalOrden=parseFloat($("#totalordenes").val());
           let saldo=parseFloat($("#saldo").val())
           let monto=parseFloat($("#monto").val())
           $("#monto").attr("min",saldo);
           $("#monto").val(monto+saldo);
           $("#saldo").val(monto+saldo);
           $("#saldoFinal").val((monto+saldo)-totalOrden)
        }
        else{
          let totalOrden=parseFloat($("#totalordenes").val());
          let saldo=parseFloat($("#saldo").val())
          let monto=parseFloat($("#monto").val())
          $("#monto").attr("min","0")
          let saldoFinal=saldo-saldoOriginal;
          if(saldoFinal<totalOrden){
            alert('No se puede ejecutar la acción:El total rebasa el monto de pago')
            $("#usarsaldo").prop("checked",true);
        }
        else{
            $("#monto").val(saldoFinal);
            $("#saldo").val(saldoOriginal)
        }
        $("#saldoFinal").val(saldoFinal-totalOrden)

        }
    })

    //--------------------Seleccionar las ordenes para ser pagadas ventan Aplicación
    $("#tabla_ordenes").on('click','.checkdinero',function(){
       var montoCantidad=$("#monto").val();
       var numeroOrden=$(this).attr("data-orden");
       var importeOrden=$(this).attr("data-importe");
       var descuentoOrden=$(this).attr("data-descuento");
       if(montoCantidad!=undefined &&  montoCantidad!=""){
           var monto=parseFloat($(this).attr("data-monto"));
        if($(this).is(':checked')){
             montoglobal=montoglobal+monto
             if(montoglobal>montoCantidad){
                toastr.error("Monto superado, no se puede agregar");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
                $(this).prop("checked",false);
                montoglobal=montoglobal-monto
            }
            else{
                $("#totalordenes").val(montoglobal) 
                listaOrdenes.push(numeroOrden);
                listaImportes.push(importeOrden);
                listaDescuentos.push(descuentoOrden);
                numeroChecks++;
                $("#monto").attr("disabled",true);
            }
            
        } else {
            montoglobal=montoglobal-monto
            $("#totalordenes").val(montoglobal)
            let indice=listaOrdenes.indexOf(numeroOrden)
            listaOrdenes.splice(indice,1)
            listaDescuentos.splice(indice,1)
            listaImportes.splice(indice,1)
            numeroChecks--;
            if(numeroChecks==0)
                $("#monto").attr("disabled",false);
        }
        if($("#usarsaldo").prop("checked")==true){
            var saldo=parseFloat($("#saldo").val())
            saldo=saldo-montoglobal
            $("#saldoFinal").val(saldo)
        }
        var cadena="";
        var cadenaImportes="";
        var cadenaDescuentos="";
        
        for (i=0;i<listaOrdenes.length;i++){
          cadena=cadena+listaOrdenes[i]+",";
          cadenaDescuentos=cadenaDescuentos+listaDescuentos[i]+",";
          cadenaImportes=cadenaImportes+listaImportes[i]+",";
      }
      $("#lista_ordenes").val(cadena)
      $("#lista_descuentos").val(cadenaDescuentos)
      $("#lista_importes").val(cadenaImportes)

    }

    });

    //-----------------Botón filtrar en la ventana pagos
    $("#btn_filtrar").click(function(){
      var fecha_inicio=$("#fecha_inicio").val();
      var fecha_fin=$("#fecha_fin").val();
      var id_empresa=$("#empresa_lista").val();
      $.ajax({
            url: "controller/administracion/administracion?opc=pagos_empresa",
            type: "POST",
            data: {'empresa': id_empresa,'fecha_inicio':fecha_inicio,'fecha_fin':fecha_fin},
            success: function (data) {
                $("#cuerpoTablal").html(data);
                $("#tabla_lista_pagos").DataTable(); 
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })


    //---------------- Botón para filtrar los pagos de fechas en general de empresas de crédito
    $("#btn_filtrar_general").click(function(){
        var fecha_inicio=$("#fechaInicialG").val();
        var fecha_fin=$("#fechaFinalG").val();
        $("#tabla_lista_pagos_g").DataTable().destroy();
        $.ajax({
            url: "controller/administracion/administracion?opc=ordenes_adeudo_empresas",  
            type: "POST",
            data: {'fecha_inicio':fecha_inicio,'fecha_fin':fecha_fin},
            success: function (data) {
                $("#cuerpoTablaEmpresasT").html(data);
                $("#tabla_lista_pagos_g").DataTable(); 
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    $("#ver_detalles_oe").click(function(){
        
    })
    
    $("#reporte").click(function(){
        var fecha_inicial=$("#fecha_inicial").val();
        var fecha_final=$("#fecha_final").val();
        var empresa=$("#empresa").val();
        var nombre_empresa=$( "#empresa option:selected" ).text();
        $.ajax({
            url: "controller/administracion/administracion?opc=imprime_pago_empresas",  
            type: "POST",
            data: {'fecha_inicial':fecha_inicial,'fecha_final':fecha_final,'id_empresa':empresa,'nombre_empresa':nombre_empresa},
            success: function (data) {
                window.open('/reportes/'+data)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    $("#checkall").click(function(){
        var checado=$(this).prop("checked")
        console.log('checado',checado)
        $(".checkdinero").each(function(){
                $(this).trigger('click')
        })
    })

});


function verestudios(id_orden,nombre_paciente){
    $.ajax({
            url: "controller/administracion/administracion?opc=lista_estudios_orden",
            type: "POST",
            data: {'id_orden': id_orden},
            dataType: 'json',
            success: function (data) {
                $("#lista-estudios .modal-body").html(data.html)
                $("#paciente_modal").text(nombre_paciente)
                $("#lista-estudios").modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
}




//===================== Aplicación de pagos empresas==========================