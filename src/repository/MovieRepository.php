<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Movie.php';

class MovieRepository extends Repository
{
    //as of php 8.4 typed arrays aren't supported, so only doc block is used
    /**
     * @return Movie[]
     */
    public function getAllMovies(): array
    {
        return $this->getDBClassesArray(
            Movie::class,
            'SELECT * FROM "Movie" order by "title"'
        );
    }

    public function getAllMoviesKV(): array
    {
        $data = $this->getDBAssocArray(
            'SELECT "ID_Movie", "title" FROM "Movie" order by "title"'
        );

        $result = [];
        foreach ($data as $row) {
            $result[$row['ID_Movie']] = $row['title'];
        }
        return $result;
    }

    /**
     * @return Movie[]
     */
    public function getAllMoviesThatHaveScreeningsNotBeforeDateTime(DateTime $start = new DateTime('now', new DateTimeZone(Database::CLIENT_TIMEZONE))): array
    {
        return $this->getDBClassesArrayTZ(
            Movie::class,
            '
            SELECT *
            FROM "Movie" 
            WHERE "ID_Movie" IN (
                                    SELECT DISTINCT "ID_Movie" 
                                    FROM "Screening" 
                                    WHERE "start_time" >= ?
            )
            ORDER BY "title"',
            Database::CLIENT_TIMEZONE,
            $start->format('Y-m-d H:i:s')
        );
    }

    public function getMovieById(int $ID_Movie): Movie
    {
        return $this->getDBClass(
            Movie::class,
            'SELECT * FROM "Movie" WHERE "ID_Movie" = ?',
            $ID_Movie
        );
    }


    public function addMovie(Movie $movie): Movie
    {
        return $this->getDBClass(
            Movie::class,
            '
                INSERT INTO "Movie" ("title", "original_title", "duration", "description", "ID_Language", "ID_Dubbing", "ID_Subtitles")
                VALUES (?, ?, ?, ?, ?, ?, ?)
                RETURNING *
            ',
            $movie->title,
            $movie->original_title,
            $movie->duration_string,
            $movie->description,
            $movie->ID_Language,
            $movie->ID_Dubbing,
            $movie->ID_Subtitles
        );
    }


}
