<?php
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Student');
include('../includes/header.php');

$user_id = $_SESSION['id'];

$stmt = $conn->prepare('SELECT courseName FROM user_profile WHERE user_id = ?');
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $courseName = $user['courseName'];
} else {
    echo "Your course could not be found.";
    include('../includes/footer.php');
    exit();
}

$stmt->close();

// Fetch only active subjects based on the courseName
$stmt = $conn->prepare('
    SELECT * 
    FROM subjects
    WHERE courseName = ? AND is_active = 1
');
$stmt->bind_param("s", $courseName);
$stmt->execute();
$subjects = $stmt->get_result();
$stmt->close();
?>
<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <?php if ($subjects->num_rows > 0): ?>
                    <?php while ($subject = $subjects->fetch_assoc()): ?>
                        <div class="col-md-4 d-flex">
                            <div class="card mb-4 w-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($subject['subjectName']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($subject['description']); ?></p>
                                    <a href="student_pages/view_subject_details.php?id=<?php echo $subject['id']; ?>" class="btn btn-info mt-auto">View</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No active subjects assigned to you.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>