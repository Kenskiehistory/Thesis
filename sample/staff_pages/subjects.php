<?php
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Staff');  // Assuming you have a function to check for 'Staff' role
include('../includes/header.php');

// Get the logged-in user's ID
$user_id = $_SESSION['id'];  // Assuming you store the logged-in user's ID in the session

// Get the logged-in user's courseName
$stmt = $conn->prepare('
    SELECT courseName 
    FROM staff 
    WHERE user_id = ?
');
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

// Fetch subjects based on the courseName
$stmt = $conn->prepare('
    SELECT * 
    FROM subjects
    WHERE courseName = ?
');
$stmt->bind_param("s", $courseName);
$stmt->execute();
$subjects = $stmt->get_result();
$stmt->close();
?>

<div class="container-fluid page-body-wrapper">
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <?php if ($subjects->num_rows > 0): ?>
                    <?php while ($subject = $subjects->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($subject['subjectName']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($subject['description']); ?></p>
                                    <button class="btn btn-info view-subject" data-subject-id="<?php echo $subject['id']; ?>">View</button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No subjects assigned to you.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- main-panel ends -->
</div>
