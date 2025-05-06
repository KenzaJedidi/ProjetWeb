<?php
$host = 'localhost';
$dbname = 'reddit';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requêtes pour les statistiques du forum
    $total_posts = $pdo->query("SELECT COUNT(*) AS total FROM posts")->fetch()['total'];
    $total_comments = $pdo->query("SELECT COUNT(*) AS total FROM comments")->fetch()['total'];
    $avg_comments = $pdo->query("SELECT AVG(comment_count) AS moyenne FROM (SELECT COUNT(*) as comment_count FROM comments GROUP BY post_id) AS counts")->fetch()['moyenne'];

    // Récupération des posts avec auteur
    $all_posts = $pdo->query("
        SELECT p.*, u.username 
        FROM posts p 
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Posts récents (7 derniers jours)
    $recent_posts = $pdo->query("
        SELECT p.*, u.username 
        FROM posts p 
        LEFT JOIN users u ON p.user_id = u.id
        WHERE p.created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
        ORDER BY p.created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    $grouped_posts = [];
    foreach ($recent_posts as $post) {
        $date = date('Y-m-d', strtotime($post['created_at']));
        $grouped_posts[$date][] = $post;
    }

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
  <title>Localoo - Forum</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <style>
    .sidenav .navbar-brand img { content: url('../assets/img/logolocaloo.png'); }
    .nav-link.active, .nav-link.bg-gradient-dark { background: linear-gradient(195deg, #0ABAB5, #00897B) !important; }
    .table thead th { background: #0ABAB5; color: white; }
    .btn-custom { background-color: #0ABAB5; color: white; }
    .btn-custom:hover { background-color: #00897B; }
    .badge-event { background-color: #0ABAB5; }
    .event-timeline { border-left: 2px solid #0ABAB5; margin-left: 15px; }
    .event-item { position: relative; padding-left: 25px; margin-bottom: 20px; }
    .event-item::before { content: ''; position: absolute; left: -6px; top: 4px; width: 12px; height: 12px; background: #0ABAB5; border-radius: 50%; }
    .event-date-badge { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 8px 12px; margin-bottom: 10px; }
    .alert-center { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; min-width: 300px; text-align: center; animation: alertSlide 0.5s ease-out; }
    @keyframes alertSlide { from { transform: translate(-50%, -200%); opacity: 0; } to { transform: translate(-50%, -50%); opacity: 1; } }
  </style>
</head>
<body class="g-sidenav-show bg-gray-100">

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success alert-dismissible fade show alert-center">
  <div class="d-flex align-items-center">
    <i class="fas fa-check-circle me-2"></i>
    <span>Action réussie</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show alert-center">
  <div class="d-flex align-items-center">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <span><?= htmlspecialchars($_GET['error']) ?></span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"></i>
    <a class="navbar-brand px-4 py-3 m-0" href="dashboard.html">
      <img src="../assets/img/logolocaloo.png" class="navbar-brand-img" width="250" height="250" alt="Logo Localoo">
    </a>
  </div>
  <hr class="horizontal dark mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link text-dark" href="../pages/dashboard.html"><i class="material-symbols-rounded opacity-5">dashboard</i><span class="nav-link-text ms-1">Dashboard</span></a></li>
      <li class="nav-item"><a class="nav-link text-dark" href="../pages/events.php"><i class="material-symbols-rounded opacity-5">table_view</i><span class="nav-link-text ms-1">Événements</span></a></li>
      <li class="nav-item"><a class="nav-link text-dark" href="../pages/emploi.html"><i class="material-symbols-rounded opacity-5">receipt_long</i><span class="nav-link-text ms-1">Emploi</span></a></li>
      <li class="nav-item"><a class="nav-link text-dark" href="../pages/review.html"><i class="material-symbols-rounded opacity-5">view_in_ar</i><span class="nav-link-text ms-1">Review</span></a></li>
      <li class="nav-item"><a class="nav-link text-dark" href="../pages/reclamation.html"><i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i><span class="nav-link-text ms-1">Réclamation</span></a></li>
      <li class="nav-item"><a class="nav-link active bg-gradient-dark text-white" href="../pages/forum.php"><i class="material-symbols-rounded opacity-5">forum</i><span class="nav-link-text ms-1">Forum</span></a></li>
    </ul>
  </div>
  <div class="sidenav-footer position-absolute w-100 bottom-0">
    <div class="mx-3">
      <a class="btn btn-outline-dark mt-4 w-100" href="https://www.localoo.com/documentation">Documentation</a>
      <a class="btn bg-gradient-dark w-100" href="https://www.localoo.com/upgrade">Upgrade to pro</a>
    </div>
  </div>
</aside>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
  <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl">
    <div class="container-fluid py-1 px-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1">
          <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="../pages/dashboard.html">Dashboard</a></li>
          <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Forum</li>
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
                <p class="text-sm mb-0">Total Posts</p>
                <h4 class="mb-0"><?php echo $total_posts ?></h4>
              </div>
              <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                <i class="material-symbols-rounded opacity-10">forum</i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3">
            <div class="d-flex justify-content-between">
              <div>
                <p class="text-sm mb-0">Commentaires</p>
                <h4 class="mb-0"><?php echo number_format($total_comments) ?></h4>
              </div>
              <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                <i class="material-symbols-rounded opacity-10">comment</i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-6 col-sm-12">
        <div class="card h-100">
          <div class="card-header pb-0"><h6>Activité Récente</h6></div>
          <div class="card-body p-3">
            <?php if (!empty($grouped_posts)): ?>
              <div class="event-timeline">
                <?php foreach ($grouped_posts as $date => $posts): ?>
                  <div class="event-date-badge">
                    <i class="fas fa-calendar-day me-2"></i>
                    <?= utf8_encode(strftime('%A %d %B %Y', strtotime($date))) ?>
                  </div>
                  <?php foreach ($posts as $post): ?>
                    <div class="event-item">
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                          <h6 class="mb-0 text-sm"><?= htmlspecialchars($post['title']) ?></h6>
                          <p class="text-xs text-muted mb-0">
                            <i class="fas fa-user me-1"></i>
                            <?= htmlspecialchars($post['username']) ?>
                          </p>
                        </div>
                        <span class="badge badge-event ms-3"><?= date('H:i', strtotime($post['created_at'])) ?></span>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="text-center p-4">
                <p class="text-muted">Aucune activité récente</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex gap-3 mb-4 align-items-center">
      <div class="input-group flex-grow-1">
        <span class="input-group-text" style="background-color: #0ab5ff; color: white;"><i class="fas fa-search" style="color: white;"></i></span>
        <input type="text" class="form-control border rounded-3" id="search-input" placeholder="Rechercher un post" onkeyup="applyFilters()">
      </div>
      <div class="dropdown ms-2">
        <button class="btn btn-custom btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
          <i class="fas fa-filter me-1"></i>Filtrer <i class="fas fa-caret-down ms-1"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#" onclick="filterByAuthor('all')">Tous auteurs</a></li>
          <?php foreach(array_unique(array_column($all_posts, 'username')) as $author): ?>
            <li><a class="dropdown-item" href="#" onclick="filterByAuthor('<?= htmlspecialchars($author) ?>')"><?= htmlspecialchars($author) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <a href="../pages/add_forum.php" class="btn btn-custom btn-sm">Nouveau Post</a>
    </div>

    <div class="card mb-4">
      <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Tous les Posts</h6>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0" id="posts-table">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Titre</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Auteur</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Commentaires</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($all_posts as $post): ?>
              <tr>
                <td>
                  <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm"><?= htmlspecialchars($post['title']) ?></h6>
                    </div>
                  </div>
                </td>
                <td><p class="text-xs mb-0"><?= htmlspecialchars($post['username']) ?></p></td>
                <td><p class="text-xs mb-0"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></p></td>
                <td class="text-center"><span class="badge badge-event"><?= $post['comment_count'] ?? 0 ?></span></td>
                <td class="text-center">
                <td class="text-center">
  <!-- Bouton Voir (nouveau) -->
  <a href="view_post.php?id=<?= $post['id'] ?>" class="btn btn-info btn-sm me-1">Voir</a>
  <!-- Bouton Modifier existant -->
  <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-custom btn-sm me-1">Modifier</a>
  <!-- Formulaire Supprimer existant -->
  <form method="POST" action="delete_post.php" class="d-inline">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer suppression ?')">Supprimer</button>
  </form>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let currentAuthor = 'all';

function filterByAuthor(author) {
  currentAuthor = author;
  applyFilters();
}

function applyFilters() {
  const searchTerm = document.getElementById('search-input').value.toLowerCase();
  const rows = document.querySelectorAll('#posts-table tr');

  rows.forEach(row => {
    const author = row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
    const title = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
    const matchAuthor = (currentAuthor === 'all' || author === currentAuthor.toLowerCase());
    const matchSearch = title.includes(searchTerm) || author.includes(searchTerm);
    row.style.display = (matchAuthor && matchSearch) ? '' : 'none';
  });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.alert-center').forEach(alert => {
        setTimeout(() => { alert.style.opacity = '0'; setTimeout(() => alert.remove(), 500); }, 5000);
    });
});
</script>
</body>
</html>