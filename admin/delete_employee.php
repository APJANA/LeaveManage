<?php
include("../includes/autoload.php");

// Establish database connection


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the EmployeeID is provided via GET
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']); // Ensure it's an integer to prevent SQL injection

    // Start transaction
    $conn->begin_transaction();

    try {
        // Step 1: Delete related leave requests first
        $query = "DELETE FROM `leaverequests` WHERE `EmployeeID` = $employee_id";
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Failed to delete from `leaverequests`: " . mysqli_error($conn));
        }

        // Step 2: Delete the employee from the `employees` table
        $query = "DELETE FROM `employees` WHERE `EmployeeID` = $employee_id";
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Failed to delete from `employees`: " . mysqli_error($conn));
        }

        // Commit the transaction
        $conn->commit();

        // Redirect back to employee list or success page
        header("Location: manage_staff.php?success=1");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo "Error occurred: " . $e->getMessage();
    }
} else {
    header("Location: manage_staff.php?error=1");
    exit();
}
