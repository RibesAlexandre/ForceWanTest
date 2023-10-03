//console.meme("I see you", "Looking at my code", "Not Sure Fry", 400, 400);

const updatePieChart = function (data) {
    console.log(data['pieCharts']);
    window.pieChart.series[0].update({
        data: data['pieCharts']
    });
};

const updateLineChart = function (data) {
    console.log(data['lineCharts']);
    console.log(window.lineChart);
    window.lineChart.series[0].update({
        data: data['lineCharts']['series'][0]['data']
    });
    window.lineChart.series[1].update({
        data: data['lineCharts']['series'][1]['data']
    });

    //window.lineChart.xAxis[0].setCategories(data['lineCharts']['categories'], true);
    window.lineChart.xAxis[0].update({
        categories: data['lineCharts']['categories']
    });
};

document.getElementById('add-good-event').addEventListener('click', function () {
    fetch('?url=/api/good-event')
        .then(response => response.json())
        .then(function(data) {
            //  TODO: un Toast serait plus sympa
            console.log('Evènement positif enregistré');
            updatePieChart(data);
            updateLineChart(data);
        })
        .catch(error => console.error(error));
});

document.getElementById('add-bad-event').addEventListener('click', function () {
    fetch('?url=/api/bad-event')
        .then(response => response.json())
        .then(function(data) {
            //  TODO: un Toast serait plus sympa
            console.log('Evènement négatif enregistré');
            updatePieChart(data);
            updateLineChart(data);
        })
        .catch(error => console.error(error));
});

document.getElementById('remove-last-event').addEventListener('click', function () {
    fetch('?url=/api/remove-last-event')
        .then(response => response.json())
        .then(function(data) {
            //  TODO: un Toast serait plus sympa
            //window.alert('Dernier évènement supprimé');
            updatePieChart(data);
            updateLineChart(data);
        })
        .catch(error => console.error(error));
});