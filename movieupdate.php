<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

function loadMovies() {
    $xml = simplexml_load_file('moviecollect.xml');
    return $xml->movie; 
}

function updateMovie($updatedMovie) {
    $xml = simplexml_load_file('moviecollect.xml');
    if ($xml === false) {
        echo "Failed loading XML: ";
        foreach (libxml_get_errors() as $error) {
            echo "\t", $error->message;
        }
        return false; 
    }

    $movieUpdated = false; 
    foreach ($xml->movie as $movie) {
        if (trim($movie['id']) == trim($updatedMovie['id'])) { 
            $movie->title = $updatedMovie['title'];
            $movie->genre = $updatedMovie['genre'];
            $movie->release_year = $updatedMovie['release_year'];
            $movie->duration = $updatedMovie['duration'];
            $movieUpdated = true;
        }
    }

    if ($movieUpdated) {
        $result = $xml->asXML('moviecollect.xml');
        if ($result) {
            return true; 
        } else {
            echo "Error writing to XML file.";
        }
    } else {
        echo "No matching movie found for ID: " . htmlspecialchars($updatedMovie['id']);
    }

    return false; 
}

if (!isset($_GET['id'])) {
    header('Location: moviedashboard.php');
    exit();
}

$movieId = trim($_GET['id']);
$movies = loadMovies();
$selectedMovie = null;

foreach ($movies as $movie) {
    if (trim($movie['id']) == $movieId) {
        $selectedMovie = $movie;
        break;
    }
}

if ($selectedMovie === null) {
    echo "Movie not found in XML: " . htmlspecialchars($movieId);
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedMovie = [
        'id' => $selectedMovie['id'],
        'title' => htmlspecialchars($_POST['title']),
        'genre' => htmlspecialchars($_POST['genre']),
        'release_year' => htmlspecialchars($_POST['release_year']),
        'duration' => htmlspecialchars($_POST['duration']),
    ];

    if (updateMovie($updatedMovie)) {
        header('Location: movieview.php?id=' . $movieId);
        exit(); 
    } else {
        echo "<p style='color: red;'>Failed to update movie details. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Movie Details</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/profstyle.css" rel="stylesheet">
</head>

<body>
<header class="header" data-header>
    <div class="container">
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
                            <img src="<?php echo htmlspecialchars($selectedMovie->image); ?>" alt="Movie Poster" class="rounded-circle" width="150">
                            <div class="mt-3">
                                <h4><?php echo htmlspecialchars($selectedMovie->title); ?></h4>
                                <p class="text-secondary mb-1">Movie</p>
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
                                    <h6 class="mb-0">Movie Title</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($selectedMovie->title); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Genre</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="genre" value="<?php echo htmlspecialchars($selectedMovie->genre); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Release Year</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="release_year" value="<?php echo htmlspecialchars($selectedMovie->release_year); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Duration (minutes)</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="duration" value="<?php echo htmlspecialchars($selectedMovie->duration); ?>">
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
