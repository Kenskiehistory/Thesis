<?php
include('validate_email.php')
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ACE+ Review Center</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="stylesheet" href="register.css">
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <H6>ACE+ Review Center</H6>
              </div>
              <h4>Online Pre-Enrollment</h4>
              <p>Returning student? <a href="returnee_registration.php">Click here</a></p>
              <form id="regForm" class="pt-3" action="submit_registration.php" method="post" enctype="multipart/form-data">
                <!-- Step 1 -->
                <div class="step active">
                  <div class="form-group">
                    <label for="courseName">Course Name</label>
                    <select class="form-control" id="courseName" name="courseName">
                      <option value="">Click to Select Courses</option>
                      <option value="Architecture">Architecture</option>
                      <option value="Civil Engineering">Civil Engineering</option>
                      <option value="Mechanical Engineering">Mechanical Engineer</option>
                      <option value="Master Plumber">Master Plumber</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="courseReview">Course Package</label>
                    <select id="courseReview" name="courseReview" class="form-control">
                      <!-- Default option will be populated by AJAX -->
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="section">Section</label>
                    <select id="section" name="courseSection" class="form-control">
                      <!-- Default option will be populated by AJAX -->
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="courseStatus">Status</label>
                    <select id="courseStatus" name="courseStatus" class="form-control">
                      <!-- Default option will be populated by AJAX -->
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="school_grad">School Graduated</label>
                    <input type="text" class="form-control" id="school_grad" name="school_grad" placeholder="School Graduated" required>
                  </div>

                  <!--Div for Architecture -->
                  <div id="architectureDiv">
                    <div class="form-group">
                      <label for="employ_status_arki">Employment Status</label>
                      <select class="form-control" id="employ_status_arki" name="employ_status_arki">
                        <option value="">Click to Select</option>
                        <option value="Employed">Employed</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Business Owner">Business Owner</option>
                        <option value="Unemployed">Unemployed</option>
                        <option value="Apprenticeship/Intern">Apprenticeship/Intern</option>
                      </select>
                    </div>

                    <div id="company_input" style="display: none;">
                      <div class="form-group">
                        <label for="additional_info_arki">If Employed what Company</label>
                        <input type="text" class="form-control" id="additional_info_arki" name="additional_info_arki" placeholder="Company">
                      </div>
                    </div>
                  </div>
                  <!--End of Architecture -->

                  <!--Div for plumber -->
                  <div id="plumber">
                    <div class="form-group">
                      <label for="year_graduated">Year Graduated</label>
                      <input type="text" class="form-control" id="year_graduated" name="year_graduated" placeholder="Year Graduated">
                    </div>

                    <div class="form-group">
                      <label for="other_prc_licenses">Other PRC Licenses</label>
                      <input type="text" class="form-control" id="other_prc_licenses" name="other_prc_licenses" placeholder="Other PRC Licenses">
                    </div>
                  </div>
                  <!--End of plumber -->

                  <div class="form-group">
                    <label for="facebook">Facebook</label>
                    <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook">
                  </div>
                </div>
                <!-- Step 2 -->
                <div class="form-group">
                  <div class="step">
                    <label for="contactnumber">Personal Information</label>
                    <div class="form-group">
                      <label for="fName">First Name</label>
                      <input type="text" class="form-control" id="fName" name="fName" placeholder="First Name">
                    </div>

                    <div class="form-group">
                      <label for="mName">Middle Name</label>
                      <input type="text" class="form-control" id="mName" name="mName" placeholder="Middle Name">
                    </div>

                    <div class="form-group">
                      <label for="lName">Last Name</label>
                      <input type="text" class="form-control" id="lName" name="lName" placeholder="Last Name">
                    </div>

                    <div class="form-group">
                      <label for="cNumb">Contact Number</label>
                      <input type="text" class="form-control" id="conNumb" name="conNumb" placeholder="Contact Number">
                    </div>

                    <div class="form-group">
                      <label for="gender">Sex</label>
                      <select class="form-select" id="gender" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="bod">Birth Date</label>
                      <input type="date" class="form-control" id="bDate" name="bDate">
                    </div>

                    <div class="form-group">
                      <label for="address_stud">Address</label>
                      <input type="text" class="form-control" id="address_stud" name="address_stud" placeholder="Address">
                    </div>

                    <div class="form-group">
                      <label for="email_stud">Email</label>
                      <input type="email" class="form-control" id="email_stud" name="email_stud" required>
                      <span id="email_validation_result"></span>
                    </div>

                    <div class="form-group">
                      <label for="Pname">Contact Person</label>
                      <input type="text" class="form-control" id="pName" name="pName" placeholder="Parent Name">
                    </div>
                  </div>
                </div>
                <!-- Step 3 -->
                <div class="step">
                  <h4>Review Your Information</h4>
                  <div class="form-group">
                    <label>Course Name:</label>
                    <p id="review_courseName"></p>
                  </div>

                  <div class="form-group">
                    <label>Course Package:</label>
                    <p id="review_courseReview"></p>
                  </div>

                  <div class="form-group">
                    <label>Section:</label>
                    <p id="review_section"></p>
                  </div>

                  <div class="form-group">
                    <label>Status:</label>
                    <p id="review_courseStatus"></p>
                  </div>

                  <div class="form-group">
                    <label>School Graduated:</label>
                    <p id="review_school_grad"></p>
                  </div>

                  <div id="review_architectureDiv">
                    <div class="form-group">
                      <label>Employment Status:</label>
                      <p id="review_employ_status_arki"></p>
                    </div>

                    <div class="form-group">
                      <label>If Employed what Company:</label>
                      <p id="review_additional_info_arki"></p>
                    </div>
                  </div>

                  <div id="review_plumber">
                    <div class="form-group">
                      <label>Year Graduated:</label>
                      <p id="review_year_graduated"></p>
                    </div>

                    <div class="form-group">
                      <label>Other PRC Licenses:</label>
                      <p id="review_other_prc_licenses"></p>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Facebook:</label>
                    <p id="review_facebook"></p>
                  </div>

                  <div class="form-group">
                    <label>First Name:</label>
                    <p id="review_fName"></p>
                  </div>

                  <div class="form-group">
                    <label>Middle Name:</label>
                    <p id="review_mName"></p>
                  </div>

                  <div class="form-group">
                    <label>Last Name:</label>
                    <p id="review_lName"></p>
                  </div>

                  <div class="form-group">
                    <label>Contact Number:</label>
                    <p id="review_cNumb"></p>
                  </div>

                  <div class="form-group">
                    <label>Sex:</label>
                    <p id="review_gender"></p>
                  </div>

                  <div class="form-group">
                    <label>Birth Date:</label>
                    <p id="review_bod"></p>
                  </div>

                  <div class="form-group">
                    <label>Address:</label>
                    <p id="review_address_stud"></p>
                  </div>

                  <div class="form-group">
                    <label>Email Address:</label>
                    <p id="review_email_stud"></p>
                  </div>

                  <div class="form-group">
                    <label>Contact Person:</label>
                    <p id="review_Pname"></p>
                  </div>
                  <button type="submit" class="btn btn-primary">Submit Registration</button>
                </div>
                <div class="step-buttons">
                  <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                  <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">Next</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <script>
    let currentStep = 0;
    showStep(currentStep);

    function showStep(step) {
      let steps = document.getElementsByClassName("step");
      steps[step].classList.add("active");
      if (step == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }
      if (step == (steps.length - 1)) {
        document.getElementById("nextBtn").style.display = "none";
      } else {
        document.getElementById("nextBtn").style.display = "inline";
      }
      if (step == (steps.length - 2)) {
        displayReview();
      }
    }

    function openPaymongoWindow() {
      window.open('paymongo.php', 'PayMongo', 'width=800,height=600');
    }

    function nextPrev(n) {
      let steps = document.getElementsByClassName("step");
      steps[currentStep].classList.remove("active");
      currentStep = currentStep + n;
      if (currentStep >= steps.length) {
        document.getElementById("regForm").submit();
        return false;
      }
      showStep(currentStep);
    }

    function displayReview() {
      document.getElementById("review_courseName").textContent = document.getElementById("courseName").value;

      // Fetch the selected course review details using AJAX
      let selectedReviewId = document.getElementById("courseReview").value;
      if (selectedReviewId) {
        $.ajax({
          type: 'POST',
          url: 'fetch_review_details.php',
          data: {
            reviewId: selectedReviewId
          },
          success: function(response) {
            let reviewDetails = JSON.parse(response);
            document.getElementById("review_courseReview").textContent = reviewDetails.reviews + " TOTAL FEE PHP." + reviewDetails.tuition_fee;
          }
        });
      }



      document.getElementById("review_section").textContent = document.getElementById("section").value;
      document.getElementById("review_courseStatus").textContent = document.getElementById("courseStatus").value;
      document.getElementById("review_school_grad").textContent = document.getElementById("school_grad").value;
      document.getElementById("review_employ_status_arki").textContent = document.getElementById("employ_status_arki").value;
      document.getElementById("review_additional_info_arki").textContent = document.getElementById("additional_info_arki").value;
      document.getElementById("review_year_graduated").textContent = document.getElementById("year_graduated").value;
      document.getElementById("review_other_prc_licenses").textContent = document.getElementById("other_prc_licenses").value;
      document.getElementById("review_facebook").textContent = document.getElementById("facebook").value;
      document.getElementById("review_fName").textContent = document.getElementById("fName").value;
      document.getElementById("review_mName").textContent = document.getElementById("mName").value;
      document.getElementById("review_lName").textContent = document.getElementById("lName").value;
      document.getElementById("review_cNumb").textContent = document.getElementById("conNumb").value;
      document.getElementById("review_gender").textContent = document.getElementById("gender").value;
      document.getElementById("review_Pname").textContent = document.getElementById("pName").value;
      document.getElementById("review_bod").textContent = document.getElementById("bDate").value;
      document.getElementById("review_address_stud").textContent = document.getElementById("address_stud").value;
      document.getElementById("review_email_stud").textContent = document.getElementById("email_stud").value;
    }
    $(document).ready(function() {
      let emailValid = false;
      let currentStep = 0;
      // Show/hide company input based on employment status
      $('#employ_status_arki').change(function() {
        if ($(this).val() === 'Employed') {
          $('#company_input').show();
        } else {
          $('#company_input').hide();
          $('#additional_info_arki').val(''); // Clear the company input when not employed
        }
      });

      // Email validation
      $('#email_stud').blur(function() {
        var email = $(this).val();
        $.ajax({
          type: 'POST',
          url: 'validate_email.php',
          data: {
            email: email
          },
          success: function(response) {
            if (response.trim() === 'ok') {
              $('#email_validation_result').html('<span style="color: green;">Valid email</span>');
              emailValid = true;
            } else {
              $('#email_validation_result').html('<span style="color: red;">Invalid email</span>');
              emailValid = false;
            }
            updateNextButtonState();
          },
          error: function() {
            $('#email_validation_result').html('<span style="color: red;">Error validating email</span>');
            emailValid = false;
            updateNextButtonState();
          }
        });
      });

      // Function to check if all required fields in the current step are filled
      function checkRequiredFields() {
        let stepValid = true;
        $('.step.active input[required], .step.active select[required]').each(function() {
          if ($(this).val() === '') {
            stepValid = false;
            return false; // break the loop
          }
        });
        return stepValid;
      }

      // Function to update the state of the Next button
      function updateNextButtonState() {
        let requiredFieldsFilled = checkRequiredFields();
        let emailFieldPresent = $('.step.active #email_stud').length > 0;

        if (emailFieldPresent) {
          $('#nextBtn').prop('disabled', !(requiredFieldsFilled && emailValid));
        } else {
          $('#nextBtn').prop('disabled', !requiredFieldsFilled);
        }
      }

      // Modify the existing showStep function
      function showStep(step) {
        let steps = document.getElementsByClassName("step");
        steps[step].classList.add("active");
        if (step == 0) {
          document.getElementById("prevBtn").style.display = "none";
        } else {
          document.getElementById("prevBtn").style.display = "inline";
        }
        if (step == (steps.length - 1)) {
          document.getElementById("nextBtn").style.display = "none";
        } else {
          document.getElementById("nextBtn").style.display = "inline";
        }
        if (step == (steps.length - 2)) {
          displayReview();
        }
        updateNextButtonState();
      }

      // Modify the existing nextPrev function
      function nextPrev(n) {
        let steps = document.getElementsByClassName("step");
        if (n > 0 && !checkRequiredFields()) {
          alert("Please fill all required fields before proceeding.");
          return false;
        }
        steps[currentStep].classList.remove("active");
        currentStep = currentStep + n;
        if (currentStep >= steps.length) {
          document.getElementById("regForm").submit();
          return false;
        }
        showStep(currentStep);
      }

      // Event listener for input changes
      $('form').on('input', 'input, select', function() {
        updateNextButtonState();
      });

      // Initial call to set up the form
      showStep(currentStep);

      // Expose nextPrev function to global scope if it's called from inline onclick handlers
      window.nextPrev = nextPrev;
    });
    $(document).ready(function() {
      // Event listener for change in select element
      $('#courseName').change(function() {
        var selectedCourse = $(this).val();
        $.ajax({
          type: 'POST',
          url: 'fetch_reviews.php',
          data: {
            courseName: selectedCourse
          },
          success: function(response) {
            $('#courseReview').html(response);
            // Trigger the change event for courseReview once reviews are fetched
            $('#courseReview').change();
          }
        });

        // Show/hide architecture div based on selected course
        if (selectedCourse === 'Architecture') {
          $('#architectureDiv').show();
        } else {
          $('#architectureDiv').hide();
        }

        if (selectedCourse === 'Master Plumber') {
          $('#plumber').show();
        } else {
          $('#plumber').hide();
        }
      });

      // Event listener for change in courseReview select element
      $('#courseReview').change(function() {
        var selectedCourseReview = $(this).val();
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

      // Event listener for change in section select element
      $('#section').change(function() {
        var selectedSection = $(this).val();
        var studentId = $('#studentId').val(); // Assuming studentId is stored in an element with id="studentId"
        $.ajax({
          type: 'POST',
          url: 'update_section_count.php',
          data: {
            sectionId: selectedSection,
            studentId: studentId // Add studentId to the data sent
          },
          success: function(response) {
            alert(response);
          },
          error: function() {
            alert('Error updating section count.');
          }
        });
      });

      // Event listener for change in Status
      $('#courseName').change(function() {
        var selectedCourse = $(this).val();
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
    });
  </script>

</body>

</html>