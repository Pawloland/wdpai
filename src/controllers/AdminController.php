<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/MovieRepository.php';
require_once __DIR__ . '/../repository/ReservationRepository.php';
require_once __DIR__ . '/../repository/ScreeningRepository.php';
require_once __DIR__ . '/../repository/ClientRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../components/SecurityComponent.php';
require_once __DIR__ . '/../components/MovieComponent.php';
require_once __DIR__ . '/../components/ScreeningComponent.php';

class AdminController extends AppController
{

    private MovieRepository $movieRepository;
    private ReservationRepository $reservationRepository;
    private ScreeningRepository $screeningRepository;
    private ClientRepository $clientRepository;
    private UserRepository $userRepository;
    private SecurityComponent $securityComponent;
    private MovieComponent $movieComponent;
    private ScreeningComponent $screeningComponent;

    public function __construct()
    {
        parent::__construct();
        $this->movieRepository = new MovieRepository();
        $this->reservationRepository = new ReservationRepository();
        $this->screeningRepository = new ScreeningRepository();
        $this->clientRepository = new ClientRepository();
        $this->userRepository = new UserRepository();
        $this->securityComponent = new SecurityComponent();
        $this->movieComponent = new MovieComponent();
        $this->screeningComponent = new ScreeningComponent();
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


    public function admin_panel(): void
    {
        if (!$this->securityComponent->updateAdminAuthCookie()) {
            $_SESSION['messages'] = ['message' => 'Najpierw się zaloguj'];
            header('Location: /adminLogin');
            return;
        } //redirect to login page before letting the user access the admin panel if no active session exists
        if (!$this->isGet()) {
            $_SESSION['messages'] = ['message' => 'Niepoprawne żądanie'];
            header('Location: /adminLogin');
            return;
        }
        $this->render('admin_panel');
    }

    public function upload(): void
    {
        if (!$this->securityComponent->updateAdminAuthCookie()) {
            $_SESSION['messages'] = ['message' => 'Najpierw się zaloguj'];
            header('Location: /adminLogin');
            return;
        }

        if (!$this->isPost()) {
            $_SESSION['messages'] = ['message' => 'Niepoprawne żądanie'];
            header('Location: /adminLogin');
            return;
        }
        $messages = [];
        $messages['defaults'] = $_POST;

        if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
            $messages['upload'] = 'Illegal file name';
            $_SESSION['messages'] = $messages;
            header('Location: /admin_panel');
            return;
        }

        $title = $_POST['title'];
        $original_title = $_POST['original_title'];
        $duration = $_POST['duration'];
        $description = substr(trim($_POST['description']), 0, 500);
        $language = $_POST['language'];
        $dubbing = $_POST['dubbing'];
        $subtitles = $_POST['subtitles'];

        //validate the image file to be of mimetype image using equivalent to file linux command
        //filesize is limited first by nginx and second by PHP, so we don't need to check it
        if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != 0) {
            $messages['upload'] = 'File too big';
            $_SESSION['messages'] = $messages;
            header('Location: /admin_panel');
            return;

        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES["image"]["tmp_name"]);
        finfo_close($finfo);
        if (!$mimeType) {
            $mimeType = mime_content_type($_FILES["image"]["tmp_name"]);
        }
        if (!$mimeType || !str_contains($mimeType, "image")) {
            $messages['upload'] = "File not an image";
            $_SESSION['messages'] = $messages;
            header('Location: /admin_panel');
            return;
        }


        $messages['upload'] = '';
        $result = $this->movieComponent->addMovie(
            $title,
            $original_title,
            $duration,
            $description,
            $language,
            $dubbing,
            $subtitles,
            $messages['upload']
        );

        if ($result) {
            unset($messages['defaults']);
        }

        $_SESSION['messages'] = $messages;
        header('Location: /admin_panel');
//        $this->render('admin_panel', ["messages" => $messages]);

    }

    public function addScreening(): void
    {
        if (!$this->isPost()) {
            header('Location: /admin_panel');
            return;
        }

        if (!isset($_POST['ID_Movie']) || !isset($_POST['ID_Hall']) || !isset($_POST['ID_Screening_Type']) || !isset($_POST['start_time'])) {
            $_SESSION['messages'] = ['message' => 'Niepoprawne żądanie'];
            header('Location: /admin_panel');
            return;
        }


        $result = $this->screeningComponent->addScreening(
            intval($_POST['ID_Movie']),
            intval($_POST['ID_Hall']),
            intval($_POST['ID_Screening_Type']),
            $_POST['start_time']
        );

        if (!$result) {
            $_SESSION['messages'] = ['message' => 'Nie udało się dodać seansu'];
            header('Location: /admin_panel');
            return;
        }
        $_SESSION['messages'] = ['message' => 'Dodano seans'];
        header('Location: /admin_panel');
    }

}
