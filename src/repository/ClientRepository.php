<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Client.php';

class ClientRepository extends Repository
{
    public function getClient(string $email): ?Client
    {
        try {
            return $this->getDBClass(
                Client::class,
                'SELECT * FROM "Client" WHERE mail = ?',
                $email
            );
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function addClient(Client $client): void
    {
        try {
            $this->getDBAssocArray(
                "SELECT new_user('', '', ?, ?, ?)", //name and surname pass as empty strings
                $client->mail, // use as nickname
                $client->password_hash,
                $client->mail
            );
        } catch (Exception $e) {
            // Handle exceptions (log, rethrow, or display an error message)
            throw new Exception('Failed to add user: ' . $e->getMessage());
        }
    }

    /**
     * @return Client[]
     */
    public function getAllClients(): array
    {
        return $this->getDBClassesArray(
            Client::class,
            'SELECT * FROM "Client" order by "ID_Client"'
        );
    }

    public function removeClient(int $ID_Client): void
    {
        $this->getDBAssocArray(
            'DELETE FROM "Client" WHERE "ID_Client" = ?',
            $ID_Client
        );
    }

}