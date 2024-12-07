<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

function loadUsers() {
    $xml = simplexml_load_file('users.xml');
    return $xml->user;
}

function updateUser($updatedUser) {
   
    $xml = simplexml_load_file('users.xml');
    if ($xml === false) {
        echo "Failed loading XML: ";
        foreach (libxml_get_errors() as $error) {
            echo "\t", $error->message;
        }
        return false; 
    }

    
    $userUpdated = false; 
    foreach ($xml->user as $user) {
        if (trim($user->username) == trim($updatedUser['username'])) { 
            $user->email = $updatedUser['email'];
            $user->mobile_number = $updatedUser['mobile_number'];
            $userUpdated = true; 
            break;
        }
    }

    if ($userUpdated) {
        $result = $xml->asXML('users.xml');
        if ($result) {
            return true; 
        } else {
            echo "Error writing to XML file.";
        }
    } else {
        echo "No matching user found for username: " . htmlspecialchars($updatedUser['username']);
    }

    return false; 
}


if (!isset($_SESSION['username'])) {

    header('Location: login.php');
    exit();
}


$currentUser = null;
$username = trim($_SESSION['username']); 
$users = loadUsers();
foreach ($users as $user) {
    if (trim($user->username) == $username) { 
        $currentUser = $user;
        break;
    }
}

if ($currentUser === null) {
    echo "Current user not found in XML: " . htmlspecialchars($username);
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedUser = [
        'username' => $currentUser->username,
        'email' => htmlspecialchars($_POST['email']),
        'mobile_number' => htmlspecialchars($_POST['mob']),
    ];


    if (updateUser($updatedUser)) {
       
        header('Location: view.php');
        exit(); 
    } else {
     
        echo "<p style='color: red;'>Failed to update profile. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Customer Account</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/favicon.ico" rel="icon">
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Red+Rose:wght@600&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/profstyle.css" rel="stylesheet">
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

<div class="container">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                            <div class="mt-3">
                                <h4><?php echo htmlspecialchars($currentUser->username); ?></h4>
                                <p class="text-secondary mb-1">Movie Renter</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="post">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">User Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($currentUser->username); ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($currentUser->email); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Mobile</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="mob" value="<?php echo htmlspecialchars($currentUser->mobile_number); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>      
        </div>
    </div>
</div>

</body>
</html>
