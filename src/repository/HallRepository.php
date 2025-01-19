<?php
require_once 'Repository.php';
require_once __DIR__ . '/../models/Hall.php';

class HallRepository extends Repository
{
    public function getAllHalls(): array
    {
        return $this->getDBClassesArray(
            Hall::class,
            'SELECT * FROM "Hall"'
        );
    }

    public function getHallByID(int $ID_Hall): ?Hall
    {
        try {
            return $this->getDBClass(
                Hall::class,
                'SELECT * FROM "Hall" WHERE "ID_Hall" = ?',
                $ID_Hall
            );
        } catch (Exception $e) {
            return null;
        }
    }
}

{

}