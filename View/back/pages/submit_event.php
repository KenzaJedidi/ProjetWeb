<?php
$pdo = new PDO("mysql:host=localhost;dbname=oussema;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
header('Content-Type: application/json');

try {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'insert') {
        // Insertion d'un événement
        $required = ['eventTitle', 'eventLocation', 'startDate', 'endDate', 'participants', 'eventStatus'];
        foreach ($required as $f) {
            if (empty($_POST[$f])) throw new Exception("Champ manquant : $f");
        }

        $imgPath = null;
        if (!empty($_FILES['eventImage']['tmp_name'])) {
            $baseDir = realpath(__DIR__ . '/..');
            $uploadDir = $baseDir . '/web/uploads/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $originalName = basename($_FILES['eventImage']['name']);
            $safeName = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $originalName);
            
            $counter = 1;
            $fileName = $safeName;
            while (file_exists($uploadDir . $fileName)) {
                $fileName = pathinfo($safeName, PATHINFO_FILENAME) . "_$counter." . pathinfo($safeName, PATHINFO_EXTENSION);
                $counter++;
            }

            if (!move_uploaded_file($_FILES['eventImage']['tmp_name'], $uploadDir . $fileName)) {
                throw new Exception("Erreur lors de l'upload de l'image");
            }
            $imgPath = $fileName;
        }

        $stmt = $pdo->prepare("INSERT INTO evenements 
            (titre, ville, date_debut, date_fin, participants, statut, description, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $_POST['eventTitle'],
            $_POST['eventLocation'],
            $_POST['startDate'],
            $_POST['endDate'],
            (int)$_POST['participants'],
            $_POST['eventStatus'],
            $_POST['eventDescription'] ?? '',
            $imgPath
        ]);

        echo json_encode(['success' => true, 'event_id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($action === 'insert_category') {
        // Insertion d'une nouvelle catégorie
        if (empty($_POST['name'])) {
            throw new Exception("Nom de catégorie manquant");
        }

        $iconPath = null;
        if (!empty($_FILES['icon']['tmp_name'])) {
            $baseDir = realpath(__DIR__ . '/..');
            $uploadDir = $baseDir . '/web/uploads/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $originalName = basename($_FILES['icon']['name']);
            $safeName = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $originalName);
            
            $counter = 1;
            $fileName = $safeName;
            while (file_exists($uploadDir . $fileName)) {
                $fileName = pathinfo($safeName, PATHINFO_FILENAME) . "_$counter." . pathinfo($safeName, PATHINFO_EXTENSION);
                $counter++;
            }

            if (!move_uploaded_file($_FILES['icon']['tmp_name'], $uploadDir . $fileName)) {
                throw new Exception("Erreur lors de l'upload de l'icône");
            }
            $iconPath = $fileName;
        }

        $stmt = $pdo->prepare("INSERT INTO categorie (nom, icone) VALUES (?, ?)");
        if (!$stmt->execute([$_POST['name'], $iconPath])) {
            throw new Exception("Erreur lors de l'insertion de la catégorie");
        }

        echo json_encode(['success' => true, 'category_id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($action === 'update') {
        if (empty($_POST['event_id']) || empty($_POST['categorie_id'])) {
            throw new Exception('Données manquantes pour la mise à jour');
        }
        $stmt = $pdo->prepare("UPDATE evenements SET categorie_id = ? WHERE id = ?");
        $stmt->execute([(int)$_POST['categorie_id'], (int)$_POST['event_id']]);
        echo json_encode(['success' => true]);
        exit;
    }

    throw new Exception('Action invalide');

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
?>