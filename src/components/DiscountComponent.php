<?php

require_once __DIR__ . '/../repository/ReservationRepository.php';
class DiscountComponent
{
    private ReservationRepository $reservationRepository;

    public function __construct()
    {
        $this->reservationRepository = new ReservationRepository();
    }

    public function getDiscount(string $discount_code): float
    {
        return $this->reservationRepository->getDiscount($discount_code);
    }


}