<?php
include('includes/student_header.php');
include('includes/function.php');
secure();
check_role('Student');
?>
<div class="container">
    <header class="mobile-header">
        <div class="logo">Logo</div>
        <button class="menu-toggle" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>

    <!-- Include the sidebar -->
    <?php include('includes/student_sidebar.php'); ?>

    <main class="main-content">
        <!-- Add a container for mini announcements -->
        <div id="mini-announcements" class="d-none">
            <!-- Mini announcements will be loaded here -->
        </div>
        <div id="content">
            <!-- Content will be loaded here -->
        </div>
    </main>
</div>
<script src="student_app.js"></script>
<?php
include('includes/footer.php');
?>