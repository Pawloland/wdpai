<?php

class Database
{
    private static ?Database $instance = null;

    private readonly string $username;
    private readonly string $password;
    private readonly string $host;
    private readonly string $port;
    private readonly string $database;

    // Make the constructor private to enforce singleton
    private function __construct()
    {
        $this->username = getenv('POSTGRES_USER');
        $this->password = getenv('POSTGRES_PASSWORD');
        $this->host = "db"; //host is docker-compose service name, not localhost nor POSTGRES_DB env variable
        // it only coincidentally matches the POSTGRES_DB env variable
        $this->port = getenv('PGPORT');
        $this->database = getenv('POSTGRES_DB');
    }

    // Static method to get the single instance of the class
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Provide a connection method that remains unchanged
    public function connect(string $default_session_postgres_timezone_name = 'UTC'): PDO
    {
        try {
            $conn = new PDO(
                "pgsql:host=$this->host;port=$this->port;dbname=$this->database",
                $this->username,
                $this->password
            );


            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Set the session-wide default timezone

            // I know it is ugly and maybe less safe than prepared statements, but prepared statements do not work with "SET TIME ZONE ?"
            $stmt = $conn->prepare("SET TIME ZONE " . $conn->quote($default_session_postgres_timezone_name));
            $stmt->execute();

            return $conn;
        } catch (PDOException $e) {

            // TODO: error page redirect
            die("Connection failed: " . $e->getMessage());

        }
    }

    public function disconnect(PDO &$connection): void
    {
        // Disconnect from database by destroying the PDO object
        $connection = null;
    }
}