<?php
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Staff');

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
        echo json_encode(['error' => 'Subject not found']);
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
    echo json_encode(['error' => 'Subject ID not provided']);
    exit();
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($subject['subjectName']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($subject['description']); ?></p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Contents</h5>
                <button class="btn btn-primary mb-3" id="addContentBtn" data-subject-id="<?php echo $subject['id']; ?>">Add Content</button>
                <?php if ($contents->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($content = $contents->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <h6><?php echo htmlspecialchars($content['title']); ?></h6>
                                <p><?php echo htmlspecialchars($content['content']); ?></p>
                                <?php if ($content['file_path']): ?>
                                    <a href="<?php echo htmlspecialchars('../sample/'.$content['file_path']); ?>" class="btn btn-primary" download>Download File</a>
                                <?php endif; ?>
                                <button class="btn btn-warning editContentBtn" data-content-id="<?php echo $content['id']; ?>">Edit</button>
                                <button class="btn btn-danger deleteContentBtn" data-content-id="<?php echo $content['id']; ?>">Delete</button>
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