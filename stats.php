<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Weather Stats</title>
  <link rel="stylesheet" href="style.css?2" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <style>
    .content {
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
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
  
  <p class="cloud-hist">Weather Stats</p>


    <div class="content">
      <table>
        <thead>
          <tr>
            <th>Min Temperature</th>
            <th>Max Temperature</th>
            <th>Total Searches</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Establish connection to database
          $servername = "localhost";
          $username = "root"; 
          $password = "";
          $dbname = "weather_db";

          $conn = new mysqli($servername, $username, $password, $dbname);

          // Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          // Fetch data from database
          $sql = "SELECT MIN(temperature) AS min_temp, MAX(temperature) AS max_temp, COUNT(*) AS total_searches FROM weather_data";
          $result = $conn->query($sql);

          // Output data
          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<tr>";
            echo "<td>" . $row["min_temp"] . "</td>";
            echo "<td>" . $row["max_temp"] . "</td>";
            echo "<td>" . $row["total_searches"] . "</td>";
            echo "</tr>";
          } else {
            echo "<tr><td colspan='3'>No results found</td></tr>";
          }
          $conn->close();
          ?>
        </tbody>
      </table>
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
      echo '<h3>Pogoda</h3>';
      echo '<p>Temperatura: ' . ($weatherData->main->temp - 273.15) . '°C</p>';
      echo '<p>Wiatr: ' . $weatherData->wind->speed . ' m/s</p>';
      echo '<p>Opis: ' . $weatherData->weather[0]->description . '</p>';
    } else { 
      echo '<p>Nie udało się pobrać danych pogodowych.</p>';
    }
  }  
  ?>

  <script>
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
  </script>
</body>
</html>
