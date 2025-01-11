<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/SessionRepository.php';

class SecurityController extends AppController
{

    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    public function __construct(
        protected int $days = 0,
        protected int $hours = 0,
        protected int $minutes = 10
    )
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->sessionRepository = new SessionRepository();
    }

    public function login(): void
    {
        if (isset($_SESSION['token'])
            and isset($_SESSION['email'])
            and $this->sessionRepository->checkSession($_SESSION['email'], $_SESSION['token'], $this->days, $this->hours, $this->minutes)
        ) {
            header('Location: /'); //redirect to main page if active session exists
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
        if (!$user || !password_verify($password, $user->password)) {
            $this->render('login', [
                'message' => 'Niepoprawny email lub hasło',
                'defaults' => ['email' => $email]
            ]);
            return;
        }

        $_SESSION['token'] = $this->sessionRepository->createSession($user->email, $this->days, $this->hours, $this->minutes);
        $_SESSION['email'] = $user->email;

        $this->render('login', [
            'message' => 'Zalogowano pomyślnie'
        ]);
    }

    public function logout(): void
    {
        $this->sessionRepository->deleteSession($_SESSION['email'], $_SESSION['token']);
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