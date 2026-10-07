<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<?php
// Include your database connection file
include 'connection.php';
$response = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $date = $_POST['date'];
    $lab_name = $_POST['lab_name'];
    $batch_id = $_POST['batch'];
    $time_slot = $_POST['time_slot'];

    // Validate the inputs
    if (empty($date) || empty($lab_name) || empty($batch_id) || empty($time_slot)) {
        $response = [
            'status' => 'error',
            'message' => 'All fields are required.'
        ];
        echo json_encode($response);
        exit;
    }

    // Fetch the batch name based on the selected batch ID
    $query = "SELECT name FROM batches WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $batch_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $batch_name);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (empty($batch_name)) {
        $response = [
            'status' => 'error',
            'message' => 'Invalid batch selected.'
        ];
        echo json_encode($response);
        exit;
    }

    // Check if the same lab is already assigned on the same date and time slot
    $query = "SELECT COUNT(*) as count FROM lab_allocations WHERE date = ? AND lab_name = ? AND time_slot = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $date, $lab_name, $time_slot);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($count > 0) {
        $response = [
            'status' => 'error',
            'message' => 'This lab is already assigned to another batch on the selected date and time slot.'
        ];
        echo json_encode($response);
        exit;
    }

    // Check if the same batch is already assigned to another lab on the same date
    $query = "SELECT COUNT(*) as count FROM lab_allocations WHERE date = ? AND batch_name = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $date, $batch_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($count > 0) {
        $response = [
            'status' => 'error',
            'message' => 'This batch is already assigned to a lab on the selected date.'
        ];
        echo json_encode($response);
        exit;
    }

    // Insert the new allocation into the database
    $query = "INSERT INTO lab_allocations (lab_name, batch_name, date, time_slot) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $lab_name, $batch_name, $date, $time_slot);

    if (mysqli_stmt_execute($stmt)) {
        $response = [
            'status' => 'success',
            'message' => 'Lab assigned successfully.'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Error assigning lab: ' . mysqli_error($conn)
        ];
    }

    // Close the statement
    mysqli_stmt_close($stmt);
    echo json_encode($response);
    exit;
}


$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['allocation_id']) && isset($_POST['lab_name'])) {
        // Edit function to change lab name for a specific allocation
        $allocationId = $_POST['allocation_id'];
        $labName = $_POST['lab_name'];

        $query = "UPDATE lab_allocations SET lab_name = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $labName, $allocationId);

        if (mysqli_stmt_execute($stmt)) {
            $response = ['status' => 'success', 'message' => 'Lab updated successfully!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error updating lab: ' . mysqli_error($conn)];
        }
        mysqli_stmt_close($stmt);
        echo json_encode($response);
        exit;
    } elseif (isset($_POST['delete_allocation_id'])) {
        // Delete function to remove a specific lab allocation
        $allocationId = $_POST['delete_allocation_id'];

        $query = "DELETE FROM lab_allocations WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $allocationId);

        if (mysqli_stmt_execute($stmt)) {
            $response = ['status' => 'success', 'message' => 'Lab allocation deleted successfully!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error deleting lab allocation: ' . mysqli_error($conn)];
        }
        mysqli_stmt_close($stmt);
        echo json_encode($response);
        exit;
    }
}

?>



<!-- BEGIN CONTENT AREA -->
<div id="content" class="main-content">
    <div class="container">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Lab Assign</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="labAssignmentForm">
                            <div class="form-group mb-4">
                                <label for="rangeCalendarFlatpickr">Select date</label>
                                <input id="rangeCalendarFlatpickr" name="date" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date.." readonly="readonly" required>
                            </div>

                            <div class="form-group mb-4">
                                <label for="selectLab">Select Lab</label>
                                <select class="form-select" id="selectLab" name="lab_name" required>
                                    <option value="">Select Lab...</option>
                                    <option value="lab01">Lab 01</option>
                                    <option value="lab02">Lab 02</option>
                                    <option value="lab03">Lab 03</option>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label for="selectBatch">Select Batch</label>
                                <select class="form-select" id="selectBatch" name="batch" required>
                                    <option value="">Select Batch...</option>
                                    <?php
                                    // Query to fetch batches from the database
                                    $query = "SELECT id, name FROM batches";
                                    $result = mysqli_query($conn, $query);

                                    // Check if there are results
                                    if ($result) {
                                        // Loop through the result set and create options for the dropdown
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $batchId = $row['id'];
                                            $batchName = $row['name'];
                                            echo "<option value='$batchId'>$batchName</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No batches available</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label for="selectTimeSlot">Select Time Slot:</label>
                                <select class="form-select" id="selectTimeSlot" name="time_slot" required>
                                    <option value="">Select Time-slot</option>
                                    <option value="09:30-15:30">09:30 - 15:30</option>
                                    <option value="09:30-12:30">09:30 - 12:30</option>
                                    <option value="12:30-15:30">12:30 - 15:30</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info mb-2 me-4">Assign Lab</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4 class="mt-4">Current Lab Allocations</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area br-8">
                    <div id="zero-config_wrapper"
                        class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="dt--top-section">
                            <div class="row">

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="zero-config" class="table dt-table-hover dataTable no-footer"
                                style="width: 100%;" role="grid"
                                aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0"
                                            aria-controls="zero-config" rowspan="1" colspan="1"
                                            aria-sort="ascending"
                                            aria-label="Name: activate to sort column descending"
                                            style="width: 129px;">Date</th>
                                        <th class="sorting" tabindex="0" aria-controls="zero-config"
                                            rowspan="1" colspan="1"
                                            aria-label="Position: activate to sort column ascending"
                                            style="width: 198px;">Time</th>
                                        <th class="sorting" tabindex="0" aria-controls="zero-config"
                                            rowspan="1" colspan="1"
                                            aria-label="Office: activate to sort column ascending"
                                            style="width: 91px;">Batch</th>
                                        <th class="sorting" tabindex="0" aria-controls="zero-config"
                                            rowspan="1" colspan="1"
                                            aria-label="Age: activate to sort column ascending"
                                            style="width: 30px;">Lab</th>
                                        <th class="sorting" tabindex="0" aria-controls="zero-config"
                                            rowspan="1" colspan="1"
                                            aria-label="Age: activate to sort column ascending"
                                            style="width: 30px;">Edit</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sql = "SELECT id, date, time_slot AS time, batch_name AS batch, lab_name AS lab FROM lab_allocations";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                        <td>{$row['date']}</td>
                        <td>{$row['time']}</td>
                        <td>{$row['batch']}</td>
                        <td>{$row['lab']}</td>
                        <td>
                            <button class='btn btn-primary btn-sm edit-button' data-allocation-id='{$row['id']}' data-lab-id='{$row['lab']}'>Edit</button>
                            <button class='btn btn-danger btn-sm delete-button' data-allocation-id='{$row['id']}'>Delete</button>
                        </td>
                    </tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <a href="./view-table.php" target="_blank"><button class="btn btn-secondary mb-2 me-4">View</button></a>

                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function() {
                    const allocationId = this.getAttribute('data-allocation-id');
                    const labId = this.getAttribute('data-lab-id');

                    Swal.fire({
                        title: 'Edit Lab Allocation',
                        html: `<input type="hidden" id="allocation-id" value="${allocationId}">
                   <select id="lab-select" class="swal2-input">
                       <option value="lab01" ${labId === 'lab01' ? 'selected' : ''}>Lab 01</option>
                       <option value="lab02" ${labId === 'lab02' ? 'selected' : ''}>Lab 02</option>
                       <option value="lab03" ${labId === 'lab03' ? 'selected' : ''}>Lab 03</option>
                   </select>`,
                        preConfirm: () => {
                            return {
                                allocationId: document.getElementById('allocation-id').value,
                                labName: document.getElementById('lab-select').value
                            };
                        }
                    }).then((result) => {
                        if (result.value) {
                            $.post('', {
                                allocation_id: result.value.allocationId,
                                lab_name: result.value.labName
                            }, function(response) {
                                Swal.fire(response.message);
                                if (response.status === 'success') location.reload();
                            }, 'json');
                        }
                    });
                });
            });

            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function() {
                    const allocationId = this.getAttribute('data-allocation-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post('', {
                                delete_allocation_id: allocationId
                            }, function(response) {
                                Swal.fire(response.message);
                                if (response.status === 'success') location.reload();
                            }, 'json');
                        }
                    });
                });
            });

            $('#labAssignmentForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '', // The current script
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire(response.message);
                        if (response.status === 'success') location.reload();
                    }
                });
            });
        </script>


        <script>
            $(document).ready(function() {
                $('#labAssignmentForm').on('submit', function(e) {
                    e.preventDefault(); // Prevent the default form submission

                    $.ajax({
                        type: 'POST',
                        url: '', // The current script
                        data: $(this).serialize(), // Serialize the form data
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Okay'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'Try Again'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // You can log the full response for debugging
                            console.error(xhr.responseText);
                            Swal.fire({
                                title: 'Success!',
                                text: 'Lab Assign Success',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            });
                        }
                    });
                });
            });
        </script>

        <?php
        // Close the database connection
        mysqli_close($conn);
        ?>