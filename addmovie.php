<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = htmlspecialchars($_POST['title']);
    $genre = htmlspecialchars($_POST['genre']);
    $release_year = htmlspecialchars($_POST['release_year']);
    $duration = htmlspecialchars($_POST['duration']);
    $rating = htmlspecialchars($_POST['rating']);
    $quality = htmlspecialchars($_POST['quality']);
    $available = htmlspecialchars($_POST['available']);
    $description = htmlspecialchars($_POST['description']);
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_destination = "assets/images/" . basename($image_name); 

        if (!move_uploaded_file($image_tmp_name, $image_destination)) {
            die("Error uploading file.");
        }
    } else {
        die("Error in file upload.");
    }

    $xml_file = 'moviecollect.xml';
    if (file_exists($xml_file)) {
        $xml = simplexml_load_file($xml_file);
    } else {
        $xml = new SimpleXMLElement('<?xml version="1.0"?><catalog></catalog>');
    }

    $new_id = 1; 
    foreach ($xml->movie as $movie) {
        $id = (int)$movie->id; 
        if ($id >= $new_id) {
            $new_id = $id + 1; 
        }
    } // Closing brace for foreach loop

    $movie = $xml->addChild('movie');
    $movie->addChild('id', $new_id);
    $movie->addChild('title', $title);
    $movie->addChild('genre', $genre);
    $movie->addChild('release_year', $release_year);
    $movie->addChild('duration', $duration);
    $movie->addChild('rating', $rating);
    $movie->addChild('quality', $quality);
    $movie->addChild('available', $available);
    $movie->addChild('image', $image_destination);
    $movie->addChild('description', $description);

    $xml->asXML($xml_file);

    echo "<script>alert('Movie added successfully!'); window.location.href='moviedash.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add New Movie</title>
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
                            <div class="mt-3">
                                <h4>Add New Movie</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="addmovie.php" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Movie Title</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="title" placeholder="Enter Movie Title" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Genre</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="genre" placeholder="Enter Genre" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Release Year</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="number" class="form-control" name="release_year" placeholder="Enter Release Year" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Duration (minutes)</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="number" class="form-control" name="duration" placeholder="Enter Duration" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Rating</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="rating" placeholder="Enter Rating (e.g., 7.8)" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Quality</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="quality" placeholder="Enter Quality (e.g., 2K, HD, 4K)" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Available</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <select class="form-control" name="available" required>
                                        <option value="">Select Availability</option>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Movie Poster</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="file" class="form-control" name="image" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Description</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <textarea class="form-control" name="description" rows="4" placeholder="Enter Movie Description" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
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
