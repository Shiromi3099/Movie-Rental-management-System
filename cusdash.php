<?php
session_start(); 

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$username = htmlspecialchars($_SESSION['username']);

function loadMovies() {
    $xml = simplexml_load_file('moviecollect.xml');
    if ($xml === false) {
        die('Error loading XML: ' . print_r(libxml_get_errors(), true));
    }
    return $xml->movie ? $xml->movie : [];
} // Closing brace added here

function loadUsers() {
    $xml = simplexml_load_file('users.xml');
    if ($xml === false) {
        die('Error loading XML: ' . print_r(libxml_get_errors(), true));
    }
    return $xml->user ? $xml->user : [];
}

$movies = loadMovies();
$users = loadUsers();

function getRentedMoviesDetails($users, $username) {
    foreach ($users as $user) {
        if ($user->username == $username) {
            $rentedMovies = [];
            if (isset($user->rented_movies) && count($user->rented_movies->rented_movie) > 0) {
                foreach ($user->rented_movies->rented_movie as $rentedMovie) {
                    $rentedMovies[] = [
                        'title' => (string)$rentedMovie->movie_name,
                        'date' => (string)$rentedMovie->rent_date
                    ];
                }
            }
            return $rentedMovies;
        }
    }
    return []; 
}

$rentedMoviesDetails = getRentedMoviesDetails($users, $username);

$selectedGenre = isset($_GET['genre']) ? htmlspecialchars(trim($_GET['genre'])) : '';

$genres = [];
foreach ($movies as $movie) {
    $genre = (string)$movie->genre;
    if (!in_array($genre, $genres)) {
        $genres[] = $genre;
    }
}

$filteredMovies = [];
foreach ($movies as $movie) {
    if ($selectedGenre === '' || $selectedGenre === (string)$movie->genre) {
        $filteredMovies[] = $movie; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Customer Dashboard" name="description">
    <title>Customer Dashboard</title>

   
    <link href="img/favicon.ico" rel="icon">

 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

  
    <link href="css/bootstrap.min1.css" rel="stylesheet">
    <link href="css/style02.css" rel="stylesheet">
    <link href="css/search.css" rel="stylesheet">
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
                    <h3 class="text-primary">Flim Lane</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="ms-3">
                        <h6 class="mb-0" style="color: white;"><?php echo $username; ?></h6> <!-- Display username -->
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="cusdash.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Pages</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="moviecategory.php" class="dropdown-item">Movies</a>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-user me-2"></i>Account</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="view.php" class="dropdown-item">Profile</a>
                            <a href="index.html" class="dropdown-item">Log Out</a>
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
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-lg-inline-flex">Customer</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="index.html" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
           
            <div class="container-fluid pt-4 px-4">
                <form method="GET" action="">
                    <div class="input-group mb-4">
                        <select class="form-select" name="genre">
                            <option value="">All Genres</option>
                            <?php foreach ($genres as $genre): ?>
                                <option value="<?php echo htmlspecialchars($genre); ?>" <?php echo $selectedGenre === htmlspecialchars($genre) ? 'selected' : ''; ?>><?php echo htmlspecialchars($genre); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                </form>
            </div>
            
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Movie List</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white">
                                    <th scope="col">Movie Title</th>
                                    <th scope="col">Run Time</th>
                                    <th scope="col">Availability</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Rented Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($filteredMovies as $movie): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($movie->title); ?></td>
                                        <td><?php echo htmlspecialchars($movie->duration); ?> mins</td>
                                        <td><?php echo htmlspecialchars($movie->available == 'true' ? 'Available' : 'Not Available'); ?></td>
                                        <td>
                                            <a href="moviedetail.php?id=<?php echo urlencode($movie['id']); ?>" class="btn btn-secondary btn-sm">View Movie</a>
                                        </td>
                                        <td>
                                            <?php 
                                            $movieTitle = (string)$movie->title;
                                            $rentedStatus = 'Available'; 
                                            foreach ($rentedMoviesDetails as $rentedMovie) {
                                                if ($rentedMovie['title'] === $movieTitle) {
                                                    $rentedStatus = 'Rented';
                                                    break;
                                                }
                                            }
                                            echo $rentedStatus;
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            foreach ($rentedMoviesDetails as $rentedMovie) {
                                                if ($rentedMovie['title'] === $movieTitle) {
                                                    echo "Rented on: " . htmlspecialchars($rentedMovie['date']);
                                                    break;
                                                }
                                            }
                                            ?>
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
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <script src="js/main.js"></script>
</body>
</html>
