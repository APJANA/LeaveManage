<?php 
include("../includes/autoload.php");

$stmt = $conn->prepare("SELECT COUNT(*) as total_leave FROM tblleave");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_leave = $row['total_leave'];

// Fetch the count of pending leaves
$stmt = $conn->prepare("SELECT COUNT(*) as pending_leave FROM tblleave WHERE leave_status = 0");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$pending_leave = $row['pending_leave'];

// Fetch the count of approved leaves
$stmt = $conn->prepare("SELECT COUNT(*) as approved_leave FROM tblleave WHERE leave_status = 1");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$approved_leave = $row['approved_leave'];

// Fetch the count of recalled leaves
$stmt = $conn->prepare("SELECT COUNT(*) as recalled_leave FROM tblleave WHERE leave_status = 3");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$recalled_leave = $row['recalled_leave'];


// /Fetch the count of canceled leaves
$stmt = $conn->prepare("SELECT COUNT(*) as rejected_leave FROM tblleave WHERE leave_status = 4");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$rejected_leave = $row['rejected_leave'];

// Calculate the percentages
$pending_percentage = ($total_leave > 0) ? floor(($pending_leave / $total_leave) * 100) : 0;
$approved_percentage = ($total_leave > 0) ? floor(($approved_leave / $total_leave) * 100) : 0;
$recalled_percentage = ($total_leave > 0) ? floor(($recalled_leave / $total_leave) * 100) : 0;
$rejected_percentage = ($total_leave > 0) ? floor(($rejected_leave / $total_leave) * 100) : 0;
?>



<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management Admin Panel</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"> 
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" type="text/css" href="../assets/css/feather/css/feather.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">

    <style>
        .sidebar {
            height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
        .content {
            padding: 20px;
        }
        .stat-card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-primary" pcoded-header-position="fixed">
        
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Leave Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    



    

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                    
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-calendar-alt"></i> Leave Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-users"></i> Employees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            

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
                                                        $stmt = $conn->prepare("SELECT COUNT(*) as total_employee FROM tblemployees");
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_assoc();
                                                        $total_employee = $row['total_employee'];    
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
                                                        $stmt = $conn->prepare("SELECT COUNT(*) as total_depart FROM tbldepartments");
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_assoc();
                                                        $total_depart = $row['total_depart'];    
                                                    ?>                                                  <div class="card-block-small">
                                                        <i class="feather icon-home bg-c-pink card1-icon"></i>
                                                        <span class="text-c-pink f-w-600">Departments</span>
                                                        <?php if ($total_depart == 0): ?>
                                                            <h4>No</h4>
                                                        <?php else: ?>
                                                            <h4><?= $total_depart ?></h4>
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
                                                       
                                                        $stmt = $conn->prepare("SELECT COUNT(*) as total_leave FROM tblleave");
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_assoc();
                                                        $total_leave = $row['total_leave'];    
                                                    ?>                                          <div class="card-block-small">
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
                                                            <div class="progress-bar bg-c-lite-green" style="width:0%"></div>
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
                                                            <div class="progress-bar bg-c-green" style="width:0%"></div>
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
                                                            <div class="progress-bar bg-c-pink" style="width:0%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                         
                                    </div>

</div>
<!-- end coustom depart -->

                <!--  Leave Statistics
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Leaves</h5>
                                <p class="card-text display-4">150</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Approved</h5>
                                <p class="card-text display-4">120</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card card bg-warning text-dark">
                            <div class="card-body">
                                <h5 class="card-title">Pending</h5>
                                <p class="card-text display-4">25</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">Rejected</h5>
                                <p class="card-text display-4">5</p>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Recent Leave Requests -->
                <!-- <h2 class="mb-3">Recent Leave Requests</h2>
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
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>Vacation</td>
                                <td>2023-06-15</td>
                                <td>2023-06-20</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-success">Approve</button>
                                    <button class="btn btn-sm btn-danger">Reject</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>Sick Leave</td>
                                <td>2023-06-18</td>
                                <td>2023-06-19</td>
                                <td><span class="badge bg-success">Approved</span></td>
                                <td>
                                    <button class="btn btn-sm btn-secondary">View</button>
                                </td>
                            </tr> -->
                            <!-- Add more rows as needed -->
                        <!-- </tbody>
                    </table>
                </div> -->

                <!-- New Leave Request Form -->
                <h2 class="mb-3">Submit New Leave Request</h2>
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="employeeName" class="form-label">Employee Name</label>
                            <input type="text" class="form-control" id="employeeName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="leaveType" class="form-label">Leave Type</label>
                            <select class="form-select" id="leaveType" required>
                                <option value="">Choose...</option>
                                <option>Vacation</option>
                                <option>Sick Leave</option>
                                <option>Personal Leave</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" required>
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control" id="reason" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>