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

    public function getDiscount(string $discount_code): float
    {
        $amount = $this->getDBAssocArray(
            'SELECT "amount" FROM "Discount" WHERE "discount_name" = ?',
            $discount_code
        )[0]['amount'];
        return $amount ? floatval($amount) : 0;
    }

    public function getDiscountID(string $discount_code): ?int
    {
        $id = $this->getDBAssocArray(
            'SELECT "ID_Discount" FROM "Discount" WHERE "discount_name" = ?',
            $discount_code
        );
        return count($id) == 0 ? null : $id[0]['ID_Discount'];
    }

//    public function addReservation(int $ID_Screening, int $ID_Seat, int $ID_Client, string $discount_code): Reservation
//    {
//        $ID_Discount = $this->getDBAssocArray(
//            'SELECT "ID_Discount" FROM "Discount" WHERE "discount_name" = ?',
//            $discount_code
//        );
//        // if size of returned array is 0, then discount code is invalid and set it to null, alese extract the first element from array
//        $ID_Discount = count($ID_Discount) == 0 ? null : $ID_Discount[0]['ID_Discount'];
//
//        $new_reservation = $this->getDBClassTZ(
//            Reservation::class,
//            'SELECT * FROM new_reservation(?, ?, ?, ?, ?,    ?,?,?,?,?,?,? )',
//            Database::CLIENT_TIMEZONE,
//            $ID_Seat,
//            $ID_Screening,
//            $ID_Discount,
//            $ID_Client,
//            23,
//
//            null, null, null, null, null, null, null
//        );
//
//        return $new_reservation;
//
//
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
            return $this->getDBClassTZ(
                Reservation::class,
                'SELECT * from new_reservation(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                Database::CLIENT_TIMEZONE,
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


    public function removeReservation(int $ID_Reservation): void
    {
        $this->getDBAssocArray(
            'DELETE FROM "Reservation" WHERE "ID_Reservation" = ?',
            $ID_Reservation
        );
    }
}