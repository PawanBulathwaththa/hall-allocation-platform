<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $batch = $_POST['name'];
    $count = $_POST['count'];
    $school = $_POST['school'];

    // Check for duplicate batch name
    $checkSql = "SELECT * FROM batches WHERE name = '$batch'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Duplicate batch name found
        echo "
        <script>
        Swal.fire({
          icon: 'warning',
          title: 'Duplicate Batch',
          text: 'This batch name already exists. Please choose a different name.'
        });
        </script>";
    } else {
        // No duplicate, proceed with insertion
        $sql = "INSERT INTO batches (name, student_count, school) VALUES ('$batch', '$count', '$school')";

        if ($conn->query($sql) === TRUE) {
            // Success SweetAlert
            echo "
            <script>
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Batch has been added successfully!'
            }).then(function() {
                window.location = 'admin-dashboard.php?page=manage-batches';
            });
            </script>";
        } else {
            // Error SweetAlert
            echo "
            <script>
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error adding batch: " . $conn->error . "'
            });
            </script>";
        }
    }
}
?>
