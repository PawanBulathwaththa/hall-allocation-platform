<?php
session_start();
include('connection.php'); // Include your DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    

    // Query the database for the admin user
    $sql = "SELECT * FROM admin WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Admin credentials are correct, create session
        $admin = $result->fetch_assoc();
        
        // Storing admin info in session
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];

        // Redirect to the admin dashboard
        header("Location: ../admin-dashboard.php");
    } else {
        // Incorrect login
        echo "Invalid email or password";
    }

    $stmt->close();
    $conn->close();
}
?>
