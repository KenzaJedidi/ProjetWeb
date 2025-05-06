<?php
include_once '../../../controllers/OffreEmploiController.php';
include_once '../../../models/OffreEmploi.php';

$controller = new OffreEmploiController();
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {
      $action = $_POST['action'];

      if ($action === 'add' || $action === 'edit') {
          if (
              isset($_POST['titre'], $_POST['description'], $_POST['type_contrat']) &&
              !empty($_POST['titre']) && !empty($_POST['description']) && !empty($_POST['type_contrat'])
          ) {
              $titre = trim($_POST['titre']);
              $description = trim($_POST['description']);
              $type_contrat = trim($_POST['type_contrat']);
              $salaire = !empty($_POST['salaire']) ? trim($_POST['salaire']) : null;
              $localisation = !empty($_POST['localisation']) ? trim($_POST['localisation']) : null;
              $competences = !empty($_POST['competences']) ? trim($_POST['competences']) : null;
              $status = !empty($_POST['status']) ? trim($_POST['status']) : 'Active';

              $offre = new OffreEmploi($titre, $description, $type_contrat, $salaire, $localisation, $competences, $status);

              if ($action === 'add') {
                  $controller->ajouter($offre);
                  $success = "Offre ajoutée avec succès.";
              } elseif ($action === 'edit' && isset($_POST['id']) && !empty($_POST['id'])) {
                  $id = (int)$_POST['id'];
                  if ($controller->modifier($offre, $id)) {
                      $success = "Offre modifiée avec succès.";
                  } else {
                      $error = "Aucune offre trouvée pour cet ID.";
                  }
              }
          } else {
              $error = "Les champs Poste, Description et Type de contrat sont obligatoires.";
          }
      } elseif ($action === 'delete' && isset($_POST['id']) && !empty($_POST['id'])) {
          $id = (int)$_POST['id'];
          if ($controller->supprimer($id)) {
              $success = "Offre supprimée avec succès.";
          } else {
              $error = "Aucune offre trouvée pour cet ID.";
          }
      }
  }
}

// Charger les offres
$offres = $controller->afficher();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Localoo - Back Office Emploi
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
    .badge-emploi {
      background-color: #0ABAB5;
      color: white;
    }
    .error-message {
      color: red;
      font-size: 0.9em;
      margin-bottom: 10px;
    }
    .success-message {
      color: green;
      font-size: 0.9em;
      margin-bottom: 10px;
    }
    .is-invalid {
      border-color: #dc3545 !important;
    }
    .invalid-feedback {
      color: #dc3545;
      font-size: 0.875em;
      display: none;
    }
    .was-validated .form-control:invalid ~ .invalid-feedback,
    .form-control.is-invalid ~ .invalid-feedback {
      display: block;
    }
    .status-select {
      width: 120px;
    }
    .badge-status {
      padding: 0.5em 1em;
      border-radius: 0.25rem;
    }
    .badge-status.en-attente {
      background-color: #ffc107;
      color: #000;
    }
    .badge-status.en-cours {
      background-color: #17a2b8;
      color: #fff;
    }
    .badge-status.accepté {
      background-color: #28a745;
      color: #fff;
    }
    .badge-status.rejeté {
      background-color: #dc3545;
      color: #fff;
    }
    /* Style pour le badge de notification */
    #notification-count {
      position: absolute;
      top: -5px;
      right: -5px;
      font-size: 0.75rem;
      padding: 2px 6px;
      display: none;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <!-- Sidenav -->
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
         aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="dashboard.php">
        <img src="../assets/img/logolocaloo.png" class="navbar-brand-img" width="250" height="250" alt="Logo Localoo">
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/events.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Events</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/emploi.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Emploi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/virtual-reality.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Review</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/rtl.php">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text ms-1">Reclamation</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/notifications.php">
            <i class="material-symbols-rounded opacity-5">notifications</i>
            <span class="nav-link-text ms-1">forum</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/profile.php">
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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="../pages/dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Gestion des Offres d'Emploi</li>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Rechercher une offre...</label>
              <input type="text" class="form-control" id="search-offre">
            </div>
          </div>
          <ul class="navbar-nav d-flex align-items-center justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <button class="btn btn-custom btn-sm mb-0 me-3" data-bs-toggle="modal" data-bs-target="#offreModal">Ajouter une Offre</button>
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
                <span class="badge badge-sm bg-gradient-primary" id="notification-count"></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4 ms-n5" aria-labelledby="dropdownMenuButton" id="notification-list">
                <!-- Les notifications seront chargées ici -->
              </ul>
            </li>
            <li class="nav-item d-flex align-items-center">
              <a href="../pages/sign-in.php" class="nav-link text-body p-0">
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
      <!-- Messages -->
      <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($success); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($error); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Titre de la page -->
      <div class="row mb-4">
        <div class="col-12">
          <h3 class="mb-0 h4 font-weight-bolder">Gestion des Offres d'Emploi</h3>
          <p class="mb-0">Ajouter, modifier ou supprimer des offres d'emploi.</p>
        </div>
      </div>

      <!-- Listing des Offres d'Emploi -->
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Liste des Offres d'Emploi</h6>
              <button class="btn btn-custom btn-sm" data-bs-toggle="modal" data-bs-target="#offreModal">Ajouter une Offre</button>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Poste</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Localisation</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Salaire</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Compétences</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="offres-table-body">
                    <?php foreach ($offres as $offre): ?>
                      <tr>
                        <td><p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($offre->getId()); ?></p></td>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($offre->getTitre()); ?></h6>
                            </div>
                          </div>
                        </td>
                        <td><p class="text-xs mb-0"><?php echo htmlspecialchars($offre->getDescription()); ?></p></td>
                        <td><p class="text-xs mb-0"><?php echo htmlspecialchars($offre->getLocalisation() ?: '-'); ?></p></td>
                        <td class="align-middle text-center">
                          <span class="badge badge-emploi"><?php echo htmlspecialchars($offre->getTypeContrat()); ?></span>
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-xs font-weight-bold"><?php echo htmlspecialchars($offre->getSalaire() ?: '-'); ?></span>
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-xs"><?php echo htmlspecialchars($offre->getCompetences() ?: '-'); ?></span>
                        </td>
                        <td class="align-middle text-center">
                          <span class="badge badge-sm <?php echo $offre->getStatus() === 'Active' ? 'bg-gradient-success' : 'bg-gradient-warning'; ?>">
                            <?php echo htmlspecialchars($offre->getStatus()); ?>
                          </span>
                        </td>
                        <td class="align-middle text-center">
                          <button class="btn btn-custom btn-sm" data-bs-toggle="modal" data-bs-target="#offreModal"
                           onclick="fillEditForm(<?php echo $offre->getId(); ?>, '<?php echo addslashes($offre->getTitre()); ?>', '<?php echo addslashes($offre->getDescription()); ?>', '<?php echo addslashes($offre->getTypeContrat()); ?>', '<?php echo addslashes($offre->getSalaire()); ?>', '<?php echo addslashes($offre->getLocalisation()); ?>', '<?php echo addslashes($offre->getCompetences()); ?>', '<?php echo addslashes($offre->getStatus()); ?>')">Modifier</button>
                          <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                            onclick="setDeleteId(<?php echo $offre->getId(); ?>)">Supprimer</button>
                          <button class="btn btn-info btn-sm view-candidates" 
                                  data-offre-id="<?php echo $offre->getId(); ?>"
                                  data-offre-titre="<?php echo htmlspecialchars($offre->getTitre()); ?>">
                              <i class="material-symbols-rounded">people</i> Candidats
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    <?php if (empty($offres)): ?>
                      <tr>
                        <td colspan="9" class="text-center">Aucune offre d'emploi trouvée.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Fin Listing des Offres d'Emploi -->
    </div>
    <!-- Fin Main Container -->

    <!-- Modal pour Ajouter/Modifier une Offre -->
    <div class="modal fade" id="offreModal" tabindex="-1" aria-labelledby="offreModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="offreModalLabel">Ajouter une Offre d'Emploi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php if (!empty($error)): ?>
              <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form id="offreForm" method="POST" class="needs-validation" novalidate>
              <input type="hidden" id="offre-id" name="id">
              <input type="hidden" id="offre-action" name="action" value="add">
              
              <div class="mb-3">
                <label for="titre" class="form-label">Poste <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="titre" name="titre" required maxlength="100" pattern="[A-Za-zÀ-ÿ0-9\s\-]+" oninput="validateInput(this)">
                <div class="invalid-feedback">
                  Veuillez saisir un titre valide (100 caractères max, pas de caractères spéciaux).
                </div>
              </div>
              
              <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="4" required minlength="20" maxlength="2000" oninput="validateInput(this)"></textarea>
                <div class="invalid-feedback">
                  La description doit contenir entre 20 et 2000 caractères.
                </div>
              </div>
              
              <div class="mb-3">
                <label for="type_contrat" class="form-label">Type de contrat <span class="text-danger">*</span></label>
                <select class="form-control" id="type_contrat" name="type_contrat" required>
                  <option value="">Sélectionnez un type</option>
                  <option value="CDI">CDI</option>
                  <option value="CDD">CDD</option>
                  <option value="Saisonnier">Saisonnier</option>
                  <option value="Freelance">Freelance</option>
                </select>
                <div class="invalid-feedback">
                  Veuillez sélectionner un type de contrat.
                </div>
              </div>
              
              <div class="mb-3">
                <label for="salaire" class="form-label">Salaire</label>
                <input type="text" class="form-control" id="salaire" name="salaire" placeholder="Ex: 1800-2200 DT" 
                       pattern="[0-9\-\s]+" oninput="validateSalary(this)">
                <div class="invalid-feedback">
                  Format invalide. Exemples valides: "1800-2200 DT" ou "2500".
                </div>
              </div>
              
              <div class="mb-3">
                <label for="localisation" class="form-label">Localisation</label>
                <input type="text" class="form-control" id="localisation" name="localisation" maxlength="100" 
                       pattern="[A-Za-zÀ-ÿ\s\-,]+" oninput="validateInput(this)">
                <div class="invalid-feedback">
                  Veuillez saisir une localisation valide (100 caractères max).
                </div>
              </div>
              
              <div class="mb-3">
                <label for="competences" class="form-label">Compétences</label>
                <input type="text" class="form-control" id="competences" name="competences" 
                       placeholder="Ex: PHP, JavaScript, MySQL" maxlength="255" 
                       pattern="[A-Za-zÀ-ÿ0-9\s\-,]+" oninput="validateInput(this)">
                <div class="invalid-feedback">
                  Veuillez saisir des compétences valides (255 caractères max).
                </div>
              </div>
              
              <div class="mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-control" id="status" name="status">
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>
              
              <button type="submit" class="btn btn-custom">Enregistrer</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Confirmation de Suppression -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la Suppression</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Êtes-vous sûr de vouloir supprimer cette offre d'emploi ?
          </div>
          <div class="modal-footer">
            <form id="deleteForm" method="POST">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" id="delete-id" name="id">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal pour afficher les candidats -->
    <div class="modal fade" id="candidatesModal" tabindex="-1" aria-labelledby="candidatesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="candidatesModalLabel">Candidats pour l'offre: <span id="offreTitre"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom Complet</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Téléphone</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                  </tr>
                </thead>
                <tbody id="candidatesList">
                  <!-- Les candidats seront chargés ici -->
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal pour afficher les détails d'un candidat -->
    <div class="modal fade" id="candidateDetailsModal" tabindex="-1" aria-labelledby="candidateDetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="candidateDetailsModalLabel">Détails du Candidat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>ID:</strong> <span id="detail-id"></span></p>
            <p><strong>Nom Complet:</strong> <span id="detail-nom"></span></p>
            <p><strong>Email:</strong> <span id="detail-email"></span></p>
            <p><strong>Téléphone:</strong> <span id="detail-telephone"></span></p>
            <p><strong>Date de Postulation:</strong> <span id="detail-date"></span></p>
            <p><strong>Statut:</strong> <span id="detail-status"></span></p>
            <p><strong>CV:</strong> <a id="detail-cv" href="#" target="_blank">Voir CV</a></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer py-4">
      <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="copyright text-center text-sm text-muted text-lg-start">
              © <script>document.write(new Date().getFullYear())</script>, made with <i class="fa fa-heart"></i> by
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

  <!-- Core JS Files -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
  <script>
    // Initialiser la barre de défilement si sur Windows
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }

    // Rechercher dans les offres
    document.getElementById('search-offre').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const rows = document.querySelectorAll('#offres-table-body tr');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });

    // Remplir le formulaire pour modifier une offre
    function fillEditForm(id, titre, description, typeContrat, salaire, localisation, competences, status) {
      document.getElementById('offre-id').value = id;
      document.getElementById('offre-action').value = 'edit';
      document.getElementById('titre').value = titre;
      document.getElementById('description').value = description;
      document.getElementById('type_contrat').value = typeContrat;
      document.getElementById('salaire').value = salaire;
      document.getElementById('localisation').value = localisation;
      document.getElementById('competences').value = competences;
      document.getElementById('status').value = status;
      
      // Change the modal title
      document.getElementById('offreModalLabel').innerText = 'Modifier une Offre d\'Emploi';
    }

    function setDeleteId(id) {
      document.getElementById('delete-id').value = id;
    }

    // Validation du formulaire
    (function() {
      'use strict';
      
      // Récupérer le formulaire
      const form = document.getElementById('offreForm');
      
      // Empêcher la soumission si invalide
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        
        form.classList.add('was-validated');
      }, false);
    })();
    
    // Fonction de validation générique
    function validateInput(input) {
      const pattern = new RegExp(input.pattern);
      const isValid = pattern.test(input.value) && 
                     (input.maxLength === -1 || input.value.length <= input.maxLength) &&
                     (input.minLength === -1 || input.value.length >= input.minLength);
      
      if (isValid) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
      } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
      }
    }
    
    // Validation spécifique pour le salaire
    function validateSalary(input) {
      const salaryPattern = /^[0-9]+(\s*-\s*[0-9]+)?(\s*[A-Za-z]+)?$/;
      const isValid = salaryPattern.test(input.value) || input.value === '';
      
      if (isValid) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
      } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
      }
    }
    
    // Réinitialiser le formulaire quand le modal est fermé
    document.getElementById('offreModal').addEventListener('hidden.bs.modal', function() {
      const form = document.getElementById('offreForm');
      form.reset();
      form.classList.remove('was-validated');
      
      // Réinitialiser les classes de validation
      const inputs = form.querySelectorAll('.form-control');
      inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
      });
      
      // Réinitialiser le titre du modal
      document.getElementById('offreModalLabel').innerText = 'Ajouter une Offre d\'Emploi';
      document.getElementById('offre-action').value = 'add';
    });

    // Charger les notifications basées sur les candidatures récentes
    function loadNotifications() {
      fetch('../../../controllers/CandidatureController.php?action=getRecentCandidatures')
        .then(response => response.json())
        .then(data => {
          const notificationList = document.getElementById('notification-list');
          const notificationCount = document.getElementById('notification-count');
          notificationList.innerHTML = '';

          if (data.success && data.candidatures.length > 0) {
            notificationCount.textContent = data.candidatures.length;
            notificationCount.style.display = 'inline-block';
            
            data.candidatures.forEach(candidature => {
              const li = document.createElement('li');
              li.className = 'mb-2';
              li.innerHTML = `
                <a class="dropdown-item border-radius-md" href="javascript:;" onclick="viewCandidature(${candidature.id}, '${candidature.nom_complet}')">
                  Nouvelle candidature de ${candidature.nom_complet} pour ${candidature.poste}
                  <br><small>${candidature.date_postulation}</small>
                </a>
              `;
              notificationList.appendChild(li);
            });
          } else {
            notificationList.innerHTML = '<li class="mb-2"><a class="dropdown-item border-radius-md" href="javascript:;">Aucune notification</a></li>';
            notificationCount.style.display = 'none';
          }
        })
        .catch(error => {
          console.error('Erreur lors du chargement des notifications:', error);
          notificationList.innerHTML = '<li class="mb-2"><a class="dropdown-item border-radius-md" href="javascript:;">Erreur de chargement</a></li>';
        });
    }

    // Fonction pour voir une candidature (ouvre le modal des candidats)
    function viewCandidature(candidatureId, nomComplet) {
      fetch(`../../../controllers/CandidatureController.php?action=getCandidature&id=${candidatureId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success && data.candidature) {
            const candidature = data.candidature;
            document.getElementById('offreTitre').textContent = candidature.poste;
            const candidatesList = document.getElementById('candidatesList');
            candidatesList.innerHTML = `
              <tr>
                <td><p class="text-xs font-weight-bold mb-0">${candidature.id}</p></td>
                <td><p class="text-xs mb-0">${candidature.nom_complet}</p></td>
                <td><p class="text-xs mb-0">${candidature.email}</p></td>
                <td><p class="text-xs mb-0">${candidature.telephone || '-'}</p></td>
                <td><p class="text-xs mb-0">${candidature.date_postulation}</p></td>
                <td class="align-middle text-center">
                  <span class="badge badge-status ${candidature.status.toLowerCase().replace('é', 'e').replace(' ', '-')}">${candidature.status}</span>
                </td>
                <td class="align-middle text-center">
                  <a href="../../../Uploads/cvs/${candidature.cv_path}" target="_blank" class="btn btn-custom btn-sm">Voir CV</a>
                  <button class="btn btn-info btn-sm view-candidature" data-candidature='${JSON.stringify(candidature)}'>
                    <i class="material-symbols-rounded">visibility</i>
                  </button>
                  <button class="btn btn-success btn-sm accept-candidature" data-candidature-id="${candidature.id}">
                    <i class="material-symbols-rounded">check_circle</i>
                  </button>
                  <button class="btn btn-danger btn-sm reject-candidature" data-candidature-id="${candidature.id}">
                    <i class="material-symbols-rounded">cancel</i>
                  </button>
                </td>
              </tr>
            `;
            
            const candidatesModal = new bootstrap.Modal(document.getElementById('candidatesModal'), {
              keyboard: false
            });
            candidatesModal.show();
          }
        });
    }

    // Afficher les candidats pour une offre
    document.addEventListener('click', function(event) {
      const candidatesButton = event.target.closest('.view-candidates');
      if (candidatesButton) {
        const offreId = candidatesButton.getAttribute('data-offre-id');
        const offreTitre = candidatesButton.getAttribute('data-offre-titre');
        const candidatesList = document.getElementById('candidatesList');
        const offreTitreSpan = document.getElementById('offreTitre');

        // Mettre à jour le titre du modal
        offreTitreSpan.textContent = offreTitre;

        // Vider la liste des candidats
        candidatesList.innerHTML = '';

        // Afficher le modal
        const candidatesModal = new bootstrap.Modal(document.getElementById('candidatesModal'), {
          keyboard: false
        });
        candidatesModal.show();

        // Appeler l'API pour récupérer les candidats
        fetch(`../../../controllers/CandidatureController.php?action=getCandidaturesByOffre&offre_id=${offreId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success && data.candidatures.length > 0) {
              data.candidatures.forEach(candidature => {
                const statusClass = candidature.status.toLowerCase().replace('é', 'e').replace(' ', '-');
                const row = document.createElement('tr');
                row.innerHTML = `
                  <td><p class="text-xs font-weight-bold mb-0">${candidature.id}</p></td>
                  <td><p class="text-xs mb-0">${candidature.nom_complet}</p></td>
                  <td><p class="text-xs mb-0">${candidature.email}</p></td>
                  <td><p class="text-xs mb-0">${candidature.telephone || '-'}</p></td>
                  <td><p class="text-xs mb-0">${candidature.date_postulation}</p></td>
                  <td class="align-middle text-center">
                    <span class="badge badge-status ${statusClass}">${candidature.status}</span>
                  </td>
                  <td class="align-middle text-center">
                    <a href="../../../Uploads/cvs/${candidature.cv_path}" target="_blank" class="btn btn-custom btn-sm">Voir CV</a>
                    <button class="btn btn-info btn-sm view-candidature" 
                            data-candidature='${JSON.stringify(candidature)}'>
                      <i class="material-symbols-rounded">visibility</i>
                    </button>
                    <button class="btn btn-success btn-sm accept-candidature" 
                            data-candidature-id="${candidature.id}">
                      <i class="material-symbols-rounded">check_circle</i>
                    </button>
                    <button class="btn btn-danger btn-sm reject-candidature" 
                            data-candidature-id="${candidature.id}">
                      <i class="material-symbols-rounded">cancel</i>
                    </button>
                  </td>
                `;
                candidatesList.appendChild(row);
              });

              // Gérer le bouton "Voir" pour afficher les détails
              document.querySelectorAll('.view-candidature').forEach(button => {
                button.addEventListener('click', function() {
                  const candidature = JSON.parse(this.getAttribute('data-candidature'));
                  document.getElementById('detail-id').textContent = candidature.id;
                  document.getElementById('detail-nom').textContent = candidature.nom_complet;
                  document.getElementById('detail-email').textContent = candidature.email;
                  document.getElementById('detail-telephone').textContent = candidature.telephone || '-';
                  document.getElementById('detail-date').textContent = candidature.date_postulation;
                  document.getElementById('detail-status').textContent = candidature.status;
                  document.getElementById('detail-cv').setAttribute('href', `../../../Uploads/cvs/${candidature.cv_path}`);

                  const detailsModal = new bootstrap.Modal(document.getElementById('candidateDetailsModal'), {
                    keyboard: false
                  });
                  detailsModal.show();
                });
              });

              // Gérer les boutons "Accepter" et "Rejeter"
              document.querySelectorAll('.accept-candidature, .reject-candidature').forEach(button => {
                button.addEventListener('click', function() {
                  const candidatureId = this.getAttribute('data-candidature-id');
                  const newStatus = this.classList.contains('accept-candidature') ? 'Accepté' : 'Rejeté';

                  fetch('../../../controllers/CandidatureController.php', {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=updateStatus&candidature_id=${candidatureId}&status=${encodeURIComponent(newStatus)}`
                  })
                    .then(response => response.json())
                    .then(data => {
                      if (data.success) {
                        const row = this.closest('tr');
                        const statusCell = row.querySelector('.badge-status');
                        const statusClass = newStatus.toLowerCase().replace('é', 'e').replace(' ', '-');
                        statusCell.className = `badge badge-status ${statusClass}`;
                        statusCell.textContent = newStatus;
                        alert(`Candidature ${newStatus.toLowerCase()} avec succès`);
                        // Recharger les notifications après mise à jour du statut
                        loadNotifications();
                      } else {
                        alert('Erreur : ' + data.message);
                      }
                    })
                    .catch(error => {
                      alert('Erreur lors de la mise à jour du statut : ' + error);
                    });
                });
              });
            } else {
              candidatesList.innerHTML = '<tr><td colspan="7" class="text-center">Aucune candidature trouvée pour cette offre.</td></tr>';
            }
          })
          .catch(error => {
            candidatesList.innerHTML = '<tr><td colspan="7" class="text-center">Erreur lors du chargement des candidats.</td></tr>';
          });
      }
    });

    // Charger les notifications au chargement de la page
    document.addEventListener('DOMContentLoaded', loadNotifications);

    
  </script>
  <script src="../assets/js/notifications.js"></script>
  <script src="../assets/js/notification-handlers.js"></script>
</body>
</html>