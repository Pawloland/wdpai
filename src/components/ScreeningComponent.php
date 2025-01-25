<?php
require_once __DIR__ . '/../repository/ScreeningRepository.php';
require_once __DIR__ . '/../repository/MovieRepository.php';

class  ScreeningComponent
{
    private ScreeningRepository $screeningRepository;
    private MovieRepository $movieRepository;


    public function __construct()
    {
        $this->screeningRepository = new ScreeningRepository();
        $this->movieRepository = new MovieRepository();
    }

    public function getScreeningsForMovie(int $ID_Movie, Movie &$movie): array
    {
        $movie = $this->movieRepository->getMovieById($ID_Movie);

        return $this->screeningRepository->getScreeningsByMovieIdAssoc($movie->ID_Movie ?? -1);

    }
}