<?php
session_start();
include("../includes/autoload.php");
include("../includes/header.php");
include("../includes/topbar.php");
if (!isset($_SESSION['RoleID']) || $_SESSION['RoleID'] != 1 && $_SESSION['RoleID'] != 2) {
    header("Location: ../index.php");
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include("../includes/sidebar.php"); ?>
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            </div>
            <!-- Custom Department Section -->
            <div class="page-body">
                <div class="row">
                    <h2 class="mb-3">New Staff</h2>

                    <!-- Form Section -->
                    <?php
                    // Handle POST request
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $employeeName = $_POST['employeeName'];
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $confirmPassword = $_POST['confirmPassword'];
                        $roleType = $_POST['roleType'];

                        // Server-side validation for password match
                        if ($password !== $confirmPassword) {
                            echo "<div class='alert alert-danger'>Passwords do not match.</div>";
                        } else {
                            // Map roles to RoleID
                            $roleMap = [
                                "Academic Staff" => 3,
                                "Admin" => 1,
                                "Director" => 2,
                                "Non-Academic Staff" => 4,
                            ];
                            $roleID = $roleMap[$roleType] ?? 0;

                            // Calculate Leave Balance from leavepolicies table
                            $query = "SELECT SUM(LeaveEntitlement) AS TotalLeave FROM leavepolicies WHERE RoleID = $roleID";
                            $result = mysqli_query($conn, $query);

                            $defaultLeaveBalance = 20; // Default value if no policy exists
                            if ($result) {
                                $row = mysqli_fetch_assoc($result);
                                $defaultLeaveBalance = $row['TotalLeave'] ?? 0;
                            }

                            // Insert new employee with calculated LeaveBalance
                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                            $insertQuery = "
                                INSERT INTO employees (FullName, Email, PasswordHash, RoleID, LeaveBalance, IsActive) 
                                VALUES ('$employeeName', '$email', '$passwordHash', $roleID, $defaultLeaveBalance, 1)
                            ";

                            if (mysqli_query($conn, $insertQuery)) {
                                echo "<div class='alert alert-success' id='success-alert'>Staff successfully added with Leave Balance!</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                            }
                        }
                    }
                    ?>

                    <form method="POST" action="" onsubmit="return validateForm();">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="employeeName" class="form-label">Employee Full Name</label>
                                <input type="text" class="form-control" id="employeeName" name="employeeName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="confirmPassword" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            </div>
                            <div class="col-md-6">
                                <label for="roleType" class="form-label">Role Type</label>
                                <select class="form-select" id="roleType" name="roleType" required>
                                    <option value="">Choose...</option>
                                    <option>Academic Staff</option>
                                    <option>Admin</option>
                                    <option>Director</option>
                                    <option>Non-Academic Staff</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                    <!-- Client-side Validation with JavaScript -->
                    <script>
                        function validateForm() {
                            var password = document.getElementById("password").value;
                            var confirmPassword = document.getElementById("confirmPassword").value;

                            if (password !== confirmPassword) {
                                alert("Passwords do not match. Please try again.");
                                return false; // Prevent form submission
                            }
                            return true; // Allow submission
                        }
                    </script>
                    <script>
                        setTimeout(function() {
                            var successAlert = document.getElementById('success-alert');
                            if (successAlert) {
                                successAlert.style.display = 'none';
                            }
                        }, 3000);
                    </script>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include("../includes/footer.php"); ?>