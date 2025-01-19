<?php

class Screening
{
    public string $start_time_string {
        get => $this->start_time->format('Y.m.d H:i:s');
    }

    public function __construct(
        public int      $ID_Screening = -1,
        public int      $ID_Movie = -1,
        public int      $ID_Hall = -1,
        public int      $ID_Screening_Type = -1,
        public DateTime $start_time = new DateTime('1970-01-01 00:00:00') {
            set(string|DateTime $value) {
                if (is_string($value)) {
                    $a = new DateTime($value);
                    $this->start_time = $a;
                } elseif ($value instanceof DateTime) {
                    $this->start_time = $value;
                } else {
                    throw new InvalidArgumentException('Invalid type for duration');
                }

            }
        }
    )
    {
    }
}