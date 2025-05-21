<?php
session_start();
include('includes/db_connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include('send_email.php');

// Include Composer's autoloader
require 'vendor/autoload.php';

// Function to send dashboard access email
function sendDashboardEmail($to, $name, $token) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply.acereview@gmail.com';
        $mail->Password   = 'zbkj kttf qqpj zkpp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('noreply.acereview@gmail.com', 'Ace Review');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Access Your Ace Review Dashboard';
        $mail->Body    = "
            <html>
            <body>
                <h2>Welcome to Ace Review, {$name}!</h2>
                <p>Thank you for registering with us. Your registration is complete!</p>
                <p>You can access your dashboard using the link below:</p>
                <p><a href='http://localhost/sample/dashboard.php?token={$token}'>Access Your Dashboard</a></p>
                <p>If you haven't completed your payment yet, please do so to finalize your enrollment.</p>
                <p>If you have any questions, please don't hesitate to contact us.</p>
            </body>
            </html>
        ";
        $mail->AltBody = "Welcome to Ace Review, {$name}! Thank you for registering with us. Your registration is complete! You can access your dashboard using this link: http://yourdomain.com/dashboard.php?token={$token} If you haven't completed your payment yet, please do so to finalize your enrollment. If you have any questions, please don't hesitate to contact us.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Dashboard email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

// Check if the token is provided
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid access. Please complete the registration process.");
}

$token = $_GET['token'];

// Verify the token in the database and check if email has been sent
$stmt = $conn->prepare("SELECT id, fName, lName, email_stud, email_sent FROM user_profile WHERE registration_token = ? AND token_expiry > NOW()");

if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$bind_result = $stmt->bind_param("s", $token);
if ($bind_result === false) {
    die("Binding parameters failed: " . htmlspecialchars($stmt->error));
}

$execute_result = $stmt->execute();
if ($execute_result === false) {
    die("Execute failed: " . htmlspecialchars($stmt->error));
}

$result = $stmt->get_result();

if ($result === false) {
    die("Getting result set failed: " . htmlspecialchars($stmt->error));
}

if ($result->num_rows === 0) {
    die("Invalid or expired token. Please start the registration process again.");
}

$user = $result->fetch_assoc();

// Check if email has already been sent
if ($user['email_sent'] == 1) {
    // Redirect to dashboard if email has already been sent
    header("Location: dashboard.php?token=" . urlencode($token));
    exit();
}

// Send email with dashboard link
$emailSent = sendDashboardEmail($user['email_stud'], $user['fName'] . ' ' . $user['lName'], $token);

if ($emailSent) {
    // Update the database to mark email as sent
    $update_stmt = $conn->prepare("UPDATE user_profile SET email_sent = 1 WHERE id = ?");
    if ($update_stmt === false) {
        die("Prepare update failed: " . htmlspecialchars($conn->error));
    }
    $update_stmt->bind_param("i", $user['id']);
    $update_stmt->execute();
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Complete - Ace Review</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Registration Complete</h1>
        <p>Thank you, <?php echo htmlspecialchars($user['fName'] . ' ' . $user['lName']); ?>, for registering with Ace Review!</p>
        <p>Your registration is complete, but you still need to make a payment to finalize your enrollment.</p>
        <?php if ($emailSent): ?>
            <div class="alert alert-success" role="alert">
                An email with your dashboard access link has been sent to your registered email address.
            </div>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                There was an issue sending the dashboard access email. Please use the link below to access your dashboard.
            </div>
        <?php endif; ?>
        <p>You have two options:</p>
        <a href="upload_receipt.php?token=<?php echo urlencode($token); ?>" class="btn btn-primary">Upload Payment Receipt Now</a>
        <a href="dashboard.php?token=<?php echo urlencode($token); ?>" class="btn btn-secondary">Continue to Dashboard</a>
        <p class="mt-3">You can always come back later to upload your payment receipt or complete any other pending tasks.</p>
    </div>
</body>
</html>
<?php
$conn->close();
?>