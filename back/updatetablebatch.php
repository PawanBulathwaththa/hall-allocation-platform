<?php
include('includes/connection.php');

// Fetch and display the batches
$stmt = $conn->prepare("SELECT * FROM batches");
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h3>Batches</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Batch Name</th>
                <th>Count</th>
                <th>School</th>
                <th class='text-center'>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr id="batch-row-<?php echo $row['id']; ?>">
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['student_count']; ?></td>
                    <td><?php echo $row['school']; ?></td>
                    <td class='text-center'>
                        <a href='#' onclick="openEditModal('<?php echo $row['id']; ?>', '<?php echo addslashes($row['name']); ?>', '<?php echo $row['student_count']; ?>', '<?php echo addslashes($row['school']); ?>')" class='action-btn btn-edit' title='Edit'>
                            <!-- Edit Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                        </a>
                        <a href='#' onclick="confirmDelete('<?php echo $row['id']; ?>')" class='action-btn btn-delete' title='Delete'>
                            <!-- Delete Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </a>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr><td colspan='4'>No batch found</td></tr>
            <?php
        }

        $stmt->close();
        ?>
        </tbody>
    </table>
</div>

<!-- Edit Batch Modal -->
<div class="modal" id="editBatchModal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Edit Batch</h5>
            <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="editBatchForm">
                <input type="hidden" id="batchId" name="batchId">
                <div class="form-group mb-2">
                    <label for="batchName">Batch Name</label>
                    <input type="text" class="form-control" id="batchName" name="batchName" required>
                </div>
                <div class="form-group mb-2">
                    <label for="batchCount">Count</label>
                    <input type="number" class="form-control" id="batchCount" name="batchCount" required>
                </div>
                <div class="form-group mb-4">
                    <label for="batchSchool">Select School</label>
                    <select class="form-select" name="batchSchool" id="batchSchool">
                        <option value="computing">School of Computing</option>
                        <option value="business">School of Business</option>
                        <option value="languages">School of Languages</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
        </div>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    width: 400px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.close {
    cursor: pointer;
    font-size: 1.5em;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Function to handle the SweetAlert delete confirmation
function confirmDelete(batchId) {
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
            // Send AJAX request to delete the batch
            $.ajax({
                url: 'back/delete-batch.php', // Send to the delete-batch.php file
                method: 'POST',
                data: { delete_batch: batchId }, // Sending batch id for deletion
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Remove the batch row from the table
                        $('#batch-row-' + batchId).remove();
                        Swal.fire('Deleted!', response.message, 'success');
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error: ", xhr.responseText); // Log the response text
                    Swal.fire('Error!', 'An error occurred while deleting the batch: ' + error, 'error');
                }
            });
        }
    });
}


// Function to open the edit modal and populate the form fields
function openEditModal(batchId, batchName, batchCount, batchSchool) {
    $('#batchId').val(batchId);
    $('#batchName').val(batchName);
    $('#batchCount').val(batchCount);
    
    // Set the selected option in the dropdown based on the passed batchSchool value
    $('#batchSchool').val(batchSchool.toLowerCase()); // Assuming the school values are lowercase in the dropdown

    $('#editBatchModal').show(); // Show modal
}


// Function to close the modal
function closeModal() {
    $('#editBatchModal').hide(); // Hide modal
}

// Handle the form submission for editing a batch
$('#editBatchForm').on('submit', function(e) {
    e.preventDefault(); // Prevent the form from submitting the traditional way
    $.ajax({
        url: 'back/edit-batch.php', // Your script to handle the update
        method: 'POST',
        data: $(this).serialize(), // Serialize the form data
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Update the batch row in the table
                $('#batch-row-' + response.batch.id + ' td:nth-child(1)').text(response.batch.batch_name);
                $('#batch-row-' + response.batch.id + ' td:nth-child(2)').text(response.batch.count);
                $('#batch-row-' + response.batch.id + ' td:nth-child(3)').text(response.batch.school);
                $('#editBatchModal').hide(); // Hide modal
                Swal.fire('Updated!', response.message, 'success');
            } else {
                Swal.fire('Error!', response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error: ", xhr.responseText);
            Swal.fire('Error!', 'An error occurred while updating the batch: ' + error, 'error');
        }
    });
});
</script>
</body>
</html>
