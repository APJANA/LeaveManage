<?php
include("../includes/autoload.php");

// Check if EmployeeID is provided
if (isset($_GET['id'])) {
    $employeeID = intval($_GET['id']);

    // Fetch employee details based on EmployeeID
    $query = "SELECT * FROM employees WHERE EmployeeID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $employeeID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the employee exists
    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
    } else {
        echo "Employee not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

// Update employee details when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['FullName'];
    $email = $_POST['Email'];
    $roleID = intval($_POST['RoleID']);
    $leaveBalance = floatval($_POST['LeaveBalance']);
    $isActive = isset($_POST['IsActive']) ? 1 : 0;

    // Update query
    $updateQuery = "
        UPDATE employees 
        SET FullName = ?, Email = ?, RoleID = ?, LeaveBalance = ?, IsActive = ?
        WHERE EmployeeID = ?
    ";
    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "ssidis", $fullName, $email, $roleID, $leaveBalance, $isActive, $employeeID);

    if (mysqli_stmt_execute($updateStmt)) {
        echo "<script>alert('Employee details updated successfully.');</script>";
        echo "<script>window.location.href = 'manage_staff.php';</script>"; // Redirect to staff list
        exit;
    } else {
        echo "<script>alert('Failed to update employee details.');</script>";
    }
}

include("../includes/header.php");
include("../includes/topbar.php");
?>

<div class="container mt-4">
    <h2>Edit Employee Details</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="FullName" class="form-label">Full Name</label>
            <input type="text" name="FullName" id="FullName" class="form-control" value="<?php echo htmlspecialchars($employee['FullName']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="email" name="Email" id="Email" class="form-control" value="<?php echo htmlspecialchars($employee['Email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="RoleID" class="form-label">Role</label>
            <select name="RoleID" id="RoleID" class="form-select" required>
                <?php
                // Fetch all roles from the database
                $roleQuery = "SELECT RoleID, RoleName FROM userroles";
                $roleResult = mysqli_query($conn, $roleQuery);

                while ($role = mysqli_fetch_assoc($roleResult)) {
                    $selected = $employee['RoleID'] == $role['RoleID'] ? "selected" : "";
                    echo "<option value='{$role['RoleID']}' $selected>{$role['RoleName']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="LeaveBalance" class="form-label">Leave Balance</label>
            <input type="number" name="LeaveBalance" id="LeaveBalance" class="form-control" value="<?php echo htmlspecialchars($employee['LeaveBalance']); ?>" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="IsActive" id="IsActive" class="form-check-input" <?php echo $employee['IsActive'] ? "checked" : ""; ?>>
            <label for="IsActive" class="form-check-label">Is Active</label>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="manage_staff.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>