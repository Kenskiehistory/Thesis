<?php
include('../includes/db_connect.php');
include('../includes/function.php');
secure();
check_role('Staff');
include('upload_image.php');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'html' => ''];

try {
    if (isset($_GET['subject_id'])) {
        $subject_id = $_GET['subject_id'];

        // Check if the subject ID is valid
        $stmt = $conn->prepare('SELECT * FROM subjects WHERE id = ?');
        $stmt->bind_param("i", $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $subject = $result->fetch_assoc();
        } else {
            throw new Exception("Invalid subject ID");
        }
        $stmt->close();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['content'])) {
            // Handle the file upload first
            $image_path = null;
            if (!empty($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $upload_result = upload_image($_FILES['image']);
                if ($upload_result['success']) {
                    $image_path = 'all/uploads/' . $upload_result['filename'];
                } else {
                    throw new Exception($upload_result['message']);
                }
            }

            // Insert new content into the subject_contents table
            $stmt = $conn->prepare('INSERT INTO subject_contents (subject_id, title, content, file_path, created_at) VALUES (?, ?, ?, ?, NOW())');
            $stmt->bind_param("isss", $subject_id, $_POST['title'], $_POST['content'], $image_path);
            $stmt->execute();

            $response['success'] = true;
            $response['message'] = "New content titled '" . $_POST['title'] . "' has been added";
            $response['subject_id'] = $subject_id;
        } else {
            // Return the form HTML
            ob_start();
?>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add Content</h4>
                    <p class="card-description">Add new content to the subject</p>
                    <form id="addContentForm" class="forms-sample" enctype="multipart/form-data">
                        <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
                        <div class="form-group">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Title" required />
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="content">Content</label>
                            <textarea id="content" name="content" class="form-control" placeholder="Content" rows="5" required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="image">Image (optional)</label>
                            <input type="file" id="image" name="image" class="form-control-file" accept="image/*" />
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Upload</button>
                        <button type="button" class="btn btn-success" id="backToSubject">Back to Subject</button>
                    </form>
                </div>
            </div>
<?php
            $response['html'] = ob_get_clean();
        }
    } else {
        throw new Exception("No subject ID provided");
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit();
?>