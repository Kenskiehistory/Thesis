<?php
include('../includes/db_connect.php');
include('../includes/header.php');
$availableCourses = ['Architecture', 'Civil Engineering', 'Mechanical Engineering', 'Master Plumber'];
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
                <h4 class="card-title mb-1">Payment Fees Management</h4>
                <p class="card-description text-muted">Comprehensive Payment and Fee Tracking</p>
              </div>
            </div>

            <form class="forms-sample" id="paymentForm">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="courseName" class="form-label">Course</label>
                    <select class="form-control" id="courseName" name="courseName" required>
                      <option value="">Select Course</option>
                      <?php foreach ($availableCourses as $course) : ?>
                        <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="particular" class="form-label">Particular</label>
                    <input type="text" class="form-control" id="particular" name="particular" placeholder="Enter payment details" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="amount" class="form-label">Amount</label>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount" step="0.01" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2" id="submitPayment">
                  <i class="mdi mdi-content-save-outline me-1"></i>Submit Payment
                </button>
                <button type="button" class="btn btn-light" id="cancelPayment">
                  <i class="mdi mdi-close-circle-outline me-1"></i>Cancel
                </button>
              </div>
            </form>

            <div class="row mt-4 mb-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="courseFilter" class="form-label">Filter by Course</label>
                  <select class="form-control" id="courseFilter">
                    <option value="">All Courses</option>
                    <?php foreach ($availableCourses as $course) : ?>
                      <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover" id="paymentTable">
                <thead class="table-light">
                  <tr>
                    <th class="">Course</th>
                    <th class="">Particular</th>
                    <th class="">Amount</th>
                    <th class="">Date</th>
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
  .btn-primary, .btn-light {
    display: inline-flex;
    align-items: center;
  }
  #courseFilter {
    max-width: 300px;
  }
</style>

<?php
include('../includes/footer.php');
?>