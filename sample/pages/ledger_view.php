<?php
ob_start();
include('../includes/db_connect.php');
include('../includes/function.php');

// Redirect if student_id is not set
if (!isset($_POST['student_id'])) {
    echo "Invalid request";
    exit();
}

// Fetch ledger entries for the student
$studentId = $_POST['student_id'];
$stmt = $conn->prepare('SELECT * FROM user_ledger WHERE user_profile_id = ?');
$stmt->bind_param('i', $studentId);
$stmt->execute();
$ledgerResult = $stmt->get_result();
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Date</th>
                <th>Particulars</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($ledger = $ledgerResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ledger['date']); ?></td>
                    <td><?php echo htmlspecialchars($ledger['particulars']); ?></td>
                    <td><?php echo htmlspecialchars($ledger['debit']); ?></td>
                    <td><?php echo htmlspecialchars($ledger['credit']); ?></td>
                    <td><?php echo htmlspecialchars($ledger['balance']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
// Close the connection
$conn->close();
?>