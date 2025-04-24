<?php
session_start();
// Replace relative paths with absolute paths using __DIR__
include_once(__DIR__ . '/../../Model/user.php');
include_once(__DIR__ . '/../../Controller/userC.php');

$errorMessage = "";
$userC = new userC();

if (isset($_POST["email"]) && isset($_POST["password"])) {
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $user = $userC->verifyLogin($_POST["email"], $_POST["password"]);
        
        if ($user) {
            $_SESSION['user'] = [
                'id' => $user->getIdUser(),
                'email' => $user->getEmail(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'tel' => $user->getTel(),
                'role' => $user->getRole()
            ];

            if ($user->getRole() == "admin") {
                header('Location: back/index.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $errorMessage = 'Invalid email or password';
        }
    } else {
        $errorMessage = 'Please enter both email and password';
    }
}
?>

<link rel="stylesheet" href="css/css.css">

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