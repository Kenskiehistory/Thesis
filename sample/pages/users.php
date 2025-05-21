<?php
include('../includes/db_connect.php');
include('../includes/config.php');

// Fetch users
$users = [];
if ($stm = $conn->prepare('SELECT * FROM users_new')) {
    $stm->execute();
    $result = $stm->get_result();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stm->close();
}
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">User Management</h4>
                            <p class="text-muted mb-0">Manage User Accounts and Access</p>
                        </div>
                        <button class="btn btn-outline-primary btn-add-user">
                            <i class="mdi mdi-account-plus"></i> Add New User
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="userManagementTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="">Username</th>
                                    <th class="">Email</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class=""><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td class=""><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td class="text-center">
                                            <span class="badge <?php echo $user['active'] == 1 ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo $user['active'] == 1 ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="text-center"><?php echo htmlspecialchars($user['roles']); ?></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-primary btn-sm edit-user" data-id="<?php echo $user['id']; ?>">
                                                    <i class="mdi mdi-pencil me-1"></i>Edit
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="mdi mdi-account-edit me-2"></i>Edit User Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editUsername" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                                <input type="text" class="form-control" id="editUsername" name="username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="editRole" class="form-label">Role</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-account-group"></i></span>
                                <select class="form-select" id="editRole" name="roles" required>
                                    <option value="Staff">Staff</option>
                                    <option value="Student">Student</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="editActive" class="form-label">Status</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-toggle-switch"></i></span>
                                <select class="form-select" id="editActive" name="active" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" id="saveUserChanges">
                    <i class="mdi mdi-content-save me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .card {
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
    }
    .input-group-text {
        background-color: transparent;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .btn-group .btn {
        display: inline-flex;
        align-items: center;
    }
    .modal-header {
        padding: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
</style>