<?php
// index.php
require_once 'connection.php';

// Function to check hall availability
function getAvailableHall($conn, $studentCount, $date, $timeSlot)
{
    $sql = "SELECT h.* FROM halls h 
            WHERE h.capacity >= ? 
            AND h.id NOT IN (
                SELECT hall_id FROM allocations 
                WHERE allocation_date = ? AND time_slot = ?
            ) 
            ORDER BY h.capacity ASC 
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $studentCount, $date, $timeSlot);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function to check if batch already has allocation on the same day
function checkBatchAllocation($conn, $batchId, $date)
{
    $sql = "SELECT a.*, h.name as hall_name, time_slot 
            FROM allocations a 
            JOIN halls h ON a.hall_id = h.id 
            WHERE batch_id = ? AND allocation_date = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $batchId, $date);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function to update hall allocation
function updateHallAllocation($conn, $allocationId, $hallId)
{
    $sql = "UPDATE allocations SET hall_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $hallId, $allocationId);
    return $stmt->execute();
}

// Function to delete hall allocation
function deleteHallAllocation($conn, $allocationId)
{
    $sql = "DELETE FROM allocations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $allocationId);
    return $stmt->execute();
}


// Function to check hall availability
function isHallAllocated($conn, $hallId, $date, $timeSlot)
{
    $sql = "SELECT 1 FROM allocations WHERE hall_id = ? AND allocation_date = ? AND time_slot = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $hallId, $date, $timeSlot);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

// Set a specific date and time slot for checking availability
$date = $_POST['date'] ?? date('Y-m-d'); // Replace with the desired date or selected date
$timeSlot = $_POST['time_slot'] ?? "09:30-15:30"; // Replace with the desired time slot or selected slot




// Process allocation request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['allocate'])) {
    $batchId = $_POST['batch'];
    $date = $_POST['date'];
    $timeSlot = $_POST['time_slot'];

    // Check if batch already has allocation on the same day
    $existingAllocation = checkBatchAllocation($conn, $batchId, $date);

    if ($existingAllocation) {
        $error = "This batch already has a lecture scheduled in Hall {$existingAllocation['hall_name']} at {$existingAllocation['time_slot']} on this day!";
    } else {
        // Get batch details
        $stmt = $conn->prepare("SELECT student_count, name FROM batches WHERE id = ?");
        $stmt->bind_param("i", $batchId);
        $stmt->execute();
        $batchData = $stmt->get_result()->fetch_assoc();

        // Find suitable hall
        $availableHall = getAvailableHall($conn, $batchData['student_count'], $date, $timeSlot);

        if ($availableHall) {
            // Check if student count exceeds hall capacity
            if ($batchData['student_count'] > $availableHall['capacity']) {
                $error = "Hall {$availableHall['name']} capacity ({$availableHall['capacity']}) is less than the batch size ({$batchData['student_count']})!";
            } else {
                // Allocate hall
                $stmt = $conn->prepare("INSERT INTO allocations (hall_id, batch_id, allocation_date, time_slot) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiss", $availableHall['id'], $batchId, $date, $timeSlot);

                if ($stmt->execute()) {
                    $success = "Hall {$availableHall['name']} allocated successfully for batch {$batchData['name']}!";
                } else {
                    $error = "Error allocating hall: " . $conn->error;
                }
            }
        } else {
            // Check if any hall is allocated for this time slot
            $stmt = $conn->prepare("SELECT h.name, b.name as batch_name 
                                  FROM allocations a 
                                  JOIN halls h ON a.hall_id = h.id 
                                  JOIN batches b ON a.batch_id = b.id 
                                  WHERE a.allocation_date = ? AND a.time_slot = ?");
            $stmt->bind_param("ss", $date, $timeSlot);
            $stmt->execute();
            $conflictingAllocation = $stmt->get_result()->fetch_assoc();

            if ($conflictingAllocation) {
                $error = "Hall {$conflictingAllocation['name']} is already allocated to batch {$conflictingAllocation['batch_name']} at this time!";
            } else {
                $error = "No suitable hall available for this batch size ({$batchData['student_count']} students) at the selected time!";
            }
        }
    }
}

// Process allocation request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if it's an edit request
    if (isset($_POST['allocation_id']) && isset($_POST['hall_id'])) {
        $allocationId = $_POST['allocation_id'];
        $hallId = $_POST['hall_id'];

        if (updateHallAllocation($conn, $allocationId, $hallId)) {
            $success = "Hall updated successfully!";
        } else {
            $error = "Error updating hall: " . $conn->error;
        }
    } elseif (isset($_POST['allocate'])) {
        // Existing allocation request handling code...
    }
}

// Process allocation request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if it's a delete request
    if (isset($_POST['delete_allocation_id'])) {
        $allocationId = $_POST['delete_allocation_id'];

        if (deleteHallAllocation($conn, $allocationId)) {
            $success = "Hall allocation deleted successfully!";
        } else {
            $error = "Error deleting hall allocation: " . $conn->error;
        }
    } elseif (isset($_POST['allocation_id']) && isset($_POST['hall_id'])) {
        // Existing edit request handling code...
    } elseif (isset($_POST['allocate'])) {
        // Existing allocation request handling code...
    }
}



// Get halls and batches for dropdowns
$halls = $conn->query("SELECT * FROM halls ORDER BY capacity");
$batches = $conn->query("SELECT * FROM batches ORDER BY name");

// Get current allocations with more detailed information
$allocations = $conn->query("
    SELECT a.*, h.name as hall_name, h.capacity as hall_capacity,
           b.name as batch_name, b.student_count,
           CASE 
               WHEN b.student_count > h.capacity THEN 1 
               ELSE 0 
           END as capacity_exceeded
    FROM allocations a
    JOIN halls h ON a.hall_id = h.id
    JOIN batches b ON a.batch_id = b.id
    WHERE a.allocation_date >= CURRENT_DATE()
    ORDER BY a.allocation_date, a.time_slot
");


?>
<div id="content" class="main-content">
    <div class="container-xxl">
        <div class="">
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">

                    </ol>
                </nav>
            </div>
            <div class="col-lg-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12"><br>
                                <h4 class="mb-4">Allocate Hall</h4>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="widget-content widget-content-area">
                        <form method="POST">
                            <div class="form-group mb-4">
                                <label for="exampleFormControlSelect1">Select Batch</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="batch" required>
                                    <option value="">Select Batch</option>
                                    <?php while ($batch = $batches->fetch_assoc()): ?>
                                        <option value="<?php echo $batch['id']; ?>">
                                            <?php echo $batch['name']; ?>
                                            (<?php echo $batch['student_count']; ?> students)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label for="exampleFormControlSelect1">Select Date</label>
                                <input id="rangeCalendarFlatpickr"
                                    class="form-control flatpickr flatpickr-input" type="date" name="date"
                                    min="<?php echo date('Y-m-d'); ?>"
                                    required placeholder="Select Date..">
                            </div>

                            <div class="form-group mb-4">
                                <label for="exampleFormControlSelect1">Select Time Slot:</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="time_slot" required>
                                    <option value="">Select Time-slot</option>
                                    <option value="09:30-15:30">09:30 - 15:30</option>
                                    <option value="09:30-12:30">09:30 - 12:30</option>
                                    <option value="12:30-15:30">12:30 - 15:30</option>
                                </select>
                            </div>
                            <button type="submit" name="allocate" class="btn btn-info mb-2 me-4">Allocate Hall</button>
                        </form>
                    </div>

                    <div class="row layout-top-spacing">

                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4 class="mt-4">Current Allocations</h4>
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
                                                        style="width: 30px;">Hall</th>
                                                    <th class="sorting" tabindex="0" aria-controls="zero-config"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Age: activate to sort column ascending"
                                                        style="width: 30px;">Utilization</th>
                                                    <th class="sorting" tabindex="0" aria-controls="zero-config"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Age: activate to sort column ascending"
                                                        style="width: 30px;">Edit</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php while ($allocation = $allocations->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo date('D, d M Y', strtotime($allocation['allocation_date'])); ?></td>
                                                        <td><?php echo $allocation['time_slot']; ?></td>
                                                        <td><?php echo $allocation['batch_name']; ?></td>
                                                        <td><?php echo $allocation['hall_name']; ?></td>
                                                        <td class="utilization <?php echo $allocation['capacity_exceeded'] ? 'capacity-warning' : ''; ?>">
                                                            <?php
                                                            $utilization = round(($allocation['student_count'] / $allocation['hall_capacity']) * 100);
                                                            echo $allocation['student_count'] . '/' . $allocation['hall_capacity'];
                                                            echo " ({$utilization}%)";
                                                            if ($allocation['capacity_exceeded']) {
                                                                echo ' <span class="capacity-warning">⚠️ Over Capacity</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <form method="POST" style="display:inline;">
                                                                <input type="hidden" name="delete_allocation_id" value="<?php echo $allocation['id']; ?>">
                                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                            </form>
                                                            <button class="btn btn-primary btn-sm edit-button" data-allocation-id="<?php echo $allocation['id']; ?>" data-hall-id="<?php echo $allocation['hall_id']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>



                                        </table>
                                    </div>
                                </div>
                                <a href="./view-table.php" target="_blank"><button class="btn btn-secondary mb-2 me-4">View</button></a>

                            </div>
                        </div>
                    </div>

                    <div id="badgeLight" class="col-lg-12 mx-auto layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header text-center">
                                <h4 class="mt-4">Available Halls</h4>
                            </div>

                            <div class="widget-content widget-content-area d-flex flex-wrap justify-content-center">
                                <?php
                                $halls->data_seek(0); // Reset the pointer for the result set
                                while ($hall = $halls->fetch_assoc()):
                                    // Check if the hall is already allocated for the selected date and time slot
                                    $isAllocated = isHallAllocated($conn, $hall['id'], $date, $timeSlot);
                                    $cardClass = $isAllocated ? 'bg-danger text-white' : 'bg-success text-white';
                                ?>
                                    <div class="card m-3 p-4 <?php echo $cardClass; ?>" style="width: 18rem;">
                                        <div class="card-body text-center">
                                            <h5 class="card-title"><?php echo $hall['name']; ?></h5>
                                            <p class="card-text"><?php echo $hall['capacity']; ?> seats</p>
                                            <?php if ($isAllocated): ?>
                                                <p class="badge bg-warning text-dark">Allocated</p>
                                            <?php else: ?>
                                                <p class="badge bg-info text-dark">Available</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Edit Hall Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Hall</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="allocation_id" id="edit-allocation-id">
                    <div class="mb-3">
                        <label for="hall-select" class="form-label">Select Hall</label>
                        <select class="form-select" id="hall-select" name="hall_id" required>
                            <?php
                            // Reload halls for selection
                            $halls->data_seek(0);
                            while ($hall = $halls->fetch_assoc()): ?>
                                <option value="<?php echo $hall['id']; ?>"><?php echo $hall['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Hall</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function() {
            const allocationId = this.getAttribute('data-allocation-id');
            const hallId = this.getAttribute('data-hall-id');
            document.getElementById('edit-allocation-id').value = allocationId;
            document.getElementById('hall-select').value = hallId;
        });
    });
</script>