<?php
require '../vendor/autoload.php';
include('../includes/db_connect.php');
include('../includes/function.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$start_date = $_POST['student_start_date'];
$end_date = $_POST['student_end_date'];
$course = $_POST['course'];

// Main query to get student information
$query = "SELECT up.*, un.email, un.added
          FROM user_profile up
          JOIN users_new un ON up.user_id = un.id
          WHERE un.added BETWEEN ? AND ?";
$params = [$start_date, $end_date];

if ($course !== 'all') {
    $query .= " AND up.courseName = ?";
    $params[] = $course;
}

$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set up headers based on whether a specific course is selected
$headers = [
    'Student ID', 'First Name', 'Middle Name', 'Last Name', 'Course', 'Email', 'Contact Number',
    'Gender', 'Birth Date', 'Address', 'Facebook', 'Parent\'s Name', 'Enrollment Date',
    'Account Status'
];

if ($course !== 'all') {
    $headers = array_merge($headers, ['Package', 'Review', 'Section', 'School Graduated', 'Employment Status', 'Additional Info']);
}

foreach ($headers as $index => $header) {
    $column = chr(65 + $index); // Convert number to letter (A, B, C, ...)
    $sheet->setCellValue($column . '1', $header);
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

$sheet->getStyle('A1:' . $column . '1')->getFont()->setBold(true);
$sheet->getStyle('A1:' . $column . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');

$row = 2;
while ($student = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $student['id']);
    $sheet->setCellValue('B' . $row, $student['fName']);
    $sheet->setCellValue('C' . $row, $student['mName']);
    $sheet->setCellValue('D' . $row, $student['lName']);
    $sheet->setCellValue('E' . $row, $student['courseName']);
    $sheet->setCellValue('F' . $row, $student['email']);
    $sheet->setCellValue('G' . $row, $student['conNumb']);
    $sheet->setCellValue('H' . $row, $student['gender']);
    $sheet->setCellValue('I' . $row, $student['bDate']);
    $sheet->setCellValue('J' . $row, $student['address_stud']);
    $sheet->setCellValue('K' . $row, $student['facebook']);
    $sheet->setCellValue('L' . $row, $student['pName']);
    $sheet->setCellValue('M' . $row, $student['added']);
    $sheet->setCellValue('N' . $row, $student['account_status'] == 0 ? 'Active' : 'Inactive');

    // Fetch course-specific data only if a specific course is selected
    if ($course !== 'all') {
        $courseSpecificData = getCourseSpecificData($conn, $student['id'], $student['courseName']);

        $sheet->setCellValue('O' . $row, $courseSpecificData['package'] ?? '');
        $sheet->setCellValue('P' . $row, $courseSpecificData['review'] ?? '');
        $sheet->setCellValue('Q' . $row, $courseSpecificData['section'] ?? '');
        $sheet->setCellValue('R' . $row, $courseSpecificData['school_graduated'] ?? '');
        $sheet->setCellValue('S' . $row, $courseSpecificData['employment_status'] ?? '');
        $sheet->setCellValue('T' . $row, $courseSpecificData['additional_info'] ?? '');
    }

    $row++;
}

// Apply borders to all cells
$lastColumn = $column;
$lastRow = $row - 1;
$range = 'A1:' . $lastColumn . $lastRow;
$sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Auto-filter for all columns
$sheet->setAutoFilter($range);

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="student_report.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');

function getCourseSpecificData($conn, $studentId, $course) {
    $data = [];
    switch ($course) {
        case 'Architecture':
            $stmt = $conn->prepare("SELECT * FROM arki_stud WHERE student_id = ?");
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $data['package'] = $row['courseReview'];
                $data['review'] = $row['courseSection'];
                $data['section'] = $row['courseStatus'];
                $data['school_graduated'] = $row['school_grad'];
                $data['employment_status'] = $row['employ_status_arki'];
                $data['additional_info'] = $row['additional_info_arki'];
            }
            break;
        case 'Civil Engineering':
            $stmt = $conn->prepare("SELECT * FROM civil_stud WHERE student_id = ?");
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $data['review'] = $row['courseReview'];
                $data['section'] = $row['courseSection'];
                $data['school_graduated'] = $row['school_grad'];
            }
            break;
        case 'Mechanical Engineering':
            $stmt = $conn->prepare("SELECT * FROM mecha_stud WHERE student_id = ?");
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $data['review'] = $row['courseReview'];
                $data['section'] = $row['courseSection'];
                $data['school_graduated'] = $row['school_grad'];
            }
            break;
        case 'Master Plumber':
            $stmt = $conn->prepare("SELECT * FROM plumber_stud WHERE student_id = ?");
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $data['review'] = $row['courseReview'];
                $data['school_graduated'] = $row['graduated_plumber'];
                $data['additional_info'] = $row['prc_licences'];
            }
            break;
    }
    if (isset($stmt)) {
        $stmt->close();
    }
    return $data;
}
?>