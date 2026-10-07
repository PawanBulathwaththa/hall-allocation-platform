<!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<!-- SweetAlert JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<?php
include 'connection.php'; // Include your database connection

// Set timezone to Sri Lanka (UTC+5:30)
date_default_timezone_set('Asia/Colombo');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the message from the form
    $message = $_POST['message'];

    // Get lecturer name and email from session
    $lecturer_name = $_SESSION['lecturer_name']; // Ensure these session variables are set
    $lecturer_email = $_SESSION['lecturer_email'];

    // Get the current date and time in Sri Lankan time
    $req_date = date('Y-m-d'); // Format: YYYY-MM-DD
    $req_time = date('H:i:s'); // Format: HH:MM:SS

    // Prepare the SQL statement to insert the message with time
    $stmt = $conn->prepare("INSERT INTO lecturer_req (lecturer_name, lecturer_email, message, is_read, req_date, time) VALUES (?, ?, ?, ?, ?, ?)");
    $is_read = 1; // Default value for is_read

    // Bind parameters and execute the statement
    $stmt->bind_param("sssiss", $lecturer_name, $lecturer_email, $message, $is_read, $req_date, $req_time);

    // Check execution and output SweetAlert message
    if ($stmt->execute()) {
        echo "<script>
                swal('Success!', 'Message sent successfully!', 'success');
              </script>";
    } else {
        echo "<script>
                swal('Error!', 'Error sending message. Please try again.', 'error');
              </script>";
    }

    // Close the statement
    $stmt->close();
}
?>

<!-- BEGIN CONTENT AREA -->
<div id="content" class="main-content">
    <div class="container">
        <div class="container">
            <div class="row layout-top-spacing">
                <div id="basic" class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Sent Message</h4>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="">
                                    <form method="post">
                                        <div class="form-group">
                                            <textarea id="message" name="message" class="form-control" rows="4" placeholder="Enter your message here..." required></textarea>
                                        </div>
                                        <button class="btn btn-success btn-rounded mb-2 mt-4" type="submit">Send Message</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
