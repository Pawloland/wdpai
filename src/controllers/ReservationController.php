<?php

require_once 'AppController.php';
require_once __DIR__ . '/../components/SecurityComponent.php';
require_once __DIR__ . '/../repository/MovieRepository.php';
require_once __DIR__ . '/../repository/ReservationRepository.php';
require_once __DIR__ . '/../repository/ClientRepository.php';
require_once __DIR__ . '/../repository/ScreeningRepository.php';

class ReservationController extends AppController
{

    private SecurityComponent $securityComponent;
    private MovieRepository $movieRepository;
    private ReservationRepository $reservationRepository;
    private ClientRepository $clientRepository;
    private ScreeningRepository $screeningRepository;

    public function __construct()
    {
        parent::__construct();
        $this->securityComponent = new SecurityComponent();
        $this->movieRepository = new MovieRepository();
        $this->reservationRepository = new ReservationRepository();
        $this->clientRepository = new ClientRepository();
        $this->screeningRepository = new ScreeningRepository();
    }

    public function select_place(): void
    {
        if (!$this->securityComponent->updateAuthCookie()) {
            $_SESSION['messages'] = ['message' => 'Najpierw się zaloguj'];
            header('Location: /login');
            return;
        } //redirect to login page before selecting place if no active session exists


        if ($this->isGet()) {
            try {
                if (!isset($_GET['ID_Movie'])) {
                    throw new Exception();
                }
                $movie = $this->movieRepository->getMovieById($_GET['ID_Movie']);
                $this->render('select_place', [
                    'messages' => [
                        'movie' => $movie
                    ]
                ]);
            } catch (Exception $e) {
                $_SESSION['messages'] = ['message' => 'Nie znaleziono filmu'];
                header('Location: /');
            }
            return;
        }
        $_SESSION['messages'] = ['message' => 'Niepoprawne żądanie'];
        header('Location: /');

    }

    public function getDiscount(): void
    {
        $discount = 0;
        if (!$this->securityComponent->updateAuthCookie()) {
            echo $discount;
            return;
        }
        if (isset($_POST['discount_code'])) {
            $discount = $this->reservationRepository->getDiscount($_POST['discount_code']);
        }
        echo $discount;
    }

    public function addReservation(): void
    {
        if (!$this->securityComponent->updateAuthCookie()) {
            $_SESSION['messages'] = ['message' => 'Najpierw się zaloguj'];
            header('Location: /login');
        }
        if ($this->isPost()) {
            $successes = [];
            $fails = [];
            foreach ($_POST['ID_Seat'] as $index => &$seat) {
                try {
                    $reservation = new Reservation(
                        ID_Seat: intval($seat),
                        ID_Screening: intval($_POST['ID_Screening']),
                        ID_Discount: $index === 0 ? $this->reservationRepository->getDiscountID($_POST['discount_name']) : null,
                        ID_Client: $this->clientRepository->getClient($this->securityComponent->getMail())->ID_Client,
                        vat_percentage: 23
                    );

                    $reservation = $this->reservationRepository->addReservation($reservation);
                    $successes[] = intval($seat);
                } catch (Exception $e) {
                    $fails[] = intval($seat);
                }
            }
            $resp['successes'] = $successes;
            $resp['fails'] = $fails;
            $resp['new_data'] = $this->screeningRepository->getScreeningsByMovieIdAssoc($_POST['ID_Movie'] ?? -1);
            echo json_encode($resp);
            return;
        }

        $_SESSION['messages'] = ['message' => 'Niepoprawne żądanie'];
        header('Location: /');
    }
}