<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
$currentPage = 'users-list';
$isSubPage = true;
include '../includes/sidebar.php';
include_once '../../../Controller/userC.php';
include_once '../../../Model/user.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

$userC = new userC();

// Get filter parameters from request
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$roleFilter = isset($_GET['role']) ? $_GET['role'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'name';

// Get filtered and sorted users
$users = $userC->getFilteredUsers($searchTerm, $roleFilter, $sortBy);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Users List - Admin Dashboard</title>
    <?php include '../includes/head.php'; ?>
    <!-- Add SweetAlert2 CSS and JS locally -->
    <link rel="stylesheet" href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <style>
        /* Variables globales améliorées */
        :root {
            --primary: #4680FF;
            --primary-light: #edf2ff;
            --primary-hover: #3464c5;
            --primary-gradient: linear-gradient(135deg, #4680FF 0%, #6AA6FF 100%);
            --success: #2ecc71;
            --success-light: #e6f7f0;
            --danger: #ff4d4f;
            --danger-light: #ffeded;
            --warning: #ffc107;
            --warning-light: #fff8e1;
            --text-dark: #2c3e50;
            --text-muted: #8392ab;
            --border-color: #edf2f9;
            --bg-light: #f8f9fa;
            --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 5px 15px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --radius-sm: 6px;
            --radius-md: 12px;
            --radius-lg: 18px;
            --transition: auto;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* PC Sidebar Styles - Consistent with add-user.php and index.php */
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

        /* Conteneur principal */
        .pc-content {
            padding: 1.5rem;
        }

        /* Card styling */
        .card {
            border: none;
            margin-bottom: 30px;
            box-shadow: var(--shadow-md);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: var(--transition);
            background: white;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        /* Header de la carte */
        .card-header {
            background: white;
            padding: 1.75rem;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }

        .card-header h5 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
            letter-spacing: -0.02em;
        }

        .card-header p {
            color: var(--text-muted);
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        /* Boutons d'actions principaux */
        .card-header .btn-success {
            background: var(--success);
            border: none;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            font-size: 0.9rem;
            box-shadow: 0 3px 10px rgba(46, 204, 113, 0.2);
        }

        .card-header .btn-success:hover {
            background: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(46, 204, 113, 0.3);
        }

        .card-header .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            font-size: 0.9rem;
            box-shadow: 0 3px 10px rgba(70, 128, 255, 0.2);
        }

        .card-header .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(70, 128, 255, 0.3);
        }

        /* Filtres améliorés */
        .card-body.border-bottom {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-light);
        }

        /* Boîte de recherche améliorée */
        .search-box {
            position: relative;
            max-width: 100%;
            transition: var(--transition);
        }

        .search-box .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-box input {
            padding: 0.75rem 1rem 0.75rem 45px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            background-color: white;
            font-size: 0.95rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(70, 128, 255, 0.15);
        }

        .search-box:hover .search-icon {
            color: var(--primary);
        }

        /* Select améliorés */
        .form-select {
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            padding: 0.75rem 2rem 0.75rem 1rem;
            background-position: right 1rem center;
            min-width: 160px;
            font-size: 0.95rem;
            box-shadow: var(--shadow-sm);
            background-color: white;
            transition: var(--transition);
            appearance: none;
        }

        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(70, 128, 255, 0.15);
        }

        /* Boutons des filtres */
        .btn-light {
            background: white;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .btn-light:hover {
            background: #f0f2f5;
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Tableau amélioré */
        .table-responsive {
            padding: 0;
            border-radius: 0 0 var(--radius-md) var(--radius-md);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        /* En-tête de tableau */
        .table thead th {
            background: var(--bg-light);
            color: var(--text-dark);
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Corps du tableau */
        .table tbody td {
            vertical-align: middle;
            color: var(--text-dark);
            border-bottom: 1px solid var(--border-color);
            padding: 1.15rem 1.25rem;
            font-size: 0.95rem;
        }

       .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background-color: var(--primary-light);
        }

        /* Avatar utilisateur */
        .avatar-sm {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 3px 8px rgba(70, 128, 255, 0.25);
            transition: var(--transition);
        }

        .avatar-sm:hover {
            transform: scale(1.1);
        }

        .avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Informations utilisateur */
        .user-info {
            display: flex;
            align-items: center;
        }

        .user-name {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0;
            transition: var(--transition);
        }

        /* Badge amélioré */
        .badge {
            padding: 0.5rem 0.85rem;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 30px;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
        }

        .badge.bg-primary {
            background: var(--primary-gradient) !important;
        }

        .badge.bg-success {
            background: var(--success) !important;
        }

        /* Badge utilisateur banni */
        .banned-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ff4d4f 0%, #ff7875 100%);
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            line-height: 1.4;
            vertical-align: middle;
            margin-left: 8px;
            box-shadow: 0 2px 5px rgba(255, 77, 79, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 77, 79, 0.6); }
            70% { box-shadow: 0 0 0 5px rgba(255, 77, 79, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 77, 79, 0); }
        }

        /* Boutons d'action */
        .d-flex.gap-2 {
            display: flex;
            gap: 0.5rem !important;
        }

        /* Bouton ban/unban */
        .ban-toggle-btn {
            transition: var(--transition);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ban-toggle-btn.btn-success {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            border: none;
            box-shadow: 0 3px 6px rgba(46, 204, 113, 0.2);
        }

        .ban-toggle-btn.btn-danger {
            background: linear-gradient(135deg, #ff4d4f 0%, #ff7875 100%);
            border: none;
            box-shadow: 0 3px 6px rgba(255, 77, 79, 0.2);
        }

        .ban-toggle-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
        }

        /* Boutons d'action */
        .btn-light {
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .btn-light i {
            font-size: 1.1rem;
        }

        .btn-light:hover {
            transform: translateY(-2px);
            background-color: #f0f2f5;
            box-shadow: var(--shadow-sm);
        }

        /* Icônes spécifiques */
        .btn-light i.ti-eye:hover {
            color: var(--primary);
        }

        .btn-light i.ti-edit:hover {
            color: var(--warning);
        }

        .btn-light i.ti-trash {
            transition: var(--transition);
        }

        .btn-light:hover i.ti-trash {
            color: var(--danger);
        }

        /* Style pour les utilisateurs bannis */
        tr.banned-user {
            position: relative;
        }

        tr.banned-user::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, #ff4d4f 0%, #ff7875 100%);
            border-radius: 4px;
        }

        tr.banned-user td {
            color: #dc3545;
            background-color: var(--danger-light);
        }

        /* Message "aucun résultat" */
        .no-results {
            padding: 60px 20px;
            text-align: center;
            color: var(--text-muted);
            background: var(--bg-light);
            border-radius: var(--radius-sm);
        }

        .no-results i {
            font-size: 4rem;
            margin-bottom: 1.25rem;
            color: #b0bfd0;
            opacity: 0.8;
        }

        .no-results p {
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Mise en surbrillance de recherche */
        .search-results-highlight {
            background-color: rgba(70, 128, 255, 0.15);
            border-radius: 3px;
            padding: 0.15rem 0.3rem;
            margin: 0 -0.3rem;
            font-weight: 600;
            color: var(--primary);
            position: relative;
            display: inline-block;
        }

        .search-results-highlight::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--primary);
            opacity: 0.5;
        }

        /* Effets de transition */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table tbody tr {
            animation: fadeIn 0.3s ease-out forwards;
        }

        /* Styles pour appareils mobiles */
        @media (max-width: 992px) {
            .card-header {
                padding: 1.25rem;
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start !important;
            }

            .card-header > div:last-child {
                width: 100%;
            }

            .card-header .btn {
                width: 100%;
                justify-content: center;
                margin-bottom: 0.5rem;
            }

            .card-body.border-bottom {
                padding: 1.25rem;
            }

            .d-flex.justify-content-md-end {
                flex-wrap: wrap;
            }

            .form-select, .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .table thead th, .table tbody td {
                padding: 0.75rem 1rem;
            }

            .ban-toggle-btn {
                padding: 0.4rem 0.7rem;
                font-size: 0.8rem;
            }

            .btn-light {
                width: 32px;
                height: 32px;
                padding: 0.4rem;
            }

            .d-flex.gap-2 {
                flex-wrap: wrap;
            }
        }

        /* Animation lors du chargement */
        .table-wrapper {
            position: relative;
            transition: var(--transition);
        }

        .table-skeleton {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            transition: opacity 0.3s ease;
            opacity: 0;
            pointer-events: none;
        }

        .loading .table-skeleton {
            opacity: 1;
        }

        /* Animation des boutons */
        .btn {
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: '';
            position: absolute;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1);
            transform-origin: 50% 50%;
            transition: transform 0.5s, opacity 0.5s;
        }

        .btn:active::after {
            transform: scale(20, 20);
            opacity: 0.3;
            transition: 0s;
        }

        /* Espace pour les icônes dans les boutons */
        .btn i {
            transition: transform 0.3s ease;
        }

        .btn:hover i {
            transform: scale(1.2);
        }

        /* Animation de survol pour les lignes */
        .table tbody tr {
            position: relative;
            z-index: 1;
        }

        .table tbody tr::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--primary-light);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
            z-index: -1;
            opacity: 0.5;
        }

        .table tbody tr:hover::after {
            transform: scaleX(1);
        }

        /* Améliorations pour la barre de recherche et les filtres */
        .filter-container {
            display: flex;
            align-items: center;
            width: 100%;
            gap: 10px;
            flex-wrap: nowrap;
        }
        
        .search-filter-row {
            background: rgba(240, 242, 245, 0.6);
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 0;
        }
        
        .search-box {
            position: relative;
            min-width: 200px;
            flex: 1.5;
        }
        
        .filter-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 2.5;
            flex-wrap: nowrap;
        }
        
        .form-select {
            flex: 1;
            min-width: 110px;
            max-width: 180px;
        }
        
        .filter-btn {
            min-width: 90px;
            white-space: nowrap;
            padding: 0.65rem 1rem;
        }
        
        .filter-btn i {
            font-size: 0.9rem;
        }
        
        @media (max-width: 1100px) {
            .filter-container {
                flex-wrap: wrap;
            }
            
            .search-box {
                flex: 1 0 100%;
                margin-bottom: 10px;
            }
            
            .filter-actions {
                flex: 1 0 100%;
            }
        }
        
        @media (max-width: 768px) {
            .filter-actions {
                flex-wrap: wrap;
            }
            
            .form-select {
                flex: 1 0 calc(50% - 5px);
                max-width: none;
            }
            
            .filter-btn {
                flex: 1 0 calc(50% - 5px);
            }
        }
        
        @media (max-width: 480px) {
            .form-select, .filter-btn {
                flex: 1 0 100%;
            }
        }

        /* Style amélioré pour le bouton Apply */
        .filter-btn.btn-apply {
            background: var(--primary-gradient);
            color: white;
            border: none;
            box-shadow: 0 3px 10px rgba(70, 128, 255, 0.2);
            transition: var(--transition);
        }
        
        .filter-btn.btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(70, 128, 255, 0.3);
        }
        
        .filter-btn.btn-apply:active {
            transform: translateY(0);
            box-shadow: 0 3px 5px rgba(70, 128, 255, 0.2);
        }
        
        /* Style amélioré pour le bouton Reset */
        .filter-btn.btn-reset {
            background: white;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        
        .filter-btn.btn-reset:hover {
            color: var(--danger);
            border-color: var(--danger-light);
            background-color: var(--danger-light);
            transform: translateY(-2px);
        }
        
        .filter-btn.btn-reset:active {
            transform: translateY(0);
        }
        
        /* Effet d'icône pour les boutons */
        .filter-btn.btn-apply i,
        .filter-btn.btn-reset i {
            margin-right: 6px;
            transition: transform 0.3s ease;
        }
        
        .filter-btn.btn-apply:hover i {
            transform: rotate(-15deg) scale(1.2);
        }
        
        .filter-btn.btn-reset:hover i {
            transform: rotate(180deg);
        }

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
    </style>
</head>
<body>
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="#" class="b-brand">
                    <img src="../../assets/img/localoo.png" alt="Localoo" class="logo" style="max-height: 115px;">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="../index.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item active">
                        <a href="usersList.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Users List</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="add-user.php" class="pc-link">
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
    
    <div class="pc-container">
        <div class="pc-content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Users List</h5>
                                <p class="text-muted mb-0">Manage and monitor user accounts</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="export-users.php" class="btn btn-success">
                                    <i class="ti ti-file-export me-1"></i> Export to Excel
                                </a>
                                <a href="add-user.php" class="btn btn-primary">
                                    <i class="ti ti-user-plus me-1"></i> Add New User
                                </a>
                            </div>
                        </div>
                        <div class="card-body border-bottom search-filter-row">
                            <form id="filterForm" method="get" action="">
                                <div class="filter-container">
                                    <!-- Zone de recherche -->
                                    <div class="search-box">
                                        <input type="text" 
                                               class="form-control" 
                                               id="searchInput" 
                                               name="search" 
                                               placeholder="Search by name, email, or phone..." 
                                               value="<?php echo htmlspecialchars($searchTerm); ?>"
                                               autocomplete="off">
                                        <i class="ti ti-search search-icon"></i>
                                    </div>
                                    
                                    <!-- Zone des filtres et boutons -->
                                    <div class="filter-actions">
                                        <select class="form-select" id="roleFilter" name="role">
                                            <option value="">All Roles</option>
                                            <option value="admin" <?php echo $roleFilter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            <option value="user" <?php echo $roleFilter === 'user' ? 'selected' : ''; ?>>User</option>
                                        </select>
                                        <select class="form-select" id="sortBy" name="sort">
                                            <option value="name" <?php echo $sortBy === 'name' ? 'selected' : ''; ?>>Sort by Name</option>
                                            <option value="email" <?php echo $sortBy === 'email' ? 'selected' : ''; ?>>Sort by Email</option>
                                            <option value="recent" <?php echo $sortBy === 'recent' ? 'selected' : ''; ?>>Most Recent</option>
                                        </select>
                                        <button type="submit" class="btn filter-btn btn-apply">
                                            <i class="ti ti-filter"></i> Apply
                                        </button>
                                        <button type="button" class="btn filter-btn btn-reset" onclick="resetFilters()">
                                            <i class="ti ti-refresh"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr class="<?php echo $user->getIsBanned() ? 'banned-user' : ''; ?>" data-user-id="<?php echo $user->getIdUser(); ?>">
                                                <td>
                                                    <div class="d-flex align-items-center user-info">
                                                        <div class="avatar-sm">
                                                            <?php if ($user->getProfilePicture()): ?>
                                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($user->getProfilePicture()); ?>" alt="Profile">
                                                            <?php else: ?>
                                                                <?php echo strtoupper(substr($user->getNom(), 0, 1)); ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="ms-2">
                                                            <h6 class="user-name mb-0">
                                                                <?php echo htmlspecialchars($user->getNom() . ' ' . $user->getPrenom()); ?>
                                                                <?php if ($user->getIsBanned()): ?>
                                                                    <span class="banned-badge ms-2">Banned</span>
                                                                <?php endif; ?>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                                                <td><?php echo htmlspecialchars($user->getTel()); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $user->getRole() === 'admin' ? 'primary' : 'success'; ?>">
                                                        <?php echo ucfirst(htmlspecialchars($user->getRole())); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" 
                                                                class="btn <?php echo $user->getIsBanned() ? 'btn-success' : 'btn-danger'; ?> btn-sm ban-toggle-btn"
                                                                onclick="toggleBanStatus(<?php echo $user->getIdUser(); ?>, '<?php echo htmlspecialchars($user->getNom()); ?>', <?php echo $user->getIsBanned() ? 'true' : 'false'; ?>)">
                                                            <i class="ti <?php echo $user->getIsBanned() ? 'ti-user-check' : 'ti-user-x'; ?>"></i>
                                                            <?php echo $user->getIsBanned() ? 'Unban' : 'Ban'; ?>
                                                        </button>
                                                        <button type="button" class="btn btn-light" onclick="viewUser(<?php echo $user->getIdUser(); ?>)">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-light" onclick="editUser(<?php echo $user->getIdUser(); ?>)">
                                                            <i class="ti ti-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-light" onclick="deleteUser(<?php echo $user->getIdUser(); ?>)">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5">
                                                <div class="no-results">
                                                    <i class="ti ti-users-off"></i>
                                                    <p class="mb-0">No users found</p>
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
    </div>

    <?php include '../includes/scripts.php'; ?>
    <script>
        function viewUser(userId) {
            window.location.href = `view-user.php?id=${userId}`;
        }

        function editUser(userId) {
            window.location.href = `edit-user.php?id=${userId}`;
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`delete-user.php?id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error deleting user');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting user');
                    });
            }
        }

        function toggleBanStatus(userId, userName, isBanned) {
            const action = isBanned ? 'unban' : 'ban';
            
            Swal.fire({
                title: `${action.charAt(0).toUpperCase() + action.slice(1)} User?`,
                html: `Are you sure you want to <strong>${action}</strong> ${userName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isBanned ? '#198754' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${action}!`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('toggle-ban-user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `userId=${userId}`
                    })
                    .then(response => {
                        console.log('Response status:', response.status); // Log response status
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data); // Log response data
                        if (data.success) {
                            const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                            const banButton = row.querySelector('.ban-toggle-btn');
                            const userName = row.querySelector('.user-name');
                            
                            // Update row and button states
                            if (data.isBanned) {
                                row.classList.add('banned-user');
                                banButton.className = 'btn btn-success btn-sm ban-toggle-btn';
                                banButton.innerHTML = '<i class="ti ti-user-check"></i> Unban';
                                
                                // Add banned badge if not exists
                                if (!userName.querySelector('.banned-badge')) {
                                    userName.insertAdjacentHTML('beforeend', 
                                        '<span class="banned-badge ms-2">Banned</span>'
                                    );
                                }
                            } else {
                                row.classList.remove('banned-user');
                                banButton.className = 'btn btn-danger btn-sm ban-toggle-btn';
                                banButton.innerHTML = '<i class="ti ti-user-x"></i> Ban';
                                
                                // Remove banned badge
                                const badge = userName.querySelector('.banned-badge');
                                if (badge) badge.remove();
                            }

                            // Update button click handler
                            banButton.setAttribute('onclick', 
                                `toggleBanStatus(${userId}, '${userName.textContent.trim()}', ${!data.isBanned})`
                            );

                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#4680FF'
                            });
                        } else {
                            console.error('Error response:', data); // Log error response
                            throw new Error(data.message || 'Failed to update user status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error); // Log error details
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'An error occurred while processing your request.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    });
                }
            });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('roleFilter').value = '';
            document.getElementById('sortBy').value = 'name';
            document.getElementById('filterForm').submit();
        }

        function initializeDynamicSearch() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('table tbody tr');
            
            let searchTimeout;

            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                
                // Add small delay to avoid too many searches while typing
                searchTimeout = setTimeout(() => {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    let hasResults = false;

                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        const userName = row.querySelector('.user-name');
                        const email = row.querySelector('td:nth-child(2)');
                        const phone = row.querySelector('td:nth-child(3)');
                        
                        if (searchTerm === '') {
                            // Reset everything if search is empty
                            row.style.display = '';
                            row.classList.remove('search-no-results');
                            resetHighlight(userName);
                            resetHighlight(email);
                            resetHighlight(phone);
                            hasResults = true;
                        } else if (text.includes(searchTerm)) {
                            // Show and highlight matching rows
                            row.style.display = '';
                            row.classList.remove('search-no-results');
                            highlightText(userName, searchTerm);
                            highlightText(email, searchTerm);
                            highlightText(phone, searchTerm);
                            hasResults = true;
                        } else {
                            // Hide non-matching rows
                            row.style.display = 'none';
                            row.classList.add('search-no-results');
                        }
                    });

                    // Show/hide no results message
                    const noResultsRow = document.querySelector('.no-results')?.closest('tr');
                    if (noResultsRow) {
                        noResultsRow.style.display = !hasResults ? '' : 'none';
                    }
                }, 200);
            });
        }

        function highlightText(element, searchTerm) {
            if (!element) return;
            
            const text = element.textContent;
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            
            // Keep existing classes and only replace text content
            const existingClasses = element.className;
            element.innerHTML = text.replace(regex, '<span class="search-results-highlight">$1</span>');
            element.className = existingClasses;
        }

        function resetHighlight(element) {
            if (!element) return;
            
            const text = element.textContent;
            const existingClasses = element.className;
            element.innerHTML = text;
            element.className = existingClasses;
        }

        // Initialize the dynamic search when the document is ready
        document.addEventListener('DOMContentLoaded', initializeDynamicSearch);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter des effets d'animation au chargement de la page
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
            });

            // Améliorer les boutons d'action
            const actionButtons = document.querySelectorAll('.btn');
            actionButtons.forEach(btn => {
                btn.addEventListener('mousedown', function(e) {
                    const x = e.clientX - e.target.getBoundingClientRect().left;
                    const y = e.clientY - e.target.getBoundingClientRect().top;
                    const ripple = document.createElement('span');
                    ripple.className = 'ripple';
                    ripple.style.left = `${x}px`;
                    ripple.style.top = `${y}px`;
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Améliorer l'expérience de recherche
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('focus', () => {
                    document.querySelector('.search-box').style.transform = 'scale(1.02)';
                });

                searchInput.addEventListener('blur', () => {
                    document.querySelector('.search-box').style.transform = 'scale(1)';
                });
            }

            // Créer un effet de loading au changement de filtre
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.addEventListener('submit', function() {
                    document.querySelector('.table-responsive').classList.add('loading');
                    
                    // Simulation d'un délai pour le chargement
                    setTimeout(() => {
                        document.querySelector('.table-responsive').classList.remove('loading');
                    }, 500);
                });
            }
        });
    </script>
</body>
</html>