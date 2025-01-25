<?php
require_once __DIR__ . '/../repository/MovieRepository.php';
require_once __DIR__ . '/../models/Movie.php';

class MovieComponent
{

    private MovieRepository $movieRepository;


    public function __construct()
    {
        $this->movieRepository = new MovieRepository();
    }

    public function addMovie(
        string $title,
        string $original_title,
        string $duration,
        string $description,
        string $language,
        string $dubbing,
        string $subtitles,
        string &$message = ''
    ): bool
    {
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
        $message = '';
        $target_file = "public/img/posters/" . $movie->poster;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $message = "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
//            unset($messages['defaults']);
            return true;
        } else {
            $message = "Sorry, there was an error uploading your file.";
            return false;
        }

    }


}