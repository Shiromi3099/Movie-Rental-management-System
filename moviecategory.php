<?php
// Load the XML file for categories
$xml = simplexml_load_file("moviecat.xml") or die("Error: Cannot create object");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Filmlane - Best movie collections</title>

  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="./assets/css/style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body id="top">

  <header class="header" data-header>
    <div class="container">
      <div class="overlay" data-overlay></div>
      <a href="./index.html" class="logo">
        <img src="./assets/images/logo.svg" alt="Filmlane logo">
      </a>

      <div class="header-actions">
        <button class="btn btn-primary">
          <a href="cusdash.php" style="color: white; text-decoration: none;">Back</a>
        </button>
      </div>
    </div>
  </header>

  <main>
    <article>
      <section class="upcoming">
        <div class="container">
          <div class="flex-wrapper">
            <div class="title-wrapper">
              <h2 class="h2 section-title">Categories</h2>
            </div>
          </div>

          <ul class="movies-list has-scrollbar">
            <?php
            // Loop through the categories in the XML
            foreach ($xml->category as $category) {
              echo '<li>';
              echo '  <div class="movie-card">';
              echo '    <a href="./moviecollection.php?category=' . urlencode($category->name) . '">';
              echo '      <figure class="card-banner">';
              echo '        <img src="' . htmlspecialchars($category->image) . '" alt="' . htmlspecialchars($category->name) . '">';
              echo '      </figure>';
              echo '    </a>';
              echo '    <div class="title-wrapper">';
              echo '      <a href="./moviecollection.php?category=' . urlencode($category->name) . '">';
              echo '        <h3 class="card-title">' . htmlspecialchars($category->name) . '</h3>';
              echo '      </a>';
              echo '    </div>';
              echo '  </div>';
              echo '</li>';
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
