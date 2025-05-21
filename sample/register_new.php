<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACE+ Review Center - Online Pre-Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8 bg-white p-10 rounded-xl shadow-md">
            <div>
                 <!-- Back Button -->
                 <button type="button" onclick="history.back()" class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <
                    </button>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    ACE+ Review Center
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Online Pre-Enrollment
                </p>
                <p class="text-center text-sm text-gray-500">
                    Returning student? <a href="returnee_registration.php" class="font-medium text-indigo-600 hover:text-indigo-500">Click here</a>
                </p>
            </div>
            <form id="regForm" class="mt-8 space-y-6" action="submit_registration.php" method="post" enctype="multipart/form-data">
                <!-- Step 1 -->
                <div class="step active space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="courseName" class="block text-sm font-medium text-gray-700">Course Name</label>
                            <select id="courseName" name="courseName" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Click to Select Courses</option>
                                <option value="Architecture">Architecture</option>
                                <option value="Civil Engineering">Civil Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                                <option value="Master Plumber">Master Plumber</option>
                            </select>
                        </div>
                        <div>
                            <label for="courseReview" class="block text-sm font-medium text-gray-700">Course Package</label>
                            <select id="courseReview" name="courseReview" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </select>
                        </div>
                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                            <select id="section" name="courseSection" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </select>
                        </div>
                        <div>
                            <label for="courseStatus" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="courseStatus" name="courseStatus" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="school_grad" class="block text-sm font-medium text-gray-700">School Graduated</label>
                        <input type="text" id="school_grad" name="school_grad" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <!-- Architecture specific fields -->
                    <div id="architectureDiv" class="hidden space-y-6">
                        <div>
                            <label for="employ_status_arki" class="block text-sm font-medium text-gray-700">Employment Status</label>
                            <select id="employ_status_arki" name="employ_status_arki" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Click to Select</option>
                                <option value="Employed">Employed</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Business Owner">Business Owner</option>
                                <option value="Unemployed">Unemployed</option>
                                <option value="Apprenticeship/Intern">Apprenticeship/Intern</option>
                            </select>
                        </div>
                        <div id="company_input" class="hidden">
                            <label for="additional_info_arki" class="block text-sm font-medium text-gray-700">If Employed, what Company</label>
                            <input type="text" id="additional_info_arki" name="additional_info_arki" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <!-- Plumber specific fields -->
                    <div id="plumber" class="hidden space-y-6">
                        <div>
                            <label for="year_graduated" class="block text-sm font-medium text-gray-700">Year Graduated</label>
                            <input type="text" id="year_graduated" name="year_graduated" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="other_prc_licenses" class="block text-sm font-medium text-gray-700">Other PRC Licenses</label>
                            <input type="text" id="other_prc_licenses" name="other_prc_licenses" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div>
                        <label for="facebook" class="block text-sm font-medium text-gray-700">Facebook</label>
                        <input type="text" id="facebook" name="facebook" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="step space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="fName" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="fName" name="fName" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="mName" class="block text-sm font-medium text-gray-700">Middle Name</label>
                            <input type="text" id="mName" name="mName" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="lName" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="lName" name="lName" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="conNumb" class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="text" id="conNumb" name="conNumb" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Sex</label>
                            <select id="gender" name="gender" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label for="bDate" class="block text-sm font-medium text-gray-700">Birth Date</label>
                            <input type="date" id="bDate" name="bDate" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div>
                        <label for="address_stud" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" id="address_stud" name="address_stud" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="email_stud" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email_stud" name="email_stud" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        <span id="email_validation_result" class="text-sm"></span>
                    </div>
                    <div>
                        <label for="pName" class="block text-sm font-medium text-gray-700">Contact Person</label>
                        <input type="text" id="pName" name="pName" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                <!-- Step 3 (Review) -->
                <div class="step space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">Review Your Information</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2" id="reviewContent">
                        <!-- Review fields will be populated dynamically -->
                    </div>
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Registration
                        </button>
                    </div>
                </div>
                <div class="flex justify-between mt-4">
                    <button type="button" id="prevBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Previous
                    </button>
                    <button type="button" id="nextBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Next
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentStep = 0;
            let emailValid = false;

            function showStep(step) {
                $('.step').removeClass('active');
                $('.step:eq(' + step + ')').addClass('active');

                if (step === 0) {
                    $('#prevBtn').hide();
                } else {
                    $('#prevBtn').show();
                }

                if (step === $('.step').length - 1) {
                    $('#nextBtn').hide();
                    $('button[type="submit"]').show();
                } else {
                    $('#nextBtn').show();
                    $('button[type="submit"]').hide();
                }

                // Remove this line to prevent disabling the next button
                // updateNextButtonState();
            }

            function nextPrev(n) {
                console.log('nextPrev called with:', n); // Debugging
                if (n === 1 && !validateStep(currentStep)) {
                    console.log('Validation failed for step:', currentStep); // Debugging
                    return false;
                }

                currentStep += n;
                console.log('New currentStep:', currentStep); // Debugging

                if (currentStep >= $('.step').length) {
                    $('#regForm').submit();
                    return false;
                }

                showStep(currentStep);

                if (currentStep === $('.step').length - 1) {
                    displayReview();
                }
            }

            function validateStep(step) {
                let valid = true;
                $('.step:eq(' + step + ') input[required], .step:eq(' + step + ') select[required]').each(function() {
                    if ($(this).val() === '') {
                        $(this).addClass('border-red-500');
                        valid = false;
                    } else {
                        $(this).removeClass('border-red-500');
                    }
                });

                if (step === 1 && !emailValid) {
                    $('#email_stud').addClass('border-red-500');
                    valid = false;
                }

                console.log('Step', step, 'validation result:', valid); // Debugging
                return valid;
            }

            // Remove this function as we're not using it anymore
            // function updateNextButtonState() {
            //     let stepValid = validateStep(currentStep);
            //     $('#nextBtn').prop('disabled', !stepValid);
            // }

            $('#nextBtn').click(function() {
                console.log('Next button clicked'); // Debugging
                nextPrev(1);
            });

            $('#prevBtn').click(function() {
                console.log('Previous button clicked'); // Debugging
                nextPrev(-1);
            });

            // Course name change event
            $('#courseName').change(function() {
                let selectedCourse = $(this).val();

                // AJAX call to fetch course reviews
                $.ajax({
                    type: 'POST',
                    url: 'fetch_reviews.php',
                    data: {
                        courseName: selectedCourse
                    },
                    success: function(response) {
                        $('#courseReview').html(response);
                        $('#courseReview').trigger('change');
                    }
                });

                // Show/hide architecture div
                if (selectedCourse === 'Architecture') {
                    $('#architectureDiv').removeClass('hidden');
                } else {
                    $('#architectureDiv').addClass('hidden');
                }

                // Show/hide plumber div
                if (selectedCourse === 'Master Plumber') {
                    $('#plumber').removeClass('hidden');
                } else {
                    $('#plumber').addClass('hidden');
                }

                // AJAX call to fetch course status
                $.ajax({
                    type: 'POST',
                    url: 'fetch_status.php',
                    data: {
                        courseStatus: selectedCourse
                    },
                    success: function(response) {
                        $('#courseStatus').html(response);
                    }
                });
            });

            // Course review change event
            $('#courseReview').change(function() {
                let selectedCourseReview = $(this).val();

                // AJAX call to fetch sections
                $.ajax({
                    type: 'POST',
                    url: 'fetch_sections.php',
                    data: {
                        courseReview: selectedCourseReview
                    },
                    success: function(response) {
                        $('#section').html(response);
                    },
                    error: function() {
                        alert('Error fetching sections.');
                    }
                });
            });

            // Section change event
            $('#section').change(function() {
                let selectedSection = $(this).val();
                let studentId = $('#studentId').val(); // Make sure this element exists

                // AJAX call to update section count
                $.ajax({
                    type: 'POST',
                    url: 'update_section_count.php',
                    data: {
                        sectionId: selectedSection,
                        studentId: studentId
                    },
                    success: function(response) {
                        alert(response);
                    },
                    error: function() {
                        alert('Error updating section count.');
                    }
                });
            });

            // Email validation
            $('#email_stud').blur(function() {
                let email = $(this).val();

                // AJAX call to validate email
                $.ajax({
                    type: 'POST',
                    url: 'validate_email.php',
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.trim() === 'ok') {
                            $('#email_validation_result').html('<span class="text-green-500">Valid email</span>');
                            emailValid = true;
                        } else {
                            $('#email_validation_result').html('<span class="text-red-500">Invalid email</span>');
                            emailValid = false;
                        }
                        //updateNextButtonState();
                    },
                    error: function() {
                        $('#email_validation_result').html('<span class="text-red-500">Error validating email</span>');
                        emailValid = false;
                        //updateNextButtonState();
                    }
                });
            });

            // Employment status change event
            $('#employ_status_arki').change(function() {
                if ($(this).val() === 'Employed') {
                    $('#company_input').removeClass('hidden');
                } else {
                    $('#company_input').addClass('hidden');
                    $('#additional_info_arki').val('');
                }
            });

            function displayReview() {
                let reviewHtml = '';
                let fieldMappings = {
                    'courseName': 'Course Name',
                    'courseReview': 'Course Package', // This will be dynamically populated below
                    'courseStatus': 'Status',
                    'school_grad': 'School Graduated',
                    'facebook': 'Facebook',
                    'fName': 'First Name',
                    'mName': 'Middle Name',
                    'lName': 'Last Name',
                    'conNumb': 'Contact Number',
                    'gender': 'Gender',
                    'bDate': 'Birth Date',
                    'address_stud': 'Address',
                    'email_stud': 'Email',
                    'pName': 'Contact Person'
                };

                $('input, select').each(function() {
                    let fieldName = $(this).attr('name');
                    let fieldValue = $(this).val();

                    // Skip the courseReview for now, we will populate it using AJAX below
                    if (fieldName !== 'courseReview' && fieldValue && fieldMappings[fieldName]) {
                        reviewHtml += `
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">${fieldMappings[fieldName]}</dt>
                    <dd class="mt-1 text-sm text-gray-900">${fieldValue}</dd>
                </div>
            `;
                    }
                });

                // Fetch the selected course review details using AJAX
                let selectedReviewId = $('#courseReview').val();
                if (selectedReviewId) {
                    $.ajax({
                        type: 'POST',
                        url: 'fetch_review_details.php',
                        data: {
                            reviewId: selectedReviewId
                        },
                        success: function(response) {
                            let reviewDetails = JSON.parse(response);
                            reviewHtml += `
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
                        <dt class="text-sm font-medium text-gray-500">Course Package</dt>
                        <dd class="mt-1 text-sm text-gray-900">${reviewDetails.reviews} TOTAL FEE PHP. ${reviewDetails.tuition_fee}</dd>
                    </div>
                `;
                            $('#reviewContent').html(reviewHtml);
                        }
                    });
                } else {
                    $('#reviewContent').html(reviewHtml);
                }
            }

            // Initial setup
            showStep(currentStep);
        });
    </script>
</body>

</html>