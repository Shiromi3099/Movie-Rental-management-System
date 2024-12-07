<?php

function loadUsers() {
    $xml = simplexml_load_file('users.xml');
    if ($xml === false) {
        echo "Failed loading XML: ";
        foreach (libxml_get_errors() as $error) {
            echo "\t", $error->message;
        }
        return [];
    }
    return $xml->user; 
}

$users = loadUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Customer List</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    
    <link href="img/favicon.ico" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">


    <link href="css/bootstrap.min1.css" rel="stylesheet">

    <link href="css/style02.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
     
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
      

        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary">Flimlane</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="ms-3">
                        <h6 class="mb-0" style="color: white;"></h6>
                        <span style="color:Green;">Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="customerlist.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Pages</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="moviedash.php" class="dropdown-item">Movies</a>
                            <a href="customerlist.php" class="dropdown-item">Customers</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Pages</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="login.php" class="dropdown-item">Log out</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
       
        <div class="content">
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
                </a>
                
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="addmovie.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-lg-inline-flex">Add New movies</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0">
                            <hr class="dropdown-divider">
                            <a href="addmovie.php" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Add New movies</h6>
                            </a>                           
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-lg-inline-flex">Admin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0">
                            <a href="index.html" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
      
            <div class="container-fluid1 pt-4 px-4">
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Customer List</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white">
                                    <th scope="col">Username</th>
                                    <th scope="col">Rented Movie(s)</th>
                                    <th scope="col">Rent Date(s)</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user->username); ?></td>
                                  
                                    <td>
                                        <?php 
                                       
                                        if (!empty($user->rented_movies->rented_movie)) {
                                            foreach ($user->rented_movies->rented_movie as $rented_movie) {
                                                echo htmlspecialchars($rented_movie->movie_name) . "<br>";
                                            }
                                        } else {
                                            echo "No rented movies";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                       
                                        if (!empty($user->rented_movies->rented_movie)) {
                                            foreach ($user->rented_movies->rented_movie as $rented_movie) {
                                                echo htmlspecialchars($rented_movie->rent_date) . "<br>";
                                            }
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <form action="update_user.php" method="post" onsubmit="return confirmReturn();">
                                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($user->username); ?>">
                                            <button type="submit" class="btn btn-secondary btn-sm">Returned</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          
        </div>
  
    </div>

  
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>

    <script>
    function confirmReturn() {
        return confirm("Updated Successfully");
    }
    </script>
</body>

</html>
