<?php
require_once 'src/repository/UserRepository.php';
if (!isset($userRepository)) {
    $userRepository = new UserRepository();
}
?>

<div class="list" id="clients_list">
    <p>Konta administracyjne: </p>
    <form>
        <table>
            <thead>
            <tr>
                <th>
                    <label>ID</label>
                </th>
                <th>
                    <label>Typ</label>
                </th>
                <th>
                    <label>Imię</label>
                </th>
                <th>
                    <label>Nazwisko</label>
                </th>
                <th>
                    <label>Nick</label>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($userRepository->getAllUsersAssoc() as $user): ?>
                <tr>
                    <td>
                        <?= $user['ID_User']; ?>
                    </td>
                    <td>
                        <?= $user['type_name']; ?>
                    </td>
                    <td>
                        <?= $user['user_name']; ?>
                    </td>
                    <td>
                        <?= $user['user_surname']; ?>
                    </td>
                    <td>
                        <?= $user['nick']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>

