<?php
ob_start();
include('../includes/db_connect.php');
include('../includes/function.php');

// Redirect if student_id is not set
if (!isset($_POST['student_id'])) {
    echo "Invalid request";
    exit();
}

// Fetch student details
$studentId = $_POST['student_id'];
$stmt = $conn->prepare("SELECT * FROM user_profile WHERE id = ?");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$studentResult = $stmt->get_result();
$student = $studentResult->fetch_assoc();

if (!$student) {
    echo "Student not found";
    exit();
}

// Fetch course-specific data
$course = $student['courseName'];
$courseSpecificData = [];

switch ($course) {
    case 'Architecture':
        $stmt = $conn->prepare("SELECT * FROM arki_stud WHERE student_id = ?");
        break;
    case 'Civil Engineering':
        $stmt = $conn->prepare("SELECT * FROM civil_stud WHERE student_id = ?");
        break;
    case 'Mechanical Engineering':
        $stmt = $conn->prepare("SELECT * FROM mecha_stud WHERE student_id = ?");
        break;
    case 'Master Plumber':
        $stmt = $conn->prepare("SELECT * FROM plumber_stud WHERE student_id = ?");
        break;
    default:
        die("Invalid course.");
}

$stmt->bind_param("i", $studentId);
$stmt->execute();
$courseResult = $stmt->get_result();
$courseSpecificData = $courseResult->fetch_assoc();
$stmt->close();
?>

<div class="container-fluid">
    <div class="row">
        <!-- Left column for personal information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center"><?php echo htmlspecialchars($student['fName'] . ' ' . $student['mName'] . ' ' . $student['lName']); ?></h3>
                    <p class="text-center text-muted"><?php echo htmlspecialchars($student['courseName']); ?></p>
                    <hr>
                    <ul class="list-unstyled">
                        <li><strong>Gender:</strong> <?php echo htmlspecialchars($student['gender']); ?></li>
                        <li><strong>Contact:</strong> <?php echo htmlspecialchars($student['conNumb']); ?></li>
                        <li><strong>Email:</strong> <?php echo htmlspecialchars($student['email_stud']); ?></li>
                        <li><strong>Parent's Name:</strong> <?php echo htmlspecialchars($student['pName']); ?></li>
                        <li><strong>Birth Date:</strong> <?php echo htmlspecialchars($student['bDate']); ?></li>
                        <li><strong>Address:</strong> <?php echo htmlspecialchars($student['address_stud']); ?></li>
                        <li><strong>Facebook:</strong> <?php echo htmlspecialchars($student['facebook']); ?></li>
                        <li><strong>Account Status:</strong> <?php echo $student['account_status'] == 0 ? 'Active' : 'Inactive'; ?></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Right column for course-specific information -->
        <div class="col-md-8">
            <!-- Course Specific Information Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Course Specific Information</h4>
                    <table class="table table-bordered">
                        <?php if ($course == 'Architecture'): ?>
                            <tr>
                                <th>Package</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseReview']); ?></td>
                            </tr>
                            <tr>
                                <th>Review</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseSection']); ?></td>
                            </tr>
                            <tr>
                                <th>Section</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseStatus']); ?></td>
                            </tr>
                            <tr>
                                <th>School Graduated</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['school_grad']); ?></td>
                            </tr>
                            <tr>
                                <th>Employment Status</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['employ_status_arki']); ?></td>
                            </tr>
                            <tr>
                                <th>Other</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['additional_info_arki']); ?></td>
                            </tr>
                        <?php elseif ($course == 'Civil Engineering'): ?>
                            <tr>
                                <th>Review</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseReview']); ?></td>
                            </tr>
                            <tr>
                                <th>Section</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseSection']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseStatus']); ?></td>
                            </tr>
                            <tr>
                                <th>School Graduated</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['school_grad']); ?></td>
                            </tr>
                        <?php elseif ($course == 'Mechanical Engineering'): ?>
                            <tr>
                                <th>Review</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseReview']); ?></td>
                            </tr>
                            <tr>
                                <th>Section</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseSection']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseStatus']); ?></td>
                            </tr>
                            <tr>
                                <th>School Graduated</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['school_grad']); ?></td>
                            </tr>
                        <?php elseif ($course == 'Master Plumber'): ?>
                            <tr>
                                <th>Review</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['courseReview']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['status_plumber']); ?></td>
                            </tr>
                            <tr>
                                <th>School Graduated</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['graduated_plumber']); ?></td>
                            </tr>
                            <tr>
                                <th>Year Graduated</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['graduated_year_plumber']); ?></td>
                            </tr>
                            <tr>
                                <th>Other PRC Licenses</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['prc_licences']); ?></td>
                            </tr>
                            <tr>
                                <th>Facebook Link</th>
                                <td><?php echo htmlspecialchars($courseSpecificData['facebook_plumber']); ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Close the connection
$conn->close();
?>