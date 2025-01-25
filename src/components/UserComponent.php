<?php
require_once __DIR__ . '/../repository/UserRepository.php';

class UserComponent
{

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function verifyUser(string $nick, string $password): bool
    {
        $user = $this->userRepository->getUser($nick);
        return $user && password_verify($password, $user->password_hash);

    }

}