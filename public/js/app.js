console.meme("I see you", "Looking at my code", "Not Sure Fry", 400, 400);

const updatePieChart = function (data) {
    window.pieChart.series[0].update({
        data: data['pieCharts']
    });
};

const updateLineChart = function (data) {
    window.lineChart.series[0].update({
        data: data['lineCharts']['series'][0]['data']
    });
    window.lineChart.series[1].update({
        data: data['lineCharts']['series'][1]['data']
    });

    window.lineChart.xAxis[0].update({
        categories: data['lineCharts']['categories']
    });
};

const insertEventInList = function(data) {
    const colDiv = document.createElement('div');
    colDiv.className = "col-sm-12 col-md-6 col-lg-4 col-xl-3";
    colDiv.setAttribute('id', "event_" + data.id);

    const cardDiv = document.createElement('div');
    cardDiv.className = "card my-4";

    const cardBodyDiv = document.createElement('div');
    cardBodyDiv.className = "card-body";

    const cardTitle = document.createElement('h5');
    cardTitle.className = "card-title text-center";
    cardTitle.className += data.status === 'good' ? ' text-success' : ' text-danger';
    cardTitle.textContent = data.status === 'good' ? 'Bon évènement' : 'Mauvais évènement';

    const cardText = document.createElement('p');
    cardText.className = "card-text";
    cardText.textContent = "Enregistré le " + data.created_at;

    cardBodyDiv.appendChild(cardTitle);
    cardBodyDiv.appendChild(cardText);
    cardDiv.appendChild(cardBodyDiv);
    colDiv.appendChild(cardDiv);

    const listEventDiv = document.getElementById('list-events');
    listEventDiv.insertBefore(colDiv, listEventDiv.firstChild);

    const maxEntries = 8;
    while (listEventDiv.children.length > maxEntries) {
        listEventDiv.removeChild(listEventDiv.lastChild);
    }
}

document.getElementById('add-good-event').addEventListener('click', function () {
    fetch('?url=/api/good-event')
        .then(response => response.json())
        .then(function(data) {
            //  TODO: un Toast serait sympa

            updatePieChart(data);
            updateLineChart(data);
            insertEventInList(data.event);
        })
        .catch(error => console.error(error));
});

document.getElementById('add-bad-event').addEventListener('click', function () {
    fetch('?url=/api/bad-event')
        .then(response => response.json())
        .then(function(data) {
            //  TODO: un Toast serait sympa

            updatePieChart(data);
            updateLineChart(data);
            insertEventInList(data.event);
        })
        .catch(error => console.error(error));
});

document.getElementById('remove-last-event').addEventListener('click', function () {
    fetch('?url=/api/remove-last-event')
        .then(response => response.json())
        .then(function(data) {
            //  TODO: un Toast serait sympa
            if( data['status'] === 'success' ) {
                updatePieChart(data);
                updateLineChart(data);

                let cardElement = document.getElementById('event_' + data.id);
                if( cardElement ) {
                    cardElement.remove();
                }
            } else {
                window.alert(data.message);
            }

        })
        .catch(error => console.error(error));
});