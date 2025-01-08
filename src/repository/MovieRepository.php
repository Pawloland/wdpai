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
        $query = '
            SELECT 
                "title", "original_title", "duration", "description", 
                "poster", 
                a."language_name" as "language_name",
                b."language_name" as "dubbing_name",
                c."language_name" as "subtitles_name"
            FROM 
                "Movie" as m
            inner JOIN 
                "Language" as a ON a."ID_Language" = m."ID_Language"
            inner JOIN 
                "Language" as b ON b."ID_Language" = m."ID_Dubbing"
            inner JOIN 
                "Language" as c ON c."ID_Language" = m."ID_Subtitles"
            
            order by "title"
        ';

        $result = [];
        $movies = $this->getDB($query);
        foreach ($movies as $movie) {
            $result[] = new Movie(
                $movie["title"],
                $movie["original_title"],
                $movie["duration"],
                $movie["description"],
                $movie["language_name"],
                $movie["dubbing_name"],
                $movie["subtitles_name"],
                $movie["poster"]
            );
        }
        return $result;
    }


//    public function getAllMovies(): void
//    {
//        $stmt = $this->database->connect()->prepare('
//            SELECT * FROM "Movie"
//        ');
//        $stmt->execute();
//
//        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        foreach ($users as $user) {
//            echo implode(', ', $user) . "<br>\n";
//        }
//    }


    public function addMovie(Movie $movie): string
    {
        $new_movie = $this->getDB('
            INSERT INTO "Movie" ("title", "original_title", "duration", "description", "ID_Language", "ID_Dubbing", "ID_Subtitles")
            VALUES (?, ?, ?, ?, ?, ?, ?)
            RETURNING *
        ',
            $movie->title,
            $movie->original_title,
            $movie->duration,
            $movie->description,
            $movie->language,
            $movie->dubbing,
            $movie->subtitles
        );


        return $new_movie['poster'];

    }
}
