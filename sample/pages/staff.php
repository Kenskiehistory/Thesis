<?php
include('../includes/db_connect.php');
include('../includes/function.php');

$sql = "SELECT id, CONCAT(firstName, ' ', middleName, ' ', lastName) AS full_name, courseName, contactInfo, email, active 
        FROM staff";
$result = $conn->query($sql);

if (!$result) {
    die('Error: ' . $conn->error);
}
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
                <h4 class="card-title mb-1">Staff Management</h4>
                <p class="card-description text-muted">Manage User Accounts and Access</p>
              </div>
              <button id="addNewStaffBtn" class="btn btn-primary">
                <i class="mdi mdi-account-plus-outline me-1"></i>Add New Staff
              </button>
            </div>

            <div class="table-responsive">
              <table class="table table-hover" id="staffTable">
                <thead class="table-light">
                  <tr>
                    <th class="">Name</th>
                    <th class="">Course</th>
                    <th class="text-center">Contact Info</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  // Reset pointer after previous query
                  mysqli_data_seek($result, 0);
                  while ($record = mysqli_fetch_assoc($result)) { 
                  ?>
                    <tr>
                      <td class=""><?php echo htmlspecialchars($record['full_name']); ?></td>
                      <td class=""><?php echo htmlspecialchars($record['courseName']); ?></td>
                      <td class="text-center"><?php echo htmlspecialchars($record['contactInfo']); ?></td>
                      <td class="text-center"><?php echo htmlspecialchars($record['email']); ?></td>
                      <td class="text-center">
                        <span class="badge <?php echo $record['active'] ? 'bg-success' : 'bg-danger'; ?>">
                          <?php echo $record['active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                      </td>
                    </tr>
                  <?php } ?>
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
  #courseFilter, #statusFilter {
    max-width: 300px;
  }
</style>


<?php
include('../includes/footer.php');
?>