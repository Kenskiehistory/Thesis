<?php
include('../includes/db_connect.php');

$id = $_GET['id'];
$result = $conn->query("SELECT answer_key FROM exam_answer_keys WHERE id = $id");
$row = $result->fetch_assoc();

if ($row) {
    $answer_key = json_decode($row['answer_key'], true);
    echo '<ul>';
    foreach ($answer_key as $question => $answer) {
        echo "<li>Q$question: $answer</li>";
    }
    echo '</ul>';
}
?>
