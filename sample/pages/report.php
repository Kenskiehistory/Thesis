<?php
include('../includes/db_connect.php');
include('../includes/function.php');

// Initialize variables for student enrollment date filtering
$student_start_date = isset($_GET['student_start_date']) ? $_GET['student_start_date'] : date('Y-m-01');
$student_end_date = isset($_GET['student_end_date']) ? $_GET['student_end_date'] : date('Y-m-t');
$selected_student_course = isset($_GET['student_course']) ? $_GET['student_course'] : 'all';


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

// Fetch student enrollment data
$student_query = "SELECT up.fName, up.lName, up.courseName, un.email, up.conNumb, un.added
                  FROM user_profile up
                  JOIN users_new un ON up.user_id = un.id
                  WHERE un.added BETWEEN ? AND ?";
$student_params = [$student_start_date, $student_end_date];

if ($selected_course !== 'all') {
    $student_query .= " AND up.courseName = ?";
    $student_params[] = $selected_course;
}

$student_stmt = $conn->prepare($student_query);
$student_stmt->bind_param(str_repeat('s', count($student_params)), ...$student_params);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$students = $student_result->fetch_all(MYSQLI_ASSOC);
$student_stmt->close();
?>

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

<!-- Student Enrollment Report section -->
<div class="col-md-12 grid-margin stretch-card mt-4">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Student Enrollment Report</h4>
            <form id="student-filter-form">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="student_start_date">Start Date:</label>
                        <input type="date" class="form-control" id="student_start_date" name="student_start_date" value="<?php echo htmlspecialchars($student_start_date); ?>">
                    </div>
                    <div class="col-auto">
                        <label for="student_end_date">End Date:</label>
                        <input type="date" class="form-control" id="student_end_date" name="student_end_date" value="<?php echo htmlspecialchars($student_end_date); ?>">
                    </div>
                    <div class="col-auto">
                        <label for="student_course">Course:</label>
                        <select class="form-control" id="student_course" name="student_course">
                            <option value="all" <?php echo $selected_student_course === 'all' ? 'selected' : ''; ?>>All Courses</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['courseName']); ?>" <?php echo $selected_student_course === $course['courseName'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['courseName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" id="student-filter-submit">Filter Students</button>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-success" id="export-students-excel">Export Students to Excel</button>
                    </div>
                </div>
            </form>
            <div id="student-report-results" class="mt-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Enrollment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['fName'] . ' ' . $student['lName']); ?></td>
                                <td><?php echo htmlspecialchars($student['courseName']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['conNumb']); ?></td>
                                <td><?php echo htmlspecialchars($student['added']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>