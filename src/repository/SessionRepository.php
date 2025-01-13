<?php

require_once 'Repository.php';

class SessionRepository extends Repository
{

    public function createSession(string $mail, int $days, int $hours, int $minutes, string &$expiration_date): ?string
    {
        $result = $this->getDB('
            Select * from create_session((Select "ID_Client" from "Client" where "mail" = ?), \'c\' , ?, ?, ?, ?);
        ',
            $mail,
            $days,
            $hours,
            $minutes,
            "UTC"
        );
        $expiration_date = $result[0]['expiration_date'];


        return $result[0]['session_token'];

    }

    public function checkSession(string $mail, string $token, int $days, int $hours, int $minutes, string &$expiration_date): bool
    {
        $result = $this->getDB('
            Select * from check_session((Select "ID_Client" from "Client" where "mail" = ?), \'c\', ?, ?, ?, ?, ?); 
        ',
            $mail,
            $token,
            $days,
            $hours,
            $minutes,
            "UTC"
        );

        $expiration_date = $result[0]['expiration_date'];
        return $result[0]['is_valid'];
    }

    public function deleteSession(string $mail, string $token): bool
    {
        try {
            $result = $this->getDB('
                Select delete_session((Select "ID_Client" from "Client" where "mail" = ?), \'c\', ?) as is_deleted;
            ',
                $mail,
                $token
            );
        } catch (Exception $e) {
            return false;
        }
        return $result[0]['is_deleted'];
    }
}
