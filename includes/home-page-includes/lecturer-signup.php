<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Lecturer Sign-Up</title>
    <link rel="icon" type="image/x-icon" href="../../src/assets/img2/nibmicon.png" />
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

    <!-- Sweet Alert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <?php
    // Include the database connection
    include('../../includes/connection.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $lecturer_name = trim($_POST['lecturer_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $terms = isset($_POST['terms']) ? 1 : 0;

        // Validate NIBM email format
        if (!preg_match("/^[a-zA-Z0-9._%+-]+@nibm\.lk$/", $email)) {
            $errors[] = 'Please enter a valid NIBM email address (example@nibm.lk).';
        }

        // Validate password length (at least 8 characters)
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }

        // Prepare and execute the insert statement if no errors
        if ($terms == 1 && empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO lecturer (lecturer_name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $lecturer_name, $email, $password);

            if ($stmt->execute()) {
                // Use Sweet Alert for success message
                echo '<script>
                    Swal.fire({
                        title: "Success!",
                        text: "You have signed up successfully.",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../home-page-includes/lecturer-login.php";
                        }
                    });
                </script>';
                exit();
            } else {
                $errors[] = 'An error occurred while signing up. Please try again.';
            }
        }
    }
    ?>

    <div class="auth-container d-flex">
        <div class="container mx-auto align-self-center">
            <div class="row">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h2>Sign Up</h2>
                                    <p>Enter your NIBM email and password to register a lecturer account</p>
                                </div>

                                <!-- Display errors if there are any -->
                                <?php if (!empty($errors)): ?>
                                    <script>
                                        Swal.fire({
                                            title: "Error!",
                                            text: "<?php echo htmlspecialchars(implode(' ', $errors)); ?>",
                                            icon: "error",
                                            confirmButtonText: "OK"
                                        });
                                    </script>
                                <?php endif; ?>

                                <form method="POST" action="" onsubmit="return validateForm()">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="lecturer_name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" required
                                                pattern="[a-zA-Z0-9._%+-]+@nibm\.lk$"
                                                title="Please enter a valid NIBM email (example@nibm.lk)">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" id="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="terms" id="form-check-default" required>
                                                <label class="form-check-label" for="form-check-default">
                                                    I agree to the <a href="javascript:void(0);" class="text-primary">Terms and Conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <button type="submit" class="btn btn-secondary w-100">SIGN UP</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="col-12 mb-4">
                                    <div class="">
                                        <div class="seperator">
                                            <hr>
                                            <div class="seperator-text"> </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="mb-0">Already have an account? <a href="../home-page-includes/lecturer-login.php" class="text-warning">Sign in</a></p>
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
    <script src="../../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../../src/plugins/src/waves/waves.min.js"></script>
    <script src="../../layouts/horizontal-light-menu/app.js"></script>

    <!-- Custom script for password validation -->
    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            
            if (password.length < 8) {
                Swal.fire({
                    title: "Error!",
                    text: "Password must be at least 8 characters long.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>

</body>

</html>
