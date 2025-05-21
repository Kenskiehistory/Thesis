<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Civil Engineering</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .img-hover-zoom {
            transition: transform 0.3s ease;
        }

        .img-hover-zoom:hover {
            transform: scale(1.05);
        }

        .card-title {
            font-size: 1.25rem;
        }

        .card-text {
            font-size: 1rem;
            color: #6c757d;
        }

        h2 {
            color: #0e1442;
        }

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
            padding: 10px 0;
        }

        .section {
            padding: 20px 0;
        }

        .team-member img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }

        .btn-primary {
            background-color: #0e1442;
            border-color: #0e1442;
        }

        .btn-primary:hover {
            background-color: #0e1442;
            border-color: #0e1442;
        }

        .core-values-section {
            padding: 50px;
        }

        .core-value {
            text-align: center;
            margin-bottom: 40px;
        }

        .core-value h4 {
            font-weight: bold;
        }

        .core-value p {
            color: #6c757d;
        }

        .icon {
            font-size: 40px;
            color: #0d6efd;
            margin-bottom: 15px;
        }

        .program-section {
            background-color: #f28c24;
            color: #fff;
            padding: 50px 0;
        }

        .program-content h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .program-content p {
            font-size: 1.2rem;
            color: #fff;
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

        .program-image img {
            border-radius: 10px;
        }

        .details-header {
            background-color: #002e5b;
            color: white;
            padding: 20px 0;
        }

        .details-header h6 {
            font-size: 1rem;
        }

        .details-header p {
            margin: 0;
            font-weight: bold;
        }

        .requirements-section {
            padding: 40px 0;
        }

        .requirements-section img {
            border-radius: 10px;
        }

        .features-section {
            background-color: #f8f9fa;
            padding: 40px 0;
        }

        .feature-box {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
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

    <section class="program-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 program-content">
                    <h1>Civil Engineering Review Program</h1>
                    <p>The Civil Engineering Review Program is 5 months of review in preparation for the Civil Engineering Licensure Exam. Graduates of Civil Engineering are eligible to take the board exam.</p>
                    <a href="#" class="btn btn-primary btn-lg">Enroll Now!</a>
                </div>
                <div class="col-md-6 program-image text-center">
                    <img src="all/images/image2 (3).jpg" alt="Civil Engineering" class="img-fluid">
                </div>
            </div>
        </div>
    </section>


    <section class="details-header">
        <div class="container text-center text-white">
            <div class="row">
                <div class="col-md-4">
                    <h6>Starts On</h6>
                    <p>March 20, 2023</p>
                </div>
                <div class="col-md-4">
                    <h6>Duration</h6>
                    <p>12 Months, Online <br>15-18 Hours/Week</p>
                </div>
                <div class="col-md-4">
                    <h6>Program Fee</h6>
                    <p>Php 20,000 <br><small>Flexible payment available</small></p>
                </div>
            </div>
        </div>
    </section>

    <section class="requirements-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Course Requirements</h2>
                    <p>The review program includes review books, face-to-face or online review options, and classes are held every weekend. Download the course modules to learn more about the review program. You may also check the link to see the PRC requirements in preparation for the board exam.</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="all/images/image2 (8).jpg" alt="Course Requirements" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container text-center">
            <h2>Course Features</h2>
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon text-warning">
                            📚
                        </div>
                        <h5>Comprehensive Curriculum and Review Manuals</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon text-dark">
                            📝
                        </div>
                        <h5>Weekly Quizzes</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon text-primary">
                            🧠
                        </div>
                        <h5>Mid and Final Mock Board Exams</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon text-danger">
                            🎓
                        </div>
                        <h5>Top-Notcher Lecturers</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon text-warning">
                            🎥
                        </div>
                        <h5>Recorded Lectures Available Anytime</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon text-success">
                            🤝
                        </div>
                        <h5>Face-to-Face and Online Options</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>

    