<?php
require_once 'back/assign_hall_back.php';

if (isset($_POST['batch_id'])) {
    $batch_id = $_POST['batch_id'];
    $halls = getHallsByBatch($batch_id);

    // Return the halls as JSON response
    header('Content-Type: application/json');
    echo json_encode($halls);
}
?>
