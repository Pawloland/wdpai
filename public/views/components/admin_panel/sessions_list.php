<?php
require_once 'src/repository/SessionRepository.php';
$sessionRepository = new SessionRepository();
if (isset($show_clients) && $show_clients) {
    $data = $sessionRepository->getAllClientSessionsNoTokenAssoc();
    $info_header = "Sesje klientów:";
    $id = "client_sessions_list";
} else {
    $data = $sessionRepository->getAllUserSessionsNoTokenAssoc();
    $info_header = "Sesje pracowników:";
    $id = "user_sessions_list";
}
?>

<div class="list" id="<?= $id ?>">
    <p><?= $info_header ?></p>
    <form>
        <table>
            <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Nick
                </th>
                <th>
                    Data wygaśnięcia
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $i): ?>
                <?php $i = array_values($i); ?>
                <tr>
                    <td>
                        <?= $i[0]; ?>
                    </td>
                    <td>
                        <?= $i[1]; ?>
                    </td>
                    <td>
                        <?= $i[2]; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>

