<?php
declare(strict_types=1);
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Find useful weather information and forecasts here." />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="weather, forecast, information" />
        <title>Weather Info</title>	
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <![endif]-->
        <link rel="icon" type="image/png" href="" />
        <link rel="stylesheet" type="text/css" href="assets/css/main-styles.css?mod=12232020" />
        <link rel="stylesheet" type="text/css" href="assets/css/print-styles.css?mod=12232020" media="print" />
    </head>
    <body class="page-index">
        <div class="body-wrapper">
            <header class="header">
                <div class="inner-wrapper">
                    <div id="logo">
                        <a href="index.php"><img src="" alt=""></a>
                    </div>
                    <div class="main-title-container">
                        <h1 class="main-title-container__main-title">Weather Info</h1>
                    </div>
                    <div class="subtitle-container">
                        <h2 class="subtitle-container__sub-title">All the Info you Need</h2>
                    </div>
                </div>
            </header>
            <div class="inner-wrapper">
                <div class="content">
                    <div class="content-row">
                        <div class="col-sma-6">
                            <?php
                               $jsonData = file_get_contents( "https://www.metaweather.com/api/location/2475687/" );
                               $jsonArray = json_decode( $jsonData );
                               echo "<div>Weather info for Portland, Oregon:</div>";
                               $result = "";
                               foreach ( $jsonArray->consolidated_weather as $item=>$consolidated_weather ) {
                                   $minTempFahrenheit = round( ( $consolidated_weather->min_temp * 9 / 5 ) + 32 );
                                   $maxTempFahrenheit = round( ( $consolidated_weather->max_temp * 9 / 5 ) + 32 );
                                   
                                   $result .= "<br>";
                                   $result .= $consolidated_weather->applicable_date . " <br>";
                                   $result .= $minTempFahrenheit . " &degF / " . $maxTempFahrenheit . " &degF<br>";
                                   $result .= $consolidated_weather->weather_state_name . " <br>";
                               }
                               $result .= "<br>";
                               echo $result;
                            ?>
                            <div>Data provided by <a href="https://www.metaweather.com/" target="_blank">MetaWeather</a>.</div>
                        </div>
                        <div class="col-sma-6">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
