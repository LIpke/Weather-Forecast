<?php
if(isset($_POST['lat']) && isset($_POST['lon'])) {
    $latitude = $_POST['lat'];
    $longitude = $_POST['lon'];

    
    $servername = "localhost";
    $username = "root"; 
    $password = "";
    $dbname = "weather_db";

 
    $conn = new mysqli($servername, $username, $password, $dbname);

  
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $apiKey = '4e49499dccfba191a01406fb8aaba246';
    $url = 'http://api.openweathermap.org/data/2.5/weather?lat=' . $latitude . '&lon=' . $longitude . '&appid=' . $apiKey;

    $weatherData = json_decode(file_get_contents($url));

    if($weatherData) {
        $temperature = $weatherData->main->temp - 273.15;
        $windSpeed = $weatherData->wind->speed;
        $description = $weatherData->weather[0]->description;

        $sql = "INSERT INTO weather_data (latitude, longitude, temperature, wind_speed, description)
        VALUES ('$latitude', '$longitude', '$temperature', '$windSpeed', '$description')";

        if ($conn->query($sql) === TRUE) {
            echo "<br>";
            echo "The temperature is:$temperature °C";
            echo "<br>";
            echo "The wind speed is: $windSpeed";
            echo "<br>";
            echo "Weather description:$description";
        } else {
            echo "Błąd: " . $sql . "<br>" . $conn->error;
        }
    } else { 
        echo 'Nie udało się pobrać danych pogodowych.';
    }

    $conn->close();
} else {
    echo 'Brak danych o szerokości i długości geograficznej.';
}
?>
