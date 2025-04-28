<?php
session_start();
require_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/../../Model/user.php');
include_once(__DIR__ . '/../../Controller/userC.php');

// Initialize Google Client for front-end
$google_config = require_once(__DIR__ . '/../../config/google-config.php');
try {
    $client = new Google\Client();
    $client->setClientId($google_config['client_id']);
    $client->setClientSecret($google_config['client_secret']);
    $client->setRedirectUri($google_config['redirect_uri_front']);
    $client->addScope('email');
    $client->addScope('profile');

    $google_login_url = $client->createAuthUrl();
} catch (Exception $e) {
    error_log("Google client setup error: " . $e->getMessage());
    $google_login_url = '#';
}

$errorMessage = "";
$userC = new userC();

if (isset($_POST["email"]) && isset($_POST["password"])) {
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $result = $userC->verifyLogin($_POST["email"], $_POST["password"]);

        if ($result === 'banned') {
            echo '<script>
                Swal.fire({
                    title: "Account Banned",
                    html: `
                        <div class="text-danger">
                            <i class="ti ti-ban" style="font-size: 48px;"></i>
                            <p class="mt-3">Your account has been banned by the administrator.</p>
                            <p class="small">If you believe this is a mistake, please contact support.</p>
                        </div>
                    `,
                    icon: "error",
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: "OK",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "index.php";
                    }
                });
            </script>';
            exit();
        } elseif ($result) {
            $user = $result;
            $_SESSION['user'] = [
                'id' => $user->getIdUser(),
                'email' => $user->getEmail(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom() ?? '',
                'tel' => $user->getTel(),
                'role' => $user->getRole()
            ];

            // For admin users, set both admin and user sessions
            if ($user->getRole() == "admin") {
                $_SESSION['admin'] = [
                    'id' => $user->getIdUser(),
                    'nom' => $user->getNom(),
                    'role' => 'admin'
                ];
                // Let admin choose where to go with a redirect menu
                echo '<script>
                    if(confirm("Would you like to go to the admin dashboard?")) {
                        window.location.href = "back/index.php";
                    } else {
                        window.location.href = "index.php";
                    }
                </script>';
                exit();
            } else {
                header('Location: index.php');
                exit();
            }
        } else {
            $errorMessage = 'Invalid email or password';
        }
    } else {
        $errorMessage = 'Please enter both email and password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign In</title>
    <link rel="stylesheet" href="css/css.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<style>
    .form-group {
        position: relative;
        width: 100%;
        margin-bottom: 20px;
    }

    .error-message {
        color: #FF4B2B;
        font-size: 12px;
        margin-top: 5px;
        position: absolute;
        bottom: -18px;
        left: 0;
    }

    .input-error {
        border: 1px solid #FF4B2B !important;
    }

    .social-container {
        margin: 20px 0;
        text-align: center;
    }

    .google-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
        color: #757575;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: background-color 0.3s;
        margin-top: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .google-btn:hover {
        background-color: #f8f8f8;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .google-btn img {
        width: 18px;
        height: 18px;
        margin-right: 10px;
    }

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
        border-bottom: 1px solid #ddd;
        margin: 0 10px;
    }

    .separator span {
        color: #757575;
        padding: 0 10px;
    }
</style>

<div class="container" id="container">
    <div class="form-container sign-in-container">
        <form method="POST" id="signinForm">
            <h1>Sign in</h1>
            
            <div class="form-group">
                <input type="text" name="email" id="email" placeholder="Email" />
                <div class="error-message" id="email-error"></div>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" id="password" placeholder="Password" />
                <div class="error-message" id="password-error"></div>
            </div>
            
            <a href="forgot.php">Forgot your password?</a>
            <button type="submit">Sign In</button>
            
            <!-- Add the Google Sign In button immediately after the form -->
            <div class="social-container">
                <div class="separator">
                    <span>Or continue with</span>
                </div>
                <a href="<?php echo htmlspecialchars($google_login_url); ?>" class="google-btn">
                    <img src="assets/images/google.svg" alt="Google" />
                    <span>Sign in with Google</span>
                </a>
            </div>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <h1>Welcome Back</h1>
                <p>Don't have an account? <a href="signup.php" id="signIn">Sign Up</a></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('signinForm');

    // Validation rules
    const validationRules = {
        email: {
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Veuillez entrer une adresse email valide'
        },
        password: {
            pattern: /^.{8,}$/,
            message: 'Le mot de passe doit contenir au moins 8 caractères'
        }
    };

    // Validation function
    const validateField = (input) => {
        const fieldName = input.id;
        const value = input.value.trim();
        const errorElement = document.getElementById(`${fieldName}-error`);
        
        // Clear previous errors
        errorElement.textContent = '';
        input.classList.remove('input-error');
        
        // Empty field validation
        if (value === '') {
            errorElement.textContent = 'Ce champ est requis';
            input.classList.add('input-error');
            return false;
        }
        
        // Pattern validation
        if (!validationRules[fieldName].pattern.test(value)) {
            errorElement.textContent = validationRules[fieldName].message;
            input.classList.add('input-error');
            return false;
        }
        
        return true;
    };

    // Real-time validation
    Object.keys(validationRules).forEach(fieldName => {
        const input = document.getElementById(fieldName);
        input.addEventListener('input', () => validateField(input));
        input.addEventListener('blur', () => validateField(input));
    });

    // Form submission
    form.addEventListener('submit', (event) => {
        let hasErrors = false;
        
        Object.keys(validationRules).forEach(fieldName => {
            const input = document.getElementById(fieldName);
            if (!validateField(input)) {
                hasErrors = true;
            }
        });

        if (hasErrors) {
            event.preventDefault();
        }
    });
});
</script>
</body>
</html>