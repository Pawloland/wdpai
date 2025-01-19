<?php

class User
{
    public function __construct(
        public int    $ID_User = -1,
        public int    $ID_User_Type = -1,
        public string $user_name = '',
        public string $user_surname = '',
        public string $nick = '',
        public string $password_hash = ''
    )
    {

    }

}