<?php
include('includes/db_connect.php');
include('includes/function.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    die();
}

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Password rule: at least 8 characters, 1 uppercase, 1 lowercase, 1 number, and 1 special character
    $password_regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';

    if ($new_password === $confirm_password) {
        if (preg_match($password_regex, $new_password)) {
            $hashed_password = SHA1($new_password);
            $stmt = $conn->prepare('UPDATE users_new SET password = ?, password_changed = TRUE WHERE id = ?');
            $stmt->bind_param('si', $hashed_password, $user_id);
            $stmt->execute();
            $stmt->close();

            // Fetch the user's courseName from user_profile table
            $stmt = $conn->prepare('SELECT courseName FROM user_profile WHERE user_id = ?');
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->bind_result($courseName);
            $stmt->fetch();
            $stmt->close();

            // Determine the redirect URL based on the courseName
            switch ($courseName) {
                case 'Architecture':
                    $redirect_url = '/sample';
                    break;
                case 'Civil Engineering':
                    $redirect_url = '/sample';
                    break;
                // Add more cases as needed
                default:
                    $redirect_url = '/sample';
                    break;
            }

            set_message('Password successfully updated.');
            header('Location: ' . $redirect_url);
            die();
        } else {
            set_message('Password must be at least 8 characters long, contain one uppercase letter, one lowercase letter, one number, and one special character.');
        }
    } else {
        set_message('Passwords do not match.');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <title>Change Password</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 15px;
        }
        .card-body {
            padding: 3rem;
        }
        .form-control {
            border-radius: 25px;
        }
        .btn-primary {
            border-radius: 25px;
            padding: 10px 30px;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .password-strength {
            margin-top: 10px;
            font-size: 0.9rem;
        }
        .strength-meter {
            height: 4px;
            width: 100%;
            background: #ddd;
            margin-top: 5px;
        }
        .strength-meter span {
            display: block;
            height: 100%;
            transition: width 0.3s;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h1 class="card-title text-center mb-4">Change Password</h1>
                    <?php get_message(); ?>
                    <form method="post" action="">
                        <div class="form-group password-container">
                            <label for="new_password">New Password:</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                            <i class="toggle-password fas fa-eye" id="toggleNewPassword"></i>
                            <div class="password-strength">
                                <div>Password strength: <span id="strength-text"></span></div>
                                <div class="strength-meter"><span></span></div>
                            </div>
                        </div>
                        <div class="form-group password-container">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            <i class="toggle-password fas fa-eye" id="toggleConfirmPassword"></i>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg mt-3">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const form = document.querySelector('form');
        const toggleNewPassword = document.getElementById('toggleNewPassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const strengthMeter = document.querySelector('.strength-meter span');
        const strengthText = document.getElementById('strength-text');

        // Password visibility toggle
        function togglePasswordVisibility(inputElement, toggleElement) {
            if (inputElement.type === 'password') {
                inputElement.type = 'text';
                toggleElement.classList.remove('fa-eye');
                toggleElement.classList.add('fa-eye-slash');
            } else {
                inputElement.type = 'password';
                toggleElement.classList.remove('fa-eye-slash');
                toggleElement.classList.add('fa-eye');
            }
        }

        toggleNewPassword.addEventListener('click', () => togglePasswordVisibility(newPasswordInput, toggleNewPassword));
        toggleConfirmPassword.addEventListener('click', () => togglePasswordVisibility(confirmPasswordInput, toggleConfirmPassword));

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^A-Za-z0-9]/)) strength += 1;

            switch (strength) {
                case 0:
                case 1:
                    return { text: 'Weak', color: '#ff4d4d', width: '20%' };
                case 2:
                case 3:
                    return { text: 'Moderate', color: '#ffa64d', width: '50%' };
                case 4:
                    return { text: 'Strong', color: '#ffff4d', width: '75%' };
                case 5:
                    return { text: 'Very Strong', color: '#4dff4d', width: '100%' };
            }
        }

        newPasswordInput.addEventListener('input', function() {
            const result = checkPasswordStrength(this.value);
            strengthText.textContent = result.text;
            strengthMeter.style.width = result.width;
            strengthMeter.style.backgroundColor = result.color;
        });

        // Form submission validation
        form.addEventListener('submit', function (event) {
            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            // Password rule: at least 8 characters, 1 uppercase, 1 lowercase, 1 number, and 1 special character
            const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (!passwordRegex.test(password)) {
                event.preventDefault();
                alert('Password must be at least 8 characters long, contain one uppercase letter, one lowercase letter, one number, and one special character.');
            }

            if (password !== confirmPassword) {
                event.preventDefault();
                alert('Passwords do not match.');
            }
        });
    });
</script>

</body>
</html>