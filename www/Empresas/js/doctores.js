$(document).ready(function () {

	$("#btb_lista_doctores").on('click', '.rounded-circle', function () {

		$("#dnombre_doctor").html($(this).attr("data-nombre"))
		var listaSucursales=$(this).attr("data-sucursales")
		console.log("listaSucursales", listaSucursales);
		$.each(listaSucursales.split(","), function(i,e){
		    $("#lista_sucursales option[value='" + e + "']").prop("selected", true);
		});
		$("#lista_sucursales").change()
		var id_doctor=$(this).attr("data-id")
		$("#id_doctor_sucursales").val(id_doctor)

	});

	$("#editar_medico").click(function(e){
		e.preventDefault();
		var id_doctor=$("#id_doctor_sucursales").val()
		var listaSucursales=$("#lista_sucursales").val();

		$.ajax({
            url: "controller/Doctor?opc=doctor-sucursales&" + "id_doctor=" + id_doctor + "&lista_sucursales=" + listaSucursales,
            type: "POST",

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

});