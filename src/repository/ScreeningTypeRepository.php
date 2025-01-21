<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/ScreeningType.php';

class ScreeningTypeRepository extends Repository
{
    public function getAllScreeningTypes(): array
    {
        return $this->getDBClassesArray(
            ScreeningType::class,
            'SELECT * FROM "Screening_Type"'
        );
    }

    public function getScreeningTypeByID(int $ID_Screening_Type): ?ScreeningType
    {
        try {
            return $this->getDBClass(
                ScreeningType::class,
                'SELECT * FROM "Screening_Type" WHERE "ID_Screening_Type" = ?',
                $ID_Screening_Type
            );
        } catch (Exception $e) {
            return null;
        }
    }

    public function getAllScreeningTypesKV()
    {
        $data = $this->getDBAssocArray(
            'SELECT "ID_Screening_Type", "screening_name" FROM "Screening_Type"'
        );

        $result = [];
        foreach ($data as $row) {
            $result[$row['ID_Screening_Type']] = $row['screening_name'];
        }
        return $result;
    }
}