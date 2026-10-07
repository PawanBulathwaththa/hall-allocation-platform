<?php

// Database configuration
$servername = "localhost";
$username = "root"; // default username in XAMPP
$password = ""; // default password is empty in XAMPP
$dbname = "allocation_platform";

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Retrieve the JSON data sent via AJAX
$data = json_decode(file_get_contents("php://input"), true);

// Debugging - Check if data is received correctly
if (!$data) {
    http_response_code(400); // Bad request
    echo json_encode(["error" => "Invalid or missing input data"]);
    exit;
}

$date = isset($data['date']) ? mysqli_real_escape_string($connection, $data['date']) : null;
$assignments = isset($data['assignments']) ? $data['assignments'] : null;

if (!$date || empty($assignments)) {
    http_response_code(400); // Bad request
    echo json_encode(["error" => "Date or assignments missing"]);
    exit;
}

// Loop through assignments and store them in the assign_hall table
foreach ($assignments as $assignment) {
    // Sanitize input values
    $batch = isset($assignment['batch']) ? mysqli_real_escape_string($connection, $assignment['batch']) : null;
    $hall = isset($assignment['hall']) ? mysqli_real_escape_string($connection, $assignment['hall']) : null;
    $lectureHours = isset($assignment['lectureHours']) ? mysqli_real_escape_string($connection, $assignment['lectureHours']) : null;

    // Validate required fields
    if (!$batch || !$hall || !$lectureHours) {
        http_response_code(400); // Bad request
        echo json_encode(["error" => "Missing batch, hall, or lecture hours in one of the assignments"]);
        exit;
    }

    // Insert into assign_hall table
    $query = "INSERT INTO assign_hall (hall_name, batch_name, date, hours) VALUES ('$hall', '$batch', '$date', '$lectureHours')";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        http_response_code(500); // Internal server error
        echo json_encode(["error" => "Failed to insert data: " . mysqli_error($connection)]);
        exit;
    }
}

// If everything is successful
echo json_encode(["success" => "Assignments updated successfully!"]);

?>
