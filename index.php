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

    // Hash the password using md5 for compatibility
    // NOTE: It is strongly recommended to use password_hash() instead of md5()
    $password = md5($password);

    // Secure the query using a prepared statement
    $query = "SELECT * FROM tblemployees WHERE email_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        $_SESSION['error'] = "Database query error!";
        header("Location: index.php");
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if user exists
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if ($user['password'] === $password) {
            // Set session variables
            $_SESSION['emp_id'] = $user['emp_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            switch (strtolower($user['role'])) {
                case 'admin':
                    header("Location: admin/index.php");
                    break;
                case 'staff':
                    header("Location: staff/index.php");
                    break;
                case 'director':
                    header("Location: director/index.php");
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
        $_SESSION['error'] = "User does not exist!";
    }

    mysqli_stmt_close($stmt);
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
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                    {$_SESSION['error']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
            unset($_SESSION['error']); // Clear error after displaying
        }
        ?>

        <form action="index.php" method="post">
            <input class="form-control" type="email" id="email" name="email" placeholder="Email" required>
            <br>
            <input class="form-control" type="password" id="password" name="password" placeholder="Password" required>
            <br>
            <button class="btn btn-primary w-100" type="submit" id="login-form">Login</button>
            <br>
            
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
            alert.remove();
        }
    }, 2000);
</script>

<?php include("includes/footer.php"); ?>
