<?php
session_start();
include('includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Check if the email exists in the user_profile table
    $sql = "SELECT id, courseName FROM user_profile WHERE email_stud = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }

    $bind_result = $stmt->bind_param("s", $email);
    if ($bind_result === false) {
        die("Error binding parameters: " . htmlspecialchars($stmt->error));
    }

    $execute_result = $stmt->execute();
    if ($execute_result === false) {
        die("Error executing statement: " . htmlspecialchars($stmt->error));
    }

    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $courseName = $user['courseName'];
        
        // Generate new token
        $new_token = bin2hex(random_bytes(32));
        
        // Update user_profile table with new token
        $update_sql = "UPDATE user_profile SET registration_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);

        if ($update_stmt === false) {
            die("Error preparing update statement: " . htmlspecialchars($conn->error));
        }

        $update_bind_result = $update_stmt->bind_param("si", $new_token, $user_id);
        if ($update_bind_result === false) {
            die("Error binding update parameters: " . htmlspecialchars($update_stmt->error));
        }

        $update_execute_result = $update_stmt->execute();
        if ($update_execute_result === false) {
            die("Error executing update statement: " . htmlspecialchars($update_stmt->error));
        }

        // Update course status to 'Returning'
        $course_table = '';
        switch (strtolower($courseName)) {
            case 'architecture':
                $course_table = 'arki_stud';
                break;
            case 'civil engineering':
                $course_table = 'civil_stud';
                break;
            case 'mechanical engineering':
                $course_table = 'mecha_stud';
                break;
            case 'master plumber':
                $course_table = 'plumber_stud';
                break;
            default:
                die("Unknown course: " . htmlspecialchars($courseName));
        }

        $update_status_sql = "UPDATE $course_table SET courseStatus = 'Returning' WHERE student_id = ?";
        $update_status_stmt = $conn->prepare($update_status_sql);

        if ($update_status_stmt === false) {
            die("Error preparing status update statement: " . htmlspecialchars($conn->error));
        }

        $update_status_bind_result = $update_status_stmt->bind_param("i", $user_id);
        if ($update_status_bind_result === false) {
            die("Error binding status update parameters: " . htmlspecialchars($update_status_stmt->error));
        }

        $update_status_execute_result = $update_status_stmt->execute();
        if ($update_status_execute_result === false) {
            die("Error executing status update statement: " . htmlspecialchars($update_status_stmt->error));
        }

        // Redirect to registration_complete.php with the new token
        header("Location: registration_complete.php?token=" . urlencode($new_token) . "&status=returning");
        exit();
    } else {
        $error = "Email not found. Please register as a new student.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returnee Student Registration - Ace Review</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-lg">
            <!-- Back Button -->
            <button type="button" onclick="history.back()" class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <
                    </button>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Returnee Student Registration</h2>
            
            <!-- Error Alert -->
            <?php if (isset($error)): ?>
                <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <input type="email" id="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Continue Registration</button>
            </form>
            
            <p class="mt-4 text-sm text-gray-600">
                New student? <a href="register_new.php" class="text-indigo-600 hover:underline">Register here</a>
            </p>
        </div>
    </div>
</body>
</html>
