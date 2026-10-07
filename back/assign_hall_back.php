<?php
require_once 'includes/connection.php';

// Fetch hall data for dropdown
function getHalls() {
    global $conn;
    $sql = "SELECT id, hall_name, capacity, floor FROM hall";
    $result = $conn->query($sql);
    $halls = [];
    while ($row = $result->fetch_assoc()) {
        $halls[] = $row;
    }
    return $halls;
}

// Fetch batch data for dropdown
function getBatches() {
    global $conn;
    $sql = "SELECT id, batch_name, count FROM batch";
    $result = $conn->query($sql);
    $batches = [];
    while ($row = $result->fetch_assoc()) {
        // Concatenate the batch name with the count
        $batch_with_count = $row['batch_name'] . " (" . $row['count'] . ")";
        $batches[] = [
            'id' => $row['id'],
            'batch_name' => $batch_with_count,
        ];
    }
    return $batches;
}


// Update assign_hall table
function assignHall($hall_id, $batch_id, $date, $hours) {
    global $conn;
    
    // Get hall and batch details based on IDs
    $hall_query = "SELECT hall_name, capacity, floor FROM hall WHERE id = $hall_id";
    $hall_result = $conn->query($hall_query);
    $hall_data = $hall_result->fetch_assoc();

    $batch_query = "SELECT batch_name, count FROM batch WHERE id = $batch_id";
    $batch_result = $conn->query($batch_query);
    $batch_data = $batch_result->fetch_assoc();

    // Insert into assign_hall table
    $sql = "INSERT INTO assign_hall (hall_name, capacity, floor, batch_name, count, date, hours) 
            VALUES ('{$hall_data['hall_name']}', '{$hall_data['capacity']}', '{$hall_data['floor']}', 
                    '{$batch_data['batch_name']}', '{$batch_data['count']}', '$date', '$hours')";
    
    if ($conn->query($sql) === TRUE) {
        return "Hall assigned successfully!";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

function getHallsByBatch($batch_id) {
    global $conn;

    // Prepare the query to get the student count for the selected batch
    $batch_query = "SELECT count FROM batch WHERE id = ?";
    $stmt = $conn->prepare($batch_query);
    $stmt->bind_param("i", $batch_id);
    $stmt->execute();
    $batch_result = $stmt->get_result();
    $batch_data = $batch_result->fetch_assoc();
    $batch_count = $batch_data['count'];
    $stmt->close();

    // Prepare the query to fetch halls that have capacity greater than or equal to the batch count
    $hall_query = "SELECT id, hall_name, capacity FROM hall WHERE capacity >= ?";
    $stmt = $conn->prepare($hall_query);
    $stmt->bind_param("i", $batch_count);
    $stmt->execute();
    $hall_result = $stmt->get_result();

    $halls = [];
    while ($row = $hall_result->fetch_assoc()) {
        $halls[] = $row;
    }

    $stmt->close();

    return $halls;
}

function assignHallToBatch($hall_id, $batch_id, $date, $hours) {
    global $conn;

    // Get hall details (hall_name, capacity, floor) based on hall_id
    $hall_query = "SELECT hall_name, capacity, floor FROM hall WHERE id = $hall_id";
    $hall_result = $conn->query($hall_query);
    $hall_data = $hall_result->fetch_assoc();

    // Get batch details (batch_name, count) based on batch_id
    $batch_query = "SELECT batch_name, count FROM batch WHERE id = $batch_id";
    $batch_result = $conn->query($batch_query);
    $batch_data = $batch_result->fetch_assoc();

    // Prepare insert query for assign_data_table
    $insert_query = "INSERT INTO assign_hall (hall_name, capacity, floor, batch_name, count, date, hours)
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare the statement to avoid SQL injection
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param(
        'sississ', 
        $hall_data['hall_name'], 
        $hall_data['capacity'], 
        $hall_data['floor'], 
        $batch_data['batch_name'], 
        $batch_data['count'], 
        $date, 
        $hours
    );
    
    // Execute the statement and check for success
    if ($stmt->execute()) {
        return "Data assigned successfully.";
    } else {
        return "Error: " . $conn->error;
    }
}




?>
