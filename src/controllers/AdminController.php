<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/MovieRepository.php';
require_once __DIR__ . '/../repository/ReservationRepository.php';
require_once __DIR__ . '/../repository/ScreeningRepository.php';
require_once __DIR__ . '/../repository/ClientRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class AdminController extends AppController
{

    private MovieRepository $movieRepository;
    private ReservationRepository $reservationRepository;
    private ScreeningRepository $screeningRepository;
    private ClientRepository $clientRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->movieRepository = new MovieRepository();
        $this->reservationRepository = new ReservationRepository();
        $this->screeningRepository = new ScreeningRepository();
        $this->clientRepository = new ClientRepository();
        $this->userRepository = new UserRepository();
    }


    private function removeX(string $key, Repository &$repository, string $method): void
    {
        if (!$this->isPost()) {
            header('Location: /admin_panel');
            return;
        }

        try {
            $repository->$method(intval($_POST[$key]));
            http_response_code(200);
            return;
        } catch (Exception $e) {
            http_response_code(500);
            return;
        }
    }


    public function removeMovie(): void
    {
        $this->removeX('ID_Movie', $this->movieRepository, 'removeMovie');
    }

    public function removeReservation(): void
    {
        $this->removeX('ID_Reservation', $this->reservationRepository, 'removeReservation');
    }

    public function removeScreening(): void
    {
        $this->removeX('ID_Screening', $this->screeningRepository, 'removeScreening');
    }

    public function removeClient(): void
    {
        $this->removeX('ID_Client', $this->clientRepository, 'removeClient');
    }

    public function removeUser(): void
    {
        $this->removeX('ID_User', $this->userRepository, 'removeUser');
    }


}
