document.addEventListener('DOMContentLoaded', function() {
    var f3 = flatpickr(document.getElementById('rangeCalendarFlatpickr'), {
        dateFormat: "Y-m-d",
        minDate: "today", // No past dates allowed
        onChange: function(selectedDates, dateStr, instance) {
            validateInputs(); // Call the validation function when the date is selected
        }
    });
});

var f3 = flatpickr(document.getElementById('rangeCalendarFlatpickr'), {
    mode: "range"
})