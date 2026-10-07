<?php
include('includes/connection.php'); // Include database connection

// Retrieve the JSON data sent from JavaScript
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['assigned_data']) && !empty($data['date'])) {
    $date = $data['date'];
    $assignedData = $data['assigned_data'];
    
    $query = "INSERT INTO assign_hall (hall_name, capacity, floor, batch_name, count, date, hours) VALUES ";
    $valuesArr = [];

    // Loop through the assigned data to construct the query
    foreach ($assignedData as $assigned) {
        $hall_name = mysqli_real_escape_string($conn, $assigned['hall_name']);
        $capacity = mysqli_real_escape_string($conn, $assigned['capacity']);
        $batch_name = mysqli_real_escape_string($conn, $assigned['batch_name']);
        $count = mysqli_real_escape_string($conn, $assigned['count']);
        $hours = mysqli_real_escape_string($conn, $assigned['hours']);
        $floor = 1; // Assuming floor is 1 for simplicity, adjust as necessary

        $valuesArr[] = "('$hall_name', '$capacity', '$floor', '$batch_name', '$count', '$date', '$hours')";
    }

    // Concatenate the values for the query
    $query .= implode(',', $valuesArr);

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data received.']);
}
?>
