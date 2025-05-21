<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page Ace+ Review Center</title>
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
        .btn-primary {
            background-color: #002e5b;
            color: #fff;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #001f3e;
            color: #fff;
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

    <section class="hero-section">
        <div class="container text-center">
            <h1>Welcome to Ace+ Review Center</h1>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Now Open For Enrollment!</h2>
                    <h2>November 202 Civil Engineering Review Program</h2>
                    <a href="#" class="btn btn-primary btn-lg">Enroll Now</a>
                </div>
                <div class="col-md-6">
                    <img src="all/images/image2 (1).jpg" alt="Our Story" class="card-img-top rounded img-hover-zoom">
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5 display-4 font-weight-bold">Courses</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="all/images/image2 (2).jpg" class="card-img-top rounded img-hover-zoom" alt="Architecture">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold">Architecture</h5>
                            <p class="card-text">A 5-month review program in preparation for the Architecture Licensure Exam.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="all/images/image2 (3).jpg" class="card-img-top rounded img-hover-zoom" alt="Civil Engineering">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold">Civil Engineering</h5>
                            <p class="card-text">A 5-month review program in preparation for the Civil Engineering Licensure Exam.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="all/images/image2 (4).jpg" class="card-img-top rounded img-hover-zoom" alt="Mechanical Engineering">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold">Mechanical Engineering</h5>
                            <p class="card-text">A 5-month review program in preparation for the Mechanical Engineering Licensure Exam.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="all/images/image2 (5).jpg" class="card-img-top rounded img-hover-zoom" alt="Master Plumber">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold">Master Plumber</h5>
                            <p class="card-text">A 4-month review program in preparation for the Master Plumber Licensure Exam.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="text">News Feed</h2>
                <a href="https://www.facebook.com/aceplusreview" class="btn btn-primary btn-lg">Follow Us On Facebook</a>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <div class="col-md-6 image-section">
                <img src="all/images/image2 (6).jpg" alt="Lecturer Image" class="img-fluid">
            </div>
            <div class="col-md-6 subscribe-text">
                <h1>Subscribe to our Youtube Channel</h1>
                <p>Lecture videos on all our review programs are uploaded to our Youtube Channel, which our students can watch anytime and anywhere.</p>
                <div class="subscribe-button">
                    <a href="https://www.youtube.com/channel/UC4fWC5Q4zgpXKnB93gezzZw" class="btn btn-primary btn-lg">Subscribe Now!</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</php>
