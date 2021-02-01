<!DOCTYPE html>
<html>

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="/TrainingTasks/MyTask1/LibraryTask1/Shared/View/LogIn.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
    <div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
        <div class="card card0 border-0">
            <div class="row d-flex">
                <div class="col-lg-6">
                    <div class="card1 pb-5">

                        <div class="row px-3 justify-content-center mt-4 mb-5 border-line"> <img
                                src="https://thumbs.dreamstime.com/z/librarian-online-service-platform-knowledge-education-idea-llibrary-bookshelves-guid-isolated-vector-illustration-191844276.jpg"
                                class="image"> </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card2 card border-0 px-4 py-5">
                        <div class="row mb-4 px-3">
                            <h6 class="mb-0 mr-4 mt-2">weather</h6>
                        </div>
                        <form action="" method="post">
                            <div class="row px-3"> <label class="mb-1">
                                    <h6 class="mb-0 text-sm">longitude</h6>
                                </label> <input class="mb-4" type="text" name="long" placeholder="Enter your longitude">
                            </div>
                            <div class="row px-3"> <label class="mb-1">
                                    <h6 class="mb-0 text-sm">latitude</h6>
                                </label> <input type="text" name="lat" placeholder="Enter your latitude"> </div>
                            <div class="row mb-3 px-3"> <button type="submit" name="Weather"
                                    class="btn btn-blue text-center">Get Weather</button> </div>

                            <?php
                             if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                 $long = $_POST["long"];
                                 $lat = $_POST["lat"];
                                 function buildBaseString($baseURI, $method, $params) {
                                    $r = array();
                                    ksort($params);
                                    foreach($params as $key => $value) {
                                        $r[] = "$key=" . rawurlencode($value);
                                    }
                                    return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
                                }
                                
                                function buildAuthorizationHeader($oauth) {
                                    $r = 'Authorization: OAuth ';
                                    $values = array();
                                    foreach($oauth as $key=>$value) {
                                        $values[] = "$key=\"" . rawurlencode($value) . "\"";
                                    }
                                    $r .= implode(', ', $values);
                                    return $r;
                                }
                                
                                $url = 'https://weather-ydn-yql.media.yahoo.com/forecastrss';
                                $app_id = 'L9DUUPFf';
                                $consumer_key = 'dj0yJmk9TjI5UU9VcWZONjdtJmQ9WVdrOVREbEVWVlZRUm1ZbWNHbzlNQT09JnM9Y29uc3VtZXJzZWNyZXQmc3Y9MCZ4PWY4';
                                $consumer_secret = '325b8fca6c79f5792bf6cc80d1848c22145823f5';
                                
                                $query = array(
                                   'lat' => $lat,
                                   'lon' => $long,
                                   // 'location' => 'sunnyvale,ca',
                                    'format' => 'json',
                                );
                                
                                $oauth = array(
                                    'oauth_consumer_key' => $consumer_key,
                                    'oauth_nonce' => uniqid(mt_rand(1, 1000)),
                                    'oauth_signature_method' => 'HMAC-SHA1',
                                    'oauth_timestamp' => time(),
                                    'oauth_version' => '1.0'
                                );
                                
                                $base_info = buildBaseString($url, 'GET', array_merge($query, $oauth));
                                $composite_key = rawurlencode($consumer_secret) . '&';
                                $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
                                $oauth['oauth_signature'] = $oauth_signature;
                                
                                $header = array(
                                    buildAuthorizationHeader($oauth),
                                    'X-Yahoo-App-Id: ' . $app_id
                                );
                                $options = array(
                                    CURLOPT_HTTPHEADER => $header,
                                    CURLOPT_HEADER => false,
                                    CURLOPT_URL => $url . '?' . http_build_query($query),
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_SSL_VERIFYPEER => false
                                );
                                
                                $ch = curl_init();
                                curl_setopt_array($ch, $options);
                                $response = curl_exec($ch);
                                curl_close($ch);
                                
                                //print_r($response);
                                $return_data = json_decode($response);
                                for ($x = 1; $x <= 7; $x++) {
                                echo "<table >";
                                echo "<tr>";
                                echo "<th style='border=1px solid black' >". "day:". "</th>";
                                echo "<th style='border=1px solid black' >". $return_data -> forecasts[$x] -> day. "</th>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th style='border=1px solid black' >". "low:". "</th>";
                                echo "<th style='border=1px solid black' >" . $return_data -> forecasts[$x] -> low . "</th>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th style='border=1px solid black' >". "High:". "</th>";
                                echo "<th style='border=1px solid black' >" . $return_data -> forecasts[$x] -> high . "</th>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th style='border=1px solid black' >". "State:". "</th>";
                                echo "<th style='border=1px solid black'>" . $return_data -> forecasts[$x] -> text . "</th>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th style='border=1px solid black' >". "code:". "</th>";
                                echo "<th style='border=1px solid black'>" . $return_data -> forecasts[$x] -> code . "</th>";
                                echo "</tr>";
                               echo "</table>";
                               echo "-----------------";
                                }
                                }
                                
                                ?>


                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-blue py-4">

            </div>
        </div>
    </div>

</body>

</html>