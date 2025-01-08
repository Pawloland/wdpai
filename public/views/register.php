<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/auth_form.css">
    <link rel="icon" type="image/png" href="/public/img/assets/logo.png">
    <script src="/public/js/hamburger.js" defer></script>
    <title>REGISTER PAGE</title>
</head>

<body>

<header>
    <img src="/public/img/assets/logo.png" alt="Biletron" width="100" height="100">
    <h1>Rejestracja</h1>
    <ul>
        <li>
            <a href="/">
                <span class="icon icon-home"></span>
                Strona główna
            </a>
        </li>
        <li>
            <a href="/login">
                <span class="icon icon-pen"></span>
                Zaloguj
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
    <form class="auth" method="post" action="/register">
        <label for="email">E-mail:</label>
        <input id="email" type="email" name="email" value="<?= $defaults['email'] ?? ''; ?>" required>
        <label for="password">Hasło:</label>
        <input id="password" type="password" name="password" minlength="8" required>
        <label for="password_rep">Powtórz hasło:</label>
        <input id="password_rep" type="password" name="password_rep" minlength="8" required>
        <input type="submit" value="Zarejestruj">
        <label><?= $message ?? ''; ?></label>
    </form>
</main>

</body>