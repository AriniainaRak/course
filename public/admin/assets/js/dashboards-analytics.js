/**
 * Dashboard Analytics
 */

'use strict';

(function () {
    let cardColor, headingColor, axisColor, shadeColor, borderColor;



    function stockerValeurs(callback) {
        console.log('miditra ato izy');
        fetch('/classement_equipe').then(response => response.json()).then(data => {
            //   console.log('miditra ato koa izy');
            console.log('miditra ato izy');
            const valeurs = data.map(item => item.points); // Utiliser la méthode map pour extraire la propriété 'nombre' de chaque objet
            const equipe = data.map(item => item.equipe);
            console.log("Voici les valeurs : ", valeurs); // Afficher les valeurs dans la console
            console.log("Voici les equipes : ", equipe); // Afficher les valeurs dans la console
            callback(valeurs,equipe); // Appel du callback avec les valeurs en argument
        })
            .catch(error => console.error('Une erreur s\'est produite : ', error));
    }

    // Order Statistics Chart
    // --------------------------------------------------------------------
    stockerValeurs(function (valeurs,equipe) {

    console.log("Valeurs récupérées dans le callback : ", valeurs);
    cardColor = config.colors.white;
    headingColor = config.colors.headingColor;
    axisColor = config.colors.axisColor;
    borderColor = config.colors.borderColor;

        const chartOrderStatistics = document.querySelector('#orderStatisticsChart'),
            orderChartConfig = {
                chart: {
                    height: 165,
                    width: 130,
                    type: 'donut'
                },
                labels: equipe,
                series: valeurs,
                colors: [config.colors.primary, config.colors.secondary, config.colors.info, config.colors.success],
                stroke: {
                    width: 5,
                    colors: cardColor
                },
                dataLabels: {
                    enabled: false,
                    formatter: function (val, opt) {
                        return parseInt(val) + '%';
                    }
                },
                legend: {
                    show: false
                },
                grid: {
                    padding: {
                        top: 0,
                        bottom: 0,
                        right: 15
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                value: {
                                    fontSize: '1.5rem',
                                    fontFamily: 'Public Sans',
                                    color: headingColor,
                                    offsetY: -15,
                                    formatter: function (val) {
                                        return parseInt(val) + '%';
                                    }
                                },
                                name: {
                                    offsetY: 20,
                                    fontFamily: 'Public Sans'
                                },
                                total: {
                                    show: true,
                                    fontSize: '0.8125rem',
                                    color: axisColor,
                                    label: 'Weekly',
                                    formatter: function (w) {
                                        return '38%';
                                    }
                                }
                            }
                        }
                    }
                }
            };

            const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
            statisticsChart.render();

    });


});
