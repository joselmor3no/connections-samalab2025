/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChart2);
    
    function drawChart() {
        var tmp = [];
        tmp.push(["Estudios", "total"]);
        var estudios = $(".estudios");
        for (var i = 0; i < estudios.length; i++) {
            tmp.push([estudios[i].dataset.estudio + " (" + estudios[i].dataset.total + ")", parseFloat(estudios[i].dataset.total)]);
        }
        
        var data = google.visualization.arrayToDataTable(tmp);

        var options = {
            title: 'Estudios más vendidos durante el día',
            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }


    function drawChart2() {
        
        var tmp = [];
        tmp.push(["Horario", "Ventas"]);
        var horario = $(".horario");
        for (var i = 0; i < horario.length; i++) {
            tmp.push([horario[i].dataset.horario+":00" , parseFloat(horario[i].dataset.total)]);
        }
        
        var data = google.visualization.arrayToDataTable(tmp);

        var options = {
            title: 'Horarios de ventas',
            hAxis: {title: 'Horario', titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }


