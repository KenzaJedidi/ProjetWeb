<?php
session_start();
require_once '../../vendor/autoload.php';
require_once '../../Controller/userC.php';

try {
    // Load Google configuration
    $google_config = require_once '../../config/google-config.php';
    
    $client = new Google\Client();
    $client->setClientId($google_config['client_id']);
    $client->setClientSecret($google_config['client_secret']);
    $client->setRedirectUri($google_config['redirect_uri']);
    
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);
        
        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        
        $email = $google_account_info->getEmail();
        $name = $google_account_info->getName();
        
        // Check if email is in allowed admin list
        if (!in_array($email, $google_config['allowed_admin_emails'])) {
            $_SESSION['error'] = 'This Google account is not authorized for admin access.';
            header('Location: login.php');
            exit();
        }
        
        $userC = new userC();
        $user = $userC->getUserByEmail($email);
        
        if (!$user) {
            // Create new admin user
            $names = explode(' ', $name);
            $firstName = $names[0];
            $lastName = isset($names[1]) ? $names[1] : '';
            
            $newUser = new User(
                null,
                $firstName,
                $lastName,
                $email,
                password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
                'admin', // Set role as admin
                '',
                null,
                date('Y-m-d H:i:s')
            );
            
            $userId = $userC->addUser($newUser);
            if (!$userId) {
                throw new Exception('Failed to create admin account');
            }
            
            $user = $userC->getUserById($userId);
        }
        
        // Verify user is an admin
        if ($user->getRole() !== 'admin') {
            $_SESSION['error'] = 'This account does not have admin privileges.';
            header('Location: login.php');
            exit();
        }
        
        // Set admin session
        $_SESSION['admin'] = [
            'id' => $user->getIdUser(),
            'nom' => $user->getNom(),
            'email' => $user->getEmail(),
            'role' => 'admin',
            'last_login' => date('Y-m-d H:i:s'),
            'login_method' => 'google'
        ];
        
        // Log successful login
        error_log("Admin login successful via Google: " . $email);
        
        header('Location: index.php');
        exit();
        
    } else {
        throw new Exception('No authorization code received from Google');
    }
    
} catch (Exception $e) {
    error_log("Google authentication error: " . $e->getMessage());
    $_SESSION['error'] = 'Authentication failed: ' . $e->getMessage();
    header('Location: login.php');
    exit();
}