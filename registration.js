function validateForm() {
    var username = document.getElementById("username").value;
    var address = document.getElementById("address").value;
    var contactNumber = document.getElementById("contact-number").value;
    var userType = document.getElementById("user-type").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm-password").value;

    var errorContainer = document.getElementById("error-container");
    errorContainer.innerHTML = "";  // Clear previous error messages

    if (username === "" || address === "" || contactNumber === "" || userType === "" || email === "" || password === "" || confirmPassword === "") {
        errorContainer.innerHTML += "All fields are required.<br>";
    }

    if (password.length < 6) {
        errorContainer.innerHTML += "Password must contain at least 6 characters.<br>";
    }

    if (password !== confirmPassword) {
        errorContainer.innerHTML += "Password and Confirm Password do not match.<br>";
    }

    if (!/^\d{10}$/.test(contactNumber)) {
        errorContainer.innerHTML += "Contact Number must contain exactly 10 digits.<br>";
    }

    // Return false to prevent the form from submitting if there are errors
    return errorContainer.innerHTML === "";
}