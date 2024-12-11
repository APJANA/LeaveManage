<?php
session_start();
include("../includes/autoload.php");
include("../includes/header.php");
include("../includes/topbar.php");

// Start session to access logged-in user details

$loggedInEmployeeID = $_SESSION['EmployeeID']; // Assume 'employee_id' is set during login

?>

<div class="container-fluid">
    <div class="row">
        <?php include("../includes/sidebar.php"); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2 class="mb-3">My Leave Requests</h2>
            </div>
            <div class="page-body">
                <div class="row">
                    <!-- Recent Leave Requests Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch leave requests for the logged-in employee
                                $query = "
                                    SELECT 
                                        lr.LeaveRequestID AS ID,
                                        e.FullName AS Employee,
                                        lr.LeaveType,
                                        lr.LeaveStartDate AS `From`,
                                        lr.LeaveEndDate AS `To`,
                                        lr.Status
                                    FROM leaverequests lr
                                    JOIN employees e ON lr.EmployeeID = e.EmployeeID
                                    WHERE lr.EmployeeID = $loggedInEmployeeID
                                    ORDER BY lr.RequestedDate DESC
                                ";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>{$row['ID']}</td>";
                                        echo "<td>{$row['Employee']}</td>";
                                        echo "<td>{$row['LeaveType']}</td>";
                                        echo "<td>{$row['From']}</td>";
                                        echo "<td>{$row['To']}</td>";

                                        // Set status badge color
                                        $statusClass = match ($row['Status']) {
                                            'Approved' => 'bg-success',
                                            'Rejected' => 'bg-danger',
                                            default => 'bg-warning',
                                        };
                                        echo "<td><span class='badge $statusClass'>{$row['Status']}</span></td>";

                                        // Action buttons based on status
                                        if ($row['Status'] === 'Pending') {
                                            echo "
                                                <td>
                                                    <button class='btn btn-sm btn-success'>Approve</button>
                                                    <button class='btn btn-sm btn-danger'>Reject</button>
                                                </td>
                                            ";
                                        } else {
                                            echo "<td><button class='btn btn-sm btn-secondary'>View</button></td>";
                                        }

                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No leave requests found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include("../includes/footer.php"); ?>