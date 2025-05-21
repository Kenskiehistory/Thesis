<?php
ob_start();
// Include database connection
include('includes/db_connect.php');
include('includes/function.php');

get_message();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Add reCAPTCHA verification function
function verifyRecaptcha($recaptcha_response) {
    $secret = '6LdKjl4qAAAAABZnF1CG8fFqQspKFMbRl3CfWgCY';
    $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $recaptcha_response);
    $response_data = json_decode($verify_response);
    return $response_data->success;
}

if (isset($_POST["email"])) {
    // Verify reCAPTCHA
    $recaptcha_response = $_POST['g-recaptcha-response'];
    if (!verifyRecaptcha($recaptcha_response)) {
        set_message('Please complete the reCAPTCHA.');
        header('Location: /sample/login_page.php');
        exit();
    }

    if ($stm = $conn->prepare('SELECT * FROM users_new WHERE email = ? AND password = ? AND active = 1')) {
        $hashed = SHA1($_POST["password"]);
        $stm->bind_param("ss", $_POST['email'], $hashed);
        $stm->execute();

        $result = $stm->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['roles'] = $user['roles'];

            // Check if the password has been changed
            if (!$user['password_changed']) {
                header('Location: change_password.php');
                exit();
            }

            // Fetch section_id based on user role
            if ($user['roles'] === 'Staff') {
                if ($stm_staff = $conn->prepare('SELECT firstName, lastName, section_id, courseName FROM staff WHERE user_id = ?')) {
                    $stm_staff->bind_param('i', $user['id']);
                    $stm_staff->execute();
                    $stm_staff->bind_result($firstName, $lastName, $section_id, $course);
                    $stm_staff->fetch();
                    $stm_staff->close();

                    $_SESSION['firstName'] = $firstName;
                    $_SESSION['lastName'] = $lastName;
                    $_SESSION['section_id'] = $section_id;
                    $_SESSION['course'] = $course;


                    header('Location: staff_dashboard.php');
                    exit();
                } else {
                    echo 'Could not prepare statement for fetching staff details!';
                    exit();
                }
            } elseif ($user['roles'] === 'Student') {
                if ($stm_student = $conn->prepare('SELECT courseName, section_id FROM user_profile WHERE user_id = ?')) {
                    $stm_student->bind_param('i', $user['id']);
                    $stm_student->execute();
                    $stm_student->bind_result($course, $section_id);
                    $stm_student->fetch();
                    $stm_student->close();

                    $_SESSION['course'] = $course;
                    $_SESSION['section_id'] = $section_id;

                    // Redirect to the designated dashboard based on the course
                    switch ($course) {
                        case 'Architecture':
                            header('Location:student_dashboard.php');
                            break;
                        case 'Civil Engineering':
                            header('Location:student_dashboard.php');
                            break;
                        case 'Mechanical Engineering':
                            header('Location:student_dashboard.php');
                            break;
                        default:
                            header('Location: index.php');
                            break;
                    }
                    exit();
                } else {
                    echo 'Could not prepare statement for fetching user profile!';
                    exit();
                }
            }
        } else {
            set_message('Invalid Email or Password');
            header('Location: /sample/login_page.php');
            exit();
        }
        $stm->close();
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
    <title>Login - Demo Company</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .logo {
            max-height: 60px;
        }

        .nav-link {
            color: #0e1442 !important;
        }

        .hero-section {
            background-color: #7BA6B4;
            color: #f8f9fa;
            padding: 50px 0;
        }

        .login-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        }

        .card {
            --bs-card-spacer-y: 4rem;
            --bs-card-spacer-x: 7rem;
        }

        .img-hover-zoom {
            transition: transform 0.3s ease;
        }

        .img-hover-zoom:hover {
            transform: scale(1.05);
        }

        .btn-primary {
            background-color: #0e1442;
            border-color: #0e1442;
        }

        .btn-primary:hover {
            background-color: #001f3e;
            border-color: #001f3e;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .btn-enroll {
            background-color: #002e5b;
            color: #fff;
            border-radius: 5px;
        }

        .btn-enroll:hover {
            background-color: #001f3e;
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="#" class="navbar-brand">
                    <img src="all/images/image2 (1).png" alt="Logo" class="logo">
                </a>
                <nav>
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Courses
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="archipage.php">Architecture</a></li>
                                <li><a class="dropdown-item" href="civilpage.php">Civil Engineering</a></li>
                                <li><a class="dropdown-item" href="mecheng.php">Mechanical Engineering</a></li>
                                <li><a class="dropdown-item" href="MP.php">Master Plumber</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutus.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login_page.php">Login</a>
                        </li>
                    </ul>
                    </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1>
About Ace Review Center

</h1>
            <p class="lead">
            Enroll now for a better future</p>
        </div>
    </section>

    <!-- Login Form -->
    <div class="container-fluid form-container">
        <div class="row w-100">
            <div class="col-md-6 img-hover-zoom">
                <img src="all/images/image2 (1).jpg" alt="Login Image" class="img-fluid rounded">
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="card login-card p-4">
                    <div class="card-body">
                        <h2 class="mb-4 text-center" style="color: #0e1442;">Login</h2>
                        <form method="post">
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" style="background-color:#f0f0f0;" required />
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" style="background-color: #f0f0f0;" required />
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="g-recaptcha mb-3" data-sitekey="6LdKjl4qAAAAANLkvph_Wr56hVy64gy7MBBkAuxM"></div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block mb-3">Sign in</button>
                            <a href="register_new.php" class="btn btn-success w-100">Register</a>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 Demo Company. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>