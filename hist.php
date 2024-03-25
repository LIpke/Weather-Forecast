<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Weather Forecast Lipus</title>
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
  
  <p class="cloud-hist">Weather Forecast</p>


    <div class="content">
      <table>
        <thead>
          <tr>
            <th>Id</th>
            <th>Temperature</th>
            <th>Wind Speed</th>
            <th>Description</th>
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

// Fetch last 10 records from database
$sql = "SELECT temperature, wind_speed, description FROM weather_data ORDER BY id DESC LIMIT 10";
$result = $conn->query($sql);

// Output data
if ($result->num_rows > 0) {
  $counter = 1;
  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $counter . "</td>"; // Numerowanie rekordów
    echo "<td>" . $row["temperature"] . "</td>";
    echo "<td>" . $row["wind_speed"] . "</td>";
    echo "<td>" . $row["description"] . "</td>";
    echo "</tr>";
    $counter++;
  }
} else {
  echo "0 results";
}
$conn->close();
?>
        </tbody>
      </table>
    </div>
        </section>

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
