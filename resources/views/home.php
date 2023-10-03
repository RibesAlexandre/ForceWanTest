<header class="master-head">
    <div class="container">
        <div class="row h-100 align-items-center">
            <div class="col-12 text-center">
                <h1 class="fw-light"><?= \App\App::getTitle(); ?></h1>
                <p class="lead">Lorem Ipsum</p>
            </div>
        </div>
    </div>
</header>

<div class="container my-4">
    <div class="row">
        <div class="col-sm-12 col-md-4 text-center py-2">
            <button class="btn btn-success" id="add-good-event">Ajouter un évènement Positif</button>
        </div>
        <div class="col-sm-12 col-md-4 text-center py-2">
            <button class="btn btn-warning" id="add-bad-event">Ajouter un évènement Négatif</button>
        </div>
        <div class="col-sm-12 col-md-4 text-center py-2">
            <button class="btn btn-danger" id="remove-last-event">Supprimer le dernier évènement</button>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <figure class="highcharts-figure">
                <div id="pieChart"></div>
                <p class="highcharts-description">
                    Pourcentage des évènements positifs / négatifs.
                </p>
            </figure>
        </div>
        <div class="col-sm-12 col-md-6">
            <figure class="highcharts-figure">
                <div id="lineChart"></div>
                <p class="highcharts-description">
                    Evolution des évènements positifs / négatifs.
                </p>
            </figure>
        </div>
    </div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    // Create the chart
    window.pieChart = Highcharts.chart('pieChart', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Nature des évènements',
            align: 'left'
        },

        colors: ['#198754', '#dc3545'],

        accessibility: {
            announceNewData: {
                enabled: true
            },
            point: {
                valueSuffix: '%'
            }
        },

        plotOptions: {
            series: {
                borderRadius: 5,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.1f}%'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },

        series: [
            {
                name: 'Evènements',
                colorByPoint: true,
                data: [
                    {
                        name: 'Bons',
                        y: <?= $pieStats['good'] ?>,
                    },
                    {
                        name: 'Mauvais',
                        y: <?= $pieStats['bad'] ?>,
                    },
                ]
            }
        ]
    });

    window.lineChart = Highcharts.chart('lineChart', {

        colors: ['#198754', '#dc3545'],

        title: {
            text: 'Evolution des évènements',
            align: 'left'
        },

        yAxis: {
            title: {
                text: 'Nombre d\'évènements',
            }
        },

        xAxis: {
            categories: <?= json_encode($lineStats['minutes']) ?>,
            accessibility: {
                description: 'Minutes'
            }
        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        series: [
            {
                name: 'Bons Evènements',
                data: <?= json_encode(array_values($lineStats['good'])) ?>
            },
            {
                name: 'Mauvais évènements',
                data: <?= json_encode(array_values($lineStats['bad'])) ?>
            }
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });


</script>