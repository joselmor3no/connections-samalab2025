$(".summernote").summernote({

});

$("#formato").change(function(){
	$.ajax({
			url: "./ajax/reporte_ajax.php",
			data: "opcion=2&id_formato="+$(this).val(),
			error: function (objeto, quepaso, otroobj) {
				console.log("Pasó lo siguiente en catalogo: " + quepaso + " " + otroobj);
			},
			success: function (datos) {
				console.log("datos", datos);
				$(".summernote").summernote("code", datos);
			},
			type: "POST"
		}); //autocomplete
}) 

$("#actualizar_resultado").click(function(){
	var resultado=$("#resultado").val();
	var dcm=getParameterByName("dcm");
	var d=getParameterByName("d");
	var fuente=$("#ft_datos").val();
	var fuente_f=$("#ft_firma").val();
	var fuente_c=$("#ft_cuerpo").val();
	var estudio=$("#estudio").val();
	var ajustar_firma=$("#ajustar_firma").val();
	d=d+"-"+fuente+"-"+fuente_f+"-"+fuente_c+"-"+estudio+"-"+ajustar_firma;
	var nh=$("#nhojas").val();
	var fecha=$("#fecha_estudio").val();
	var formData = new FormData();
	formData.append("dcm", dcm);
	formData.append("fecha", fecha);
	formData.append("estudio", estudio);
	formData.append("resultado", resultado);
	formData.append("opcion", '1');
	formData.append("datos_guardados", d); 
	formData.append("nh", nh);
	$.ajax({
			url: "./ajax/reporte_ajax.php",
			data: formData,
  			processData: false,
  			contentType: false,
			error: function (objeto, quepaso, otroobj) {
				console.log("Pasó lo siguiente en catalogo: " + quepaso + " " + otroobj);
			},
			success: function (datos) {
				//console.log("datos", datos);
				$(".iframe_").attr("src","hoja_carta.php?dcm="+dcm+"&p=1&d="+getParameterByName("d")+"&h="+nh)

			},
			type: "POST"
		}); //autocomplete
})


 function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}