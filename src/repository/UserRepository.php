<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository
{
    public function getAllUsers(): void
    {
        $users = $this->getDB('
            SELECT * FROM "Client"
        ');
        foreach ($users as $user) {
            echo implode(', ', $user) . "<br>\n";
        }
    }

    public function getUser(string $email): ?User
    {
        try {
            $user = $this->getDB(
                'SELECT * FROM "Client" WHERE mail = ?',
                $email
            );
            if (!$user) {
                return null;
            }
            return new User(
                $user[0]['mail'],
                $user[0]['password_hash'],
                $user[0]['client_name'],
                $user[0]['client_surname']
            );
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function addUser(User $user): void
    {

        //-- ----------------------------
        //-- Procedure structure for new_user
        //                           -- ----------------------------
        //    DROP FUNCTION IF EXISTS new_user;
        //CREATE OR REPLACE FUNCTION new_user(
        //        vCname VARCHAR(40),
        //    vCsurname VARCHAR(40),
        //    vNick VARCHAR(40),
        //    vPassHash VARCHAR(80),
        //    vMail VARCHAR(320)
        //)
        //    RETURNS VOID AS
        //$$
        //DECLARE
        //-- Declare any necessary variables (if needed)
        //        BEGIN
        //        -- Start the transaction
        //    SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
        //
        //    -- Check if the nickname or email already exists
        //    IF EXISTS (SELECT 1 FROM "Client" WHERE nick = vNick OR mail = vMail) THEN
        //        RAISE EXCEPTION 'Nazwa lub mail jest zajęta';
        //    END IF;
        //
        //    -- Insert the new user
        //    INSERT INTO "Client" (client_name, client_surname, nick, password_hash, mail)
        //    VALUES (vCname, vCsurname, vNick, vPassHash, vMail);
        //
        //    -- Commit the transaction
        //    COMMIT;
        //END;
        //$$ LANGUAGE plpgsql;

        try {
            // Prepare the SQL to call the database function
            $this->getDB(
                "SELECT new_user('', '', ?, ?, ?)",
                $user->email, // use as nickname
                $user->password,
                $user->email
            );
        } catch (Exception $e) {
            // Handle exceptions (log, rethrow, or display an error message)
            throw new Exception('Failed to add user: ' . $e->getMessage());
        }
    }

    public function getUserDetailsId(User $user): int
    {
//        $stmt = $this->database->connect()->prepare('
//            SELECT * FROM public.users_details WHERE name = :name AND surname = :surname AND phone = :phone
//        ');
//        $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
//        $stmt->bindParam(':surname', $user->surname, PDO::PARAM_STR);
//        $stmt->execute();
//
//        $data = $stmt->fetch(PDO::FETCH_ASSOC);
//        return $data['id'];
        return 1;
    }
}