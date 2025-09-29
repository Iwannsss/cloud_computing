<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Ambil data jumlah lokasi parkir per kecamatan
$kecamatanList = ['Mpunda', 'Rasanae Barat', 'Rasanae Timur', 'Asakota', 'Raba'];
$jumlahParkirPerKecamatan = [];

foreach ($kecamatanList as $kec) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM parkir WHERE kecamatan = ?");
    $stmt->bind_param("s", $kec);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $jumlahParkirPerKecamatan[$kec] = $result['total'];
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard DISHUB</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6f9;
    }
    .side-menu {
        position: fixed;
        background: #333;
        width: 220px;
        min-height: 100vh;
        color: white;
        padding-top: 20px;
    }
    .side-menu h1 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 30px;
    }
    .side-menu ul {
        padding-left: 0;
    }
    .side-menu li {
        list-style: none;
    }
    .side-menu li a {
        display: block;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
    }
    .side-menu li a.active, .side-menu li a:hover {
        background: #d32f2f;
    }
    .main-content {
        margin-left: 230px;
        padding: 30px;
    }
    .card-kecamatan {
        border-radius: 1rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }
    .card-kecamatan:hover {
        transform: translateY(-5px);
    }
    .chart-container {
    position: relative;
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
}
#chartKecamatan {
    width: 100% !important;
    height: auto !important;
    max-height: 400px;
}

  </style>
</head>
<body>

<div class="side-menu">
  <h1>DISHUB</h1>
  <ul>
    <li><a href="index.php" class="active">Dashboard</a></li>
    <li><a href="daftar_parkir.php">Informasi Legalitas</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<div class="main-content">
  <h2 class="mb-4">Dashboard Lokasi Parkir - Per Kecamatan</h2>
  <div class="row">
    <?php foreach ($jumlahParkirPerKecamatan as $kec => $total): ?>
      <div class="col-md-4 mb-4">
        <div class="card card-kecamatan">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($kec) ?></h5>
            <p class="card-text">Jumlah Lokasi Parkir: <strong><?= $total ?></strong></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="card mt-4 p-4">
  <h5 class="mb-3">Grafik Jumlah Lokasi Parkir per Kecamatan</h5>
  <div class="chart-container">
    <canvas id="chartKecamatan"></canvas>
  </div>
</div>
<script>
const ctx = document.getElementById('chartKecamatan').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($jumlahParkirPerKecamatan)) ?>,
        datasets: [{
            label: 'Jumlah Lokasi Parkir',
            data: <?= json_encode(array_values($jumlahParkirPerKecamatan)) ?>,
            backgroundColor: 'rgba(211, 47, 47, 0.7)',
            borderColor: 'rgba(211, 47, 47, 1)',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>

  </div>
</div>
</body>
</html>
