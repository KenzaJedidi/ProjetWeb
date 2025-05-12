<?php
$host = 'localhost';
$dbname = 'oussema';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $search_term = isset($_POST['search']) ? $_POST['search'] : '';

    $query = "SELECT e.*, c.nom AS categorie_name 
              FROM evenements e 
              LEFT JOIN categorie c ON e.categorie_id = c.id 
              WHERE e.titre LIKE :search_term";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['search_term' => '%'.$search_term.'%']);
    $filtered_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($filtered_events as $event) {
        // Déterminer la classe du statut comme dans events.php
        $status_class = match($event['statut']) {
            'Actif' => 'bg-gradient-success',
            'Annulé' => 'bg-gradient-danger',
            'Dépassé' => 'bg-gradient-secondary',
            'En attente' => 'bg-gradient-warning',
            default => 'bg-gradient-secondary'
        };
        
        echo "<tr>";
        echo "<td class='ps-4'><p class='text-xs font-weight-bold mb-0'>" . $event['id'] . "</p></td>";
        echo "<td><div class='d-flex px-2 py-1'><div class='d-flex flex-column justify-content-center'><h6 class='mb-0 text-sm'>" . htmlspecialchars($event['titre']) . "</h6></div></div></td>";
        echo "<td><p class='text-xs mb-0'>" . htmlspecialchars($event['ville']) . "</p></td>";
        echo "<td><p class='text-xs mb-0'>" . $event['date_debut'] . " - " . $event['date_fin'] . "</p></td>";
        echo "<td class='text-center'><span class='badge badge-event'>" . htmlspecialchars($event['categorie_name']) . "</span></td>";
        echo "<td class='text-center'><span class='text-xs font-weight-bold'>" . number_format($event['participants']) . "</span></td>";
        echo "<td class='text-center'><span class='badge badge-sm $status_class'>" . $event['statut'] . "</span></td>";
        echo "<td class='text-center'>";
        echo "<a href='edit_event.php?id={$event['id']}' class='btn btn-custom btn-sm'>Modifier</a>";
        echo "<form method='POST' action='delete_event.php' class='d-inline'>";
        echo "<input type='hidden' name='id' value='{$event['id']}'>";
        echo "<button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Confirmer suppression ?\")'>Supprimer</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }

} catch(PDOException $e) {
    echo "<tr><td colspan='8'>Erreur : " . $e->getMessage() . "</td></tr>";
}
?>