<?php
require_once 'src/repository/MovieRepository.php';
$movieRepository = new MovieRepository();
?>

<!DOCTYPE html>

<head>
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
    <ul>
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
    foreach ($movieRepository->getAllMovies() as $movie) {
        $imagePath = '/public/img/posters/' . $movie->poster;
        $title = $movie->title;
        include 'components/poster.php';
    }
    ?>

</main>


<!--<div class="container">-->
<!--    <div class="logo">-->
<!--        <img src="public/img/logo.png">-->
<!--    </div>-->
<!--    <div class="login-container">-->
<!--        <form class="login" action="login" method="POST">-->
<!--            <div class="messages">-->
<!--                --><?php
//                if (isset($messages)) {
//                    foreach ($messages as $message) {
//                        echo $message;
//                    }
//                }
//                ?>
<!--            </div>-->
<!--            <input name="email" type="text" placeholder="email@email.com">-->
<!--            <input name="password" type="password" placeholder="password">-->
<!--            <button type="submit">LOGIN</button>-->
<!--        </form>-->
<!--    </div>-->
<!--</div>-->
</body>