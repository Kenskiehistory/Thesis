<?php
include('config.php');
include('db_connect.php'); // Ensure this is included to use $conn

$base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function secure() {
    global $base_url, $conn;

    if (!isset($_SESSION['id'])) {
        set_message('Please login first to view this page');
        header('Location:' . $base_url . 'index.php');
        die();
    }

    $userId = $_SESSION['id'];

    if ($stm = $conn->prepare('SELECT active FROM users_new WHERE id = ?')) {
        $stm->bind_param('i', $userId);
        $stm->execute();
        $stm->bind_result($active);
        $stm->fetch();
        $stm->close();

        if ($active != 1) {
            session_destroy();
            set_message('Your account is inactive. Please contact support.');
            header('Location:' . $base_url . 'index.php');
            die();
        }
    } else {
        echo 'Could not prepare statement!';
        die();
    }
}

function check_role($required_role) {
    global $base_url;

    if (isset($_SESSION['roles']) && $_SESSION['roles'] === 'Admin') {
        return;
    }

    if (!isset($_SESSION['roles']) || $_SESSION['roles'] !== $required_role) {
        set_message('You do not have permission to view this page');
        header('Location:' . $base_url . '../includes/no_access.php');
        die();
    }
}

function set_message($message) {
    $_SESSION['message'] = $message;
}

function get_message() {
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p><hr>';
        unset($_SESSION['message']);
    }
}

function get_user_course($user_id) {
    global $conn;

    if ($stm = $conn->prepare('SELECT courseName FROM user_profile WHERE user_id = ?')) {
        $stm->bind_param('i', $user_id);
        $stm->execute();
        $stm->bind_result($courseName);
        $stm->fetch();
        $stm->close();
        return $courseName;
    } else {
        return null;
    }
}

function get_user_role($user_id) {
    global $conn;

    if ($stm = $conn->prepare('SELECT roles FROM users_new WHERE id = ?')) {
        $stm->bind_param('i', $user_id);
        $stm->execute();
        $stm->bind_result($role);
        $stm->fetch();
        $stm->close(); // Ensure to close the statement
        return $role;
    } else {
        return null;
    }
}

function get_paid_students_count($conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM waitlist WHERE payment_status = 'Paid'");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    $stmt->close();
    return $count;
}

function get_all_users($conn) {
    $users = [];
    $sql = "SELECT id, username, roles FROM users_new";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    return $users;
}

?>
