<?php
session_start();
include_once '../../Controller/userC.php';
require_once '../../vendor/autoload.php';

// Initialize Google Client
$clientID = '376525179412-78ffmmmq31409kt53a2m9ouvleh128o8.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/amal/View/admin/google-callback.php';
try {
    $client = new Google\Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->setAccessType('offline');
    $client->setPrompt('consent');
    $client->addScope('email');
    $client->addScope('profile');

    $authUrl = $client->createAuthUrl();
} catch (Exception $e) {
    error_log("Google client setup error: " . $e->getMessage());
    $authUrl = '#';
}

$error = '';

if (isset($_POST['login'])) {
    $userC = new userC();
    
    // Get form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Debug output
    error_log("Login attempt for email: " . $email);
    
    $user = $userC->getUserByEmail($email);
    
    if ($user && password_verify($password, $user->getPassword())) {
        if ($user->getRole() === 'admin') {
            $_SESSION['admin'] = [
                'id' => $user->getIdUser(),
                'nom' => $user->getNom(),
                'role' => $user->getRole()
            ];
            header('Location: index.php');
            exit();
        } else {
            $error = 'Insufficient privileges. Admin access only.';
        }
    } else {
        $error = 'Invalid email or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    
    <!-- [Template CSS Files] -->
    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon">
    <link href="assets/css/plugins/animate.min.css" rel="stylesheet">
    <!-- [Google Font] Family -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <!-- [Tabler Icons] -->
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
    <!-- [Feather Icons] -->
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <!-- [Font Awesome Icons] -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <!-- [Material Icons] -->
    <link rel="stylesheet" href="assets/fonts/material.css">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style-preset.css">
    
    <style>
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }
        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
            margin: 0 1rem;
        }
        .separator-text {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .btn-outline-secondary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <!-- [ auth-wrapper ] start -->
    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="#"><img src="assets/images/logo-dark.svg" alt="Logo" class="mb-4"></a>
                        </div>
                        <form method="POST">
                            <div class="text-center">
                                <h4 class="mb-4">Admin Login</h4>
                            </div>
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger">
                                    <?php 
                                        echo htmlspecialchars($_SESSION['error']); 
                                        unset($_SESSION['error']); 
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-lock"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" name="login" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        
                        <!-- Add Google Sign-In -->
                        <div class="mt-4">
                            <div class="separator">
                                <span class="separator-text">Or</span>
                            </div>
                            <div class="d-grid mt-3">
                                <a href="<?php echo htmlspecialchars($authUrl); ?>" class="btn btn-outline-secondary">
                                    <img src="assets/images/google.svg" alt="Google" class="me-2" style="width: 18px;">
                                    Sign in with Google
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
</body>
</html>