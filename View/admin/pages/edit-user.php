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
</body>
</html>