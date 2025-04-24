<?php
session_start();
// Use absolute paths with __DIR__
include_once(__DIR__ . '/../../Model/user.php');
include_once(__DIR__ . '/../../Controller/userC.php');

$error = "";
$userC = new userC();

if (isset($_POST["signup"])) {
    if (
        !empty($_POST["nom"]) &&
        !empty($_POST["prenom"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["tel"]) &&
        !empty($_POST["password"])
    ) {
        $tel = $_POST["tel"];
        if (!is_numeric($tel) || strlen($tel) !== 8) {
            echo '<p style="color: red;">Invalid phone number. Please enter a valid 8-digit phone number.</p>';
        } else {
            if ($userC->emailExists($_POST["email"])) {
                echo '<p style="color: red;">Email already in use. Please choose a different one.</p>';
            } elseif ($userC->phoneExists($tel)) {
                echo '<p style="color: red;">Phone number already in use. Please choose a different one.</p>';
            } else {
                $user = new User(
                    0,
                    $_POST['nom'],
                    $_POST['prenom'],
                    $_POST['email'],
                    $_POST['password'],
                    "client",
                    $tel
                );
                $userC->addUser($user);
                header('Location:signin.php');
                exit();
            }
        }
    } else {
        echo '<p style="color: red;">Please fill in all required fields.</p>';
    }
}
?>

<link rel="stylesheet" href="css/css.css">

<style>
    .error-messages {
        color: red;
        font-size: 14px;
        margin-bottom: 10px;
        list-style-type: none;
        padding: 0;
    }

    .error-messages li {
        margin-bottom: 5px;
    }

    .input-error {
        border: 1px solid red !important;
    }

    .error-message {
        color: red;
        font-size: 12px;
        margin-top: 5px;
        min-height: 15px;
    }

    .form-group {
        margin-bottom: 15px;
        position: relative;
    }
</style>

<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form method="POST" id="signupForm">
            <h1>Create Account</h1>
            
            <div class="form-group">
                <input type="text" name="nom" id="nom" placeholder="Name" />
                <div class="error-message" id="nom-error"></div>
            </div>
            
            <div class="form-group">
                <input type="text" name="prenom" id="prenom" placeholder="Last Name" />
                <div class="error-message" id="prenom-error"></div>
            </div>
            
            <div class="form-group">
                <input type="text" name="email" id="email" placeholder="Email" />
                <div class="error-message" id="email-error"></div>
            </div>
            
            <div class="form-group">
                <input type="text" name="tel" id="tel" placeholder="Phone (8 digits)" />
                <div class="error-message" id="tel-error"></div>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" id="password" placeholder="Password" />
                <div class="error-message" id="password-error"></div>
            </div>
            
            <button type="submit" name="signup">Sign Up</button>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form>
            <h1>Welcome Back</h1>
            <p>Already have an account?</p>
            <button type="button" onclick="window.location.href='signin.php'">Sign In</button>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h2>Already Registered?</h2>
                <p>Sign in to access your account</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h2>New Here?</h2>
                <p>Create your account and start your journey</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Panel switching functionality
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });

    // Form validation
    const form = document.getElementById('signupForm');
    const validationRules = {
        nom: {
            pattern: /^[a-zA-ZÀ-ÿ\s'-]{2,}$/,
            message: 'Le prénom doit contenir au moins 2 caractères alphabétiques'
        },
        prenom: {
            pattern: /^[a-zA-ZÀ-ÿ\s'-]{2,}$/,
            message: 'Le nom doit contenir au moins 2 caractères alphabétiques'
        },
        email: {
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Veuillez entrer une adresse email valide'
        },
        tel: {
            pattern: /^\d{8}$/,
            message: 'Le numéro doit contenir exactement 8 chiffres'
        },
        password: {
            pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
            message: 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial'
        }
    };

    const validateField = (input) => {
        const fieldName = input.id;
        const value = input.value.trim();
        const errorElement = document.getElementById(`${fieldName}-error`);
        
        // Clear previous error
        errorElement.textContent = '';
        input.classList.remove('input-error');
        
        // Check if empty
        if (value === '') {
            errorElement.textContent = 'Ce champ est requis';
            input.classList.add('input-error');
            return false;
        }
        
        // Check pattern
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
        let isValid = true;
        
        Object.keys(validationRules).forEach(fieldName => {
            const input = document.getElementById(fieldName);
            if (!validateField(input)) {
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault();
        }
    });
});
</script>