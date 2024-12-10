<?php
session_start();
include("../includes/autoload.php");
include("../includes/header.php");
include("../includes/topbar.php");

// Check if the user is an admin
if (!isset($_SESSION['RoleID']) || $_SESSION['RoleID'] != 1) {
    header("Location: ../index.php");
    exit;
}

// Fetch leave requests
$query = "
    SELECT lr.LeaveRequestID, e.FullName AS Employee, lr.LeaveType, lr.LeaveStartDate, lr.LeaveEndDate, lr.Status 
    FROM leaverequests lr
    JOIN employees e ON lr.EmployeeID = e.EmployeeID
    ORDER BY lr.LeaveRequestID DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching leave requests: " . mysqli_error($conn));
}

// Handle form submissions (approve/reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['LeaveRequestID'])) {
        $action = $_POST['action'];
        $LeaveRequestID = intval($_POST['LeaveRequestID']); // Sanitize input

        if ($action === 'approve') {
            $updateQuery = "UPDATE leaverequests SET Status = 'Approved' WHERE LeaveRequestID = $LeaveRequestID";
            if (mysqli_query($conn, $updateQuery)) {
                echo "<script>alert('Leave request approved'); window.location.reload();</script>";
            } else {
                echo "<script>alert('Failed to approve leave request'); window.location.reload();</script>";
            }
        } elseif ($action === 'reject') {
            $updateQuery = "UPDATE leaverequests SET Status = 'Rejected' WHERE LeaveRequestID = $LeaveRequestID";
            if (mysqli_query($conn, $updateQuery)) {
                echo "<script>alert('Leave request rejected'); window.location.reload();</script>";
            } else {
                echo "<script>alert('Failed to reject leave request'); window.location.reload();</script>";
            }
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include("../includes/sidebar.php"); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2 class="mb-3">Leave Requests</h2>
            </div>
            <div class="page-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['LeaveRequestID']); ?></td>
                                        <td><?= htmlspecialchars($row['Employee']); ?></td>
                                        <td><?= htmlspecialchars($row['LeaveType']); ?></td>
                                        <td><?= htmlspecialchars($row['LeaveStartDate']); ?></td>
                                        <td><?= htmlspecialchars($row['LeaveEndDate']); ?></td>
                                        <td>
                                            <span class="badge bg-<?= $row['Status'] == 'Pending' ? 'warning' : ($row['Status'] == 'Approved' ? 'success' : 'danger'); ?>">
                                                <?= htmlspecialchars($row['Status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="LeaveRequestID" value="<?= $row['LeaveRequestID']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="LeaveRequestID" value="<?= $row['LeaveRequestID']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
