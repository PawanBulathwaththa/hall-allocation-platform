<?php
// Include database connection
include('connection.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data from the AJAX request
    $hallId = isset($_POST['hallId']) ? intval($_POST['hallId']) : 0;
    $hallName = isset($_POST['hallName']) ? trim($_POST['hallName']) : '';
    $hallCapacity = isset($_POST['hallCapacity']) ? intval($_POST['hallCapacity']) : 0;
    $hallFloor = isset($_POST['hallFloor']) ? trim($_POST['hallFloor']) : '';

    // Validate the data
    if ($hallId > 0 && !empty($hallName) && $hallCapacity > 0 && !empty($hallFloor)) {
        // Prepare the SQL statement to update the hall
        $stmt = $conn->prepare("UPDATE halls SET name = ?, capacity = ?, floor = ? WHERE id = ?");
        $stmt->bind_param("sisi", $hallName, $hallCapacity, $hallFloor, $hallId);

        // Execute the query and check if the update was successful
        if ($stmt->execute()) {
            // Fetch the updated hall details to send back as JSON
            $response = [
                'status' => 'success',
                'message' => 'Hall updated successfully!',
                'hall' => [
                    'id' => $hallId,
                    'hall_name' => $hallName,
                    'capacity' => $hallCapacity,
                    'floor' => $hallFloor
                ]
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to update the hall. Please try again.'
            ];
        }

        // Close the statement
        $stmt->close();
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Invalid input. Please check your form data.'
        ];
    }

    // Return the response as JSON
    echo json_encode($response);

    // Close the database connection
    $conn->close();
} else {
    // If the request is not POST, return an error response
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
