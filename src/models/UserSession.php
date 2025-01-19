<?php

class UserSession
{
    public string $expiration_date_string {
        get => $this->expiration_date->format('Y-m-d H:i:s.u');
    }

    public function __construct(
        public int      $ID_Session_User = -1,
        public int      $ID_User = -1,
        public string   $session_token = '',
        public DateTime $expiration_date = new DateTime('1970-01-01 00:00:00') {
            set (string|DateTime $value) {
                if (is_string($value)) {
                    $this->expiration_date = new DateTime($value);
                } else {
                    $this->expiration_date = $value;
                }
            }
        }
    )
    {
    }
}