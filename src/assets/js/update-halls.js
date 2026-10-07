batchSelect.addEventListener("change", function () {
    const batchId = batchSelect.value;

    // If batch is selected, fetch halls via AJAX
    if (batchId) {
        fetch('get_halls.php', {  // Ensure this path is correct
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'batch_id=' + batchId
        })
        .then(response => response.json())
        .then(halls => {
            // Clear the hall dropdown first
            hallSelect.innerHTML = '<option value="">Select Hall</option>';
            
            // Populate the hall dropdown with filtered halls
            halls.forEach(hall => {
                const option = document.createElement('option');
                option.value = hall.id;
                option.textContent = `${hall.hall_name} (Capacity: ${hall.capacity})`;
                hallSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching halls:', error));
    } else {
        // Clear hall dropdown if no batch is selected
        hallSelect.innerHTML = '<option value="">Select Hall</option>';
    }
});