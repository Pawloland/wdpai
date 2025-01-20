<?php

require_once 'AppController.php';
require_once __DIR__ . '/../components/SecurityComponent.php';
require_once __DIR__ . '/../repository/MovieRepository.php';

class ReservationController extends AppController
{

    private SecurityComponent $securityComponent;
    private MovieRepository $movieRepository;

    public function __construct()
    {
        parent::__construct();
        $this->securityComponent = new SecurityComponent();
        $this->movieRepository = new MovieRepository();
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
}