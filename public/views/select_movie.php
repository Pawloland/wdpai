<?php
require_once 'src/repository/MovieRepository.php';
$movieRepository = new MovieRepository();
?>

<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/movie_select.css">
    <link rel="stylesheet" type="text/css" href="/public/css/poster.css">
    <link rel="icon" type="image/png" href="/public/img/assets/logo.png">
    <script src="/public/js/hamburger.js" defer></script>
    <title>SELECT MOVIE PAGE</title>
</head>

<body>

<header>
    <img src="/public/img/assets/logo.png" alt="Biletron" width="100" height="100">
    <h1>Twój system do kupowania biletów on-line!</h1>
    <?php if (isset($message)) : ?>
        <?= $message ?>
    <?php endif; ?>
    <ul>
        <?php if (isset($_COOKIE['auth'])): ?>
            <li>
                <a href="/logout">
                    <span class="icon icon-logout"></span>
                    <?= json_decode($_COOKIE['auth'], true)['email'] ?>
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="/login">
                    <span class="icon icon-pen"></span>
                    Zaloguj
                </a>
            </li>
            <li>
                <a href="/register">
                    <span class="icon icon-pen"></span>
                    Zarejestruj
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <ul>
        <li>
            <a>
                <span class="icon icon-hamburger"></span>
            </a>
        </li>
    </ul>
</header>
<main>
    <?php
    $link = true;
    foreach ($movieRepository->getAllMoviesThatHaveScreeningsNotBeforeDateTime() as $movie) {
        $ID_Movie = $movie->ID_Movie;
        $posterID = $movie->poster;
        $title = $movie->title;
        include 'components/poster.php';
    }
    ?>

</main>

</body>