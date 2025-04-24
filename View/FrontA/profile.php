<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getSessionValue($key, $default = '') {
    return isset($_SESSION['user'][$key]) ? $_SESSION['user'][$key] : $default;
}

function safeEcho($value) {
    echo htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function getImageData($userId, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT profile_picture FROM user WHERE id_user = ?");
        $stmt->execute([$userId]);
        $imageData = $stmt->fetchColumn();
        if ($imageData) {
            
            return base64_encode($imageData);
        }
    } catch (PDOException $e) {
        error_log("Error fetching image data: " . $e->getMessage());
    }
    return null;
}

session_start();
include_once(__DIR__ . '/../../Model/user.php');
include_once(__DIR__ . '/../../Controller/userC.php');
include_once(__DIR__ . '/../../config.php');


echo "<!-- Debug: Profile picture status: ";
var_dump(isset($_SESSION['user']['profile_picture']));
echo " -->";


if (isset($_SESSION['user']['id'])) {
    if (!isset($_SESSION['user']['profile_picture']) || $_SESSION['user']['profile_picture'] === null) {
        $_SESSION['user']['profile_picture'] = getImageData(
            $_SESSION['user']['id'], 
            config::getConnexion()
        );
    }
}

$error = "";
$userC = new userC();


if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    try {
        $imageData = file_get_contents($_FILES['profile_picture']['tmp_name']);
        
       
        if ($userC->updateProfilePicture($_SESSION['user']['id'], $imageData)) {
         
            $_SESSION['user']['profile_picture'] = base64_encode($imageData);
            header('Location: profile.php?updated=1');
            exit();
        } else {
            $error = '<div class="alert alert-danger">Failed to update profile picture.</div>';
        }
    } catch (Exception $e) {
        error_log("Error uploading image: " . $e->getMessage());
        $error = '<div class="alert alert-danger">Error uploading image.</div>';
    }
}


if (isset($_POST['update_profile'])) {
    if (
        !empty($_POST["nom"]) &&
        !empty($_POST["prenom"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["tel"])
    ) {
        $newEmail = $_POST['email'];

       
        $existingUser = $userC->getUserByEmail($newEmail);
        if ($existingUser && $existingUser->getIdUser() != $_SESSION['user']['id']) {
            $error = '<div class="alert alert-danger">Email already exists. Please choose a different email.</div>';
        } else {
           
            $password = !empty($_POST["password"]) ? $_POST["password"] : $_SESSION['user']['password'];

            $user = new User(
                $_SESSION['user']['id'],
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['email'],
                $password,
                $_SESSION['user']['role'],
                $_POST['tel']
            );

            if ($userC->updateUser($user)) {
                
                if (!empty($_POST["password"])) {
                    $userC->updatePassword($user->getIdUser(), $_POST["password"]);
                }
                
               
                $_SESSION['user'] = [
                    'id' => $user->getIdUser(),
                    'nom' => $user->getNom(),
                    'prenom' => $user->getPrenom(),
                    'email' => $user->getEmail(),
                    'tel' => $user->getTel(),
                    'role' => $user->getRole(),
                    'password' => $user->getPassword()
                ];
                
                
                header("Location: profile.php?updated=1");
                exit();
            } else {
                $error = '<div class="alert alert-danger">Error updating profile. Please try again.</div>';
            }
        }
    } else {
        $error = '<div class="alert alert-danger">Please fill in all required fields.</div>';
    }
}


if (isset($_GET['updated'])) {
    $error = '<div class="alert alert-success">Profile updated successfully!</div>';
}
?>
<!doctype html>
<html class="no-js" lang="en">

    <head>
    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
      
        
        <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&display=swap" rel="stylesheet">
        
     
        <title>User Profile</title>

      
        <link rel="shortcut icon" type="image/icon" href="assets/logo/favicon.png"/>
       
      
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">

       
        <link rel="stylesheet" href="assets/css/linearicons.css">

        <!--animate.css-->
        <link rel="stylesheet" href="assets/css/animate.css">

        <!--flaticon.css-->
        <link rel="stylesheet" href="assets/css/flaticon.css">

        <!--slick.css-->
        <link rel="stylesheet" href="assets/css/slick.css">
        <link rel="stylesheet" href="assets/css/slick-theme.css">
        
        <!--bootstrap.min.css-->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        
        <!-- bootsnav -->
        <link rel="stylesheet" href="assets/css/bootsnav.css">	
        
        <!--style.css-->
        <link rel="stylesheet" href="assets/css/style.css">
        
        <!--responsive.css-->
        <link rel="stylesheet" href="assets/css/responsive.css">

        <style>
            :root {
                --primary: #2563eb;
                --primary-dark: #1d4ed8;
                --secondary: #7dd3fc;
                --background: #f0f9ff;
                --surface: #ffffff;
                --text: #1e293b;
                --text-light: #64748b;
                --success: #059669;
                --danger: #dc2626;
            }

            .profile-area {
                min-height: 100vh;
                background: var(--background);
                padding: 4rem 0;
                position: relative;
            }

            .profile-area::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 400px;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                clip-path: polygon(0 0, 100% 0, 100% 60%, 0% 100%);
                z-index: 0;
            }

            .profile-card {
                position: relative;
                z-index: 1;
                background: var(--surface);
                border-radius: 24px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
                overflow: hidden;
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                display: grid;
                grid-template-columns: 320px 1fr;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                transform: translateY(0);
            }

            .profile-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
            }

            .profile-sidebar {
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                padding: 3rem 2rem;
                position: relative;
                overflow: hidden;
            }

            .profile-sidebar::before {
                content: '';
                position: absolute;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, var(--secondary) 0%, transparent 70%);
                top: -300px;
                right: -300px;
                opacity: 0.1;
                animation: pulse 8s infinite;
            }

            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 0.1; }
                50% { transform: scale(1.2); opacity: 0.15; }
            }

            .user-avatar {
                width: 140px;
                height: 140px;
                margin: 0 auto 2rem;
                position: relative;
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }

            .user-avatar::before {
                content: '';
                position: absolute;
                inset: -3px;
                border-radius: 50%;
                padding: 3px;
                background: linear-gradient(135deg, var(--secondary) 0%, transparent 50%);
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                animation: spin 4s linear infinite;
            }

            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .user-avatar i {
                font-size: 3.5rem;
                color: var(--surface);
                background: rgba(255, 255, 255, 0.1);
                width: 100%;
                height: 100%;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(5px);
            }

            .user-avatar .avatar-upload-label {
                position: relative;
                cursor: pointer;
                display: block;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                overflow: hidden;
            }

            .avatar-upload-label .profile-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .avatar-upload-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: white;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .avatar-upload-label:hover .avatar-upload-overlay {
                opacity: 1;
            }

            .avatar-upload-overlay i {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .avatar-upload-overlay span {
                font-size: 0.875rem;
            }

            .user-info {
                text-align: center;
                color: var(--surface);
            }

            .user-name {
                font-size: 1.8rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                background: linear-gradient(to right, var(--surface) 0%, rgba(255,255,255,0.8) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .profile-menu {
                margin-top: 3rem;
            }

            .profile-menu a {
                display: flex;
                align-items: center;
                padding: 1rem 1.5rem;
                color: var(--surface);
                border-radius: 12px;
                margin-bottom: 0.5rem;
                transition: all 0.3s ease;
                background: rgba(255, 255, 255, 0.1);
            }

            .profile-menu a:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateX(5px);
            }

            .profile-menu i {
                margin-right: 1rem;
                font-size: 1.2rem;
                transition: transform 0.3s ease;
            }

            .profile-menu a:hover i {
                transform: scale(1.1);
            }

            .profile-content {
                padding: 3rem;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-control {
                background: #f8fafc;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                padding: 0.75rem 1rem;
                font-size: 1rem;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
                background: var(--surface);
            }

            .btn {
                padding: 0.75rem 1.5rem;
                border-radius: 12px;
                font-weight: 600;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                min-width: 160px;
            }

            .btn i {
                font-size: 1rem;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
            }

            .btn-primary, .btn-danger {
                color: white;
                border: none;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            }

            .btn-danger {
                background: linear-gradient(135deg, var(--danger) 0%, #991b1b 100%);
            }

            .btn-group {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .alert {
                border-radius: 12px;
                padding: 1rem 1.5rem;
                margin-bottom: 2rem;
                animation: slideIn 0.5s ease-out;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 992px) {
                .profile-card {
                    grid-template-columns: 1fr;
                }
                
                .profile-sidebar {
                    padding: 2rem;
                }
            }

            /* Add this CSS to style error messages and invalid inputs */
            .error-messages {
                color: var(--danger);
                font-size: 14px;
                margin-bottom: 10px;
                list-style-type: none;
                padding: 0;
            }

            .error-messages li {
                margin-bottom: 5px;
            }

            .input-error {
                border: 2px solid var(--danger);
                background-color: #ffe4e6;
            }

            .field-error {
                color: var(--danger);
                font-size: 12px;
                margin-top: 5px;
                margin-bottom: 10px;
            }

            .form-group {
                position: relative;
                margin-bottom: 20px;
            }
        </style>
    </head>
    
    <body>
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->
        
        <!-- Original Header -->
        <header id="header-top" class="header-top">
            <ul>
                <li>
                    <div class="header-top-left">
                        <ul>
                            <li class="select-opt">
                                <select name="language" id="language">
                                    <option value="default">EN</option>
                                    <option value="Bangla">BN</option>
                                    <option value="Arabic">AB</option>
                                </select>
                            </li>
                            <li class="select-opt">
                                <select name="currency" id="currency">
                                    <option value="usd">USD</option>
                                    <option value="euro">Euro</option>
                                    <option value="bdt">BDT</option>
                                </select>
                            </li>
                            <li class="select-opt">
                                <a href="#"><span class="lnr lnr-magnifier"></span></a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="head-responsive-right pull-right">
                    <div class="header-top-right">
                        <ul>
                            <li class="header-top-contact">
                                +1 222 777 6565
                            </li>
                            <li class="header-top-contact">
                                <?php if (empty($_SESSION['user'])): ?>
                                    <a href="signin.php" class="btn">Log In | Sign Up</a>
                                <?php else: ?>
                                    <a href="profile.php" class="btn"><?php echo htmlspecialchars($_SESSION['user']['nom']); ?></a>
                                    <a href="logout.php" class="btn">Log Out</a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>                  
        </header>
        
        
    
        <section class="top-area">
            <div class="header-area">
              
            <nav class="navbar navbar-default bootsnav navbar-sticky navbar-scrollspy" data-minus-value-desktop="70" data-minus-value-mobile="55" data-speed="1000">
                    <div class="container">
                       
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                                <i class="fa fa-bars"></i>
                            </button>
                            
                        </div>

                        
                        <div class="footer-menu">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="navbar-header">
                                <a class="navbar-brand" href="index.html">Loca<span>loo</span></a>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <ul class="footer-menu-item">
                                <li class="scroll"><a href="#works">how it works</a></li>
                                <li class="scroll"><a href="#explore">explore</a></li>
                                <li class="scroll"><a href="#reviews">review</a></li>
                                <li class="scroll"><a href="#blog">blog</a></li>
                                <li class="scroll"><a href="#contact">contact</a></li>
                            </ul><!--/.nav -->
                        </div>
                    </div>
                </div>
                    </div>
                </nav>
            </div>
            <div class="clearfix"></div>
        </section>
        

       
        <section class="profile-area">
            <div class="container">
                <div class="profile-card">
                    <div class="profile-sidebar">
                        <div class="user-avatar">
                            <label for="profile_picture_input" class="avatar-upload-label">
                                <?php if (isset($_SESSION['user']['profile_picture']) && $_SESSION['user']['profile_picture'] !== null): ?>
                                    <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($_SESSION['user']['profile_picture']); ?>" 
                                         alt="Profile" class="profile-image">
                                <?php else: ?>
                                    <i class="fa fa-user"></i>
                                <?php endif; ?>
                                <div class="avatar-upload-overlay">
                                    <i class="fa fa-camera"></i>
                                    <span>Change Photo</span>
                                </div>
                            </label>
                            <input type="file" 
                                   id="profile_picture_input" 
                                   name="profile_picture" 
                                   accept="image/*" 
                                   form="profile-form"
                                   style="display: none;"
                                   onchange="submitProfilePicture(this);">
                        </div>
                        <div class="user-info">
                            <h2 class="user-name">
                                <?php 
                                $nom = isset($_SESSION['user']['nom']) ? $_SESSION['user']['nom'] : '';
                                $prenom = isset($_SESSION['user']['prenom']) ? $_SESSION['user']['prenom'] : '';
                                echo htmlspecialchars($nom . ' ' . $prenom); 
                                ?>
                            </h2>
                            <span class="user-role"><?php echo htmlspecialchars($_SESSION['user']['role']); ?></span>
                        </div>
                        <ul class="profile-menu">
                            <li class="active"><a href="profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                    <div class="profile-content">
                        <h2 class="section-title">Profile Settings</h2>
                        <?php if (!empty($error)) echo $error; ?>
                        
                        <form method="POST" action="profile.php" enctype="multipart/form-data" id="profile-form">
                            <input type="hidden" name="update_profile_picture" value="1">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" name="nom" class="form-control" 
                                       value="<?php safeEcho(getSessionValue('nom')); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="prenom" class="form-control" 
                                       value="<?php safeEcho(getSessionValue('prenom')); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?php safeEcho(getSessionValue('email')); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="tel" class="form-control" 
                                       value="<?php safeEcho(getSessionValue('tel')); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="passwordField" class="form-control" placeholder="New password">
                                    <div class="input-group-append">
                                        <span class="input-group-text" onclick="togglePassword()">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <small class="text-muted">Leave blank to keep current password</small>
                            </div>
                            
                            <div class="form-group" style="margin-top: 2.5rem;">
                                <div class="btn-group">
                                    <button type="submit" name="update_profile" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Profile
                                    </button>
                                    <a href="delete.php?id=<?php echo $_SESSION['user']['id']; ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('Are you sure you want to delete your account?')">
                                        <i class="fas fa-trash-alt"></i> Delete Account
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Original Footer -->
        <footer id="footer"  class="footer">
            <div class="container">
               
                <div class="hm-footer-copyright">
                    <div class="row">
                        <div class="col-sm-5">
                            <p>
                                &copy;copyright. designed and developed by <a href="https://www.themesine.com/">themesine</a>
                            </p><!--/p-->
                        </div>
                        <div class="col-sm-7">
                            <div class="footer-social">
                                <span><i class="fa fa-phone"> +1  (222) 777 8888</i></span>
                                <a href="#"><i class="fa fa-facebook"></i></a>    
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="scroll-Top">
                <div class="return-to-top">
                    <i class="fa fa-angle-up " id="scroll-top" data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to Top" aria-hidden="true"></i>
                </div>
            </div>
        </footer>
        
       
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/bootsnav.js"></script>
        <script src="assets/js/custom.js"></script>
        
        <script>
            function togglePassword() {
                const passwordField = document.getElementById('passwordField');
                const icon = document.querySelector('.input-group-text i');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }

            function submitProfilePicture(input) {
                if (input.files && input.files[0]) {
                    const form = document.getElementById('profile-form');
                   
                    const formData = new FormData();
                    formData.append('profile_picture', input.files[0]);
                    
                    
                     fetch('profile.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            window.location.reload();
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                }
            }
        </script>
        <script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('profile-form');

    // Fonction pour créer/mettre à jour le message d'erreur
    const showError = (input, message) => {
        // Supprimer l'ancien message d'erreur s'il existe
        const existingError = input.parentElement.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        // Créer et ajouter le nouveau message d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        input.parentElement.appendChild(errorDiv);
        input.classList.add('input-error');
    };

    // Fonction pour supprimer le message d'erreur
    const removeError = (input) => {
        const existingError = input.parentElement.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        input.classList.remove('input-error');
    };

    // Validation en temps réel pour chaque champ
    const validateField = (input, validationRules) => {
        input.addEventListener('input', () => {
            removeError(input);
            const value = input.value.trim();
            
            if (validationRules.required && value === '') {
                showError(input, validationRules.requiredMessage);
                return false;
            }
            
            if (validationRules.pattern && !validationRules.pattern.test(value)) {
                showError(input, validationRules.patternMessage);
                return false;
            }
            
            return true;
        });
    };

    // Règles de validation pour chaque champ
    const validationRules = {
        nom: {
            required: true,
            requiredMessage: 'Le prénom est requis',
            pattern: /^[a-zA-ZÀ-ÿ\s'-]{2,}$/,
            patternMessage: 'Le prénom doit contenir au moins 2 caractères et uniquement des lettres'
        },
        prenom: {
            required: true,
            requiredMessage: 'Le nom est requis',
            pattern: /^[a-zA-ZÀ-ÿ\s'-]{2,}$/,
            patternMessage: 'Le nom doit contenir au moins 2 caractères et uniquement des lettres'
        },
        email: {
            required: true,
            requiredMessage: 'L\'email est requis',
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            patternMessage: 'Veuillez entrer une adresse email valide'
        },
        tel: {
            required: true,
            requiredMessage: 'Le numéro de téléphone est requis',
            pattern: /^\d{8}$/,
            patternMessage: 'Le numéro doit contenir exactement 8 chiffres'
        },
        password: {
            pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
            patternMessage: 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial'
        }
    };

    // Appliquer la validation à chaque champ
    Object.keys(validationRules).forEach(fieldName => {
        const input = form.querySelector(`[name="${fieldName}"]`);
        if (input) {
            validateField(input, validationRules[fieldName]);
        }
    });

    // Validation lors de la soumission du formulaire
    form.addEventListener('submit', (event) => {
        let hasErrors = false;
        
        Object.keys(validationRules).forEach(fieldName => {
            const input = form.querySelector(`[name="${fieldName}"]`);
            if (!input) return;

            removeError(input);
            const value = input.value.trim();
            const rules = validationRules[fieldName];

            if (rules.required && value === '') {
                showError(input, rules.requiredMessage);
                hasErrors = true;
            } else if (rules.pattern && !rules.pattern.test(value)) {
                // Pour le mot de passe, ne valider que s'il n'est pas vide
                if (fieldName !== 'password' || value !== '') {
                    showError(input, rules.patternMessage);
                    hasErrors = true;
                }
            }
        });

        if (hasErrors) {
            event.preventDefault();
        }
    });
});
</script>
    </body>
</html>