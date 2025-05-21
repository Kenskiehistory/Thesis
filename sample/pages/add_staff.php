<?php
// Include necessary files
require_once('../includes/config.php');
require_once('../includes/db_connect.php');
require_once('../includes/function.php');

// Fetch available courses for the dropdown
$courses = ['Architecture', 'Civil Engineering', 'Mechanical Engineering', 'Master Plumber'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    // Check if email already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM staff WHERE email = ?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'Error: Email address already exists.']);
        exit();
    }

    try {
        // Insert the new staff member into the staff table
        $stmt = $conn->prepare('INSERT INTO staff (firstName, middleName, lastName, contactInfo, email, courseName, active) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param("ssssssi", $_POST['firstName'], $_POST['middleName'], $_POST['lastName'], $_POST['contactInfo'], $_POST['email'], $_POST['courseName'], $_POST['active']);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => "A new staff member " . $_POST['firstName'] . " " . $_POST['middleName'] . " " . $_POST['lastName'] . " has been added"]);
        $stmt->close();
        exit();
    } catch (mysqli_sql_exception $e) {
        // Handle other potential database errors
        echo json_encode(['success' => false, 'message' => "Error: " . $e->getMessage()]);
        exit();
    }
}
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Add Staff</h4>
        <p class="card-description">Add Staff Member</p>
        <form id="addStaffForm" class="forms-sample">
            <div class="form-group">
                <label class="form-label" for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" class="form-control" placeholder="First Name" required />
            </div>

            <div class="form-group">
                <label class="form-label" for="middleName">Middle Name</label>
                <input type="text" id="middleName" name="middleName" class="form-control" placeholder="Middle Name" required />
            </div>

            <div class="form-group">
                <label class="form-label" for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Last Name" required />
            </div>

            <div class="form-group">
                <label class="form-label" for="contactInfo">Contact Info</label>
                <input type="text" id="contactInfo" name="contactInfo" class="form-control" placeholder="Contact Info" required />
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required />
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
                <label class="form-label" for="active">Active</label>
                <select name="active" id="active" class="form-select" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Add Staff</button>
        </form>
    </div>
</div>
