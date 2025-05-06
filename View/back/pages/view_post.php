<?php
// view_post.php
$pdo = new PDO("mysql:host=localhost;dbname=reddit;charset=utf8","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupération du post
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("<div class='alert alert-danger'>Identifiant invalide.</div>");
}
$stmt = $pdo->prepare(
  "SELECT p.*, u.username 
   FROM posts p
   LEFT JOIN users u ON p.user_id = u.id
   WHERE p.id = :id"
);
$stmt->execute([':id' => $id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) {
    die("<div class='alert alert-warning'>Post non trouvé.</div>");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png"/>
  <link rel="icon" type="image/png" href="../assets/img/favicon.png"/>
  <title>Détail du Post – <?= htmlspecialchars($post['title']) ?></title>
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <style>
    .post-image { max-height: 400px; object-fit: cover; border-radius: 8px; }
    .detail-label { font-weight: 600; color: #555; }
    .detail-value { color: #333; }
  </style>
</head>
<body class="g-sidenav-show bg-gray-100">
  <!-- Sidebar répliqué ici -->
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
        <li class="nav-item"><a class="nav-link active bg-gradient-dark text-white" href="../pages/notifications.php"><i class="material-symbols-rounded opacity-5">notifications</i><span class="nav-link-text ms-1">Forum</span></a></li>
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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="forum.php">Forum</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Détail du Post</li>
          </ol>
        </nav>
      </div>
    </nav>
    <div class="container-fluid py-4">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h5 class="mb-0"><?= htmlspecialchars($post['title']) ?></h5>
        </div>
        <div class="card-body">
          <div class="row gx-4 gy-3">
            <?php if ($post['image']): ?>
            <div class="col-md-6">
              <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Image du post" class="img-fluid post-image" />
            </div>
            <?php endif; ?>
            <div class="col-md-<?= $post['image'] ? '6' : '12' ?>">
              <div class="d-flex mb-2">
                <i class="material-symbols-rounded me-1">person</i>
                <span class="detail-label">Auteur :</span>&nbsp;
                <span class="detail-value"><?= htmlspecialchars($post['username'] ?? 'Anonyme') ?></span>
              </div>
              <div class="d-flex mb-2">
                <i class="material-symbols-rounded me-1">schedule</i>
                <span class="detail-label">Créé le :</span>&nbsp;
                <span class="detail-value"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
              </div>
              <div class="mt-3">
                <span class="detail-label">Contenu :</span>
                <p class="detail-value mt-2" style="white-space: pre-wrap;"><?= htmlspecialchars($post['content']) ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-end">
          <a href="notifications.php" class="btn btn-outline-secondary me-2"><i class="fas fa-arrow-left"></i>&nbsp;Retour</a>
        </div>
      </div>
    </div>
  </main>
</body>
</html>