<?php
session_start(); 

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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];


    if ($username === 'Admin' && $password === 'Admin123') {
        $_SESSION['username'] = $username; 
        header("Location: addash.php"); 
        exit;
    }


    $users = loadUsers();
    $isAuthenticated = false;

    foreach ($users as $user) {
        if ($user->username == $username && password_verify($password, $user->password)) {
            $isAuthenticated = true;
            break;
        }
    }

    if ($isAuthenticated) {
       
        $_SESSION['username'] = $username; 
        header("Location: cusdash.php"); 
        exit; 
    } else {
        echo "<script>alert('Invalid username or password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=yes, initial-scale=1" />
    <link rel="stylesheet" href="../MovieRental/CSS/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="../MovieRental/js/login.js" defer></script>
    <title>Login</title>
</head>

<header class="header" data-header>
    <div class="container">
        <div class="overlay" data-overlay></div>
        <a href="./index.html" class="logo">
            <img src="./assets/images/logo.svg" alt="Filmlane logo">
        </a>
    </div>
</header>

<body>
    <div class="line1">
        <p class="pclass1" id="b1">Welcome</p>
        <p class="pclass2" id="b1">To access all the features, please log in</p>
    </div>
    <div class="line2">
        <form method="POST" action="login.php">
            <div id="rcorners3">
                <button class="button1 info" type="button" onclick="BckSignin()">Register</button>
                <button class="button1 info" type="submit">Log In</button>
                <br>

                <input class="input1" type="text" id="username" placeholder="Username" name="username" required>
                <small id="usernameError" class="error-message" style="color: red;"></small>
                <br>

                <input class="input2" type="password" id="password" placeholder="Password" name="password" required>
                <small id="passwordError" class="error-message" style="color: red;"></small>
                <br>

                <button class="logSignButton log" type="submit">LOG IN</button>
            </div>
        </form>
    </div>
    <div class="line3">
        <h2><i class="material-icons">movie</i> Discover the best movies & TV shows<br>
            <i class="material-icons">shop</i> Buy & rent your favourite movies<br>
            <i class="material-icons">theaters</i> Watch trailers & see ratings
        </h2>
    </div>
</body>
</html>
