<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="<?php echo isset($isSubPage) ? '../' : ''; ?>index.php" class="b-brand">
                <img src="<?php echo isset($isSubPage) ? '../' : ''; ?>assets/images/logo.svg" alt="" class="logo logo-lg">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                    <a href="<?php echo isset($isSubPage) ? '../' : ''; ?>index.php" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <li class="pc-item <?php echo $currentPage === 'users-list' ? 'active' : ''; ?>">
                    <a href="<?php echo isset($isSubPage) ? '' : 'pages/'; ?>usersList.php" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-users"></i></span>
                        <span class="pc-mtext">Users List</span>
                    </a>
                </li>

                <li class="pc-item <?php echo $currentPage === 'add-user' ? 'active' : ''; ?>">
                    <a href="<?php echo isset($isSubPage) ? '' : 'pages/'; ?>add-user.php" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                        <span class="pc-mtext">Add User</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <span>COMING SOON</span>
                    <span>New Features</span>
                </li>

                <li class="pc-item disabled">
                    <a href="#" class="pc-link" onclick="return false;">
                        <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                        <span class="pc-mtext">Events</span>
                    </a>
                </li>

                <li class="pc-item disabled">
                    <a href="#" class="pc-link" onclick="return false;">
                        <span class="pc-micon"><i class="ti ti-briefcase"></i></span>
                        <span class="pc-mtext">Emploi</span>
                    </a>
                </li>

                <li class="pc-item disabled">
                    <a href="#" class="pc-link" onclick="return false;">
                        <span class="pc-micon"><i class="ti ti-bell"></i></span>
                        <span class="pc-mtext">Notifications</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
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
    background: rgba(70, 128, 255, 0.1);
    color: #4680FF;
}

.pc-navbar .pc-item.active .pc-link {
    background: #4680FF;
    color: #ffffff;
    box-shadow: 0 4px 8px rgba(70, 128, 255, 0.2);
}

.pc-navbar .pc-item.disabled .pc-link {
    opacity: 0.6;
    cursor: not-allowed;
}

.pc-navbar .pc-item .pc-micon {
    margin-right: 10px;
    width: 35px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(70, 128, 255, 0.1);
}

.pc-navbar .pc-item.active .pc-micon {
    background: rgba(255, 255, 255, 0.2);
}

.pc-item.pc-caption {
    margin: 20px 0 5px;
    padding: 10px 15px;
}

.pc-item.pc-caption span:first-child {
    color: #4680FF;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pc-item.pc-caption span:last-child {
    color: #67748e;
    font-size: 11px;
    display: block;
    margin-top: 4px;
}
</style>

<!-- Header -->
<header class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <li class="pc-h-item">
                    <a class="pc-head-link me-0" href="#" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button">
                        <i class="ti ti-user-circle"></i>
                        <span>
                            <span class="user-name"><?php echo $_SESSION['admin']['nom'] ?? 'Admin'; ?></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <a href="../logout.php" class="dropdown-item">
                            <i class="ti ti-logout"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>