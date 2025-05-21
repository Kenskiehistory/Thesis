<?php
ob_start();
// Include database connection and functions
include('includes/db_connect.php');
include('includes/function.php');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is an admin
function is_admin($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT roles FROM users_new WHERE id = ? AND roles = 'Admin'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Check if user is logged in and is an admin
function require_admin() {
    if (!isset($_SESSION['id']) || !is_admin($_SESSION['id'])) {
        set_message("Access denied. Admin privileges required.");
        header('Location: login_page.php');
        exit();
    }
}

// Handle login form submission
if (isset($_POST["email"]) && isset($_POST["password"])) {
    if ($stmt = $conn->prepare('SELECT * FROM users_new WHERE email = ? AND password = ? AND active = 1')) {
        $hashed = SHA1($_POST["password"]);
        $stmt->bind_param("ss", $_POST['email'], $hashed);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && is_admin($user['id'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['roles'] = $user['roles'];

            if (!$user['password_changed']) {
                header('Location: change_password.php');
                exit();
            }

            // Redirect to admin dashboard
            header('Location: admin_dashboard.php');
            exit();
        } else {
            set_message('Invalid credentials or insufficient privileges');
        }
    } else {
        echo 'Could not prepare statement for user authentication!';
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login_page.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="#" class="navbar-brand">
                    <img src="/api/placeholder/120/40" alt="Logo" class="logo">
                </a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row login-container">
            <div class="col-lg-6 img-container">
                <img src="/api/placeholder/500/500" alt="Login Image" class="img-fluid">
            </div>
            <div class="col-lg-6 form-container">
                <div class="card rounded login-card">
                    <div class="card-body">
                        <?php
                        // Display any messages set by set_message()
                        if (isset($_SESSION['message'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['message'] . '</div>';
                            unset($_SESSION['message']);
                        }
                        ?>
                        <form class="login-form" method="POST" action="">
                            <h2 class="mb-4 text-center" style="color: #1a237e;">Admin Login</h2>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>