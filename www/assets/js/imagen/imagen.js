$(document).ready(function () { 

	if ($("#msg").val() == "ok") {
        toastr.success("Operación Exitosa");
        $('#toast-container').addClass('toast-top-center');
        $('#toast-container').removeClass('toast-top-right');
    }

	$(".summernote").summernote({height: 600});	
	$(".summernoteL").summernote({height: screen.height/2});	

	$("#lista_formatos_existentes").on('click','.btn-editar_formato',function(){
		var id_formato=$(this).attr("data-id")
		$.ajax({
	            url: "controller/imagen/imagen?opc=contenido_formato",
	            type: "POST",
	            data: {'id_formato': id_formato},
	            dataType: 'json', 
	            success: function (data) {
	            	var re = /\*str\*/g;
	            	contenido=data.texto
			    	contenido=contenido.replace(re,"&")
	            	$(".summernote").summernote("code",contenido);
	            	$("#id_formato").val(data.id)
	            },
	            error: function (jqXHR, textStatus, errorThrown) {
	                console.log(jqXHR);
	                console.log(textStatus);
	                console.log(errorThrown);
	            }
	    });
	})

	$("#lista_formatos_existentes").on('click','.btn-eliminar_formato',function(){
		var id_formato=$(this).attr("data-id")
		$.ajax({
	            url: "controller/imagen/imagen?opc=eliminar_formato",
	            type: "POST",
	            data: {'id_formato': id_formato},
	            success: function (data) {
	            	location.reload();
	            },
	            error: function (jqXHR, textStatus, errorThrown) {
	                console.log(jqXHR);
	                console.log(textStatus);
	                console.log(errorThrown);
	            }
	    });
	})

	$("#btn-guardar_formato").click(function(){
			var contenido=$(".summernote").val()
			var id_cat_estudio=$("#id_cat_estudio").val();
			var id_formato=$("#id_formato").val();
			var fnombre_estudio=$("#fnombre_estudio").val();
			var datos = new FormData();
			datos.append("contenido", contenido);
			datos.append("id_cat_estudio", id_cat_estudio);
			datos.append("id_formato", id_formato);
			datos.append("nombre_estudio", fnombre_estudio);
			$.ajax({
	            url: "controller/imagen/imagen?opc=guarda_formato",
	            type: "POST",
	            data: datos,
	            cache: false,
	      		contentType: false,
	      		processData: false,
	            success: function (data) {
	            	console.log("data", data);
	            	if(data==""  || data==null){
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

		$("#f_seccion").change(function(){
			
	   		var seccion=$(this).val();
	   	 	$.ajax({
	            url: "controller/imagen/imagen?opc=estudios_seccion",
	            type: "POST",
	            data: {'seccion': seccion},
	            success: function (data) {
	            	$("#f_estudio").html(data.lista);
	            },
	            error: function (jqXHR, textStatus, errorThrown) {
	                console.log(jqXHR);
	                console.log(textStatus);
	                console.log(errorThrown);
	            }
	        });
		})


		$("#f_estudio").change(function(){
			var texto=$("#"+$(this).attr("id")+" option:selected").text();
			$("#fnombre_estudio").val(texto);
	   		var id_cat_estudio=$(this).val();
	   		$("#id_cat_estudio").val(id_cat_estudio);
	   	 	$.ajax({
	            url: "controller/imagen/imagen?opc=formato_estudio",
	            type: "POST",
	            data: {'id_cat_estudio': id_cat_estudio},
	            success: function (data) {
	            	$("#fnombre_estudio").attr("disabled",false);
	            	$("#lista_formatos_existentes").html(data)
	            	$("#id_formato").val("-1")
	            	$(".summernote").summernote("code", '');
	            },
	            error: function (jqXHR, textStatus, errorThrown) {
	                console.log(jqXHR);
	                console.log(textStatus);
	                console.log(errorThrown);
	            }
	        });
		})

	   $(document).on('click','.btn-descomprimir',function(){
			$(this).html('Espere...')
	   		var id_dcm=$(this).attr("data-id_dcm");
	   		var nombrep=$(this).attr("data-nombrep");
	   		var ruta=$(this).attr("data-ruta");
	   		var zip=$(this).attr("data-zip");
	   	 	$.ajax({
	            url: "controller/imagen/imagen?opc=descomprimir_dcm",
	            type: "POST",
	            data: {'id_dcm': id_dcm,'nombrep':nombrep,'ruta':ruta,'zip':zip},
	            success: function (data) {
					console.log(data)
	            	location.reload();
	            },
	            error: function (jqXHR, textStatus, errorThrown) {
	                console.log(jqXHR);
	                console.log(textStatus);
	                console.log(errorThrown);
	            }
	        });
	   });

	   $(document).on('click','.btn-concluir',function(){
	   		var id_dcm=$(this).attr("data-id_dcm");
	   		var nombrep=$(this).attr("data-nombrep");
	   		var ruta=$(this).attr("data-ruta");
	   	 	$.ajax({
	            url: "controller/imagen/imagen?opc=concluir_dcm",
	            type: "POST",
	            data: {'id_dcm': id_dcm,'nombrep':nombrep,'ruta':ruta},
	            success: function (data) {
	            	location.reload();
	            },
	            error: function (jqXHR, textStatus, errorThrown) {
	                console.log(jqXHR);
	                console.log(textStatus);
	                console.log(errorThrown);
	            }
	        });
	   });

	   $(document).on('click','.btn-quitar-conclusion',function(){
	   		var id_dcm=$(this).attr("data-id_dcm");
	   		var nombrep=$(this).attr("data-nombrep");
	   		var ruta=$(this).attr("data-ruta");
	   	 	$.ajax({
	            url: "controller/imagen/imagen?opc=quitar_conclusion",
	            type: "POST",
	            data: {'id_dcm': id_dcm,'nombrep':nombrep,'ruta':ruta},
	            success: function (data) {
	            	location.reload();
	            },
	            error: function (jqXHR, textStatus, errorThrown) {
	                console.log(jqXHR);
	                console.log(textStatus);
	                console.log(errorThrown);
	            }
	        });
	   });

	   

	   $(document).on('click','.elimina-dcm',function(){

	   		var id_dcm=$(this).attr("data-id_dcm");
	   		var ruta=$(this).attr("data-ruta");
	   	 	$.ajax({
	            url: "controller/imagen/imagen?opc=eliminar_dcm",
	            type: "POST",
	            data: {'id_dcm': id_dcm,'ruta':ruta},
	            success: function (data) {
	            	location.reload();
	            },
	            error: function (jqXHR, textStatus, errorThrown) {
	                console.log(jqXHR);
	                console.log(textStatus);
	                console.log(errorThrown);
	            }
	        });
	   });


	   //------------------Reporte Local
	   
	   $("#tabla_listaplocal").on('click','.btn-local',function(){
	   		$("#ft_nombre_estudio").val($(this).attr('data-nombre'))
	   		$("#ft_id_cat_estudio").val($(this).attr('data-id_estudio'))
	   		$("#ft_id_paciente").val($(this).attr('data-paciente'))
	   		$("#ft_id_orden").val($(this).attr('data-orden'))
	   		$("#ft_id_dcm").val($(this).attr('data-dcm'))
	   		var id_dcm=$(this).attr('data-dcm')
	   		$.ajax({
		            url: "controller/imagen/imagen?opc=resultado_dcm",
		            type: "POST",
		            data: {'id_dcm': id_dcm},
		            success: function (data) {
		            	$(".summernoteL").summernote("code", data);
		            },
		            error: function (jqXHR, textStatus, errorThrown) {
		                console.log(jqXHR);
		                console.log(textStatus);
		                console.log(errorThrown);
		            }
		    });

	   });

	   $("#formatop").change(function(){
	    	var id_formato=$(this).val();
			$.ajax({
		            url: "controller/imagen/imagen?opc=contenido_formato",
		            type: "POST",
		            data: {'id_formato': id_formato},
		            dataType: 'json',
		            success: function (data) {
		            	$(".summernoteL").summernote("code", data.texto);
		            },
		            error: function (jqXHR, textStatus, errorThrown) {
		                console.log(jqXHR);
		                console.log(textStatus);
		                console.log(errorThrown);
		            }
		    }); 
	   })


	   $("#tabla_listaplocal").on('click','.btn-concluir-local',function(){
	   		var id_dcm=$(this).attr('data-dcm');
	   		$.ajax({
		            url: "controller/imagen/imagen?opc=concluir_dcm_local",
		            type: "POST",
		            data: {'id_dcm': id_dcm},
		            success: function (data) {
		            	location.reload();
		            },
		            error: function (jqXHR, textStatus, errorThrown) {
		                console.log(jqXHR);
		                console.log(textStatus);
		                console.log(errorThrown);
		            }
		    });
	   });

});