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
            position: relative;
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

        .user-banned {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .user-banned td {
            color: #dc3545;
        }

        .badge-banned {
            background-color: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .table tbody tr.banned-user {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .banned-user td {
            color: #dc3545;
        }

        .banned-badge {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1.4;
            vertical-align: middle;
        }

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
            background-color: #dc3545;
        }

        .ban-toggle-btn {
            transition: all 0.3s ease;
        }

        .ban-toggle-btn.btn-success {
            background-color: #198754;
        }

        .ban-toggle-btn.btn-danger {
            background-color: #dc3545;
        }

        .ban-toggle-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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

        .banned-user {
            background-color: rgba(220, 53, 69, 0.05) !important;
        }

        .banned-user td {
            color: #dc3545;
        }

        .search-results-highlight {
            background-color: rgba(70, 128, 255, 0.1);
            border-radius: 3px;
            padding: 0 2px;
        }

        .search-no-results {
            opacity: 0.3;
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body>
    <?php
    include '../includes/sidebar.php';
    ?>
    
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
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="searchInput" 
                                                   name="search" 
                                                   placeholder="Search users..." 
                                                   value="<?php echo htmlspecialchars($searchTerm); ?>"
                                                   autocomplete="off">
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
</body>
</html>