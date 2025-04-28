<?php
session_start();
include_once '../Model/user.php';
include_once '../Controller/userC.php';

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

<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form method="POST" >
            <h1>Create Account</h1>
            <input type="text" name="nom" placeholder="Name" required />
            <input type="text" name="prenom" placeholder="Last Name" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="text" name="tel" placeholder="Phone (8 digits)" required pattern="\d{8}" />
            <input type="password" name="password" placeholder="Password" required />
            
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
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>