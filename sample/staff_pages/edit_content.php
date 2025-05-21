<?php
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Staff');
include('upload_image.php');

$response = ['success' => false, 'message' => '', 'html' => ''];

if (isset($_GET['id'])) {
    $content_id = $_GET['id'];

    // Fetch the content details
    $stmt = $conn->prepare('SELECT * FROM subject_contents WHERE id = ?');
    $stmt->bind_param("i", $content_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $content = $result->fetch_assoc();
    } else {
        $response['message'] = "Invalid content ID";
        echo json_encode($response);
        exit();
    }
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['content'])) {
        try {
            // Handle the file upload if a new file is provided
            $image_path = $content['file_path']; // Keep the existing file path by default
            if (!empty($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $upload_result = upload_image($_FILES['image']);
                if ($upload_result['success']) {
                    $image_path = 'all/uploads/' . $upload_result['filename'];
                    
                    // Delete the old file if it exists
                    if ($content['file_path'] && file_exists('../' . $content['file_path'])) {
                        unlink('../' . $content['file_path']);
                    }
                } else {
                    $response['message'] = $upload_result['message'];
                    echo json_encode($response);
                    exit();
                }
            }

            // Update the content in the database
            $stmt = $conn->prepare('UPDATE subject_contents SET title = ?, content = ?, file_path = ? WHERE id = ?');
            $stmt->bind_param("sssi", $_POST['title'], $_POST['content'], $image_path, $content_id);
            $stmt->execute();

            $response['success'] = true;
            $response['message'] = "Content has been updated successfully";
            $response['subject_id'] = $content['subject_id'];
            echo json_encode($response);
            exit();
        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
            echo json_encode($response);
            exit();
        }
    } else {
        // Return the form HTML
        ob_start();
?>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Content</h4>
                <p class="card-description">Edit existing content</p>
                <form id="editContentForm" class="forms-sample" enctype="multipart/form-data">
                    <input type="hidden" name="content_id" value="<?php echo $content_id; ?>">
                    <div class="form-group">
                        <label class="form-label" for="title">Title</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($content['title']); ?>" required />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="content">Content</label>
                        <textarea id="content" name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($content['content']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="image">Image (optional)</label>
                        <input type="file" id="image" name="image" class="form-control-file" accept="image/*" />
                    </div>

                    <?php if ($content['file_path']): ?>
                        <div class="form-group">
                            <p>Current image: <a href="<?php echo htmlspecialchars('../' . $content['file_path']); ?>" target="_blank">View</a></p>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary btn-block">Update Content</button>
                    <button type="button" class="btn btn-success" id="backToSubject" data-subject-id="<?php echo $content['subject_id']; ?>">Back to Subject</button>
                </form>
            </div>
        </div>
<?php
        $response['html'] = ob_get_clean();
        echo json_encode($response);
        exit();
    }
} else {
    $response['message'] = "No content ID provided";
    echo json_encode($response);
    exit();
}
?>