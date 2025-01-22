<?php
require_once 'Repository.php';
require_once 'src/models/User.php';

class UserRepository extends Repository
{
    public function getUser(string $nick): ?User
    {
        try {
            return $this->getDBClass(
                User::class,
                'SELECT * FROM "User" WHERE "nick" = ?',
                $nick
            );
        } catch (Exception $e) {
            return null;
        }
    }

    public function getAllUsersAssoc(): array
    {
        return $this->getDBAssocArray(
            '
            SELECT
                "User"."ID_User",
                "User_Type"."type_name",
                "User"."user_name",
                "User"."user_surname",
                "User"."nick"
            FROM "User"
                natural join "User_Type"
            order by "ID_User"
            '
        );
    }

    public function removeUser(int $ID_User): void
    {
        $this->getDBAssocArray(
            'DELETE FROM "User" WHERE "ID_User" = ?',
            $ID_User
        );
    }

    public function checkPermission(string $nick, string $perm_name): bool
    {
        try {
            return $this->getDBAssocArray(
                '
                SELECT EXISTS (
                    SELECT 1
                    FROM "User" 
                        NATURAL JOIN "User_Type" 
                        NATURAL JOIN "UserType_Permissions" 
                        NATURAL JOIN "Permissions" 
                    WHERE "nick" = ?
                        AND "perm_name" = ?
                ) AS has_permission',
                $nick,
                $perm_name
            )[0]['has_permission'];
        } catch (Exception $e) {
            return false;
        }
    }

}