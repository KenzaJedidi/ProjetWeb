<?php
session_start();
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
    <style>
        .card {
            border: none;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        .card-header {
            background: #fff;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .table-responsive {
            padding: 0;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            color: #344767;
            font-weight: 600;
            border-bottom: 2px solid #eee;
        }

        .table tbody td {
            vertical-align: middle;
            color: #67748e;
            border-bottom: 1px solid #eee;
        }

        .avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e9ecef;
            color: #344767;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .user-info {
            margin-left: 10px;
        }

        .user-name {
            color: #344767;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .badge {
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        .btn-group .btn {
            padding: 6px 12px;
            border-radius: 6px;
            margin: 0 2px;
        }

        .btn-group .btn i {
            font-size: 1rem;
        }

        .search-box {
            position: relative;
            max-width: 300px;
        }

        .search-box .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #67748e;
            font-size: 1.1rem;
        }

        .search-box input {
            padding-left: 40px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            min-width: 140px;
        }

        .btn-primary {
            background: #4680FF;
            border: none;
            padding: 8px 16px;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-light {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .btn-success {
            background: #2ecc71;
            border: none;
            padding: 8px 16px;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-success:hover {
            background: #27ae60;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(46,204,113,0.2);
        }

        .no-results {
            padding: 40px 20px;
            text-align: center;
            color: #67748e;
        }

        .no-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #95aac9;
        }

        .d-flex.gap-2 {
            display: flex;
            gap: 0.5rem !important;
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                gap: 1rem;
            }

            .filter-controls {
                flex-direction: column;
                width: 100%;
            }

            .form-select, .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .search-box {
                max-width: 100%;
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
                        <div class="card-body border-bottom">
                            <form id="filterForm" method="get" action="">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="search-box">
                                            <i class="ti ti-search search-icon"></i>
                                            <input type="text" class="form-control" id="searchInput" 
                                                   name="search" placeholder="Search users..." 
                                                   value="<?php echo htmlspecialchars($searchTerm); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex gap-2 justify-content-md-end">
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
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ti ti-filter me-1"></i> Apply
                                            </button>
                                            <button type="button" class="btn btn-light" onclick="resetFilters()">
                                                <i class="ti ti-refresh me-1"></i> Reset
                                            </button>
                                        </div>
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
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm">
                                                            <?php if ($user->getProfilePicture()): ?>
                                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($user->getProfilePicture()); ?>" 
                                                                     alt="Profile">
                                                            <?php else: ?>
                                                                <?php echo strtoupper(substr($user->getNom(), 0, 1)); ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="user-info">
                                                            <h6 class="user-name">
                                                                <?php echo htmlspecialchars($user->getNom() . ' ' . $user->getPrenom()); ?>
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
                                                    <div class="btn-group">
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

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('roleFilter').value = '';
            document.getElementById('sortBy').value = 'name';
            document.getElementById('filterForm').submit();
        }
    </script>
</body>
</html>