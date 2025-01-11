<?php

require_once 'Repository.php';

class SessionRepository extends Repository
{

    public function createSession(string $mail, int $days, int $hours, int $minutes): string
    {
        $token = $this->getDB('
            Select create_session((Select "ID_Client" from "Client" where "mail" = ?), \'c\' , ?, ?, ?) as session_token;
        ',
            $mail,
            $days,
            $hours,
            $minutes
        );


        return $token[0]['session_token'];

    }

    public function checkSession(string $mail, string $token, int $days, int $hours, int $minutes): bool
    {
        $result = $this->getDB('
            Select check_session((Select "ID_Client" from "Client" where "mail" = ?), \'c\', ?, ?, ?, ?) as is_valid; 
        ',
            $mail,
            $token,
            $days,
            $hours,
            $minutes
        );

        return $result[0]['is_valid'];
    }

    public function deleteSession(string $mail, string $token): bool
    {
        $result = $this->getDB('
            Select delete_session((Select "ID_Client" from "Client" where "mail" = ?), \'c\', ?) as is_deleted;
        ',
            $mail,
            $token
        );
        return $result[0]['is_deleted'];
    }
}
