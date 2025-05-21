<?php
session_start();
include('includes/db_connect.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to log debug information
function debug_log($message)
{
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, 'debug.log');
}

// Function to verify token
function verifyToken($conn, $token)
{
    $stmt = $conn->prepare("SELECT id FROM user_profile WHERE registration_token = ? AND token_expiry > NOW()");
    if ($stmt === false) {
        debug_log("Prepare statement failed: " . $conn->error);
        return false;
    }
    $stmt->bind_param("s", $token);
    if (!$stmt->execute()) {
        debug_log("Execute statement failed: " . $stmt->error);
        return false;
    }
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        debug_log("Invalid or expired token attempt: " . $token);
        return false;
    }

    $user = $result->fetch_assoc();
    $_SESSION['user_profile_id'] = $user['id'];
    return true;
}

// Check if the token is provided and valid
if (!isset($_GET['token']) || empty($_GET['token'])) {
    debug_log("Invalid access attempt: Token not provided");
    die("Invalid access. Please complete the registration process.");
}

$token = $_GET['token'];

// Verify the token
if (!verifyToken($conn, $token)) {
    die("Invalid or expired token. Please complete the registration process again.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    $receipt_path = '';
    $payment_status = 'Pending'; // Default payment status

    // Handle receipt upload
    if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] == 0) {
        $upload_dir = 'all/uploads/receipts/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = uniqid() . '_' . basename($_FILES['payment_receipt']['name']);
        $upload_path = $upload_dir . $file_name;
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        
        if (in_array($_FILES['payment_receipt']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['payment_receipt']['tmp_name'], $upload_path)) {
                $receipt_path = $upload_path;
                debug_log("File uploaded successfully. Path: " . $receipt_path);
            } else {
                debug_log("File upload failed. Error: " . error_get_last()['message']);
                die("Error uploading file. Please try again.");
            }
        } else {
            debug_log("Invalid file type uploaded.");
            die("Invalid file type. Please upload an image or PDF.");
        }
    } else {
        debug_log("No file uploaded or upload error occurred.");
        die("No file uploaded or an error occurred. Please try again.");
    }

    // Insert into waitlist table
    try {
        // Begin transaction
        $conn->begin_transaction();

        $user_profile_id = $_SESSION['user_profile_id'];

        // Check if a record already exists for this user
        $check_stmt = $conn->prepare("SELECT waitlist_id FROM waitlist WHERE user_profile_id = ?");
        if ($check_stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $check_stmt->bind_param("i", $user_profile_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE waitlist SET payment_status = ?, receipt_path = ? WHERE user_profile_id = ?");
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ssi", $payment_status, $receipt_path, $user_profile_id);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO waitlist (user_profile_id, payment_status, registration_date, receipt_path) VALUES (?, ?, NOW(), ?)");
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("iss", $user_profile_id, $payment_status, $receipt_path);
        }

        if (!$stmt->execute()) {
            throw new Exception('Execute statement failed: ' . $stmt->error);
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to thank you page or show success message
        header('Location: thank_you.php');
        exit();
    } catch (Exception $e) {
        // Rollback transaction if an error occurs
        $conn->rollback();
        debug_log("Error occurred: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Receipt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Upload Receipt</h2>
        <form action="upload_receipt.php?token=<?php echo htmlspecialchars($token); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Select Payment Method:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="f2f" value="F2F" checked>
                    <label class="form-check-label" for="f2f">Face-to-Face</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="online" value="online">
                    <label class="form-check-label" for="online">Pay Online</label>
                </div>
            </div>

            <div class="form-group" id="receiptUpload">
                <label for="payment_receipt">Upload Payment Receipt:</label>
                <input type="file" class="form-control-file" name="payment_receipt" id="payment_receipt" accept="image/*,application/pdf" required>
            </div>

            <div id="paymongoButton" style="display:none;">
                <button type="button" onclick="openPaymongoWindow()" class="btn btn-primary mb-3">Pay with PayMongo</button>
            </div>

            <button type="submit" class="btn btn-success">Submit Receipt</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('input[name="payment_method"]').change(function() {
                if (this.value === 'online') {
                    $('#paymongoButton').show();
                } else {
                    $('#paymongoButton').hide();
                }
            });
        });

        function openPaymongoWindow() {
            window.open('paymongo.php', 'PayMongo', 'width=800,height=600');
        }
    </script>
</body>
</html> 