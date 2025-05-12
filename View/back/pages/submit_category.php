<?php
$pdo = new PDO("mysql:host=localhost;dbname=oussema;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
header('Content-Type: application/json');

if (($_POST['action'] ?? '') === 'insert') {
    $name = trim($_POST['name'] ?? '');
    if (strlen($name) < 2) {
        echo json_encode(['success' => false, 'message' => 'Nom trop court']);
        exit;
    }

    // Nettoyage du nom pour générer un nom de fichier propre
    $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($name));
    $iconPath = "web/uploads/" . $cleanName . ".png"; // Image attendue dans ce chemin

    // Vérifier si la catégorie existe déjà
    $stmt = $pdo->prepare("SELECT id FROM categorie WHERE LOWER(nom)=LOWER(?)");
    $stmt->execute([$name]);
    if ($id = $stmt->fetchColumn()) {
        $upd = $pdo->prepare("UPDATE categorie SET icone = ? WHERE id = ?");
        $upd->execute([$iconPath, $id]);
        echo json_encode(['success' => true, 'category_id' => $id]);
        exit;
    }

    // Sinon, insérer une nouvelle catégorie
    $ins = $pdo->prepare("INSERT INTO categorie (nom, icone) VALUES (?, ?)");
    $ins->execute([$name, $iconPath]);
    echo json_encode(['success' => true, 'category_id' => $pdo->lastInsertId()]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action invalide']);
