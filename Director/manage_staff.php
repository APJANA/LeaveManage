<?php
session_start();
include("../includes/autoload.php");
include("../includes/header.php");
include("../includes/topbar.php");
if (!isset($_SESSION['RoleID']) ||  $_SESSION['RoleID'] != 2) {
    header("Location: ../index.php");
    exit;
}
?>
<?php if (isset($_GET['message'])) { ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_GET['message']); ?>
    </div>
<?php } ?>

<?php if (isset($_GET['error'])) { ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php } ?>

<div class="container-fluid">
    <div class="row">
        <?php include("../includes/sidebar.php"); ?>
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Staff Details</h2>
            </div>

            <!-- Staff Details Table -->
            <div class="table-responsive mb-4">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Leave Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // SQL query to fetch all staff details
                        $query = "
                            SELECT 
                                e.EmployeeID, 
                                e.FullName, 
                                e.Email, 
                                e.RoleID, 
                                e.LeaveBalance, 
                                u.RoleName 
                            FROM 
                                employees e 
                            LEFT JOIN 
                                userroles u 
                            ON 
                                e.RoleID = u.RoleID
                        ";

                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>{$row['EmployeeID']}</td>
                                    <td>{$row['FullName']}</td>
                                    <td>{$row['Email']}</td>
                                    <td>{$row['RoleName']}</td>
                                    <td>{$row['LeaveBalance']}</td>
                                    <td>
                                        <a href='edit_employee.php?id={$row['EmployeeID']}' class='btn btn-sm btn-primary'>Edit</a>
                                        <a href='delete_employee.php?id={$row['EmployeeID']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>Delete</a>
                                    </td>
                                    

                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No staff details found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include("../includes/footer.php"); ?>
</html>
