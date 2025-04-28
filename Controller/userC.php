<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/amal/config.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/amal/Model/user.php";

class userC {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    public function addUser(User $user) {
        try {            
            $query = "INSERT INTO user (nom, prenom, email, password, role, tel, profile_picture) 
                      VALUES (:nom, :prenom, :email, :password, :role, :tel, :profile_picture)";
            
            $stmt = $this->pdo->prepare($query);
            
           
            $profilePicture = $user->getProfilePicture();
            if ($profilePicture && file_exists($profilePicture)) {
                $profilePicture = file_get_contents($profilePicture);
            }
            
            $stmt->execute([
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword(),
                ':role' => $user->getRole(),
                ':tel' => $user->getTel(),
                ':profile_picture' => $profilePicture
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error adding user: " . $e->getMessage());
            return false;
        }
    }
    public function listUsers() {
        try {
            $query = "SELECT * FROM user";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = new User(
                    $row['id_user'],
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    $row['password'],
                    $row['role'],
                    $row['tel'],
                    $row['profile_picture'],
                    $row['created_at'],
                    (bool)$row['is_banned'] // Ensure boolean value
                );
            }
            return $users;
        } catch (PDOException $e) {
            error_log("Error listing users: " . $e->getMessage());
            return [];
        }
    }

    public function getUserById($id) {
        try {
            $query = "SELECT * FROM user WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? new User(
                $row['id_user'],
                $row['nom'],
                $row['prenom'],
                $row['email'],
                $row['password'],
                $row['role'],
                $row['tel'],
                $row['profile_picture'],
                $row['created_at'],
                (bool)$row['is_banned'] // Ensure boolean value
            ) : null;
        } catch (PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            return null;
        }
    }

    public function updateUser($user) {
        try {
            $pdo = config::getConnexion();
            
            if ($user->getPassword()) {
                $query = "UPDATE user SET nom = :nom, prenom = :prenom, email = :email, 
                         password = :password, role = :role, tel = :tel 
                         WHERE id_user = :id";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(':password', $user->getPassword());
            } else {
                $query = "UPDATE user SET nom = :nom, prenom = :prenom, email = :email, 
                         role = :role, tel = :tel 
                         WHERE id_user = :id";
                $stmt = $pdo->prepare($query);
            }
            
            $stmt->bindValue(':nom', $user->getNom());
            $stmt->bindValue(':prenom', $user->getPrenom());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':role', $user->getRole());
            $stmt->bindValue(':tel', $user->getTel());
            $stmt->bindValue(':id', $user->getIdUser());
            
            return $stmt->execute();
        } catch(Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updatePassword($userId, $newPassword) {
        try {
          
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $query = "UPDATE user SET password = :password WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                ':id' => $userId,
                ':password' => $hashedPassword
            ]);
        } catch (PDOException $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }

    public function updateProfilePicture($userId, $imageData) {
        try {
            $query = "UPDATE user SET profile_picture = :picture WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':picture', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating profile picture: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($id) {
        try {
            $query = "DELETE FROM user WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }

    public function verifyLogin($email, $password) {
        try {
            $query = "SELECT * FROM user WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email' => $email]);
            
            if ($row = $stmt->fetch()) {
                $user = new User(
                    $row['id_user'],
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    $row['password'],
                    $row['role'],
                    $row['tel'],
                    $row['profile_picture'],
                    $row['created_at'],
                    $row['is_banned']
                );
                
                if ($user->getIsBanned()) {
                    return 'banned';
                }
                
                if (password_verify($password, $user->getPassword())) {
                    return $user;
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("Login verification error: " . $e->getMessage());
            return false;
        }
    }

    public function emailExists($email) {
        try {
            $query = "SELECT COUNT(*) FROM user WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':email' => $email]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking email: " . $e->getMessage());
            return false;
        }
    }

    public function phoneExists($tel) {
        try {
            $query = "SELECT COUNT(*) FROM user WHERE tel = :tel";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':tel' => $tel]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking phone: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByPhone($tel) {
        try {
            $query = "SELECT * FROM user WHERE tel = :tel";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':tel' => $tel]);
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? new User(
                $row['id_user'],
                $row['nom'],
                $row['prenom'],
                $row['email'],
                $row['password'],
                $row['role'],
                $row['tel'],
                $row['profile_picture']
            ) : null;
        } catch (PDOException $e) {
            error_log("Error getting user by phone: " . $e->getMessage());
            return null;
        }
    }

    public function countUsers() {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT COUNT(*) FROM user";
            return $pdo->query($query)->fetchColumn();
        } catch(Exception $e) {
            return 0;
        }
    }

    public function getUserByEmail($email) {
        try {
            $query = "SELECT * FROM user WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email' => $email]);
            
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($userData) {
                return new User(
                    $userData['id_user'],
                    $userData['nom'],
                    $userData['prenom'],
                    $userData['email'],
                    $userData['password'],
                    $userData['role'],
                    $userData['tel']
                );
            }
            return null;
        } catch (Exception $e) {
            error_log("Error in getUserByEmail: " . $e->getMessage());
            return null;
        }
    }

    public function generateVerificationCode() {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function saveVerificationCode($userId, $code) {
        try {
            $query = "UPDATE user SET verification_code = :code WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                ':code' => $code,
                ':id' => $userId
            ]);
        } catch (PDOException $e) {
            error_log("Error saving verification code: " . $e->getMessage());
            return false;
        }
    }

    public function verifyCode($userId, $code) {
        try {
            $query = "SELECT * FROM user WHERE id_user = :id AND verification_code = :code";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':id' => $userId,
                ':code' => $code
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error verifying code: " . $e->getMessage());
            return false;
        }
    }

    public function clearVerificationCode($userId) {
        try {
            $query = "UPDATE user SET verification_code = NULL WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([':id' => $userId]);
        } catch (PDOException $e) {
            error_log("Error clearing verification code: " . $e->getMessage());
            return false;
        }
    }

    public function getUserStatistics() {
        $pdo = config::getConnexion();
        
        return [
            'total_users' => $this->countUsers(),
            'active_users' => $this->countUsersByStatus('active'),
            'roles_distribution' => $this->getRolesDistribution(),
            'recent_registrations' => $this->getRecentRegistrations(),
            'monthly_signups' => $this->getMonthlySignups(),
            'verification_status' => $this->getVerificationStatus()
        ];
    }

    private function countUsersByStatus($status) {
        $pdo = config::getConnexion();
        $query = "SELECT COUNT(*) FROM user WHERE status = :status";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['status' => $status]);
        return $stmt->fetchColumn();
    }

    private function getRolesDistribution() {
        $pdo = config::getConnexion();
        $query = "SELECT role, COUNT(*) as count FROM user GROUP BY role";
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getRecentRegistrations() {
        $pdo = config::getConnexion();
        $query = "SELECT * FROM user ORDER BY created_at DESC LIMIT 5";
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMonthlySignups() {
        $pdo = config::getConnexion();
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                 FROM user 
                 GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                 ORDER BY month DESC 
                 LIMIT 12";
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getVerificationStatus() {
        $pdo = config::getConnexion();
        $query = "SELECT verified, COUNT(*) as count FROM user GROUP BY verified";
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Alternative implementation without status column

    public function countActiveUsers() {
        // Consider all users as active for now
        return $this->countUsers();
    }

    public function countBannedUsers() {
        // Return 0 if you don't have banned users functionality yet
        return 0;
    }

    public function countNewUsersToday() {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT COUNT(*) FROM user 
                     WHERE DATE(created_at) = CURRENT_DATE()";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Error counting new users today: " . $e->getMessage());
            return 0;
        }
    }

    public function getFilteredUsers($searchTerm = '', $roleFilter = '', $sortBy = 'name') {
        try {
            $pdo = config::getConnexion();
            $conditions = [];
            $params = [];
            
            // Base query
            $query = "SELECT * FROM user WHERE 1=1";
            
            // Add search condition
            if (!empty($searchTerm)) {
                $conditions[] = "(nom LIKE :search OR prenom LIKE :search OR email LIKE :search OR tel LIKE :search)";
                $params[':search'] = "%$searchTerm%";
            }
            
            // Add role filter
            if (!empty($roleFilter)) {
                $conditions[] = "role = :role";
                $params[':role'] = $roleFilter;
            }
            
            // Combine conditions
            if (!empty($conditions)) {
                $query .= " AND " . implode(" AND ", $conditions);
            }
            
            // Add sorting
            switch ($sortBy) {
                case 'name':
                    $query .= " ORDER BY nom ASC";
                    break;
                case 'email':
                    $query .= " ORDER BY email ASC";
                    break;
                case 'recent':
                    $query .= " ORDER BY created_at DESC";
                    break;
                case 'oldest':
                    $query .= " ORDER BY created_at ASC";
                    break;
                default:
                    $query .= " ORDER BY nom ASC";
            }
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = new User(
                    $row['id_user'],
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    $row['password'],
                    $row['role'],
                    $row['tel'],
                    $row['profile_picture'] ?? null
                );
            }
            
            return $users;
            
        } catch (Exception $e) {
            error_log("Error in getFilteredUsers: " . $e->getMessage());
            return [];
        }
    }

    public function isAdminEmail($email) {
        try {
            $query = "SELECT role FROM user WHERE email = :email AND role = 'admin'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email' => $email]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error checking admin status: " . $e->getMessage());
            return false;
        }
    }

    public function addGoogleUser($email, $name, $role = 'user') {
        try {
            $names = explode(' ', $name);
            $firstName = $names[0];
            $lastName = isset($names[1]) ? $names[1] : '';
            
            $query = "INSERT INTO user (nom, prenom, email, password, role) 
                     VALUES (:nom, :prenom, :email, :password, :role)";
            
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                'nom' => $firstName,
                'prenom' => $lastName,
                'email' => $email,
                'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                'role' => $role
            ]);
        } catch (Exception $e) {
            error_log("Error adding Google user: " . $e->getMessage());
            return false;
        }
    }

    public function updateUserRole($userId, $newRole) {
        try {
            $query = "UPDATE user SET role = :role WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                'role' => $newRole,
                'id' => $userId
            ]);
        } catch (Exception $e) {
            error_log("Error updating user role: " . $e->getMessage());
            return false;
        }
    }

    public function handleGoogleLogin($email, $name) {
        try {
            // Check if user exists
            $user = $this->getUserByEmail($email);
            
            if (!$user) {
                // Create new user with admin role if email is in allowed list
                $google_config = require dirname(__DIR__) . '/config/google-config.php';
                
                if (!in_array($email, $google_config['allowed_admin_emails'])) {
                    throw new Exception('Email not authorized for admin access');
                }
                
                $names = explode(' ', $name);
                $firstName = $names[0];
                $lastName = isset($names[1]) ? $names[1] : '';
                
                $newUser = new User(
                    null,
                    $firstName,
                    $lastName,
                    $email,
                    password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
                    'admin',
                    '',
                    null,
                    date('Y-m-d H:i:s')
                );
                
                $userId = $this->addUser($newUser);
                if (!$userId) {
                    throw new Exception('Failed to create admin account');
                }
                
                $user = $this->getUserById($userId);
            }
            
            // Verify user is an admin
            if ($user->getRole() !== 'admin') {
                throw new Exception('Account does not have admin privileges');
            }
            
            return $user;
            
        } catch (Exception $e) {
            error_log("Google login error: " . $e->getMessage());
            return null;
        }
    }

    public function toggleBanUser($userId) {
        try {
            // Get current ban status
            $query = "SELECT is_banned FROM user WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $userId]);
            $currentStatus = $stmt->fetchColumn();

            // Toggle the status
            $newStatus = $currentStatus ? 0 : 1;
            
            $query = "UPDATE user SET is_banned = :status WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            $success = $stmt->execute([
                ':status' => $newStatus,
                ':id' => $userId
            ]);

            if ($success) {
                return [
                    'success' => true,
                    'isBanned' => (bool)$newStatus,
                    'message' => $newStatus ? 'User has been banned' : 'User has been unbanned'
                ];
            }
            return ['success' => false, 'message' => 'Failed to update user status'];
        } catch (PDOException $e) {
            error_log("Error toggling ban status: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred'];
        }
    }
}
?>