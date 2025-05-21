<?php
// export_dashboard_data.php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the required files exist
if (!file_exists('../vendor/autoload.php')) {
    die('Error: vendor/autoload.php not found. Please make sure you have run "composer install".');
}
if (!file_exists('../includes/db_connect.php')) {
    die('Error: includes/db_connect.php not found.');
}
if (!file_exists('../includes/function.php')) {
    die('Error: includes/function.php not found.');
}

require '../vendor/autoload.php';
include('../includes/db_connect.php');
include('../includes/function.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Check if the required POST data is present
    if (!isset($_POST['start_date']) || !isset($_POST['end_date']) || !isset($_POST['course'])) {
        throw new Exception('Required POST data is missing.');
    }

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $selected_course = $_POST['course'];

    // Validate date formats
    if (!DateTime::createFromFormat('Y-m-d', $start_date) || !DateTime::createFromFormat('Y-m-d', $end_date)) {
        throw new Exception('Invalid date format. Please use YYYY-MM-DD.');
    }

    $query = "SELECT ul.date, CONCAT(up.fName, ' ', up.mName, ' ', up.lName) AS full_name, 
                     up.courseName, ul.debit, ul.credit, ul.balance, ul.particulars
              FROM user_ledger ul
              JOIN user_profile up ON ul.user_profile_id = up.id
              WHERE ul.date BETWEEN ? AND ?";
    $params = [$start_date, $end_date];
    
    if ($selected_course !== 'all') {
        $query .= " AND up.courseName = ?";
        $params[] = $selected_course;
    }
    
    $query .= " ORDER BY ul.date, full_name";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    $result = $stmt->get_result();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $sheet->setCellValue('A1', 'Date');
    $sheet->setCellValue('B1', 'Full Name');
    $sheet->setCellValue('C1', 'Course');
    $sheet->setCellValue('D1', 'Debit');
    $sheet->setCellValue('E1', 'Credit');
    $sheet->setCellValue('F1', 'Balance');
    $sheet->setCellValue('G1', 'Particulars');

    // Populate data
    $row = 2;
    while ($data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $data['date']);
        $sheet->setCellValue('B' . $row, $data['full_name']);
        $sheet->setCellValue('C' . $row, $data['courseName']);
        $sheet->setCellValue('D' . $row, $data['debit']);
        $sheet->setCellValue('E' . $row, $data['credit']);
        $sheet->setCellValue('F' . $row, $data['balance']);
        $sheet->setCellValue('G' . $row, $data['particulars']);
        $row++;
    }
    // Create a temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'excel');
    if ($temp_file === false) {
        throw new Exception('Unable to create temporary file.');
    }
    
    // Save to the temporary file
    $writer = new Xlsx($spreadsheet);
    $writer->save($temp_file);

    // Check if the file was created successfully
    if (!file_exists($temp_file)) {
        throw new Exception('Failed to create Excel file.');
    }

    // Send appropriate headers
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="dashboard_export.xlsx"');
    header('Cache-Control: max-age=0');
    header('Content-Length: ' . filesize($temp_file));

    // Clear the output buffer
    ob_clean();
    flush();

    // Output the file
    if (!readfile($temp_file)) {
        throw new Exception('Failed to read the Excel file.');
    }

    // Delete the temporary file
    unlink($temp_file);

} catch (Exception $e) {
    error_log('Excel export error: ' . $e->getMessage());
    header('HTTP/1.1 500 Internal Server Error');
    echo 'An error occurred during export: ' . $e->getMessage();
}

exit;
?>