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
        /* Variables globales */
        :root {
            --primary: #4680FF;
            --primary-light: #edf2ff;
            --primary-dark: #3464c5;
            --primary-gradient: linear-gradient(135deg, #4680FF 0%, #6AA6FF 100%);
            --success: #2ecc71;
            --success-light: #d4edda;
            --text-dark: #2c3e50;
            --text-muted: #8392ab;
            --border-color: #edf2f9;
            --bg-light: #f8f9fa;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 5px 15px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.12);
            --radius-sm: 8px;
            --radius-md: 15px;
            --radius-lg: 20px;
            --transition: all 0.3s ease;
        }

        /* Card styling modernisé */
        .card {
            border: none;
            box-shadow: var(--shadow-md);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
        }

        .card-header {
            background: white;
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }
        
        .card-header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        .card-header h5 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
        }

        .card-body {
            padding: 2.5rem 2rem;
        }

        /* User details box styling */
        .user-details {
            background: var(--bg-light);
            border-radius: var(--radius-sm);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .user-details:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }
        
        .user-details:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 3px;
            width: 0;
            background: var(--primary-gradient);
            transition: var(--transition);
        }
        
        .user-details:hover:before {
            width: 100%;
        }

        /* Text styling */
        .detail-label {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            position: relative;
            display: inline-block;
        }
        
        .detail-label:after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--primary-light);
        }

        .detail-value {
            color: var(--text-dark);
            font-size: 1.1rem;
            font-weight: 500;
            word-break: break-word;
        }

        /* Avatar styling */
        .avatar-lg {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 2rem;
            background: var(--primary-gradient);
            color: white;
            font-size: 3.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(70, 128, 255, 0.3);
            transition: var(--transition);
            border: 5px solid white;
            position: relative;
        }

        .avatar-lg:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 30px rgba(70, 128, 255, 0.4);
        }
        
        .avatar-lg:after {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;
            border-radius: 50%;
            border: 2px solid var(--primary-light);
            opacity: 0;
            transition: var(--transition);
        }
        
        .avatar-lg:hover:after {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Back button styling */
        .back-btn {
            background: var(--primary-light);
            color: var(--primary);
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .back-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: var(--primary);
            transition: var(--transition);
            z-index: -1;
        }

        .back-btn:hover {
            color: white;
            transform: translateX(-5px);
            box-shadow: var(--shadow-sm);
        }
        
        .back-btn:hover:before {
            width: 100%;
        }

        /* Badge styling */
        .badge {
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .badge.bg-primary {
            background: var(--primary) !important;
        }

        .badge.bg-success {
            background: var(--success) !important;
        }

        /* User name styling */
        h4 {
            color: var(--text-dark);
            font-weight: 700;
            margin: 0.75rem 0;
            font-size: 1.6rem;
            position: relative;
            display: inline-block;
            padding-bottom: 0.5rem;
        }
        
        h4:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40%;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        /* Animation pour le contenu */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .text-center {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        .row {
            animation: fadeInUp 0.8s ease forwards;
        }
        
        .row:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .row:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        .row:nth-child(4) {
            animation-delay: 0.6s;
        }

        /* Status indicator */
        .status-indicator {
            display: inline-flex;
            align-items: center;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            background: var(--success-light);
            color: var(--success);
            font-weight: 500;
        }
        
        .status-indicator i {
            margin-right: 0.5rem;
        }
        
        /* User info container */
        .user-info-container {
            position: relative;
            padding: 3rem 0 2rem;
            margin-bottom: 2rem;
        }
        
        .user-info-container:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            right: -100%;
            height: 100%;
            background: var(--primary-light);
            z-index: -1;
            opacity: 0.2;
        }
        
        /* Icônes dans les détails */
        .detail-label-container {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        
        .detail-label-container i {
            margin-right: 0.75rem;
            color: var(--primary);
            font-size: 1.2rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem 1.25rem;
            }

            .user-details {
                padding: 1.25rem;
            }

            .avatar-lg {
                width: 120px;
                height: 120px;
                font-size: 3rem;
                margin-bottom: 1.5rem;
            }
            
            h4 {
                font-size: 1.4rem;
            }
            
            .badge {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
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
                            <div class="user-info-container">
                                <div class="text-center mb-4">
                                    <div class="avatar-lg mx-auto">
                                        <?php echo strtoupper(substr($user->getNom(), 0, 1)); ?>
                                    </div>
                                    <h4><?php echo htmlspecialchars($user->getNom() . ' ' . $user->getPrenom()); ?></h4>
                                    <span class="badge bg-<?php echo $user->getRole() === 'admin' ? 'primary' : 'success'; ?>">
                                        <?php echo htmlspecialchars($user->getRole()); ?>
                                    </span>
                                    <div class="status-indicator">
                                        <i class="ti ti-circle-check"></i> Active Account
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label-container">
                                            <i class="ti ti-user"></i>
                                            <div class="detail-label">First Name</div>
                                        </div>
                                        <div class="detail-value"><?php echo htmlspecialchars($user->getNom()); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label-container">
                                            <i class="ti ti-user"></i>
                                            <div class="detail-label">Last Name</div>
                                        </div>
                                        <div class="detail-value"><?php echo htmlspecialchars($user->getPrenom()); ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label-container">
                                            <i class="ti ti-mail"></i>
                                            <div class="detail-label">Email</div>
                                        </div>
                                        <div class="detail-value"><?php echo htmlspecialchars($user->getEmail()); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label-container">
                                            <i class="ti ti-phone"></i>
                                            <div class="detail-label">Phone</div>
                                        </div>
                                        <div class="detail-value"><?php echo htmlspecialchars($user->getTel()); ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label-container">
                                            <i class="ti ti-shield"></i>
                                            <div class="detail-label">Role</div>
                                        </div>
                                        <div class="detail-value">
                                            <span class="badge bg-<?php echo $user->getRole() === 'admin' ? 'primary' : 'success'; ?>">
                                                <?php echo ucfirst(htmlspecialchars($user->getRole())); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="user-details">
                                        <div class="detail-label-container">
                                            <i class="ti ti-calendar"></i>
                                            <div class="detail-label">Registration Date</div>
                                        </div>
                                        <div class="detail-value">
                                            <?php echo $user->getCreatedAt() ? date('M d, Y', strtotime($user->getCreatedAt())) : 'N/A'; ?>
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