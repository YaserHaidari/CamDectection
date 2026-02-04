<?php
// Connect to the database
$conn = mysqli_connect('feenix-mariadb.swin.edu.au', 's104608220', '010104', 's104608220_db');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the ID from the request URL
$requestUri = $_SERVER['REQUEST_URI'];
$id = end(explode('/', $requestUri));

// Debugging code
error_log("Request URI: $requestUri");
error_log("Extracted ID: $id");

// Delete the record from the database
$sql = "DELETE FROM `Camlocations` WHERE `id` = ?";

// Prepare and bind parameters
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);

// Execute query
if (mysqli_stmt_execute($stmt)) {
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    echo json_encode(['affected_rows' => $affected_rows]);
} else {
    echo json_encode(['error' => 'Failed to delete record']);
}

// Close statement
mysqli_stmt_close($stmt);

// Close connection
mysqli_close($conn);
?>