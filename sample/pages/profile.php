<?php
include('../includes/db_connect.php');
include('../includes/header.php');
include('../includes/function.php');

$sql = "SELECT up.id, CONCAT(up.fName,' ',up.mName,' ',up.lName) AS full_name, up.courseName, w.payment_status 
        FROM user_profile up
        LEFT JOIN waitlist w ON up.id = w.user_profile_id";
$result = $conn->query($sql);

// Get unique course names
$courseSql = "SELECT DISTINCT courseName FROM user_profile";
$courseResult = $conn->query($courseSql);
?>
<!-- partial -->
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <h1 class="card-title">Profiles Management</h1>

              <!-- Add course filter dropdown -->
              <div class="form-group">
                <label for="courseFilter">Filter by Course:</label>
                <select class="form-control" id="courseFilter">
                  <option value="">All Courses</option>
                  <?php while ($course = mysqli_fetch_assoc($courseResult)) { ?>
                    <option value="<?php echo $course['courseName']; ?>"><?php echo $course['courseName']; ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="table-responsive mt-4">
                <table class="table" id="profileTable">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Course</th>
                      <th>Payment Status</th>
                      <th>View Profile</th>
                      <th>View ledger</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($record = mysqli_fetch_assoc($result)) {
                      // Only display if payment is 'Paid' or user is admin
                      if ($record['payment_status'] == 'Paid' || check_role('Staff')) {
                    ?>
                        <tr>
                          <td><?php echo $record['full_name']; ?></td>
                          <td><?php echo $record['courseName']; ?></td>
                          <td><?php echo $record['payment_status']; ?></td>
                          <td>
                            <button type="button" class="btn btn-primary btn-sm view-profile" data-student-id="<?php echo $record['id']; ?>">View Profile</button>
                          </td>
                          <td>
                            <button type="button" class="btn btn-primary btn-sm view-profile-ledger" data-student-id="<?php echo $record['id']; ?>">View Ledger</button>
                          </td>
                        </tr>
                    <?php
                      }
                    } ?>
                  </tbody>
                </table>
              </div>


              <!-- Modal for displaying profile view -->
              <div class="modal fade" id="profileViewModal" tabindex="-1" role="dialog" aria-labelledby="profileViewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="profileViewModalLabel">Student Profile</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="profileViewContent">
                      <!-- Profile view content will be loaded here -->
                    </div>
                  </div>
                </div>
              </div>

              <!-- Modal for displaying ledger view -->
              <div class="modal fade" id="ledgerViewModal" tabindex="-1" role="dialog" aria-labelledby="ledgerViewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="ledgerViewModalLabel">Student Ledger</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="ledgerViewContent">
                      <!-- Ledger view content will be loaded here -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- main-panel ends -->
</div>
<?php
include('../includes/footer.php')
?>