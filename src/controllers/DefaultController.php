<?php

require_once 'AppController.php';

class DefaultController extends AppController
{

    public function index(): void
    {
        $this->render('select_movie');
    }

    public function not_found(): void
    {
        $this->render('404');
    }
}