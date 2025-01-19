<?php

class ScreeningType
{
    public function __construct(
        public int    $ID_Screening_Type = -1,
        public string $screening_name = '',
        public float  $price = -1
    )
    {
    }
}