<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController
{

    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login(): void
    {
        if (!$this->isPost()) {
            $this->render('login');
            $this->userRepository->getAllUsers();
            return;
        }

        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $user = $this->userRepository->getUser($email);

        if (!$user) {
            $this->render('login', ['messages' => ['User not found!']]);
            return;
        }

        if ($user->email !== $email) {
            $this->render('login', ['messages' => ['User with this email not exist!']]);
            return;
        }

        if ($user->password !== $password) {
            $this->render('login', ['messages' => ['Wrong password!']]);
            return;
        }

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/projects");
    }

    public function register(): void
    {
        if (!$this->isPost()) {
            $this->render('register');
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirmedPassword'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $phone = $_POST['phone'];

        if ($password !== $confirmedPassword) {
            $this->render('register', ['messages' => ['Please provide proper password']]);
            return;
        }

        //TODO try to use better hash function
        $user = new User($email, md5($password), $name, $surname);

        $this->userRepository->addUser($user);

        $this->render('login', ['messages' => ['You\'ve been succesfully registrated!']]);
    }
}