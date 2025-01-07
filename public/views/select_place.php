<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/auth_form.css">
    <link rel="stylesheet" type="text/css" href="/public/css/select_place.css">
    <link rel="stylesheet" type="text/css" href="/public/css/poster.css">
    <link rel="icon" type="image/png" href="/public/img/assets/logo.png">
    <script src="/public/js/hamburger.js" defer></script>
    <title>SELECT PLACE PAGE</title>
</head>

<body>

<header>
    <img src="/public/img/assets/logo.png" alt="Biletron" width="100" height="100">
    <h1>Rezerwacja</h1>
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
    <div class="left">
        <?php
        if (isset($messages)) {
            $movie = $messages['movie'];
            $imagePath = '/public/img/posters/' . $movie->poster;
            $title = $movie->title;
        } else {
            $imagePath = '/public/img/posters/default';
            $title = "Missing title";
        }
        include 'components/poster.php';
        ?>
    </div>
    <form action="#" method="post" class="right">
        <div class="room">
            <div class="screen">
                <p>Sala 2</p>
                <p>
                    <!--                    <span class="icon icon-pen"></span>-->
                    Dubbing 2D
                </p>
            </div>
            <div class="seats">
                <table>
                    <?php
                    $rows = 10;
                    $cols = 6;
                    $letters = range('A', 'Z');
                    for ($i = 0; $i < $rows; $i++): ?>
                        <tr>
                            <th><?= $letters[$i] ?></th>
                            <?php
                            $cellsInRow = 1;  // Start with the first cell
                            for ($j = 1; $j < $cols; $j++):
                                $id = $i * ($cols - 1) + $j;
                                $cellsInRow++;
                                ?>
                                <td>
                                    <input type="checkbox" id="<?= $id ?>" name="<?= $id ?>">
                                </td>
                            <?php endfor;
                            ?>
                        </tr>
                    <?php endfor; ?>
                </table>

            </div>
        </div>
        <div class="details">
            <label for="date">Data</label>
            <input type="date" id="date" name="date" required>
            <label for="time">Godzina startu</label>
            <input type="time" id="time" name="time" required>
            <div class="summary">
                <span>Typ biletu</span>
                <span>Ilość</span>
            </div>
            <div class="summary specific">
                <span>Normalny</span>
                <span>10</span>
            </div>
            <div class="summary specific">
                <span>Ulgowy</span>
                <span>10</span>
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


</body>