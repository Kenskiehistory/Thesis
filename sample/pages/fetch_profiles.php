    <?php
    include('../includes/db_connect.php');

    header('Content-Type: application/json');

    $role = $_GET['role'] ?? '';

    if ($role == 'Student') {
        $query = "SELECT id, CONCAT(fName, ' ', mName, ' ', lName) AS name FROM user_profile WHERE user_id IS NULL";
    } elseif ($role == 'Staff') {
        $query = "SELECT id, CONCAT(firstName, ' ', middleName, ' ', lastName) AS name FROM staff WHERE user_id IS NULL";
    } else {
        echo json_encode([]);
        exit;
    }

    $result = $conn->query($query);
    $profiles = [];

    while ($row = $result->fetch_assoc()) {
        $profiles[] = $row;
    }

    echo json_encode($profiles);