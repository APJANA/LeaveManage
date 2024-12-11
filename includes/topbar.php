<!-- Navigation Bar -->

<?php

$user=$_SESSION['FullName'];




?>
<nav class="navbar navbar-expand-lg  text-bg-dark" pcoded-header-position="fixed">

    <div class="container-fluid">
        <a class="navbar-brand text-light" href="#">Leave Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-light" href="#"><i class="fas fa-user"></i> 
                   <?php echo $user;?></a>
                </li>
                <li class="nav-item">

                    <a class="nav-link text-light" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>