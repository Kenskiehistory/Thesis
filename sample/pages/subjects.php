<?php
include('../includes/db_connect.php');
include('../includes/header.php');

// Fetch all subjects
$subjectsResult = $conn->query('SELECT * FROM subjects');

// Fetch all unique course names
$coursesResult = $conn->query('SELECT DISTINCT courseName FROM subjects');
?>

<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h4 class="card-title">Subjects</h4>
                </div>
                <div class="col-md-6 text-right">
                    <button id="addNewSubjectBtn" class="btn btn-primary">Add New Subject</button>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <!-- Add course filter dropdown -->
                    <div class="form-group">
                        <label for="courseFilter">Filter by Course:</label>
                        <select id="courseFilter" class="form-control">
                            <option value="">All Courses</option>
                            <?php while ($course = $coursesResult->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($course['courseName']); ?>">
                                    <?php echo htmlspecialchars($course['courseName']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="row" id="subjectsContainer">
                        <?php while ($subject = $subjectsResult->fetch_assoc()): ?>
                            <div class="col-md-4 mb-4 subject-card" data-course="<?php echo strtolower(htmlspecialchars($subject['courseName'])); ?>">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?php echo htmlspecialchars($subject['subjectName']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($subject['description']); ?></p>
                                        <p class="card-text"><strong>Course:</strong> <?php echo htmlspecialchars($subject['courseName']); ?></p>
                                        <div class="mt-auto">
                                            <p class="card-text">
                                                <strong>Status:</strong> 
                                                <span class="status-text <?php echo $subject['is_active'] ? 'text-success' : 'text-danger'; ?>">
                                                    <?php echo $subject['is_active'] ? 'Active' : 'Inactive'; ?>
                                                </span>
                                            </p>
                                            <button class="btn btn-sm toggle-active-btn <?php echo $subject['is_active'] ? 'btn-danger' : 'btn-success'; ?>"
                                                    data-subject-id="<?php echo $subject['id']; ?>"
                                                    data-status="<?php echo $subject['is_active']; ?>">
                                                <?php echo $subject['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>