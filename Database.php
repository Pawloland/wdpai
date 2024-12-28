<?php

class Database
{
    private readonly string $username;
    private readonly string $password;
    private readonly string $host;
    private readonly string $port;
    private readonly string $database;

    public function __construct()
    {
        $this->username = getenv('POSTGRES_USER');
        $this->password = getenv('POSTGRES_PASSWORD');
        $this->host = "db"; //host is docker-compose service name, not localhost nor POSTGRES_DB env variable
        // it only coincidentally matches the POSTGRES_DB env variable
        $this->port = getenv('PGPORT');
        $this->database = getenv('POSTGRES_DB');
    }

    public function connect()
    {
        try {
            $conn = new PDO(
                "pgsql:host=$this->host;port=$this->port;dbname=$this->database",
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