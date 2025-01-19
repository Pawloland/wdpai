<?php

class Reservation

{
    public string $reservation_date_string {
        get => $this->reservation_date->format('Y-m-d H:i:s.u');
    }


    public function __construct(
        public int      $ID_Reservation = -1,
        public int      $ID_Seat = -1,
        public int      $ID_Screening = -1,
        public ?int     $ID_Discount = null,
        public ?int     $ID_Client = -1,
        public float    $total_price_netto = -1,
        public float    $total_price_brutto = -1,
        public float    $vat_percentage = -1,
        public DateTime $reservation_date = new DateTime('1970-01-01 00:00:00') {
            set (string|DateTime $value) {
                if (is_string($value)) {
                    $a = new DateTime($value);
                    $this->reservation_date = $a;
                } elseif ($value instanceof DateTime) {
                    $this->reservation_date = $value;
                } else {
                    throw new InvalidArgumentException('Invalid type for duration');
                }

            }
        },
        public ?string  $NIP = '',
        public ?string  $NRB = '',
        public ?string  $address_street = '',
        public ?string  $address_nr = '',
        public ?string  $address_flat = '',
        public ?string  $address_city = '',
        public ?string  $address_zip = ''
    )
    {
    }
}
