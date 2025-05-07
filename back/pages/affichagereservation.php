<?php
// Connexion à la base de données
require_once 'config.php';

// Récupérer les réservations depuis la base de données
try {
    $stmt = $pdo->query("SELECT * FROM reservations ORDER BY date_depart DESC");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcul des statistiques
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reservations WHERE statut = 'active'");
    $reservations_actives = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reservations WHERE statut = 'en_attente'");
    $reservations_attente = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(DISTINCT destination) as total FROM reservations");
    $destinations = $stmt->fetchColumn();
    
    // Taux de confirmation
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reservations WHERE statut = 'active' OR statut = 'confirme'");
    $confirmees = $stmt->fetchColumn();
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reservations");
    $total = $stmt->fetchColumn();
    $taux_confirmation = $total > 0 ? round(($confirmees / $total) * 100) : 0;
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Localoo - Réservation et Voyage
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
    .sidenav .navbar-brand img {
      content: url('../assets/img/logolocaloo.png');
    }
    .sidenav .navbar-brand span {
      color: #0ABAB5;
    }
    .nav-link.active, .nav-link.bg-gradient-dark {
      background: linear-gradient(195deg, #0ABAB5, #00897B) !important;
    }
    .table thead th {
      color: #fff;
      background: #0ABAB5;
    }
    .btn-custom {
      background-color: #0ABAB5;
      border: none;
      color: #fff;
    }
    .btn-custom:hover {
      background-color: #00897B;
      color: #fff;
    }
    .badge-reservation {
      background-color: #0ABAB5;
      color: white;
    }
    .hover-effect {
      transition: all 0.3s ease;
    }
    .hover-effect:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <!-- Sidenav -->
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
         aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="../dashboard.html">
        <img src="../assets/img/logolocaloo.png" class="navbar-brand-img" width="250" height="250" alt="Logo Localoo">
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="../dashboard.html">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="events.html">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Events</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="emploi.html">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Emploi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="reservation-et-voyage.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Reservation et Voyage</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="reclamation.html">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text ms-1">Reclamation</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="Forum.html">
            <i class="material-symbols-rounded opacity-5">notifications</i>
            <span class="nav-link-text ms-1">Forum</span>
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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="../dashboard.html">Dashboard</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Gestion des Réservations et Voyages</li>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Rechercher une réservation...</label>
              <input type="text" class="form-control" id="search-input">
            </div>
          </div>
          <ul class="navbar-nav d-flex align-items-center justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="addreservation.php" class="btn btn-custom btn-sm">Ajouter une Réservation</a>
            </li>
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
              <a href="sign-in.html" class="nav-link text-body p-0">
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
          <h3 class="mb-0 h4 font-weight-bolder">Gestion des Réservations et Voyages</h3>
          <p class="mb-0">Ajouter, modifier ou supprimer des réservations de voyage.</p>
        </div>
      </div>

      <!-- Statistiques adaptées -->
      <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card hover-effect">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Réservations Actives</p>
                  <h4 class="mb-0"><?php echo $reservations_actives; ?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">check_circle</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><?php echo round($reservations_actives / max($total, 1) * 100); ?>% des réservations</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card hover-effect">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Réservations en Attente</p>
                  <h4 class="mb-0"><?php echo $reservations_attente; ?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">pending</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><?php echo round($reservations_attente / max($total, 1) * 100); ?>% des réservations</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card hover-effect">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Destinations Populaires</p>
                  <h4 class="mb-0"><?php echo $destinations; ?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">location_on</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><?php echo $total; ?> réservations au total</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card hover-effect">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Taux de Confirmation</p>
                  <h4 class="mb-0"><?php echo $taux_confirmation; ?>%</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">trending_up</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><?php echo $confirmees; ?> confirmations</p>
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
                <a href="export-reservations.php" class="btn btn-outline-dark btn-sm">Exporter</a>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prénom</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Destination</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($reservation['id']); ?></p>
                      </td>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($reservation['nom']); ?></h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($reservation['prenom']); ?></p>
                      </td>
                      <td>
                        <p class="text-xs mb-0"><?php echo htmlspecialchars($reservation['destination']); ?></p>
                      </td>
                      <td>
                        <p class="text-xs mb-0"><?php echo date('d/m/Y', strtotime($reservation['date_depart'])); ?></p>
                      </td>
                      <td class="align-middle text-center">
                        <?php 
                        $badge_class = '';
                        if ($reservation['statut'] == 'active') {
                            $badge_class = 'bg-gradient-success';
                        } elseif ($reservation['statut'] == 'en_attente') {
                            $badge_class = 'bg-gradient-warning';
                        } else {
                            $badge_class = 'bg-gradient-danger';
                        }
                        ?>
                        <span class="badge badge-sm <?php echo $badge_class; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $reservation['statut'])); ?>
                        </span>
                      </td>
                      <td class="align-middle text-center">
                        <a href="edit-reservation.php?id=<?php echo $reservation['id']; ?>" class="btn btn-custom btn-sm">Modifier</a>
                        <a href="delete-reservation.php?id=<?php echo $reservation['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">Supprimer</a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($reservations)): ?>
                    <tr>
                      <td colspan="7" class="text-center py-4">Aucune réservation trouvée</td>
                    </tr>
                    <?php endif; ?>
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

    <!-- Footer -->
    <footer class="footer py-4">
      <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="copyright text-center text-sm text-muted text-lg-start">
              © <?php echo date('Y'); ?>, made with <i class="fa fa-heart"></i> by
              <a href="https://www.localoo.com" class="font-weight-bold" target="_blank">Localoo</a> for a better web.
            </div>
          </div>
          <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="https://www.localoo.com" class="nav-link text-muted" target="_blank">Localoo</a>
              </li>
              <li class="nav-item">
                <a href="https://www.localoo.com/about" class="nav-link text-muted" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="https://www.localoo.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
              </li>
              <li class="nav-item">
                <a href="https://www.localoo.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
  </main>
  
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    
    // Fonctionnalité de recherche
    document.getElementById("search-input").addEventListener("keyup", function() {
      const value = this.value.toLowerCase();
      document.querySelectorAll("tbody tr").forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
      });
    });
    
    // Confirmation avant suppression
    document.querySelectorAll(".btn-danger").forEach(btn => {
      btn.addEventListener("click", function(e) {
        if (!confirm("Êtes-vous sûr de vouloir supprimer cette réservation ?")) {
          e.preventDefault();
        }
      });
    });
  </script>
</body>
</html>