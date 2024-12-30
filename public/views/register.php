<!DOCTYPE html>

<head>
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
    <form class="auth">
        <label for="email">E-mail:</label>
        <input id="email" type="email" name="email">
        <label for="passwd">Hasło:</label>
        <input id="passwd" type="password" name="password">
        <label for="passwd_rep">Powtórz hasło:</label>
        <input id="passwd_rep" type="password" name="password">
        <input type="submit" value="Zarejestruj">
    </form>
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