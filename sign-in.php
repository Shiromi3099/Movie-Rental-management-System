<?php

function loadUsers() {
    $users = [];
    if (file_exists('users.xml')) {
        $xml = simplexml_load_file('users.xml');
        if ($xml === false) {
            echo "Failed loading XML: ";
            foreach(libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
            return $users;
        }
        foreach ($xml->user as $user) {
            $users[] = $user;
        }
    }
    return $users;
}


function saveUser($email, $username, $mobile, $password) {
    $users = loadUsers();
    $newId = count($users) + 1; 

    
    if (file_exists('users.xml')) {
        $existingXml = simplexml_load_file('users.xml');
    } else {
        $existingXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><users></users>');
    }

    $newUser = $existingXml->addChild('user');
    $newUser->addAttribute('id', $newId);
    $newUser->addChild('email', htmlspecialchars($email));
    $newUser->addChild('username', htmlspecialchars($username));
    $newUser->addChild('mobile_number', htmlspecialchars($mobile));
    $newUser->addChild('password', password_hash($password, PASSWORD_DEFAULT));
    $newUser->addChild('confirm_password', password_hash($password, PASSWORD_DEFAULT)); 


    $existingXml->asXML('users.xml');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];


    if ($password === $confirmPassword) {
        saveUser($email, $username, $mobile, $password);
        echo "<script>alert('User registered successfully!'); window.location.href='./login.php';</script>";
    } else {
        echo "<script>alert('Passwords do not match!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="user-scalable=no, initial-scale=1" />
    <link rel="stylesheet" href="../MovieRental/CSS/signin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="../MovieRental/js/signup.js" defer></script>
</head>
<body>
    <header class="header" data-header>
        <div class="container">
            <div class="overlay" data-overlay></div>
            <a href="./index.html" class="logo">
                <img src="./assets/images/logo.svg" alt="Filmlane logo">
            </a>
        </div>
    </header>

    <div class="line1">
        <p class="pclass1" id="b1">Welcome</p>
        <p class="pclass2" id="b1">To access all the features, please sign-in</p>
    </div>

    <div class="line2">
        <form method="POST" action="sign-in.php"> <!-- action added -->
            <div id="rcorners3">
                <button class="button1 info" type="button" onclick="BckSignin()">Register</button>
                <button class="button1 info" type="button" onclick="BckLogin()">Log In</button>
                <br>

                <input class="input3" type="text" id="email" placeholder="Email" name="email" required>
                <small id="emailError" class="error-message"></small>
                <br>

                <input class="input1" type="text" id="username" placeholder="Username" name="username" required>
                <small id="usernameError" class="error-message"></small>
                <br>

                <input class="input1" type="text" id="mobile" placeholder="Mobile Number" name="mobile" required>
                <small id="mobileError" class="error-message"></small>
                <br>

                <input class="input2" type="password" id="password" placeholder="Password" name="password" required>
                <small id="passwordError" class="error-message"></small>
                <br>

                <input class="input2" type="password" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword" required>
                <small id="confirmPasswordError" class="error-message"></small>
                <br>

                <button class="logSignButton log" type="submit">SIGN IN</button>
            </div>
        </form>
    </div>

    <div class="line3">
        <h2>
            <i class="material-icons">movie</i> Discover the best movies & TV shows<br>
            <i class="material-icons">shop</i> Buy & rent your favourite movies<br>
            <i class="material-icons">theaters</i> Watch trailers & see ratings
        </h2>
    </div>
</body>
</html>
