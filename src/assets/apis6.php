<?php
// Connect to the database
$conn = mysqli_connect('feenix-mariadb.swin.edu.au', 's104608220', '010104', 's104608220_db');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the ID from the request URL
$id = end(explode('/', $_SERVER['REQUEST_URI']));

// Get the request body
$input = json_decode(file_get_contents('php://input'), true);

// Update the 'Suburb' field in the database
if (isset($input['Suburb'])) {
    $Suburb = $input['Suburb'];
    $sql = "UPDATE `Camlocations` SET `Suburb` = ? WHERE `id` = ?";
    
    // Prepare and bind parameters
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $Suburb, $id);
    
    // Execute query
    if (mysqli_stmt_execute($stmt)) {
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        echo json_encode(['affected_rows' => $affected_rows]);
    } else {
        echo json_encode(['error' => 'Failed to update Suburb']);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Invalid input data']);
}

// Close connection
mysqli_close($conn);
?>