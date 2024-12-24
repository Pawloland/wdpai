<?php

class Database
{
    private $username;
    private $password;
    private $host;
    private $database;

    public function __construct()
    {
        // TODO: Extract those vars to dockerfile as env vars
        $this->username = "docker";
        $this->password = "docker";
        $this->host = "db";
        $this->database = "db";
    }

    public function connect()
    {
        try {
            $conn = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database",
                $this->username,
                $this->password
            );

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            //TODO: error page redirect
            die("Connection failed: " . $e->getMessage());
        }
    }

    //TODO: disconnect method
}