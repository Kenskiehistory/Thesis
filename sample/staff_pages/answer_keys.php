<?php
session_start(); // Make sure sessions are enabled

if (!isset($_SESSION['course'])) {
    die("Error: Course not set in session");
}

$course_name = $_SESSION['course']; // Fetch the course name from the session

header('Content-Type: application/json');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../includes/db_connect.php');

    $exam_title = $_POST['exam_title'];
    $answer_key_raw = $_POST['answer_key'];

    // Convert answer key into JSON
    $lines = explode("\n", $answer_key_raw);
    $answer_key_array = [];
    
    foreach ($lines as $line) {
        $parts = explode(":", trim($line));
        if (count($parts) === 2) {
            $question = trim($parts[0]);
            $answer = trim($parts[1]);
            $answer_key_array[$question] = $answer;
        }
    }

    $answer_key_json = json_encode($answer_key_array);

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO exam_answer_keys (exam_title, course_name, answer_key) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $exam_title, $course_name, $answer_key_json);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Answer key added successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error adding answer key: ' . $conn->error]);
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['error' => 'Method not allowed']);
}
?>
<div class="container">
    <h3>Submit Answer Key</h3>
    <form id="answer-key-form">
        <div class="form-group">
            <label for="exam_title">Exam Title</label>
            <input type="text" class="form-control" id="exam_title" name="exam_title" placeholder="Enter exam title" required>
        </div>

        <div class="form-group">
            <label for="answer_key">Answer Key</label>
            <textarea class="form-control" id="answer_key" name="answer_key" rows="8" placeholder="Enter answer key in format: QuestionNumber: Answer" required></textarea>
            <small class="form-text text-muted">
                Example: <br>
                1: A <br>
                2: B <br>
                3: C
            </small>
        </div>

        <!-- Hidden field for course name from session -->
        <input type="hidden" id="course_name" name="course_name" value="<?php echo $course_name; ?>">

        <button type="submit" class="btn btn-primary">Submit Answer Key</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('answer-key-form');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);

            fetch('submit_answer_key.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    form.reset(); // Clear form after successful submission
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error submitting the form.');
            });
        });
    });
</script>
