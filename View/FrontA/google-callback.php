<?php
session_start();
require_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/../../Controller/userC.php');
include_once(__DIR__ . '/../../Model/user.php');

// Load Google configuration
$google_config = require_once(__DIR__ . '/../../config/google-config.php');

try {
    $client = new Google\Client();
    $client->setClientId($google_config['client_id']);
    $client->setClientSecret($google_config['client_secret']);
    $client->setRedirectUri($google_config['redirect_uri_front']); // New redirect URI for front
    $client->addScope('email');
    $client->addScope('profile');
    
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);
        
        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        
        $email = $google_account_info->getEmail();
        $name = $google_account_info->getName();
        
        $userC = new userC();
        $user = $userC->getUserByEmail($email);
        
        if (!$user) {
            // Create new user with role 'client'
            $names = explode(' ', $name);
            $firstName = $names[0];
            $lastName = isset($names[1]) ? $names[1] : '';
            
            $newUser = new User(
                null,
                $firstName,
                $lastName,
                $email,
                password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
                'client', // Default role for Google sign-ins
                '',
                null,
                date('Y-m-d H:i:s')
            );
            
            $userId = $userC->addUser($newUser);
            if (!$userId) {
                throw new Exception('Failed to create user account');
            }
            
            $user = $userC->getUserById($userId);
        }
        
        // Set user session
        $_SESSION['user'] = [
            'id' => $user->getIdUser(),
            'nom' => $user->getNom(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'last_login' => date('Y-m-d H:i:s'),
            'login_method' => 'google'
        ];
        
        // Redirect based on role
        switch($user->getRole()) {
            case 'admin':
                header('Location: ../admin/index.php');
                break;
            case 'client':
            default:
                header('Location: index.php');
                break;
        }
        exit();
        
    } else {
        throw new Exception('No authorization code received from Google');
    }
    
} catch (Exception $e) {
    error_log("Google authentication error: " . $e->getMessage());
    $_SESSION['error'] = 'Authentication failed: ' . $e->getMessage();
    header('Location: signin.php');
    exit();
}