<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add-reservation.php');
    exit;
}

// Récupération et validation des données
$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$destination_id = intval($_POST['destination_id'] ?? 0);
$date_depart = $_POST['date_depart'] ?? '';
$date_retour = $_POST['date_retour'] ?? null;
$nombre_personnes = intval($_POST['nombre_personnes'] ?? 1);
$commentaires = trim($_POST['commentaires'] ?? '');

// Validation simple
$errors = [];
if (empty($nom)) $errors[] = "Le nom est requis";
if (empty($prenom)) $errors[] = "Le prénom est requis";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
if (empty($telephone)) $errors[] = "Le téléphone est requis";
if ($destination_id <= 0) $errors[] = "Destination invalide";
if (empty($date_depart)) $errors[] = "Date de départ requise";
if ($nombre_personnes < 1) $errors[] = "Nombre de personnes invalide";

if (!empty($errors)) {
    session_start();
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = $_POST;
    header('Location: add-reservation.php');
    exit;
}

// Insertion dans la base de données
try {
    $pdo = config::getConnexion();
    
    // Vérifier que la destination existe
    $stmt = $pdo->prepare("SELECT id FROM destinations WHERE id = ?");
    $stmt->execute([$destination_id]);
    if (!$stmt->fetch()) {
        throw new Exception("Destination introuvable");
    }
    
    // Insérer la réservation
    $stmt = $pdo->prepare("
        INSERT INTO reservations 
        (nom, prenom, email, telephone, destination_id, date_depart, date_retour, nombre_personnes, commentaires, statut, date_creation) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'en_attente', NOW())
    ");
    
    $stmt->execute([
        $nom,
        $prenom,
        $email,
        $telephone,
        $destination_id,
        $date_depart,
        $date_retour ?: null,
        $nombre_personnes,
        $commentaires
    ]);
    
    // Redirection avec message de succès
    session_start();
    $_SESSION['success'] = "Réservation ajoutée avec succès!";
    header('Location: reservationetvoyage.php');
    exit;
    
} catch (PDOException $e) {
    die("Erreur lors de l'ajout de la réservation: " . $e->getMessage());
} catch (Exception $e) {
    die($e->getMessage());
}