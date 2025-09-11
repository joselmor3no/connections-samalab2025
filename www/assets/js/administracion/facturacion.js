var lista_ordenes_masiva=[]; //---para el timbrado de forma masiva
var lista_ordenes_masiva_empresa=[]; //---para el timbrado de empresas

$(document).ready(function () { 

    if (getParameterByName('msg') == "facturado") {
        toastr.success("Factura timbrada con éxito");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        $("#custom-tabs-one-facturadas-tab").click();
    }
    if (getParameterByName('msg') == "cancelado") {
        toastr.success("Factura cancelada");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
        $("#custom-tabs-one-cancelarSAT").click();
    }
    if (getParameterByName('msg') == "fcancelado") {
        $("#custom-tabs-one-cancelarSAT").click();
    }

$("#c_tabla_individuales").on('click','.boton_dfp',function(){
	//$(".boton_dfp").click(function(){
		let id_paciente=$(this).attr("data-id");
		$("#span_df").text($(this).attr("data-nombre"));
		$("#m_id_paciente").val(id_paciente)
		$.ajax({
            url: "controller/administracion/facturacion?opc=datos_factura_paciente",
            type: "POST",
            data: {'id_paciente': id_paciente},
            dataType: 'json',
            success: function (data) {
                limpiarModal();
            	if(data.length>0){
            		$("#m_actualizar").val("1");
            		$("#m_rfc").val(data[0].rfc)
            		$("#m_nombre_fiscal").val(data[0].nombre_fiscal)
            		$("#m_direccion").val(data[0].direccion_fiscal)
            		$("#m_correo").val(data[0].correo)
                    if(data[0].codigo_postal!="")
            		  $("#m_codigo_postal").val(data[0].codigo_postal)
            		if(data[0].condiciones_pago!="")
                        $("#m_condiciones_pago").val(data[0].condiciones_pago)
            		if(data[0].metodo_pago!="")
                        $("#m_metodo_pago").val(data[0].metodo_pago)
            		if(data[0].forma_pago!="")
                        $("#m_forma_pago").val(data[0].forma_pago)
            		if(data[0].uso_cfdi!="")
                        $("#m_usocfdi").val(data[0].uso_cfdi)
                    if(data[0].cfdi_relacionado!="")
                        $("#m_cfdir").val(data[0].cfdi_relacionado)
            		$("#m_observaciones").val(data[0].observaciones)
            	}
                else{
                    $("#m_actualizar").val("0");
                }

            	$("#modal_datosfp").modal('show');
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
	})


	$(".publico_general").click(function(){
		$("#m_rfc").val('XAXX010101000');
		$("#m_nombre_fiscal").val('Público en General')
        $("#fm_rfc").val('XAXX010101000');
        $("#fm_nombre_fiscal").val('Público en General')
        $("#empresa_rfc").val('XAXX010101000');
        $("#empresa_nombre_fiscal").val('Público en General')
	})




	

//==================================== TIMBRADO POR ORDEN INDIVIDUAL ==================
	$("#c_tabla_individuales").on('click','.timbrar_individual',function(){
        //------------- botón cambiar a timbrando...
        var objeto=$(this).parent();
        var html_at=objeto.html();
        objeto.html('<img src="../../assets/images/cargando.gif" style="width:25px;"><br>Timbrando...')
		//------------- botón cambiar a timbrando...

        let id_orden=$(this).attr("data-orden");
        let id_paciente=$(this).attr("data-paciente");
        let id_empresa=null;
        let tipo='individual';
        let observaciones=null;
		$.ajax({
            url: "controller/administracion/facturacion?opc=datos_cfdi_orden",
            type: "POST",
            data: {'id_orden': id_orden,'id_paciente': id_paciente},
            success: function (data) {
                console.log("data", data);
            	if(data.error==undefined){
                    //--- Envio a timbrar y envió el boton original de timbrando
                    timbrarCFDI(data,id_orden,id_empresa,tipo,observaciones,objeto,html_at);
                }
                else{
                    toastr.error(data.error);
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');
                    objeto.html(html_at)
                }
            }, 
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
	})	


//====================================== TIMBRADO DE FORMA MASIVA
    //------------------------------Agrupando las ordenes
    $("#c_tabla_masiva").on('click','.fm_check',function(){
        var check=$(this);
        let consecutivo=$(this).attr("data-orden");
        if($(this).prop("checked")==true){
            lista_ordenes_masiva.push(consecutivo);
        }
        else{
            lista_ordenes_masiva.splice(lista_ordenes_masiva.indexOf(consecutivo),1);
        }
        
        $.ajax({
            url: "controller/administracion/facturacion?opc=datos_ordenes_fm", 
            type: "POST",
            data: {'lista_ordenes': lista_ordenes_masiva},
            dataType:'json',
            success: function (data) {
                //console.log("data", data);
                if(data.error==1){
                    toastr.error('No se pueden agregar ordenes con precio $0.00');
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');
                    check.prop("checked",false);
                    lista_ordenes_masiva.splice(lista_ordenes_masiva.indexOf(consecutivo),1);
                }
                else{
                    $("#btabla_estudiosfm").html(data.tabla)
                    //console.log("data.tabla)", data.tabla);
                    $("#total_consecutivos_span").html('$'+data.total.toFixed(2))
                }
                cadena_consecutivos=lista_ordenes_masiva.join();
                $("#lista_consecutivos_span").text(' '+cadena_consecutivos)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });

    })

//------------------------Usar descripción en lugar de la lista de estudios en la factura
    $("#fm_udescripcion").click(function(){
        if($(this).prop("checked")==true){
            $("#fm_ddescripcion").attr("readonly",false);
        }
        else{
            $("#fm_ddescripcion").attr("readonly",true);
        }
    })

//------------------------Obtener los datos de facturación
    $("#btn_forma_masiva").click(function(){
        $.ajax({
            url: "controller/administracion/facturacion?opc=datos_factura_masiva",
            type: "POST",
            data: {'d': null},
            dataType: 'json',
            success: function (data) {
                limpiarModal();
                if(data.length>0){
                    $("#fm_actualizar").val("1");
                    $("#fm_rfc").val(data[0].rfc)
                    $("#fm_nombre_fiscal").val(data[0].nombre_fiscal)
                    $("#fm_direccion").val(data[0].direccion_fiscal)
                    $("#fm_correo").val(data[0].correo)
                    $("#fm_codigo_postal").val(data[0].codigo_postal)
                    if(data[0].condiciones_pago!="")
                        $("#fm_condiciones_pago").val(data[0].condiciones_pago)
                    if(data[0].metodo_pago!="")
                        $("#fm_metodo_pago").val(data[0].metodo_pago)
                    if(data[0].forma_pago!="")
                        $("#fm_forma_pago").val(data[0].forma_pago)
                    if(data[0].uso_cfdi!="")
                        $("#fm_usocfdi").val(data[0].uso_cfdi)
                    if(data[0].cfdi_relacionado!="")
                        $("#fm_cfdir").val(data[0].cfdi_relacionado)
                    $("#fm_observaciones").val(data[0].observaciones)
                    if(data[0].usar_descripcion==1){
                        $("#fm_udescripcion").prop("checked",true);
                        $("#fm_ddescripcion").attr("readonly",false);
                    }
                    $("#fm_ddescripcion").val(data[0].descripcion)
                }
                else{
                    $("#fm_actualizar").val("0");
                }

                $("#modal_datos_forma_masiva").modal('show');
                $("#btn_timbrar_masivo").attr("disabled",false);
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
        
    })


//------------------------ Guardar los datos de facturación
$("#enviar_datos_fm").click(function(){
    var formulario=$('#form_factura_masiva').serialize();
    $.ajax({
            url: "controller/administracion/facturacion?opc=guarda_datos_factura_masiva",
            type: "POST",
            data:formulario ,
            success: function (data) {
                //console.log("data", data);
                if(data.trim()=="ok"){
                    toastr.success("Datos guardados");
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');
                    $("#modal_datos_forma_masiva").modal('hide');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
})

//Obtener los datos de la factura para enviar al timbrado

$("#btn_timbrar_masivo").click(function(){
        //------------- botón cambiar a timbrando...
        var objeto=$(this).parent();
        var html_at=objeto.html();
        objeto.html('<img src="../../assets/images/cargando.gif" style="width:25px;"><br>Timbrando...')
        //------------- //botón cambiar a timbrando..
        let observaciones=null;
        let id_empresa=null;
        let tipo='masiva';
        $.ajax({
            url: "controller/administracion/facturacion?opc=datos_cfdi_orden_masiva", 
            type: "POST",
            data: {'lista_ordenes': lista_ordenes_masiva},
            success: function (data) {
                console.log("data:", data);
                if(data.error==undefined){
                    //--- Envio a timbrar y envió el boton original de timbrando
                    timbrarCFDI(data,lista_ordenes_masiva,id_empresa,tipo,observaciones,objeto,html_at);
                }
                else{
                    toastr.error(data.error);
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');
                    objeto.html(html_at)
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

//====================================== TERMINA TIMBRADO DE FORMA MASIVA

//====================================== TIMBRADO PARA EMPRESAS

//------------------------Usar descripción en lugar de la lista de estudios en la factura
    $("#empresa_udescripcion").click(function(){
        if($(this).prop("checked")==true){
            $("#empresa_ddescripcion").attr("readonly",false);
        }
        else{
            $("#empresa_ddescripcion").attr("readonly",true);
        }
    })

    //-------------- lista de empresas dependiendo la fecha de registro de la orden (Empresas)
    $(".fechaEmpresas").change(function(){
        let fechaInicial=$("#fechainicioE").val();
        let fechaFinal=$("#fechafinE").val();
        //console.log(fechaInicial,fechaFinal)
        $.ajax({
            url: "controller/catalogos/Empresa?opc=lista_empresas",
            type: "POST",
            data: {'fecha_inicial': fechaInicial,'fecha_final': fechaFinal},
            success: function (data) {
                $("#empresa").html(data)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    //---- Lista de ordenes dependiendo fecha de registro y empresa (Facturación)
    $("#btn_lista_empresa").click(function(){
        let fechaInicial=$("#fechainicioE").val();
        let fechaFinal=$("#fechafinE").val();
        let empresa=$("#empresa").val();
        $.ajax({
            url: "controller/administracion/facturacion?opc=lista_ordenes_empresas",
            type: "POST",
            data: {'empresa': empresa,'fecha_inicial': fechaInicial,'fecha_final': fechaFinal},
            success: function (data) {
                $("#tabla_ordenes_empresa").dataTable().fnDestroy();
                $("#btabla_ordenes_empresa").html(data.tabla);
                $("#tabla_ordenes_empresa").dataTable()      
                $("#span_dempresa").html(data.nombre_empresa)  
                $("#empresa_id").val(data.id_empresa);        
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    //------------------------------Agrupando las ordenes
    $("#btabla_ordenes_empresa").on('click','.fm_check',function(){
        var check=$(this);
        let consecutivo=$(this).attr("data-orden");
        let empresa=$(this).attr("data-empresa");
        if($(this).prop("checked")==true){
            lista_ordenes_masiva_empresa.push(consecutivo);
        }
        else{
            lista_ordenes_masiva_empresa.splice(lista_ordenes_masiva_empresa.indexOf(consecutivo),1);
        }
        
        $.ajax({
            url: "controller/administracion/facturacion?opc=datos_ordenes_fm",
            type: "POST",
            data: {'lista_ordenes': lista_ordenes_masiva_empresa},
            dataType:'json',
            success: function (data) {
                if(data.error==1){
                    toastr.error('No se pueden agregar ordenes con precio $0.00');
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');
                    check.prop("checked",false);
                    lista_ordenes_masiva_empresa.splice(lista_ordenes_masiva_empresa.indexOf(consecutivo),1);
                }
                else{
                    $("#btabla_estudiosEm").html(data.tabla)
                    //console.log("data.tabla)", data.tabla);
                    $("#total_consecutivosE_span").html('$'+data.total.toFixed(2))
                }
                cadena_consecutivos=lista_ordenes_masiva_empresa.join();
                $("#lista_consecutivosE_span").text(' '+cadena_consecutivos)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });

    })

//------------------ Obtener los datos de facturación de la empresa
    $("#btn_factura_empresa").click(function(){ 
        var id_empresa=$("#empresa_id").val();

        $.ajax({
            url: "controller/administracion/facturacion?opc=datos_cfdi_empresa",
            type: "POST",
            data: {'id_empresa': id_empresa},
            dataType:'json',
            success: function (data) {
                $("#empresa_rfc").val(data.rfc);
                $("#empresa_nombre_fiscal").val(data.nombre_fiscal)
                $("#empresa_correo").val(data.email)
                $("#empresa_direccion").val(data.direccion)
                if(data.cp!="")
                    $("#empresa_codigo_postal").val(data.cp)
                else
                    $("#empresa_codigo_postal").val(0)
                if(data.condiciones_pago!="")
                    $("#empresa_condiciones_pago").val(data.condiciones_pago)
                if(data.metodo_pago!="")
                    $("#empresa_metodo_pago").val(data.metodo_pago)

                if(data.forma_pago!="" && data.forma_pago!=0)
                    $("#empresa_forma_pago").val(data.forma_pago)
                if(data.uso_cfdi="")
                    $("#empresa_usocfdi").val(data.uso_cfdi)
                if(data.cfdi_relacionado="")
                    $("#empresa_cfdir").val(data.cfdi_relacionado)
                $("#empresa_observaciones").val(data.observaciones)
                if(data.usar_descripcion==1){
                    $("#empresa_udescripcion").prop("checked",true);
                    $("#empresa_ddescripcion").attr("readonly",false);
                }
                else{
                    $("#empresa_udescripcion").prop("checked",false);
                    $("#empresa_ddescripcion").attr("readonly",true);
                }
                $("#empresa_ddescripcion").val(data.descripcion)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }

            
        });
        $("#btn_timbrar_empresa").prop("disabled",false)
        $("#modal_datos_empresa").modal('show')
    })


    //------------------------ Guardar los datos de facturación
$("#enviar_datos_empresa").click(function(){
    var formulario=$('#form_factura_empresa').serialize();
    $.ajax({
        url: "controller/administracion/facturacion?opc=guarda_datos_factura_empresa",
        type: "POST",
        data:formulario ,
        success: function (data) {
            //console.log("data", data);
            if(data.trim()=="ok"){
                toastr.success("Datos guardados");
                $('#toast-container').addClass('toast-top-center');
                $('#toast-container').removeClass('toast-top-right');
                $("#modal_datos_empresa").modal('hide');
            }
            else{
                toastr.error('Error al actualizar');
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
})

//Obtener los datos de la factura empresa para enviar al timbrado

$("#btn_timbrar_empresa").click(function(){
        //------------- botón cambiar a timbrando...
        var objeto=$(this).parent();
        var html_at=objeto.html();
        objeto.html('<img src="../../assets/images/cargando.gif" style="width:25px;"><br>Timbrando...')
        //------------- //botón cambiar a timbrando..
        let observaciones="";
        let id_empresa=$("#empresa_id").val();;
        let tipo='empresa';
        $.ajax({
            url: "controller/administracion/facturacion?opc=datos_cfdi_orden_masiva_empresa",
            type: "POST",
            data: {'lista_ordenes': lista_ordenes_masiva_empresa,'id_empresa':id_empresa},
            success: function (data) {
                console.log("data", data);
                if(data.error==undefined){
                    //--- Envio a timbrar y envió el boton original de timbrando
                    timbrarCFDI(data,lista_ordenes_masiva_empresa,id_empresa,tipo,observaciones,objeto,html_at);
                }
                else{
                    toastr.error(data.error);
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');
                    objeto.html(html_at)
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })


//========================================== FACTURAS TIMBRADAS ===================================

//---------------------Descargar XML 
    $("#btabla_facturas_timbradas").on('click','.btn_xml',function(){
        var id_cfdi=$(this).attr("data-id")
        $.ajax({
            url: "controller/administracion/facturacion?opc=obtener_blob_cfdi",
            type: "POST",
            data:{'id_cfdi': id_cfdi},
            success: function (datos) {
                var blob = converBase64toBlob(datos, 'application/xml');
                var blobURL = URL.createObjectURL(blob);
                //window.open(blobURL,'download');
                var a = document.createElement("a");
                document.body.appendChild(a);
                a.style = "display: none";
                a.href = blobURL;
                a.download = id_cfdi+".xml";
                a.click();
                window.URL.revokeObjectURL(blobURL);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })


    //----------------------- Descargar PDF
    $("#btabla_facturas_timbradas").on('click','.btn_pdf',function(){
        var id_cfdi=$(this).attr("data-id")
        $.ajax({
            url: "controller/administracion/facturacion?opc=crear_pdf_cfdi",
            type: "POST",
            data:{'id_cfdi': id_cfdi},
            success: function (datos) {
                console.log("datos", datos);
                window.open("reportes/facturacion/Factura-"+id_cfdi+".pdf");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })

    //----------------------- CANCELAR EN EL SISTEMA
    $("#btabla_facturas_timbradas").on('click','.btn_pcancelar',function(){
        var id_cfdi=$(this).attr("data-id")
        $.ajax({
            url: "controller/administracion/facturacion?opc=precancelar_cfdi",
            type: "POST",
            data:{'id_cfdi': id_cfdi},
            success: function (datos) {
                //console.log("datos", datos);
                window.location.href="/facturacion?msg=cancelado";  
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    })


    //------------------------ Enviar correo al Cliente
    $("#btabla_facturas_timbradas").on('click','.btn_correo',function(){
        var id_cfdi=$(this).attr("data-id")
        var correo= prompt("Ingresa un correo:", "");
          if (correo== null || correo== "") {
                 toastr.error(data.error);
                    $('#toast-container').addClass('toast-top-center');
                    $('#toast-container').removeClass('toast-top-right');
                    objeto.html('no es un correo valido')
          } else {
                $.ajax({
                    type:"POST",
                    url:"controller/administracion/facturacion?opc=envio_correo",
                    data:{'id_cfdi': id_cfdi,'correo': correo},
                    error: function(objeto, quepaso, otroobject){
                        console.log('Error al obtener blob_cfdi: ' + quepaso + " " + otroobject);
                    },
                    success:function(datos){
                        console.log("datos", datos);
                    }
                });
          }
    });

//---------------------------- Filtrar timbradas
    $(".btn_filtro_timbradas").click(function(){
        var form=$("#ft_form").serialize();
        $.ajax({
            type:"POST",
            url:"controller/administracion/facturacion?opc=filtrar_timbradas",
            data:form,
            error: function(objeto, quepaso, otroobject){
                console.log('Error al obtener blob_cfdi: ' + quepaso + " " + otroobject);
            },
            success:function(datos){
                //console.log("datos", datos);
                $("#btabla_facturas_timbradas").html(datos)
            }
        });
    })

    
    //---------------- Modal para cancelación de una factura
    $("#btabla_facturas_canceladas").on('click','.btn-cancelar_cfdi',function(){
        var id_cfdi=$(this).attr('data-id');
        var id_cfdi_tabla=$(this).attr('data-id-tabla');
        $("#idCfdiC").val(id_cfdi);
        $("#id_cfdi_tabla").val(id_cfdi_tabla);
        $("#modal_motivo_cancelacion").modal('show')
    });


    //cancelar una factura SAT

    $("#cancelar_Sat").click(function(){ 
        var motivo=$("#motivoC").val();
        var uuid_relacionado=$("#uuidC").val();
        var idCfdi=$("#idCfdiC").val();
        var idCfdi_tabla=$("#id_cfdi_tabla").val();
        Facturama.Cfdi.CancelarMotivo(idCfdi, motivo, uuid_relacionado, function(result){ 
            //console.log("result1", result);
            $.ajax({
                type:"POST",
                url:"controller/administracion/facturacion?opc=registra_cancelacion",
                data:{'id_cfdi': idCfdi,'id_cfdi_tabla': idCfdi_tabla,'motivo': motivo,'uuid_relacionado': uuid_relacionado,'mensaje' : result.Message, 'status' : result.Status},
                error: function(objeto, quepaso, otroobject){
                    console.log('Error al obtener blob_cfdi: ' + quepaso + " " + otroobject);
                },
                success:function(datos){
                    //console.log("datos", datos);
                    $("#div_respuesta").html('Respuesta'+result.Message)
                }
            });
 
        }, function(error) {
            console.log("error", error);
        });

    })



});

//-------------------------TERMINA DOCUMENT.READY

//==================== API FACTURAMA

function listarCFDIdeRFC(rfc){
    Facturama.Cfdi.List("?rfc=" + rfc, function(result){ 
            clientUpdate = result;
            console.log("todos",result);
        });
}

function verCFDI(rfc){
    //obtener los certificados de un rfc en especifico
    Facturama.Certificates.Get(rfc, function (result) {
        certif = result;
        console.log("obtener CSD de un RFC ", result);
    });
}

function eliminarCFDI(rfc){
    //eliminar los certificados del rfc especificado
    Facturama.Certificates.Remove(rfc, function (result) {
        console.log("se elimino", result);
    }, function (error) {
        if (error && error.responseJSON) {
            console.log("errores", error.responseJSON);
        }
    });
}

function converBase64toBlob(content, contentType) {
    contentType = contentType || '';
    var sliceSize = 512;
    var byteCharacters = window.atob(content); //method which converts base64 to binary
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);
        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }
        var byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, {type: contentType}); //statement which creates the blob
    return blob;
}

function limpiarModal(){
    $("#m_actualizar").val("1");
    $("#m_rfc").val('')
    $("#m_nombre_fiscal").val('')
    $("#m_direccion").val('')
    $("#m_correo").val('')
    $("#m_codigo_postal").val('0')
    $("#m_condiciones_pago").val(1)
    $("#m_metodo_pago").val('PUE')
    $("#m_forma_pago").val(1)
    $("#m_usocfdi").val(1)
    $("#m_observaciones").val('')
//----------masiva
    $("#fm_actualizar").val("1");
    $("#fm_rfc").val('')
    $("#fm_nombre_fiscal").val('')
    $("#fm_direccion").val('')
    $("#fm_correo").val('')
    $("#fm_codigo_postal").val('0')
    $("#fm_condiciones_pago").val(1)
    $("#fm_metodo_pago").val('PUE')
    $("#fm_forma_pago").val(1)
    $("#fm_usocfdi").val(1)
    $("#fm_observaciones").val('')
}


//========================= Función para hacer el timbrado de un CFDI 
var fecha=new Date();
function timbrarCFDI(newCfdi, lista_ordenes,id_empresa,tipo,observaciones,objeto,html_objeto){

    Facturama.Cfdi.Create(newCfdi, function(result){ 
        //console.log("result", result);
        cfdi = result;
        //descargar el XML del cfdi
        Facturama.Cfdi.Download("xml", "issuedLite", cfdi.Id, function(result){
            fecha_actual=fecha.getFullYear()+"-"+(fecha.getMonth()+1)+"-"+fecha.getDate()+" "+fecha.getHours()+":"+fecha.getMinutes()+":"+fecha.getSeconds();
            $.ajax({
                url: "controller/administracion/facturacion?opc=guarda_cfdi",
                type: "POST",
                data: {'tipo': tipo ,'rfc':newCfdi.Receiver.Rfc,'lista_ordenes':lista_ordenes,'id_empresa': id_empresa,'folio': newCfdi.Folio, 'id_cfdi': cfdi.Id, 'fecha_emision':fecha_actual,'blob': result.Content,'observaciones':''},
                success: function (data) {
                    //console.log("data", data);
                    window.location.href="/facturacion?msg=facturado";  
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });
    }, function(error) {
        if (error && error.responseJSON) {
            toastr.error('Erro al Timbrar:'+JSON.stringify(error.responseJSON.ModelState));
            $('#toast-container').addClass('toast-top-center');
            $('#toast-container').removeClass('toast-top-right');
        }
        objeto.html(html_objeto);
    });

}

function getParameterByName( name ) //courtesy Artem
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return decodeURIComponent(results[1].replace(/\+/g, " "));
}


