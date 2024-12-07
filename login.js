let errorOccurred = false; // Global variable to track if an error occurred

function displayError(elementId, message) {
    document.getElementById(elementId).textContent = message;
}

function clearLoginErrors() {
    displayError('usernameError', '');
    displayError('passwordError', '');
}

function validateLogin() {
    // Clear previous error messages
    clearLoginErrors();

    // Get login form values
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    
    let hasError = false;

    // Field validation
    if (username === "") {
        displayError('usernameError', 'Username cannot be empty.');
        hasError = true;
    }

    if (password === "") {
        displayError('passwordError', 'Password cannot be empty.');
        hasError = true;
    }

    // If there was an error, set the errorOccurred flag and stop further processing
    if (hasError) {
        errorOccurred = true; // Set the error flag
        return; // Stop processing if any field is empty
    }

    // Retrieve existing XML from localStorage
    let existingXML = localStorage.getItem('userCatalogXML');
    if (!existingXML) {
        displayError('usernameError', 'No users found. Please register first.');
        errorOccurred = true; // Set the error flag
        return;
    }

    // Parse the XML data
    const parser = new DOMParser();
    const xmlDoc = parser.parseFromString(existingXML, "application/xml");
    
    // Find matching user credentials
    const users = xmlDoc.getElementsByTagName('user');
    let userFound = false;

    for (let i = 0; i < users.length; i++) {
        const storedUsername = users[i].getElementsByTagName('username')[0].textContent;
        const storedPassword = users[i].getElementsByTagName('password')[0].textContent;

        if (storedUsername === username && storedPassword === password) {
            userFound = true;
            break;
        }
    }

    if (userFound) {
        alert('Login successful!');
        // Redirect to another page or perform other actions after login
        window.location.href = "dashboard.html"; // Change to the actual URL after successful login
    } else {
        alert('Invalid username or password.'); // Show error as a pop-up
        location.reload(); // Refresh the page to allow for a new attempt
    }
}

// Function to handle the retry login attempt
function attemptLogin() {
    if (errorOccurred) {
        location.reload(); // Refresh the page if there was a previous error
    } else {
        validateLogin(); // Attempt to validate the login
    }
}

// Clear error messages when user starts typing
document.getElementById('username').addEventListener('input', function() {
    displayError('usernameError', ''); // Clear the username error
});

document.getElementById('password').addEventListener('input', function() {
    displayError('passwordError', ''); // Clear the password error
});

function BckSignin() {
    window.location.href = "sign-in.php"; // Change to the actual URL of your registration page
}

function BckLogin() {
    window.location.href = "login.php"; // Change to the actual URL of your login page
}