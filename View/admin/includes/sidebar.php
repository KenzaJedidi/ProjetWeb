<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="../index.php" class="b-brand">
                <!-- Logo -->
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
                    <a href="add-user.php" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                        <span class="pc-mtext">Add User</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="usersList.php" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-users"></i></span>
                        <span class="pc-mtext">Users List</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

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