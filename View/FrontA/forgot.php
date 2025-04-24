<?php
session_start();
include_once __DIR__ . '/../../Model/user.php';
include_once __DIR__ . '/../../Controller/userC.php';
include_once __DIR__ . '/../../Controller/SmsService.php';

$error = "";
$success = "";
$userC = new userC();
$smsService = new SmsService();

if (isset($_POST["phone"])) {
    if (!empty($_POST["phone"])) {
        if (preg_match('/^[0-9]{8}$/', $_POST["phone"])) {
            $user = $userC->getUserByPhone($_POST["phone"]);
            if ($user) {
                // Generate verification code
                // $verificationCode = $userC->generateVerificationCode();
                
                // Save the code in database
                // if ($userC->saveVerificationCode($user->getIdUser(), $verificationCode)) {
                if ($userC->saveVerificationCode($user->getIdUser(), 1234)) {
                    // Send SMS
                    if ($smsService->sendVerificationCode($_POST["phone"], 1234)) {
                        header('Location:code.php?id=' . $user->getIdUser());
                        exit();
                    } else {
                        $error = 'Failed to send SMS. Please try again.';
                    }
                } else {
                    $error = 'System error. Please try again.';
                }
            } else {
                $error = 'Phone number not found in our records.';
            }
        } else {
            $error = 'Invalid phone number format. Please enter a valid 8-digit phone number.';
        }
    } else {
        $error = 'Missing phone number.';
    }
}
?>

<link rel="stylesheet" href="css/css.css">

<div class="container" id="container">
    <div class="form-container sign-in-container">
        <form method="POST">
            <h1>Enter your Phone</h1>

            <?php
            if (!empty($error)) {
                echo '<p style="color: red;">' . $error . '</p>';
            }
            ?>

            <input type="text" name="phone" placeholder="Phone" />
            <a href="forgot.php">Forgot your password?</a>
            <button type="submit">Send Code</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <h1>Welcome to Localoo</h1>
                <p>Don't Have an account click here!</p>
                <a class="ghost" href="signup.php" id="signIn">Sign Up</a>
            </div>
        </div>
    </div>
</div>
