<?php
require_once 'config.php';

// Récupérer les destinations depuis la base de données pour le select
$destinations = [];
try {
    $stmt = config::getConnexion()->query("SELECT id, nom FROM destinations");
    $destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des destinations: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Réservation - Localoo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            background-color: #0ABAB5;
            border: none;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #00897B;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Ajouter une nouvelle réservation</h3>
                    </div>
                    <div class="card-body">
                        <form action="process-reservation.php" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="destination" class="form-label">Destination</label>
                                <select class="form-select" id="destination" name="destination_id" required>
                                    <option value="">Sélectionnez une destination</option>
                                    <?php foreach ($destinations as $destination): ?>
                                        <option value="<?= $destination['id'] ?>"><?= htmlspecialchars($destination['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_depart" class="form-label">Date de départ</label>
                                    <input type="date" class="form-control" id="date_depart" name="date_depart" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="date_retour" class="form-label">Date de retour</label>
                                    <input type="date" class="form-control" id="date_retour" name="date_retour">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nombre_personnes" class="form-label">Nombre de personnes</label>
                                <input type="number" class="form-control" id="nombre_personnes" name="nombre_personnes" min="1" value="1" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="commentaires" class="form-label">Commentaires</label>
                                <textarea class="form-control" id="commentaires" name="commentaires" rows="3"></textarea>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-custom">Enregistrer la réservation</button>
                                <a href="reservationetvoyage.html" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation des dates
        document.getElementById('date_depart').addEventListener('change', function() {
            const dateRetour = document.getElementById('date_retour');
            if (dateRetour.value && new Date(dateRetour.value) < new Date(this.value)) {
                alert('La date de retour doit être après la date de départ');
                dateRetour.value = '';
            }
            dateRetour.min = this.value;
        });
    </script>
</body>
</html>