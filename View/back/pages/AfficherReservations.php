<?php
include '../../../Controller/ReservationC.php';
include '../../../Controller/BonPlanC.php';

$ReservationC = new ReservationC();
$BonPlanC = new BonPlanC();

$listReservations = $ReservationC->AfficherReservation();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Localoo - Back Office Réservations
  </title>
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
  <style>
    .sidenav .navbar-brand img { content: url('../assets/img/logolocaloo.png'); }
    .sidenav .navbar-brand span { color: #0ABAB5; }
    .nav-link.active, .nav-link.bg-gradient-dark { background: linear-gradient(195deg, #0ABAB5, #00897B) !important; }
    .table thead th { background: #0ABAB5; color: white; }
    .btn-custom { background-color: #0ABAB5; color: white; }
    .btn-custom:hover { background-color: #00897B; }
    .badge-resolved { background-color: #43A047; color: white; }
    .badge-pending { background-color: #FB8C00; color: white; }
    .badge-rejected { background-color: #E53935; color: white; }
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
    .custom-alert.alert-success { background-color: #0ABAB5 !important; }
    .custom-alert.alert-danger { background-color: #e74c3c !important; }
    .btn-close-sm {
      filter: invert(1);
      padding: 0.3rem;
      margin-left: 10px;
      opacity: 0.8;
    }
    .btn-export-csv, .btn-export-excel, .btn-export-pdf {
      border: none;
      border-radius: 8px;
      padding: 0.8rem;
      width: 100%;
      transition: all 0.2s ease;
    }
    .btn-export-csv {
      background: linear-gradient(45deg, #6c757d, #5a6268);
      color: white;
    }
    .btn-export-excel {
      background: linear-gradient(45deg, #217346, #1d6f42);
      color: white;
    }
    .btn-export-pdf {
      background: linear-gradient(45deg, #c7162b, #b51224);
      color: white;
    }
    .btn-export-csv:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
    }
    .btn-export-excel:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(33, 115, 70, 0.2);
    }
    .btn-export-pdf:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(199, 22, 43, 0.2);
    }
    .modal-content {
      box-shadow: 0 6px 20px rgba(10, 186, 181, 0.1);
    }
    .alert-light {
      background-color: rgba(250, 250, 250, 0.95);
      font-size: 0.85rem;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">

<?php if (isset($_GET['success'])): ?>
<div class="alert-center">
  <div class="alert alert-success alert-dismissible fade show custom-alert">
    <i class="fas fa-check-circle me-2"></i>
    Opération réussie
    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<div class="alert-center">
  <div class="alert alert-danger alert-dismissible fade show custom-alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <?= htmlspecialchars($_GET['error']) ?>
    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>

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
          <i class="material-symbols-rounded opacity-5">view_in_ar</i>
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
        <a class="nav-link active bg-gradient-dark text-white" href="AfficherReservations.php">
          <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
          <span class="nav-link-text ms-1">Reservations</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="reclamtion.php">
          <i class="material-symbols-rounded opacity-5">receipt_long</i>
          <span class="nav-link-text ms-1">Reclamation</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="AfficherPosts.php">
          <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
          <span class="nav-link-text ms-1">Forum</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="emploi.php">
          <i class="material-symbols-rounded opacity-5">notifications</i>
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
          <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="dashboard.html">Dashboard</a></li>
          <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Gestion Réservations</li>
        </ol>
      </nav>
      <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
          <div class="input-group input-group-outline">
            <label class="form-label">Rechercher une réservation...</label>
            <input type="text" class="form-control">
          </div>
        </div>
        <ul class="navbar-nav d-flex align-items-center justify-content-end">
          <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
              <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
              </div>
            </a>
          </li>
          <li class="nav-item px-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0">
              <i class="material-symbols-rounded fixed-plugin-button-nav">settings</i>
            </a>
          </li>
          <li class="nav-item dropdown pe-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="material-symbols-rounded">notifications</i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4 ms-n5" aria-labelledby="dropdownMenuButton">
              <li class="mb-2"><a class="dropdown-item border-radius-md" href="javascript:;">Aucune notification</a></li>
            </ul>
          </li>
          <li class="nav-item d-flex align-items-center">
            <a href="../pages/sign-in.html" class="nav-link text-body p-0">
              <i class="material-symbols-rounded">account_circle</i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->

  <!-- Main Container -->
  <div class="container-fluid py-2">
    <!-- Titre de la page -->
    <div class="row mb-4">
      <div class="col-12">
        <h3 class="mb-0 h4 font-weight-bolder">Gestion des Réservations</h3>
      </div>
    </div>

    <!-- Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Total</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?php
                      $count = 0;
                      foreach($listReservations as $reservation){
                        $count++;
                      }
                      echo $count;
                    ?>
                    <span class="text-success text-sm font-weight-bolder">+12%</span>
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape shadow text-center border-radius-md" style="background-color: #81D8D0;">
                  <i class="material-symbols-rounded opacity-10">today</i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">En attente</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?php
                      $countPending = 0;
                      foreach($listReservations as $reservation){
                        if($reservation['statut'] == 'En attente'){
                          $countPending++;
                        }
                      }
                      echo $countPending;
                    ?>
                    <span class="text-danger text-sm font-weight-bolder">+3%</span>
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape shadow text-center border-radius-md" style="background-color: #81D8D0;">
                  <i class="material-symbols-rounded opacity-10">pending</i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Confirmées</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?php
                      $countConfirmed = 0;
                      foreach($listReservations as $reservation){
                        if($reservation['statut'] == 'Confirmée'){
                          $countConfirmed++;
                        }
                      }
                      echo $countConfirmed;
                    ?>
                    <span class="text-success text-sm font-weight-bolder">+8%</span>
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape shadow text-center border-radius-md" style="background-color: #81D8D0;">
                  <i class="material-symbols-rounded opacity-10">check_circle</i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6">
        <div class="card">
          <div class="card-body p-3">
            <div class="row">
              <div class="col-8">
                <div class="numbers">
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Annulées</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?php
                      $countCancelled = 0;
                      foreach($listReservations as $reservation){
                        if($reservation['statut'] == 'Annulée'){
                          $countCancelled++;
                        }
                      }
                      echo $countCancelled;
                    ?>
                    <span class="text-danger text-sm font-weight-bolder">-2%</span>
                  </h5>
                </div>
              </div>
              <div class="col-4 text-end">
                <div class="icon icon-shape shadow text-center border-radius-md" style="background-color: #81D8D0;">
                  <i class="material-symbols-rounded opacity-10">cancel</i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Listing des Réservations -->
    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Liste des Réservations</h6>
            <div>
              <a href="AfficherBonPlans.php" class="btn btn-custom btn-sm">Liste Bon Plans</a>
              <button class="btn btn-custom btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="fas fa-download me-1"></i>Exporter
              </button>
            </div>
          </div>
          <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DESTINATION</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DATE DEPART</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DATE RETOUR</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NOMBRE DE PERSONNES</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">COMMENTAIRE</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STATUT</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DATE CREATION</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACTIONS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if($listReservations) {
                      foreach($listReservations as $reservation){
                        // Get BonPlan details based on idBonPlan
                        $bonPlanDestination = "N/A";
                        if(!empty($reservation['idBonPlan'])) {
                          $bonPlan = $BonPlanC->RecupererBonPlan($reservation['idBonPlan']);
                          if($bonPlan && isset($bonPlan['destination'])) {
                            $bonPlanDestination = $bonPlan['destination'];
                          }
                        }
                  ?>
                  <tr>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?php echo $reservation['idReservation']; ?></p>
                    </td>
                    <td>
                      <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($bonPlanDestination); ?></h6>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($reservation['dateDepart']); ?></p>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($reservation['dateRetour']); ?></p>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($reservation['nbPersonne']); ?></p>
                    </td>
                    <td>
                      <p class="text-xs mb-0"><?php echo htmlspecialchars($reservation['commentaire']); ?></p>
                    </td>
                    <td>
                      <?php
                        $statusClass = "";
                        switch($reservation['statut']) {
                          case 'Confirmée':
                            $statusClass = "badge-resolved";
                            break;
                          case 'En attente':
                            $statusClass = "badge-pending";
                            break;
                          case 'Annulée':
                            $statusClass = "badge-rejected";
                            break;
                          default:
                            $statusClass = "badge-pending";
                        }
                      ?>
                      <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($reservation['statut']); ?></span>
                    </td>
                    <td>
                      <p class="text-xs mb-0"><?php echo htmlspecialchars($reservation['dateCreation']); ?></p>
                    </td>
                    <td class="align-middle text-center">
                      <?php if($reservation['statut'] == 'En attente'): ?>
                        <a href="ConfirmReservation.php?idReservation=<?php echo $reservation['idReservation']; ?>" class="btn btn-success btn-sm mb-1">Confirmer</a>
                      <?php endif; ?>
                      <form method="GET" action="ModifierReservation.php">
                        <input type="hidden" value="<?php echo $reservation['idReservation']; ?>" name="idReservation">
                        <input type="submit" class="btn btn-custom btn-sm mb-1" value="Modifier">
                      </form>
                      <a href="SupprimerReservation.php?idReservation=<?php echo $reservation['idReservation']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                  </tr>
                  <?php
                      }
                    } else {
                      echo "<tr><td colspan='9' class='text-center'>Aucune réservation trouvée</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin Listing des Réservations -->
  </div>
  <!-- Fin Main Container -->

  <!-- Modal pour l'exportation -->
  <div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
      <div class="modal-content" style="border-radius: 12px;">
        <div class="modal-header bg-gradient-primary py-3" style="background: linear-gradient(195deg, #0ABAB5, #00897B);">
          <h5 class="modal-title text-white fs-6 mb-0">
            <i class="fas fa-file-export me-2"></i>Exporter les Réservations
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body py-3">
          <div class="alert alert-light border-start border-3 border-primary mb-3 py-2 px-3">
            <div class="d-flex align-items-center gap-2">
              <i class="fas fa-info-circle text-primary fs-5"></i>
              <div>
                <p class="mb-0 small">Sélectionnez le format d'exportation</p>
              </div>
            </div>
          </div>
          
            <button class="btn btn-export-excel" onclick="exportData('excel')">
              <div class="d-flex align-items-center justify-content-center gap-2">
                <i class="fab fa-microsoft fs-5"></i>
                <span class="small">Excel (XLS)</span>
              </div>
              <small class="d-block mt-1 opacity-75">Format tableur</small>
            </button>
            <button class="btn btn-export-pdf" onclick="exportData('pdf')">
              <div class="d-flex align-items-center justify-content-center gap-2">
                <i class="fas fa-file-pdf fs-5"></i>
                <span class="small">PDF</span>
              </div>
              <small class="d-block mt-1 opacity-75">Document portable</small>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="copyright text-center text-sm text-muted text-lg-start">
            © 2023, Localoo
          </div>
        </div>
      </div>
    </div>
  </footer>
</main>

<!-- Core JS Files -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<!-- Control Center for Material Dashboard -->
<script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Gestion des alertes
  document.querySelectorAll('.alert-center').forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });
});

function exportData(format) {
  const form = document.createElement('form');
  form.method = 'GET';
  form.action = 'ExporterReservation.php';

  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'format';
  input.value = format;

  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
  $('#exportModal').modal('hide');
}
</script>

</body>
</html>