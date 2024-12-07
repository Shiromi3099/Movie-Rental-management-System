// Event listener for the login form
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission for validation

    // Clear previous error messages
    document.getElementById('loginEmailError').textContent = '';
    document.getElementById('loginPasswordError').textContent = '';

    const email = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value.trim();
    let valid = true;

    // Check for empty fields
    if (!email) {
        document.getElementById('loginEmailError').textContent = 'Email is required.';
        valid = false;
    } else if (!validateEmail(email)) {
        document.getElementById('loginEmailError').textContent = 'Invalid email format.';
        valid = false;
    }

    if (!password) {
        document.getElementById('loginPasswordError').textContent = 'Password is required.';
        valid = false;
    }

    if (valid) {
        console.log('Login successful');
        // You can proceed with login logic or redirect
    }
});

// Event listener for the signup form
document.getElementById('signupForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission for validation

    // Clear previous error messages
    document.getElementById('signupNameError').textContent = '';
    document.getElementById('signupEmailError').textContent = '';
    document.getElementById('signupPhoneError').textContent = '';
    document.getElementById('signupPasswordError').textContent = '';
    document.getElementById('signupConfirmPasswordError').textContent = '';

    const name = document.getElementById('signupName').value.trim();
    const email = document.getElementById('signupEmail').value.trim();
    const phone = document.getElementById('signupPhone').value.trim();
    const password = document.getElementById('signupPassword').value.trim();
    const confirmPassword = document.getElementById('signupConfirmPassword').value.trim();
    let valid = true;

    // Check for empty fields
    if (!name) {
        document.getElementById('signupNameError').textContent = 'Name is required.';
        valid = false;
    }
    if (!email) {
        document.getElementById('signupEmailError').textContent = 'Email is required.';
        valid = false;
    } else if (!validateEmail(email)) {
        document.getElementById('signupEmailError').textContent = 'Invalid email format.';
        valid = false;
    }
    if (!phone) {
        document.getElementById('signupPhoneError').textContent = 'Phone number is required.';
        valid = false;
    }
    if (!password) {
        document.getElementById('signupPasswordError').textContent = 'Password is required.';
        valid = false;
    }
    if (password !== confirmPassword) {
        document.getElementById('signupConfirmPasswordError').textContent = 'Passwords do not match.';
        valid = false;
    }

    if (valid) {
        console.log('Signup successful');
        // You can proceed with signup logic or redirect
    }
});

// Helper function to validate email format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
