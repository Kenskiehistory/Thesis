<?php
// dashboard.php

include('../includes/db_connect.php');
include('../includes/function.php');

try {
    // Fetch the number of paid students
    $paid_students_count = get_paid_students_count($conn);

    // Query to count the number of staff members
    $staff_count_result = $conn->query("SELECT COUNT(*) as count FROM staff");
    if (!$staff_count_result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    $staff_count_row = $staff_count_result->fetch_assoc();
    $staff_count = $staff_count_row['count'];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
include ('../includes/header.php');
?>
<div class="row">
                <div class="col-12 grid-margin">
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card d-flex align-items-center justify-content-center">
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        Number of students: <br>
                                        <strong><?php echo htmlspecialchars($paid_students_count); ?></strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card d-flex align-items-center justify-content-center">
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        Number of staff members: <br>
                                        <strong><?php echo htmlspecialchars($staff_count); ?></strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
// You can add PHP code here, for example:
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
include ('../includes/footer.php');
?>
