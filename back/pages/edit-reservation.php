<?php
require_once 'config.php';
session_start();

// Vérifier si l'ID est présent
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID de réservation manquant";
    header('Location: reservationetvoyage.php');
    exit;
}

$id = $_GET['id'];

// Récupérer les données de la réservation
try {
    $stmt = config::getConnexion()->prepare("
        SELECT r.*, d.nom AS destination_nom 
        FROM reservations r
        JOIN destinations d ON r.destination_id = d.id
        WHERE r.id = ?
    ");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        $_SESSION['error'] = "Réservation non trouvée";
        header('Location: reservationetvoyage.php');
        exit;
    }

    // Récupérer la liste des destinations pour le select
    $destinations = config::getConnexion()->query("SELECT id, nom FROM destinations")->fetchAll();

} catch (PDOException $e) {
    die("Erreur: " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $destination_id = $_POST['destination_id'] ?? '';
    $date_depart = $_POST['date_depart'] ?? '';
    $statut = $_POST['statut'] ?? '';

    try {
        $stmt = config::getConnexion()->prepare("
            UPDATE reservations 
            SET nom = ?, prenom = ?, destination_id = ?, date_depart = ?, statut = ?
            WHERE id = ?
        ");
        $stmt->execute([$nom, $prenom, $destination_id, $date_depart, $statut, $id]);

        $_SESSION['success'] = "Réservation mise à jour avec succès";
        header('Location: reservationetvoyage.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la mise à jour: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Réservation</title>
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <style>
        .form-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="g-sidenav-show bg-gray-100">
    <div class="container mt-5">
        <div class="form-container">
            <h2 class="mb-4">Modifier la Réservation #<?= htmlspecialchars($reservation['id']) ?></h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?= htmlspecialchars($reservation['nom']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" 
                           value="<?= htmlspecialchars($reservation['prenom']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="destination_id" class="form-label">Destination</label>
                    <select class="form-control" id="destination_id" name="destination_id" required>
                        <?php foreach ($destinations as $destination): ?>
                            <option value="<?= $destination['id'] ?>" 
                                <?= $destination['id'] == $reservation['destination_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($destination['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="date_depart" class="form-label">Date de départ</label>
                    <input type="date" class="form-control" id="date_depart" name="date_depart" 
                           value="<?= htmlspecialchars($reservation['date_depart']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select class="form-control" id="statut" name="statut" required>
                        <option value="active" <?= $reservation['statut'] == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="en_attente" <?= $reservation['statut'] == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                        <option value="annulee" <?= $reservation['statut'] == 'annulee' ? 'selected' : '' ?>>Annulée</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="reservationetvoyage.php" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</body>
</html>