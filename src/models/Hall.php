<?php

class Hall
{
    public function __construct(
        public int     $ID_Hall = -1,
        public ?string $hall_name = null
    )
    {

    }
}