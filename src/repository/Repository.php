<?php

require_once __DIR__.'/../../Database.php';

class Repository {
    protected Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    protected function getDB(string $query): array
    {
        $stmt = $this->database->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}