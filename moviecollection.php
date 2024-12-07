<?php
// Check if category is set in URL
if (isset($_GET['category'])) {
    $selectedCategory = urldecode($_GET['category']);

    // Load the XML file for movies
    $xml = simplexml_load_file("moviecollect.xml") or die("Error: Cannot create object");

    // Filter movies by the selected category
    $movies = [];
    foreach ($xml->movie as $movie) {
        if ((string)$movie->genre == $selectedCategory) {
            $movies[] = $movie;
        }
    }

    // Check if any movies were found
    if (empty($movies)) {
        $noMoviesMessage = "No movies found for the category: " . htmlspecialchars($selectedCategory);
    }
} else {
    die("Category not selected.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies - <?php echo htmlspecialchars($selectedCategory); ?> Collection</title>

    <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="./assets/css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body id="top">

<header class="header" data-header>
    <div class="container">
        <div class="logo">
            <a href="./index.html">
                <img src="./assets/images/logo.svg" alt="Filmlane logo">
            </a>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary">
                <a href="moviecategory.php" style="color: white; text-decoration: none;">Back</a>
            </button>
        </div>
    </div>
</header>

<main>
    <article>
        <section class="top-rated">
            <div class="container">
                <h2 class="h2 section-title"><?php echo htmlspecialchars($selectedCategory); ?> Movies</h2>
                <ul class="movies-list">
                    <?php
                    // Display filtered movies
                    if (!empty($movies)) {
                        foreach ($movies as $movie) {
                            echo '<li>';
                            echo '  <div class="movie-card">';
                            echo '    <a href="./moviedetail.php?id=' . htmlspecialchars($movie['id']) . '">'; // Pass the movie ID for details page
                            echo '      <figure class="card-banner">';
                            echo '        <img src="' . htmlspecialchars($movie->image) . '" alt="' . htmlspecialchars($movie->title) . '">';
                            echo '      </figure>';
                            echo '    </a>';
                            echo '    <div class="title-wrapper">';
                            echo '      <h3 class="card-title">' . htmlspecialchars($movie->title) . '</h3>';
                            echo '      <time datetime="' . htmlspecialchars($movie->release_year) . '">' . htmlspecialchars($movie->release_year) . '</time>';
                            echo '    </div>';
                            echo '    <div class="card-meta">';
                            echo '      <div class="badge badge-outline">' . htmlspecialchars($movie->quality) . '</div>';
                            echo '      <div class="duration">';
                            echo '        <ion-icon name="time-outline"></ion-icon>';
                            echo '        <time datetime="PT' . htmlspecialchars($movie->duration) . 'M">' . htmlspecialchars($movie->duration) . ' min</time>';
                            echo '      </div>';
                            echo '      <div class="rating">';
                            echo '        <ion-icon name="star"></ion-icon>';
                            echo '        <data>' . htmlspecialchars($movie->rating) . '</data>';
                            echo '      </div>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</li>';
                        }
                    } else {
                        // Display a message if no movies found
                        echo '<li>' . htmlspecialchars($noMoviesMessage) . '</li>';
                    }
                    ?>
                </ul>
            </div>
        </section>
    </article>
</main>

<a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up"></ion-icon>
</a>

<script src="./assets/js/script.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>
