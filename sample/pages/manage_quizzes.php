<!DOCTYPE html>
<html>
<head>
    <title>Manage Quizzes</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        #quiz-list {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h1>Manage Quizzes</h1>
        
        <form id="quiz-form" method="POST" enctype="multipart/form-data">
            <h2>Create New Quiz</h2>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="course_name">Course:</label>
                <select id="course_name" name="course_name" required>
                    <option value="Achitecture">Achitecture</option>
                    <option value="Civil Engineering">Civil Engineering</option>
                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                    <option value="Master Plumbing">Master Plumbing</option>
                </select>
            </div>
            <div class="form-group">
                <label for="file">Upload Quiz File:</label>
                <input type="file" id="file" name="file" required>
            </div>
            <div class="form-group">
                <label for="num_questions">Number of Questions:</label>
                <input type="number" id="num_questions" name="num_questions" min="1" required>
            </div>
            <div id="answers-container">
                <!-- Answer inputs will be dynamically added here -->
            </div>
            <button type="button" id="generate-answers">Generate Answer Fields</button>
            <button type="submit">Create Quiz</button>
            <button type="button" onclick="window.history.back();">Back</button>
        </form>
    </div>

    <script>
        document.getElementById('generate-answers').addEventListener('click', function() {
            const numQuestions = document.getElementById('num_questions').value;
            const answersContainer = document.getElementById('answers-container');
            answersContainer.innerHTML = ''; // Clear previous answer fields
            
            for (let i = 0; i < numQuestions; i++) {
                const answerGroup = document.createElement('div');
                answerGroup.className = 'answer-group';
                answerGroup.innerHTML = `
                    <label for="answer_${i}">Answer for Question ${i + 1}:</label>
                    <input type="text" id="answer_${i}" name="answers[]" required>
                `;
                answersContainer.appendChild(answerGroup);
            }
        });

        document.getElementById('quiz-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(this);

            fetch('../api/upload_quizzes.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Display the response from the server
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>