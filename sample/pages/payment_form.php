<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title text-primary mb-4">Payment Management</h3>

                            <!-- Payment Form -->
                            <div class="row align-items-center mb-4">
                                <div class="col-md-9">
                                    <label for="user_profile_id" class="form-label">Select Student</label>
                                    <div class="input-group" style="gap: 20px;">
                                        <select id="user_profile_id" class="form-select" name="user_profile_id"
                                            required>
                                            <option value="">Select a student</option>
                                        </select>
                                        <button id="placeholder_button" type="button"
                                            class="btn btn-success btn-paymongo">
                                            <i class="bi bi-currency-dollar"></i> Paymongo
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Debit Section -->
                                <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card border-info">
                                        <div class="card-body">
                                            <h5 class="text-info">Add Debit</h5>
                                            <form id="debit_form" class="mt-3">
                                                <input type="hidden" name="user_profile_id" id="debit_user_id">

                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="debit_option" id="debit_course" value="course"
                                                                checked>
                                                            <label class="form-check-label" for="debit_course">Select
                                                                Course</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="debit_option" id="debit_manual" value="manual">
                                                            <label class="form-check-label" for="debit_manual">Manual
                                                                Entry</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="course_selection" class="mb-3">
                                                    <label for="selected_course" class="form-label">Select
                                                        Course</label>
                                                    <select name="selected_course" id="selected_course"
                                                        class="form-select">
                                                        <option value="">Select a course</option>
                                                    </select>
                                                </div>

                                                <div id="manual_entry" class="mb-3" style="display:none;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="debit_amount" class="form-label">Amount</label>
                                                            <input type="number" step="0.01" name="debit_amount"
                                                                id="debit_amount" class="form-control">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="debit_particulars"
                                                                class="form-label">Particulars</label>
                                                            <input type="text" name="debit_particulars"
                                                                id="debit_particulars" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-info btn-block mt-3">Add
                                                    Debit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Credit Section -->
                                <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <h5 class="text-success">Add Credit</h5>
                                            <form id="credit_form" class="mt-3">
                                                <input type="hidden" name="user_profile_id" id="credit_user_id">

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="credit_amount" class="form-label">Amount</label>
                                                        <input type="number" step="0.01" name="credit_amount" required
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="credit_or" class="form-label">Original Receipt
                                                            (OR)</label>
                                                        <input type="text" name="credit_or" id="credit_or" required
                                                            class="form-control">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="credit_particulars"
                                                        class="form-label">Particulars</label>
                                                    <input type="text" name="credit_particulars" required
                                                        class="form-control">
                                                </div>

                                                <button type="submit" class="btn btn-success btn-block mt-3">Add
                                                    Credit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Ledger -->
                            <h4 class="card-title mt-5">Account Ledger</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered mt-3" id="ledger_table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Particulars</th>
                                            <th>OR</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows to be dynamically populated -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Payment Status -->
                            <form id="payment_status_form" style="display: none; margin-top: 20px;">
                                <input type="hidden" name="waitlist_id">
                                <input type="hidden" name="current_status">
                                <input type="hidden" name="user_profile_id">
                                <button type="submit" class="btn btn-success">Mark as Paid</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>

</style>