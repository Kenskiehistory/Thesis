<?php
include('includes/staff_header.php');
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
    <?php include('includes/staff_sidebar.php'); ?>

    <main class="main-content">
        <div id="content">
            <!-- Content will be loaded here -->
        </div>
    </main>
</div>
<script src="staff_app.js"></script>
<?php
include('includes/footer.php');
?>