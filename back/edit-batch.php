<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batchId = $_POST['batchId'];
    $batchName = $_POST['batchName'];
    $batchCount = $_POST['batchCount'];
    $batchSchool = $_POST['batchSchool'];

    // Check for duplicate batch names
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM batches WHERE name = ? AND id != ?");
    $checkStmt->bind_param("si", $batchName, $batchId);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Batch name already exists.']);
        exit; // Stop further execution
    }

    // Prepare and execute update statement
    $stmt = $conn->prepare("UPDATE batches SET name = ?, student_count = ?, school = ? WHERE id = ?");
    $stmt->bind_param("sssi", $batchName, $batchCount, $batchSchool, $batchId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Batch updated successfully', 'batch' => ['id' => $batchId, 'batch_name' => $batchName, 'count' => $batchCount, 'school' => $batchSchool]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update batch']);
    }

    $stmt->close();
}
?>
