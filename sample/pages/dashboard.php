<?php
// dashboard.php

include('../includes/db_connect.php');
include('../includes/function.php');

try {
    // Fetch the number of paid students
    $paid_students_count = get_paid_students_count($conn);

    // Fetch the number of unpaid (pending) students
    $unpaid_students_result = $conn->query("SELECT COUNT(*) as count FROM waitlist WHERE payment_status = 'pending'");
    if (!$unpaid_students_result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    $unpaid_students_row = $unpaid_students_result->fetch_assoc();
    $unpaid_students_count = $unpaid_students_row['count'];

    // Query to count the number of staff members
    $staff_count_result = $conn->query("SELECT COUNT(*) as count FROM staff");
    if (!$staff_count_result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    $staff_count_row = $staff_count_result->fetch_assoc();
    $staff_count = $staff_count_row['count'];

    // Fetch all courses for the dropdown
    $courses_result = $conn->query("SELECT DISTINCT courseName FROM user_profile ORDER BY courseName");
    $courses = $courses_result->fetch_all(MYSQLI_ASSOC);

    // Initialize variables for date and course filtering
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
    $selected_course = isset($_GET['course']) ? $_GET['course'] : 'all';

    // Fetch total debit and credit collected within the date range and for the selected course
    $query = "SELECT SUM(ul.debit) as total_debit, SUM(ul.credit) as total_credit 
          FROM user_ledger ul
          JOIN user_profile up ON ul.user_profile_id = up.id
          WHERE ul.date BETWEEN ? AND ?";
    $params = [$start_date, $end_date];

    if ($selected_course !== 'all') {
        $query .= " AND up.courseName = ?";
        $params[] = $selected_course;
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_debit = $row['total_debit'] ? $row['total_debit'] : 0;
    $total_credit = $row['total_credit'] ? $row['total_credit'] : 0;
    $stmt->close();

    // Fetch data for exports with joined user profile information
    $user_ledger_data = $conn->query("
        SELECT ul.*, CONCAT(up.fName, ' ', up.mName, ' ', up.lName) AS full_name
        FROM user_ledger ul
        JOIN user_profile up ON ul.user_profile_id = up.id
        WHERE ul.date BETWEEN '$start_date' AND '$end_date'
    ")->fetch_all(MYSQLI_ASSOC);

    $staff_data = $conn->query("SELECT * FROM staff")->fetch_all(MYSQLI_ASSOC);

    $waitlist_data = $conn->query("
        SELECT w.*, CONCAT(up.fName, ' ', up.mName, ' ', up.lName) AS full_name
        FROM waitlist w
        JOIN user_profile up ON w.user_profile_id = up.id
    ")->fetch_all(MYSQLI_ASSOC);

    // Fetch user profile data
    $user_profile_data = $conn->query("
        SELECT id, fName, mName, lName, gender, conNumb, email_stud, pName, bDate, address_stud, courseName, facebook, account_status, user_id, section_id
        FROM user_profile
    ")->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

include('../includes/header.php');
?>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="row">
            <!-- Paid students -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card d-flex align-items-center justify-content-center">
                    <div class="card-body text-center">
                        <p class="card-text">
                            Number of paid students: <br>
                            <strong><?php echo htmlspecialchars($paid_students_count); ?></strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Staff count -->
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

            <!-- Unpaid students (Pending) -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card d-flex align-items-center justify-content-center">
                    <div class="card-body text-center">
                        <p class="card-text">
                            Number of unpaid (pending) students: <br>
                            <strong><?php echo htmlspecialchars($unpaid_students_count); ?></strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Credit Collected -->
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card d-flex align-items-center justify-content-center">
                    <div class="card-body text-center">
                        <form id="filter-form">
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                                </div>
                                <div class="col-auto">
                                    <label for="end_date">End Date:</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                                </div>
                                <div class="col-auto">
                                    <label for="course">Course:</label>
                                    <select class="form-control" id="course" name="course">
                                        <option value="all" <?php echo $selected_course === 'all' ? 'selected' : ''; ?>>All Courses</option>
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?php echo htmlspecialchars($course['courseName']); ?>" <?php echo $selected_course === $course['courseName'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($course['courseName']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" id="filter-submit">Filter</button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-success" id="export-excel">Export to Excel</button>
                                </div>
                            </div>
                        </form>
                        <h5>Total Debit and Credit</h5>
                        <p class="card-text">
                            From <strong id="start-date-display"><?php echo htmlspecialchars($start_date); ?></strong> to <strong id="end-date-display"><?php echo htmlspecialchars($end_date); ?></strong> <br>
                            Course: <strong id="course-display"><?php echo htmlspecialchars($selected_course === 'all' ? 'All Courses' : $selected_course); ?></strong> <br>
                            Total Debit: <strong>PHP <span id="total-debit"><?php echo number_format($total_debit, 2); ?></span></strong>
                            Total Credit: <strong>PHP <span id="total-credit"><?php echo number_format($total_credit, 2); ?></span></strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Export Data</h5>
                        <button class="btn btn-primary export-btn" data-export="user_ledger">Export User Ledger</button>
                        <button class="btn btn-primary export-btn" data-export="staff">Export Staff Data</button>
                        <button class="btn btn-primary export-btn" data-export="waitlist">Export Waitlist Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden data for exports -->
<div id="export-data" style="display: none;">
    <div id="user_ledger_data"><?php echo htmlspecialchars(json_encode($user_ledger_data)); ?></div>
    <div id="staff_data"><?php echo htmlspecialchars(json_encode($staff_data)); ?></div>
    <div id="waitlist_data"><?php echo htmlspecialchars(json_encode($waitlist_data)); ?></div>
</div>
<?php
date_default_timezone_set('Asia/Manila');
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
include('../includes/footer.php');
?>