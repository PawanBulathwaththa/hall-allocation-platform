document.getElementById('signupForm').onsubmit = function(event) {
    // Get form values
    const lecturerName = document.querySelector('input[name="lecturer_name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value.trim();
    const termsChecked = document.getElementById('terms').checked;

    // Initialize an empty array to hold error messages
    let errors = [];

    // Validate Name
    if (lecturerName === '') {
        errors.push('Name is required.');
    }

    // Validate Email
    const emailPattern = /^[a-zA-Z0-9._%+-]+@nibm\.lk$/;
    if (email === '' || !emailPattern.test(email)) {
        errors.push('Email must be a valid NIBM work email (example@nibm.lk).');
    }

    // Validate Password
    if (password.length < 8) {
        errors.push('Password must be at least 8 characters.');
    }

    // Validate Terms and Conditions
    if (!termsChecked) {
        errors.push('You must agree to the Terms and Conditions.');
    }

    // If there are errors, prevent form submission and display them
    if (errors.length > 0) {
        event.preventDefault(); // Prevent form submission
        alert(errors.join('\n')); // Show error messages
    }
};