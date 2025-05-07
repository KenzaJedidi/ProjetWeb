<?php
require_once 'config.php';
session_start();

// Vérifier si l'ID est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID de réservation manquant";
    header('Location: reservationetvoyage.php');
    exit;
}

$id = $_GET['id'];

try {
    // Préparer et exécuter la requête de suppression
    $stmt = config::getConnexion()->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    
    // Vérifier si une ligne a été affectée
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Réservation supprimée avec succès";
    } else {
        $_SESSION['error'] = "Aucune réservation trouvée avec cet ID";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de la suppression: " . $e->getMessage();
}

// Rediriger vers la page principale
header('Location: reservationetvoyage.php');
exit;
?>