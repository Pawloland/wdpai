<?php
require_once 'Repository.php';
require_once __DIR__ . '/../models/Reservation.php';


class ReservationRepository extends Repository
{
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