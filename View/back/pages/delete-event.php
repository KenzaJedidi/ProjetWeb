<?php
$host = 'localhost';
$dbname = 'oussema';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        
        if($action === 'event' && !empty($_POST['id'])) {
            // Suppression de l'événement seulement
            $stmt = $pdo->prepare("DELETE FROM evenements WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            
            if ($stmt->rowCount() > 0) {
                header("Location: events.php?success=1");
            } else {
                header("Location: events.php?error=Événement introuvable");
            }
        }
        elseif($action === 'category' && !empty($_POST['categorie_id'])) {
            // Suppression de la catégorie seulement
            $pdo->beginTransaction();
            
            try {
                // 1. Désassocier les événements de la catégorie
                $stmtUpdate = $pdo->prepare("UPDATE evenements SET categorie_id = NULL WHERE categorie_id = ?");
                $stmtUpdate->execute([$_POST['categorie_id']]);
                
                // 2. Supprimer la catégorie
                $stmtCat = $pdo->prepare("DELETE FROM categorie WHERE id = ?");
                $stmtCat->execute([$_POST['categorie_id']]);
                
                $pdo->commit();
                header("Location: events.php?success=1");
            } catch(Exception $e) {
                $pdo->rollBack();
                header("Location: events.php?error=" . urlencode($e->getMessage()));
            }
        }
        else {
            header("Location: events.php?error=Action invalide");
        }
        exit;
    }

} catch (Exception $e) {
    header("Location: events.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>