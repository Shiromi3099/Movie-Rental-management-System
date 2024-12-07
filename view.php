<?php
session_start();


function loadUsers() {
    $users = [];
    if (file_exists('users.xml')) {
        $xml = simplexml_load_file('users.xml');
        if ($xml === false) {
            echo "Failed loading XML: ";
            foreach (libxml_get_errors() as $error) {
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


if (!isset($_SESSION['username'])) {
    echo "<script>alert('You need to log in first!'); window.location.href='login.php';</script>";
    exit();
}

$username = htmlspecialchars($_SESSION['username']);


$users = loadUsers();


$currentUser = null;
foreach ($users as $user) {
    if ($user->username == $username) {
        $currentUser = $user;
        break;
    }
}


if ($currentUser === null) {
    echo "<script>alert('User not found!'); window.location.href='login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Customer Profile View</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/profstyle.css" rel="stylesheet">
    
</head>
<body>

<header class="header" data-header>
    <div class="container d-flex justify-content-between align-items-center">
        <div class="logo">
            <a href="./index.html">
                <img src="./assets/images/logo.svg" alt="Filmlane logo">
            </a>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary">
                <a href="cusdash.php" style="color: white; text-decoration: none;">Back</a>
            </button>
        </div>
    </div>
</header>


<div class="container">
    <div class="main-body">
        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="User Avatar" class="rounded-circle" width="150">
                            <div class="mt-3">
                                <h4><?php echo $currentUser->username; ?></h4>
                                <p class="text-secondary mb-1">Movie Renter</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">User Name</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $currentUser->username; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $currentUser->email; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Mobile</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $currentUser->mobile_number; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <a class="btn btn-info" href="userupdate.php">Update</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
