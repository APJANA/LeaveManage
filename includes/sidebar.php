<?php
// Assuming the user's role is stored in a session or fetched from the database

$roleID = $_SESSION['RoleID']; // Replace with appropriate user role fetching logic
?>

<!-- Sidebar -->
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-dark">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link text-light" href="index.php">
                    <span class="f-left m-t-10 text-c-white">
                        <i class="text-light text-c-white f-16 feather icon-home m-r-10"></i>Dashboard
                    </span>
                </a>
            </li>

            <?php if (in_array($roleID, [2, 3, 4])): // Director, Academic Staff, Non-Academic Staff ?>
            <!-- Leave Section -->
            <li class="nav-item">
                <a class="nav-link text-light" href="apply_leave.php">
                    <span class="f-left m-t-10 text-c-white">
                        <i class="text-light text-c-white f-16 feather icon-calendar m-r-10"></i>Apply Leave
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light" href="my_leave.php">
                    <span class="f-left m-t-10 text-c-white">
                        <i class="text-light text-c-white f-16 feather icon-user-check m-r-10"></i>My Leave
                    </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($roleID == 2): // Director ?>
            <li class="nav-item">
                <a class="nav-link text-light" href="manage_leave.php">
                    <span class="f-left m-t-10 text-c-white">
                        <i class="text-light text-c-white f-16 feather icon-folder m-r-10"></i>Manage Leave
                    </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (in_array($roleID, [1, 2])): // Admin or Director ?>
            <!-- Staff Section -->
            <li class="nav-item">
                <a class="nav-link text-light" href="new_staff.php">
                    <span class="f-left m-t-10 text-c-white">
                        <i class="text-light text-c-white f-16 feather icon-user m-r-10"></i>New Staff
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light" href="manage_staff.php">
                    <span class="f-left m-t-10 text-c-white">
                        <i class="text-light text-c-white f-16 feather icon-users m-r-10"></i>Manage Staff
                    </span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
