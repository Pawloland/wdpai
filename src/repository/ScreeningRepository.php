<?php
require_once 'Repository.php';
require_once __DIR__ . '/../models/Screening.php';

class ScreeningRepository extends Repository
{

    /**
     * @param DateTime $start
     * @return Screening[]
     * @throws Exception
     */
    public function getAllScreeningsStartingNotBeforeDateTime(DateTime $start = new DateTime("now", new DateTimeZone(Database::CLIENT_TIMEZONE))): array
    {
        return $this->getDBClassesArrayTZ(
            Screening::class,
            'SELECT * FROM "Screening" WHERE "start_time" >= ? order by "start_time"',
            Database::CLIENT_TIMEZONE,
            $start->format('Y-m-d H:i:s')
        );
    }

    public function getAllScreeningsStartingNotBeforeDateTimeAssoc(DateTime $start = new DateTime("now", new DateTimeZone(Database::CLIENT_TIMEZONE))): array
    {
        return $this->getDBAssocArrayTZ(
            'SELECT 
                "Screening"."ID_Screening",
                "Screening"."start_time",
                "Movie"."title",
                "Screening"."ID_Hall",
                "Screening_Type"."screening_name"
            FROM "Screening"
                natural join "Movie"
                natural join "Screening_Type"
            WHERE "start_time" >= ? 
            order by "start_time"
        ',
            Database::CLIENT_TIMEZONE,
            $start->format('Y-m-d H:i:s')
        );
    }
}