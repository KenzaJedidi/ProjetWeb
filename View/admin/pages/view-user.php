<?php
session_start();
include_once '../../../Controller/userC.php';
include_once '../../../Model/user.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

$userC = new userC();
$user = null;

if (isset($_GET['id'])) {
    $user = $userC->getUserById($_GET['id']);
    if (!$user) {
        header('Location: usersList.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View User - Admin Dashboard</title>
    <?php include '../includes/head.php'; ?>
    <style>
        .user-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .detail-label {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #2c3e50;
            font-size: 1rem;
            font-weight: 500;
        }

        .avatar-lg {
            width: 120px;
            height: 120px;
            border-radius: 16px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #4680FF 0%, #6AA6FF 100%);
            color: white;
            font-size: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn {
            background: #edf2ff;
            color: #4680FF;
            border: none;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #4680FF;
            color: white;
            transform: translateX(-3px);
        }
    </style>
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="pc-container">
        <div class="pc-content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">User Details</h5>
                            <a href="usersList.php" class="btn back-btn">
                                <i class="ti ti-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar-lg mx-auto">
                                    <?php echo strtoupper(substr($user->getNom(), 0, 1)); ?>
                                </div>
                                <h4><?php echo htmlspecialchars($user->getNom() . ' ' . $user->getPrenom()); ?></h4>
                                <span class="badge bg-<?php echo $user->getRole() === 'admin' ? 'primary' : 'success'; ?>">
                                    <?php echo htmlspecialchars($user->getRole()); ?>
                                </span>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label">Email</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($user->getEmail()); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label">Phone</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($user->getTel()); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/scripts.php'; ?>
</body>
</html>