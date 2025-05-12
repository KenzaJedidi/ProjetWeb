<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../../config.php';
include_once '../../Controller/userC.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Get profile image if exists
$profileImage = "assets/images/user/me.jpg"; // Default image
if (isset($_SESSION['admin']['profile_picture']) && $_SESSION['admin']['profile_picture']) {
    $profileImage = 'data:image/jpeg;base64,' . base64_encode($_SESSION['admin']['profile_picture']);
}

$userC = new userC();

// Enhanced statistics collection
$stats = [
    'total_users' => $userC->countUsers(),
    'active_users' => $userC->countActiveUsers(),
    'banned_users' => $userC->countBannedUsers(),
    'new_users_today' => $userC->countNewUsersToday(),
    'monthly_signups' => [],
    'roles_distribution' => [],
    'recent_registrations' => [],
    'gender_distribution' => [],
    'user_activity' => [],
    'platform_distribution' => []
];

// Get roles distribution
try {
    $pdo = config::getConnexion();
    $query = "SELECT role, COUNT(*) as count FROM user GROUP BY role";
    $stats['roles_distribution'] = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $stats['roles_distribution'] = [];
}

// Get monthly signups (last 12 months)
try {
    $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
             FROM user 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
             ORDER BY month ASC";
    $stats['monthly_signups'] = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $stats['monthly_signups'] = [];
}

// Get recent registrations
try {
    $query = "SELECT * FROM user ORDER BY created_at DESC LIMIT 5";
    $stats['recent_registrations'] = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    error_log("Error fetching recent registrations: " . $e->getMessage());
    $stats['recent_registrations'] = [];
}

// Get gender distribution
try {
    $query = "SELECT gender, COUNT(*) as count FROM user GROUP BY gender";
    $stats['gender_distribution'] = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $stats['gender_distribution'] = [];
}

// Get user activity data (last 7 days)
try {
    $query = "SELECT DATE(login_time) as date, COUNT(DISTINCT user_id) as active_users
             FROM user_activity
             WHERE login_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY DATE(login_time)
             ORDER BY date ASC";
    $stats['user_activity'] = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $stats['user_activity'] = [];
}

// Get platform distribution
try {
    $query = "SELECT platform, COUNT(*) as count 
             FROM user_activity 
             WHERE login_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY platform";
    $stats['platform_distribution'] = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $stats['platform_distribution'] = [];
}

// Prepare data for charts
$monthlyLabels = array_column($stats['monthly_signups'], 'month');
$monthlyData = array_column($stats['monthly_signups'], 'count');

$rolesLabels = array_column($stats['roles_distribution'], 'role');
$rolesData = array_column($stats['roles_distribution'], 'count');

$genderLabels = array_column($stats['gender_distribution'], 'gender');
$genderData = array_column($stats['gender_distribution'], 'count');

$activityLabels = array_column($stats['user_activity'], 'date');
$activityData = array_column($stats['user_activity'], 'active_users');

$platformLabels = array_column($stats['platform_distribution'], 'platform');
$platformData = array_column($stats['platform_distribution'], 'count');
?>

<?php
$currentPage = 'dashboard';
?>
<?php include 'includes/sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    
    <!-- [Template CSS Files] -->
    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon">
    <link href="assets/css/plugins/animate.min.css" rel="stylesheet">
    <link href="assets/fonts/tabler-icons.min.css" rel="stylesheet">
    <link href="assets/fonts/feather.css" rel="stylesheet">
    <link href="assets/fonts/fontawesome.css" rel="stylesheet">
    <link href="assets/fonts/material.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-preset.css" rel="stylesheet">

    <!-- Additional CSS for enhanced design -->
    <style>
        .pc-sidebar {
            background: #3f4d67;
        }
        
        .statistics-card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border: none;
            margin-bottom: 20px;
        }
        
        .statistics-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .card-header {
            border-bottom: none;
            background: transparent;
            padding: 1.25rem 1.5rem;
        }

        .statistics-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .bg-primary-light {
            background: rgba(70, 128, 255, 0.1);
        }
        .bg-success-light {
            background: rgba(46, 212, 119, 0.1);
        }
        .bg-danger-light {
            background: rgba(255, 77, 79, 0.1);
        }
        .bg-warning-light {
            background: rgba(255, 171, 0, 0.1);
        }
        .bg-info-light {
            background: rgba(0, 184, 212, 0.1);
        }

        .text-value {
            font-size: 24px;
            font-weight: 600;
        }

        .dashboard-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
        }

        .pc-navbar .pc-item .pc-link:hover {
            background: rgba(255,255,255,0.1);
        }

        .pc-header .avatar {
            width: 32px;
            height: 32px;
            position: relative;
        }

        .pc-header .avatar.avatar-md {
            width: 48px;
            height: 48px;
        }

        .pc-header .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pc-h-dropdown {
            width: 280px;
            padding: 0;
        }

        .pc-h-dropdown .dropdown-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
        }

        .pc-h-dropdown .dropdown-item {
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pc-h-dropdown .dropdown-item:hover {
            background: #f8f9fa;
        }

        .pc-h-dropdown .dropdown-item i {
            font-size: 18px;
            color: #4680FF;
        }

        .user-name {
            color: #333;
            font-weight: 500;
        }

        .user-desc {
            font-size: 12px;
        }

        .dropdown-divider {
            margin: 0;
        }

        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
        }

        .avatar-initial {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #4680FF;
            color: white;
            font-weight: bold;
        }

        .badge {
            font-size: 12px;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .badge-admin {
            background-color: #4680FF;
            color: white;
        }

        .badge-user {
            background-color: #2ed477;
            color: white;
        }

        .badge-moderator {
            background-color: #ffa21d;
            color: white;
        }

        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-active {
            background-color: #2ed477;
        }

        .status-inactive {
            background-color: #ffa21d;
        }

        .status-banned {
            background-color: #ff4d4f;
        }

        .progress-thin {
            height: 6px;
            border-radius: 3px;
        }

        .activity-badge {
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 10px;
            background-color: #f0f0f0;
        }

        .platform-icon {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        .statistics-section {
            padding: 1.5rem;
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            border-radius: 15px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(70, 128, 255, 0.08);
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(70, 128, 255, 0.1);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 24px;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .stat-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: inherit;
            border-radius: inherit;
            filter: blur(8px);
            z-index: -1;
            opacity: 0.6;
        }

        .stat-content {
            padding: 1.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-trend {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .trend-up {
            background: rgba(46, 212, 119, 0.1);
            color: #2ed477;
        }

        .trend-down {
            background: rgba(255, 82, 82, 0.1);
            color: #ff5252;
        }

        .progress-mini {
            height: 4px;
            border-radius: 2px;
            margin-top: 1rem;
            background: rgba(0, 0, 0, 0.05);
        }

        .progress-bar {
            border-radius: 2px;
            position: relative;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            right: 0;
            top: -2px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: inherit;
        }

        .pc-sidebar {
            background: #ffffff !important;
            border-right: 1px solid #e9ecef;
        }

        .pc-navbar .pc-item {
            position: relative;
        }

        .pc-navbar .pc-item .pc-link {
            color: #344767;
            padding: 12px 15px;
            margin: 5px 10px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .pc-navbar .pc-item .pc-link:hover {
            background: #f8f9fa;
            color: #4680FF;
        }

        .pc-navbar .pc-item.active .pc-link {
            background: #4680FF;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(70, 128, 255, 0.2);
        }

        .pc-navbar .pc-item.disabled .pc-link {
            cursor: not-allowed;
            background: #f8f9fa;
        }

        .pc-navbar .pc-item .pc-micon {
            margin-right: 10px;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            background: rgba(70, 128, 255, 0.1);
        }

        .pc-navbar .pc-item.active .pc-micon {
            background: rgba(255, 255, 255, 0.2);
        }

        .pc-item.pc-caption {
            margin-top: 20px;
            padding: 10px 15px;
        }

        .pc-item.pc-caption label {
            color: #4680FF;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pc-item.pc-caption span {
            color: #67748e;
            font-size: 11px;
            display: block;
            margin-top: 4px;
        }

        .pc-navbar .pc-item.disabled .pc-micon {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        /* Ajouter ce CSS à la fin de votre section style existante */

        /* Logo styling - centered and extra large */
        .m-header {
            padding: 30px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(233, 236, 239, 0.5);
            margin-bottom: 15px;
            text-align: center;
        }

        .b-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
        }

        .b-brand:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .logo {
            max-height: 115px; /* Logo beaucoup plus grand */
            width: auto;
            display: block;
            margin: 0 auto; /* Suppression de la marge en bas */
            transition: all 0.3s ease;
        }

        /* Nous cachons le texte logo-text */
        .logo-text {
            display: none; /* Cache le texte */
        }

        /* Couleur du texte de la barre latérale en noir */
        .pc-navbar .pc-item .pc-link {
            color: #000000 !important; /* Texte en noir */
        }

        /* Hover avec texte toujours en noir */
        .pc-navbar .pc-item .pc-link:hover {
            background: #f8f9fa;
            color: #000000 !important; /* Texte reste en noir au survol */
        }

        /* État actif avec contraste préservé (texte blanc sur fond bleu) */
        .pc-navbar .pc-item.active .pc-link {
            background: #4680FF;
            color: #ffffff !important; /* Le texte reste blanc pour l'élément actif */
        }

        /* Couleur des textes de section en noir */
        .pc-item.pc-caption label {
            color: #000000 !important;
            opacity: 0.9;
        }

        .pc-item.pc-caption span {
            color: #000000 !important;
            opacity: 0.7;
        }

        /* Éléments désactivés en gris foncé */
        .pc-navbar .pc-item.disabled .pc-link {
            color: #6c757d !important;
        }
    </style>
</head>

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <!-- [ Mobile header ] start -->
    <div class="pc-mob-header pc-header">
        <div class="pcm-toolbar">
            <a href="#!" class="pc-head-link" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- [ navigation menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="#" class="b-brand">
                    <img src="../assets/img/localoo.png" alt="Localoo" class="logo" style="max-height: 115px;">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item active">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="pages/usersList.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Users List</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="pages/add-user.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                            <span class="pc-mtext">Add User</span>
                        </a>
                    </li>

                    <!-- Nouveaux boutons de gestion - version simplifiée -->
                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-alert-circle"></i></span>
                            <span class="pc-mtext">Reclamation</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-gift"></i></span>
                            <span class="pc-mtext">Bon Plans</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-star"></i></span>
                            <span class="pc-mtext">Review</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-messages"></i></span>
                            <span class="pc-mtext">Forum</span>
                        </a>
                    </li>

                    <!-- Separator -->
                    <li class="pc-item pc-caption">
                      
                      
                    </li>

                    <!-- Éléments désactivés -->
                    <li class="pc-item disabled">
                        <a href="#!" class="pc-link" style="pointer-events: none; opacity: 0.6;">
                            <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                            <span class="pc-mtext">Events</span>
                            <span class="pc-arrow"></span>
                        </a>
                    </li>

                    <li class="pc-item disabled">
                        <a href="#!" class="pc-link" style="pointer-events: none; opacity: 0.6;">
                            <span class="pc-micon"><i class="ti ti-briefcase"></i></span>
                            <span class="pc-mtext">Emploi</span>
                            <span class="pc-arrow"></span>
                        </a>
                    </li>

                    <li class="pc-item disabled">
                        <a href="#!" class="pc-link" style="pointer-events: none; opacity: 0.6;">
                            <span class="pc-micon"><i class="ti ti-bell"></i></span>
                            <span class="pc-mtext">Notifications</span>
                            <span class="pc-arrow"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- [ Header ] start -->
    <header class="pc-header">
        <div class="header-wrapper">
            <!-- Search section if needed -->
            <div class="me-auto pc-mob-drp">
                <!-- ... existing search code ... -->
            </div>
            
            <!-- Updated profile section -->
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm">
                                    <img src="<?php echo $profileImage; ?>" alt="user-image" class="rounded-circle">
                                </div>
                                <div class="ms-2 d-none d-sm-block">
                                    <div class="user-name h6 mb-0"><?php echo $_SESSION['admin']['nom'] ?? 'Admin'; ?></div>
                                    <small class="user-desc text-muted">Administrator</small>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md">
                                        <img src="<?php echo $profileImage; ?>" alt="user-image" class="rounded-circle">
                                    </div>
                                    <div class="ms-2">
                                        <div class="user-name h6 mb-0"><?php echo $_SESSION['admin']['nom'] ?? 'Admin'; ?></div>
                                        <small class="user-desc text-muted">Administrator</small>
                                    </div>
                                </div>
                            </div>
                            <a href="profile.php" class="dropdown-item">
                                <i class="ti ti-user-circle"></i>
                                <span>Profile</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="ti ti-settings"></i>
                                <span>Settings</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="logout.php" class="dropdown-item">
                                <i class="ti ti-logout"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dashboard Overview</h5>
                            <p class="text-muted mb-0">Welcome back, <?php echo $_SESSION['admin']['nom'] ?? 'Admin'; ?>. Here's what's happening with your platform.</p>
                        </div>
                        <div class="card-body">
                            <!-- Enhanced Statistics Section -->
                            <div class="statistics-section mb-4">
                                <div class="row g-4">
                                    <!-- Total Users Card -->
                                    <div class="col-sm-6 col-xl-3">
                                        <div class="stat-card">
                                            <div class="stat-content">
                                                <div class="stat-icon bg-primary-subtle">
                                                    <i class="ti ti-users text-primary"></i>
                                                </div>
                                                <div class="stat-label">Total Users</div>
                                                <div class="stat-value">
                                                    <?php echo number_format($stats['total_users']); ?>
                                                    <span class="stat-trend trend-up">
                                                        <i class="ti ti-trending-up me-1"></i>
                                                        12%
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">Overall users</small>
                                                    <small class="text-primary"><?php echo $stats['total_users']; ?>/1000</small>
                                                </div>
                                                <div class="progress-mini">
                                                    <div class="progress-bar bg-primary" style="width: <?php echo min(100, ($stats['total_users'] / 1000) * 100); ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Active Users Card -->
                                    <div class="col-sm-6 col-xl-3">
                                        <div class="stat-card">
                                            <div class="stat-content">
                                                <div class="stat-icon bg-success-subtle">
                                                    <i class="ti ti-user-check text-success"></i>
                                                </div>
                                                <div class="stat-label">Active Users</div>
                                                <div class="stat-value">
                                                    <?php echo number_format($stats['active_users']); ?>
                                                    <span class="stat-trend trend-up">
                                                        <i class="ti ti-trending-up me-1"></i>
                                                        8%
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">Active rate</small>
                                                    <small class="text-success"><?php echo round(($stats['active_users'] / $stats['total_users']) * 100); ?>%</small>
                                                </div>
                                                <div class="progress-mini">
                                                    <div class="progress-bar bg-success" style="width: <?php echo ($stats['active_users'] / $stats['total_users']) * 100; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- New Users Card -->
                                    <div class="col-sm-6 col-xl-3">
                                        <div class="stat-card">
                                            <div class="stat-content">
                                                <div class="stat-icon bg-warning-subtle">
                                                    <i class="ti ti-user-plus text-warning"></i>
                                                </div>
                                                <div class="stat-label">New Users Today</div>
                                                <div class="stat-value">
                                                    <?php 
                                                        $newUsersToday = $userC->countNewUsersToday();
                                                        echo number_format($newUsersToday); 
                                                    ?>
                                                    <span class="stat-trend <?php echo $newUsersToday > 0 ? 'trend-up' : 'trend-down'; ?>">
                                                        <i class="ti ti-trending-<?php echo $newUsersToday > 0 ? 'up' : 'down'; ?> me-1"></i>
                                                        Today
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">Daily target</small>
                                                    <small class="text-warning"><?php echo $newUsersToday; ?>/10</small>
                                                </div>
                                                <div class="progress-mini">
                                                    <div class="progress-bar bg-warning" 
                                                         style="width: <?php echo min(100, ($newUsersToday / 10) * 100); ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Banned Users Card -->
                                    <div class="col-sm-6 col-xl-3">
                                        <div class="stat-card">
                                            <div class="stat-content">
                                                <div class="stat-icon bg-danger-subtle">
                                                    <i class="ti ti-user-off text-danger"></i>
                                                </div>
                                                <div class="stat-label">Banned Users</div>
                                                <div class="stat-value">
                                                    <?php echo number_format($stats['banned_users']); ?>
                                                    <span class="stat-trend trend-down">
                                                        <i class="ti ti-trending-down me-1"></i>
                                                        3%
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">Ban rate</small>
                                                    <small class="text-danger"><?php echo round(($stats['banned_users'] / $stats['total_users']) * 100, 1); ?>%</small>
                                                </div>
                                                <div class="progress-mini">
                                                    <div class="progress-bar bg-danger" style="width: <?php echo ($stats['banned_users'] / $stats['total_users']) * 100; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Charts Row -->
                            <div class="row mt-4">
                                <!-- Enhanced User Registration Trends -->
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0">User Growth Analytics</h5>
                                                <small class="text-muted">Monthly user registration analysis</small>
                                            </div>
                                            <div class="chart-actions">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary active">Monthly</button>
                                                    <button type="button" class="btn btn-sm btn-outline-primary">Weekly</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-4">
                                                <div class="chart-info">
                                                    <h3 class="mb-1"><?php echo array_sum($monthlyData); ?></h3>
                                                    <small class="text-muted">Total new users this year</small>
                                                </div>
                                                <div class="chart-info text-end">
                                                    <h3 class="mb-1"><?php echo end($monthlyData); ?></h3>
                                                    <small class="text-muted">New users this month</small>
                                                </div>
                                            </div>
                                            <div id="user-growth-chart" style="height: 360px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Role Distribution Chart - Enhanced -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">User Roles Overview</h5>
                                            <small class="text-muted">Distribution of user roles</small>
                                        </div>
                                        <div class="card-body">
                                            <div id="roles-distribution-chart" style="height: 360px;"></div>
                                            <div class="mt-4">
                                                <?php foreach ($stats['roles_distribution'] as $role): ?>
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge bg-<?php echo $role['role'] === 'admin' ? 'primary' : 'success'; ?> p-2">
                                                            <i class="ti ti-user"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0"><?php echo ucfirst($role['role']); ?></h6>
                                                        <small class="text-muted"><?php echo $role['count']; ?> users</small>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Registrations Table -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5>Recent Registrations</h5>
                                            <a href="pages/usersList.php" class="btn btn-sm btn-primary">View All Users</a>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>User</th>
                                                            <th>Email</th>
                                                            <th>Role</th>
                                                            <th>Status</th>
                                                            <th>Registered</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($stats['recent_registrations'])): ?>
                                                            <?php foreach ($stats['recent_registrations'] as $user): ?>
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar-initial me-3">
                                                                                <?php echo strtoupper(substr($user['nom'], 0, 1)); ?>
                                                                            </div>
                                                                            <div>
                                                                                <div class="fw-medium"><?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?></div>
                                                                                <small class="text-muted">ID: <?php echo $user['id_user']; ?></small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                                    <td>
                                                                        <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'primary' : 'success'; ?>">
                                                                            <?php echo ucfirst($user['role']); ?>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-success">Active</span>
                                                                    </td>
                                                                    <td><?php echo date('M d, Y', strtotime($user['created_at'] ?? 'now')); ?></td>
                                                                    <td>
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                                                                <i class="ti ti-dots-vertical"></i>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a class="dropdown-item" href="pages/view-user.php?id=<?php echo $user['id_user']; ?>">
                                                                                        <i class="ti ti-eye me-2"></i>View
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="dropdown-item" href="pages/edit-user.php?id=<?php echo $user['id_user']; ?>">
                                                                                        <i class="ti ti-edit me-2"></i>Edit
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <hr class="dropdown-divider">
                                                                                </li>
                                                                                <li>
                                                                                    <a class="dropdown-item text-danger" href="#" onclick="deleteUser(<?php echo $user['id_user']; ?>)">
                                                                                        <i class="ti ti-trash me-2"></i>Delete
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="6" class="text-center py-4">
                                                                    <div class="text-muted">
                                                                        <i class="ti ti-users text-muted mb-2" style="font-size: 24px;"></i>
                                                                        <p class="mb-0">No recent registrations found</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add this JavaScript for delete functionality -->
                            <script>
                                function deleteUser(userId) {
                                    if (confirm('Are you sure you want to delete this user?')) {
                                        // Add your delete logic here
                                        window.location.href = `pages/delete-user.php?id=${userId}`;
                                    }
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/plugins/highlight.min.js"></script>

    <!-- Add ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced User Growth Chart
            const monthlyData = <?php echo json_encode($monthlyData); ?>;
            const monthlyLabels = <?php echo json_encode($monthlyLabels); ?>;
            
            const growthOptions = {
                series: [{
                    name: 'New Users',
                    data: monthlyData,
                    type: 'area'
                }, {
                    name: 'Growth Rate',
                    data: monthlyData.map((val, i) => {
                        if (i === 0) return 0;
                        const prevVal = monthlyData[i - 1];
                        return prevVal === 0 ? 0 : ((val - prevVal) / prevVal * 100).toFixed(1);
                    }),
                    type: 'line'
                }],
                chart: {
                    height: 360,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                stroke: {
                    width: [2, 2],
                    curve: 'smooth'
                },
                fill: {
                    type: ['gradient', 'solid'],
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                colors: ['#4680FF', '#2ed477'],
                labels: monthlyLabels,
                grid: {
                    padding: {
                        right: 30,
                        left: 20
                    }
                },
                yaxis: [{
                    title: {
                        text: 'Number of Users'
                    },
                    labels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                }, {
                    opposite: true,
                    title: {
                        text: 'Growth Rate (%)'
                    },
                    labels: {
                        formatter: function(val) {
                            return val + '%';
                        }
                    }
                }],
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: [{
                        formatter: function(y) {
                            if (typeof y !== "undefined") {
                                return y.toFixed(0) + " users";
                            }
                            return y;
                        }
                    }, {
                        formatter: function(y) {
                            if (typeof y !== "undefined") {
                                return y + "%";
                            }
                            return y;
                        }
                    }]
                }
            };

            new ApexCharts(document.querySelector("#user-growth-chart"), growthOptions).render();

            // Enhanced Roles Distribution Chart
            const rolesData = <?php echo json_encode($rolesData); ?>;
            const rolesLabels = <?php echo json_encode($rolesLabels); ?>;
            
            const rolesOptions = {
                series: rolesData,
                chart: {
                    type: 'donut',
                    height: 360
                },
                labels: rolesLabels,
                colors: ['#4680FF', '#2ed477', '#ffa21d'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: {
                                    show: true
                                },
                                value: {
                                    show: true,
                                    formatter: function(val) {
                                        return val + ' users';
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total Users',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' users';
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            };

            new ApexCharts(document.querySelector("#roles-distribution-chart"), rolesOptions).render();

            // Gender Distribution Chart
            const genderData = <?php echo json_encode($genderData); ?>;
            const genderLabels = <?php echo json_encode($genderLabels); ?>;
            
            const genderOptions = {
                series: genderData,
                chart: {
                    type: 'pie',
                    height: '100%'
                },
                labels: genderLabels,
                colors: ['#4680FF', '#ff4d4f', '#2ed477'],
                legend: { 
                    position: 'bottom'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            
            new ApexCharts(document.querySelector("#gender-distribution-chart"), genderOptions).render();

            // User Activity Chart
            const activityData = <?php echo json_encode($activityData); ?>;
            const activityLabels = <?php echo json_encode($activityLabels); ?>;
            
            const activityOptions = {
                series: [{
                    name: 'Active Users',
                    data: activityData
                }],
                chart: {
                    type: 'bar',
                    height: '100%',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: activityLabels,
                    labels: {
                        formatter: function(value) {
                            return new Date(value).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Active Users'
                    },
                    min: 0
                },
                colors: ['#2ed477'],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " active users";
                        }
                    }
                }
            };
            
            new ApexCharts(document.querySelector("#user-activity-chart"), activityOptions).render();

            // Platform Distribution Chart
            const platformData = <?php echo json_encode($platformData); ?>;
            const platformLabels = <?php echo json_encode($platformLabels); ?>;
            
            const platformOptions = {
                series: platformData,
                chart: {
                    type: 'radialBar',
                    height: '100%'
                },
                labels: platformLabels,
                colors: ['#4680FF', '#2ed477', '#ffa21d', '#ff4d4f'],
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: {
                                fontSize: '14px',
                            },
                            value: {
                                fontSize: '16px',
                                formatter: function(val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            };
            
            new ApexCharts(document.querySelector("#platform-distribution-chart"), platformOptions).render();
        });

        function updateChartRange(range) {
            // This would be implemented with AJAX to fetch new data based on the selected range
            console.log('Range changed to: ' + range);
            // In a real implementation, you would fetch new data and update the chart
        }
    </script>
</body>
</html>