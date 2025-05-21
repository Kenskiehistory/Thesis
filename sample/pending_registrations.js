function initializePendingPage() {
    fetchWaitlistData();
    setupEventListeners();
}

function fetchWaitlistData() {
    fetch('api/get_waitlist.php')
        .then(response => response.json())
        .then(data => {
            renderWaitlistTable(data);
            initProfileViewButtons();
        })
        .catch(error => {
            console.error('Error fetching waitlist data:', error);
            showErrorMessage('Failed to load waitlist data.');
        });
}

function renderWaitlistTable(data) {
    const tableBody = document.querySelector('#waitlist-table tbody');
    tableBody.innerHTML = '';

    data.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${escapeHtml(row.student_name)}</td>
            <td>${escapeHtml(row.courseName)}</td>
            <td>${escapeHtml(row.payment_status)}</td>
            <td>${escapeHtml(row.registration_date)}</td>
            <td>
                ${row.payment_status === 'Pending' ?
                    `<button class="btn btn-sm btn-success mark-paid" 
                        data-waitlist-id="${row.waitlist_id}" 
                        data-user-profile-id="${row.user_profile_id}">
                        Mark as Paid & Create Account
                    </button>` :
                    '<button class="btn btn-sm btn-secondary" disabled>Already Paid</button>'
                }
                ${row.receipt_path ?
                    `<button class="btn btn-sm btn-info view-receipt" 
                        data-receipt-path="${row.receipt_path}">
                        View Receipt
                    </button>` :
                    ''
                }
                <button class="btn btn-sm btn-primary view-profile" 
                    data-student-id="${row.user_profile_id}">
                    View Profile
                </button>
            </td>
        `;
        tableBody.appendChild(tr);
    });
}

function setupEventListeners() {
    document.getElementById('waitlist-table').addEventListener('click', event => {
        if (event.target.classList.contains('mark-paid')) {
            const waitlistId = event.target.dataset.waitlistId;
            const userProfileId = event.target.dataset.userProfileId;
            markAsPaidAndCreateAccount(event.target, waitlistId, userProfileId);
        } else if (event.target.classList.contains('view-receipt')) {
            const receiptPath = event.target.dataset.receiptPath;
            viewReceipt(receiptPath);
        }
    });
}

function markAsPaidAndCreateAccount(button, waitlistId, userProfileId) {
    if (confirm('Are you sure you want to mark as paid, create an account, and send login details via email?')) {
        // Disable button and show loading state
        button.disabled = true;
        const originalText = button.textContent;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

        const formData = new FormData();
        formData.append('waitlist_id', waitlistId);
        formData.append('user_profile_id', userProfileId);
        formData.append('send_email', 'true');

        fetch('create_student_account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message);
                fetchWaitlistData(); // Refresh the table
            } else {
                showErrorMessage(data.message);
                // Reset button state
                button.disabled = false;
                button.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('An error occurred while processing your request.');
            // Reset button state
            button.disabled = false;
            button.textContent = originalText;
        });
    }
}

function viewReceipt(receiptPath) {
    // Open the receipt in a new tab or window
    window.open(receiptPath, '_blank');
}

function initProfileViewButtons() {
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
                    $('#profileViewModal .modal-dialog').addClass('modal-fullscreen');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
}

function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success';
    alertDiv.textContent = message;
    document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.table-responsive'));
    setTimeout(() => alertDiv.remove(), 5000);
}

function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger';
    alertDiv.textContent = message;
    document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.table-responsive'));
    setTimeout(() => alertDiv.remove(), 5000);
}

function escapeHtml(unsafe) {
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}
