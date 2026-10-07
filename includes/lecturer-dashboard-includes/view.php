<!-- BEGIN CONTENT AREA -->
<div id="content" class="main-content">
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
                                include 'connection.php'; // Ensure the correct path if needed

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
        <table id="lab-config" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Batch</th>
                    <th>Lab</th>
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
                            echo "<td>" . htmlspecialchars($rowLab['batch_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($rowLab['lab_name']) . "</td>";
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

    