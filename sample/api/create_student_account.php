<?php
include('../includes/function.php');
include('../includes/db_connect.php');
check_role('Staff');
secure();

header('Content-Type: application/json');

function returnJson($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    returnJson(false, "Invalid request method");
}

$waitlist_id = isset($_POST['waitlist_id']) ? $_POST['waitlist_id'] : '';
$user_profile_id = isset($_POST['user_profile_id']) ? $_POST['user_profile_id'] : '';
$send_email = isset($_POST['send_email']) ? $_POST['send_email'] : 'false';

if (!$waitlist_id || !$user_profile_id) {
    returnJson(false, "Missing required parameters");
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Update waitlist status
    $stmt = $conn->prepare("UPDATE waitlist SET payment_status = 'Paid' WHERE id = ?");
    $stmt->bind_param("i", $waitlist_id);
    $stmt->execute();
    $stmt->close();

    // Fetch user profile data
    $stmt = $conn->prepare("SELECT * FROM user_profile WHERE id = ?");
    $stmt->bind_param("i", $user_profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_profile = $result->fetch_assoc();
    $stmt->close();

    // Create account (using the logic from create_student_account.php)
    $email = $user_profile['email_stud'];
    $fName = $user_profile['fName'];
    $lName = $user_profile['lName'];
    $year = date('Y');
    $password = $fName . '.' . $lName . '-' . $user_profile_id . '-' . $year;
    $encrypted_password = SHA1($password);
    $username = $fName . ' ' . $user_profile['mName'] . ' ' . $lName;
    $active = '1';
    $role = 'Student';

    $stmt = $conn->prepare('INSERT INTO users_new (username, email, password, active, roles) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param("sssss", $username, $email, $encrypted_password, $active, $role);
    $stmt->execute();
    $user_id = $stmt->insert_id;
    $stmt->close();

    // Update user_profile with new user_id
    $stmt = $conn->prepare("UPDATE user_profile SET user_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $user_id, $user_profile_id);
    $stmt->execute();
    $stmt->close();

    // Commit transaction
    $conn->commit();

    // Send email if requested
    if ($send_email === 'true') {
        require_once 'send_email.php';
        $email_sent = sendEmail($email, $username, $email, $password);
        if ($email_sent) {
            returnJson(true, "Payment marked as paid, account created, and login details sent via email.");
        } else {
            returnJson(true, "Payment marked as paid and account created, but there was an error sending the email.");
        }
    } else {
        returnJson(true, "Payment marked as paid and account created successfully.");
    }
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    returnJson(false, "Error: " . $e->getMessage());
}
?>