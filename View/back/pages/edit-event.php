<?php
$host = 'localhost';
$dbname = 'oussema';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit;
}

$categories = $pdo->query("SELECT nom FROM categorie ORDER BY nom")->fetchAll(PDO::FETCH_COLUMN);
$event = null;

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT e.*, c.nom AS categorie_name FROM evenements e LEFT JOIN categorie c ON e.categorie_id=c.id WHERE e.id=?");
    $stmt->execute([$_GET['id']]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$event) exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomCat = trim($_POST['eventCategory']);
    if ($nomCat === '__new__') {
        $newCat = trim($_POST['newCategoryName']);
        if (empty($newCat)) {
            header("Location: edit-event.php?id={$_GET['id']}&error=Nom de catégorie requis");
            exit;
        }
        $nomCat = $newCat;
    }

    $stmt = $pdo->prepare("SELECT id FROM categorie WHERE nom = ?");
    $stmt->execute([$nomCat]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categorie_id = $row['id'];
    } else {
        $ins = $pdo->prepare("INSERT INTO categorie(nom) VALUES(?)");
        $ins->execute([$nomCat]);
        $categorie_id = $pdo->lastInsertId();
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id === 0) die("ID invalide");

    $u = $pdo->prepare("UPDATE evenements SET titre=?, ville=?, date_debut=?, date_fin=?, categorie_id=?, participants=?, statut=?, description=? WHERE id=?");
    $success = $u->execute([
        $_POST['titre'],
        $_POST['ville'],
        $_POST['date_debut'],
        $_POST['date_fin'],
        $categorie_id,
        $_POST['participants'],
        $_POST['statut'],
        $_POST['description'],
        $id
    ]);

    if ($success) header("Location: edit-event.php?id=$id&success=1");
    else header("Location: edit-event.php?id=$id&error=Erreur lors de la mise à jour");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png"/>
  <link rel="icon" type="image/png" href="../assets/img/favicon.png"/>
  <title>Localoo - Modifier Événement</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900"/>
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet"/>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"/>
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"/>
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet"/>
  <style>
    .sidenav .navbar-brand img { content: url('../assets/img/logolocaloo.png'); }
    .nav-link.active, .nav-link.bg-gradient-dark { background: linear-gradient(195deg, #0ABAB5, #00897B) !important; }
    .bg-gradient-tiffany { background: linear-gradient(195deg, #0ABAB5, #00897B) !important; }
    .table thead th { background: #0ABAB5; color: white; }
    .btn-custom { background-color: #0ABAB5; color: white; padding: 0.25rem 0.75rem; min-width: 100px; }
    .btn-custom:hover { background-color: #00897B; }
    .btn-modify { background-color: #FF5722; color: white; }
    .btn-modify:hover { background-color: #E64A19; }
    .badge-event { background-color: #0ABAB5; }
    .event-timeline { border-left: 2px solid #0ABAB5; margin-left: 15px; }
    .event-item { position: relative; padding-left: 25px; margin-bottom: 20px; }
    .event-item::before { content: ''; position: absolute; left: -6px; top: 4px; width: 12px; height: 12px; background: #0ABAB5; border-radius: 50%; }
    .event-date-badge { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 8px 12px; margin-bottom: 10px; }
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
    .error { color: red; font-size: 0.9em; margin-top: 3px; }
    #newCategoryDiv { display: none; }
  </style>
</head>
<body class="g-sidenav-show bg-gray-100">

<?php if (isset($_GET['success'])): ?>
<div class="alert-center">
  <div class="alert alert-success alert-dismissible fade show custom-alert">
    <i class="fas fa-check-circle me-2"></i>
    Modification enregistrée !
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
    <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
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
        <a class="nav-link text-dark" href="AfficherCommentaires.php">
          <i class="material-symbols-rounded opacity-5">receipt_long</i>
          <span class="nav-link-text ms-1">Reclamation</span>
        </a>
      </li>
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
    <?php if ($event): ?>
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-tiffany shadow-dark border-radius-lg p-3 d-flex justify-content-between align-items-center">
              <h6 class="text-white mb-0">Modifier l'événement</h6>
              <a href="events.php" class="btn btn-light btn-sm">Retour</a>
            </div>
          </div>
          <div class="card-body px-4 pb-2">
            <form method="POST" class="p-4" id="eventForm">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <div class="input-group input-group-static">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" id="titre" class="form-control" value="<?= htmlspecialchars($event['titre']) ?>" required maxlength="100">
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="input-group input-group-static">
                    <label for="ville">Ville</label>
                    <input type="text" name="ville" id="ville" class="form-control" value="<?= htmlspecialchars($event['ville']) ?>" required pattern="[A-Za-zÀ-ÿ\s\-]+">
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="input-group input-group-static">
                    <label for="eventCategory">Catégorie</label>
                    <select name="eventCategory" id="eventCategory" class="form-control" required>
                      <option value="" disabled>Choisir une catégorie...</option>
                      <?php foreach ($categories as $nom): ?>
                        <option value="<?= htmlspecialchars($nom) ?>" <?= $nom === $event['categorie_name'] ? 'selected' : '' ?>>
                          <?= htmlspecialchars($nom) ?>
                        </option>
                      <?php endforeach; ?>
                      <option value="__new__">Nouvelle catégorie...</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6 mb-3" id="newCategoryDiv">
                  <div class="input-group input-group-static">
                    <label for="newCategoryName">Nouvelle catégorie</label>
                    <input type="text" name="newCategoryName" id="newCategoryName" class="form-control">
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <div class="input-group input-group-static">
                    <label for="date_debut">Date début</label>
                    <input type="datetime-local" name="date_debut" id="date_debut" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($event['date_debut'])) ?>" required>
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <div class="input-group input-group-static">
                    <label for="date_fin">Date fin</label>
                    <input type="datetime-local" name="date_fin" id="date_fin" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($event['date_fin'])) ?>" required>
                  </div>
                </div>
                <div class="col-md-2 mb-3">
                  <div class="input-group input-group-static">
                    <label for="participants">Participants</label>
                    <input type="number" name="participants" id="participants" class="form-control" value="<?= htmlspecialchars($event['participants']) ?>" required>
                  </div>
                </div>
                <div class="col-md-2 mb-3">
                  <div class="input-group input-group-static">
                    <label for="statut">Statut</label>
                    <select name="statut" id="statut" class="form-control" required>
                      <option value="Actif" <?= $event['statut'] === 'Actif' ? 'selected' : '' ?>>Actif</option>
                      <option value="En attente" <?= $event['statut'] === 'En attente' ? 'selected' : '' ?>>En attente</option>
                      <option value="Dépassé" <?= $event['statut'] === 'Dépassé' ? 'selected' : '' ?>>Dépassé</option>
                      <option value="Annulé" <?= $event['statut'] === 'Annulé' ? 'selected' : '' ?>>Annulé</option>
                    </select>
                  </div>
                </div>
                <div class="col-12 mb-3">
                  <div class="input-group input-group-static">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"><?= htmlspecialchars($event['description']) ?></textarea>
                  </div>
                </div>
                <div class="col-12 text-end">
                  <button type="submit" class="btn btn-custom">Enregistrer</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const eventCategory = document.getElementById('eventCategory');
  const newCategoryDiv = document.getElementById('newCategoryDiv');
  const newCategoryName = document.getElementById('newCategoryName');

  // Initialize visibility of new category input
  if (eventCategory.value === '__new__') {
    newCategoryDiv.style.display = 'block';
    newCategoryName.required = true;
  } else {
    newCategoryDiv.style.display = 'none';
    newCategoryName.required = false;
  }

  // Toggle new category input based on selection
  eventCategory.addEventListener('change', function() {
    if (this.value === '__new__') {
      newCategoryDiv.style.display = 'block';
      newCategoryName.required = true;
    } else {
      newCategoryDiv.style.display = 'none';
      newCategoryName.required = false;
    }
  });

  // Auto-dismiss alerts after 5 seconds
  document.querySelectorAll('.alert-center').forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });
});
</script>
</body>
</html>