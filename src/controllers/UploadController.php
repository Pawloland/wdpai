<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../repository/MovieRepository.php';

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
            $this->render('login');
//            $this->userRepository->getAllUsers();
            return;
        }

        $title = $_POST['title'];
        $original_title = $_POST['original_title'];
        $duration = $_POST['duration'];
        $description = $_POST['description'];
        $language = $_POST['language'];
        $dubbing = $_POST['dubbing'];
        $subtitles = $_POST['subtitles'];
//        $image = $_POST['image'];


        $movie = new Movie($title, $original_title, $duration, $description, $language, $dubbing, $subtitles, '');
        $movie->poster = $this->movieRepository->addMovie($movie);

        //save the image to the server
        $target_file = "public/img/posters/" . $movie->poster;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }


        $this->render('admin_panel', ['messages' => ['Movie added!']]);

    }
}
