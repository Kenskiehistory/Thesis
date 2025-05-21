<?php
include('../includes/db_connect.php');
include('../includes/config.php');
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add User</h4>
                    <form id="userForm" class="forms-sample">
                        <div class="form-group">
                            <label for="roles">Select Role</label>
                            <select name="roles" id="roles" class="form-select">
                                <option value="Staff">Staff</option>
                                <option value="Student">Student</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>

                        <div id="profile_selection" style="display: none;">
                            <label for="profile_id">Select Profile</label>
                            <select name="profile_id" id="profile_id" class="form-select">
                                <option value="">Select Profile</option>
                            </select>
                        </div>

                        <div id="admin_username" style="display: none;">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Username" />
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required />
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required />
                        </div>

                        <div class="form-group">
                            <label for="active">Active Status</label>
                            <select name="active" id="active" class="form-select" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>