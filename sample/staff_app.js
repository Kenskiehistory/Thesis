document.addEventListener('DOMContentLoaded', () => {
    const content = document.getElementById('content');
    const sidebar = document.querySelector('.sidebar');
    const menuToggle = document.querySelector('.menu-toggle');

    const loadContent = (page) => {
        fetch(`staff_pages/${page}.php`)
            .then(response => response.text())
            .then(data => {
                content.innerHTML = data;
                if (page === 'subjects') {
                    attachViewListeners();
                }
                if (page === 'post_announcement') {
                    initializeAnnouncementForm();
                }
                if (page === 'view_announcements') {
                    loadAnnouncementsView();
                }
                if (page === 'answer_keys') {
                    initializeAnswerKeyForm();
                }
                initializeLogoutButton(); 

            })
            .catch(error => {
                content.innerHTML = '<h1>Error</h1><p>Sorry, an error occurred while loading the page.</p>';
                console.error('Error loading content:', error);
            });
    };

    const attachViewListeners = () => {
        const viewButtons = document.querySelectorAll('.btn-info');
        viewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const subjectId = e.target.getAttribute('data-subject-id');
                loadSubjectDetails(subjectId);
            });
        });
    };

    const loadSubjectDetails = (subjectId) => {
        fetch(`staff_pages/view_subject_details.php?id=${subjectId}`)
            .then(response => response.text())
            .then(data => {
                content.innerHTML = data;
                attachContentListeners();
            })
            .catch(error => {
                content.innerHTML = '<h1>Error</h1><p>Sorry, an error occurred while loading subject details.</p>';
                console.error('Error loading subject details:', error);
            });
    };

    const attachContentListeners = () => {
        const addContentButton = document.getElementById('addContentBtn');
        if (addContentButton) {
            addContentButton.addEventListener('click', (e) => {
                const subjectId = e.target.getAttribute('data-subject-id');
                loadAddContentForm(subjectId);
            });
        }

        const editButtons = document.querySelectorAll('.editContentBtn');
        editButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const contentId = e.target.getAttribute('data-content-id');
                loadEditContentForm(contentId);
            });
        });

        const deleteButtons = document.querySelectorAll('.deleteContentBtn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                if (confirm('Are you sure you want to delete this content?')) {
                    const contentId = e.target.getAttribute('data-content-id');
                    deleteContent(contentId);
                }
            });
        });
    };

    const loadAddContentForm = (subjectId) => {
        fetch(`staff_pages/add_content.php?subject_id=${subjectId}`)
            .then(response => response.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.html) {
                        content.innerHTML = data.html;
                        attachAddContentListeners(subjectId);
                    } else {
                        content.innerHTML = '<h1>Error</h1><p>' + (data.message || 'An error occurred') + '</p>';
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.log('Server response:', text);
                    content.innerHTML = '<h1>Error</h1><p>An error occurred while processing the server response.</p>';
                }
            })
            .catch(error => {
                console.error('Error loading add content form:', error);
                content.innerHTML = '<h1>Error</h1><p>Sorry, an error occurred while loading the add content form.</p>';
            });
    };
    const attachAddContentListeners = (subjectId) => {
        const addContentForm = document.getElementById('addContentForm');
        const backToSubjectBtn = document.getElementById('backToSubject');

        if (addContentForm) {
            addContentForm.addEventListener('submit', (e) => {
                e.preventDefault();
                submitAddContentForm(subjectId);
            });
        }

        if (backToSubjectBtn) {
            backToSubjectBtn.addEventListener('click', () => {
                loadSubjectDetails(subjectId);
            });
        }
    };

    const submitAddContentForm = (subjectId) => {
        const form = document.getElementById('addContentForm');
        const formData = new FormData(form);

        fetch('staff_pages/add_content.php?subject_id=' + subjectId, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    alert(data.message);
                    loadSubjectDetails(subjectId);
                } else {
                    alert('Error: ' + (data.message || 'An error occurred'));
                }
            } catch (error) {
                console.error('Error parsing JSON:', error);
                console.log('Server response:', text);
                alert('An error occurred while processing the server response.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the form.');
        });
    };

    const loadEditContentForm = (contentId) => {
        fetch(`staff_pages/edit_content.php?id=${contentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    content.innerHTML = data.html;
                    attachEditContentListeners();
                } else {
                    content.innerHTML = '<h1>Error</h1><p>' + data.message + '</p>';
                }
            })
            .catch(error => {
                content.innerHTML = '<h1>Error</h1><p>Sorry, an error occurred while loading the edit content form.</p>';
                console.error('Error loading edit content form:', error);
            });
    };
    const attachEditContentListeners = () => {
        const editContentForm = document.getElementById('editContentForm');
        const backToSubjectBtn = document.getElementById('backToSubject');

        if (editContentForm) {
            editContentForm.addEventListener('submit', (e) => {
                e.preventDefault();
                submitEditContentForm();
            });
        }

        if (backToSubjectBtn) {
            backToSubjectBtn.addEventListener('click', () => {
                const subjectId = backToSubjectBtn.getAttribute('data-subject-id');
                loadSubjectDetails(subjectId);
            });
        }
    };
    const submitEditContentForm = () => {
        const form = document.getElementById('editContentForm');
        const formData = new FormData(form);
        const contentId = formData.get('content_id');

        fetch('staff_pages/edit_content.php?id=' + contentId, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                loadSubjectDetails(data.subject_id);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the form.');
        });
    };


    const deleteContent = (contentId) => {
        fetch(`staff_pages/delete_content.php?id=${contentId}`, { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the subject details page
                    loadSubjectDetails(data.subject_id);
                } else {
                    alert('Error deleting content: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting content:', error);
                alert('An error occurred while deleting the content.');
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

    function loadAnnouncementsView() {
        fetch('staff_pages/view_announcements.php')
            .then(response => response.json())
            .then(announcements => {
                let html = '<div class="card"><div class="card-body">';
                html += '<h4 class="card-title">Announcements</h4>';
                html += '<div class="table-responsive"><table class="table table-striped">';
                html += '<thead><tr><th>Title</th><th>Course</th><th>Date</th></tr></thead>';
                html += '<tbody>';

                content.innerHTML = html;
                 // Scroll to the top of the page
            window.scrollTo(0, 0);
                
                if (announcements.length > 0) {
                    announcements.forEach(announcement => {
                        html += `<tr>
                            <td><a href="#" class="announcement-link" data-id="${announcement.id}">${announcement.title}</a></td>
                            <td>${announcement.courseName || 'General'}</td>
                            <td>${announcement.created_at}</td>
                        </tr>`;
                    });
                } else {
                    html += '<tr><td colspan="3">No announcements found for your course.</td></tr>';
                }
                
                html += '</tbody></table></div></div></div>';
                content.innerHTML = html;
                
                // Add event listeners for announcement links
                document.querySelectorAll('.announcement-link').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const announcementId = e.target.getAttribute('data-id');
                        showAnnouncementDetails(announcementId);
                    });
                });
            })
            .catch(error => {
                content.innerHTML = '<h1>Error</h1><p>Sorry, an error occurred while loading announcements.</p>';
                console.error('Error loading announcements:', error);
            });
    }
    
    function showAnnouncementDetails(id) {
        fetch(`staff_pages/view_announcement_details.php?id=${id}`)
            .then(response => response.json())
            .then(announcement => {
                if (announcement.error) {
                    content.innerHTML = `<h1>Error</h1><p>${announcement.error}</p>`;
                } else {
                    let html = `<div class="card">
                        <div class="card-body">
                            <h4 class="card-title">${announcement.title}</h4>
                            <p><strong>Course:</strong> ${announcement.courseName || 'General'}</p>
                            <p><strong>Date:</strong> ${announcement.created_at}</p>
                            <p><strong>Author:</strong> ${announcement.created_by}</p>
                            <hr>
                            <div class="announcement-content">${announcement.content}</div>
                            <button class="btn btn-primary mt-3" id="backToAnnouncements">Back to Announcements</button>
                        </div>
                    </div>`;
                    content.innerHTML = html;
                    
                    // Add event listener to the "Back to Announcements" button
                    document.getElementById('backToAnnouncements').addEventListener('click', loadAnnouncementsView);
                }
            })
            .catch(error => {
                content.innerHTML = '<h1>Error</h1><p>Sorry, an error occurred while loading the announcement details.</p>';
                console.error('Error loading announcement details:', error);
            });
    }

    // Initialize the logout button
function initializeLogoutButton() {
    const logoutButton = document.getElementById('logoutButton');
    if (logoutButton) {
        logoutButton.addEventListener('click', function(e) {
            e.preventDefault();

            // Send a request to logout and redirect to index.php
            fetch('api/logout.php', {
                method: 'POST'
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = 'index.php'; // Redirect after logout
                } else {
                    console.error('Logout failed');
                }
            })
            .catch(error => console.error('Error during logout:', error));
        });
    }
}
const initializeAnswerKeyForm = () => {
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
    };
    
    // Load dashboard page by default
    loadContent('dashboard');


});