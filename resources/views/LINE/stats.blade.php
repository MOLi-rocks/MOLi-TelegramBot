<!DOCTYPE html>
<html>
<head>
    <title>暨大最新公告 LINE Notify</title>
    <link rel="icon" href="https://moli.rocks/favicon.ico" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            margin-bottom: 150px; /* Margin bottom by footer height */
        }

        .line-logo {
            width: 150px;
        }

        .tg-logo {
            width: 50px;
        }

        .fb-logo {
            width: 50px;
        }

        .center-text {
            text-align: center;
            vertical-align: center;
        }

        .piechart {
            height: 300px;
        }

        .padding-top {
            padding-top: 1em;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 150px; /* Set the fixed height of the footer here */
        }

        @media (prefers-color-scheme: dark)
        {
            body {
                background-color: #2a2a2e;
                color: #cfcfd1;
            }
            
            rect {
                fill: #2a2a2e;
            }

            g > text {
                fill: #ffffff;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row padding-top">
            <div class="col center-text">
                <img class="line-logo" src="{{ asset('img/LINE_APP_logo.png') }}" alt="LINE logo">
            </div>
        </div>
        <div class="row padding-top">
            <div class="col center-text">
                Total: {{ $stats['Total']['Total'] }}
            </div>
        </div>
        <div class="row padding-top">
            <div class="col center-text">
                <div id="total-piechart" class="piechart"></div>
            </div>
        </div>
        <div class="row padding-top">
            <div class="col center-text">
                <div id="user-piechart" class="piechart"></div>
            </div>
        </div>
        <div class="row padding-top">
            <div class="col center-text">
                <div id="group-piechart" class="piechart"></div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container padding-top">
            <div class="row">
                <div class="col center-text">
                    聯絡我們 Contact Us
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-7 padding-top">
                    <P>服務供應商：MOLi 實驗室</P>
                    <P>南投縣埔里鎮大學路 470 號管理學院 237 室</P>
                </div>
                <div class="col-12 col-md-2 center-text">
                    <a href="https://telegram.me/MOLi_rocks" target="_blank" rel="noopener">
                        <img class="tg-logo" src="{{ asset('img/telegram_logo.png') }}" alt="Telegram logo">
                    </a>
                    <p>Telegram</p>
                </div>
                <div class="col-12 col-md-3 center-text">
                    <a href="https://www.facebook.com/MOLi.rocks" target="_blank" rel="noopener">
                        <img class="fb-logo" src="{{ asset('img/facebook_logo.png') }}" alt="Facebook logo">
                    </a>
                    <p>Facebook</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        $(function() {
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                let total_data = google.visualization.arrayToDataTable([
                    ['Status', 'Count'],
                    ['Active', {{ $stats['Total']['Active'] }}],
                    ['Inactive', {{ $stats['Total']['Inactive'] }}],
                    ['Others', {{ $stats['Total']['Others'] }}]
                ]);

                let user_data = google.visualization.arrayToDataTable([
                    ['Status', 'Count'],
                    ['Active', {{ $stats['USER']['Active'] }}],
                    ['Inactive', {{ $stats['USER']['Inactive'] }}],
                    ['Others', {{ $stats['USER']['Others'] }}]
                ]);

                let group_data = google.visualization.arrayToDataTable([
                    ['Status', 'Count'],
                    ['Active', {{ $stats['GROUP']['Active'] }}],
                    ['Inactive', {{ $stats['GROUP']['Inactive'] }}],
                    ['Others', {{ $stats['GROUP']['Others'] }}]
                ]);

                let total_options = {
                    title: 'Total Stats',
                    sliceVisibilityThreshold: 0,
                    legend: {
                        alignment: 'center',
                        position: 'bottom'
                    }
                };

                let user_options = {
                    title: 'User Stats',
                    sliceVisibilityThreshold: 0,
                    legend: {
                        alignment: 'center',
                        position: 'bottom'
                    }
                };

                let group_options = {
                    title: 'Group Stats',
                    sliceVisibilityThreshold: 0,
                    legend: {
                        alignment: 'center',
                        position: 'bottom'
                    }
                };

                const total_chart = new google.visualization.PieChart(document.getElementById('total-piechart'));

                const user_chart = new google.visualization.PieChart(document.getElementById('user-piechart'));

                const group_chart = new google.visualization.PieChart(document.getElementById('group-piechart'));

                total_chart.draw(total_data, total_options);
                user_chart.draw(user_data, user_options);
                group_chart.draw(group_data, group_options);
            }
            
            $(window).resize(function () {
                drawChart();
            });
        });
    </script>
</body>
</html>
