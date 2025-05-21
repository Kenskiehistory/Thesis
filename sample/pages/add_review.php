<?php
include('../includes/db_connect.php');
include('../includes/header.php');
?>
<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div>
                <h4 class="card-title mb-1">Course Maintenance</h4>
                <p class="card-description text-muted">Comprehensive Course Review and Management</p>
              </div>
            </div>

            <form class="forms-sample" id="courseForm">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="courseName" class="form-label">Course Name</label>
                    <select class="form-control" id="courseName">
                      <option value="Architecture">Architecture</option>
                      <option value="Civil Engineering">Civil Engineering</option>
                      <option value="Mechanical Engineering">Mechanical Engineering</option>
                      <option value="Master Plumber">Master Plumber</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="reviewSchedule" class="form-label">Review Schedule</label>
                    <input type="text" class="form-control" id="reviewSchedule" placeholder="Schedule">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="tuitionFee" class="form-label">Tuition Fee</label>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="number" class="form-control" id="tuitionFee" placeholder="Enter tuition fee">
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2" id="submitForm">
                  <i class="mdi mdi-content-save-outline me-1"></i>Submit
                </button>
                <button type="button" class="btn btn-light" id="cancelForm">
                  <i class="mdi mdi-close-circle-outline me-1"></i>Cancel
                </button>
              </div>
            </form>

            <div class="table-responsive mt-4">
              <table class="table table-hover" id="courseTable">
                <thead class="table-light">
                  <tr>
                    <th class="">Course Name</th>
                    <th class="">Review Schedule</th>
                    <th class="">Tuition Fee</th>
                    <th class="">Status</th>
                    <th class="">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Table rows will be inserted here via AJAX -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Additional CSS for Enhanced Styling -->
<style>
  .card {
    border-radius: 10px;
    transition: all 0.3s ease;
  }
  .card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  }
  .table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
  }
  .form-group label {
    font-weight: 600;
    color: #495057;
  }
  .btn-primary {
    display: inline-flex;
    align-items: center;
  }
  .btn-light {
    display: inline-flex;
    align-items: center;
  }
</style>

<?php
include('../includes/footer.php');
?>