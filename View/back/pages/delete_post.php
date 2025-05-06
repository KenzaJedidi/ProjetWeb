<?php
// delete_post.php
$host = 'localhost';
$dbname = 'reddit';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            header("Location: notifications.php?success=1");
        } else {
            header("Location: notifications.php?error=Post introuvable");
        }
        exit;
    } else {
        header("Location: notifications.php?error=ID manquant");
        exit;
    }

} catch (Exception $e) {
    header("Location: notifications.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>