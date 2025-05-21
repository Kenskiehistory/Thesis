<?php
include('includes/db_connect.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to log debug information
function debug_log($message)
{
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, 'debug.log');
}

// Start session
session_start();

// Check if the email already exists
$email_stud = $_POST['email_stud'];
$stmt = $conn->prepare("SELECT id FROM user_profile WHERE email_stud = ?");
if ($stmt === false) {
    die("Error preparing statement: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("s", $email_stud);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email already exists, redirect to returnee registration
    header("Location: returnee_registration.php?error=email_exists");
    exit();
}

// Basic student information
$fName = $_POST['fName'];
$mName = $_POST['mName'];
$lName = $_POST['lName'];
$gender = $_POST['gender'];
$conNumb = $_POST['conNumb'];
$email_stud = $_POST['email_stud'];
$pName = $_POST['pName'];
$bDate = $_POST['bDate'];
$address_stud = $_POST['address_stud'];
$courseName = $_POST['courseName'];
$facebook = $_POST['facebook'];
$courseReviewId = $_POST['courseReview'];
$courseSection = $_POST['courseSection'];



// Start transaction
try {
    $conn->begin_transaction();

    // Insert into user_profile table
    $stmt = $conn->prepare("INSERT INTO user_profile (fName, mName, lName, gender, conNumb, email_stud, pName, bDate, address_stud, courseName, facebook) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    $stmt->bind_param("sssssssssss", $fName, $mName, $lName, $gender, $conNumb, $email_stud, $pName, $bDate, $address_stud, $courseName, $facebook);
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }

    // Get the last inserted user_profile ID
    $studentId = $stmt->insert_id;
    debug_log("Inserted user_profile. ID: " . $studentId);

    // Store user_profile ID in session
    $_SESSION['user_profile_id'] = $studentId;

    // Fetch the selected review details from course_reviews table
    $stmt = $conn->prepare("SELECT reviews, tuition_fee FROM course_reviews WHERE id = ?");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    $stmt->bind_param("i", $courseReviewId);
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }
    $result = $stmt->get_result();
    $reviewDetails = $result->fetch_assoc();
    if (!$reviewDetails) {
        throw new Exception('No review found with the given ID.');
    }
    $courseReviewText = $reviewDetails['reviews'] . " TOTAL FEE PHP." . $reviewDetails['tuition_fee'];
    $tuitionFee = $reviewDetails['tuition_fee'];

    // Insert course-specific information
    switch ($courseName) {
        case 'Architecture':
            $courseStatus = $_POST['courseStatus'];
            $school_grad = $_POST['school_grad'];
            $employ_status_arki = $_POST['employ_status_arki'];
            $additional_info_arki = $_POST['additional_info_arki'];
            $stmt = $conn->prepare("INSERT INTO arki_stud (student_id, courseReview, courseSection, courseStatus, school_grad, employ_status_arki, additional_info_arki) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $conn->error);
            }
            $stmt->bind_param("issssss", $studentId, $courseReviewText, $courseSection, $courseStatus, $school_grad, $employ_status_arki, $additional_info_arki);
            break;

        case 'Civil Engineering':
            $courseStatus = $_POST['courseStatus'];
            $school_grad = $_POST['school_grad'];
            $stmt = $conn->prepare("INSERT INTO civil_stud (student_id, courseReview, courseSection, courseStatus, school_grad) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $conn->error);
            }
            $stmt->bind_param("issss", $studentId, $courseReviewText, $courseSection, $courseStatus, $school_grad);
            break;

        case 'Mechanical Engineering':
            $courseStatus = $_POST['courseStatus'];
            $school_grad = $_POST['school_grad'];
            $stmt = $conn->prepare("INSERT INTO mecha_stud (student_id, courseReview, courseSection, courseStatus, school_grad) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $conn->error);
            }
            $stmt->bind_param("issss", $studentId, $courseReviewText, $courseSection, $courseStatus, $school_grad);
            break;

        case 'Master Plumber':
            $status_plumber = $_POST['status_plumber'];
            $graduated_plumber = $_POST['graduated_plumber'];
            $graduated_year_plumber = $_POST['graduated_year_plumber'];
            $prc_licences = $_POST['prc_licences'];
            $facebook_plumber = $_POST['facebook_plumber'];
            $stmt = $conn->prepare("INSERT INTO plumber_stud (student_id, courseReview, status_plumber, graduated_plumber, graduated_year_plumber, prc_licences, facebook_plumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('Prepare statement failed: ' . $conn->error);
            }
            $stmt->bind_param("issssss", $studentId, $courseReviewText, $status_plumber, $graduated_plumber, $graduated_year_plumber, $prc_licences, $facebook_plumber);
            break;

        default:
            throw new Exception('Invalid course selected.');
    }

    // Execute course-specific insert
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }

    // Fetch the section ID from the sectioning table
    $stmt = $conn->prepare("SELECT id FROM sectioning WHERE section_name = ?");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    $stmt->bind_param("s", $courseSection);
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }
    $result = $stmt->get_result();
    $section = $result->fetch_assoc();
    if (!$section) {
        throw new Exception('No section found with the given name.');
    }
    $sectionId = $section['id'];

    // Update the section_id in the user_profile table
    $stmt = $conn->prepare("UPDATE user_profile SET section_id = ? WHERE id = ?");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    $stmt->bind_param("ii", $sectionId, $studentId);
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }

    // Insert into student_section table
    $stmt = $conn->prepare("INSERT INTO student_section (student_id, section_id, course_review_id) VALUES (?, ?, ?)");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    $stmt->bind_param("iii", $studentId, $sectionId, $courseReviewId);
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }

    // Update section count in the sectioning table
    $stmt = $conn->prepare("UPDATE sectioning SET section_count = section_count + 1 WHERE id = ?");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    $stmt->bind_param("i", $sectionId);
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }

    // Insert into waitlist table without receipt_path
    $stmt = $conn->prepare("INSERT INTO waitlist (user_profile_id, payment_status) VALUES (?, ?)");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    $payment_status = 'Pending'; // Default status
    $stmt->bind_param("is", $studentId, $payment_status);
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }
    
    // Get the last inserted waitlist ID
    $waitlistId = $stmt->insert_id;
    
    // Store waitlist ID in session
    $_SESSION['waitlist_id'] = $waitlistId;
    
    debug_log("Inserted into waitlist. user_profile_id: " . $studentId . ", payment_status: " . $payment_status . ", waitlist_id: " . $waitlistId);

    // Insert into user_ledger with the fetched tuition fee
    $particulars = "Total Tuition Fee";
    $stmt = $conn->prepare("INSERT INTO user_ledger (user_profile_id, date, particulars, debit, credit, balance) VALUES (?, NOW(), ?, ?, 0, ?)");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    $stmt->bind_param("isii", $studentId, $particulars, $tuitionFee, $tuitionFee);
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }

    // Generate a unique token
    $token = bin2hex(random_bytes(32));
    
    // Store the token in the user_profile table
    $stmt = $conn->prepare("UPDATE user_profile SET registration_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 7 DAY) WHERE id = ?");
    if ($stmt === false) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    $stmt->bind_param("si", $token, $studentId);
    if (!$stmt->execute()) {
        throw new Exception('Execute statement failed: ' . $stmt->error);
    }
    
    // Store the token and user ID in the session
    $_SESSION['registration_token'] = $token;
    $_SESSION['user_profile_id'] = $studentId;

    // Commit transaction
    $conn->commit();
    debug_log("Transaction committed successfully.");
    
    // Redirect to a new page that gives the user options
    header('Location: registration_complete.php?token=' . urlencode($token));
    die();
} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();
    debug_log("Error occurred: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
}

// Close connection
$conn->close();
?>