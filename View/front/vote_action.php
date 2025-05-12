<?php
session_start();
require_once '../../Controller/VoteC.php';

// Debug session information
// echo json_encode([
//     'session_id' => session_id(),
//     'session_vars' => $_SESSION,
//     'user_id_set' => isset($_SESSION['user_id']),
//     'message' => 'Session debug info'
// ]);
// exit;

// Validate input
if (!isset($_POST['post_id'])) {
// if (!isset($_POST['post_id']) || !isset($_POST['vote_type'])) {
    echo json_encode(['success' => false, 'message' => 'Paramètres invalides.']);
    exit;
}

// $userId = $_SESSION['user_id'];
$postId = (int)$_POST['post_id'];
// $voteType = $_POST['vote_type'] === 'up' ? 'up' : 'down';

$voteC = new VoteC();

try {
    $result = $voteC->AddVote($postId);

    // if ($result) {
    //     echo json_encode([
    //         'success' => true, 
    //         'message' => 'Vote enregistré avec succès.', 
    //         'upvotes' => $voteC->CountVotes($postId, 'up'),
    //         'downvotes' => $voteC->CountVotes($postId, 'down')
    //     ]);
    // } else {
    //     echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du vote.']);
    // }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue: ' . $e->getMessage()]);
}
?>
