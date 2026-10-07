
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
                <td><button class="btn btn-primary assign-button" data-capacity="${hall.capacity}" data-count="${batchCount}">Assign</button></td>
            `;
            assignedTableBody.appendChild(row);
        });
    }
    
    document.getElementById('assignButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent form submission
        updateAssignedTable();
    });
</script>
