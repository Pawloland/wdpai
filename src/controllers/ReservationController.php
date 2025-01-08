<?php

require_once 'AppController.php';

class ReservationController extends AppController
{

    public function select_place(): void
    {
        $this->render('select_place');
    }
}