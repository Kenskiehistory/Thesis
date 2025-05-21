<?php
ob_start();
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Student');

// Check if this is an AJAX request
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if (!$isAjax) {
    include('../includes/header.php');
}

if (isset($_GET['id'])) {
    $subject_id = $_GET['id'];

    // Fetch subject details
    $stmt = $conn->prepare('SELECT * FROM subjects WHERE id = ?');
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $subject = $result->fetch_assoc();
    } else {
        echo "<p>Subject not found.</p>";
        exit();
    }
    $stmt->close();

    // Fetch contents associated with the subject
    $stmt = $conn->prepare('SELECT * FROM subject_contents WHERE subject_id = ?');
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    $contents = $stmt->get_result();
    $stmt->close();
} else {
    echo "<p>Subject ID not provided.</p>";
    exit();
}
?>

<div class="row">
    <div class="col-12">
        <!-- Subject Information Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($subject['subjectName']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($subject['description']); ?></p>
            </div>
        </div>

        <!-- Contents Section Card -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Contents</h5>
                <?php if ($contents->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($content = $contents->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <h6><?php echo htmlspecialchars($content['title']); ?></h6>
                                <p><?php echo htmlspecialchars($content['content']); ?></p>
                                <?php if ($content['file_path']): ?>
                                    <a href="<?php echo htmlspecialchars('../'.$content['file_path']); ?>" class="btn btn-primary" download>Download File</a>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No contents available for this subject.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!$isAjax) {
    include('../includes/footer.php');
} ?>
