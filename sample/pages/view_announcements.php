<div class="card">
    <div class="card-body">
        <h4 class="card-title">Announcements (Admin View)</h4>
        
        <!-- Course filter form -->
        <div class="form-group mb-4">
            <label for="courseFilter">Filter by Course:</label>
            <select id="courseFilter" class="form-control">
                <option value="">All Courses</option>
            </select>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="announcementTableBody">
                    <!-- Announcements will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>