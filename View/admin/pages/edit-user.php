<?php
session_start();
include_once '../../../Controller/userC.php';
include_once '../../../Model/user.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

$userC = new userC();
$error = '';
$success = '';
$user = null;

// Get user data
if (isset($_GET['id'])) {
    $user = $userC->getUserById($_GET['id']);
    if (!$user) {
        header('Location: usersList.php');
        exit();
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Validation
    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['tel'])) {
        $error = "All fields are required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (!preg_match('/^[0-9]{8}$/', $_POST['tel'])) {
        $error = "Phone number must be 8 digits";
    } else {
        // Create updated user object
        $updatedUser = new User(
            $_GET['id'],
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user->getPassword(),
            $_POST['role'],
            $_POST['tel']
        );

        // Try to update
        if ($userC->updateUser($updatedUser)) {
            $success = "User updated successfully";
            // Refresh user data
            $user = $userC->getUserById($_GET['id']);
        } else {
            $error = "Error updating user";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit User - Admin Dashboard</title>
    <?php include '../includes/head.php'; ?>
    <style>
        /* Variables de style harmonieuses */
        :root {
            --primary: #4680FF;
            --primary-light: #edf2ff;
            --primary-dark: #3464c5;
            --success: #2ecc71;
            --danger: #ff4d4f;
            --text-dark: #2c3e50;
            --text-light: #8392ab;
            --border-color: #edf2f9;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            --input-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            --transition: all 0.35s ease;
            --radius: 10px;
        }

        /* Style global pour adoucir l'apparence */
        body {
            background: #f8fafd;
        }
        
        .pc-container {
            background: linear-gradient(135deg, #f8f9fa, #edf2ff);
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        
        /* Carte principale */
        .card {
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: var(--transition);
            background: white;
        }
        
        /* En-tête de la carte */
        .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.75rem;
            position: relative;
        }
        
        .card-header::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -1px;
            height: 2px;
            width: 60px;
            background: var(--primary);
        }
        
        .card-header h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .card-header p {
            color: var(--text-light);
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        /* Corps de la carte */
        .card-body {
            padding: 2rem;
        }
        
        /* Champs de formulaire améliorés */
        .form-label {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }
        
        .form-control, 
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: var(--text-dark);
            transition: var(--transition);
            background-color: #fcfcff;
            box-shadow: var(--input-shadow);
        }
        
        .form-control:focus, 
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(70, 128, 255, 0.15);
            background-color: white;
        }
        
        .form-control::placeholder {
            color: #b0bec5;
            font-style: italic;
        }
        
        /* Style d'espacement des rangées */
        .row.g-3 {
            margin-bottom: 0.5rem;
        }
        
        .row.g-3 + .row.g-3 {
            margin-top: 0.5rem;
        }
        
        /* Alerte améliorée */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .alert i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: #27ae60;
        }
        
        .alert-danger {
            background-color: rgba(255, 77, 79, 0.1);
            color: #e74c3c;
        }
        
        /* Boutons améliorés */
        .btn {
            border-radius: var(--radius);
            padding: 0.65rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            box-shadow: 0 4px 12px rgba(70, 128, 255, 0.25);
        }
        
        .btn-primary:hover {
            box-shadow: 0 6px 15px rgba(70, 128, 255, 0.35);
            transform: translateY(-2px);
        }
        
        .btn-light {
            background: white;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
        }
        
        .btn-light:hover {
            background-color: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-light);
        }
        
        /* Icônes dans les boutons */
        .btn i {
            margin-right: 0.5rem;
        }
        
        /* Validation visuelle améliorée */
        .is-invalid {
            border-color: var(--danger) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.4rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .invalid-feedback {
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
        
        /* Texte d'aide pour le mot de passe */
        .form-text {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-top: 0.4rem;
        }
        
        /* Animation subtile au chargement */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card {
            animation: fadeIn 0.6s ease-out;
        }
        
        /* Espace pour le bouton de soumission */
        .mt-4 {
            margin-top: 2rem !important;
        }
        
        /* Effets de survol pour les champs */
        .form-control:hover,
        .form-select:hover {
            border-color: #c0d3ff;
        }
        
        /* Style adaptatif pour mobile */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
            
            .btn {
                padding: 0.6rem 1rem;
            }
        }
    </style>
</head>
<body>
    <?php $currentPage = 'users-list'; $isSubPage = true; ?>
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="pc-container">
        <div class="pc-content">
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>Edit User</h5>
                                    <p class="text-muted mb-0">Update user information</p>
                                </div>
                                <a href="usersList.php" class="btn btn-light">
                                    <i class="ti ti-arrow-left"></i>
                                    Back to List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($error || $success): ?>
                                <div class="alert alert-<?php echo $error ? 'danger' : 'success'; ?>">
                                    <i class="ti ti-<?php echo $error ? 'alert-circle' : 'check'; ?>"></i>
                                    <span><?php echo htmlspecialchars($error ?: $success); ?></span>
                                </div>
                            <?php endif; ?>

                            <form method="POST" class="needs-validation" novalidate>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="nom" 
                                               value="<?php echo htmlspecialchars($user->getNom()); ?>" required>
                                        <div class="invalid-feedback">Please enter first name</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="prenom" 
                                               value="<?php echo htmlspecialchars($user->getPrenom()); ?>" required>
                                        <div class="invalid-feedback">Please enter last name</div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                                        <div class="invalid-feedback">Please enter a valid email</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" class="form-control" name="tel" pattern="[0-9]{8}" 
                                               value="<?php echo htmlspecialchars($user->getTel()); ?>" required>
                                        <div class="invalid-feedback">Please enter an 8-digit phone number</div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="password" 
                                               placeholder="Leave empty to keep current password">
                                        <div class="form-text">Minimum 8 characters</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Role</label>
                                        <select class="form-select" name="role" required>
                                            <option value="user" <?php echo $user->getRole() === 'user' ? 'selected' : ''; ?>>
                                                User
                                            </option>
                                            <option value="admin" <?php echo $user->getRole() === 'admin' ? 'selected' : ''; ?>>
                                                Admin
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">Please select a role</div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/scripts.php'; ?>
    
    <script>
        // Form validation
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.needs-validation');
            
            // Expressions régulières pour la validation
            const patterns = {
                name: /^[a-zA-ZÀ-ÿ\s'-]{2,}$/,  // Lettres, espaces, tirets et apostrophes uniquement
                email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                phone: /^[0-9]{8}$/,
                // Pattern pour le mot de passe (uniquement s'il est rempli)
                password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
            };

            // Messages d'erreur personnalisés
            const errorMessages = {
                nom: 'First name should contain at least 2 characters (letters only)',
                prenom: 'Last name should contain at least 2 characters (letters only)',
                email: 'Please enter a valid email address',
                tel: 'Phone number must be exactly 8 digits',
                password: 'Password must contain at least 8 characters, including uppercase, lowercase, number and special character',
                role: 'Please select a role'
            };

            // Fonction pour afficher les erreurs
            function showError(input, message) {
                input.classList.add('is-invalid');
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.textContent = message;
                }
            }

            // Fonction pour enlever les erreurs
            function clearError(input) {
                input.classList.remove('is-invalid');
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.textContent = '';
                }
            }

            // Valider un champ selon un pattern
            function validateField(field, pattern, message) {
                if (!pattern.test(field.value) && field.value.trim() !== '') {
                    showError(field, message);
                    return false;
                } else if (field.required && field.value.trim() === '') {
                    showError(field, 'This field is required');
                    return false;
                } else {
                    clearError(field);
                    return true;
                }
            }

            // Validation du prénom et nom
            const nameInputs = form.querySelectorAll('input[name="nom"], input[name="prenom"]');
            nameInputs.forEach(input => {
                // Empêcher la saisie de chiffres
                input.addEventListener('keypress', function(e) {
                    if (!patterns.name.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
                        e.preventDefault();
                    }
                });

                // Validation lors de la saisie
                input.addEventListener('input', function() {
                    validateField(this, patterns.name, errorMessages[this.name]);
                });

                // Validation au focus out
                input.addEventListener('blur', function() {
                    validateField(this, patterns.name, errorMessages[this.name]);
                });
            });

            // Validation de l'email
            const emailInput = form.querySelector('input[name="email"]');
            emailInput.addEventListener('input', function() {
                validateField(this, patterns.email, errorMessages.email);
            });

            emailInput.addEventListener('blur', function() {
                validateField(this, patterns.email, errorMessages.email);
            });

            // Validation du téléphone
            const phoneInput = form.querySelector('input[name="tel"]');
            phoneInput.addEventListener('input', function(e) {
                // Limiter à 8 chiffres
                if (this.value.length > 8) {
                    this.value = this.value.slice(0, 8);
                }
                // Ne permettre que les chiffres
                this.value = this.value.replace(/\D/g, '');
                validateField(this, patterns.phone, errorMessages.tel);
            });

            phoneInput.addEventListener('blur', function() {
                validateField(this, patterns.phone, errorMessages.tel);
            });

            // Validation du mot de passe (seulement s'il est rempli)
            const passwordInput = form.querySelector('input[name="password"]');
            passwordInput.addEventListener('input', function() {
                // Valider seulement si un mot de passe est entré (pas obligatoire pour la modification)
                if (this.value.trim() !== '') {
                    validateField(this, patterns.password, errorMessages.password);
                } else {
                    clearError(this);
                }
            });

            passwordInput.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    validateField(this, patterns.password, errorMessages.password);
                } else {
                    clearError(this);
                }
            });

            // Validation du rôle
            const roleSelect = form.querySelector('select[name="role"]');
            roleSelect.addEventListener('change', function() {
                if (!this.value) {
                    showError(this, errorMessages.role);
                } else {
                    clearError(this);
                }
            });

            // Validation à la soumission du formulaire
            form.addEventListener('submit', function(event) {
                let isValid = true;

                // Valider tous les champs obligatoires
                form.querySelectorAll('input[required], select[required]').forEach(field => {
                    if (field.name === 'nom' || field.name === 'prenom') {
                        if (!patterns.name.test(field.value)) {
                            isValid = false;
                            showError(field, errorMessages[field.name]);
                        }
                    } else if (field.name === 'email') {
                        if (!patterns.email.test(field.value)) {
                            isValid = false;
                            showError(field, errorMessages.email);
                        }
                    } else if (field.name === 'tel') {
                        if (!patterns.phone.test(field.value)) {
                            isValid = false;
                            showError(field, errorMessages.tel);
                        }
                    } else if (field.name === 'role' && !field.value) {
                        isValid = false;
                        showError(field, errorMessages.role);
                    }
                });

                // Valider le mot de passe s'il est rempli
                if (passwordInput.value.trim() !== '' && !patterns.password.test(passwordInput.value)) {
                    isValid = false;
                    showError(passwordInput, errorMessages.password);
                }

                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                    // Ne pas ajouter was-validated - on gère notre propre validation
                }
            });
        });
    </script>

    <script>
        // Amélioration de l'expérience utilisateur sans changer les fonctionnalités
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des champs lors du focus
            const formControls = document.querySelectorAll('.form-control, .form-select');
            formControls.forEach(control => {
                control.addEventListener('focus', function() {
                    this.closest('.col-md-6').style.transform = 'translateY(-3px)';
                    this.closest('.col-md-6').style.transition = 'transform 0.3s ease';
                });
                
                control.addEventListener('blur', function() {
                    this.closest('.col-md-6').style.transform = 'translateY(0)';
                });
            });
            
            // Effet de pulse sur le bouton de sauvegarde
            const saveButton = document.querySelector('button[type="submit"]');
            if (saveButton) {
                saveButton.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    
                    // Créer un effet de pulse
                    this.animate([
                        { transform: 'scale(1)' },
                        { transform: 'scale(1.05)' },
                        { transform: 'scale(1)' }
                    ], {
                        duration: 800,
                        iterations: 1
                    });
                });
            }
            
            // Effet spécial pour les alertes
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = 'transform 0.5s ease';
                    alert.style.transform = 'translateY(-3px)';
                    
                    setTimeout(() => {
                        alert.style.transform = 'translateY(0)';
                    }, 300);
                }, 500);
            }
        });
    </script>
</body>
</html>