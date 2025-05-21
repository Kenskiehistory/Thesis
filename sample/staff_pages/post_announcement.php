<?php
ob_start();
include('../includes/config.php');
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Staff');
include('../includes/staff_header.php');

// Retrieve the courseName from the session
$courseName = isset($_SESSION['courseName']) ? $_SESSION['courseName'] : '';
?>

<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Post Announcement</h4>
                    <p class="card-description">Post an announcement for a course</p>
                    <form id="announcementForm" class="forms-sample">
                        <!-- Hidden input field for courseName -->
                        <input type="hidden" name="courseName" id="courseName" value="<?php echo htmlspecialchars($courseName); ?>" required>
                        
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Post Announcement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>