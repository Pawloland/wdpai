<?php

require_once 'Repository.php';

class SessionRepository extends Repository
{

    public function getAllClientSessionsNoTokenAssoc(): array
    {
        return $this->getDBAssocArrayTZ(
            '
            Select
                "Client_sessions"."ID_Session_Client",
                "Client"."nick",
                "Client_sessions"."expiration_date" at time zone ? -- converts timestamp with time zone to timestamp without time zone (drops +01 in the string representation)
            from "Client_sessions" 
                natural join "Client"
            where expiration_date > now()
            ',
            'Europe/Warsaw',
            'Europe/Warsaw'
        );
    }

    public function getAllUserSessionsNoTokenAssoc(): array
    {
        return $this->getDBAssocArray(
            '
            Select
                "User_sessions"."ID_Session_User",
                "User"."nick",
                "User_sessions"."expiration_date"
            from "User_sessions" 
                natural join "User"
            where expiration_date > now()
            '
        );
    }


    public function createSession(string $mail, int $days, int $hours, int $minutes, string &$expiration_date): ?string
    {
        $result = $this->getDBAssocArray('
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
        $result = $this->getDBAssocArray('
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
            $result = $this->getDBAssocArray('
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
