<?php
include('../includes/db_connect.php');
include('../includes/config.php');

// Get the user data
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $user = $conn->query("SELECT * FROM users_new WHERE id = $userId")->fetch_assoc();
} else {
    die('User ID is missing.');
}
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit User</h4>
                    <form id="updateUserForm" method="POST" action="pages/update_user.php">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="roles">Role</label>
                            <select id="roles" name="roles" class="form-select">
                                <option value="Admin" <?php if ($user['roles'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                <option value="Staff" <?php if ($user['roles'] == 'Staff') echo 'selected'; ?>>Staff</option>
                                <option value="Student" <?php if ($user['roles'] == 'Student') echo 'selected'; ?>>Student</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="active">Active Status</label>
                            <select id="active" name="active" class="form-select">
                                <option value="1" <?php if ($user['active'] == 1) echo 'selected'; ?>>Active</option>
                                <option value="0" <?php if ($user['active'] == 0) echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>