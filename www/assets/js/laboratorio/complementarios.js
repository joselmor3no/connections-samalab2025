
$(document).ready(function () {
	
	$("#pacientes").keyup(function(){
	     var busqueda=$(this).val();
	
	     $.ajax({
	        url: "controller/laboratorio/Reporte?opc=buscar-paciente",
            type: "POST",
            data: {'busqueda': busqueda},
            dataType:'json',
	        success: function (respuesta) {
	          $("#opcionesPacientes").html(respuesta.opciones)
	        }
	      });
  	}) 

    $("#pacientes").blur(function(){
        var busqueda=$(this).val();
	     $.ajax({
	        url: "controller/laboratorio/Reporte?opc=buscar-paciente",
            type: "POST",
            data: {'busqueda': busqueda},
            dataType:'json',
	        success: function (respuesta) {
	        	console.log("respuesta", respuesta);
	        	$("#id_orden").val(respuesta.datos[0].id_orden)
	        }
	      });
    })

     $("#pacientes").change(function(){
        var busqueda=$(this).val();
	     $.ajax({
	        url: "controller/laboratorio/Reporte?opc=buscar-paciente",
            type: "POST",
            data: {'busqueda': busqueda},
            dataType:'json',
	        success: function (respuesta) {
	        	console.log("respuesta", respuesta);
	        	$("#id_orden").val(respuesta.datos[0].id_orden)
	        }
	      });
    })

    $("#pacientes").change(function(){
        var busqueda=$(this).val();
	     $.ajax({
	        url: "controller/laboratorio/Reporte?opc=buscar-paciente",
            type: "POST",
            data: {'busqueda': busqueda},
            dataType:'json',
	        success: function (respuesta) {
	        	$("#id_orden").val(respuesta.datos[0].id_orden)
	        }
	      });
    })

    $("#tabla_complementarios").on('change','.lcomplementarios',function(){
    	var id_documento=$(this).val();
    	var id_orden=this.dataset.id_orden
    	var id_estudio=this.dataset.id_estudio
    	let id_lista=$(this).prop("id")
    	var nombreComplementario=$( "#"+id_lista+" option:selected" ).text();
    	$("#ordenComplementario").val(id_orden)
    	$("#estudioComplementario").val(id_estudio)
    	$("#nombreComplementario").val(nombreComplementario)
    	$("#idComplementario").val(id_documento)
    	$("#modal-subirdc H5").text(nombreComplementario);
    	$.ajax({
	        url: "controller/laboratorio/Reporte?opc=orden-complementarios",
            type: "POST",
            data: {'id_documento': id_documento,'id_orden':id_orden},
	        success: function (respuesta) {
	        	console.log("respuesta", respuesta);
	        	if(respuesta!=""){
	        		$("#documentoCargado").html('<span style="color:green;">'+respuesta+'</span>')
	        	}
	        	else{
	        		$("#documentoCargado").html('<span style="color:red;">Sin documento cargado...</span>')
	        	}
	        	
	        }
	      });
    	$("#modal-subirdc").modal('show')
    })

    
    $(".btn-edita-documento").click(function(e){
    	e.preventDefault();
    	var id_documento=this.dataset.id;
    	var nombre_documento=this.dataset.nombre;
    	$("#nombre_documento").val(nombre_documento)
    	$("#id_documento").val(id_documento)
    })

    $("#btn_guarda_documento").click(function(e){
    	e.preventDefault();
    	var id_documento=$("#id_documento").val();
    	var nombre_documento=$("#nombre_documento").val();
    	$.ajax({
	        url: "controller/laboratorio/Reporte?opc=nuevo-complementario",
            type: "POST",
            data: {'id_documento': id_documento,'nombre_documento':nombre_documento},
	        success: function (respuesta) {
	        	//console.log("respuesta", respuesta);
	        	location.reload();
	        }
	     });
    })

    $(".tListaComplementarios").on('click','.btn-elimina-documento',function(){
    	var id_documento=this.dataset.id;
    	console.log("id_documento", id_documento);
    	$.ajax({
	        url: "controller/laboratorio/Reporte?opc=elimina-complementario",
            type: "POST",
            data: {'id_documento': id_documento},
	        success: function (respuesta) {
	        	//console.log("respuesta", respuesta);
	        	location.reload();
	        }
	     });
    })

    $("#tabla_complementarios").on('click',".btn_elimina_complementario",function(){
    	$("#eordenComplementario").val(this.dataset.id_orden)
    	$("#eestudioComplementario").val(this.dataset.id_estudio)
    })


});