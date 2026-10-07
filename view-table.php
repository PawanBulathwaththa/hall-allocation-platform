<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Hall Management System NIBM Kandy</title>
    <link rel="icon" type="image/x-icon" href="src/assets/img2/nibmicon.png" />
    <link href="layouts/collapsible-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="layouts/collapsible-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="layouts/collapsible-menu/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="layouts/collapsible-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="layouts/collapsible-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="src/plugins/src/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="src/assets/css/light/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link href="src/assets/css/dark/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="src/plugins/src/noUiSlider/nouislider.min.css" rel="stylesheet" type="text/css">
    <!-- END THEME GLOBAL STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->

    <link href="src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">


    <link href="src/plugins/css/dark/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">

</head>

<body class="layout-boxed alt-menu">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->
    <!-- BEGIN CONTENT AREA -->

    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="row layout-top-spacing">
                <h3>View Current Allocations</h3>
                <div id="range_calendar" class="col-lg-12 col-12 layout-spacing mt-2">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area">
                            <!-- Form to select date and submit -->
                            <form method="POST" action="">
                                <div class="form-group mb-4">
                                    <input name="selected_date" class="form-control" type="date" placeholder="Select Date.." required>
                                </div>
                                <button type="submit" class="btn btn-success mb-2 me-4">View</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Table to display allocations -->
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <table id="zero-config" class="table dt-table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Batch</th>
                                    <th>Hall</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'includes/connection.php'; // Ensure the correct path if needed

                                // Check if form is submitted and date is set
                                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_date'])) {
                                    $selectedDate = $_POST['selected_date'];

                                    // Prepare the SQL statement with JOINs
                                    $stmt = $conn->prepare("
                                        SELECT b.name AS batch_name, h.name AS hall_name 
                                        FROM allocations a
                                        JOIN batches b ON a.batch_id = b.id
                                        JOIN halls h ON a.hall_id = h.id
                                        WHERE a.allocation_date = ?
                                    ");
                                    $stmt->bind_param("s", $selectedDate);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    // Display data in the table
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['batch_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['hall_name']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No allocations found for this date.</td></tr>";
                                    }

                                    // Close the statement
                                    $stmt->close();
                                }

                                // Close the database connection

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Display lab allocations -->
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing mt-4">
                    <div class="widget-content widget-content-area br-8">
                        <h4>Lab Allocations</h4>
                        <table id="lab-config" class="table dt-table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Lab Name</th>
                                    <th>Batch Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_date'])) {
                                    $selectedDate = $_POST['selected_date'];

                                    // Query for lab allocations without joins
                                    $stmtLab = $conn->prepare("
                        SELECT lab_name, batch_name
                        FROM lab_allocations
                        WHERE date = ?
                    ");
                                    $stmtLab->bind_param("s", $selectedDate);
                                    $stmtLab->execute();
                                    $resultLab = $stmtLab->get_result();

                                    if ($resultLab->num_rows > 0) {
                                        while ($rowLab = $resultLab->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($rowLab['lab_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($rowLab['batch_name']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No lab allocations found for this date.</td></tr>";
                                    }
                                    $stmtLab->close();
                                }

                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- BEGIN FOOTER -->
    <div class="footer-wrapper mt-0">
        <div class="footer-section f-section-1">
            <p class="">Copyright © <span class="dynamic-year">2024</span> <a target="_blank" href="https://designreset.com/equation/">DesignReset</a>, All rights reserved.</p>
        </div>
        <div class="footer-section f-section-2">
            <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg></p>
        </div>
    </div>
    <!-- END FOOTER -->
    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/collapsible-menu/app.js"></script>
    <script src="src/plugins/src/highlight/highlight.pack.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="src/assets/js/scrollspyNav.js"></script>
    <script src="src/plugins/src/flatpickr/flatpickr.js"></script>

    <script src="src/plugins/src/flatpickr/custom-flatpickr.js"></script>

    <script src="src/assets/js/datepicker.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

</body>

</html>