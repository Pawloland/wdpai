<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Language.php';

class LanguageRepository extends Repository
{
    //as of php 8.4 typed arrays aren't supported, so only doc block is used
    /**
     * @return Language[]
     */
    public function getAllLanguages(): array
    {
        return $this->getDBClassesArray(
            Language::class,
            'SELECT * FROM "Language"'
        );
    }

    /**
     * Returns an associative array where:
     * - The key is the `ID_Language` (string) from the database.
     * - The value is the `language_name` (string) corresponding to that ID.
     *
     * @return array<string, string> // Key is `ID_Language`, value is `language_name`
     */
    public function getAllLanguagesKV(): array
    {
        $query = '
            SELECT "ID_Language", "language_name" FROM "Language"
            ORDER BY 
                CASE 
                    WHEN "language_name" = \'polish\' THEN 1
                    WHEN "language_name" = \'english\' THEN 2
                    WHEN "language_name" = \'ukrainian\' THEN 3
                    ELSE 4
                END, "language_name"
        ';

        $result = [];
        foreach ($this->getDBAssocArray($query) as $language) {
            $result[$language["ID_Language"]] = $language["language_name"];
        }
        return $result;

    }
}
