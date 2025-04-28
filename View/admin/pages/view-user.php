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

<?php $currentPage = 'users-list'; $isSubPage = true; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View User - Admin Dashboard</title>
    <?php include '../includes/head.php'; ?>
    <style>
        /* Card styling */
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
            border-radius: 15px;
        }

        .card-header {
            background: white;
            padding: 20px 25px;
            border-bottom: 1px solid #edf2f9;
        }

        .card-body {
            padding: 30px;
        }

        /* User details box styling */
        .user-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #edf2f9;
            transition: all 0.3s ease;
        }

        .user-details:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: #4680FF;
        }

        /* Text styling */
        .detail-label {
            color: #8392ab;
            font-size: 0.875rem;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            color: #2c3e50;
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Avatar styling */
        .avatar-lg {
            width: 130px;
            height: 130px;
            border-radius: 20px;
            margin-bottom: 25px;
            background: linear-gradient(135deg, #4680FF 0%, #6AA6FF 100%);
            color: white;
            font-size: 3.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(70, 128, 255, 0.3);
            transition: all 0.3s ease;
        }

        .avatar-lg:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(70, 128, 255, 0.4);
        }

        /* Back button styling */
        .back-btn {
            background: #edf2ff;
            color: #4680FF;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: #4680FF;
            color: white;
            transform: translateX(-3px);
            box-shadow: 0 5px 15px rgba(70, 128, 255, 0.2);
        }

        /* Badge styling */
        .badge {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .badge.bg-primary {
            background: #4680FF !important;
        }

        .badge.bg-success {
            background: #2ecc71 !important;
        }

        /* User name styling */
        h4 {
            color: #2c3e50;
            font-weight: 600;
            margin: 15px 0;
            font-size: 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 20px;
            }

            .user-details {
                padding: 20px;
            }

            .avatar-lg {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }
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
                                        <div class="detail-label">First Name</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($user->getNom()); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label">Last Name</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($user->getPrenom()); ?></div>
                                    </div>
                                </div>
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label">Role</div>
                                        <div class="detail-value">
                                            <span class="badge bg-<?php echo $user->getRole() === 'admin' ? 'primary' : 'success'; ?>">
                                                <?php echo ucfirst(htmlspecialchars($user->getRole())); ?>
                                            </span>
                                        </div>
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