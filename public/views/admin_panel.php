<?php
require_once 'src/repository/LanguageRepository.php';
$languageRepository = new LanguageRepository();
?>
<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/movie_select.css">
    <link rel="stylesheet" type="text/css" href="/public/css/poster.css">
    <link rel="stylesheet" type="text/css" href="/public/css/auth_form.css">
    <link rel="icon" type="image/png" href="/public/img/assets/logo.png">
    <script src="/public/js/hamburger.js" defer></script>
    <title>Admin PAGE</title>
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
    <form class="auth" action="/upload" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input id="title" type="text" name="title" required>

        <label for="original_title">Original Title:</label>
        <input id="original_title" type="text" name="original_title" required>

        <label for="duration">Duration:</label>
        <input id="duration" type="time" name="duration" step="1" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <?php
        $id = "language";
        $name = "language";
        $label = "Language:";
        $array = $languageRepository->getAllLanguagesKV();
        include 'components/select.php';
        ?>

        <?php
        $id = "dubbing";
        $name = "dubbing";
        $label = "Dubbing:";
        include 'components/select.php';
        ?>

        <?php
        $id = "subtitles";
        $name = "subtitles";
        $label = "Subtitles:";
        include 'components/select.php';
        ?>

        <label for="poster">Poster:</label>
        <input id="poster" type="file" name="image" required accept="image/png, image/gif, image/jpeg">

        <input type="submit" value="Upload">
    </form>
    <?php
    if (isset($messages)) {
        foreach ($messages as $message) {
            echo $message;
        }
    }
    ?>

</main>

</body>