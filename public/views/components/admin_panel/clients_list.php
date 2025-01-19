<?php
require_once 'src/repository/ClientRepository.php';
if (!isset($clientRepository)) {
    $clientRepository = new ClientRepository();
}
?>

<div class="list" id="clients_list">
    <p>Klienci</p>
    <form>
        <table>
            <thead>
            <tr>
                <th>
                    <label>ID</label>
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
                <th>
                    <label>Mail</label>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($clientRepository->getAllClients() as $client): ?>
                <tr>
                    <td>
                        <?= $client->ID_Client; ?>
                    </td>
                    <td>
                        <?= $client->client_name; ?>
                    </td>
                    <td>
                        <?= $client->client_surname; ?>
                    </td>
                    <td>
                        <?= $client->nick; ?>
                    </td>
                    <td>
                        <?= $client->mail; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>

