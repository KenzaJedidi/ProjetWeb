<?php
header('Content-Type: application/json; charset=utf-8');

// Enable error logging for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-errors.log');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'reddit');
define('DB_USER', 'root');
define('DB_PASS', '');

// Upload configuration
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_TYPES', [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp'
]);

function validateInput($data) {
    $errors = [];
    
    if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT)) {
        $errors[] = "Invalid user ID";
    }
    
    if (empty($data['title']) || strlen(trim($data['title'])) < 5) {
        $errors[] = "Title must be at least 5 characters";
    }
    
    if (empty($data['content']) || strlen(trim($data['content'])) < 10) {
        $errors[] = "Content must be at least 10 characters";
    }
    
    return $errors;
}

function handleFileUpload($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload error: " . $file['error']);
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new Exception("File too large. Maximum size: " . (MAX_FILE_SIZE / 1024 / 1024) . "MB");
    }
    
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfo, $file['tmp_name']);
    finfo_close($fileInfo);
    
    if (!array_key_exists($mimeType, ALLOWED_TYPES)) {
        throw new Exception("Invalid file type. Allowed: " . implode(', ', array_keys(ALLOWED_TYPES)));
    }
    
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    $extension = ALLOWED_TYPES[$mimeType];
    $filename = uniqid('post_', true) . '.' . $extension;
    $destination = UPLOAD_DIR . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Failed to save uploaded file");
    }
    
    return $filename;
}

try {
    // Connect to database
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    // Validate inputs
    $input = [
        'user_id' => $_POST['user_id'] ?? null,
        'title' => $_POST['title'] ?? null,
        'content' => $_POST['content'] ?? null
    ];
    
    $validationErrors = validateInput($input);
    if (!empty($validationErrors)) {
        throw new Exception(implode("\n", $validationErrors));
    }
    
    // Handle file upload if present
    $filename = null;
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $filename = handleFileUpload($_FILES['image']);
    }
    
    // Insert post into database
    $stmt = $pdo->prepare("
        INSERT INTO posts (user_id, title, content, image, created_at)
        VALUES (:user_id, :title, :content, :image, NOW())
    ");
    
    $stmt->execute([
        ':user_id' => $input['user_id'],
        ':title' => trim($input['title']),
        ':content' => trim($input['content']),
        ':image' => $filename
    ]);
    
    $postId = $pdo->lastInsertId();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Post created successfully',
        'post_id' => $postId
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}