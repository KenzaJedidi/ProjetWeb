<?php
require_once 'Config.php';
require_once 'Model/Post.php';

// Configuration de l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Paramètres de test
$userId = 1;  // Utilisateur de test
$postId = 125;  // Post de test
$reactionTypes = ['like', 'love', 'wow', 'sad', 'angry'];

// Créer une instance de Post
$post = new Post(null, null, null, null);
$post->setId($postId);

// Fonction de test détaillée
function testReaction($post, $userId, $reactionType) {
    echo "===== Test de réaction : $reactionType =====\n";
    
    // Connexion à la base de données
    $pdo = Config::GetConnexion();
    
    // Vérification avant la réaction
    $stmtBefore = $pdo->prepare("SELECT * FROM reactions WHERE user_id = ? AND post_id = ?");
    $stmtBefore->execute([$userId, $post->getId()]);
    $reactionsBefore = $stmtBefore->fetchAll(PDO::FETCH_ASSOC);
    echo "Réactions avant le test : " . count($reactionsBefore) . "\n";
    
    // Ajout de la réaction
    $result = $post->addReaction($userId, $reactionType);
    
    echo "Statut : " . $result['status'] . "\n";
    echo "Message : " . $result['message'] . "\n";
    
    // Vérification après la réaction
    $stmtAfter = $pdo->prepare("SELECT * FROM reactions WHERE user_id = ? AND post_id = ?");
    $stmtAfter->execute([$userId, $post->getId()]);
    $reactionsAfter = $stmtAfter->fetchAll(PDO::FETCH_ASSOC);
    echo "Réactions après le test : " . count($reactionsAfter) . "\n";
    
    // Affichage des détails des réactions si présentes
    if (!empty($reactionsAfter)) {
        echo "Détails des réactions :\n";
        foreach ($reactionsAfter as $reaction) {
            echo " - ID: " . $reaction['id'] . "\n";
            echo " - Type: " . $reaction['reaction_type'] . "\n";
            echo " - Date: " . $reaction['created_at'] . "\n";
        }
    } else {
        echo "ATTENTION : Aucune réaction n'a été ajoutée !\n";
    }
    
    // Vérification des erreurs PDO
    $errorInfo = $stmtAfter->errorInfo();
    if ($errorInfo[0] !== '00000') {
        echo "Erreur PDO : " . print_r($errorInfo, true) . "\n";
    }
    
    echo "\n";
    return $result;
}

// Scénarios de test
echo "DÉBUT DES TESTS DE RÉACTION\n\n";

foreach ($reactionTypes as $type) {
    // Premier clic : ajout de la réaction
    echo "Premier clic - Ajout de $type\n";
    $result1 = testReaction($post, $userId, $type);
    
    // Deuxième clic : suppression de la réaction (toggle)
    echo "Deuxième clic - Toggle de $type\n";
    $result2 = testReaction($post, $userId, $type);
    
    // Changement de réaction
    $nextIndex = (array_search($type, $reactionTypes) + 1) % count($reactionTypes);
    $nextType = $reactionTypes[$nextIndex];
    echo "Troisième clic - Changement vers $nextType\n";
    $result3 = testReaction($post, $userId, $nextType);
    
    echo "-------------------\n\n";
}

echo "FIN DES TESTS DE RÉACTION\n";
?>