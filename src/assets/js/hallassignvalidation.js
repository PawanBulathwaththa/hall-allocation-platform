document.addEventListener('click', function(event) {
    if (event.target.classList.contains('assign-button')) {
        const assignButton = event.target;
        const hallCapacity = assignButton.getAttribute('data-capacity');
        const batchCount = assignButton.getAttribute('data-count');

        if (parseInt(batchCount) > parseInt(hallCapacity)) {
            alert('Cannot assign this hall. Student count exceeds hall capacity.');
        } else {
            assignButton.textContent = 'Assigned'; // Change button text to 'Assigned'
            assignButton.classList.add('assigned'); // Add a class to identify assigned buttons
            assignButton.disabled = true; // Disable the button after assignment
        }
    }
});

