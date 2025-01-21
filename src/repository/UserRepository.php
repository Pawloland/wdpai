<?php
require_once 'Repository.php';
require_once 'src/models/User.php';

class UserRepository extends Repository
{
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

}