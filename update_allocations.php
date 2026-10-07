<?php
include 'connection.php'; // Include your connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allocationId = $_POST['allocationId'];
    $hallName = $_POST['hall_name'];

    // Update the hall name in your database
    $stmt = $conn->prepare("UPDATE allocations SET hall_name = ? WHERE id = ?");
    $stmt->bind_param("si", $hallName, $allocationId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}
?>
