<?php
require_once 'src/repository/ScreeningRepository.php';
$screeningRepository = new ScreeningRepository();

?>

<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/auth_form.css">
    <link rel="stylesheet" type="text/css" href="/public/css/select_place.css">
    <link rel="stylesheet" type="text/css" href="/public/css/poster.css">
    <link rel="icon" type="image/png" href="/public/img/assets/logo.png">
    <script src="/public/js/hamburger.js" defer></script>
    <script src="/public/js/select_place.js" defer></script>
    <title>SELECT PLACE PAGE</title>
</head>

<body>

<header>
    <img src="/public/img/assets/logo.png" alt="Biletron" width="100" height="100">
    <h1>Rezerwacja</h1>
    <ul>
        <li>
            <a href="/">
                <span class="icon icon-home"></span>
                Strona główna
            </a>
        </li>
        <li>
            <?php if (isset($_COOKIE['auth'])): ?>
                <a href="/logout">
                    <span class="icon icon-logout"></span>
                    <?= json_decode($_COOKIE['auth'], true)['email'] ?>
                </a>
            <?php else: ?>
                <a href="/register">
                    <span class="icon icon-pen"></span>
                    Zarejestruj
                </a>
            <?php endif; ?>
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
    <div class="left">
        <?php
        if (isset($messages['movie'])) {
            $movie = $messages['movie'];
            $ID_Movie = $movie->ID_Movie;
            $posterID = $movie->poster;
            $title = $movie->title;
        }
        include 'components/poster.php';
        ?>
    </div>
    <form class="right">
        <?php
        $data = $screeningRepository->getScreeningsByMovieIdAssoc($movie->ID_Movie ?? -1);
        ?>
        <div class="room">
            <div class="screen">
                <p>
                     
                    <!-- this will be populated with Hall number from js with data embedded in script tag as json -->
                </p>
                <p>
                     
                    <!-- this will be populated Screening Type name from js with data embedded in script tag as json -->
                </p>
            </div>
            <div class="seats">
                <!-- this will be populated with seats from js with data embedded in script tag as json -->
            </div>
        </div>
        <div class="details">
            <?php
            $skip_label = false;
            $label = 'Data i godzina startu';
            $name = 'start';
            $id = 'start';
            $array = $data['kv'];
            include 'components/select.php';
            ?>
            <div class="summary">
                <span>Typ biletu</span>
                <span>Ilość</span>
            </div>
            <div class="summary specific">
                <span>Standard</span>
                <span>0</span>
            </div>
            <div class="summary specific">
                <span>Premium</span>
                <span>0</span>
            </div>
            <input type="text" placeholder="Wpisz kod rabatowy">

            <div class="summary">
                <span>Suma:</span>
                <span>90,99zł</span>
            </div>
            <input type="submit" value="Potwierdzam i płacę">
        </div>
    </form>
</main>
<script>
    let data = JSON.parse(`<?= json_encode($data['data']); ?>`)
</script>

</body>