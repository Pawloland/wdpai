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

    public function getScreeningsByMovieIdAssoc(int $ID_Movie): array
    {
        $data = $this->getDBAssocArray('
            SELECT 
                "ID_Screening", 
                "start_time" AT TIME ZONE ? as "start_time",
                "ID_Hall",
                "screening_name",
                "price"
            FROM "Screening" 
                NATURAL JOIN "Screening_Type"
            WHERE "ID_Movie" = ?
                AND "start_time" >= now()
            ORDER BY "start_time"',
            Database::CLIENT_TIMEZONE,
            $ID_Movie
        );

        $kv = [];
        foreach ($data as $screening) {
            $kv[$screening["ID_Screening"]] = $screening["start_time"] . ' - ' . $screening["screening_name"];
        }

        foreach ($data as &$screening) {
            $screening['seats'] = $this->getDBAssocArray('
                SELECT 
                    s."ID_Hall",
                    s."ID_Seat",
                    s.row,
                    s.number,
                    st."ID_Seat_Type",
                    st.seat_name,
                    st.price,
                    CASE 
                        WHEN r."ID_Seat" IS NOT NULL THEN TRUE
                        ELSE FALSE
                    END AS is_taken
                FROM 
                    "Seat" s
                INNER JOIN 
                    "Seat_Type" st ON s."ID_Seat_Type" = st."ID_Seat_Type"
                INNER JOIN 
                    "Hall" h ON s."ID_Hall" = h."ID_Hall"
                INNER JOIN 
                    "Screening" scr ON scr."ID_Hall" = h."ID_Hall"
                LEFT JOIN 
                    "Reservation" r ON r."ID_Seat" = s."ID_Seat" AND r."ID_Screening" = scr."ID_Screening"
                WHERE 
                    scr."ID_Screening" = ?
                ORDER BY 
                    s.row, s.number
                ',
                $screening['ID_Screening']
            );
        }

        return [
            'kv' => $kv,
            'data' => $data
        ];
    }
}