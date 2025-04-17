<?php
// Connexion à la base
$host = 'localhost';
$dbname = 'localoo';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}

// Récupérer tous les événements
$query = "SELECT * FROM evenements";
$stmt = $pdo->query($query);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Affichage des événements dans le tableau
if (count($events) > 0) {
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Titre</th>';
    echo '<th>Lieu</th>';
    echo '<th>Date Début</th>';
    echo '<th>Date Fin</th>';
    echo '<th>Type</th>';
    echo '<th>Participants</th>';
    echo '<th>Statut</th>';
    echo '<th>Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($events as $event) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($event['titre']) . '</td>';
        echo '<td>' . htmlspecialchars($event['lieu']) . '</td>';
        echo '<td>' . htmlspecialchars($event['date_debut']) . '</td>';
        echo '<td>' . htmlspecialchars($event['date_fin']) . '</td>';
        echo '<td>' . htmlspecialchars($event['type']) . '</td>';
        echo '<td>' . htmlspecialchars($event['participants']) . '</td>';
        echo '<td>' . htmlspecialchars($event['statut']) . '</td>';
        echo '<td><button class="btn btn-warning btn-sm">Modifier</button> <button class="btn btn-danger btn-sm">Supprimer</button></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>Aucun événement trouvé.</p>';
}
?>
