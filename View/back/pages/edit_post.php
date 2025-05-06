<?php
// edit_post.php
$host = 'localhost';
$dbname = 'reddit';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("<div class='alert alert-danger'>Erreur de connexion à la base de données.</div>");
}

// Récupérer la liste des utilisateurs pour le select
$users = $pdo->query("SELECT id, username FROM users ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);

$post = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$post) {
        exit("<div class='alert alert-warning'>Post non trouvé.</div>");
    }
}

// Traitement du POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_GET['id'];
    // Récupérer et valider
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $title   = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    $content = trim(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING));

    // Gestion upload image
    $filename = $post['image'];
    if (!empty($_FILES['image']['tmp_name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $uploadDir = dirname(_FILE_) . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newName);
        $filename = $newName;
    }

    // $p = new post
    // $pc  

    // $pc->edit($, $p)

    // Update
    $upd = $pdo->prepare(
        "UPDATE posts SET user_id = :uid, title = :t, content = :c, image = :img WHERE id = :id"
    );
    $success = $upd->execute([
        ':uid' => $user_id,
        ':t'   => $title,
        ':c'   => $content,
        ':img' => $filename,
        ':id'  => $id
    ]);

    if ($success) {
        header("Location: edit_post.php?id=$id&success=1");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <link href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet"/>
  <title>Modifier Post – <?= htmlspecialchars($post['title'] ?? '') ?></title>
  <style>
    .form-text { color: red; font-size: 0.9em; }
    .success-message { color: green; margin-bottom: 1rem; }
  </style>
</head>
<body class="g-sidenav-show bg-gray-100">
  <!-- Sidebar -->
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="dashboard.html">
        <img src="../assets/img/logolocaloo.png" class="navbar-brand-img" alt="Logo">
      </a>
    </div>
    <hr class="horizontal dark" />
    <div class="collapse navbar-collapse w-auto">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link text-dark" href="dashboard.html"><i class="material-symbols-rounded opacity-5">dashboard</i><span class="nav-link-text ms-1">Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="events.php"><i class="material-symbols-rounded opacity-5">table_view</i><span class="nav-link-text ms-1">Événements</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="emploi.html"><i class="material-symbols-rounded opacity-5">receipt_long</i><span class="nav-link-text ms-1">Emploi</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="review.html"><i class="material-symbols-rounded opacity-5">view_in_ar</i><span class="nav-link-text ms-1">Review</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="reclamation.html"><i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i><span class="nav-link-text ms-1">Réclamation</span></a></li>
        <li class="nav-item"><a class="nav-link active bg-gradient-dark text-white" href="forum.php"><i class="material-symbols-rounded opacity-5">forum</i><span class="nav-link-text ms-1">Forum</span></a></li>
      </ul>
    </div>
  </aside>
  
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl">
      <div class="container-fluid py-1 px-3">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1">
          <li class="breadcrumb-item text-sm"><a href="forum.php">Forum</a></li>
          <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Modifier Post</li>
        </ol>
      </div>
    </nav>
    <div class="container-fluid py-4">
      <?php if (isset($_GET['success'])): ?>
        <div class="success-message">Post modifié avec succès !</div>
      <?php endif; ?>
      <div class="card">
        <div class="card-header p-3 bg-gradient-dark text-white d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Modifier le Post</h6>
          <a href="forum.php" class="btn btn-light btn-sm">Retour</a>
        </div>
        <div class="card-body p-4">
          <form method="POST" enctype="multipart/form-data" novalidate>
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="input-group input-group-static">
                  <label for="user_id">Auteur</label>
                  <select name="user_id" id="user_id" class="form-control" required>
                    <?php foreach($users as $u): ?>
                      <option value="<?= $u['id'] ?>" <?= ($post['user_id']==$u['id'])?'selected':'' ?>><?= htmlspecialchars($u['username']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="input-group input-group-static">
                  <label for="title">Titre</label>
                  <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div class="input-group input-group-static">
                  <label for="content">Contenu</label>
                  <textarea name="content" id="content" class="form-control" rows="4" required><?= htmlspecialchars($post['content']) ?></textarea>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="input-group input-group-static">
                  <label for="image">Image (laisser vide pour ne pas changer)</label>
                  <input type="file" name="image" id="image" class="form-control" accept="image/*">
                </div>
                <?php if ($post['image']): ?>
                  <div class="mt-2">
                    <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Image actuelle" style="max-height:100px;border-radius:4px;" />
                  </div>
                <?php endif; ?>
              </div>
              <div class="col-12 text-end">
                <button type="submit" class="btn btn-custom">Enregistrer</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</body>
</html>