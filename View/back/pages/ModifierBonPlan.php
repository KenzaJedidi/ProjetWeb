<?php
include '../../../Controller/BonPlanC.php';

$BonPlanC = new BonPlanC();

// Check if idBonPlan is set in GET parameters
if (isset($_GET["idBonplan"])) {
  $idBonplan = $_GET["idBonplan"];
  $bonplan = $BonPlanC->RecupererBonPlan($idBonplan);

  if (isset($_POST["destination"]) && isset($_POST["restaurant"]) && isset($_POST["hotel"])) {
    if (!empty($_POST["destination"]) && !empty($_POST["restaurant"]) && !empty($_POST["hotel"])) {
      // Update the bon plan
      $BonPlan = new BonPlan($_POST['destination'], $_POST['restaurant'], $_POST['hotel']);
      $BonPlanC->ModifierBonPlan($BonPlan, $idBonplan);

      header("Location: AfficherBonPlans.php");
      exit();
    } else {
      $errorMessage = "<label id='form' style='color: red; font-weight: bold;'>&emsp;Tous les champs sont obligatoires!</label>";
    }
  }
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
    Localoo - Modifier un Bon Plan
  </title>
  <!--     Fonts and icons     -->
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
    .btn-custom {
      background-color: #0ABAB5;
      border: none;
      color: #fff;
    }
    .form-label {
      color: #0ABAB5;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
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
          <a class="nav-link active bg-gradient-dark text-white" href="AfficherBonPlans.php">
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
          <a class="nav-link text-dark" href="AfficherPosts.php">
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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="AfficherBonPlans.php">Bon Plans</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Modifier un Bon Plan</li>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Rechercher...</label>
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
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Modifier un Bon Plan</h6>
              </div>
            </div>
            <div class="card-body px-4 pb-2">
              <!-- Form to modify a Bon Plan -->
              <form method="POST" action="">
                <?php if(isset($errorMessage)) echo $errorMessage; ?>
                <div class="row">
                  <div class="col-md-12 mb-3">
                  <label class="form-label">Destination</label>
                    <div class="input-group input-group-outline mb-4">
                      <input type="text" class="form-control" name="destination" value="<?= $bonplan['destination']; ?>" >
                    </div>
                  </div>
                  <div class="col-md-12 mb-3">
                  <label class="form-label">Restaurant</label>
                    <div class="input-group input-group-outline mb-4">
                      <input type="text" class="form-control" name="restaurant" value="<?= $bonplan['restaurant']; ?>" >
                    </div>
                  </div>
                  <div class="col-md-12 mb-3">
                  <label class="form-label">Hôtel</label>
                    <div class="input-group input-group-outline mb-4">
                      <input type="text" class="form-control" name="hotel" value="<?= $bonplan['hotel']; ?>" >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 text-end">
                    <a href="AfficherBonPlans.php" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-custom">Modifier</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin Main Container -->

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
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
</body>

</html>
