function initializeAnnouncementForm() {
    const form = document.querySelector('#announcementForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitAnnouncement();
        });
    }
}

function submitAnnouncement() {
    const form = document.querySelector('#announcementForm');
    const formData = new FormData(form);

    fetch('api/submit_announcement.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Announcement posted successfully!');
            form.reset();
        } else {
            alert('Error posting announcement: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while posting the announcement.');
    });
}
function initializeAnnouncementView() {
    waitForElement('#announcementTableBody', loadAnnouncements, 100, 5000); // 100ms interval, 5 seconds timeout
    document.getElementById('courseFilter').addEventListener('change', loadAnnouncements);
}

function waitForElement(selector, callback, interval, timeout) {
    const startTime = new Date().getTime();
    
    (function checkElement() {
        const element = document.querySelector(selector);
        
        if (element) {
            callback();  // Call the function when the element is found
        } else if (new Date().getTime() - startTime < timeout) {
            setTimeout(checkElement, interval);  // Retry after the specified interval
        } else {
            console.error(`Error: Element ${selector} not found within ${timeout / 1000} seconds.`);
        }
    })();
}


function loadAnnouncements() {
    const selectedCourse = document.getElementById('courseFilter').value;

    // Retry mechanism in case tableBody is not yet available
    const tableBody = document.getElementById('announcementTableBody');
    if (!tableBody) {
        console.error('Error: tableBody element not found. Retrying in 100ms.');
        setTimeout(loadAnnouncements, 100); // Retry after 100ms
        return;
    }

    fetch(`api/get_announcements.php?course=${encodeURIComponent(selectedCourse)}`)
        .then(response => response.json())
        .then(data => {
            updateAnnouncementTable(data.announcements);
            updateCourseFilter(data.courses);
        })
        .catch(error => console.error('Error loading announcements:', error));
}


function updateAnnouncementTable(announcements) {
    console.log('updateAnnouncementTable called');
    const tableBody = document.getElementById('announcementTableBody');
    console.log('tableBody:', tableBody);

    if (!tableBody) {
        console.error('Error: tableBody element not found');
        return;
    }

    tableBody.innerHTML = '';

    if (announcements.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5">No announcements found.</td></tr>';
        return;
    }

    announcements.forEach(announcement => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><a href="#" onclick="viewAnnouncementDetails(${announcement.id})">${escapeHtml(announcement.title)}</a></td>
            <td>${escapeHtml(announcement.courseName || 'General')}</td>
            <td>${escapeHtml(announcement.created_at)}</td>
            <td>${escapeHtml(announcement.created_by)}</td>
            <td>
                <button onclick="editAnnouncement(${announcement.id})" class="btn btn-sm btn-info">Edit</button>
                <button onclick="deleteAnnouncement(${announcement.id})" class="btn btn-sm btn-danger">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function updateCourseFilter(courses) {
    const courseFilter = document.getElementById('courseFilter');
    const currentValue = courseFilter.value;
    courseFilter.innerHTML = '<option value="">All Courses</option>';
    courses.forEach(course => {
        const option = document.createElement('option');
        option.value = course.courseName;
        option.textContent = course.courseName;
        courseFilter.appendChild(option);
    });
    courseFilter.value = currentValue;
}

function viewAnnouncementDetails(id) {
    fetch(`api/get_announcement_details.php?id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(announcement => {
            if (announcement.error) {
                throw new Error(announcement.error);
            }
            document.getElementById('content').innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">${escapeHtml(announcement.title)}</h4>
                        <p><strong>Course:</strong> ${escapeHtml(announcement.courseName || 'General')}</p>
                        <p><strong>Date:</strong> ${escapeHtml(announcement.created_at)}</p>
                        <p><strong>Author:</strong> ${escapeHtml(announcement.created_by)}</p>
                        <hr>
                        <p>${escapeHtml(announcement.content).replace(/\n/g, '<br>')}</p>
                        <button onclick="loadAnnouncements()" class="btn btn-primary">Back to Announcements</button>
                        <button onclick="editAnnouncement(${announcement.id})" class="btn btn-info">Edit Announcement</button>
                        <button onclick="deleteAnnouncement(${announcement.id})" class="btn btn-danger">Delete Announcement</button>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error loading announcement details:', error);
            document.getElementById('content').innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Error</h4>
                        <p>An error occurred while loading the announcement details:</p>
                        <pre>${escapeHtml(error.toString())}</pre>
                        <button onclick="loadAnnouncements()" class="btn btn-primary">Back to Announcements</button>
                    </div>
                </div>
            `;
        });
}

function editAnnouncement(id) {
    // Implement edit functionality
    console.log('Edit announcement:', id);
}

function deleteAnnouncement(id) {
    if (confirm('Are you sure you want to delete this announcement?')) {
        // Implement delete functionality
        console.log('Delete announcement:', id);
    }
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
