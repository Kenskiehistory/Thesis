<?php
// Include database connection file
include '../includes/db_connect.php';

// Fetch available courses for the dropdown
$availableCourses = ['Architecture', 'Civil Engineering', 'Mechanical Engineering', 'Master Plumber'];

// Check if request is to get all payment records
if (isset($_GET['action']) && $_GET['action'] == 'get_fees') {
    $sql = "SELECT * FROM payment_fees";
    $result = $conn->query($sql);

    $fees = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fees[] = $row;
        }
    }

    echo json_encode($fees);
    $conn->close();
    exit;
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete_fee') {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
        $id = $_GET['id'];
        
        $sql = "DELETE FROM payment_fees WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo json_encode(["success" => false, "error" => "SQL Error: " . $conn->error]);
            exit;
        }
        
        if (!$stmt->bind_param("i", $id)) {
            echo json_encode(["success" => false, "error" => "Bind Error: " . $stmt->error]);
            exit;
        }
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Record deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "error" => "Execution Error: " . $stmt->error]);
        }
        
        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo json_encode(["success" => false, "error" => "Invalid request"]);
        exit;
    }
}

// Handle form submission for adding payment fees
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $particular = $_POST['particular'];
    $courseName = $_POST['courseName'];
    $amount = $_POST['amount'];

    // Validate input
    if (empty($particular) || empty($courseName) || empty($amount)) {
        echo json_encode(["status" => "error", "message" => "All fields must be filled!"]);
        exit;
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO payment_fees (particular, courseName, amount, current_year) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
        exit;
    }

    if (!$stmt->bind_param("ssd", $particular, $courseName, $amount)) {
        echo json_encode(["status" => "error", "message" => "Bind Error: " . $stmt->error]);
        exit;
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Payment record added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execution Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>