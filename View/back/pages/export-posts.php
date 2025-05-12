<?php
include '../../../Controller/PostC.php';
include '../../../Controller/UserC.php';

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="posts_export_' . date('Y-m-d') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

$PostC = new PostC();
$UserC = new UserC();

// Get all posts
$posts = $PostC->AfficherPosts();

// Start output buffering
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Posts Export</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0ABAB5;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Posts Export - <?php echo date('Y-m-d'); ?></h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Title</th>
                <th>Content</th>
                <th>Date Created</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo $post['id']; ?></td>
                <td>
                    <?php 
                    $user = $UserC->RecupererUser($post['user_id']);
                    echo $user ? $user['username'] : 'Unknown User'; 
                    ?>
                </td>
                <td><?php echo $post['title']; ?></td>
                <td><?php echo $post['content']; ?></td>
                <td><?php echo $post['created_at']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php
// Get the output buffer content and send it to the browser
echo ob_get_clean();
?>
