<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
    <link rel="stylesheet" type="text/css" href="/public/css/auth_form.css">
    <link rel="icon" type="image/png" href="/public/img/assets/logo.png">
    <script src="/public/js/hamburger.js" defer></script>
    <title>LOGIN PAGE</title>
</head>

<body>

<header>
    <img src="/public/img/assets/logo.png" alt="Biletron" width="100" height="100">
    <h1>Logowanie</h1>
    <ul>
        <li>
            <a href="/">
                <span class="icon icon-home"></span>
                Strona główna
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
    <form class="auth" action="/login" method="post">
        <label for="email">E-mail:</label>
        <input id="email" type="email" name="email" value="<?= $defaults['email'] ?? ''; ?>" required>
        <label for="password">Hasło:</label>
        <input id="password" type="password" name="password" required>
        <input type="submit" value="Zaloguj">
        <label><?= $message ?? ''; ?></label>
    </form>
</main>


</body>