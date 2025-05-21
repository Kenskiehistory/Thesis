<?php
include('../includes/db_connect.php');
include('../includes/function.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['roles'];
    $email = $_POST['email'];
    $password = SHA1($_POST['password']);
    $active = $_POST['active'];
    
    if ($role == 'Admin') {
        $username = $_POST['username'];
    } else {
        $profile_id = $_POST['profile_id'];
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users_new WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => "Error: Email address already exists."]);
        exit();
    }

    try {
        if ($role == 'Student' || $role == 'Staff') {
            $table = ($role == 'Student') ? 'user_profile' : 'staff';
            $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
            $stmt->bind_param("i", $profile_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $profile = $result->fetch_assoc();
            $stmt->close();
            $username = ($role == 'Student') 
                ? $profile['fName'] . ' ' . $profile['mName'] . ' ' . $profile['lName']
                : $profile['firstName'] . ' ' . $profile['middleName'] . ' ' . $profile['lastName'];
        }

        // Insert the new user
        $stmt = $conn->prepare('INSERT INTO users_new (username, email, password, active, roles) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param("sssss", $username, $email, $password, $active, $role);
        $stmt->execute();
        $user_id = $conn->insert_id;
        $stmt->close();

        if ($role == 'Student' || $role == 'Staff') {
            $table = ($role == 'Student') ? 'user_profile' : 'staff';
            $stmt = $conn->prepare("UPDATE $table SET user_id = ? WHERE id = ?");
            $stmt->bind_param("ii", $user_id, $profile_id);
            $stmt->execute();
            $stmt->close();
        }

        echo json_encode(['success' => true, 'message' => "User $username has been added successfully"]);
    } catch (mysqli_sql_exception $e) {
        echo json_encode(['success' => false, 'message' => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Invalid request method"]);
}