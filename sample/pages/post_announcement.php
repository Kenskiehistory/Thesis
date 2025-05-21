<?php
ob_start();
include('../includes/config.php');
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Admin');
include('../includes/header.php');

// Fetch available courses for the dropdown
$courses = ['Architecture', 'Civil Engineering', 'Mechanical Engineering', 'Master Plumber'];
?>

<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Post Announcement (Admin)</h4>
                    <p class="card-description">Post an announcement for a course</p>
                    <form id="announcementForm" class="forms-sample">
                        <div class="form-group">
                            <label for="courseName">Course</label>
                            <select name="courseName" id="courseName" class="form-control" required>
                                <option value="">Select Course</option>
                                <?php foreach ($courses as $course) : ?>
                                    <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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

<?php include('../includes/footer.php'); ?>