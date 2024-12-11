<?php
session_start();
include("../includes/autoload.php");



if (!isset($_SESSION['EmployeeID']) || !isset($_SESSION['RoleID'])) {
    header('Location: ../index.php');
    exit();
}

$userRole = $_SESSION['RoleID'];
if ($userRole != 1) {
    header('Location: ../index.php');
    exit();
}



// Prepare the SQL query securely using prepared statements
$stmt = $conn->prepare("
    SELECT 
        COUNT(*) AS total_leaves,
        SUM(CASE WHEN `Status` = 'Pending' THEN 1 ELSE 0 END) AS pending_count,
        SUM(CASE WHEN `Status` = 'Rejected' THEN 1 ELSE 0 END) AS rejected_count,
        SUM(CASE WHEN `Status` = 'Approved' THEN 1 ELSE 0 END) AS approved_count
    FROM `leaverequests`
    ");


if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();

    // Ensure the query returned data
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pending_leave = $row['pending_count'];
        $rejected_leave = $row['rejected_count'];
        $approved_leave = $row['approved_count'];
        $total_leaves = $row['total_leaves'];
    } else {
        echo "No data found.";
    }

    $stmt->close();
} else {
    echo "Failed to prepare the query.";
}





// Calculate the percentages
$pending_percentage = ($total_leaves > 0) ? floor(($pending_leave / $total_leaves) * 100) : 0;
$approved_percentage = ($total_leaves > 0) ? floor(($approved_leave / $total_leaves) * 100) : 0;
$rejected_percentage = ($total_leaves > 0) ? floor(($rejected_leave / $total_leaves) * 100) : 0;
?>





<?php include("../includes/header.php"); ?>
<?php include("../includes/topbar.php"); ?>


<div class="container-fluid">
    <div class="row">
        <?php include("../includes/sidebar.php"); ?>
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>
            <!-- Start coustom depart -->
            <div class="page-body">
                <div class="row">
                    <!-- user card  start -->
                    <div class="col-md-6 col-xl-4">
                        <div class="card widget-card-1">
                            <?php
                            $sql = "SELECT COUNT(*) AS ActiveStaffCount FROM `employees` WHERE `IsActive` = 1";
                            $result = mysqli_query($conn, $sql);


                            if ($result) {

                                $row = mysqli_fetch_assoc($result);
                                $total_employee = $row['ActiveStaffCount'];
                            } else {

                                echo "Query error: " . mysqli_error($conn);
                            }
                            ?>
                            <div class="card-block-small">
                                <i class="feather icon-user bg-c-blue card1-icon"></i>
                                <span class="text-c-blue f-w-600">Active Staff</span>
                                <?php if ($total_employee == 0): ?>
                                    <h4>No</h4>
                                <?php else: ?>
                                    <h4><?= $total_employee ?></h4>
                                <?php endif; ?>
                                <div>
                                    <span class="f-left m-t-10 text-muted">
                                        <i class="text-c-blue f-16 feather icon-user m-r-10"></i>Registered Staff
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card widget-card-1">
                            <?php
                            $sql = "SELECT COUNT(DISTINCT `LeaveType`) AS LeaveTypeCount FROM `leavepolicies`";

                            $result = mysqli_query($conn, $sql);


                            if ($result) {

                                $row = mysqli_fetch_assoc($result);
                                $leaveTypeCount = $row['LeaveTypeCount'];
                            } else {
                                echo "Query error: " . mysqli_error($conn);
                            }
                            ?>
                            <div class="card-block-small">
                                <i class="feather icon-home bg-c-pink card1-icon"></i>
                                <span class="text-c-pink f-w-600">Departments</span>
                                <?php if ($leaveTypeCount == 0): ?>
                                    <h4>No Type</h4>
                                <?php else: ?>
                                    <h4><?= $leaveTypeCount ?></h4>
                                <?php endif; ?>
                                <div>
                                    <span class="f-left m-t-10 text-muted">
                                        <i class="text-c-pink f-16 feather icon-home m-r-10"></i>Available Departments
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6 col-xl-4">
                        <div class="card widget-card-1">
                            <?php

                            $sql = "SELECT COUNT(*) AS TotalLeaveRequests FROM `leaverequests`";

                            $result = mysqli_query($conn, $sql);


                            if ($result) {

                                $row = mysqli_fetch_assoc($result);
                                $total_leave = $row['TotalLeaveRequests'];
                            } else {
                                echo "Query error: " . mysqli_error($conn);
                            }
                            ?> <div class="card-block-small">
                                <i class="feather icon-list bg-c-yellow card1-icon"></i>
                                <span class="text-c-yellow f-w-600">Leave</span>
                                <?php if ($total_leave == 0): ?>
                                    <h4>No</h4>
                                <?php else: ?>
                                    <h4><?= $total_leave ?></h4>
                                <?php endif; ?>
                                <div>
                                    <span class="f-left m-t-10 text-muted">
                                        <i class="text-c-yellow f-16 feather icon-list m-r-10"></i>Leave Application
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- user card  end -->

                    <!-- statustic with progressbar  start -->
                    <div class="col-xl-4 col-md-6">
                        <div class="card statustic-progress-card">
                            <div class="card-header">
                                <h5>Pending Leave</h5>
                            </div>
                            <div class="card-block">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <label class="label bg-c-lite-green">
                                            <?php echo $pending_percentage; ?>%<i class="m-l-10 feather icon-arrow-up"></i>
                                        </label>
                                    </div>
                                    <div class="col text-right">
                                        <h5 class=""><?php echo $pending_leave; ?></h5>
                                    </div>
                                </div>
                                <div class="progress m-t-15">
                                    <div class="progress-bar bg-c-lite-green" style="width:<?php echo $pending_percentage; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card statustic-progress-card">
                            <div class="card-header">
                                <h5>Approved Leave</h5>
                            </div>
                            <div class="card-block">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <label class="label label-success">
                                            <?php echo $approved_percentage; ?>% <i class="m-l-10 feather icon-arrow-up"></i>
                                        </label>
                                    </div>
                                    <div class="col text-right">
                                        <h5 class=""><?php echo $approved_leave; ?></h5>
                                    </div>
                                </div>
                                <div class="progress m-t-15">
                                    <div class="progress-bar bg-c-green" style="width:<?php echo $approved_percentage; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card statustic-progress-card">
                            <div class="card-header">
                                <h5>Rejected Leave</h5>
                            </div>
                            <div class="card-block">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <label class="label label-danger">
                                            <?php echo $rejected_percentage; ?>% <i class="m-l-10 feather icon-arrow-up"></i>
                                        </label>
                                    </div>
                                    <div class="col text-right">
                                        <h5 class=""><?php echo $rejected_leave; ?></h5>
                                    </div>
                                </div>
                                <div class="progress m-t-15">
                                    <div class="progress-bar bg-c-pink" style="width:<?php echo $rejected_percentage; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include("../includes/footer.php"); ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>