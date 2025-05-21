<?php
// Include necessary files
require_once('../includes/config.php');
require_once('../includes/db_connect.php');
require_once('../includes/function.php');

// Fetch available courses for the dropdown
$courses = ['Architecture', 'Civil Engineering', 'Mechanical Engineering', 'Master Plumber'];
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Add Subject</h4>
        <p class="card-description">Add a new subject</p>
        <form id="addSubForm" class="forms-sample" method="POST">
            <div class="form-group">
                <label class="form-label" for="subjectName">Subject Name</label>
                <input type="text" id="subjectName" name="subjectName" class="form-control" placeholder="Subject Name" required />
            </div>

            <div class="form-group">
                <label class="form-label" for="courseName">Course</label>
                <select name="courseName" id="courseName" class="form-select" required>
                    <?php foreach ($courses as $course) : ?>
                        <option value="<?php echo $course; ?>"><?php echo $course; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-control" placeholder="Description"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Add Subject</button>
        </form>
        <div id="response-message"></div>
    </div>
</div>
