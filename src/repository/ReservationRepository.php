<?php
require_once 'Repository.php';
require_once __DIR__ . '/../models/Reservation.php';


class ReservationRepository extends Repository
{
    public function getAllReservationsNotBeforeDateTimeAssoc(DateTime $start = new DateTime('now', new DateTimeZone(Database::CLIENT_TIMEZONE))): array
    {
        return $this->getDBAssocArrayTZ(
            '
            SELECT 
                "Reservation"."ID_Reservation",
                "Client"."mail",
                "Hall"."ID_Hall",
                "Reservation"."ID_Seat",
                "Seat"."row",
                "Seat"."number",
                "Seat_Type"."seat_name",
                "Movie"."title",
                "Screening_Type"."screening_name",
                "Screening"."start_time",
                "Reservation"."total_price_brutto"
            FROM "Reservation"
                NATURAL JOIN "Client"
                NATURAL JOIN "Seat"
                NATURAL JOIN "Hall"
                NATURAL JOIN "Seat_Type"
                JOIN "Screening" ON "Reservation"."ID_Screening" = "Screening"."ID_Screening"
                NATURAL JOIN "Movie"
                JOIN "Screening_Type" ON "Screening"."ID_Screening_Type" = "Screening_Type"."ID_Screening_Type"
            WHERE "Screening"."start_time" >= ?
            ORDER BY "Screening"."start_time", "Client"."mail"',
            Database::CLIENT_TIMEZONE,
            $start->format('Y-m-d H:i:s')
        );
    }

    public function getReservation(int $ID_Reservation): ?Reservation
    {
        try {
            return $this->getDBClass(
                Reservation::class,
                'SELECT * FROM "Reservation" WHERE "ID_Reservation" = ?',
                $ID_Reservation
            );
        } catch (Exception $e) {
            return null;
        }
    }

//    public function getPotentialNettoPriceForSeatIDAtScreeningID( int $ID_Screening, int $ID_Seat ): string {
//        try {
//            return $this->getDBAssocArray(
//                'SELECT * FROM calculate_price(?, )',
//                $ID_Reservation
//            );
//        } catch (Exception $e) {
//            return null;
//        }
//    }

    /**
     * @param int $ID_Screening
     * @return Reservation[]
     * @throws Exception
     */
    public function getReservationsForScreening(int $ID_Screening): array
    {
        return $this->getDBClassesArray(
            Reservation::class,
            'SELECT * FROM "Reservation" WHERE "ID_Screening" = ?',
            $ID_Screening
        );
    }

    /**
     * @param int $ID_Client
     * @return Reservation[]
     * @throws Exception
     */
    public function getReservationsForClient(int $ID_Client): array
    {
        return $this->getDBClassesArray(
            Reservation::class,
            'SELECT * FROM "Reservation" WHERE "ID_Client" = ?',
            $ID_Client
        );
    }

    /**
     * @throws Exception
     */
    public function addReservation(Reservation $reservation): Reservation
    {
        try {
            return $this->getDBClass(
                Reservation::class,
                'SELECT * from new_reservation(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                $reservation->ID_Seat,
                $reservation->ID_Screening,
                $reservation->ID_Discount,
                $reservation->ID_Client,
                $reservation->vat_percentage,
                $reservation->NIP,
                $reservation->NRB,
                $reservation->address_street,
                $reservation->address_nr,
                $reservation->address_flat,
                $reservation->address_city,
                $reservation->address_zip
            );
        } catch (Exception $e) {
            throw new Exception('Failed to add reservation: ' . $e->getMessage());
        }
    }
}