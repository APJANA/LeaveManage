<?php
session_start();
require_once "includes/autoload.php"; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and Password are required!";
        header("Location: index.php");
        exit;
    }

    // Secure the query using a prepared statement
    $query = "
        SELECT `EmployeeID`, `FullName`, `Email`, `PasswordHash`, `RoleID`, `LeaveBalance`, `IsActive` 
        FROM `employees` 
        WHERE `Email` = '$email' AND `IsActive` = 1"; // Check if the employee is active
    $result = mysqli_query($conn, $query);
    // Check if query executed successfully
    if ($result) {
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $user['PasswordHash'])) {
                // Set session variables
                $_SESSION['EmployeeID'] = $user['EmployeeID'];
                $_SESSION['FullName'] = $user['FullName'];
                $_SESSION['RoleID'] = $user['RoleID'];

                // Redirect based on RoleID
                switch ((int)$user['RoleID']) {
                    case 1: // Admin
                        header("Location: admin/index.php");
                        break;
                    case 2: // Director
                        header("Location: director/index.php");
                        break;
                    case 3: // Academic Staff
                        header("Location: staff/index.php");
                        break;
                    case 4: // Non-Academic Staff
                        header("Location: staff/index.php");
                        break;
                    default:
                        $_SESSION['error'] = "Invalid role assigned!";
                        header("Location: index.php");
                        break;
                }
                exit;
            } else {
                $_SESSION['error'] = "Invalid email or password!";
            }
        } else {
            $_SESSION['error'] = "User does not exist or is inactive!";
        }
    } else {
        $_SESSION['error'] = "Database query error: " . mysqli_error($conn);
    }

    mysqli_free_result($result); // Free the result set
    header("Location: index.php");
    exit;
}
?>
<?php include("includes/header.php"); ?>

<div class="container-fluid">
    <div class="p-4 mx-auto shadow rounded" style="margin-top: 50px; width: 100%; max-width: 440px;">
        <h3 class="text-center">ATI LEAVE MANAGEMENT</h3>
        <img src="assets/img/logo.jpg" class="border border-primary d-block mx-auto rounded-circle" style="width: 100px;" alt="Responsive image">
        <h3 class="text-center">Login</h3>

        <!-- Display Alerts -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <?= htmlspecialchars($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['error']);
        endif; ?>

        <form action="index.php" method="post">
            <input class="form-control" type="email" id="email" name="email" placeholder="Email" required>
            <br>
            <input class="form-control" type="password" id="password" name="password" placeholder="Password" required>
            <br>
            <button class="btn btn-primary w-100" type="submit" id="login-form">Login</button>
        </form>
    </div>
</div>

<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script>
    // Auto-dismiss alert after 2 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 150);
        }
    }, 2000);
</script>

<?php include("includes/footer.php"); ?>