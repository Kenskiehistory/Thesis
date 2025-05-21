<?php
include('includes/header.php');
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
    <?php include('includes/sidebar.php'); ?>

    <main class="main-content">
        <div id="content">
            <!-- Content will be loaded here -->
        </div>
    </main>
</div>
<script src="app.js"></script>
<?php
include('includes/footer.php');
?>