<?php
session_start();
include 'connection.php'; // Adjust the path as necessary

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query
    $query = "SELECT * FROM lecturer WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $email, $password); // 'ss' means both parameters are strings
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the lecturer exists
    if ($result->num_rows > 0) {
        // Fetch the lecturer data
        $lecturer = $result->fetch_assoc();
        
        // Store lecturer information in session
        $_SESSION['lecturer_id'] = $lecturer['id']; // Adjust according to your lecturer table
        $_SESSION['lecturer_email'] = $lecturer['email'];
        $_SESSION['lecturer_name'] = $lecturer['lecturer_name']; // Assuming you have a 'name' column
    
        // Redirect to the lecturer dashboard
        header("Location: ../../lecturer-dashboard.php");
        exit();
    
    
    } else {
        // Invalid login credentials
        $error_message = 'Invalid email or password. Please try again.';
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Lecturer Log-in</title>
    <link rel="icon" type="image/x-icon" href="../../src/assets/img2/nibmicon.png"/>
    <link href="../../layouts/collapsible-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../../layouts/collapsible-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../../layouts/collapsible-menu/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    
    <link href="../../layouts/collapsible-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../../src/assets/css/light/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />
    
    <link href="../../layouts/collapsible-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../../src/assets/css/dark/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    
</head>
<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">
    
            <div class="row">
    
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
    
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h2>Sign In</h2>
                                    <p>Enter your email and password to log in to your lecturer account</p>
                                </div>

                                <!-- Display error message if login fails -->
                                <?php if (isset($error_message)): ?>
                                <div class="">
                                    <?= $error_message; ?>
                                </div>
                                <?php endif; ?>

                                <form method="POST" action="">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <button class="btn btn-secondary w-100" type="submit">SIGN IN</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="mb-0">Don't have an account? <a href="../../includes/home-page-includes/lecturer-signup.php" class="text-warning">Sign Up</a></p>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>

    </div>
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->


</body>
</html>
