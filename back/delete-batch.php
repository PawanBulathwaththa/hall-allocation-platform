<?php
include('connection.php');

// Handle batch deletion request
if (isset($_POST['delete_batch'])) {
    $id = $_POST['delete_batch'];

    // Prepare the DELETE query
    $stmt = $conn->prepare("DELETE FROM batches WHERE id = ?");
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'Batch deleted successfully.']);
    } else {
        // Return error response
        echo json_encode(['status' => 'error', 'message' => 'Error deleting batch.']);
    }

    $stmt->close();
    exit(); // Ensure the script stops after handling the AJAX request
}
?>
