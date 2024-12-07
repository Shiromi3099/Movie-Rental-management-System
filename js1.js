// Function to validate email format using regular expression
function isValidEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
}

// Event listener to validate email on input change
const emailInput = document.getElementById("email");
const emailFeedback = document.getElementById("emailValidationFeedback");

emailInput.addEventListener("input", function () {
    const email = emailInput.value;
    if (!isValidEmail(email)) {
        emailInput.classList.add("is-invalid");
        emailFeedback.style.display = "block";
    } else {
        emailInput.classList.remove("is-invalid");
        emailFeedback.style.display = "none";
    }
});

// Function to toggle password visibility
function togglePasswordVisibility() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("far", "fa-eye");
        toggleIcon.classList.add("fas", "fa-eye-slash");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("fas", "fa-eye-slash");
        toggleIcon.classList.add("far", "fa-eye");
    }
}

// Event listener to validate password on input change
const passwordInput = document.getElementById("password");
const passwordFeedback = document.getElementById("passwordValidationFeedback");

passwordInput.addEventListener("input", function () {
    const password = passwordInput.value;
    if (!password) {
        passwordInput.classList.add("is-invalid");
        passwordFeedback.style.display = "block";
    } else {
        passwordInput.classList.remove("is-invalid");
        passwordFeedback.style.display = "none";
    }
});

// Function to handle form submission
function submitForm() {
    const rememberCheckbox = document.getElementById("remember");
    const emailInput = document.getElementById("email");

    if (rememberCheckbox.checked) {
        // Save the email to local storage
        localStorage.setItem("savedEmail", emailInput.value);
    } else {
        // Remove the email from local storage
        localStorage.removeItem("savedEmail");
    }

    // Submit the form
    const form = document.querySelector("form");
    form.submit();
}

// Function to load the saved email from local storage
window.addEventListener("load", function () {
    const rememberCheckbox = document.getElementById("remember");
    const emailInput = document.getElementById("email");
    const savedEmail = localStorage.getItem("savedEmail");

    if (savedEmail) {
        emailInput.value = savedEmail;
        rememberCheckbox.checked = true;
    }
});



