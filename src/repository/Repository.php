<?php

require_once __DIR__.'/../../Database.php';

class Repository
{
    protected Database $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    /**
     * @throws Exception
     */
    protected function getDB(string $query, mixed ...$params): array
    {
        $connection = $this->database->connect();
        try {
            // Start a transaction
            $connection->beginTransaction();
            $stmt = $connection->prepare($query);
            $stmt->execute($params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Commit the transaction
            $connection->commit();
            $this->database->disconnect($connection);
            return $data;
        } catch (PDOException $e) {
            // Roll back the transaction if something failed
            $connection->rollBack();
            throw new Exception($e->getMessage());
        }
    }
}