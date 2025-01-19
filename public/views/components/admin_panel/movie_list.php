<?php
require_once 'src/repository/MovieRepository.php';
if (!isset($movieRepository)) {
    $movieRepository = new MovieRepository();
}
?>

<div class="list" id="movies_list">
    <p>Filmy</p>
    <form>
        <table>
            <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Tytuł
                </th>
                <th>
                    Długość
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($movieRepository->getAllMovies() as $movie): ?>
                <tr>
                    <td>
                        <?= $movie->ID_Movie; ?>
                    </td>
                    <td>
                        <?= $movie->title; ?>
                    </td>
                    <td>
                        <?= $movie->duration_string; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>
