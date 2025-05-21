<?php
include('../includes/header.php');
?>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title mb-1">Course Section Management</h4>
                                <p class="text-muted mb-0">Create and Manage Course Sections</p>
                            </div>

                        </div>

                        <form id="courseForm" class="forms-sample">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="courseName" class="form-label">Course Name</label>
                                        <select id="courseName" name="courseName" class="form-control">
                                            <?php
                                            // Define your database connection parameters
                                            include('../includes/db_connect.php');

                                            // Check connection
                                            if ($conn->connect_error) {
                                                die("Connection failed: " . $conn->connect_error);
                                            }

                                            // Assuming you have a database connection, perform a query to retrieve course names
                                            $query = "SELECT id, reviews FROM course_reviews where review_status = 'Active'";
                                            $result = $conn->query($query);

                                            // Check if query executed successfully
                                            if ($result->num_rows > 0) {
                                                // Loop through the results and populate the select element
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row['id'] . "'>" . $row['reviews'] . "</option>";
                                                }
                                            } else {
                                                // Handle query error if any
                                                echo "<option>No courses available</option>";
                                            }

                                            // Close the database connection
                                            $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sectionName" class="form-label">Section Group</label>
                                        <select class="form-control" id="sectionName" name="sectionName">
                                            <option value="MORNING | 8:30 AM-12:00 NN">MORNING | 8:30 AM-12:00 NN</option>
                                            <option value="AFTERNOON | 1:30 PM - 5:00 PM">AFTERNOON | 1:30 PM - 5:00 PM</option>
                                            <option value="EVENING | 6:00 Pm - 9:00 PM">EVENING | 6:00 Pm - 9:00 PM</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sectionLimit" class="form-label">Section Limit</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="mdi mdi-account-multiple"></i></span>
                                            <input type="number" class="form-control" id="sectionLimit" name="sectionLimit" placeholder="Enter section limit" min="1">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="mdi mdi-content-save-outline me-1"></i>Submit
                                </button>
                                <button type="button" class="btn btn-light">
                                    <i class="mdi mdi-close-circle-outline me-1"></i>Cancel
                                </button>
                            </div>
                        </form>

                        <div class="table-responsive mt-4">
                            <table class="table table-hover" id="sectionTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="">ID</th>
                                        <th class="">Course</th>
                                        <th class="">Section Name</th>
                                        <th class="">Section Limit</th>
                                        <th class="">Section Count</th>
                                    </tr>
                                </thead>
                                <tbody id="sectionTableBody">
                                    <!-- Table rows will be inserted here via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .card {
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .form-group label {
            font-weight: 600;
            color: #495057;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
        }
        .btn {
            display: inline-flex;
            align-items: center;
        }
        .input-group-text {
            background-color: transparent;
            border: 1px solid rgba(0,0,0,0.1);
        }
    </style>
<?php
include('../includes/footer.php');
?>