<?php
session_start();
include_once '../Controller/userC.php';
require_once '../vendor/autoload.php';

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
            $_SESSION['user'] = [
                'id' => $user->getIdUser(),
                'nom' => $user->getNom(),
                'role' => $user->getRole(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
            ];
             header('Location: index.php');
            exit();
        } else {
             $_SESSION['user'] = [
                'id' => $user->getIdUser(),
                'nom' => $user->getNom(),
                'role' => $user->getRole(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
            ];
             header('Location: index.php');
            exit();
            
            
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
    <link rel="icon" href="admin/assets/images/favicon.svg" type="image/x-icon">
    <link href="admin/assets/css/plugins/animate.min.css" rel="stylesheet">
    <!-- [Google Font] Family -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <!-- [Tabler Icons] -->
    <link rel="stylesheet" href="admin/assets/fonts/tabler-icons.min.css">
    <!-- [Feather Icons] -->
    <link rel="stylesheet" href="admin/assets/fonts/feather.css">
    <!-- [Font Awesome Icons] -->
    <link rel="stylesheet" href="admin/assets/fonts/fontawesome.css">
    <!-- [Material Icons] -->
    <link rel="stylesheet" href="admin/assets/fonts/material.css">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="admin/assets/css/style.css">
    <link rel="stylesheet" href="admin/assets/css/style-preset.css">
    
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
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }

        /* Modifiez ces styles pour agrandir le logo */
        .navbar-brand img, 
        .logo-lg, 
        .logo-display,
        .logo-scrolled,
        .auth-wrapper .card img[alt="Logo"] {
            max-height: 80px !important; /* Augmentation de la taille du logo */
            width: auto;
            object-fit: contain;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        /* Animation subtile au survol */
        .navbar-brand img:hover, 
        .logo-lg:hover, 
        .auth-wrapper .card img[alt="Logo"]:hover {
            transform: scale(1.05);
        }

        /* Ajustement pour la page de connexion admin */
        .auth-wrapper .card .text-center img {
            max-height: 90px !important; /* Logo encore plus grand sur la page de connexion */
            margin: 0.5rem 0 1.5rem;
        }

        /* Pour le mode responsive - ajustez selon vos besoins */
        @media (max-width: 768px) {
            .navbar-brand img, 
            .logo-lg, 
            .logo-display,
            .logo-scrolled {
                max-height: 60px !important;
            }
            
            .auth-wrapper .card .text-center img {
                max-height: 70px !important;
            }
        }
        
        /* Pour les très petits écrans */
        @media (max-width: 480px) {
            .navbar-brand img, 
            .logo-lg, 
            .logo-display,
            .logo-scrolled {
                max-height: 50px !important;
            }
            
            .auth-wrapper .card .text-center img {
                max-height: 60px !important;
            }
        }

        /* Agrandissement significatif du logo */
        .auth-wrapper .card .text-center img {
            max-height: 120px !important; /* Logo beaucoup plus grand */
            width: auto;
            object-fit: contain;
            margin: 0.5rem 0 1.75rem;
            transition: all 0.4s ease;
            filter: drop-shadow(0 5px 10px rgba(79, 192, 212, 0.2));
        }

        /* Animation légère au chargement */
        @keyframes logoEntrance {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .auth-wrapper .card .text-center img {
            animation: logoEntrance 0.7s ease forwards;
        }

        /* Effet au survol */
        .auth-wrapper .card .text-center img:hover {
            transform: scale(1.08);
            filter: drop-shadow(0 8px 16px rgba(79, 192, 212, 0.3));
        }

        /* Ajustements pour la page responsive */
        @media (min-width: 992px) {
            .auth-wrapper .card .text-center img {
                max-height: 140px !important; /* Encore plus grand sur les écrans larges */
            }
        }

        /* Ajustement du conteneur pour accueillir le logo plus grand */
        .auth-wrapper .card {
            padding-top: 20px;
        }
        
        .auth-wrapper .card .text-center {
            margin-bottom: 10px;
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
                            <a href="#"><img src="admin/assets/images/localoo tiffany.png" alt="Logo" class="mb-4" style="max-height: 60px;"></a>
                        </div>
                        <form method="POST" id="loginForm" novalidate>
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
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                                </div>
                                <div class="error-message" id="email-error">Please enter a valid email address</div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-lock"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="error-message" id="password-error">Password must be at least 6 characters</div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" name="login" class="btn btn-primary" id="loginBtn">Login</button>
                            </div>
                        </form>
                        
                        <!-- Add Google Sign-In -->
                        <div class="mt-4">
                            <div class="separator">
                                <span class="separator-text">Or</span>
                            </div>
                            <div class="d-grid mt-3">
                                <a href="<?php echo htmlspecialchars($authUrl); ?>" class="btn btn-outline-secondary">
                                    <img src="admin/assets/images/google.svg" alt="Google" class="me-2" style="width: 18px;">
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const loginBtn = document.getElementById('loginBtn');
            
            // Désactiver la validation HTML native
            loginForm.setAttribute('novalidate', true);
            
            // Validation en temps réel
            emailInput.addEventListener('input', validateEmail);
            passwordInput.addEventListener('input', validatePassword);
            
            // Validation au focus out
            emailInput.addEventListener('blur', validateEmail);
            passwordInput.addEventListener('blur', validatePassword);
            
            // Validation à la soumission
            loginForm.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    // Focus sur le premier champ invalide
                    if (!validateEmail()) {
                        emailInput.focus();
                    } else if (!validatePassword()) {
                        passwordInput.focus();
                    }
                }
            });
            
            function validateEmail() {
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email === '') {
                    showError(emailInput, emailError, 'Email is required');
                    return false;
                } else if (!emailRegex.test(email)) {
                    showError(emailInput, emailError, 'Please enter a valid email address');
                    return false;
                } else {
                    hideError(emailInput, emailError);
                    return true;
                }
            }
            
            function validatePassword() {
                const password = passwordInput.value.trim();
                
                if (password === '') {
                    showError(passwordInput, passwordError, 'Password is required');
                    return false;
                } else if (password.length < 6) {
                    showError(passwordInput, passwordError, 'Password must be at least 6 characters');
                    return false;
                } else {
                    hideError(passwordInput, passwordError);
                    return true;
                }
            }
            
            function validateForm() {
                const isEmailValid = validateEmail();
                const isPasswordValid = validatePassword();
                
                return isEmailValid && isPasswordValid;
            }
            
            function showError(input, errorElement, message) {
                input.classList.add('is-invalid');
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
            
            function hideError(input, errorElement) {
                input.classList.remove('is-invalid');
                errorElement.style.display = 'none';
            }
        });

        // Pour la version foncée
        if (document.querySelector('.pc-sidebar .m-header .logo-lg')) {
            document.querySelector('.pc-sidebar .m-header .logo-lg').setAttribute('src', '../assets/images/localoo tiffany.png');
        }

        // Pour la version claire
        if (document.querySelector('.pc-sidebar .m-header .logo-lg')) {
            document.querySelector('.pc-sidebar .m-header .logo-lg').setAttribute('src', '../assets/images/localoo tiffany.png');
        }
    </script>
</body>
</html>