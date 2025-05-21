<?php
include('../includes/db_connect.php');
include('../includes/function.php');

// Function to create a user account
function create_user_account($conn, $user_profile_id) {
    // Fetch user profile details
    $stmt = $conn->prepare('SELECT fName, mName, lName, email_stud FROM user_profile WHERE id = ?');
    $stmt->bind_param("i", $user_profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile = $result->fetch_assoc();
    $stmt->close();

    $username = $profile['fName'] . ' ' . $profile['mName'] . ' ' . $profile['lName'];
    $email = $profile['email_stud'];
    
    // Generate password based on the new rule
    $password = generate_password($profile['fName'], $profile['lName'], $user_profile_id);
    
    $hashed_password = SHA1($password);
    $active = '1';
    $role = 'Student';

    // Insert the new user into users_new table
    $stmt = $conn->prepare('INSERT INTO users_new (username, email, password, active, roles) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $active, $role);
    $stmt->execute();
    $user_id = $conn->insert_id;
    $stmt->close();

    // Update user_profile with the new user_id
    $stmt = $conn->prepare('UPDATE user_profile SET user_id = ? WHERE id = ?');
    $stmt->bind_param("ii", $user_id, $user_profile_id);
    $stmt->execute();
    $stmt->close();

    return ['user_id' => $user_id, 'password' => $password];
}

// Function to generate password based on the new rule
function generate_password($firstname, $lastname, $user_profile_id) {
    $year_created = date('Y');
    return strtolower($firstname) . strtolower($lastname) . '-' . $user_profile_id . $year_created;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $waitlist_id = $_POST['waitlist_id'];
    $user_profile_id = $_POST['user_profile_id'];

    // Update payment status
    $stmt = $conn->prepare("UPDATE waitlist SET payment_status = 'Paid' WHERE waitlist_id = ?");
    $stmt->bind_param("i", $waitlist_id);
    $stmt->execute();
    $stmt->close();

    // Create user account
    $result = create_user_account($conn, $user_profile_id);

    if ($result['user_id']) {
        echo json_encode(['success' => true, 'message' => "Payment marked as paid and user account created successfully. Temporary password: " . $result['password']]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error creating user account."]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Invalid request method."]);
}
?>