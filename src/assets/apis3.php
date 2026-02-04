<?php
// Connect to the database
$conn = mysqli_connect('feenix-mariadb.swin.edu.au', 's104608220', '010104', 's104608220_db');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the request body
$input = json_decode(file_get_contents('php://input'), true);

// Check if all required fields are set
if (isset($input['Road']) && isset($input['Suburb']) && isset($input['lat']) && isset($input['lng']) && isset($input['Upvote']) && isset($input['DownVote']) && isset($input['Status']) && isset($input['Date'])) {
    $sql = "INSERT INTO `Camlocations` (`Road`, `Suburb`, `lat`, `lng`, `Upvote`, `DownVote`, `Status`, `Date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare and bind parameters
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssddiiss', $input['Road'], $input['Suburb'], $input['lat'], $input['lng'], $input['Upvote'], $input['DownVote'], $input['Status'], $input['Date']);
    
    // Execute query
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => 'Record inserted successfully']);
    } else {
        echo json_encode(['error' => 'Failed to insert record']);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Invalid input data']);
}

// Close connection
mysqli_close($conn);
?>