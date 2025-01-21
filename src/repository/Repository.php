<?php

require_once __DIR__ . '/../../Database.php';

class Repository
{
    protected Database $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    /**
     * Executes a query and returns the results as an array of associative arrays.
     *
     * @param string $query The SQL query to execute.
     * @param mixed ...$params Optional parameters for the prepared statement.
     * @return array An array of associative arrays, where each associative array represents a row from the result set.
     *               If no rows are found, an empty array is returned.
     * @throws Exception If a database error occurs during query execution or the transaction process.
     */
    protected function getDBAssocArray(string $query, mixed ...$params): array
    {
        return $this->getDBAssocArrayTZ($query, 'UTC', ...$params);
    }

    /**
     * Executes a query and returns the results as an array of associative arrays.
     *
     * @param string $query The SQL query to execute.
     * @param string $TZ The timezone to use for the database connection in the format accepted by PostgreSQL.
     * @param mixed ...$params Optional parameters for the prepared statement.
     * @return array An array of associative arrays, where each associative array represents a row from the result set.
     *               If no rows are found, an empty array is returned.
     * @throws Exception If a database error occurs during query execution or the transaction process.
     */
    protected function getDBAssocArrayTZ(string $query, string $TZ, mixed ...$params): array
    {
        $connection = $this->database->connect($TZ);
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

    /**
     * Fetches objects of a specified class from the database.
     *
     * @template T
     * @param class-string<T> $className Fully qualified class name of the target object.
     * @param string $query The SQL query to execute.
     * @param mixed ...$params Parameters for the prepared statement.
     * @return T[] Array of objects of type $className.
     * @throws Exception If the class does not exist or a database error occurs.
     */
    protected function getDBClassesArray(string $className, string $query, mixed ...$params): array
    {
        return $this->getDBClassesArrayTZ($className, $query, 'UTC', ...$params);
    }

    /**
     * Fetches objects of a specified class from the database.
     *
     * @template T
     * @param class-string<T> $className Fully qualified class name of the target object.
     * @param string $query The SQL query to execute.
     * @param string $TZ The timezone to use for the database connection in the format accepted by PostgreSQL.
     * @param mixed ...$params Parameters for the prepared statement.
     * @return T[] Array of objects of type $className.
     * @throws Exception If the class does not exist or a database error occurs.
     */
    protected function getDBClassesArrayTZ(string $className, string $query, string $TZ, mixed ...$params): array
    {
        // Ensure the class exists
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class '$className' does not exist.");
        }

        $connection = $this->database->connect($TZ);
        try {
            // Start a transaction
            $connection->beginTransaction();
            $stmt = $connection->prepare($query);
            $stmt->execute($params);
            // Fetch data as objects of the specified class
            $data = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
            // first creates an object without passing any data to constructor,
            // then populates the properties of that object with the data from the database
            // and each property is additionally guarded by set property hook of each class field
            // which handles datatype conversion (especially for DateTime fields)
            // FETCH_PROPS_LATE is needed, to trigger set property hooks, otherwise it would not
            // handle datatype conversion and error out
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


    /**
     * Fetches the first object of a specified class from the database.
     *
     * @template T
     * @param class-string<T> $className Fully qualified class name of the target object.
     * @param string $query The SQL query to execute.
     * @param mixed ...$params Parameters for the prepared statement.
     * @return T The first object of type $className
     * @throws Exception If the class does not exist or a database error occurs.
     */
    protected function getDBClass(string $className, string $query, mixed ...$params): object
    {
        return $this->getDBClassTZ($className, $query, 'UTC', ...$params);
    }


    /**
     * Fetches the first object of a specified class from the database.
     *
     * @template T
     * @param class-string<T> $className Fully qualified class name of the target object.
     * @param string $query The SQL query to execute.
     * @param string $TZ The timezone to use for the database connection in the format accepted by PostgreSQL.
     * @param mixed ...$params Parameters for the prepared statement.
     * @return T The first object of type $className
     * @throws Exception If the class does not exist or a database error occurs.
     */
    protected function getDBClassTZ(string $className, string $query, string $TZ, mixed ...$params): object
    {
        // Ensure the class exists
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class '$className' does not exist.");
        }

        $connection = $this->database->connect($TZ);
        try {
            // Start a transaction
            $connection->beginTransaction();
            $stmt = $connection->prepare($query);
            $stmt->execute($params);

            // Fetch the first row as an object of the specified class
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
            // first creates an object without passing any data to constructor,
            // then populates the properties of that object with the data from the database
            // and each property is additionally guarded by set property hook of each class field
            // which handles datatype conversion (especially for DateTime fields)
            // FETCH_PROPS_LATE is needed, to trigger set property hooks, otherwise it would not
            // handle datatype conversion and error out
            $data = $stmt->fetch();

            // Return the first object or null if no rows were fetched
            if ($data === false) {
                throw new Exception('No data found');
            }
            // Commit the transaction
            $connection->commit();
            $this->database->disconnect($connection);


            return $data;
        } catch (Exception $e) {
            // Roll back the transaction if something failed
            $connection->rollBack();
            $this->database->disconnect($connection);
            throw new Exception($e->getMessage());
        }
    }




}