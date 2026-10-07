

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hall = $_POST['name'];
    $capacity = $_POST['capacity'];
    $floor = $_POST['floor'];
    $type = $_POST['hallType'];


    // Check for duplicate batch name
    $checkSql = "SELECT * FROM halls WHERE name = '$hall'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Duplicate batch name found
        echo "
        <script>
        Swal.fire({
          icon: 'warning',
          title: 'Duplicate hall',
          text: 'This hall name already exists. Please choose a different name.'
        });
        </script>";
    } else {
        // No duplicate, proceed with insertion
        $sql = "INSERT INTO halls (name, capacity, floor, type) VALUES ('$hall', '$capacity', '$floor', '$type')";

        if ($conn->query($sql) === TRUE) {
            // Success SweetAlert
            echo "
            <script>
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Hall has been added successfully!'
            }).then(function() {
                window.location = 'admin-dashboard.php?page=manage-halls';
            });
            </script>";
        } else {
            // Error SweetAlert
            echo "
            <script>
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error adding hall: " . $conn->error . "'
            });
            </script>";
        }
    }
}
?>
