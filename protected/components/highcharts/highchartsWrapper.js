var highcharts = new Array();
var highchart;
$(document).ready(function() {
    loadHighcharts();
});

function loadHighcharts() {
    for(i in highcharts) {
        if(highcharts[i].request)
            highcharts[i].request.abort()
        if($("#" + highcharts[i].chart.renderTo + ':visible').length > 0)
            $("#" + highcharts[i].chart.renderTo).html('<img class="loading" src="/images/loading.gif" alt="loading" />');
        highcharts[i].request = $.ajax({
            url: highcharts[i].url,
            data: $('#filter-form').serialize(),
            type: 'POST',
            success: function(result, textStatus, jqXHR) {
                for(i in highcharts) {
                    highcharts[i] = highcharts[i];
                    if(highcharts[i].url == this.url) {
                        if($("#" + highcharts[i].chart.renderTo + ':visible').length == 0)
                            return;
                        if(highcharts[i].chart.defaultSeriesType == 'line' || highcharts[i].chart.defaultSeriesType == 'column')
                            highcharts[i].xAxis.categories = result.categories;
                        highcharts[i].series = result.series;
                        console.log('draw');
                        console.log(highcharts[i].series);
                        console.log(result.categories);
                        new Highcharts.Chart(highcharts[i]);
                        drawImageLabels(highcharts[i]);
                    }
                }
            }
        });
    }
}

function drawImageLabels() {
    var chart = this;
    var overLappingPoint;
    var serie;
    var imageWidth = 32;
    for (serieNumber in chart.series) {  
        serie = chart.series[serieNumber];
        for(pointNumber in serie.data) {
            //            console.log(typeof point.text);
            if(typeof serie.data[pointNumber].text != 'undefined') {
                serie.data[pointNumber].iconX = serie.data[pointNumber].plotX + 40;
                serie.data[pointNumber].iconY = serie.data[pointNumber].plotY + 10;
                for(overLappingSerieNumber in chart.series) {
                    overLappingSerie = chart.series[overLappingSerieNumber];
                    for(overLappingPointNumber in overLappingSerie.data) {  
                        overLappingPoint = overLappingSerie.data[overLappingPointNumber];
                        if(overLappingPoint.iconX > serie.data[pointNumber].iconX - imageWidth && overLappingPoint.iconX < serie.data[pointNumber].iconX && overLappingPoint.iconY > serie.data[pointNumber].iconY - imageWidth && overLappingPoint.iconY < serie.data[pointNumber].iconY + imageWidth ) {                           
                            serie.data[pointNumber].iconY = overLappingPoint.iconY - imageWidth;
                        }
                        if(overLappingPoint.iconX > serie.data[pointNumber].iconX)
                            break;
                    }
                }

                url = 'http://ns2stats.org/images/icons/' + serie.data[pointNumber].text + '.png';
                chart.renderer.image(url, serie.data[pointNumber].iconX, serie.data[pointNumber].iconY, imageWidth, imageWidth)
                .add();   
            }
        }
    }
};

function secondsToTime(seconds)
{

    var numdays = Math.floor(seconds / 86400);
    var numhours = Math.floor((seconds % 86400) / 3600);
    var numminutes = Math.floor(((seconds % 86400) % 3600) / 60);
    var numseconds = ((seconds % 86400) % 3600) % 60;

    var string = '';
    if(numdays > 0)
        string += numdays + 'd ';
    if(numhours > 0)
        string += numhours + 'h ';
    if(numminutes > 0)
        string += numminutes + 'm ';
    if(numseconds > 0)
        string += numseconds + 's';
    return string;

}

function formatTimeLabel(x, y) {
    var d = new Date(x);
    var hours = d.getHours().toString();
    var minutes = d.getMinutes().toString();
    if(hours.length == 1)
        hours = '0' + hours;
    if(minutes.length == 1)
        minutes = '0' + minutes;
    return d.getDate() + '.' + (d.getMonth() + 1) + '.' + d.getFullYear() + ' ' + hours +'h : '+ y;
}

function formatTime(inputSeconds) {
    var d = new Date(inputSeconds * 1000);
    var hours = d.getHours().toString();
    var minutes = d.getMinutes().toString();
    var seconds = d.getSeconds().toString();
    if(hours.length == 1)
        hours = '0' + hours;
    if(minutes.length == 1)
        minutes = '0' + minutes;
    if(seconds.length == 1)
        seconds = '0' + seconds;
    return minutes + ':' + seconds;
}

