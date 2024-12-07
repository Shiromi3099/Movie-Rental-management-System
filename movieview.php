<?php
session_start(); 

function loadMovies() {
    $movies = [];
    if (file_exists('moviecollect.xml')) {
        $xml = simplexml_load_file('moviecollect.xml');
        if ($xml === false) {
            echo "Failed loading XML: ";
            foreach (libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
            return $movies; 
        }
        foreach ($xml->movie as $movie) {
            $movies[] = $movie;
        }
    }
    return $movies;
}


if (!isset($_GET['id'])) {
    echo "<script>alert('Movie ID not provided!'); window.location.href='cusdash.php';</script>";
    exit();
}


$movie_id = htmlspecialchars($_GET['id']);

$movies = loadMovies();


$selectedMovie = null;
foreach ($movies as $movie) {
    if ($movie['id'] == $movie_id) {
        $selectedMovie = $movie;
        break;
    }
}


if ($selectedMovie === null) {
    echo "<script>alert('Movie not found!'); window.location.href='cusdash.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($selectedMovie->title); ?> - Movie Detail View</title>
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
                <a href="moviedash.php" style="color: white; text-decoration: none;">Back</a>
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
                            <img src="<?php echo htmlspecialchars($selectedMovie->image); ?>" alt="Movie Poster" class="rounded-circle" width="150">
                            <div class="mt-3">
                                <h4><?php echo htmlspecialchars($selectedMovie->title); ?></h4>
                                <p class="text-secondary mb-1">Movie</p>
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
                                <h6 class="mb-0">Movie Title</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo htmlspecialchars($selectedMovie->title); ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Genre</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo htmlspecialchars($selectedMovie->genre); ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Release Year</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo htmlspecialchars($selectedMovie->release_year); ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Duration</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo htmlspecialchars($selectedMovie->duration); ?> minutes
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <a class="btn btn-info" href="movieupdate.php?id=<?php echo $movie_id; ?>">Update</a>
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
