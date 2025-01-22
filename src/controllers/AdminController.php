<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/MovieRepository.php';
require_once __DIR__ . '/../repository/ReservationRepository.php';
require_once __DIR__ . '/../repository/ScreeningRepository.php';
require_once __DIR__ . '/../repository/ClientRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../components/SecurityComponent.php';

class AdminController extends AppController
{

    private MovieRepository $movieRepository;
    private ReservationRepository $reservationRepository;
    private ScreeningRepository $screeningRepository;
    private ClientRepository $clientRepository;
    private UserRepository $userRepository;
    private SecurityComponent $securityComponent;

    public function __construct()
    {
        parent::__construct();
        $this->movieRepository = new MovieRepository();
        $this->reservationRepository = new ReservationRepository();
        $this->screeningRepository = new ScreeningRepository();
        $this->clientRepository = new ClientRepository();
        $this->userRepository = new UserRepository();
        $this->securityComponent = new SecurityComponent();
    }


    private function removeX(string $key, Repository &$repository, string $method): void
    {
        if (!$this->isPost()) {
            $_SESSION['messages'] = ['message' => 'Niepoprawne żądanie'];
            header('Location: /adminLogin');
            return;
        }

        if (!$this->securityComponent->updateAdminAuthCookie()) {
            http_response_code(401); //not authenticated
            return;
        }

        if (!$this->securityComponent->checkPermission($method)) {
            http_response_code(403); //not authorized
            return;
        }

        try {
            http_response_code(200);
            $repository->$method(intval($_POST[$key]));
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
        $nick = $this->securityComponent->getNick();
        if ($nick) {
            $user = $this->userRepository->getUser($nick);
            if ($user && $user->ID_User === intval($_POST['ID_User'])) {
                http_response_code(403); // block deleting own account for user
                return;
            }
        }

        $this->removeX('ID_User', $this->userRepository, 'removeUser');
    }


}
