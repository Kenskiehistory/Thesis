<?php
include('../includes/db_connect.php');
include('../includes/ledger_functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_profile_id = $_POST['user_profile_id'];

    if ($_POST['action'] == 'add_debit') {
        if ($_POST['debit_option'] == 'course') {
            $selected_course = $_POST['selected_course'];   
    
            // Retrieve all fees associated with the selected courseName
            $stmt = $conn->prepare("SELECT particular, amount FROM payment_fees WHERE courseName = ?");
            $stmt->bind_param("s", $selected_course);
            $stmt->execute();
            $result = $stmt->get_result();
    
            // Add each fee as a separate debit entry
            while ($row = $result->fetch_assoc()) {
                $particulars = $row['particular'] . ' for course: ' . $selected_course;
                add_debit($user_profile_id, $row['amount'], $particulars);
            }
            $stmt->close();
        } else {
            $amount = $_POST['debit_amount'];
            $particulars = $_POST['debit_particulars'];
            add_debit($user_profile_id, $amount, $particulars);
        }
        echo json_encode(['success' => true]);

    } elseif ($_POST['action'] == 'add_credit') {
        $amount = $_POST['credit_amount'];
        $particulars = $_POST['credit_particulars'];
        $original_receipt = $_POST['credit_or']; // Retrieve the OR value
    
        // Call the function to add the credit, including the OR
        add_credit_paymongo($user_profile_id, $amount, $particulars, $original_receipt);
    
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>