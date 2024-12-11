<?php
session_start();
include("../includes/autoload.php");
include("../includes/header.php");
include("../includes/topbar.php");
?>

<div class="container-fluid">
    <div class="row">
        <?php include("../includes/sidebar.php"); ?>
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2 class="mb-3">Submit New Leave Request</h2>
            </div>
            <div class="page-body">
                <div class="row">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $leaveType = $_POST['leaveType'];
                        $startDate = $_POST['startDate'];
                        $endDate = $_POST['endDate'];
                        $reason = $_POST['reason'];
                        $requestedDays = $_POST['requestedDays'];

                        // Fetch logged-in employee's details from session
                        $employeeID = $_SESSION['EmployeeID'];
                        $employeeQuery = "SELECT LeaveBalance, RoleID FROM employees WHERE EmployeeID = '$employeeID'";
                        $employeeResult = mysqli_query($conn, $employeeQuery);

                        if (mysqli_num_rows($employeeResult) > 0) {
                            $employee = mysqli_fetch_assoc($employeeResult);
                            $leaveBalance = $employee['LeaveBalance'];
                            $roleID = $employee['RoleID'];

                            // First check if leave policies exist
                            $policyQuery = "
                                SELECT LeaveEntitlement 
                                FROM leavepolicies 
                                WHERE RoleID = $roleID AND LeaveType = '$leaveType'
                            ";
                            $policyResult = mysqli_query($conn, $policyQuery);

                            if (mysqli_num_rows($policyResult) > 0) {
                                // If leave policy exists
                                $policy = mysqli_fetch_assoc($policyResult);
                                $leaveEntitlement = $policy['LeaveEntitlement'];

                                $leavesTakenQuery = "
                                    SELECT SUM(DATEDIFF(LeaveEndDate, LeaveStartDate) + 1) AS TotalTaken 
                                    FROM leaverequests 
                                    WHERE EmployeeID = $employeeID AND LeaveType = '$leaveType' AND Status = 'Approved'
                                ";
                                $leavesTakenResult = mysqli_query($conn, $leavesTakenQuery);
                                $leavesTaken = mysqli_fetch_assoc($leavesTakenResult)['TotalTaken'] ?? 0;

                                if ($leavesTaken + $requestedDays > $leaveEntitlement) {
                                    echo "<div class='alert alert-danger'>Leave request exceeds allowed entitlement for this type.</div>";
                                    exit;
                                }
                            } else {
                                // Fallback: If leave policies don't exist, allow direct leave balance validation
                                if ($requestedDays > $leaveBalance) {
                                    echo "<div class='alert alert-danger'>Requested days exceed leave balance.</div>";
                                    exit;
                                }
                            }

                            // Insert the leave request if checks passed
                            $insertQuery = "
                                INSERT INTO leaverequests 
                                (EmployeeID, LeaveType, LeaveStartDate, LeaveEndDate, LeaveReason, Status, RequestedDate) 
                                VALUES ($employeeID, '$leaveType', '$startDate', '$endDate', '$reason', 'Pending', NOW())
                            ";

                            if (mysqli_query($conn, $insertQuery)) {
                                $updatedBalance = $leaveBalance - $requestedDays;
                                $updateBalanceQuery = "UPDATE employees SET LeaveBalance = $updatedBalance WHERE EmployeeID = $employeeID";

                                if (mysqli_query($conn, $updateBalanceQuery)) {
                                    echo "<div class='alert alert-success'>Leave request submitted successfully!</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Failed to update leave balance.</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Failed to submit leave request: " . mysqli_error($conn) . "</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Logged-in employee details are not valid.</div>";
                        }
                    }
                    ?>

                    <!-- Leave Request Form -->
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="leaveType" class="form-label">Leave Type</label>
                                <select class="form-select" id="leaveType" name="leaveType" required>
                                    <option value="">Choose...</option>
                                    <?php
                                    $leaveTypesQuery = "SELECT DISTINCT LeaveType FROM leavepolicies";
                                    $leaveTypesResult = mysqli_query($conn, $leaveTypesQuery);

                                    while ($leaveType = mysqli_fetch_assoc($leaveTypesResult)) {
                                        echo "<option value='{$leaveType['LeaveType']}'>{$leaveType['LeaveType']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" required>
                            </div>
                            <div class="col-md-4">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                            </div>
                            <div class="col-md-4">
                                <label for="requestedDays" class="form-label">Requested Days</label>
                                <input type="text" class="form-control" id="requestedDays" name="requestedDays" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>

                    <script>
                        document.getElementById('startDate').addEventListener('change', calculateRequestedDays);
                        document.getElementById('endDate').addEventListener('change', calculateRequestedDays);

                        function calculateRequestedDays() {
                            const startDate = new Date(document.getElementById('startDate').value);
                            const endDate = new Date(document.getElementById('endDate').value);

                            if (startDate && endDate && startDate <= endDate) {
                                const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                                document.getElementById('requestedDays').value = days;
                            } else {
                                document.getElementById('requestedDays').value = "";
                            }
                        }
                    </script>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include("../includes/footer.php"); ?>