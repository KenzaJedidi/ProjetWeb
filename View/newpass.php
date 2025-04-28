<?php
session_start();
include_once '../Model/user.php';
include_once '../Controller/userC.php';
$error = "";
$user = NULL;
$userC = new userC();

if (isset($_POST["pass"]) && isset($_GET['id'])) {
    if (!empty($_POST["pass"])) {
        if (strlen($_POST["pass"]) >= 8) {
            if ($userC->updatePassword($_GET['id'], $_POST["pass"])) {
                header('Location:signin.php');
                exit();
            } else {
                $error = 'Failed to update password. Please try again.';
            }
        } else {
            $error = 'Password must be at least 8 characters long.';
        }
    } else {
        $error = 'Please enter a new password.';
    }
}
?>

<link rel="stylesheet" href="css/css.css">

<div class="container" id="container">
    <div class="form-container sign-in-container">
        <form method="POST">
            <h1>Enter Your New Password</h1>

            <?php
            if (!empty($error)) {
                echo '<p style="color: red;">' . $error . '</p>';
            }
            ?>

            <input type="password" name="pass" placeholder="New Password" />
            <button type="submit">Change</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <h1>Welcome to Allergicare</h1>
                <p>Don't Have an account click here!</p>
                <a class="ghost" href="signup.php" id="signIn">Sign Up</a>
            </div>
        </div>
    </div>
</div>