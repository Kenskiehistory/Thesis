
let lastSeenAnnouncementId = localStorage.getItem('lastSeenAnnouncementId') || 0;

document.addEventListener('DOMContentLoaded', () => {
    const content = document.getElementById('content');
    const sidebar = document.querySelector('.sidebar');
    const menuToggle = document.querySelector('.menu-toggle');
    const miniAnnouncements = document.getElementById('mini-announcements');

    // Add this line to create a notification dot
    const notificationDot = document.createElement('span');
    notificationDot.classList.add('notification-dot');
    document.querySelector('.sidebar li a[data-page="announcements"]').appendChild(notificationDot);

    const loadContent = (page) => {
        if (page === 'profile') {
            loadProfileView();
            hideMiniAnnouncements();
        } else if (page === 'ledger') {
            loadLedgerView();
            hideMiniAnnouncements();
        } else if (page === 'announcements') {
            loadAnnouncementsView();
            hideMiniAnnouncements();
        }
        else {
            fetch(`student_pages/${page}.php`)
                .then(response => response.text())
                .then(html => {
                    content.innerHTML = html;
                    // Call the function to initialize the logout button
                    initializeLogoutButton();
                    attachViewListeners();
                    // Call the function to attach listeners to dynamically load subject details
                    if (page === 'subjects') {
                        fetchAndDisplaySubjects();
                        loadMiniAnnouncements();
                    }
                    else if (page === 'payment') {
                        // Initialize payment form listener after loading the payment page
                        initializePaymentForm();
                        hideMiniAnnouncements();
                    }
                    else {
                        hideMiniAnnouncements();
                    }
                })
                .catch(error => {
                    content.innerHTML = '<h1>Error</h1><p>Sorry, an error occurred while loading the page.</p>';
                    console.error('Error loading content:', error);
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

    // Exclude external links from SPA handling
    document.querySelectorAll('.external_link a').forEach(link => {
        link.addEventListener('click', (event) => {
            // Allow the default action (opening the link) without SPA interference
            console.log('External link clicked: ', event.target.href);
        });
    });

    // Function to initialize payment form submit listener
    const initializePaymentForm = () => {
        const paymentForm = document.getElementById('paymentForm');
        if (paymentForm) {
            paymentForm.addEventListener('submit', function (e) {
                e.preventDefault();

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const paymentUrl = doc.querySelector('meta[name="payment-url"]')?.content;

                        if (paymentUrl) {
                            window.open(paymentUrl, '_blank');
                        } else {
                            document.body.innerHTML = html;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        }
    };

    // Updated loadMiniAnnouncements function
    function loadMiniAnnouncements() {
        fetch('student_pages/view_announcement_student.php')
            .then(response => response.json())
            .then(announcements => {
                let html = '<h5 class="font-weight-bold">Latest Announcements</h5>';
                const latestAnnouncements = announcements.slice(0, 3);

                if (latestAnnouncements.length > 0) {
                    html += '<ul class="list-unstyled">';
                    latestAnnouncements.forEach(announcement => {
                        const isNew = announcement.uniqueId > lastSeenAnnouncementId;
                        const newClass = isNew ? 'new-announcement' : '';
                        html += `<li><a href="#" class="mini-announcement-link font-weight-bold ${newClass}" data-id="${announcement.uniqueId}">${announcement.title}</a></li>`;
                    });
                    html += '</ul>';

                    // Update notification dot
                    updateNotificationDot(announcements[0].uniqueId);
                } else {
                    html += '<p>No recent announcements.</p>';
                }

                miniAnnouncements.innerHTML = html;
                miniAnnouncements.classList.remove('d-none');

                // Add event listeners for mini announcement links
                document.querySelectorAll('.mini-announcement-link').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const announcementId = e.target.getAttribute('data-id');
                        showAnnouncementDetails(announcementId);
                        markAnnouncementAsSeen(announcementId);
                    });
                });
            })
            .catch(error => {
                console.error('Error loading mini announcements:', error);
            });
    }

     // New function to update the notification dot
     function updateNotificationDot(latestAnnouncementId) {
        if (latestAnnouncementId > lastSeenAnnouncementId) {
            notificationDot.style.display = 'inline-block';
        } else {
            notificationDot.style.display = 'none';
        }
    }


    function hideMiniAnnouncements() {
        miniAnnouncements.classList.add('d-none');
        miniAnnouncements.innerHTML = '';
    }
    // New function to mark an announcement as seen
    function markAnnouncementAsSeen(announcementId) {
        lastSeenAnnouncementId = Math.max(lastSeenAnnouncementId, announcementId);
        localStorage.setItem('lastSeenAnnouncementId', lastSeenAnnouncementId);
        updateNotificationDot(lastSeenAnnouncementId);
        document.querySelectorAll('.mini-announcement-link').forEach(link => {
            if (parseInt(link.getAttribute('data-id')) <= lastSeenAnnouncementId) {
                link.classList.remove('new-announcement');
            }
        });
    }

    // Function to load subject details dynamically
    const loadSubjectDetails = (subjectId) => {
        fetch(`student_pages/view_subject_details.php?id=${subjectId}`)
            .then(response => response.text())
            .then(data => {
                content.innerHTML = data; // Load the subject details into the 'content' div
            })
            .catch(error => {
                console.error('Error loading subject details:', error);
                content.innerHTML = '<p>Error loading subject details.</p>';
            });
    };


    // Update loadAnnouncementsView function
    function loadAnnouncementsView() {
        fetch('student_pages/view_announcement_student.php')
            .then(response => response.json())
            .then(announcements => {
                let html = '<div class="card"><div class="card-body">';
                html += '<h4 class="card-title">Announcements</h4>';
                html += '<div class="table-responsive"><table class="table table-striped">';
                html += '<thead><tr><th>Title</th><th>Course</th><th>Date</th></tr></thead>';
                html += '<tbody>';

                if (announcements.length > 0) {
                    announcements.forEach(announcement => {
                        const isNew = announcement.uniqueId > lastSeenAnnouncementId;
                        const newClass = isNew ? 'new-announcement' : '';
                        html += `<tr class="${newClass}">
                            <td><a href="#" class="announcement-link" data-id="${announcement.uniqueId}">${announcement.title}</a></td>
                            <td>${announcement.courseName || 'General'}</td>
                            <td>${announcement.created_at}</td>
                        </tr>`;
                    });
                    hideMiniAnnouncements();

                    // Mark all announcements as seen when viewing the full list
                    markAnnouncementAsSeen(announcements[0].uniqueId);
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
        fetch(`student_pages/view_announcement_details.php?id=${id}`)
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

    // Function to load profile view
function loadProfileView() {
    fetch('student_pages/get_profile_data.php')
        .then(response => response.json())
        .then(data => {
            const profileHTML = `
            <div style="max-width: 900px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);">

                <h1 style="text-align: center; margin-bottom: 20px; font-size: 2.5rem; color: #333; font-weight: bold;">Student Profile</h1>

                <!-- Profile Section -->
                <div style="display: flex; justify-content: space-between; margin-bottom: 40px;">
                    <div style="flex: 1; margin-right: 10px;">
                        <h4 style="margin-bottom: 15px; color: #000000; font-weight: bold;">Personal Information</h4>
                        <p><strong>Name:</strong> ${data.fName} ${data.mName} ${data.lName}</p>
                        <p><strong>Gender:</strong> ${data.gender}</p>
                        <p><strong>Contact Number:</strong> ${data.conNumb}</p>
                        <p><strong>Email:</strong> <a href="mailto:${data.email_stud}" style="color: #007bff;">${data.email_stud}</a></p>
                    </div>
                    <div style="flex: 1; margin-left: 10px;">
                        <p><strong>Parent's Name:</strong> ${data.pName}</p>
                        <p><strong>Birthdate:</strong> ${data.bDate}</p>
                        <p><strong>Address:</strong> ${data.address_stud}</p>
                        <p><strong>Facebook:</strong> <a href="https://facebook.com/${data.facebook}" target="_blank" style="color: #007bff;">${data.facebook}</a></p>
                    </div>
                </div>

                <!-- Course Information Section -->
                <div style="border-top: 1px solid #ddd; padding-top: 20px;">
                    <h4 style="margin-bottom: 15px; color: #000000; font-weight: bold;">Course Information</h4>
                    <p><strong>Course:</strong> ${data.courseName}</p>
                    <p><strong>Account Status:</strong> ${data.account_status == 0 ? 'Active' : 'Inactive'}</p>

                    <!-- Architecture Specific Information -->
                    ${data.courseName === 'Architecture' ? `
                        <h5 style="margin-top: 20px; color: #000000; font-weight: bold;">Architecture Specific Information</h5>
                        <p><strong>Review:</strong> ${data.courseReview}</p>
                        <p><strong>Section:</strong> ${data.courseSection}</p>
                        <p><strong>Status:</strong> ${data.courseStatus}</p>
                        <p><strong>School Graduated:</strong> ${data.school_grad}</p>
                        <p><strong>Employment Status:</strong> ${data.employ_status_arki}</p>
                        <p><strong>Additional Info:</strong> ${data.additional_info_arki}</p>
                    ` : data.courseName === 'Civil Engineering' ? `
                        <h5 style="margin-top: 20px; color: #000000; font-weight: bold;">Civil Engineering Specific Information</h5>
                        <p><strong>Review:</strong> ${data.courseReview}</p>
                        <p><strong>Section:</strong> ${data.courseSection}</p>
                        <p><strong>Status:</strong> ${data.courseStatus}</p>
                        <p><strong>School Graduated:</strong> ${data.school_grad}</p>
                    ` : data.courseName === 'Mechanical Engineering' ? `
                    <h5 style="margin-top: 20px; color: #000000; font-weight: bold;">Mechanical Engineering Specific Information</h5>
                    <p><strong>Review:</strong> ${data.courseReview}</p>
                    <p><strong>Section:</strong> ${data.courseSection}</p>
                    <p><strong>Status:</strong> ${data.courseStatus}</p>
                    <p><strong>School Graduated:</strong> ${data.school_grad}</p>
                    ` : ''}
                </div>

            </div>
        `;
            content.innerHTML = profileHTML;
        })
        .catch(error => {
            console.error('Error loading profile data:', error);
            content.innerHTML = '<p class="text-danger">Error loading profile data.</p>';
        });
}


function loadLedgerView() {
    fetch('student_pages/get_ledger_data.php')
        .then(response => response.json())
        .then(data => {
            let ledgerHTML = `
            <div style="max-width: 900px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);">
                <h1 style="text-align: center; margin-bottom: 20px; font-size: 2.5rem; color: #333;">Student Ledger</h1>
                
                <!-- Ledger Table -->
                <div style="overflow-x: auto; margin-bottom: 20px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 1rem;">
                        <thead style="background-color: #007bff; color: #fff;">
                            <tr>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Date</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Particulars</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">OR</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Debit</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Credit</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.forEach(entry => {
                // Check if "particulars" and "original_receipt" are null
                const particulars = entry.particulars !== null ? entry.particulars : '';
                const original_receipt = entry.original_receipt !== null ? entry.original_receipt : '';

                // Only render the rows if "particulars" and "OR" are not null
                ledgerHTML += `
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">${entry.date}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">${particulars}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">${original_receipt}</td>    
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">${entry.debit}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">${entry.credit}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">${entry.balance}</td>
                    </tr>
                `;
            });

            ledgerHTML += `
                        </tbody>
                    </table>
                </div>
            </div>
        `;

            content.innerHTML = ledgerHTML;
        })
        .catch(error => {
            console.error('Error loading ledger data:', error);
            content.innerHTML = '<p class="text-danger">Error loading ledger data.</p>';
        });
}


    // Function to initialize the logout button
    const initializeLogoutButton = () => {
        const logoutButton = document.getElementById('logoutButton');
        if (logoutButton) {
            logoutButton.addEventListener('click', function (e) {
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
    };
    // Attach event listeners to "View" buttons for dynamically loading subject details
    const attachViewListeners = () => {
        document.querySelectorAll('.btn-info').forEach(viewButton => {
            viewButton.addEventListener('click', (event) => {
                event.preventDefault();
                const subjectId = viewButton.getAttribute('href').split('=')[1]; // Get the subject ID
                loadSubjectDetails(subjectId); // Load the subject details dynamically
            });
        });
    };

    // Function to fetch and display the subjects page
    const fetchAndDisplaySubjects = () => {
        fetch('student_pages/subjects.php')
            .then(response => response.text())
            .then(data => {
                content.innerHTML = data;
                attachViewListeners(); // Attach listeners to dynamically load subject details
            })
            .catch(error => {
                content.innerHTML = '<h1>Error</h1><p>Sorry, an error occurred while loading subjects.</p>';
                console.error('Error loading subjects:', error);
            });
    };

    // Toggle sidebar on mobile
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
                event.preventDefault();
                const page = link.getAttribute('data-page') || link.textContent.trim().toLowerCase().replace(' ', '_');
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
            const page = link.getAttribute('data-page') || link.textContent.trim().toLowerCase().replace(' ', '_');
            loadContent(page);
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
            }
        });
    });

    // Event listener for the "Create Payment" button
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-create-payment')) {
            event.preventDefault();
            loadContent('payment');
        }
    });


    // Load subjects page by default
    loadContent('subjects');
});
