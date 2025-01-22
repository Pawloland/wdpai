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
            <?php if (isset($_COOKIE['auth']) && (!isset($admin_variant) || !$admin_variant)): ?>
                <a href="/logout">
                    <span class="icon icon-logout"></span>
                    <?= json_decode($_COOKIE['auth'], true)['email'] ?>
                </a>
            <?php else: ?>
                <?php if (!isset($admin_variant) || !$admin_variant): ?>
                    <a href="/register">
                        <span class="icon icon-pen"></span>
                        Zarejestruj
                    </a>
                <?php endif; ?>
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
    <form class="auth" action="<?= (isset($admin_variant) && $admin_variant) ? '/adminLogin' : '/login' ?>" method="post">
        <?php if (isset($admin_variant) && $admin_variant): ?>
            <label for="nick">Nick:</label>
            <input id="nick" type="text" name="nick" value="<?= $defaults['nick'] ?? ''; ?>" required>
        <?php else: ?>
            <label for="email">E-mail:</label>
            <input id="email" type="email" name="email" value="<?= $defaults['email'] ?? ''; ?>" required>
        <?php endif; ?>
        <label for="password">Hasło:</label>
        <input id="password" type="password" name="password" required>
        <input type="submit" value="Zaloguj">
        <label><?= $message ?? ''; ?></label>
    </form>
</main>


</body>