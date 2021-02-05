<?php
declare(strict_types=1);
session_start();

$snowIcon = "https://www.metaweather.com/static/img/weather/png/sn.png";
$sleetIcon = "https://www.metaweather.com/static/img/weather/png/sl.png";
$hailIcon = "https://www.metaweather.com/static/img/weather/png/sn.png";
$thunderstormIcon = "https://www.metaweather.com/static/img/weather/png/t.png";
$heavyRainIcon = "https://www.metaweather.com/static/img/weather/png/hr.png";
$lightRainIcon = "https://www.metaweather.com/static/img/weather/png/lr.png";
$showersIcon = "https://www.metaweather.com/static/img/weather/png/s.png";
$heavyCloudIcon = "https://www.metaweather.com/static/img/weather/png/hc.png";
$lightCloudIcon = "https://www.metaweather.com/static/img/weather/png/lc.png";
$clearIcon = "https://www.metaweather.com/static/img/weather/png/c.png";


$CityName = "";
$ValidationResponse = "";


if(isset($_SESSION['citySearch']) === false){
    $_SESSION['citySearch'] = "not empty";
    $_SESSION['cityName'] = "not empty";
}


if(isset($_SESSION['citySearch'])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cityName'])) {
        $_SESSION['cityName'] = htmlspecialchars(strip_tags(trim($_POST['cityName'])));

        /* Validation time */
        $PassedValidation = true;


        $ValidSearchCity = true;
        if (Trim($_SESSION['cityName']) === "") {
            $ValidSearchCity = false;
        }
        if ($ValidSearchCity === false) {
            $PassedValidation = false;
        }


        if ($PassedValidation === false) {
            $ValidationResponse .= "<p>Please enter a city name.</p>";
        } else if ($PassedValidation) {
            $SearchPath = "https://www.metaweather.com/api/location/search/?query=" . $_SESSION['cityName'];

            $curl = curl_init(); 
            curl_setopt_array( $curl, array(
                CURLOPT_URL => $SearchPath,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"  
                ),
            ));
            $jsonData = curl_exec( $curl );
            $error = curl_error( $curl );
            curl_close( $curl );

            $jsonArray = json_decode( $jsonData, false );

            $ValidationResponse .= "<div class='location-search__query'>You searched for: <strong>" . $_SESSION['cityName'] . "</strong>.</div>";
            $ValidationResponse .= "<div class='location-search__intro'>Search Results (" . count( $jsonArray ) . " total):</div>";
            $ValidationResponse .= "<div class='location-search__results'>";
            for( $i = 0; $i < count( $jsonArray ); $i++ ){           
                $latLongArray = explode( ",", $jsonArray[$i]->latt_long );
                $latitude = $latLongArray[0];
                $longitude = $latLongArray[1];

                $cityURL = "https://www.metaweather.com/api/location/" . $jsonArray[$i]->woeid . "/";


                $curl = curl_init(); 
                curl_setopt_array( $curl, array(
                    CURLOPT_URL => $cityURL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache"  
                    ),
                ));
                $jsonDataCity = curl_exec( $curl );
                $error = curl_error( $curl );
                curl_close( $curl );


                $jsonArrayCity = json_decode( $jsonDataCity );
                $stateOrCountry = $jsonArrayCity->parent->title;             


                $ValidationResponse .= "<div class='location-search__results__city'>";
                $ValidationResponse .=  "<a href='index.php?city=" . $jsonArray[$i]->title . "&stateOrCountry=" . $stateOrCountry 
                        . "&latitude=" . $latitude . "&longitude=" . $longitude 
                        . "&locationURL=https://www.metaweather.com/api/location/" . $jsonArray[$i]->woeid . "/'>". $jsonArray[$i]->title  
                        . "<span class='location-search__results__state-or-country'>, " . $stateOrCountry . "</span></a>";

                $ValidationResponse .= "</div>";
            }   
            $ValidationResponse .= "</div>";
        }
    } else {
        $_SESSION['cityName'] = "";
    }
}

if(isset($_SESSION['setUnitType']) === false){
    $_SESSION['setUnitType'] = "not empty";
    $_SESSION['unitType'] = "Imperial";
}


if(isset($_SESSION['setUnitType'])){
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['unitType'])) {
        $_SESSION['unitType'] = htmlspecialchars(strip_tags(trim($_POST['unitType'])));
    }
}
 
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" 
              integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="assets/css/main-styles.css?mod=01042021" />
        <link rel="stylesheet" type="text/css" href="assets/css/print-styles.css?mod=12232020" media="print" />
    </head>
    <body class="page-index">
        <div class="body-wrapper">
            <header class="header">
                <div class="container">
                    <div id="logo">
                        <a href="index.php"><img src="" alt=""></a>
                    </div>
                    <div class="main-title-container">
                        <h1 class="main-title-container__main-title">
                            <a href="index.php" class="main-title-container__main-title__link">Weather Info</a>
                        </h1>
                    </div>
                    <div class="subtitle-container">
                        <h2 class="subtitle-container__sub-title">All the Info you Need</h2>
                    </div>
                </div>
            </header>
            <div class="container main-content-container">
                <div class="row">
                    <div class="col-sm-12">
                        <form id="citySearch" class="city-search" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <label class="city-search__city-name-label" for="cityName">City Name</label>
                            <input type="input" id="cityName" class="city-search__input" name="cityName" />
                            <button id="searchCityButton" class="city-search__search-city-button" name="searchCityButton" onsubmit="" type="submit">Search</button>
                        </form>
                        <form id="setUnitType" class="set-unit-type" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <select id="unitType" class="set-unit" name="unitType">
                                <option value=""></option>
                                <option value="Imperial">Imperial</option>
                                <option value="Metric">Metric</option>
                            </select>
                            <button id="setUnitButton" class="set-unit__button" name="setUnitButton" onsubmit="" type="submit">Set Unit</button>
                        </form>
                        <?php echo "Unit Type: " . $_SESSION['unitType']; ?>
                        <?php if ( $ValidationResponse !== "") { echo "<div class='form-transmission-results'>" . $ValidationResponse . "</div>"; } ?>
                        <?php
                        $city = "";
                        $locationURL = "";
                        $latitude = "";
                        $longitude = "";
                        $locationStateCountry = "";
                        
                        
                        if( isset( $_GET['city'] ) ){
                            $city = $_GET['city'];
                        }
                        
                        if( isset( $_GET['stateOrCountry'] ) ){
                            $locationStateCountry = $_GET['stateOrCountry'];
                        }
                        
                        if( isset( $_GET['locationURL'] ) ){
                            $locationURL = $_GET['locationURL'];
                        }   
                        
                        if( isset( $_GET['latitude'] ) ){
                            $latitude = round( $_GET['latitude'], 2 );
                            if( $latitude < 0 ) {
                                $latitude .= " &deg;S";
                            } else if ( $latitude > 0 ) { 
                                $latitude .= " &deg;N";
                            }
                        }
                        
                        if( isset( $_GET['longitude'] ) ){
                            $longitude = round( $_GET['longitude'], 2 );
                            if( $longitude < 0 ) {
                                $longitude .= " &deg;W";
                            } else if ( $longitude > 0 ) { 
                                $longitude .= " &deg;E";
                            }
                        }


                        if( $city !== "" && $locationURL !== "" && $latitude !== "" && $longitude !== "" ) {
                            $curl = curl_init(); 
                            curl_setopt_array( $curl, array(
                                CURLOPT_URL => $locationURL,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "GET",
                                CURLOPT_HTTPHEADER => array(
                                    "cache-control: no-cache"  
                                ),
                            ));
                            $jsonData = curl_exec( $curl );
                            $error = curl_error( $curl );
                            curl_close( $curl );

                            
                            $jsonArray = json_decode( $jsonData );
                            echo "<div class='weather-city'>Weather info for <strong>" . $city . ", " . $locationStateCountry . "</strong>"
                                . " at Latitude <strong>" 
                                . $latitude . "</strong>, Longitude <strong>" . $longitude . "</strong>.</div>";
                            $result = "<div class='weather-info row'>";
                            $i = 0;
                            foreach ( $jsonArray->consolidated_weather as $item=>$consolidated_weather ) {
                                $date = date( 'D', strtotime( $consolidated_weather->applicable_date ) ) . " " . 
                                        date( 'M', strtotime( $consolidated_weather->applicable_date ) ) . " " . 
                                        date( 'd', strtotime( $consolidated_weather->applicable_date ) );
                                $minTempFahrenheit = round( ( $consolidated_weather->min_temp * 9 / 5 ) + 32 );
                                $maxTempFahrenheit = round( ( $consolidated_weather->max_temp * 9 / 5 ) + 32 );
                                $weatherState = $consolidated_weather->weather_state_name;
                                $weatherIcon;
                                $windSpeed = round( $consolidated_weather->wind_speed );
                                $windDirection = $consolidated_weather->wind_direction_compass;
                                $airPressure = round( $consolidated_weather->air_pressure * 0.0295301, 2 );
                                $humidity = $consolidated_weather->humidity;
                                $visibility = round( $consolidated_weather->visibility, 1 );

                                if ( $weatherState === "Snow" ) {
                                    $weatherIcon = $snowIcon;
                                } else if ( $weatherState === "Sleet" ) {
                                    $weatherIcon = $sleetIcon;
                                } else if ( $weatherState === "Hail" ) {
                                    $weatherIcon = $hailIcon;
                                } else if ( $weatherState === "Thunder" ) {
                                   $weatherIcon = $thunderstormIcon;
                                } else if ( $weatherState === "Heavy Rain" ) {
                                    $weatherIcon = $heavyRainIcon;
                                } else if ( $weatherState === "Light Rain" ) {
                                    $weatherIcon = $lightRainIcon;
                                } else if ( $weatherState === "Showers" ) {
                                    $weatherIcon = $showersIcon;   
                                } else if ( $weatherState === "Heavy Cloud" ) {
                                    $weatherIcon = $heavyCloudIcon;
                                } else if ( $weatherState === "Light Cloud" ) {
                                    $weatherIcon = $lightCloudIcon;
                                } else if ( $weatherState === "Clear" ) {
                                    $weatherIcon = $clearIcon;   
                                } else { 
                                    $weatherIcon = "";
                                }

                                $result .= "<div class='col-6 col-sm-3 col-lg-2'>";
                                $result .= "<div class='weather-day'>";
                                if ( $i === 0 ){
                                    $result .= "<div class='weather-day__date'>Today</div>";
                                } else { 
                                    $result .= "<div class='weather-day__date'>" . $date . "</div>";
                                } 
                                $result .= "<div class='weather-day__min-temp'>Low: " . $minTempFahrenheit . " &degF</div>";
                                $result .= "<div class='weather-day__max-temp'>High: " . $maxTempFahrenheit . " &degF</div>";
                                $result .= "<div class='weather-day__conditions'>" . $weatherState . ".</div>";
                                $result .= "<div class='weather-day__wind'>Wind: " . $windDirection . " " . $windSpeed . "mph</div>";
                                $result .= "<div class='weather-day__air-pressure'>Air Pressure: " . $airPressure . " in.</div>";
                                $result .= "<div class='weather-day__humidity'>Humidity: " . $humidity . "%</div>";
                                $result .= "<div class='weather-day__visibility'>Visibility: " . $visibility . " miles</div>";
                                $result .= "<div class='weather-day__image-container'><img class='weather-day__image' src='" . $weatherIcon . "' width='100px' height='100px' /></div>";
                                $result .= "</div>";
                                $result .= "</div>";
                                $i++;
                            }
                            $result .= "</div>";
                            echo $result;
                        }
                        ?>
                        <div>Data provided by <a href="https://www.metaweather.com/" target="_blank">MetaWeather</a>.</div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="footer__message">Copyright &copy; <?php echo date( "Y" ); ?> Weather Info.  All Rights Reserved.</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>
</html>
