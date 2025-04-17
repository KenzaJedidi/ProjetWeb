<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Connexion à la base
$host = 'localhost';
$dbname = 'localoo';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base : ' . $e->getMessage()]);
    exit;
}

// Vérifie les champs obligatoires
$required = ['eventTitle', 'eventLocation', 'startDate', 'endDate', 'eventType', 'participants', 'eventStatus'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => 'Champ requis manquant : ' . $field]);
        exit;
    }
}

// Gestion de l'image
$imageName = null;
if (isset($_FILES['eventImage']) && $_FILES['eventImage']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $tmpName = $_FILES['eventImage']['tmp_name'];
    $originalName = basename($_FILES['eventImage']['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

    // Sécurité sur l'extension
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array(strtolower($extension), $allowedExtensions)) {
        echo json_encode(['success' => false, 'message' => 'Extension de fichier non autorisée']);
        exit;
    }

    $imageName = uniqid() . '.' . $extension;
    $destination = $uploadDir . $imageName;

    if (!move_uploaded_file($tmpName, $destination)) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'upload de l\'image']);
        exit;
    }
}

// Insertion en base
try {
    $stmt = $pdo->prepare("INSERT INTO evenements (titre, lieu, date_debut, date_fin, type, participants, statut, image, description)
                           VALUES (:titre, :lieu, :date_debut, :date_fin, :type, :participants, :statut, :image, :description)");

    $success = $stmt->execute([
        ':titre' => $_POST['eventTitle'],
        ':lieu' => $_POST['eventLocation'],
        ':date_debut' => $_POST['startDate'],
        ':date_fin' => $_POST['endDate'],
        ':type' => $_POST['eventType'],
        ':participants' => $_POST['participants'],
        ':statut' => $_POST['eventStatus'],
        ':image' => $imageName,
        ':description' => $_POST['eventDescription'] ?? ''
    ]);

    echo json_encode(['success' => $success, 'message' => $success ? 'Événement ajouté avec succès' : 'Échec lors de l\'insertion']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur SQL : ' . $e->getMessage()]);
    exit;
}
?>
