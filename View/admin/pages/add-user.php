<?php
session_start();
$currentPage = 'add-user';
$isSubPage = true;
include '../includes/sidebar.php';
include_once '../../../Controller/userC.php';
include_once '../../../Model/user.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

if (isset($_POST['submit'])) {
    $userC = new userC();
    
    // Validation
    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || 
        empty($_POST['password']) || empty($_POST['tel'])) {
        $error = "All fields are required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (!preg_match('/^[0-9]{8}$/', $_POST['tel'])) {
        $error = "Phone number must be 8 digits";
    } else {
        // Create new user
        $user = new User(
            0,
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['role'],
            $_POST['tel']
        );

        if ($userC->addUser($user)) {
            $success = "User added successfully";
        } else {
            $error = "Error adding user";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New User - Admin Dashboard</title>
    <?php include '../includes/head.php'; ?>
    <style>
      /* Variables de couleurs et styles globaux */
      :root {
        --primary: #1890ff;
        --primary-light: #e6f7ff;
        --primary-dark: #0c63e4;
        --success: #52c41a;
        --danger: #ff4d4f;
        --warning: #faad14;
        --text: #141414;
        --text-light: #6c757d;
        --border: #e6ebf1;
        --border-focus: #a6d8ff;
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
        --radius: 8px;
      }

      /* Améliorations de la carte */
      .card {
        border: none;
        box-shadow: var(--shadow-md);
        border-radius: var(--radius);
        transition: var(--transition);
        overflow: hidden;
      }

      .card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
      }

      .card-header {
        background: linear-gradient(to right, #1890ff, #40a9ff);
        color: white;
        border-bottom: none;
        padding: 1.5rem;
      }

      .card-header h5 {
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
      }

      .card-header p {
        opacity: 0.85;
      }

      .card-header .btn-light {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        transition: var(--transition);
        font-weight: 500;
      }

      .card-header .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
      }

      .card-body {
        padding: 2rem;
      }

      /* Améliorations des inputs */
      .form-control, .form-select {
        padding: 0.65rem 1rem;
        border-radius: var(--radius);
        border: 2px solid var(--border);
        transition: var(--transition);
        height: auto;
        box-shadow: none;
      }

      .form-control:focus, .form-select:focus {
        border-color: var(--border-focus);
        box-shadow: 0 0 0 0.25rem rgba(24, 144, 255, 0.15);
      }

      .form-control.is-valid, .form-select.is-valid {
        border-color: var(--success);
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2352c41a' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
      }

      .form-control.is-invalid, .form-select.is-invalid {
        border-color: var(--danger);
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ff4d4f'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ff4d4f' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
      }

      .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 0.5rem;
      }

      /* Labels améliorés */
      .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: var(--text);
        font-size: 0.95rem;
      }

      /* Boutons améliorés */
      .btn-primary {
        background: linear-gradient(to right, #1890ff, #40a9ff);
        border: none;
        padding: 0.65rem 1.5rem;
        font-weight: 500;
        border-radius: var(--radius);
        transition: var(--transition);
        box-shadow: 0 4px 10px rgba(24, 144, 255, 0.3);
      }

      .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(24, 144, 255, 0.4);
      }

      .btn-primary:active {
        transform: translateY(0);
      }

      /* Espacement et structure du formulaire */
      .row {
        margin-bottom: 1.25rem;
      }

      .mt-3 {
        margin-top: 1.5rem !important;
      }

      .mt-4 {
        margin-top: 2rem !important;
      }

      /* Amélioration des alertes */
      .alert {
        border-radius: var(--radius);
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        animation: fadeIn 0.5s ease;
      }

      .alert i {
        font-size: 1.25rem;
        margin-right: 0.75rem;
      }

      .alert-success {
        background-color: rgba(82, 196, 26, 0.15);
        color: #2b7412;
      }

      .alert-danger {
        background-color: rgba(255, 77, 79, 0.15);
        color: #cf1322;
      }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
      }

      /* Effet de progression du mot de passe */
      .password-strength {
        height: 4px;
        background-color: #f0f0f0;
        border-radius: 4px;
        margin-top: 5px;
        overflow: hidden;
        display: none;
      }

      .password-strength-bar {
        height: 100%;
        width: 0;
        border-radius: 4px;
        transition: width 0.3s ease, background-color 0.3s ease;
      }

      .weak { width: 25%; background-color: #ff4d4f; }
      .medium { width: 50%; background-color: #faad14; }
      .strong { width: 75%; background-color: #52c41a; }
      .very-strong { width: 100%; background-color: #1890ff; }

      /* Amélioration de la disposition sur mobile */
      @media (max-width: 767.98px) {
        .card-body {
          padding: 1.5rem;
        }
      }

      /* Tooltip de confirmation pour le mot de passe */
      .password-container {
        position: relative;
      }

      .password-requirements {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        background: white;
        padding: 1rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        width: 100%;
        z-index: 10;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
      }

      .password-container:focus-within .password-requirements {
        display: block;
        opacity: 1;
        transform: translateY(0);
      }

      .requirement {
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-light);
        display: flex;
        align-items: center;
      }

      .requirement i {
        margin-right: 8px;
        font-size: 0.9rem;
      }

      .requirement.met {
        color: var(--success);
      }

      .requirement.not-met {
        color: var(--text-light);
      }
      
      /* Sidebar styles from index.php */
      .pc-sidebar {
        background: #ffffff !important;
        border-right: 1px solid #e9ecef;
      }

      .pc-navbar .pc-item {
        position: relative;
      }

      .pc-navbar .pc-item .pc-link {
        color: #000000 !important; /* Texte en noir */
      }

      .pc-navbar .pc-item .pc-link:hover {
        background: #f8f9fa;
        color: #000000 !important; /* Texte reste en noir au survol */
      }

      .pc-navbar .pc-item.active .pc-link {
        background: #4680FF;
        color: #ffffff !important; /* Le texte reste blanc pour l'élément actif */
      }

      .pc-item.pc-caption label {
        color: #000000 !important;
        opacity: 0.9;
      }

      .pc-item.pc-caption span {
        color: #000000 !important;
        opacity: 0.7;
      }

      .pc-navbar .pc-item.disabled .pc-link {
        color: #6c757d !important;
      }

      .pc-navbar .pc-item .pc-link:hover {
        background: #f8f9fa;
        color: #000000 !important;
      }

      .pc-navbar .pc-item.active .pc-link {
        background: #4680FF;
        color: #ffffff;
        box-shadow: 0 4px 8px rgba(70, 128, 255, 0.2);
      }

      .pc-navbar .pc-item.disabled .pc-link {
        cursor: not-allowed;
        background: #f8f9fa;
      }

      .pc-navbar .pc-item .pc-micon {
        margin-right: 10px;
        font-size: 1.1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: rgba(70, 128, 255, 0.1);
      }

      .pc-navbar .pc-item.active .pc-micon {
        background: rgba(255, 255, 255, 0.2);
      }

      .pc-item.pc-caption {
        margin-top: 20px;
        padding: 10px 15px;
      }

      .pc-item.pc-caption label {
        color: #4680FF;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }

      .pc-item.pc-caption span {
        color: #67748e;
        font-size: 11px;
        display: block;
        margin-top: 4px;
      }

      .pc-navbar .pc-item.disabled .pc-micon {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
      }

      /* Logo styling - centered and extra large */
      .m-header {
          padding: 30px 15px;
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          border-bottom: 1px solid rgba(233, 236, 239, 0.5);
          margin-bottom: 15px;
          text-align: center;
      }

      .b-brand {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          text-decoration: none;
          transition: all 0.3s ease;
          width: 100%;
      }

      .b-brand:hover {
          opacity: 0.9;
          transform: translateY(-2px);
      }

      .logo {
          max-height: 115px; /* Logo beaucoup plus grand */
          width: auto;
          display: block;
          margin: 0 auto; /* Suppression de la marge en bas */
          transition: all 0.3s ease;
      }

      /* Nous cachons le texte logo-text */
      .logo-text {
          display: none; /* Cache le texte */
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
    
    <!-- [ Mobile header ] start -->
    <div class="pc-mob-header pc-header">
        <div class="pcm-toolbar">
            <a href="#!" class="pc-head-link" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <!-- [ navigation menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="#" class="b-brand">
                    <img src="../../assets/img/localoo.png" alt="Localoo" class="logo" style="max-height: 115px;">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="../index.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="usersList.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Users List</span>
                        </a>
                    </li>

                    <li class="pc-item active">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                            <span class="pc-mtext">Add User</span>
                        </a>
                    </li>

                    <!-- Nouveaux boutons de gestion - version simplifiée -->
                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-alert-circle"></i></span>
                            <span class="pc-mtext">Reclamation</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-gift"></i></span>
                            <span class="pc-mtext">Bon Plans</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-star"></i></span>
                            <span class="pc-mtext">Review</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-messages"></i></span>
                            <span class="pc-mtext">Forum</span>
                        </a>
                    </li>

                    <!-- Separator -->
                    <li class="pc-item pc-caption">
                      
                    </li>

                    <!-- Éléments désactivés -->
                    <li class="pc-item disabled">
                        <a href="#!" class="pc-link" style="pointer-events: none; opacity: 0.6;">
                            <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                            <span class="pc-mtext">Events</span>
                            <span class="pc-arrow"></span>
                        </a>
                    </li>

                    <li class="pc-item disabled">
                        <a href="#!" class="pc-link" style="pointer-events: none; opacity: 0.6;">
                            <span class="pc-micon"><i class="ti ti-briefcase"></i></span>
                            <span class="pc-mtext">Emploi</span>
                            <span class="pc-arrow"></span>
                        </a>
                    </li>

                    <li class="pc-item disabled">
                        <a href="#!" class="pc-link" style="pointer-events: none; opacity: 0.6;">
                            <span class="pc-micon"><i class="ti ti-bell"></i></span>
                            <span class="pc-mtext">Notifications</span>
                            <span class="pc-arrow"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="pc-container">
        <div class="pc-content">
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>Add New User</h5>
                                    <p class="text-muted mb-0">Create a new user account</p>
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
                                        <input type="text" class="form-control" name="nom" required>
                                        <div class="invalid-feedback">Please enter first name</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="prenom" required>
                                        <div class="invalid-feedback">Please enter last name</div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                        <div class="invalid-feedback">Please enter a valid email</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" class="form-control" name="tel" pattern="[0-9]{8}" required>
                                        <div class="invalid-feedback">Please enter an 8-digit phone number</div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                        <div class="invalid-feedback">Please enter a password</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Role</label>
                                        <select class="form-select" name="role" required>
                                            <option value="">Select Role</option>
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a role</div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        <i class="ti ti-user-plus me-2"></i>Add User
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
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.needs-validation');
        
        // Regular expressions for validation
        const patterns = {
            name: /^[a-zA-ZÀ-ÿ\s'-]+$/,  // Lettres, espaces, tirets et apostrophes uniquement
            email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            phone: /^[0-9]{8}$/,
            // Ajout du pattern pour le mot de passe
            password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
        };

        // Messages d'erreur personnalisés
        const errorMessages = {
            password: 'Password must contain at least 8 characters, including uppercase, lowercase, number and special character',
            role: 'Please select a role'
        };

        // Input event listeners for real-time validation
        form.querySelectorAll('input[name="nom"], input[name="prenom"]').forEach(input => {
            // Empêcher la saisie de chiffres
            input.addEventListener('keypress', function(e) {
                if (!patterns.name.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
                    e.preventDefault();
                }
            });

            // Validation lors de la saisie
            input.addEventListener('input', function() {
                validateField(this, patterns.name, 'This field should contain only letters');
            });
        });

        // Email validation
        const emailInput = form.querySelector('input[name="email"]');
        emailInput.addEventListener('input', function() {
            validateField(this, patterns.email, 'Please enter a valid email address');
        });

        // Phone number validation
        const phoneInput = form.querySelector('input[name="tel"]');
        phoneInput.addEventListener('input', function(e) {
            // Limiter à 8 chiffres
            if (this.value.length > 8) {
                this.value = this.value.slice(0, 8);
            }
            // Ne permettre que les chiffres
            this.value = this.value.replace(/\D/g, '');
            validateField(this, patterns.phone, 'Phone number must be exactly 8 digits');
        });

        // Password validation
        const passwordInput = form.querySelector('input[name="password"]');
        const passwordContainer = passwordInput.parentElement;

        // Créer l'élément de force du mot de passe
        const passwordStrength = document.createElement('div');
        passwordStrength.className = 'password-strength';
        passwordStrength.innerHTML = '<div class="password-strength-bar"></div>';
        passwordInput.insertAdjacentElement('afterend', passwordStrength);

        // Créer la liste des exigences de mot de passe
        const passwordRequirements = document.createElement('div');
        passwordRequirements.className = 'password-requirements';
        passwordRequirements.innerHTML = `
            <div class="requirement" data-requirement="length"><i class="ti ti-circle"></i> Au moins 8 caractères</div>
            <div class="requirement" data-requirement="lowercase"><i class="ti ti-circle"></i> Au moins une minuscule</div>
            <div class="requirement" data-requirement="uppercase"><i class="ti ti-circle"></i> Au moins une majuscule</div>
            <div class="requirement" data-requirement="number"><i class="ti ti-circle"></i> Au moins un chiffre</div>
            <div class="requirement" data-requirement="special"><i class="ti ti-circle"></i> Au moins un caractère spécial</div>
        `;
        passwordContainer.classList.add('password-container');
        passwordContainer.appendChild(passwordRequirements);

        // Évaluer la force du mot de passe
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = passwordStrength.querySelector('.password-strength-bar');
            
            // Afficher la barre de force
            passwordStrength.style.display = 'block';
            
            // Vérifier les exigences
            const requirements = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[@$!%*?&]/.test(password)
            };
            
            // Mettre à jour les indicateurs d'exigence
            Object.keys(requirements).forEach(req => {
                const reqElement = passwordRequirements.querySelector(`[data-requirement="${req}"]`);
                reqElement.classList.toggle('met', requirements[req]);
                reqElement.classList.toggle('not-met', !requirements[req]);
                reqElement.querySelector('i').className = requirements[req] ? 'ti ti-check text-success' : 'ti ti-circle';
            });
            
            // Calculer le score
            let strength = 0;
            Object.values(requirements).forEach(met => {
                if (met) strength++;
            });
            
            // Mettre à jour la barre de force
            strengthBar.className = 'password-strength-bar';
            if (password.length === 0) {
                strengthBar.style.width = '0';
            } else if (strength <= 2) {
                strengthBar.classList.add('weak');
            } else if (strength === 3) {
                strengthBar.classList.add('medium');
            } else if (strength === 4) {
                strengthBar.classList.add('strong');
            } else {
                strengthBar.classList.add('very-strong');
            }
        });

        // Role validation
        const roleSelect = form.querySelector('select[name="role"]');
        roleSelect.addEventListener('change', function() {
            if (!this.value) {
                showError(this, errorMessages.role);
            } else {
                clearError(this);
            }
        });

        // Form submission validation
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // Validate all required fields
            form.querySelectorAll('input[required], select[required]').forEach(field => {
                if (field.name === 'nom' || field.name === 'prenom') {
                    if (!patterns.name.test(field.value)) {
                        isValid = false;
                        showError(field, 'This field should contain only letters');
                    }
                } else if (field.name === 'email') {
                    if (!patterns.email.test(field.value)) {
                        isValid = false;
                        showError(field, 'Please enter a valid email address');
                    }
                } else if (field.name === 'tel') {
                    if (!patterns.phone.test(field.value)) {
                        isValid = false;
                        showError(field, 'Phone number must be exactly 8 digits');
                    }
                } else if (field.name === 'password') {
                    if (!patterns.password.test(field.value)) {
                        isValid = false;
                        showError(field, errorMessages.password);
                    }
                } else if (field.name === 'role') {
                    if (!field.value) {
                        isValid = false;
                        showError(field, errorMessages.role);
                    }
                } else if (!field.value.trim()) {
                    isValid = false;
                    showError(field, 'This field is required');
                }
            });

            if (!isValid) {
                event.preventDefault();
                event.stopPropagation();
            }
        });

        // Utility functions
        function validateField(field, pattern, errorMessage) {
            if (!pattern.test(field.value)) {
                showError(field, errorMessage);
                return false;
            } else {
                clearError(field);
                return true;
            }
        }

        function showError(field, message) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            const feedback = field.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = message;
                feedback.style.transform = 'translateY(0)';
                feedback.style.opacity = '1';
            }
        }

        function clearError(field) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }

        // Améliorer les animations des messages d'erreur
        document.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.style.transition = 'all 0.3s ease';
            feedback.style.transform = 'translateY(-5px)';
            feedback.style.opacity = '0';
        });

        // Ajouter des transitions fluides pour les états de focus
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transition = 'border-color 0.3s ease, box-shadow 0.3s ease';
            });
        });

        // Animations pour le bouton de soumission
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });
    </script>
</body>
</html>