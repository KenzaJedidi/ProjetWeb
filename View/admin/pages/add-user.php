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
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    
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
        passwordInput.addEventListener('input', function() {
            validateField(this, patterns.password, errorMessages.password);
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
            }
        }

        function clearError(field) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
    });
    </script>
</body>
</html>