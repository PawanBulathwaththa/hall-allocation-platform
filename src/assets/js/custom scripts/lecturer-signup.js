document.getElementById('signupForm').addEventListener('submit', function(event) {
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;

    // Email validation for NIBM domain
    const emailRegex = /^[a-zA-Z0-9._%+-]+@nibm\.lk$/;
    if (!emailRegex.test(email)) {
        alert("Email must be a valid NIBM email (example@nibm.lk)");
        event.preventDefault();
    }

    // Password validation (between 8 to 20 characters)
    if (password.length < 8 || password.length > 20) {
        alert("Password must be between 8 and 20 characters.");
        event.preventDefault();
    }
});