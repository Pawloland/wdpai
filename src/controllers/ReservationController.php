<?php

require_once 'AppController.php';
require_once __DIR__ . '/../components/SecurityComponent.php';
require_once __DIR__ . '/../repository/MovieRepository.php';
require_once __DIR__ . '/../repository/ReservationRepository.php';
require_once __DIR__ . '/../repository/ClientRepository.php';
require_once __DIR__ . '/../repository/ScreeningRepository.php';
require_once __DIR__ . '/../components/DiscountComponent.php';
require_once __DIR__ . '/../components/ReservationComponent.php';
require_once __DIR__ . '/../components/ScreeningComponent.php';

class ReservationController extends AppController
{

    private SecurityComponent $securityComponent;
//    private MovieRepository $movieRepository;
//    private ReservationRepository $reservationRepository;
//    private ClientRepository $clientRepository;
//    private ScreeningRepository $screeningRepository;
    private DiscountComponent $discountComponent;
    private ReservationComponent $reservationComponent;
    private  ScreeningComponent $screeningComponent;

    public function __construct()
    {
        parent::__construct();
        $this->securityComponent = new SecurityComponent();
//        $this->movieRepository = new MovieRepository();
//        $this->reservationRepository = new ReservationRepository();
//        $this->clientRepository = new ClientRepository();
//        $this->screeningRepository = new ScreeningRepository();
        $this->discountComponent = new DiscountComponent();
        $this->reservationComponent = new ReservationComponent();
        $this->screeningComponent = new ScreeningComponent();
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
                $ID_Movie = intval($_GET['ID_Movie']);
                $movie = new Movie();
                $data = $this->screeningComponent->getScreeningsForMovie($ID_Movie, $movie);
                if (empty($data['data'])) {
                    throw new Exception();
                }

                $this->render('select_place', [
                    'messages' => ['movie' => $movie],
                    'data' => $data
                ]);
            } catch (Exception $e) {
                $_SESSION['messages'] = ['message' => 'Nie znaleziono filmu lub seansów'];
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
            $discount = $this->discountComponent->getDiscount($_POST['discount_code']);
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
            echo $this->reservationComponent->addReservation($this->securityComponent->getMail());
            return;
        }

        $_SESSION['messages'] = ['message' => 'Niepoprawne żądanie'];
        header('Location: /');
    }
}