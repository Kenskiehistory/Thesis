function initializeManageQuizzesPage() {
    const quizForm = document.getElementById('quiz-form');
    const generateAnswersButton = document.getElementById('generate-answers');
    const quizList = document.getElementById('quiz-list');

    // Event listener for generating answer fields
    generateAnswersButton.addEventListener('click', function() {
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

    // Event listener for form submission
    quizForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(quizForm);

        fetch('api/upload_quiz.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Quiz created successfully!');
                loadQuizzes(); // Reload the quiz list
                quizForm.reset(); // Reset the form
            } else {
                alert('Error creating quiz: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the quiz.');
        });
    });

    // Updated function to load quizzes
    function loadQuizzes() {
        fetch('api/get_quizzes.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            quizList.innerHTML = '<h2>Existing Quizzes</h2>';
            if (data.length === 0) {
                quizList.innerHTML += '<p>No quizzes found.</p>';
            } else {
                data.forEach(quiz => {
                    const quizElement = document.createElement('div');
                    quizElement.innerHTML = `
                        <h3>${quiz.title}</h3>
                        <p>Course: ${quiz.course_name}</p>
                        <p>Created by: ${quiz.created_by}</p>
                        <p>Created at: ${quiz.created_at}</p>
                        <p>Number of questions: ${quiz.num_questions}</p>
                    `;
                    quizList.appendChild(quizElement);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            quizList.innerHTML = `<p>Error loading quizzes: ${error.message}</p>`;
        });
    }

    // Load quizzes when the page initializes
    loadQuizzes();
}