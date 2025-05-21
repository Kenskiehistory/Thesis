<?php
session_start();
include('includes/db_connect.php');

// Check if the token is provided
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid access. Please complete the registration process.");
}

$token = $_GET['token'];

// Verify the token in the database
$stmt = $conn->prepare("SELECT id, fName, lName FROM user_profile WHERE registration_token = ? AND token_expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid or expired token. Please start the registration process again.");
}

$user = $result->fetch_assoc();
$_SESSION['user_profile_id'] = $user['id'];

// Fetch user's registration details
$stmt = $conn->prepare("SELECT * FROM user_profile WHERE id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();

// Fetch payment status
$stmt = $conn->prepare("SELECT payment_status FROM waitlist WHERE user_profile_id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$paymentStatus = $result->fetch_assoc()['payment_status'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, <?php echo htmlspecialchars($user['fName'] . ' ' . $user['lName']); ?>!</h1>
        <h2>Your Dashboard</h2>
        
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Registration Details</h5>
                <p>Course: <?php echo htmlspecialchars($userDetails['courseName']); ?></p>
                <p>Email: <?php echo htmlspecialchars($userDetails['email_stud']); ?></p>
                <p>Contact Number: <?php echo htmlspecialchars($userDetails['conNumb']); ?></p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Payment Status</h5>
                <p>Status: <?php echo htmlspecialchars($paymentStatus); ?></p>
                <?php if ($paymentStatus == 'Pending'): ?>
                    <a href="upload_receipt.php?token=<?php echo urlencode($token); ?>" class="btn btn-primary">Upload Payment Receipt</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add more sections as needed for course materials, schedules, etc. -->
    </div>
</body>
</html>
<?php
$conn->close();
?>