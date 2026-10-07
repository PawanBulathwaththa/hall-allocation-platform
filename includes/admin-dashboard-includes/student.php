<?php
    include('includes/connection.php'); // Your connection file
?>


<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <table id="zero-config" class="table dt-table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT student_name, email FROM student"; // Assuming your table is 'lecturer' and columns are 'name' and 'email'
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    // Output data for each row
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr><td>" . $row["student_name"]. "</td><td>" . $row["email"]. "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>No students found</td></tr>";
                                }
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php
            include('includes/connection.php'); // Your connection file
        ?>
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0">
                    <div class="row layout-top-spacing">
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                                <!-- Form to delete student -->
                                <form method="POST" action="">
                                    <div class="btn-group  mb-2 me-4" role="group">
                                        <!-- Dropdown to select a student -->
                                        <select name="student_email" id="student_email" class="form-control">
                                            <option value="">Select a Student Email</option>
                                            <?php
                                            // Fetch students from the database
                                            $sql = "SELECT email FROM student"; 
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row["email"] . "'>" . $row["email"] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No students available</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="delete" class="btn btn-danger mb-2 me-4">Delete</button>
                                </form>

                                <?php
                                // Handle form submission for deleting a lecturer
                                if (isset($_POST['delete'])) {
                                    $selected_email = $_POST['student_email'];

                                    if (!empty($selected_email)) {
                                        // SQL to delete the lecturer by email
                                        $delete_sql = "DELETE FROM student WHERE email = '$selected_email'";
                                        
                                        if ($conn->query($delete_sql) === TRUE) {
                                            echo "<p style='color:green;'>Lecturer with email $selected_email has been deleted successfully.</p>";
                                        } else {
                                            echo "<p style='color:red;'>Error deleting lecturer: " . $conn->error . "</p>";
                                        }
                                    } else {
                                        echo "<p style='color:red;'>Please select an email to delete.</p>";
                                    }
                                }

                                $conn->close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

    </div>
</div>

        

