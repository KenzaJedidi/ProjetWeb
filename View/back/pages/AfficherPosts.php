<?php
include '../../../Controller/PostC.php';
include '../../../Controller/UserC.php';
include '../../../Controller/CommentC.php';

$PostC = new PostC();
$CommentC = new CommentC();

// Handle search and sorting
$searchQuery = '';
$sortOption = '';

// Get search query if exists
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $listPosts = $PostC->RechercherPostsParTitre($searchQuery);
} else {
    // Get sort option if exists
    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
        $sortOption = $_GET['sort'];
        $listPosts = $PostC->AfficherPosts($sortOption);
    } else {
        $listPosts = $PostC->AfficherPosts();
    }
}

// Get posts with comment counts for chart
$postsWithComments = $PostC->ObtenirPostsAvecCommentaires();

// Prepare chart data
$chartLabels = [];
$chartData = [];

foreach ($postsWithComments as $post) {
    // Truncate long titles for the chart
    $shortTitle = (strlen($post['title']) > 20) ? substr($post['title'], 0, 17) . '...' : $post['title'];
    $chartLabels[] = $shortTitle;
    $chartData[] = $post['comment_count'];
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
    Localoo - Back Office Bon Plans
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
    .table thead th {
      color: #fff;
      background: #0ABAB5;
    }
    .btn-custom {
      background-color: #0ABAB5;
      border: none;
      color: #fff;
    }
    .badge-resolved {
      background-color: #43A047;
      color: white;
    }
    .badge-pending {
      background-color: #FB8C00;
      color: white;
    }
    .badge-rejected {
      background-color: #E53935;
      color: white;
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
          <a class="nav-link text-dark" href="reclamtion.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Reclamation</span>
          </a>
          <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="AfficherPosts.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Gestion Forum</li>
            
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Rechercher un bon plan...</label>
              <input type="text" class="form-control">
            </div>
          </div>
          <ul class="navbar-nav d-flex align-items-center justify-content-end">
            <li class="nav-item d-flex align-items-center">
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
          <h3 class="mb-0 h4 font-weight-bolder">Gestion des Forums</h3>
          <p class="mb-0">Gérer et modérer les posts et commentaires</p>
        </div>
      </div>

      <!-- Search & Filters -->
      <div class="row mb-4">
        <div class="col-12 col-md-8">
          <div class="card">
            <div class="card-body p-3">
              <form action="" method="GET" class="d-flex align-items-center">
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                  <input type="text" class="form-control" placeholder="Rechercher par titre..." name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
                </div>
                <button type="submit" class="btn btn-custom ms-2">Rechercher</button>
                <?php if (!empty($searchQuery)): ?>
                  <a href="AfficherPosts.php" class="btn btn-outline-secondary ms-2">Réinitialiser</a>
                <?php endif; ?>
              </form>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="d-flex align-items-center">
                <span class="me-2">Trier par date:</span>
                <div class="btn-group">
                  <a href="?sort=date_desc" class="btn btn-sm <?php echo $sortOption === 'date_desc' ? 'btn-custom' : 'btn-outline-primary'; ?>">Plus récent</a>
                  <a href="?sort=date_asc" class="btn btn-sm <?php echo $sortOption === 'date_asc' ? 'btn-custom' : 'btn-outline-primary'; ?>">Plus ancien</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Posts with most comments chart -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6 class="mb-0">Posts avec le plus de commentaires</h6>
            </div>
            <div class="card-body p-3">
              <div style="height: 250px;">
                <canvas id="commentsChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Listing des Posts -->
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Liste des Posts <?php if (!empty($searchQuery)): ?><span class="badge bg-primary">Recherche: <?php echo htmlspecialchars($searchQuery); ?></span><?php endif; ?></h6>
              <div>
                <a class="btn btn-custom btn-sm" href="AjouterPost.php">Nouvelle Post</a>
                <a class="btn btn-custom btn-sm" href="AfficherCommentaires.php">Liste Commentaires</a>
                <a class="btn btn-success btn-sm" href="export-posts.php" title="Télécharger en Excel">
                  <i class="fas fa-file-excel me-1"></i> Excel
                </a>
                <button class="btn btn-outline-secondary btn-sm ms-2">Exporter</button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">IMAGE</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">USER</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TITLE</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">CONTENT</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DATE CREATION</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACTIONS</th>

                    </tr>
                  </thead>
                  <?php
                    foreach($listPosts as $post){
                  ?>
                  <tbody>
                    <tr>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $post['id']; ?></p>
                      </td>
                      <td>
                        <img src="uploads/<?php echo $post['image']; ?>" alt="Image du post" class="img-fluid rounded" style="width: 100px; height: 100px;">
                      </td>
                      <td>
                      <h6 class="mb-0 text-sm">
                      <?php 
                        $userC = new UserC();
                        $user = $userC->RecupererUser($post['user_id']);
                        echo $user['username']; ?>
                    </h6>
                      </td>

                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $post['title']; ?></p>
                      </td>
                      <td>
                        <p class="text-xs mb-0"><?php echo $post['content']; ?></p>
                      </td>

                      <td>
                        <p class="text-xs mb-0"><?php echo $post['created_at']; ?></p>
                      </td>

                      <td class="align-middle text-center">
                        <form method="GET" action="ModifierPost.php">
                          <input type="submit"  class="btn btn-custom btn-sm" name="Modifier" value="Modifier">
                          <input type="hidden"  value=<?php echo $post['id']; ?>  name="id">  
                        </form>
                        <a href="SupprimerPost.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                      </td>
                    </tr>

                  </tbody>
                  <?php
                    }
                    ?>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Fin Listing des Réclamations -->
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
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>
    // Chart initialization
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('commentsChart').getContext('2d');
      
      // Chart data
      const chartLabels = <?php echo json_encode($chartLabels); ?>;
      const chartData = <?php echo json_encode($chartData); ?>;
      
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: chartLabels,
          datasets: [{
            label: 'Nombre de commentaires',
            data: chartData,
            backgroundColor: '#0ABAB5',
            borderColor: '#00897B',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              precision: 0
            }
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                title: function(tooltipItems) {
                  return chartLabels[tooltipItems[0].dataIndex];
                }
              }
            }
          }
        }
      });
    });
  </script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>