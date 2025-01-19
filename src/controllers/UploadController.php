<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../repository/MovieRepository.php';
require_once __DIR__ . '/../../debug_utils.php';


class UploadController extends AppController
{

    private MovieRepository $movieRepository;

    public function __construct()
    {
        parent::__construct();
        $this->movieRepository = new MovieRepository();
    }

    public function admin_panel(): void
    {
        $this->render('admin_panel');
    }

    public function upload(): void
    {

        if (!$this->isPost()) {
            header('Location: /login');
            $this->render('login');
//            $this->userRepository->getAllUsers();
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


        $movie = new Movie(
            title: $title,
            original_title: $original_title,
            description: $description,
            ID_Language: $language,
            ID_Dubbing: $dubbing,
            ID_Subtitles: $subtitles
        );
        $movie->duration = $duration; // the set property hook will convert the string to DateTime object, but the constructor will not call property hooks
        // so we need to set the duration manually after the object is created with the default value of new DateTime('1979-01-01 00:00:00') in the constructor
        $movie = $this->movieRepository->addMovie($movie);

        //save the image to the server

        $target_file = "public/img/posters/" . $movie->poster;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $messages["upload"] = "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            unset($messages['defaults']);
        } else {
            $messages["upload"] = "Sorry, there was an error uploading your file.";
        }

        $_SESSION['messages'] = $messages;
        header('Location: /admin_panel');
//        $this->render('admin_panel', ["messages" => $messages]);

    }
}
