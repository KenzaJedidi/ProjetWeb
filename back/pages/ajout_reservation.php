<?php
// Connexion à la base de données
require_once 'config.php';

// Nombre d'éléments par page
$items_par_page = 10;

// Page actuelle
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_par_page;

// Paramètres de recherche et tri
$recherche = isset($_GET['search']) ? trim($_GET['search']) : '';
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'date_depart';
$ordre = isset($_GET['ordre']) ? $_GET['ordre'] : 'DESC';

// Validation des paramètres de tri
$colonnes_tri = ['id', 'nom', 'prenom', 'destination_nom', 'date_depart', 'statut'];
$tri = in_array($tri, $colonnes_tri) ? $tri : 'date_depart';
$ordre = $ordre === 'ASC' ? 'ASC' : 'DESC';

try {
    // Requête pour compter le nombre total de réservations
    $sql_count = "SELECT COUNT(*) FROM reservations r 
                 JOIN destinations d ON r.destination_id = d.id";
    
    if ($recherche) {
        $sql_count .= " WHERE nom LIKE :recherche OR prenom LIKE :recherche OR d.nom LIKE :recherche";
    }

    $stmt_count = config::getConnexion()->prepare($sql_count);
    
    if ($recherche) {
        $stmt_count->bindValue(':recherche', "%$recherche%");
    }
    
    $stmt_count->execute();
    $total_reservations = $stmt_count->fetchColumn();
    $pages_totales = ceil($total_reservations / $items_par_page);

    // Requête pour récupérer les réservations
    $sql = "SELECT r.*, d.nom AS destination_nom 
           FROM reservations r
           JOIN destinations d ON r.destination_id = d.id";
    
    if ($recherche) {
        $sql .= " WHERE r.nom LIKE :recherche OR r.prenom LIKE :recherche OR d.nom LIKE :recherche";
    }
    
    $sql .= " ORDER BY $tri $ordre LIMIT :limit OFFSET :offset";

    $stmt = config::getConnexion()->prepare($sql);
    
    if ($recherche) {
        $stmt->bindValue(':recherche', "%$recherche%");
    }
    
    $stmt->bindValue(':limit', $items_par_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .pagination {
            justify-content: center;
        }
        .badge-active {
            background-color: #28a745;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-cancelled {
            background-color: #dc3545;
        }
        .search-box {
            max-width: 400px;
            margin-bottom: 20px;
        }
        .sortable {
            cursor: pointer;
        }
        .sortable:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Liste des Réservations</h1>
        
        <!-- Formulaire de recherche -->
        <form method="GET" class="search-box">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($recherche) ?>">
                <button class="btn btn-primary" type="submit">Rechercher</button>
                <?php if ($recherche): ?>
                    <a href="?" class="btn btn-outline-secondary">Effacer</a>
                <?php endif; ?>
            </div>
        </form>
        
        <!-- Affichage du nombre de résultats -->
        <div class="mb-3">
            <?php if ($recherche): ?>
                <p><?= $total_reservations ?> résultat(s) trouvé(s) pour "<?= htmlspecialchars($recherche) ?>"</p>
            <?php else: ?>
                <p>Total des réservations : <?= $total_reservations ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Tableau des réservations -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="sortable" onclick="sortTable('id')">ID <?= ($tri === 'id' ? ($ordre === 'ASC' ? '↑' : '↓') : '') ?></th>
                        <th class="sortable" onclick="sortTable('nom')">Nom <?= ($tri === 'nom' ? ($ordre === 'ASC' ? '↑' : '↓') : '') ?></th>
                        <th class="sortable" onclick="sortTable('prenom')">Prénom <?= ($tri === 'prenom' ? ($ordre === 'ASC' ? '↑' : '↓') : '') ?></th>
                        <th class="sortable" onclick="sortTable('destination_nom')">Destination <?= ($tri === 'destination_nom' ? ($ordre === 'ASC' ? '↑' : '↓') : '') ?></th>
                        <th class="sortable" onclick="sortTable('date_depart')">Date <?= ($tri === 'date_depart' ? ($ordre === 'ASC' ? '↑' : '↓') : '') ?></th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">Aucune réservation trouvée</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?= htmlspecialchars($reservation['id']) ?></td>
                                <td><?= htmlspecialchars($reservation['nom']) ?></td>
                                <td><?= htmlspecialchars($reservation['prenom']) ?></td>
                                <td><?= htmlspecialchars($reservation['destination_nom']) ?></td>
                                <td><?= date('d/m/Y', strtotime($reservation['date_depart'])) ?></td>
                                <td>
                                    <?php
                                    $badge_class = match($reservation['statut']) {
                                        'active' => 'badge-active',
                                        'en_attente' => 'badge-pending',
                                        'annulee' => 'badge-cancelled',
                                        default => 'badge-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $badge_class ?>">
                                        <?= ucfirst(str_replace('_', ' ', $reservation['statut'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_reservation.php?id=<?= $reservation['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                                    <a href="delete_reservation.php?id=<?= $reservation['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">
                                        Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($pages_totales > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=1&search=<?= urlencode($recherche) ?>&tri=<?= $tri ?>&ordre=<?= $ordre ?>">Première</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page-1 ?>&search=<?= urlencode($recherche) ?>&tri=<?= $tri ?>&ordre=<?= $ordre ?>">Précédent</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php 
                    $start = max(1, $page - 2);
                    $end = min($pages_totales, $page + 2);
                    
                    if ($start > 1) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    
                    for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($recherche) ?>&tri=<?= $tri ?>&ordre=<?= $ordre ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; 
                    
                    if ($end < $pages_totales) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    ?>
                    
                    <?php if ($page < $pages_totales): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page+1 ?>&search=<?= urlencode($recherche) ?>&tri=<?= $tri ?>&ordre=<?= $ordre ?>">Suivant</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pages_totales ?>&search=<?= urlencode($recherche) ?>&tri=<?= $tri ?>&ordre=<?= $ordre ?>">Dernière</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <script>
        function sortTable(column) {
            const urlParams = new URLSearchParams(window.location.search);
            let order = 'ASC';
            
            if (urlParams.get('tri') === column && urlParams.get('ordre') === 'ASC') {
                order = 'DESC';
            }
            
            urlParams.set('tri', column);
            urlParams.set('ordre', order);
            window.location.search = urlParams.toString();
        }
    </script>
</body>
</html>