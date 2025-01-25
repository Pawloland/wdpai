<?php
require_once __DIR__ . '/../repository/ClientRepository.php';

class ClientComponent
{

    private ClientRepository $clientRepository;

    public function __construct()
    {
        $this->clientRepository = new ClientRepository();
    }

    public function verifyClient(string $email, string $password): bool
    {
        $client = $this->clientRepository->getClient($email);
        return $client && password_verify($password, $client->password_hash);
    }


    public function addClient(string $email, string $password): bool
    {
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
            return false;
        }

        return true;
    }

}