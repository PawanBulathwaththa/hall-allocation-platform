<?php
include('includes/connection.php'); // Database connection

if (isset($_POST['id'])) {
    $lecturer_id = $_POST['id'];

    // Prepare the delete query
    $query = "DELETE FROM lecturer WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $lecturer_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
