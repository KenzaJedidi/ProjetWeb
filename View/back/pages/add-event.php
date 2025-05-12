<?php
$pdo = new PDO("mysql:host=localhost;dbname=oussema;charset=utf8","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png"/>
  <link rel="icon" type="image/png" href="../assets/img/favicon.png"/>
  <title>Localoo - Back Office Événements</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900"/>
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet"/>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet"/>
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded"/>
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet"/>
  <style>
    .sidenav .navbar-brand img{content:url('../assets/img/logolocaloo.png');}
    .btn-custom{background-color:#0ABAB5;border:none;color:#fff;}
    .btn-custom:hover{background-color:#00897B;color:#fff;}
    .form-text{color:red;font-size:0.875em;}
    .success-message{color:green;font-weight:bold;margin-top:10px;}
    .form-control{border:2px solid #ccc;}
    .form-control:focus{border-color:#0ABAB5;}
  </style>
  <style>
    .sidenav .navbar-brand img { content: url('../assets/img/logolocaloo.png'); }
    .nav-link.active, .nav-link.bg-gradient-dark { background: linear-gradient(195deg, #0ABAB5, #00897B) !important; }
    .table thead th { background: #0ABAB5; color: white; }
    .btn-custom { background-color: #0ABAB5; color: white; }
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
    .btn-purple {
      background-color: #0ABAB5;
      color: white;
      padding: 0.25rem 0.75rem;
      min-width: 100px;
    }
    .btn-purple:hover {
      background-color: #00897B;
      color: white;
    }
    .button-group {
      display: flex;
      gap: 15px; /* Increased spacing between buttons */
      align-items: center;
      justify-content: flex-end; /* Align buttons to the right */
      flex: 1; /* Take up remaining space */
    }
    .input-group.search-bar {
      max-width: 50%; /* Adjusted to balance space with buttons */
      flex: 0 0 50%; /* Ensure search bar takes fixed proportion */
    }
    .filter-row {
      display: flex;
      width: 100%;
      align-items: center;
      justify-content: space-between; /* Distribute space across the line */
    }
  </style>
</head>
<body>
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
          <a class="nav-link text-dark" href="AfficherCommentaires.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Reclamation</span>
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
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">
      <div class="card">
        <div class="card-header pb-0"><h6>Ajouter un Événement 🎉</h6></div>
        <div class="card-body px-4 pt-0 pb-4">
          <form id="eventForm" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
            <div class="col-md-6"><label for="eventTitle" class="form-label">Titre 📚</label><input type="text" class="form-control" id="eventTitle" name="eventTitle" required minlength="5"/><div class="form-text" id="eventTitleError"></div></div>
            <div class="col-md-6"><label for="eventLocation" class="form-label">Ville 🏙️</label><select class="form-select" id="eventLocation" name="eventLocation" required><option selected disabled value="">Choisir une ville...</option><option value="Tunis">Tunis</option><option value="Hammamet">Hammamet</option><option value="Djem">Djem</option><option value="Sousse">Sousse</option><option value="Djerba">Djerba</option></select><div class="form-text" id="eventLocationError"></div></div>
            <div class="col-md-3"><label for="startDate" class="form-label">Date début 📅</label><input type="date" class="form-control" id="startDate" name="startDate" required/><div class="form-text" id="startDateError"></div></div>
            <div class="col-md-3"><label for="endDate" class="form-label">Date fin 📅</label><input type="date" class="form-control" id="endDate" name="endDate" required/><div class="form-text" id="endDateError"></div></div>
            <div class="col-md-6"><label for="participants" class="form-label">Participants 👥</label><input type="number" class="form-control" id="participants" name="participants" required/><div class="form-text" id="participantsError"></div></div>
            <div class="col-md-6"><label for="eventStatus" class="form-label">Statut ⚡</label><select class="form-select" id="eventStatus" name="eventStatus" required><option selected disabled value="">Choisir...</option><option>Actif</option><option>En attente</option><option>Annulé</option><option>Dépassé</option></select><div class="form-text" id="eventStatusError"></div></div>
            <div class="col-md-6"><label for="eventImage" class="form-label">Image 📸</label><input type="file" class="form-control" id="eventImage" name="eventImage" accept="image/*"/><div class="form-text" id="eventImageError"></div></div>
            <div class="col-12"><label for="eventDescription" class="form-label">Description 📝</label><textarea class="form-control" id="eventDescription" name="eventDescription" rows="4"></textarea><div class="form-text" id="eventDescriptionError"></div></div>
            <div class="col-12 text-end"><button class="btn btn-custom mt-3" type="submit">Enregistrer ✅</button></div>
          </form>

          <form id="categoryForm" class="row g-3 needs-validation" novalidate style="display:none; margin-top:20px;">
            <div class="col-md-6">
              <label for="categorySelect" class="form-label">Catégorie 🏷️</label>
              <select class="form-select" id="categorySelect" name="existingCategory" required>
                <option selected disabled value="">Choisir une catégorie...</option>
                <?php
                  $cats = $pdo->query("SELECT id, nom FROM categorie ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
                  foreach($cats as $c) {
                    echo '<option value="'.$c['id'].'">'.htmlspecialchars($c['nom']).'</option>';
                  }
                ?>
                <option value="__new__">Nouvelle catégorie…</option>
              </select>
              <div class="form-text" id="categorySelectError"></div>
            </div>
            <div id="newCategoryDiv" class="col-md-6" style="display:none;">
              <label for="newCategoryName" class="form-label">Nouvelle catégorie 🆕</label>
              <input type="text" class="form-control" id="newCategoryName" name="name" minlength="2"/>
              <div class="form-text" id="newCategoryNameError"></div>
            </div>
            <div class="col-md-6">
              <label for="categoryIconFile" class="form-label">Icône catégorie 🖼️</label>
              <input type="file" class="form-control" id="categoryIconFile" name="icon" accept="image/*"/>
              <div class="form-text" id="categoryIconFileError"></div>
            </div>
            <div class="col-12 text-end"><button class="btn btn-custom mt-3" type="submit">Enregistrer Catégorie ✅</button></div>
            <div class="col-12"><p id="categorySuccess" class="success-message" style="display:none;">Catégorie et événement enregistrés avec succès !</p></div>
          </form>
        </div>
      </div>
    </div>
  </main>
  <script>
  let eventData, eventId;
document.getElementById('eventForm').addEventListener('submit', e => {
    e.preventDefault();
    let valid = true;
    document.querySelectorAll('#eventForm .form-text').forEach(el => el.textContent = '');
    if ( document.getElementById('eventTitle').value.trim().length < 5) {
        document.getElementById('eventTitleError').textContent = 'Le titre doit contenir au moins 5 caractères.';
        valid = false;
    }
    if (!document.getElementById('eventLocation').value) {
        document.getElementById('eventLocationError').textContent = 'Veuillez sélectionner une ville.';
        valid = false;
    }
    let s = new Date(document.getElementById('startDate').value),
        f = new Date(document.getElementById('endDate').value);
    if (isNaN(s) || isNaN(f) || s >= f) {
        document.getElementById('startDateError').textContent = 'Dates invalides';
        valid = false;
    }
    if (!valid) return;
    const formData = new FormData(document.getElementById('eventForm'));
    formData.append('action', 'insert');
    fetch('submit_event.php', { method: 'POST', body: formData })
        .then(r => r.json()).then(res => {
            if (res.success) {
                eventId = res.event_id;
                document.getElementById('eventForm').style.display = 'none';
                document.getElementById('categoryForm').style.display = 'flex';
            } else alert(res.message);
        });
});

const selectCat = document.getElementById('categorySelect');
const newCatDiv = document.getElementById('newCategoryDiv');
selectCat.addEventListener('change', () => {
    if (selectCat.value === '__new__') {
        newCatDiv.style.display = 'block';
        document.getElementById('newCategoryName').required = true;
    } else {
        newCatDiv.style.display = 'none';
        document.getElementById('newCategoryName').required = false;
    }
});

document.getElementById('categoryForm').addEventListener('submit', e => {
    e.preventDefault();
    let valid = true;
    ['categorySelect', 'newCategoryName', 'categoryIconFile'].forEach(id => {
        document.getElementById(id + 'Error').textContent = '';
    });
    const chosen = selectCat.value;
    if (!chosen) {
        document.getElementById('categorySelectError').textContent = 'Merci de choisir ou créer une catégorie.';
        valid = false;
    }
    const isNew = chosen === '__new__';
    const name = isNew ? document.getElementById('newCategoryName').value.trim() : null;
    if (isNew && (!name || name.length < 2)) {
        document.getElementById('newCategoryNameError').textContent = 'Nom trop court.';
        valid = false;
    }
    const iconInput = document.getElementById('categoryIconFile');
    if (iconInput.files.length > 0) {
        const file = iconInput.files[0];
        if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(file.type)) {
            document.getElementById('categoryIconFileError').textContent = 'Format non supporté.';
            valid = false;
        }
    }
    if (!valid) return;

    if (isNew) {
        const fd = new FormData();
        fd.append('action', 'insert_category');
        fd.append('name', name);
        if (iconInput.files[0]) fd.append('icon', iconInput.files[0]);
        fetch('submit_event.php', { method: 'POST', body: fd })
            .then(r => r.json()).then(res => {
                if (!res.success) return alert(res.message);
                finalizeCategory(res.category_id);
            });
    } else {
        finalizeCategory(chosen);
    }
});

function finalizeCategory(catId) {
    const fd2 = new FormData();
    fd2.append('action', 'update');
    fd2.append('event_id', eventId);
    fd2.append('categorie_id', catId);
    fetch('submit_event.php', { method: 'POST', body: fd2 })
        .then(r => r.json()).then(res2 => {
            if (res2.success) {
                document.getElementById('categorySuccess').style.display = 'block';
            } else {
                alert(res2.message);
            }
        });
}
</script>
</body>
</html>