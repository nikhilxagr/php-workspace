<?php
// ============================================
// Manage Users - Admin Panel
// File: admin/users.php
// ============================================

require_once '../auth/auth_check.php';
require_once '../auth/role_check.php';

// Strict server-side admin role check
require_role(['admin']);

require_once '../config/db.php';
$base_path = '../';

// Filter and search parameters
$role_filter = trim($_GET['role'] ?? '');
$search      = trim($_GET['search'] ?? '');

try {
    $sql = "SELECT id, name, email, role, created_at FROM users WHERE 1=1";
    $params = [];

    // Filter by role
    if (!empty($role_filter) && in_array($role_filter, ['student', 'teacher', 'admin'], true)) {
        $sql .= " AND role = :role";
        $params[':role'] = $role_filter;
    }

    // Search by name or email
    if (!empty($search)) {
        $sql .= " AND (name LIKE :search OR email LIKE :search)";
        $params[':search'] = "%" . $search . "%";
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll();

    // Counts for filter pills
    $count_all      = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $count_students = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
    $count_teachers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'teacher'")->fetchColumn();
    $count_admins   = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();

} catch (PDOException $e) {
    error_log("Users list query error: " . $e->getMessage());
    $users = [];
    $count_all = $count_students = $count_teachers = $count_admins = 0;
}

$page_title = "Manage Users - Admin Panel";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="main-content">
    <div class="container">
        
        <div class="page-header-flex">
            <div>
                <h1>👥 Manage Library Users</h1>
                <p>View registered student and faculty accounts and their borrowing profiles.</p>
            </div>
            <div>
                <a href="add-teacher.php" class="btn btn-primary">➕ Register Teacher</a>
            </div>
        </div>

        <!-- Filter Buttons Bar -->
        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px;">
            <a href="users.php" class="btn <?php echo empty($role_filter) ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                All Users (<?php echo $count_all; ?>)
            </a>
            <a href="users.php?role=student" class="btn <?php echo ($role_filter === 'student') ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                Students (<?php echo $count_students; ?>)
            </a>
            <a href="users.php?role=teacher" class="btn <?php echo ($role_filter === 'teacher') ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                Teachers (<?php echo $count_teachers; ?>)
            </a>
            <a href="users.php?role=admin" class="btn <?php echo ($role_filter === 'admin') ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                Admins (<?php echo $count_admins; ?>)
            </a>
        </div>

        <!-- Search Bar -->
        <form action="users.php" method="GET" class="search-filter-box">
            <?php if (!empty($role_filter)): ?>
                <input type="hidden" name="role" value="<?php echo htmlspecialchars($role_filter); ?>">
            <?php endif; ?>

            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search user by name or email..." 
                value="<?php echo htmlspecialchars($search); ?>"
            >
            <button type="submit" class="btn btn-primary" style="padding: 9px 18px;">Search</button>
            <?php if (!empty($search) || !empty($role_filter)): ?>
                <a href="users.php" class="btn btn-outline" style="padding: 9px 18px;">Reset</a>
            <?php endif; ?>
        </form>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="text-align: center;">Role</th>
                        <th>Created At</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                No users found matching your criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <span style="color: var(--text-muted); font-size: 0.85rem;">
                                        #<?php echo $u['id']; ?>
                                    </span>
                                </td>
                                <td><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td style="text-align: center;">
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="badge badge-danger">Admin</span>
                                    <?php elseif ($u['role'] === 'teacher'): ?>
                                        <span class="badge badge-category" style="background-color: #fef3c7; color: #92400e; border-color: #fde68a;">Teacher</span>
                                    <?php else: ?>
                                        <span class="badge badge-category">Student</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                                <td style="text-align: center;">
                                    <a href="user-details.php?id=<?php echo (int)$u['id']; ?>" class="btn btn-outline btn-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
