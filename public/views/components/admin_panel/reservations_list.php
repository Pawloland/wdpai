<?php
require_once 'src/repository/ReservationRepository.php';
if (!isset($reservationRepository)) {
    $reservationRepository = new ReservationRepository();
}
$data = $reservationRepository->getAllReservationsNotBeforeDateTimeAssoc();
?>

<div class="list" id="reservations_list">
    <p>Rezerwacje:</p>
    <form>
        <table>
            <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Mail
                </th>
                <th>
                    ID Sali
                </th>
                <th>
                    ID Fotel
                </th>
                <th>
                    Rząd
                </th>
                <th>
                    Kolumna
                </th>
                <th>
                    Typ siedzenia
                </th>
                <th>
                    Tytuł
                </th>
                <th>
                    Typ seansu
                </th>
                <th>
                    Rozpoczęcie
                </th>
                <th></th>
                <th></th>
                <th>
                    Cena brutto
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $reservation): ?>
                <?php $datetime = new DateTime($reservation['start_time']); ?>
                <tr>
                    <td>
                        <?= $reservation['ID_Reservation']; ?>
                    </td>
                    <td>
                        <?= $reservation['mail']; ?>
                    </td>
                    <td>
                        <?= $reservation['ID_Hall']; ?>
                    </td>
                    <td>
                        <?= $reservation['ID_Seat']; ?>
                    </td>
                    <td>
                        <?= $reservation['row']; ?>
                    </td>
                    <td>
                        <?= $reservation['number']; ?>
                    </td>
                    <td>
                        <?= $reservation['seat_name']; ?>
                    </td>
                    <td>
                        <?= $reservation['title']; ?>
                    </td>
                    <td>
                        <?= $reservation['screening_name']; ?>
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
                        <?= $reservation['total_price_brutto']; ?> zł
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>

