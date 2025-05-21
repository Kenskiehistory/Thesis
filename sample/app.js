
document.addEventListener('DOMContentLoaded', () => {
    const content = document.getElementById('content');
    const sidebar = document.querySelector('.sidebar');
    const menuToggle = document.querySelector('.menu-toggle');

    const loadContent = (page) => {
        fetch(`pages/${page}.php`)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;

                // Wait until the DOM has been updated with the new content before initializing
                setTimeout(() => {
                    if (page === 'add_user') {
                        initializeAddUserPage();
                    }
                    if (page === 'users') {
                        initializeUsersPage();
                    }
                    if (page === 'add_review') {
                        initializeCourseManagement();
                    }
                    if (page === 'add_section') {
                        initializeSectionManagement();
                    }
                    if (page === 'profile') {
                        initProfileViewButtons();
                        initLedgerViewButtons();
                        initializeCourseFilter();
                    }
                    if (page === 'staff') {
                        initializeManageStaffPage();
                    }
                    if (page === 'payment_form') {
                        initializePaymentForm();
                    }
                    if (page === 'subjects') {
                        initializeManageSubjectPage();
                    }
                    if (page === 'add_subject') {
                        initializeAddSubjectForm();
                    }
                    if (page === 'add_staff') {
                        initializeAddStaffForm();
                    }
                    if (page === 'post_announcement') {
                        initializeAnnouncementForm();
                    }
                    if (page === 'view_announcements') {
                        initializeAnnouncementView();
                    }
                    if (page === 'pending_registrations') {
                        initializePendingPage();

                    }
                    if (page === 'manage_quizzes') {
                        initializeManageQuizzesPage();
                    }
                    if (page === 'dashboard') {
                        initializeDashboardFilter();
                        initializeDashboardExports();
                    }
                    if (page === 'fees_form') {
                        fetchPaymentRecords();
                    }
                    if (page === 'payment1') {
                        initializePaymentsTable();
                        initializePaymentFormButton();
                    }
                    if (page === 'report') {
                        initializeDashboardFilter();
                        initializeStudentReport();
                    }
                    initializeLogoutButton();
                }, 0); // Defer the initialization to ensure DOM is ready
            })
            .catch(error => {
                content.innerHTML = '<h1>Error</h1><p>Sorry, an error occurred while loading the page.</p>';
                console.error('Error loading content:', error);
            });
    };

    const fetchPaymentRecords = () => {
        fetch('api/fees_form_submit.php?action=get_fees')
            .then(response => response.json())
            .then(data => {
                const recordsTable = document.querySelector('.table-responsive .table tbody');
                const courseFilter = document.getElementById('courseFilter');

                // Function to render the table
                const renderTable = (filteredData) => {
                    recordsTable.innerHTML = ''; // Clear current content

                    filteredData.forEach(fee => {
                        const row = document.createElement('tr');

                        row.innerHTML = `
                            <td>${fee.particular}</td>
                            <td>${fee.courseName}</td>
                            <td>${fee.amount}</td>
                            <td>${new Date(fee.current_year).toLocaleString()}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${fee.id}">Delete</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });

                    // Add event listeners for delete buttons
                    document.querySelectorAll('.delete-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const id = this.getAttribute('data-id');
                            deletePaymentRecord(id);
                        });
                    });
                };

                // Initial render
                renderTable(data);

                // Add event listener for course filter
                courseFilter.addEventListener('change', () => {
                    const selectedCourse = courseFilter.value;
                    const filteredData = selectedCourse
                        ? data.filter(fee => fee.courseName === selectedCourse)
                        : data;
                    renderTable(filteredData);
                });
            })
            .catch(error => console.error('Error:', error));
    };

    function initializeManageSubjectPage() {
        const courseFilter = document.getElementById('courseFilter');
        const subjectsContainer = document.getElementById('subjectsContainer');

        if (courseFilter && subjectsContainer) {
            courseFilter.addEventListener('change', () => {
                const selectedCourse = courseFilter.value.toLowerCase();
                const subjectCards = subjectsContainer.querySelectorAll('.subject-card');

                subjectCards.forEach(card => {
                    const courseName = card.dataset.course.toLowerCase();
                    if (selectedCourse === '' || courseName === selectedCourse) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Add toggle functionality for is_active status
        const toggleButtons = document.querySelectorAll('.toggle-active-btn');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const subjectId = this.dataset.subjectId;
                const currentStatus = parseInt(this.dataset.status);
                const newStatus = currentStatus === 1 ? 0 : 1;

                fetch('api/update_status_subjects.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `subjectId=${subjectId}&newStatus=${newStatus}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button text and class
                            this.textContent = newStatus === 1 ? 'Deactivate' : 'Activate';
                            this.classList.toggle('btn-success');
                            this.classList.toggle('btn-danger');
                            this.dataset.status = newStatus;

                            // Update status text
                            const card = this.closest('.card');
                            const statusText = card.querySelector('.status-text');
                            statusText.textContent = newStatus === 1 ? 'Active' : 'Inactive';
                            statusText.classList.toggle('text-success');
                            statusText.classList.toggle('text-danger');
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error updating subject status:', error));
            });
        });

        const addNewSubjectBtn = document.getElementById('addNewSubjectBtn');
        if (addNewSubjectBtn) {
            addNewSubjectBtn.addEventListener('click', () => {
                loadContent('add_subject');
            });
        }
    }


    // Function to delete a payment record
    const deletePaymentRecord = (id) => {
        if (confirm('Are you sure you want to delete this record?')) {
            fetch(`api/fees_form_submit.php?action=delete_fee&id=${id}`, {
                method: 'DELETE'
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Record deleted successfully');
                        fetchPaymentRecords(); // Refresh the table
                    } else {
                        alert('Failed to delete record');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    };

    const initializeDashboardExports = () => {
        const exportButtons = document.querySelectorAll('.export-btn');
        exportButtons.forEach(button => {
            button.addEventListener('click', () => {
                const dataType = button.getAttribute('data-export');
                exportData(dataType);
            });
        });
    };

    const exportData = (dataType) => {
        const data = JSON.parse(document.getElementById(`${dataType}_data`).textContent);

        // Prepare the data for export
        let exportData = data;
        if (dataType === 'user_ledger' || dataType === 'waitlist') {
            exportData = data.map(item => {
                const { user_profile_id, ...rest } = item;
                return { ...rest, full_name: item.full_name };
            });
        }

        const ws = XLSX.utils.json_to_sheet(exportData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, dataType);
        XLSX.writeFile(wb, `${dataType}_export.xlsx`);
    };

    const initializeDashboardFilter = () => {
        const filterForm = document.getElementById('filter-form');
        const filterSubmitButton = document.getElementById('filter-submit');
        const exportExcelButton = document.getElementById('export-excel');

        filterSubmitButton.addEventListener('click', () => {
            applyFilter();
        });

        exportExcelButton.addEventListener('click', () => {
            exportToExcel();
        });

        const applyFilter = () => {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const course = document.getElementById('course').value;

            fetch('pages/filter_dashboard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ start_date: startDate, end_date: endDate, course: course })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    // Update the date, course, and total credit display dynamically
                    document.getElementById('start-date-display').textContent = data.start_date;
                    document.getElementById('end-date-display').textContent = data.end_date;
                    document.getElementById('course-display').textContent = data.course === 'all' ? 'All Courses' : data.course;
                    document.getElementById('total-credit').textContent = parseFloat(data.total_credit).toFixed(2);
                })
                .catch(error => {
                    console.error('Error filtering data:', error);
                    alert('An error occurred while filtering: ' + error.message);
                });
        };

        const exportToExcel = () => {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const course = document.getElementById('course').value;

            const formData = new FormData();
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            formData.append('course', course);

            fetch('pages/export_dashboard_data.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text || 'Unknown error occurred');
                        });
                    }
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'dashboard_export.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error('Export error:', error);
                    alert('An error occurred during export: ' + error.message);
                });
        };
    };

    const initializeStudentReport = () => {
        const studentFilterSubmitButton = document.getElementById('student-filter-submit');
        const exportStudentsExcelButton = document.getElementById('export-students-excel');

        studentFilterSubmitButton.addEventListener('click', () => {
            applyStudentFilter();
        });

        exportStudentsExcelButton.addEventListener('click', () => {
            exportStudentsToExcel();
        });

        const applyStudentFilter = () => {
            const startDate = document.getElementById('student_start_date').value;
            const endDate = document.getElementById('student_end_date').value;
            const course = document.getElementById('student_course').value;

            fetch('pages/filter_students.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    student_start_date: startDate,
                    student_end_date: endDate,
                    course: course
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    displayStudentResults(data);
                })
                .catch(error => {
                    console.error('Error filtering student data:', error);
                    alert('An error occurred while filtering student data: ' + error.message);
                });
        };

        const displayStudentResults = (data) => {
            const resultsContainer = document.getElementById('student-report-results');
            let html = '<table class="table table-striped">';
            html += '<thead><tr><th>Name</th><th>Course</th><th>Email</th><th>Contact Number</th><th>Enrollment Date</th></tr></thead>';
            html += '<tbody>';
            data.students.forEach(student => {
                html += `<tr>
                    <td>${student.fName} ${student.lName}</td>
                    <td>${student.courseName}</td>
                    <td>${student.email}</td>
                    <td>${student.conNumb}</td>
                    <td>${student.added}</td>
                </tr>`;
            });
            html += '</tbody></table>';
            resultsContainer.innerHTML = html;
        };

        const exportStudentsToExcel = () => {
            const startDate = document.getElementById('student_start_date').value;
            const endDate = document.getElementById('student_end_date').value;
            const course = document.getElementById('student_course').value;

            const formData = new FormData();
            formData.append('student_start_date', startDate);
            formData.append('student_end_date', endDate);
            formData.append('course', course);

            fetch('pages/export_students.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text || 'Unknown error occurred');
                        });
                    }
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'student_enrollment_report.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error('Export error:', error);
                    alert('An error occurred during export: ' + error.message);
                });
        };
    };

    function initializeLogoutButton() {
        const logoutButton = document.getElementById('logoutButton');
        if (logoutButton) {
            logoutButton.addEventListener('click', function (e) {
                e.preventDefault();
    
                // Create a full-screen loading overlay
                const loadingOverlay = document.createElement('div');
                loadingOverlay.innerHTML = `
                    <style>
                        #logout-overlay {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background-color: rgba(255, 255, 255, 0.9);
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            z-index: 9999;
                            opacity: 0;
                            transition: opacity 0.3s ease-in-out;
                        }
                        #logout-overlay.show {
                            opacity: 1;
                        }
                        .logout-spinner {
                            width: 100px;
                            height: 100px;
                            border: 10px solid #f3f3f3;
                            border-top: 10px solid #3498db;
                            border-radius: 50%;
                            animation: spin 1s linear infinite;
                        }
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        .logout-message {
                            margin-top: 20px;
                            font-size: 1.2em;
                            color: #333;
                        }
                    </style>
                    <div id="logout-overlay">
                        <div>
                            <div class="logout-spinner"></div>
                            <div class="logout-message">Logging out...</div>
                        </div>
                    </div>
                `;
                
                // Append to body and trigger show effect
                document.body.appendChild(loadingOverlay.querySelector('#logout-overlay'));
                
                // Slight delay to ensure overlay is in DOM before showing
                setTimeout(() => {
                    const overlay = document.getElementById('logout-overlay');
                    overlay.classList.add('show');
                }, 10);
    
                // Redirect to logout page
                setTimeout(() => {
                    window.location.href = 'pages/logout.php';
                }, 1500);
            });
        }
    }

    // Add this new function
    function initializeCourseFilter() {
        const courseFilter = document.getElementById('courseFilter');
        const profileTable = document.getElementById('profileTable');

        if (courseFilter && profileTable) {
            courseFilter.addEventListener('change', function () {
                const selectedCourse = this.value;
                const rows = profileTable.getElementsByTagName('tr');

                for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
                    const courseCell = rows[i].getElementsByTagName('td')[1]; // Index 1 is the Course column
                    if (courseCell) {
                        const courseName = courseCell.textContent || courseCell.innerText;
                        if (selectedCourse === '' || courseName === selectedCourse) {
                            rows[i].style.display = '';
                        } else {
                            rows[i].style.display = 'none';
                        }
                    }
                }
            });
        }
    }
    const initializeManageQuizzesPage = () => {
        // Rebind the "Generate Answer Fields" button functionality
        const generateAnswersButton = document.getElementById('generate-answers');
        const quizForm = document.getElementById('quiz-form');

        if (quizForm) {
            quizForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent the default form submission

                // Create FormData object to hold the form inputs
                const formData = new FormData(quizForm);

                // Send the data to the backend API using fetch
                fetch('api/upload_quizzes.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // If the quiz was successfully created
                            alert('Quiz created successfully!');
                            // Optionally, reload the quiz list or clear the form
                            quizForm.reset();
                        } else {
                            // If an error occurred, show the error message
                            alert('Error creating quiz: ' + data.message);
                        }
                    })
                    .catch(error => {
                        // Catch and log any network errors
                        console.error('Error:', error);
                        alert('An error occurred while creating the quiz.');
                    });
            });
        }

        if (generateAnswersButton) {
            generateAnswersButton.addEventListener('click', function () {
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
        }
    };

    const initializeWaitlist = () => {
        const payment_form = document.getElementById('payment_form');
        if (payment_form) {
            payment_form.addEventListener('click', (event) => {
                event.preventDefault();
                loadContent('payment_form');
            });
        }
    };
    const initializeManageStaffPage = () => {
        const addNewStaffBtn = document.getElementById('addNewStaffBtn');
        if (addNewStaffBtn) {
            addNewStaffBtn.addEventListener('click', (event) => {
                event.preventDefault();
                loadContent('add_staff');
            });
        }
    };

    // Load content based on sidebar clicks
    document.querySelectorAll('a[data-page]').forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const page = event.target.getAttribute('data-page');
            loadContent(page);
        });
    });

    function initializeAddSubjectForm() {
        const addSubForm = document.getElementById('addSubForm');
        if (addSubForm) {
            addSubForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const formData = new FormData(addSubForm);

                fetch('api/submit_subject.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            loadContent('subjects'); // Navigate back to manage subjects page
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while submitting the form.');
                    });
            });
        }
    }



    function initializeAddStaffForm() {
        const addStaffForm = document.getElementById('addStaffForm');
        if (addStaffForm) {
            addStaffForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const formData = new FormData(addStaffForm);

                fetch('api/submit_staff.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            loadContent('staff'); // Navigate back to manage subjects page
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while submitting the form.');
                    });
            });
        }
    }



    function initializePaymentForm() {
        const userSelect = document.getElementById('user_profile_id');
        const debitForm = document.getElementById('debit_form');
        const creditForm = document.getElementById('credit_form');
        const ledgerTable = document.getElementById('ledger_table');
        const courseSelection = document.getElementById('course_selection');
        const manualEntry = document.getElementById('manual_entry');

        // Load user profiles
        fetch('api/get_user_profiles.php')
            .then(response => response.json())
            .then(data => {
                userSelect.innerHTML = '<option value="">Select a student</option>';
                data.forEach(profile => {
                    userSelect.innerHTML += `<option value="${profile.id}">${profile.fName} ${profile.mName} ${profile.lName}</option>`;
                });
            });

        // Load courses
        fetch('api/get_courses.php')
            .then(response => response.json())
            .then(data => {
                const courseSelect = document.getElementById('selected_course');
                courseSelect.innerHTML = '<option value="">Select a course</option>';
                data.forEach(course => {
                    courseSelect.innerHTML += `<option value="${course}">${course}</option>`;
                });
            });

        // Handle user selection
        userSelect.addEventListener('change', (e) => {
            const userId = e.target.value;
            if (userId) {
                document.getElementById('debit_user_id').value = userId;
                document.getElementById('credit_user_id').value = userId;
                loadLedger(userId);
            } else {
                ledgerTable.innerHTML = '<tr><td colspan="5">Please select a student</td></tr>';
                paymentStatusForm.style.display = 'none';
            }
        });

        // Handle debit option change
        document.querySelectorAll('input[name="debit_option"]').forEach((elem) => {
            elem.addEventListener("change", function (event) {
                if (this.value === 'course') {
                    courseSelection.style.display = 'block';
                    manualEntry.style.display = 'none';
                } else {
                    courseSelection.style.display = 'none';
                    manualEntry.style.display = 'block';
                }
            });
        });

        // Show confirmation modal before adding credit
        creditForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(creditForm);
            const amount = formData.get('credit_amount');
            const particulars = formData.get('credit_particulars');
            const or = formData.get('credit_or');

            // Set the modal message
            const confirmModalBody = document.getElementById('confirmModalBody');
            confirmModalBody.innerHTML = `Are you sure you want to add a credit of <strong>${amount}</strong> with particulars: "<strong>${particulars}</strong>" and OR: "<strong>${or}</strong>"?`;

            // Show the modal
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();

            // Confirm button action
            document.getElementById('confirmActionBtn').onclick = function () {
                confirmModal.hide();
                formData.append('action', 'add_credit');
                submitForm(formData);
            };
        });

        // Show confirmation modal before adding debit
        debitForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(debitForm);
            const amount = formData.get('debit_amount');
            const particulars = formData.get('debit_particulars');

            // Set the modal message
            const confirmModalBody = document.getElementById('confirmModalBody');
            confirmModalBody.innerHTML = `Are you sure you want to add a debit of <strong>${amount}</strong> with particulars: "<strong>${particulars}</strong>"?`;

            // Show the modal
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();

            // Confirm button action
            document.getElementById('confirmActionBtn').onclick = function () {
                confirmModal.hide();
                formData.append('action', 'add_debit');
                submitForm(formData);
            };
        });
        
        function loadLedger(userId) {
            fetch(`api/get_ledger.php?user_profile_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ledgerTable.innerHTML = `
                        <tr>
                            <th>Date</th>
                            <th>Particulars</th>
                            <th>Original Receipt</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                    `;
                        data.data.forEach(entry => {
                            const particulars = entry.particulars ? entry.particulars : ''; // Check for null in particulars
                            const receipt = entry.original_receipt ? entry.original_receipt : ''; // Check for null in original_receipt
                            ledgerTable.innerHTML += `
                            <tr>
                                <td>${entry.date}</td>
                                <td>${particulars}</td>
                                <td>${receipt}</td>
                                <td>${entry.debit}</td>
                                <td>${entry.credit}</td>
                                <td>${entry.balance}</td>
                            </tr>
                        `;
                        });
                    } else {
                        ledgerTable.innerHTML = `<tr><td colspan="6">Error: ${data.error}</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error('Error loading ledger:', error);
                    ledgerTable.innerHTML = '<tr><td colspan="6">Error loading ledger data</td></tr>';
                });
        }

        function submitForm(formData) {
            fetch('api/update_ledger.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadLedger(formData.get('user_profile_id'));
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error submitting form:', error);
                    alert('An error occurred while submitting the form');
                });
        }

    }

    // New function to initialize the add user page
    const initializeAddUserPage = () => {
        const form = document.getElementById('userForm');
        const rolesSelect = document.getElementById('roles');
        const profileSelection = document.getElementById('profile_selection');
        const adminUsername = document.getElementById('admin_username');
        const profileIdSelect = document.getElementById('profile_id');
        const emailInput = document.getElementById('email');

        const toggleProfileSelection = () => {
            const role = rolesSelect.value;
            if (role === 'Admin') {
                profileSelection.style.display = 'none';
                adminUsername.style.display = 'block';
                profileIdSelect.innerHTML = '';
            } else {
                adminUsername.style.display = 'none';
                profileSelection.style.display = 'block';

                fetch(`pages/fetch_profiles.php?role=${role}`)
                    .then(response => response.json())
                    .then(profiles => {
                        profileIdSelect.innerHTML = '<option value="">Select Profile</option>' +
                            profiles.map(profile => `<option value="${profile.id}">${profile.name}</option>`).join('');
                    });
            }
        };

        const updateEmail = () => {
            const profileId = profileIdSelect.value;
            const role = rolesSelect.value;

            if (profileId) {
                fetch(`pages/fetch_email.php?role=${role}&profile_id=${profileId}`)
                    .then(response => response.text())
                    .then(email => {
                        emailInput.value = email;
                    });
            } else {
                emailInput.value = '';
            }
        };

        rolesSelect.addEventListener('change', toggleProfileSelection);
        profileIdSelect.addEventListener('change', updateEmail);

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(form);

            fetch('pages/submit_user.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User added successfully!');
                        loadContent('users'); // Go back to user list
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Initialize the form state
        toggleProfileSelection();
    };

    // Event delegation for dynamically added "Add User" button
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-add-user')) {
            event.preventDefault();
            loadContent('add_user');
        }
    });

    const initializeUsersPage = () => {
        const editButtons = document.querySelectorAll('.edit-user');
        const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        const editForm = document.getElementById('editUserForm');
        const saveChangesBtn = document.getElementById('saveUserChanges');

        editButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const userId = button.getAttribute('data-id');

                // Fetch user data and populate the form
                fetch(`pages/fetch_users.php?id=${userId}`)
                    .then(response => response.json())
                    .then(user => {
                        document.getElementById('editUserId').value = user.id;
                        document.getElementById('editUsername').value = user.username;
                        document.getElementById('editEmail').value = user.email;
                        document.getElementById('editRole').value = user.roles;
                        document.getElementById('editActive').value = user.active;
                        editModal.show();
                    });
            });
        });

        saveChangesBtn.addEventListener('click', () => {
            const formData = new FormData(editForm);

            fetch('pages/update_user.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User updated successfully!');
                        editModal.hide();
                        loadContent('users'); // Reload the users page
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    };

    // Event delegation for dynamically added "See Paymongo Pay" button
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-paymongo')) {
            event.preventDefault();
            loadContent('payment1');
        }
    });


    // Function to initialize section management on "add_section" page
    const initializeSectionManagement = () => {
        const sectionForm = document.getElementById('courseForm');

        // Fetch existing sections and populate the table
        fetch('pages/fetch_sections.php')
            .then(response => response.json())
            .then(data => {
                const sectionTableBody = document.getElementById('sectionTableBody');
                sectionTableBody.innerHTML = ''; // Clear any existing rows
                data.forEach(section => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                            <td>${section.id}</td>
                            <td>${section.course_name}</td>
                            <td>${section.section_name}</td>
                            <td>${section.section_limit}</td>
                            <td>${section.section_count}</td>
                        `;
                    sectionTableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching sections:', error);
            });

        // AJAX form submission for the section form
        sectionForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const courseName = document.getElementById('courseName').value;
            const sectionName = document.getElementById('sectionName').value;
            const sectionLimit = document.getElementById('sectionLimit').value;

            fetch('pages/submit_section.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `courseName=${courseName}&sectionName=${sectionName}&sectionLimit=${sectionLimit}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Section added successfully!');
                        // Optionally reload the table to reflect the new entry
                        initializeSectionManagement(); // Refresh table
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error submitting form:', error);
                });
        });
    };


    // Function to initialize course management on "add_review" page
    const initializeCourseManagement = () => {
        const courseForm = document.getElementById('courseForm');

        // Fetch existing course reviews and populate the table
        fetch('pages/fetch_courses.php')
            .then(response => response.json())
            .then(data => {
                const courseTableBody = document.querySelector('#courseTable tbody');
                courseTableBody.innerHTML = ''; // Clear any existing rows
                data.forEach(course => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${course.coursename}</td>
                        <td>${course.reviews}</td>
                        <td>${course.tuition_fee}</td>
                        <td>${course.review_status}</td>
                        <td><button class="btn btn-primary btn-sm">Edit</button></td>
                    `;
                    courseTableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching course reviews:', error);
            });

        // AJAX submission for the form
        courseForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const courseName = document.getElementById('courseName').value;
            const reviewSchedule = document.getElementById('reviewSchedule').value;
            const tuitionFee = document.getElementById('tuitionFee').value;

            fetch('pages/submit_review.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `courseName=${courseName}&reviewSchedule=${reviewSchedule}&tuitionFee=${tuitionFee}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Course added successfully!');
                        // Optionally reload the table to reflect the new entry
                        initializeCourseManagement(); // Refresh table
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error submitting form:', error);
                });
        });
    };

    // Toggle sidebar
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    // Close sidebar when clicking outside of it on mobile
    document.addEventListener('click', (event) => {
        if (window.innerWidth <= 768 && !sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove('active');
        }
    });

    // Event listeners for main menu items
    document.querySelectorAll('.sidebar ul li a').forEach(link => {
        link.addEventListener('click', (event) => {
            const submenu = link.nextElementSibling;
            const icon = link.querySelector('.submenu-icon');
            if (submenu && submenu.classList.contains('submenu')) {
                event.preventDefault();
                // Collapse other submenus
                document.querySelectorAll('.submenu').forEach(sub => {
                    if (sub !== submenu) {
                        sub.style.display = 'none';
                        const otherIcon = sub.previousElementSibling.querySelector('.submenu-icon');
                        if (otherIcon) {
                            otherIcon.classList.remove('open');
                        }
                    }
                });
                // Toggle the clicked submenu
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                if (icon) {
                    icon.classList.toggle('open');
                }
            } else {
                const page = link.textContent.trim().toLowerCase().replace(' ', '_');
                loadContent(page);
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                }
            }
        });
    });

    // Event listeners for submenu items
    document.querySelectorAll('.submenu li a').forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const page = link.textContent.trim().toLowerCase().replace(' ', '_');
            loadContent(page);
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
            }
        });
    });

    // Function to initialize profile view buttons
    const initProfileViewButtons = () => {
        document.querySelectorAll('.view-profile').forEach(button => {
            button.addEventListener('click', (event) => {
                const studentId = event.target.getAttribute('data-student-id');
                fetch('pages/profile_view.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `student_id=${studentId}`
                })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('profileViewContent').innerHTML = data;
                        $('#profileViewModal').modal('show');

                        // Adjust modal size to extra large
                        $('#profileViewModal .modal-dialog').addClass('modal-fullscreen');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    };

    // Function to initialize ledger view buttons
    const initLedgerViewButtons = () => {
        document.querySelectorAll('.view-profile-ledger').forEach(button => {
            button.addEventListener('click', (event) => {
                const studentId = event.target.getAttribute('data-student-id');
                fetch('pages/ledger_view.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `student_id=${studentId}`
                })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('ledgerViewContent').innerHTML = data;
                        $('#ledgerViewModal').modal('show');

                        // Adjust modal size to extra large
                        $('#ledgerViewModal .modal-dialog').addClass('modal-fullscreen');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    };

    // Function to initialize payment1 form
    function initializePaymentsTable() {
        console.log("Initializing payments table");
        const updateButton = document.getElementById('updateButton');
        const paymentsTable = document.getElementById('paymentsTable');

        if (updateButton && paymentsTable) {
            console.log("Update button and payments table found");
            updateButton.addEventListener('click', updatePaymentsTable);
        } else {
            console.error("Update button or payments table not found");
        }

        function updatePaymentsTable() {
            // Show loading indicator
            paymentsTable.innerHTML = '<tr><td colspan="8">Loading...</td></tr>';

            fetch('pages/payment1.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'action=update_payments'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text(); // Manually handle the response as text
                })
                .then(text => {
                    const data = JSON.parse(text); // Manually parse the JSON
                    if (data.success) {
                        updateTableContent(data.payments, data.creditResults);
                    } else {
                        throw new Error(data.message || "Unknown error occurred");
                    }
                })
                .catch(error => {
                    paymentsTable.innerHTML = `<tr><td colspan="8">An error occurred while updating the table: ${error.message}</td></tr>`;
                });
        }

        function updateTableContent(payments, creditResults) {
            let tableHTML = `
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Amount (PHP)</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Email</th>
                        <th>Payer Name</th>
                        <th>Ledger Status</th>
                    </tr>
                </thead>
                <tbody>
            `;

            if (payments.length > 0) {
                payments.forEach(payment => {
                    const billing = payment.attributes.billing;
                    const payerEmail = billing.email || 'N/A';
                    const payerName = billing.name || 'N/A';
                    const ledgerStatus = creditResults[payment.id];

                    tableHTML += `
                        <tr>
                            <td>${payment.id}</td>
                            <td>${(payment.attributes.amount / 100).toFixed(2)}</td>
                            <td>${payment.attributes.status.charAt(0).toUpperCase() + payment.attributes.status.slice(1)}</td>
                            <td>${payment.attributes.description}</td>
                            <td>${new Date(payment.attributes.created_at * 1000).toLocaleString()}</td>
                            <td>${payerEmail}</td>
                            <td>${payerName}</td>
                            <td>${getLedgerStatusHTML(ledgerStatus)}</td>
                        </tr>
                    `;
                });
            } else {
                tableHTML += '<tr><td colspan="8">No payments found</td></tr>';
            }

            tableHTML += '</tbody>';
            paymentsTable.innerHTML = tableHTML;
        }

        function getLedgerStatusHTML(ledgerStatus) {
            switch (ledgerStatus.status) {
                case 'processed':
                    return '<span class="text-success">Amount added to ledger</span>';
                case 'already_processed':
                    return '<span class="text-info">Amount already added to ledger</span>';
                case 'not_paid':
                    return '<span class="text-warning">Payment not yet paid</span>';
                default:
                    return `<span class="text-danger">${ledgerStatus.message}</span>`;
            }
        }
    }
    function initializePaymentFormButton() {
        const paymentFormButton = document.getElementById('payment_form');
        if (paymentFormButton) {
            paymentFormButton.addEventListener('click', () => {
                loadContent('payment_form');
            });
        }
    }



    // Load dashboard page by default
    loadContent('dashboard');
});