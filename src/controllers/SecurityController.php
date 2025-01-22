<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../repository/ClientRepository.php';
require_once __DIR__ . '/../components/SecurityComponent.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController
{
    private ClientRepository $clientRepository;
    private SecurityComponent $securityComponent;
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->clientRepository = new ClientRepository();
        $this->securityComponent = new SecurityComponent();
        $this->userRepository = new UserRepository();
    }


    public function login(): void
    {
        if ($this->securityComponent->updateAuthCookie()) {
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

        $client = $this->clientRepository->getClient($email);
        if (!$client || !password_verify($password, $client->password_hash)) { //user does not exist or password is incorrect
            $this->render('login', [
                'message' => 'Niepoprawny email lub hasło',
                'defaults' => ['email' => $email]
            ]);
            return;
        }

        $this->securityComponent->createAuthCookie($email);

        $_SESSION['messages'] = ['message' => 'Zalogowano pomyślnie'];
        header('Location: /');
    }

    public function adminLogin(): void
    {
        if ($this->securityComponent->updateAdminAuthCookie()) {
            header('Location: /admin_panel');
            return;
        } //skip login page if active session exists


        if (!$this->isPost()) {
            $this->render('login', [
                'admin_variant' => true
            ]);
            return;
        }

        try {
            $nick = $_POST['nick'];
            $password = $_POST['password'];
        } catch (Exception $e) {
            $this->render('login', [
                'admin_variant' => true,
                'message' => 'Niepoprawne dane'
            ]);
            return;
        }

        $user = $this->userRepository->getUser($nick);
        if (!$user || !password_verify($password, $user->password_hash)) { //user does not exist or password is incorrect
            $this->render('login', [
                'admin_variant' => true,
                'message' => 'Niepoprawny email lub hasło',
                'defaults' => ['nick' => $nick]
            ]);
            return;
        }

        $this->securityComponent->createAdminAuthCookie($nick);

        $_SESSION['messages'] = ['message' => 'Zalogowano pomyślnie'];
        header('Location: /admin_panel');

    }

    public function logout(): void
    {
        $this->securityComponent->destroyAuthCookie();
        session_unset();
        session_destroy();
        header('Location: /');
    }

    public function adminLogout(): void
    {
        $this->securityComponent->destroyAdminAuthCookie();
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
        $user = new Client(
            nick: $email,
            password_hash: $hash,
            mail: $email
        );

        try {
            $this->clientRepository->addClient($user);
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
