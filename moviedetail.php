<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the movie ID from the query parameter
if (isset($_GET['id'])) {
    $movie_id = htmlspecialchars(urldecode($_GET['id'])); // Decode and sanitize the movie ID

    // Load the movie collection from XML
    $movies = simplexml_load_file('moviecollect.xml');

    // Find the movie by ID
    $selected_movie = null;
    foreach ($movies->movie as $movie) {
        if ($movie['id'] == $movie_id) { // Check against the ID attribute
            $selected_movie = $movie;
            break;
        }
    }

    // If the movie is not found, redirect back to the dashboard
    if ($selected_movie === null) {
        header("Location: cusdash.php");
        exit();
    }
} else {
    // Redirect to dashboard if no ID is provided
    header("Location: cusdash.php");
    exit();
}

// Function to load existing users from users.xml
function loadUsers() {
    $users = [];
    if (file_exists('users.xml')) {
        $xml = simplexml_load_file('users.xml');
        if ($xml === false) {
            echo "Failed loading XML: ";
            foreach(libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
            return $users; // Return empty array if loading fails
        }
        foreach ($xml->user as $user) {
            $users[] = $user;
        }
    }
    return $users;
}

// Handle the rental process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rent_movie_id'])) {
    $user_id = $_SESSION['username']; // Using username as ID (change if needed)
    $movie_id = $_POST['rent_movie_id'];
    $movie_name = htmlspecialchars($selected_movie->title); // Get movie title for rental

    // Load users
    $users = loadUsers();
    $updated = false;

    foreach ($users as $user) {
        if ($user->username == $user_id) { // Match user by username
            // Create new rented_movie entry
            $rented_movie = $user->rented_movies->addChild('rented_movie');
            $rented_movie->addChild('movie_name', $movie_name); // Update movie name
            $rented_movie->addChild('rent_date', date('Y-m-d')); // Current date

            $updated = true;
            break;
        }
    }

    if ($updated) {
        // Save changes to users.xml
        $xml = new SimpleXMLElement('<?xml version="1.0"?><users></users>');
        foreach ($users as $user) {
            $new_user = $xml->addChild('user');
            $new_user->addChild('username', $user->username);
            $new_user->addChild('email', $user->email);
            $new_user->addChild('mobile_number', $user->mobile_number);
            $new_user->addChild('password', $user->password);

            // Append rented_movies data
            $new_rented_movies = $new_user->addChild('rented_movies');
            foreach ($user->rented_movies->rented_movie as $rented) {
                $new_rented_movie = $new_rented_movies->addChild('rented_movie');
                $new_rented_movie->addChild('movie_name', $rented->movie_name);
                $new_rented_movie->addChild('rent_date', $rented->rent_date);
            }
        }

        $xml->asXML('users.xml'); // Save the updated XML file
        echo "<script>alert('Movie rented successfully!'); window.location='moviedetail.php?id=$movie_id';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to rent movie!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($selected_movie->title); ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./assets/css/style.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body id="#top">
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

  <main>
    <article>


    <main>
        <article>
            <!-- Movie Detail Section -->
            <section class="movie-detail">
                <div class="container">
                    <figure class="movie-detail-banner">
                        <img src="<?php echo htmlspecialchars($selected_movie->image); ?>" alt="<?php echo htmlspecialchars($selected_movie->title); ?> movie poster">
                        <button class="play-btn">
                            <ion-icon name="play-circle-outline"></ion-icon>
                        </button>
                    </figure>

                    <div class="movie-detail-content">
                        <p class="detail-subtitle">New Episode</p>
                        <h1 class="h1 detail-title"><?php echo htmlspecialchars($selected_movie->title); ?></h1>

                        <div class="meta-wrapper">
                            <div class="badge-wrapper">
                                <div class="badge badge-fill">PG 13</div>
                                <div class="badge badge-outline"><?php echo htmlspecialchars($selected_movie->quality); ?></div>
                            </div>

                            <div class="genre-wrapper">
                                <a href="#"><?php echo htmlspecialchars($selected_movie->genre); ?></a>
                            </div>

                            <div class="date-time">
                                <div>
                                    <ion-icon name="calendar-outline"></ion-icon>
                                    <time datetime="<?php echo htmlspecialchars($selected_movie->release_year); ?>">
                                        <?php echo htmlspecialchars($selected_movie->release_year); ?>
                                    </time>
                                </div>
                                <div>
                                    <ion-icon name="time-outline"></ion-icon>
                                    <time datetime="PT<?php echo htmlspecialchars($selected_movie->duration); ?>M">
                                        <?php echo htmlspecialchars($selected_movie->duration); ?> min
                                    </time>
                                </div>
                            </div>
                        </div>

                        <p class="storyline"><?php echo htmlspecialchars($selected_movie->description); ?></p>

                        <form method="POST" action="">
                            <button class="btn btn-primary" type="submit" name="rent_movie_id" value="<?php echo htmlspecialchars($selected_movie['id']); ?>">
                                <ion-icon name="cash"></ion-icon>
                                <span>Rent Now</span>
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </article>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p class="copyright">
                &copy; 2024 <a href="#">YourWebsite</a>. All Rights Reserved
            </p>
        </div>
    </footer>

    <!-- GO TO TOP -->
    <a href="#top" class="go-top" data-go-top>
        <ion-icon name="chevron-up"></ion-icon>
    </a>

    <!-- Custom JS -->
    <script src="./assets/js/script.js"></script>

    <!-- Ionicon -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>
