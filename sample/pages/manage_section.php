<?php
include('../includes/db_connect.php');
include('../includes/header.php');

if (isset($_POST['assign'])) {
    $staff_id = $_POST['staff_id'];
    $section_id = $_POST['section_id'];

    $stmt = $conn->prepare('UPDATE staff SET section_id = ? WHERE id = ?');
    $stmt->bind_param("ii", $section_id, $staff_id);
    $stmt->execute();
    $stmt->close();

    set_message("Staff successfully assigned to the selected section");
}

// Fetch all staff
$staffs = $conn->query('SELECT id, firstName, lastName, courseName FROM staff WHERE active = 1');
?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Assign Staff to Sections</h4>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="staff_id">Select Staff</label>
                        <select name="staff_id" id="staff_id" class="form-control" required>
                            <option value="">Select Staff</option>
                            <?php if ($staffs->num_rows > 0): ?>
                                <?php while ($staff = $staffs->fetch_assoc()): ?>
                                    <option value="<?php echo $staff['id']; ?>" data-course="<?php echo htmlspecialchars($staff['courseName']); ?>">
                                        <?php echo htmlspecialchars($staff['firstName'] . ' ' . $staff['lastName'] . ' (' . $staff['courseName'] . ')'); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="">No staff available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="section_id">Select Section</label>
                        <select name="section_id" id="section_id" class="form-control" required>
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    <button type="submit" name="assign" class="btn btn-primary">Assign</button>
                </form>

                <h4 class="card-title mt-4">Assigned Staff to Sections</h4>
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Sections</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all assignments
                        $assignments = $conn->query('SELECT staff.firstName, staff.lastName, staff.courseName, GROUP_CONCAT(sectioning.section_name SEPARATOR ", ") as sections 
                                                         FROM staff
                                                         JOIN sectioning ON staff.section_id = sectioning.id
                                                         WHERE staff.section_id IS NOT NULL
                                                         GROUP BY staff.id');
                        if ($assignments->num_rows > 0):
                            while ($assignment = $assignments->fetch_assoc()):
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($assignment['firstName'] . ' ' . $assignment['lastName'] . ' (' . $assignment['courseName'] . ')'); ?></td>
                                    <td><?php echo htmlspecialchars($assignment['sections']); ?></td>
                                </tr>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <tr>
                                <td colspan="2">No assignments found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#staff_id').change(function() {
                var courseName = $(this).find(':selected').data('course');

                if (courseName) {
                    $.ajax({
                        type: 'POST',
                        url: 'staff_fetch_sections.php', // URL to PHP script that fetches sections based on courseName
                        data: {
                            courseName: courseName
                        },
                        success: function(response) {
                            $('#section_id').html(response);
                        }
                    });
                } else {
                    $('#section_id').html('<option value="">Select Section</option>');
                }
            });
        });
    </script>
    <?php include('../includes/footer.php'); ?>