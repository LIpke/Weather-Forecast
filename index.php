<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Weather Forecast Lipus</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <style>
     .modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>
</head>
<body>
  <section class="sidebar">
    <div class="nav-header">
      <p class="logo">Szymon Lipka</p>
      <i class="bx bx-menu btn-menu"></i>
    </div>
    <ul class="nav-links">
      <li>
        <i class="bx bx-search search-btn"></i>
        <input type="text" placeholder="search..." />
        <span class="tooltip">Search</span>
      </li>
      <li>
        <a href="index.php">
          <i class="bx bx-home-alt-2"></i>
          <span class="title">Home</span>
        </a>
        <span class="tooltip">Home</span>
      </li>
      <li>
        <a href="hist.php">
        <i class='bx bx-history'></i>
          <span class="title">History</span>
        </a>
        <span class="tooltip">History</span>
      </li>
      <li>
        <a href="stats.php">
        <i class='bx bx-stats' ></i>
          <span class="title">Stats</span>
        </a>
        <span class="tooltip">Stats</span>
      </li>
      <li>
        <a href="https://github.com/LIpke">
        <i class='bx bxs-user'></i>
          <span class="title">Dev Profile</span>
        </a>
        <span class="tooltip">Dev Profile</span>
      </li>
    </ul>
    <div class="theme-wrapper">
      <i class="bx bxs-moon theme-icon"></i>
      <p>Dark Theme</p>
      <div class="theme-btn">
        <span class="theme-ball"></span>
      </div>
    </div>
  </section>
  <section class="home">
  
  <p class="cloud">Weather Forecast</p>


    <div class="content">
      <div class="map-container" id="map"></div>
      <aside>
      <form id="weatherForm" method="GET" action="save_weather.php">
    <label for="latitude" id="napis">Szerokość geograficzna:</label><br>
    <input type="text" id="latitude" name="lat" required style="width: 90%; text-align: center;"><br>
    <label for="longitude" id="napis">Długość geograficzna:</label><br>   
    <input type="text" id="longitude" name="lon" required style="width: 90%; text-align: center;"><br><br>
    <button style="--clr:#8A2BE2" type="submit" class="btn2" name="submit"><span>Sprawdź pogodę</span><i></i></button>
</form>
        <div id="weatherInfo"></div>
      </aside>
    </div>
  </section>
  <?php
if(isset($_GET['lat']) && isset($_GET['lon'])) {
    $latitude = $_GET['lat'];
    $longitude = $_GET['lon'];
    $apiKey = '4e49499dccfba191a01406fb8aaba246';
    $url = 'http://api.openweathermap.org/data/2.5/weather?lat=' . $latitude . '&lon=' . $longitude . '&appid=' . $apiKey;
    $weatherData = json_decode(file_get_contents($url));

    if($weatherData) {
        $response = '<h3>Pogoda</h3>' .
                    '<p>Temperatura: ' . ($weatherData->main->temp - 273.15) . '°C</p>' .
                    '<p>Wiatr: ' . $weatherData->wind->speed . ' m/s</p>' .
                    '<p>Opis: ' . $weatherData->weather[0]->description . '</p>';
        echo "<script>alert('" . addslashes($response) . "');</script>";   
    } else { 
        echo "<script>alert('Nie udało się pobrać danych pogodowych.');</script>";
    }
}  
?>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
    
<script>
  document.getElementById("weatherForm").addEventListener("submit", async function(event) {
    //event.stopPropagation();
    event.preventDefault();
    //otwóz modal + loader


    const formData = new FormData(this);

    try {
      const response = await fetch("http://localhost/zadanie2/save_weather.php", {
        method: "POST",
        body: formData
      });

      if (!response.ok) {
        throw new Error("działa wszytko");
      }

      const data = await response.text();
      console.log(data);
      //loader na dane z data

      document.getElementById("weatherInfo").innerHTML = data;


    } catch (error) {
      console.error("pojawił sie problem:", error);
 
    }
  });


  
    let center_lat = 52.2297;
    let center_long = 21.0122;
    var map = L.map('map').setView([center_lat, center_long], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 5,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    var marker = L.marker([center_lat, center_long], {draggable: true}).addTo(map);
    marker.on("dragend", function(event){
        document.getElementById("latitude").value = event.target._latlng.lat;
        document.getElementById("longitude").value = event.target._latlng.lng;
    });

    const btn_menu = document.querySelector(".btn-menu");
    const side_bar = document.querySelector(".sidebar");

    btn_menu.addEventListener("click", function () {
      side_bar.classList.toggle("expand");
      changebtn();
    });

    function changebtn() {
      if (side_bar.classList.contains("expand")) {
        btn_menu.classList.replace("bx-menu", "bx-menu-alt-right");
      } else {
        btn_menu.classList.replace("bx-menu-alt-right", "bx-menu");
      }
    }

    const btn_theme = document.querySelector(".theme-btn");
    const theme_ball = document.querySelector(".theme-ball");

    const localData = localStorage.getItem("theme");

    if (localData == null) {
      localStorage.setItem("theme", "light");
    }

    if (localData == "dark") {
      document.body.classList.add("dark-mode");
      theme_ball.classList.add("dark");
    } else if (localData == "light") {
      document.body.classList.remove("dark-mode");
      theme_ball.classList.remove("dark");
    }

    btn_theme.addEventListener("click", function () {
      document.body.classList.toggle("dark-mode");
      theme_ball.classList.toggle("dark");
      if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");
      } else {
        localStorage.setItem("theme", "light");
      }
    });
    
</script>
</body>
</html>

