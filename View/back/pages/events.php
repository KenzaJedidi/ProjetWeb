<?php
$host = 'localhost';
$dbname = 'oussema';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $all_events = $pdo->query("
        SELECT e.*, c.nom AS categorie_name, c.id AS categorie_id 
        FROM evenements e 
        LEFT JOIN categorie c ON e.categorie_id = c.id
    ")->fetchAll(PDO::FETCH_ASSOC);

    $upcoming_events = $pdo->query("
        SELECT e.*, c.nom AS categorie_name 
        FROM evenements e 
        LEFT JOIN categorie c ON e.categorie_id = c.id
        WHERE date_debut > CURDATE() 
        ORDER BY date_debut
    ")->fetchAll(PDO::FETCH_ASSOC);

    $grouped_events = [];
    foreach ($upcoming_events as $event) {
        $date = date('Y-m-d', strtotime($event['date_debut']));
        $grouped_events[$date][] = $event;
    }

    $total_events = count($all_events);
    $status_counts = $pdo->query("SELECT statut, COUNT(*) as count FROM evenements GROUP BY statut")->fetchAll(PDO::FETCH_ASSOC);
    $categories = array_unique(array_column($all_events, 'categorie_name'));
    $total_status = array_sum(array_column($status_counts, 'count'));

} catch(PDOException $e) {
    die("<div class='alert alert-danger'>Erreur : ".$e->getMessage()."</div>");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>Localoo - Événements</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
          <a class="nav-link active bg-gradient-dark text-white" href="events.php">
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
          <a class="nav-link text-dark" href="AfficherPosts.php">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text ms-1">Forum</span>
          </a>
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

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
  <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl">
    <div class="container-fluid py-1 px-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1">
          <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="../pages/dashboard.html">Dashboard</a></li>
          <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Événements</li>
        </ol>
      </nav>
    </div>
  </nav>

  <div class="container-fluid py-4">
    <div class="row mb-4">
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3">
            <div class="d-flex justify-content-between">
              <div>
                <p class="text-sm mb-0">Total Événements</p>
                <h4 class="mb-0"><?= $total_events ?></h4>
              </div>
              <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                <i class="material-symbols-rounded opacity-10">event</i>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3">
            <div class="d-flex justify-content-between align-items-center">
              <p class="text-sm mb-0">Statuts des événements</p>
              <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                <i class="material-symbols-rounded opacity-10">donut_small</i>
              </div>
            </div>
            <div class="chart-container position-relative">
              <canvas id="statusChart"></canvas>
              <div class="chart-center-text"><?= $total_status ?> Év.</div>
            </div>
            <div class="chart-legend">
              <?php foreach($status_counts as $status): 
                $percentage = $total_status > 0 ? round(($status['count'] / $total_status) * 100, 1) : 0; ?>
                <div class="legend-item">
                  <div class="legend-color bg-<?= strtolower(str_replace(' ', '_', $status['statut'])) ?>"></div>
                  <span class="text-xs"><?= $status['statut'] ?> (<?= $percentage ?>%)</span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-6 col-sm-12">
        <div class="card h-100">
          <div class="card-header pb-0"><h6>Prochains Événements</h6></div>
          <div class="card-body p-3">
            <?php if (!empty($grouped_events)): ?>
              <div class="event-timeline">
                <?php foreach ($grouped_events as $date => $events): ?>
                  <div class="event-date-badge">
                    <i class="fas fa-calendar-day me-2"></i>
                    <?= utf8_encode(strftime('%A %d %B %Y', strtotime($date))) ?>
                  </div>
                  <?php foreach ($events as $event): ?>
                    <div class="event-item">
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                          <h6 class="mb-0 text-sm"><?= htmlspecialchars($event['titre']) ?></h6>
                          <p class="text-xs text-muted mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <?= htmlspecialchars($event['ville']) ?>
                          </p>
                        </div>
                        <span class="badge badge-event ms-3"><?= htmlspecialchars($event['categorie_name']) ?></span>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="text-center p-4">
                <p class="text-muted">Aucun événement à venir</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="filter-row mb-4">
      <div class="input-group search-bar">
        <input type="text" class="form-control border rounded-3" id="search-input" placeholder="Rechercher un événement" onkeyup="applyFilters()">
      </div>
      <div class="button-group">
        <a href="../pages/add-event.php" class="btn btn-custom btn-sm"><i class="fas fa-plus me-1"></i>Ajouter</a>
        <div class="dropdown">
          <button class="btn btn-custom btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
            <i class="fas fa-filter me-1"></i>Filtrer <i class="fas fa-caret-down ms-1"></i>
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="filterByCategory('all')">Toutes catégories</a></li>
            <?php foreach($categories as $category): ?>
              <li><a class="dropdown-item" href="#" onclick="filterByCategory('<?= htmlspecialchars($category) ?>')"><?= htmlspecialchars($category) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <button class="btn btn-custom btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal">
          <i class="fas fa-download me-1"></i>Exporter
        </button>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Tous les Événements</h6>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Événement</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ville</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dates</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catégorie</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Participants</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody id="event-table">
              <?php foreach ($all_events as $event):
                $status_class = match($event['statut']) {
                    'Actif' => 'bg-gradient-success',
                    'Annulé' => 'bg-gradient-danger',
                    'Dépassé' => 'bg-gradient-secondary',
                    'En attente' => 'bg-gradient-warning',
                    default => 'bg-gradient-secondary'
                };
              ?>
              <tr>
                <td class="ps-4"><p class="text-xs font-weight-bold mb-0"><?= $event['id'] ?></p></td>
                <td>
                  <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm"><?= htmlspecialchars($event['titre']) ?></h6>
                    </div>
                  </div>
                </td>
                <td><p class="text-xs mb-0"><?= htmlspecialchars($event['ville']) ?></p></td>
                <td><p class="text-xs mb-0"><?= $event['date_debut'] ?> - <?= $event['date_fin'] ?></p></td>
                <td class="text-center"><span class="badge badge-event"><?= htmlspecialchars($event['categorie_name']) ?></span></td>
                <td class="text-center"><span class="text-xs font-weight-bold"><?= number_format($event['participants']) ?></span></td>
                <td class="text-center"><span class="badge badge-sm <?= $status_class ?>"><?= $event['statut'] ?></span></td>
                <td class="text-center">
                  <a href="edit-event.php?id=<?= $event['id'] ?>" class="btn btn-modify btn-sm">Modifier</a>
                  <button type="button" 
                          class="btn btn-danger btn-sm" 
                          data-bs-toggle="modal" 
                          data-bs-target="#deleteChoiceModal"
                          data-event-id="<?= $event['id'] ?>"
                          data-category-id="<?= $event['categorie_id'] ?>">
                    Supprimer
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="deleteChoiceModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Type de suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Que souhaitez-vous supprimer ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" onclick="confirmDelete('event')">Supprimer l'événement</button>
        <button type="button" class="btn btn-warning" onclick="confirmDelete('category')">Supprimer la catégorie</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exportModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content" style="border-radius: 12px;">
      <div class="modal-header bg-gradient-primary py-3" style="background: linear-gradient(195deg, #0ABAB5, #00897B);">
        <h5 class="modal-title text-white fs-6 mb-0">
          <i class="fas fa-file-export me-2"></i>Exporter les données
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
        <div class="d-flex flex-column gap-2">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($status_counts, 'statut')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($status_counts, 'count')) ?>,
                backgroundColor: ['#4CAF50', '#F44336', '#9E9E9E', '#FFC107'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.raw;
                            const percentage = ((value / total) * 100).toFixed(1) + '%';
                            return `${context.label}: ${value} (${percentage})`;
                        }
                    }
                }
            }
        }
    });

    document.querySelectorAll('[data-bs-target="#deleteChoiceModal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            currentEventId = this.dataset.eventId;
            currentCategoryId = this.dataset.categoryId;
        });
    });
});

let currentEventId;
let currentCategoryId;

function confirmDelete(type) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'delete-event.php';
    
    if(type === 'event') {
        form.innerHTML = `
            <input type="hidden" name="action" value="event">
            <input type="hidden" name="id" value="${currentEventId}">
        `;
    } else {
        form.innerHTML = `
            <input type="hidden" name="action" value="category">
            <input type="hidden" name="categorie_id" value="${currentCategoryId}">
        `;
    }
    
    document.body.appendChild(form);
    form.submit();
}

let currentCategory = 'all';

function filterByCategory(category) {
  currentCategory = category;
  applyFilters();
}

function applyFilters() {
  const searchTerm = document.getElementById('search-input').value.toLowerCase();
  const rows = document.querySelectorAll('#event-table tr');

  rows.forEach(row => {
    const category = row.querySelector('td:nth-child(5)').textContent.trim();
    const title = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
    
    const matchCategory = (currentCategory === 'all' || category === currentCategory);
    const matchSearch = title.includes(searchTerm);

    row.style.display = (matchCategory && matchSearch) ? '' : 'none';
  });
}

document.addEventListener('DOMContentLoaded', function() {
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
    form.action = 'export.php';
    
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

<style>
.btn-export-excel, .btn-export-pdf {
  border: none;
  border-radius: 8px;
  padding: 0.8rem;
  width: 100%;
  transition: all 0.2s ease;
}
.btn-export-excel {
  background: linear-gradient(45deg, #217346, #1d6f42);
  color: white;
}
.btn-export-pdf {
  background: linear-gradient(45deg, #c7162b, #b51224);
  color: white;
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
</body>
</html>