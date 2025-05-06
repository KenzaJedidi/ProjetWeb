<?php
include '../../../Controller/ReclamationC.php';

$ReclamationC = new ReclamationC();
$stats = $ReclamationC->getReclamationStatsByType();

// Préparer les données pour Chart.js
$labels = [];
$data = [];
$backgroundColors = [
    '#0ABAB5', // Couleur principale de votre thème
    '#81D8D0', // Variation plus claire
    '#00897B', // Variation plus foncée
    '#4DB6AC', // Teinte intermédiaire
    '#26A69A'  // Autre teinte
];

foreach ($stats as $stat) {
    $labels[] = $stat['Type'];
    $data[] = $stat['count'];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Statistiques des Réclamations par Type</title>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .chart-container {
      width: 90%;
      max-width: 800px;
      margin: 30px auto;
      height: 400px;
    }
    .card-header {
      background-color: #0ABAB5;
      color: white;
    }
    .btn-back {
      background-color: #0ABAB5;
      color: white;
      margin-top: 20px;
    }
    .stat-card {
      margin: 20px auto;
      max-width: 300px;
    }
    .stat-value {
      font-size: 2rem;
      font-weight: bold;
      color: #0ABAB5;
    }
    .summary-cards {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }
    .summary-card {
      width: 22%;
      min-width: 200px;
      margin: 10px;
      text-align: center;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      background: white;
    }
    .summary-card h5 {
      color: #555;
      margin-bottom: 10px;
    }
    .summary-card .count {
      font-size: 24px;
      font-weight: bold;
      color: #0ABAB5;
    }
    @media (max-width: 768px) {
      .summary-card {
        width: 45%;
      }
    }
    @media (max-width: 480px) {
      .summary-card {
        width: 100%;
      }
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <!-- Main Content -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar (identique à reclamation.php) -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <!-- ... Votre navbar existant ... -->
    </nav>

    <!-- Main Container -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="shadow-primary border-radius-lg pt-4 pb-3" style="background-color: #81D8D0;">
                <h6 class="text-white text-capitalize ps-3">Statistiques des Réclamations par Type</h6>
              </div>
            </div>

            <div class="card-body px-0 pb-2">
              <!-- Cartes de résumé -->
              <div class="summary-cards">
                <?php
                $total = array_sum($data);
                $maxType = $labels[array_search(max($data), $data)];
                $minType = $labels[array_search(min($data), $data)];
                ?>
                <div class="summary-card">
                  <h5>Total Réclamations</h5>
                  <div class="count"><?php echo $total; ?></div>
                </div>
                <div class="summary-card">
                  <h5>Types différents</h5>
                  <div class="count"><?php echo count($labels); ?></div>
                </div>
                <div class="summary-card">
                  <h5>Type le plus fréquent</h5>
                  <div class="count"><?php echo $maxType; ?></div>
                </div>
                <div class="summary-card">
                  <h5>Type le moins fréquent</h5>
                  <div class="count"><?php echo $minType; ?></div>
                </div>
              </div>

              <!-- Graphique histogramme -->
              <div class="row">
                <div class="col-12">
                  <div class="chart-container">
                    <canvas id="typeChart"></canvas>
                  </div>
                </div>
              </div>
              
              <div class="text-center mt-4">
              <a href="reclamtion.php" class="btn btn-custom">Retour à la liste</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Graphique histogramme des types de réclamation
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    const typeChart = new Chart(typeCtx, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
          label: 'Nombre de réclamations',
          data: <?php echo json_encode($data); ?>,
          backgroundColor: <?php echo json_encode(array_slice($backgroundColors, 0, count($labels))); ?>,
          borderColor: '#00897B',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Répartition des Réclamations par Type',
            font: {
              size: 16
            }
          },
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Nombre de réclamations'
            },
            ticks: {
              stepSize: 1
            }
          },
          x: {
            title: {
              display: true,
              text: 'Types de réclamation'
            }
          }
        }
      }
    });
  </script>
</body>
</html>