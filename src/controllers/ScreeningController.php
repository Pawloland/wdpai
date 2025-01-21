<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Screening.php';
require_once __DIR__ . '/../repository/ScreeningRepository.php';


class ScreeningController extends AppController
{
    private ScreeningRepository $screeningRepository;

    public function __construct()
    {
        parent::__construct();
        $this->screeningRepository = new ScreeningRepository();
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


        $screening = new Screening(
            ID_Movie: intval($_POST['ID_Movie']),
            ID_Hall: intval($_POST['ID_Hall']),
            ID_Screening_Type: intval($_POST['ID_Screening_Type']),
            start_time: new DateTime($_POST['start_time'], new DateTimeZone(Database::CLIENT_TIMEZONE))
        ); // assume it is in local time - Database::CLIENT_TIMEZONE

        try {
            $new_screening = $this->screeningRepository->addScreening($screening);
        } catch (Exception $e) {
            $_SESSION['messages'] = ['message' => 'Nie udało się dodać seansu'];
            header('Location: /admin_panel');
            return;
        }
        $_SESSION['messages'] = ['message' => 'Dodano seans'];
        header('Location: /admin_panel');
    }

//    public function deleteScreening(): void
//    {
//        if (!$this->isPost()) {
//            $this->render('deleteScreening');
//            return;
//        }
//
//        $screening = new Screening($_POST['date'], $_POST['time'], $_POST['room'], $_POST['movie']);
//
//        $this->screeningRepository->deleteScreening($screening);
//        $this->render('deleteScreening', ['message' => ['Screening deleted']]);
//    }


}
