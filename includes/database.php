<?php
require_once "config.php"; // Include config file

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    die(json_encode(array('status' => 'error', 'message' => 'Database connection failed')));
}
