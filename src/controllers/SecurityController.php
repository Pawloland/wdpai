<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/SessionRepository.php';

class SecurityController extends AppController
{
    protected const int days = 0;
    protected const int hours = 0;
    protected const int minutes = 10;

    protected const string cookie_name = 'auth';

    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->sessionRepository = new SessionRepository();
    }

    private function createAuthCookie(string $email): void
    {
        $expiration_date = '';

        $token = $this->sessionRepository->createSession($email, static::days, static::hours, static::minutes, $expiration_date);

        $cookieValue = json_encode(['token' => $token, 'email' => $email]);
        setcookie(static::cookie_name, $cookieValue, [
            'expires' => strtotime($expiration_date . ' UTC'),
            'path' => '/',
            'samesite' => 'Strict',
            'secure' => true,
            'httponly' => true
        ]);
    }

    private function updateAuthCookie(): bool
    {
        if (!isset($_COOKIE[static::cookie_name])
            or !($auth = json_decode($_COOKIE[static::cookie_name], true))
            or !isset($auth['token'])
            or !isset($auth['email'])) {
            return false;
        }
        $expiration_date = '';
        $is_valid = $this->sessionRepository->checkSession($auth['email'], $auth['token'], static::days, static::hours, static::minutes, $expiration_date);

        $cookieValue = json_encode(['token' => $auth['token'], 'email' => $auth['email']]);
        setcookie(static::cookie_name, $cookieValue, [
            'expires' => $is_valid ? strtotime($expiration_date . ' UTC') : 1,
            'path' => '/',
            'samesite' => 'Strict',
            'secure' => true,
            'httponly' => true
        ]);
        return $is_valid;
    }

    private function destroyAuthCookie(): void
    {
        try {
            $auth = json_decode($_COOKIE[static::cookie_name], true);
            $this->sessionRepository->deleteSession($auth['email'], $auth['token']);
        } catch (Exception $e) {
        }
        // Purge the cookie by setting its value to an empty string and its expiration date to a time in the past
        setcookie(static::cookie_name, '', [
            'expires' => 1,
            'path' => '/',
            'samesite' => 'Strict', // Changed to 'Strict'
            'secure' => true, // if your site is served over HTTPS
            'httponly' => true
        ]);
    }


    public function login(): void
    {
        if ($this->updateAuthCookie()) {
            header('Location: /');
            return;
        } //skip login page if active session exists


        if (!$this->isPost()) {
            $this->render('login');
            return;
        }

        try {
            $email = $_POST['email'];
            $password = $_POST['password'];
        } catch (Exception $e) {
            $this->render('login', [
                'message' => 'Niepoprawne dane'
            ]);
            return;
        }

        $user = $this->userRepository->getUser($email);
        if (!$user || !password_verify($password, $user->password)) { //user does not exist or password is incorrect
            $this->render('login', [
                'message' => 'Niepoprawny email lub hasło',
                'defaults' => ['email' => $email]
            ]);
            return;
        }

        $this->createAuthCookie($email);

        $this->render('login', [
            'message' => 'Zalogowano pomyślnie'
        ]);
    }

    public function logout(): void
    {
        $this->destroyAuthCookie();
        session_unset();
        session_destroy();
        header('Location: /');
    }

    public function register(): void
    {
        if (!$this->isPost()) {
            $this->render('register');
            return;
        }

        try {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password_rep = $_POST['password_rep'];
        } catch (Exception $e) {
            $this->render('register', [
                'message' => 'Niepoprawne dane'
            ]);
            return;
        }

        if ($password !== $password_rep) {
            $this->render('register', [
                'message' => 'Różne hasła',
                'defaults' => ['email' => $email]
            ]);
            return;
        } else if (strlen($password) < 8) {
            $this->render('register', [
                'message' => 'Hasło za krótkie',
                'defaults' => ['email' => $email]
            ]);
            return;
        }
        // generate password hash using bcrypt
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User($email, $hash, '', '');

        try {
            $this->userRepository->addUser($user);
        } catch (Exception $e) {
            $this->render('register', [
                'message' => 'Użytkownik o podanym adresie email już istnieje',
                'defaults' => ['email' => $email]
            ]);
            return;
        }

        $_SESSION['messages'] = ['message' => 'Zarejestrowano pomyślnie - zaloguj się'];
        header('Location: /login');
    }
}
