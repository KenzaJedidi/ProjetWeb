<?php
session_start();
include_once '../Model/user.php';
include_once '../Controller/userC.php';

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

<div class="container" id="container">
    <div class="form-container sign-in-container">
        <form method="POST">
            <h1>Sign in</h1>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            
            <?php if ($errorMessage != ""): ?>
                <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
            
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