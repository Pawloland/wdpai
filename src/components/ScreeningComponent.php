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

    public function addScreening(int $ID_Movie, int $ID_Hall, int $ID_Screening_Type, string $start_time): bool
    {
        $screening = new Screening(
            ID_Movie: $ID_Movie,
            ID_Hall: $ID_Hall,
            ID_Screening_Type: $ID_Screening_Type,
            start_time: new DateTime($start_time, new DateTimeZone(Database::CLIENT_TIMEZONE))
        ); // assume it is in local time - Database::CLIENT_TIMEZONE

        try {
            $new_screening = $this->screeningRepository->addScreening($screening);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}