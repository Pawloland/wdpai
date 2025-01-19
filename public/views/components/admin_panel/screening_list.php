<?php
require_once 'src/repository/ScreeningRepository.php';
if (!isset($screeningRepository)) {
    $screeningRepository = new ScreeningRepository();
}
?>

<div class="list" id="screenings_list">
    <p>Nadchodzące seanse:</p>
    <form>
        <table>
            <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Data
                </th>
                <th>

                </th>
                <th>
                    Godzina
                </th>
                <th>
                    Tytuł
                </th>
                <th>
                    Sala
                </th>
                <th>
                    Typ
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($screeningRepository->getAllScreeningsStartingNotBeforeDateTimeAssoc() as $screening): ?>
                <?php $datetime = new DateTime($screening['start_time']); ?>
                <tr>
                    <td>
                        <?= $screening['ID_Screening']; ?>
                    </td>
                    <td>
                        <?= $datetime->format("Y.m.d"); ?>
                    </td>
                    <td>
                        <?= $datetime->format("l"); ?>
                    </td>
                    <td>
                        <?= $datetime->format("H:i"); ?>
                    </td>
                    <td>
                        <?= $screening['title'] ?>
                    </td>
                    <td>
                        <?= $screening['ID_Hall']; ?>
                    </td>
                    <td>
                        <?= $screening['screening_name']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>

