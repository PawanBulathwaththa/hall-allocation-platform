 <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="src/plugins/src/waves/waves.min.js"></script>
    <script src="layouts/collapsible-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="src/plugins/src/apex/apexcharts.min.js"></script>
    <script src="src/assets/js/dashboard/dash_1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="src/assets/js/dropdown.js"></script>
    <script src="src/assets/js/datepicker.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src="src/assets/js/calander.js"></script>


    <script src="src/plugins/src/editors/quill/quill.js"></script>
    <script src="src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
    <script src="src/plugins/src/notification/snackbar/snackbar.min.js"></script>
    <script src="src/assets/js/apps/mailbox.js"></script>

    <script src="src/assets/js/update-halls.js"></script>
    
    <script src="src/assets/js/hallassignvalidation.js"></script>
    
    <script>
        const halls = <?= json_encode($halls); ?>; // Pass halls data to JavaScript

            function updateAssignedTable() {
                const selectedBatches = document.querySelectorAll('input[name="batch[]"]:checked');
                const assignedTableBody = document.querySelector('#assignedTableBody');
                assignedTableBody.innerHTML = ''; // Clear previous rows

                selectedBatches.forEach((checkbox, index) => {
                    const batchCount = checkbox.closest('tr').querySelector('td:nth-child(3)').innerText; // Get student count
                    const hall = halls[index % halls.length]; // Select halls in a loop

                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td><p class="mb-0">${checkbox.value}</p></td>
                        <td><p class="mb-0">${hall.hall_name}</p></td>
                        <td>
                            <select class="form-select" name="hours[]">
                                <option value="6">6 hrs</option>
                                <option value="3">3 hrs</option>
                            </select>
                        </td>
                        <td ><button  class="btn btn-primary assign-button" data-capacity="${hall.capacity}" data-count="${batchCount}">Assign</button></td>
                    `;
                    assignedTableBody.appendChild(row);
                });
            }

        document.getElementById('addButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission
            updateAssignedTable();
        });


        


    </script>

    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="src/assets/js/loadTimeTableSoc01.js"></script>

    <script script="src/assets/js/delete-lecturer.js"></script>

    <script src="src/plugins/src/editors/quill/quill.js"></script>
    <script src="src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
    <script src="src/plugins/src/notification/snackbar/snackbar.min.js"></script>
    <script src="src/assets/js/apps/mailbox.js"></script>

    

    

    
    
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    

</body>
</html>