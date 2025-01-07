<?php
require_once 'src/repository/LanguageRepository.php';
$languageRepository = new LanguageRepository();
?>
<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
    <!--    <link rel="stylesheet" type="text/css" href="/public/css/movie_select.css">-->
    <!--    <link rel="stylesheet" type="text/css" href="/public/css/poster.css">-->
    <!--    <link rel="stylesheet" type="text/css" href="/public/css/auth_form.css">-->
    <link rel="stylesheet" type="text/css" href="/public/css/admin_panel.css">
    <link rel="icon" type="image/png" href="/public/img/assets/logo.png">
    <script src="/public/js/hamburger.js" defer></script>
    <title>ADMIN PAGE</title>
</head>

<body>

<header>
    <img src="/public/img/assets/logo.png" alt="Biletron" width="100" height="100">
    <h1>Panel administracyjny</h1>
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
    <div>


        <form action="/upload" method="post" enctype="multipart/form-data">
            <table>
                <tbody>
                <tr>
                    <th>
                        <label for="title">tytuł</label>
                    </th>
                    <td>
                        <input id="title" type="text" name="title" value="<?= $defaults['title'] ?? ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="original_title">oryginalny tytuł</label>
                    </th>
                    <td>
                        <input id="original_title" type="text" name="original_title" value="<?= $defaults['original_title'] ?? ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="duration">długość</label>
                    </th>
                    <td>
                        <input id="duration" type="time" name="duration" step="1" value="<?= $defaults['duration'] ?? ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="description">opis</label>
                    </th>
                    <td>
                        <textarea id="description" name="description" required><?= $defaults['description'] ?? ''; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="language">język</label>
                    </th>
                    <td>
                        <?php
                        $skip_label = true;
                        $id = "language";
                        $name = "language";
                        $array = $languageRepository->getAllLanguagesKV();
                        $default_option = $defaults['language'] ?? '';
                        include 'components/select.php';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="dubbing">dubbing</label>
                    </th>
                    <td>
                        <?php
                        $id = "dubbing";
                        $name = "dubbing";
                        $default_option = $defaults['dubbing'] ?? '';
                        include 'components/select.php';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="subtitles">napisy</label>
                    </th>
                    <td>
                        <?php
                        $id = "subtitles";
                        $name = "subtitles";
                        $label = "Subtitles:";
                        $default_option = $defaults['subtitles'] ?? '';
                        include 'components/select.php';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="poster">Poster (2MiB max)</label>
                    </th>
                    <td>
                        <input id="poster" type="file" name="image" required accept="image/png, image/gif, image/jpeg">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Dodaj film">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?= $upload ?? ''; ?>
                    </td>
                </tr>
                </tbody>
            </table>

        </form>
    </div>

    <div>
        <form action="#" method="post">
            <table>
                <tbody>
                <tr>
                    <th>
                        <label for="as_date">Data</label>
                    </th>
                    <td>
                        <input id="as_date" type="date" name="date" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="as_hour">Godzina startu</label>
                    </th>
                    <td>
                        <input id="as_hour" type="time" name="time" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="as_type">Typ seansu</label>
                    </th>
                    <td>
                        <select id="as_type">
                            <option value="2D">2D</option>
                            <option value="3D">3D</option>
                            <option value="4D">4D</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="as_hall">Sala</label>
                    </th>
                    <td>
                        <select id="as_hall">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Dodaj seans">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?= $add_screening ?? ''; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div>
        <form action="#" method="post">
            <table>
                <tbody>
                <tr>
                    <th>
                        <label for="ds_date">Data</label>
                    </th>
                    <td>
                        <input id="ds_date" type="date" name="date" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ds_hour">Godzina startu</label>
                    </th>
                    <td>
                        <input id="ds_hour" type="time" name="time" required>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ds_hall">Sala</label>
                    </th>
                    <td>
                        <select id="ds_hall" name="hall" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Usuń seans">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?= $add_screening ?? ''; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>


    <div>
        Klienci:
    </div>
    <div>
        Rezerwacje:
    </div>
    <div>
        Konta administracyjne:
    </div>
    <div>
        Sesje:
    </div>


    <?php
    if (isset($messages)) {
        foreach ($messages as $message) {
            var_dump($message);
        }
    }
    ?>

</main>

</body>