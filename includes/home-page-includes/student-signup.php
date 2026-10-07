<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Student Sign-Up</title>
    <link rel="icon" type="image/x-icon" href="../../src/assets/img2/nibmicon.png"/>
    <link href="../../layouts/collapsible-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="../../layouts/collapsible-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="../../layouts/collapsible-menu/loader.js"></script>
    
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
    // Include the database connection
    include('../../includes/connection.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $student_name = trim($_POST['student_name']);
        $batch = trim($_POST['batch_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $terms = isset($_POST['terms']) ? 1 : 0;
        $errors = []; // Initialize an array to hold error messages

        // Validate email format to allow only @nibm.lk addresses
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/@student\.nibm\.lk$/", $email)) {
            $errors[] = 'Please enter a valid NIBM email address (example@student.nibm.lk).';
        }

        // Validate password length
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }

        // Insert into database if no errors
        if ($terms == 1 && empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO student (student_name, batch, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $student_name, $batch, $email, $password);

            if ($stmt->execute()) {
                // Show success message
                echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Registration successful! Please Log into your account!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = '../home-page-includes/student-login.php';
                    });
                </script>";
                exit();
            } else {
                $errors[] = 'An error occurred while signing up. Please try again.';
            }
        }
    }
    ?>

    

    <script>
        function validateEmail() {
            const emailField = document.querySelector('input[name="email"]');
            const email = emailField.value;
            const campusEmailPattern = /^[a-zA-Z0-9._%+-]+@student\.nibm\.lk$/;

            if (!campusEmailPattern.test(email)) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please enter a valid NIBM email address (example@student.nibm.lk).',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        }

        function validatePassword() {
            const passwordField = document.querySelector('input[name="password"]');
            const password = passwordField.value;

            if (password.length < 8) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Password must be at least 8 characters long.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        }
    </script>

    <div class="auth-container d-flex">
        <div class="container mx-auto align-self-center">
            <div class="row">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h2 class="mb-4">Student Registration</h2>
                                    <p>Enter user name, batch, NIBM student email and password to register a student account</p>
                                </div>

                                <!-- Display errors if there are any -->
                                <?php if (!empty($errors)): ?>
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <ul>
                                                <?php foreach ($errors as $error): ?>
                                                    <li><?php echo htmlspecialchars($error); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" action="" onsubmit="return validateEmail() && validatePassword()">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">User Name</label>
                                            <input type="text" name="student_name" class="form-control" required>
                                        </div>
                                    </div>
                                    <?php
                                    include('connection.php');
                                    $sql = "SELECT name FROM batches";
                                    $res = $conn->query($sql);
                                    ?>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Batch</label>
                                            <select class="form-select" id="exampleFormControlSelect1" name="batch_name" required>
                                                <option value="">Select batch...</option>
                                                <?php 
                                                if($res->num_rows>0){
                                                    while($row = $res->fetch_assoc()){
                                                        echo "<option value='".$row['name']."'>".$row['name']."</option>";
                                                    }
                                                }
                                                else{
                                                    echo "<option value=''>No Batches Found</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Nibm Student Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" required>
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

                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="mb-0">Already have an account? <a href="../home-page-includes/student-login.php" class="text-warning">Sign in</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../../src/plugins/src/waves/waves.min.js"></script>
    <script src="../../layouts/horizontal-light-menu/app.js"></script>
</body>

</html>
