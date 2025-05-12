<?php
include_once '../../../Controller/OffreEmploiController.php';
include_once '../../../Model/OffreEmploi.php';

$offreController = new OffreEmploiController();
$salaryStats = $offreController->getSalaryStats();

// Préparation des données pour Chart.js
$labels = json_encode(array_column($salaryStats, 'salary_range'));
$data = json_encode(array_column($salaryStats, 'count'));
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Statistiques des Salaires</title>
  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .chart-container {
      width: 80%;
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
  </style>
  <style>
    .sidenav .navbar-brand img { content: url('../assets/img/logolocaloo.png'); }
    .nav-link.active, .nav-link.bg-gradient-dark { background: linear-gradient(195deg, #0ABAB5, #00897B) !important; }
    .table thead th { background: #0ABAB5; color: white; }
    .btn-custom { 
      background-color: #0ABAB5; 
      color: white; 
      padding: 0.25rem 0.75rem; 
      min-width: 100px; 
    }
    .btn-custom:hover { background-color: #00897B; }
    .btn-modify { background-color: #FF5722; color: white; }
    .btn-modify:hover { background-color: #E64A19; }
    .badge-event { background-color: #0ABAB5; }
    .event-timeline { border-left: 2px solid #0ABAB5; margin-left: 15px; }
    .event-item { position: relative; padding-left: 25px; margin-bottom: 20px; }
    .event-item::before { content: ''; position: absolute; left: -6px; top: 4px; width: 12px; height: 12px; background: #0ABAB5; border-radius: 50%; }
    .event-date-badge { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 8px 12px; margin-bottom: 10px; }
    .chart-container { position: relative; height: 150px; }
    .chart-legend { display: flex; justify-content: center; gap: 15px; margin-top: 10px; }
    .legend-item { display: flex; align-items: center; gap: 5px; }
    .legend-color { width: 12px; height: 12px; border-radius: 50%; }
    .bg-actif { background-color: #4CAF50; }
    .bg-annulé { background-color: #F44336; }
    .bg-dépassé { background-color: #9E9E9E; }
    .bg-en_attente { background-color: #FFC107; }
    .chart-center-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; color: #0ABAB5; }
    .alert-center {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 9999;
      width: auto;
      max-width: 90%;
    }
    .custom-alert {
      min-width: 250px;
      max-width: 400px;
      padding: 8px 15px;
      font-size: 0.9rem;
      border: none;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      background-color: #0ABAB5;
      color: white;
      display: inline-flex;
      align-items: center;
    }
    .custom-alert.alert-success {
      background-color: #0ABAB5 !important;
    }
    .custom-alert.alert-danger {
      background-color: #e74c3c !important;
    }
    .btn-close-sm {
      filter: invert(1);
      padding: 0.3rem;
      margin-left: 10px;
      opacity: 0.8;
    }
    .button-group {
      display: flex;
      gap: 15px; /* Maintain increased spacing */
      align-items: center;
      justify-content: flex-end; /* Align buttons to the right */
      flex: 1; /* Take up remaining space */
    }
    .input-group.search-bar {
      max-width: 70%; /* Increased to make search bar longer */
      flex: 0 0 70%; /* Ensure search bar takes fixed proportion */
    }
    .filter-row {
      display: flex;
      width: 100%;
      align-items: center;
      justify-content: space-between; /* Distribute space across the line */
    }
  </style>
</head>
<!-- Sidenav -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
         aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="dashboard.html">
        <img src="../assets/img/logolocaloo.png" class="navbar-brand-img" width="250" height="250" alt="Logo Localoo">
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="dashboard.html">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="events.php">
            <i class="material-symbols-rounded opacity-5">notifications</i>
            <span class="nav-link-text ms-1">Evenements</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="AfficherBonPlans.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Bon Plans</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="AfficherReservations.php">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text ms-1">Reservations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="AfficherCommentaires.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Reclamation</span>
          </a>
            <li class="nav-item">
          <a class="nav-link text-dark" href="AfficherPosts.php">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text ms-1">Forum</span>
          </a>
          <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="emploi.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Emploi</span>
          </a>
        </li>
        
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="profile.html">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="sign-in.html">
            <i class="material-symbols-rounded opacity-5">login</i>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="sign-up.html">
            <i class="material-symbols-rounded opacity-5">assignment</i>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0">
      <div class="mx-3">
        <a class="btn btn-outline-dark mt-4 w-100" href="https://www.localoo.com/documentation" type="button">Documentation</a>
        <a class="btn bg-gradient-dark w-100" href="https://www.localoo.com/upgrade" type="button">Upgrade to pro</a>
      </div>
    </div>
  </aside>
<!-- End Sidenav -->
  <!-- Main Content -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Statistiques Salaires</li>
          </ol>
        </nav>
      </div>
    </nav>

    <!-- Main Container -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="shadow-primary border-radius-lg pt-4 pb-3" style="background-color: #0ABAB5;">
                <h6 class="text-white text-capitalize ps-3">Statistiques des Salaires</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="chart-container">
                <canvas id="salaryChart"></canvas>
              </div>
              <div class="text-center mt-4">
                <a href="emploi.php" class="btn btn-back">Retour aux offres</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Graphique des salaires
    const ctx = document.getElementById('salaryChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?php echo $labels; ?>,
        datasets: [{
          label: 'Nombre d\'offres',
          data: <?php echo $data; ?>,
          backgroundColor: [
            'rgba(10, 186, 181, 0.7)',
            'rgba(0, 137, 123, 0.7)',
            'rgba(16, 204, 197, 0.7)',
            'rgba(8, 117, 108, 0.7)',
            'rgba(13, 163, 157, 0.7)'
          ],
          borderColor: [
            'rgba(10, 186, 181, 1)',
            'rgba(0, 137, 123, 1)',
            'rgba(16, 204, 197, 1)',
            'rgba(8, 117, 108, 1)',
            'rgba(13, 163, 157, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Répartition des offres par tranche de salaire',
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
            ticks: {
              stepSize: 1,
              precision: 0
            },
            title: {
              display: true,
              text: 'Nombre d\'offres'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Tranches de salaire (TND)'
            }
          }
        }
      }
    });
  </script>
</body>
</html>